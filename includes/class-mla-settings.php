<?php
/**
 * Manages the plugin option settings and provides the settings page to edit them
 *
 * @package Media Library Assistant
 * @since 0.1
 */

/**
 * Class MLA (Media Library Assistant) Settings manages the plugin option settings
 * and provides the settings page to edit them.
 *
 * @package Media Library Assistant
 * @since 0.1
 */
class MLASettings {
	/**
	 * Provides a unique name for the settings page.
	 */
	const MLA_SETTINGS_SLUG = 'mla-settings-menu';
	
	/**
	 * $mla_options defines the database options and admin page areas for setting/updating them.
	 * Each option is defined by an array with the following elements:
	 *
	 * array key => HTML id/name attribute and option database key (OMIT MLA_OPTION_PREFIX)
	 *
	 * name => admin page label or heading text
	 * type => 'checkbox', 'header', 'radio', 'select', 'text', 'textarea'
	 * std => default value
	 * help => help text
	 * size => text size, default 40
	 * cols => textbox columns, default 90
	 * rows => textbox rows, default 5
	 * options => array of radio or select option values
	 */
	private static $mla_options = array (
		'taxonomies' =>
			array('name' => 'Pre-defined Taxonomies',
				'type' => 'header'),
		
		'attachment_category' =>
			array('name' => 'Attachment Categories',
				'type' => 'checkbox',
				'std' => 'checked',
				'help' => 'Check this option to add support for Attachment Categories.'),
		
		'attachment_tag' =>
			array('name' => 'Attachment Tags',
				'type' => 'checkbox',
				'std' => 'checked',
				'help' => 'Check this option to add support for Attachment Tags.'),
	
		/* Here are examples of the other option types
		'testvalues' =>
			array('name' => 'Test Values',
				'type' => 'header'),
	
		'radio' =>
			array('name' => 'Radio List',
				'type' => 'radio',
				'std' => 'No',
				'options' => array('No', 'Yes'),
				'help' => 'This is your help text.'),
	
		'select' =>
			array('name' => 'Dropdown list',
				'type' => 'select',
				'std' => 'Red',
				'options' => array('Red', 'Green', 'Blue'),
				'help' => 'This is your help text.'),
	
		'text' =>
			array('name' => 'Text Field',
				'type' => 'text',
				'std' => 'default text',
				'size' => 20,
				'help' => 'Enter the text...'),

		'textarea' =>
			array('name' => 'Text Area',
				'type' => 'textarea',
				'std' => 'default text area',
				'cols' => 60,
				'rows' => 4,
				'help' => 'Enter the text area...'),
		*/
	);
	
	/**
	 * Initialization function, similar to __construct()
	 *
	 * @since 0.1
	 */
	public static function initialize( ) {
		add_action( 'admin_menu', 'MLASettings::mla_admin_menu_action' );
	}
	
	/**
	 * Add settings page in the "Settings" section,
	 * add settings link in the Plugins section entry for MLA.
	 *
	 * @since 0.1
	 */
	public static function mla_admin_menu_action( ) {
		$hook = add_submenu_page( 'options-general.php', 'Media Library Assistant Settings', 'Media Library Assistant', 'manage_options', self::MLA_SETTINGS_SLUG, 'MLASettings::mla_render_settings_page' );
		
		add_filter( 'plugin_action_links', 'MLASettings::mla_add_plugin_settings_link', 10, 2 );
	}
	
	/**
	 * Add the "Settings" link to the MLA entry in the Plugins section
	 *
	 * @since 0.1
	 *
	 * @param	array 	array of links for the Plugin, e.g., "Activate"
	 * @param	string 	Directory and name of the plugin Index file
	 *
	 * @return	array	Updated array of links for the Plugin
	 */
	public static function mla_add_plugin_settings_link( $links, $file ) {
		if ( $file == 'media-library-assistant/index.php' ) {
			$settings_link = sprintf( '<a href="%s">%s</a>', admin_url( 'options-general.php?page=' . self::MLA_SETTINGS_SLUG ), 'Settings' );
			array_unshift( $links, $settings_link );
		}
		
		return $links;
	}
	
	/**
	 * Return the stored value or default value of a defined MLA option
	 *
	 * @since 0.1
	 *
	 * @param	string 	Name of the desired option
	 *
	 * @return	mixed	Value(s) for the option or false if the option is not a defined MLA option
	 */
	public static function mla_get_option( $option ) {
		if ( array_key_exists( $option, self::$mla_options ) ) {
			if ( array_key_exists( 'std', self::$mla_options[ $option ] ) )
				return get_option( MLA_OPTION_PREFIX . $option, self::$mla_options[ $option ]['std'] );
			else
				return get_option( MLA_OPTION_PREFIX . $option, false );
		}
		
		return false;
	}
	
	/**
	 * Add or update the stored value of a defined MLA option
	 *
	 * @since 0.1
	 *
	 * @param	string 	Name of the desired option
	 * @param	mixed 	New value for the desired option
	 *
	 * @return	boolean	True if the value was changed or false if the update failed
	 */
	public static function mla_update_option( $option, $newvalue ) {
		if ( array_key_exists( $option, self::$mla_options ) )
			return update_option( MLA_OPTION_PREFIX . $option, $newvalue );
		
		return false;
	}
	
	/**
	 * Delete the stored value of a defined MLA option
	 *
	 * @since 0.1
	 *
	 * @param	string 	Name of the desired option
	 *
	 * @return	boolean	True if the option was deleted, otherwise false
	 */
	public static function mla_delete_option( $option ) {
		if ( array_key_exists( $option, self::$mla_options ) ) {
			return delete_option( MLA_OPTION_PREFIX . $option );
		}
		
		return false;
	}
	
	/**
	 * Render the "Media Library Assistant" subpage in the Settings section
	 *
	 * @since 0.1
	 *
	 * @return	HTML markup for the settings subpage
	 */
	public static function mla_render_settings_page( ) {
		if ( !current_user_can( 'manage_options' ) ) {
			echo "Media Library Assistant - Error</h2>\r\n";
			wp_die( __( 'You do not have permission to manage plugin settings.' ) );
		}
		
		/*
		 * Load template array and initialize page-level values.
		 */
		$page_template_array = MLAData::mla_load_template( MLA_PLUGIN_PATH . 'tpls/admin-display-settings-page.tpl' );
		$page_values = array(
			 'messages' => '',
			'options_list' => '',
			'_wpnonce' => wp_nonce_field( MLA::MLA_ADMIN_NONCE, '_wpnonce', true, false ) 
		);
		
		/*
		 * Check for submit buttons to change or reset settings.
		 */
		if ( !empty( $_REQUEST['save'] ) ) {
			$page_content = self::_save_settings( $page_template_array );
		} elseif ( !empty( $_REQUEST['reset'] ) ) {
			$page_content = self::_reset_settings( $page_template_array );
		} else {
			$page_content = array(
				 'message' => '',
				'body' => '' 
			);
		}
		
		if ( !empty( $page_content['body'] ) ) {
			echo $page_content['body'];
			return;
		}
		
		/*
		 * $custom_fields documents the name and description of custom fields
		 */
		$custom_fields = array( 
			// array("name" => "field_name", "description" => "field description.")
		);
		
		/* 
		 * $shortcodes documents the name and description of theme shortcodes
		 */
		$shortcodes = array( 
			// array("name" => "shortcode", "description" => "This shortcode...")
		);
		
		
		$options_list = '';
		foreach ( self::$mla_options as $key => $value ) {
			switch ( $value['type'] ) {
				case 'checkbox':
					$option_values = array(
						'key' => MLA_OPTION_PREFIX . $key,
						'checked' => '',
						'value' => $value['name'],
						'help' => $value['help'] 
					);
					
					if ( 'checked' == self::mla_get_option( $key ) )
						$option_values['checked'] = 'checked="checked"';
					
					$options_list .= MLAData::mla_parse_template( $page_template_array['checkbox'], $option_values );
					break;
				case 'header':
					$option_values = array(
						'key' => MLA_OPTION_PREFIX . $key,
						'value' => $value['name'] 
					);
					
					$options_list .= MLAData::mla_parse_template( $page_template_array['header'], $option_values );
					break;
				case 'radio':
					$radio_options = '';
					foreach ( $value['options'] as $optid => $option ) {
						$option_values = array(
							'key' => MLA_OPTION_PREFIX . $key,
							'checked' => '',
							'value' => $option 
						);
						
						if ( $option == self::mla_get_option( $key ) )
							$option_values['checked'] = 'checked="checked"';
						
						$radio_options .= MLAData::mla_parse_template( $page_template_array['radio-option'], $option_values );
					}
					
					$option_values = array(
						'value' => $value['name'],
						'options' => $radio_options,
						'help' => $value['help'] 
					);
					
					$options_list .= MLAData::mla_parse_template( $page_template_array['radio'], $option_values );
					break;
				case 'select':
					$option_values = array(
						'key' => MLA_OPTION_PREFIX . $key,
						'value' => $value['name'],
						'options' => $radio_options,
						'help' => $value['help'] 
					);
					
					$select_options = '';
					foreach ( $value['options'] as $optid => $option ) {
						$option_values = array(
							'selected' => '',
							'value' => $option 
						);
						
						if ( $option == self::mla_get_option( $key ) )
							$option_values['selected'] = 'selected="selected"';
						
						$select_options .= MLAData::mla_parse_template( $page_template_array['select-option'], $option_values );
					}
					
					$option_values = array(
						'key' => MLA_OPTION_PREFIX . $key,
						'value' => $value['name'],
						'options' => $select_options,
						'help' => $value['help'] 
					);
					
					$options_list .= MLAData::mla_parse_template( $page_template_array['select'], $option_values );
					break;
				case 'text':
					$option_values = array(
						'key' => MLA_OPTION_PREFIX . $key,
						'value' => $value['name'],
						'options' => $select_options,
						'help' => $value['help'],
						'size' => '40',
						'text' => '' 
					);
					
					if ( !empty( $value['size'] ) )
						$option_values['size'] = $value['size'];
					
					$option_values['text'] = self::mla_get_option( $key );
					
					$options_list .= MLAData::mla_parse_template( $page_template_array['text'], $option_values );
					break;
				case 'textarea':
					$option_values = array(
						'key' => MLA_OPTION_PREFIX . $key,
						'value' => $value['name'],
						'options' => $select_options,
						'help' => $value['help'],
						'cols' => '90',
						'rows' => '5',
						'text' => '' 
					);
					
					if ( !empty( $value['cols'] ) )
						$option_values['cols'] = $value['cols'];
					
					if ( !empty( $value['rows'] ) )
						$option_values['rows'] = $value['rows'];
					
					$option_values['text'] = stripslashes( self::mla_get_option( $key ) );
					
					$options_list .= MLAData::mla_parse_template( $page_template_array['textarea'], $option_values );
					break;
				default:
					error_log( 'ERROR: mla_render_settings_page unknown type: ' . var_export( $value, true ), 0 );
			}
		}
		
		if ( !empty( $page_content['message'] ) )
			$page_values['messages'] = MLAData::mla_parse_template( $page_template_array['messages'], array(
				 'messages' => $page_content['message'] 
			) );
		
		$page_values['options_list'] = $options_list;
		echo MLAData::mla_parse_template( $page_template_array['page'], $page_values );
	} // mla_render_settings_page
	
	/**
	 * Save settings to the options table
 	 *
	 * @since 0.1
	 *
	 * @param	array 	HTML template(s) for the settings page
	 *
	 * @return	array	Message(s) reflecting the results of the operation.
	 */
	private static function _save_settings( $template_array ) {
		$message = '';
		
		foreach ( self::$mla_options as $key => $value ) {
			if ( isset( $_REQUEST[ MLA_OPTION_PREFIX . $key ] ) ) {
				switch ( $value['type'] ) {
					case 'checkbox':
						self::mla_update_option( $key, 'checked' );
						break;
					case 'header':
						break;
					case 'radio':
						self::mla_update_option( $key, $_REQUEST[ MLA_OPTION_PREFIX . $key ] );
						break;
					case 'select':
						self::mla_update_option( $key, $_REQUEST[ MLA_OPTION_PREFIX . $key ] );
						break;
					case 'text':
						self::mla_update_option( $key, trim( $_REQUEST[ MLA_OPTION_PREFIX . $key ] ) );
						break;
					case 'textarea':
						self::mla_update_option( $key, trim( $_REQUEST[ MLA_OPTION_PREFIX . $key ] ) );
						break;
					default:
						error_log( 'ERROR: _save_settings unknown type(1): ' . var_export( $value, true ), 0 );
				}
				
				$message .= '<br>update_option(' . $key . ')';
			} else {
				switch ( $value['type'] ) {
					case 'checkbox':
						self::mla_update_option( $key, 'unchecked' );
						break;
					case 'header':
						break;
					case 'radio':
						self::mla_delete_option( $key );
						break;
					case 'select':
						self::mla_delete_option( $key );
						break;
					case 'text':
						self::mla_delete_option( $key );
						break;
					case 'textarea':
						self::mla_delete_option( $key );
						break;
					default:
						error_log( 'ERROR: _save_settings unknown type(2): ' . var_export( $value, true ), 0 );
				}
				
				$message .= '<br>delete_option(' . $key . ')';
			}
		}
		
		$page_content = array(
			'message' => 'Settings saved.',
			'body' => '' 
		);
		
		/*
		 * Uncomment this for debugging.
		 * $page_content['message'] .= $message;
		 */
		return $page_content;
	} // _save_settings
	
	/**
	 * Delete saved settings, restoring default values
 	 *
	 * @since 0.1
	 *
	 * @param	array 	HTML template(s) for the settings page
	 *
	 * @return	array	Message(s) reflecting the results of the operation.
	 */
	private static function _reset_settings( $template_array ) {
		$message = '';
		
		foreach ( self::$mla_options as $key => $value ) {
			self::mla_delete_option( $key );
			$message .= '<br>delete_option(' . $key . ')';
		}
		
		$page_content = array(
			 'message' => 'Settings reset to default values.',
			'body' => '' 
		);

		/*
		 * Uncomment this for debugging.
		 * $page_content['message'] .= $message;
		 */
		return $page_content;
	} // _reset_settings
} // class MLASettings
?>