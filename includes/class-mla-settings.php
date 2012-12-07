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
	 * Provides a unique name for the ALT Text SQL VIEW
	 *
	 * @since 0.40
	 *
	 * @var	array
	 */
	public static $mla_alt_text_view = null;
	
	/**
	 * Provides a unique suffix for the ALT Text SQL VIEW
	 */
	const MLA_ALT_TEXT_VIEW_SUFFIX = 'alt_text_view';
	
	/**
	 * Provides a unique name for the settings page
	 */
	const MLA_SETTINGS_SLUG = 'mla-settings-menu';
	
	/**
	 * Provides a unique name for the current version option
	 */
	const MLA_VERSION_OPTION = 'current_version';
	
	/**
	 * $mla_options defines the database options and admin page areas for setting/updating them.
	 * Each option is defined by an array with the following elements:
	 *
	 * array key => HTML id/name attribute and option database key (OMIT MLA_OPTION_PREFIX)
	 *
	 * tab => Settings page tab id for the option
	 * name => admin page label or heading text
	 * type => 'checkbox', 'header', 'radio', 'select', 'text', 'textarea', 'custom', 'hidden'
	 * std => default value
	 * help => help text
	 * size => text size, default 40
	 * cols => textbox columns, default 90
	 * rows => textbox rows, default 5
	 * options => array of radio or select option values
	 * texts => array of radio or select option display texts
	 * render => rendering function for 'custom' options. Usage:
	 *     $options_list .= ['render']( 'render', $key, $value );
	 * update => update function for 'custom' options; returns nothing. Usage:
	 *     $message = ['update']( 'update', $key, $value, $_REQUEST );
	 * delete => delete function for 'custom' options; returns nothing. Usage:
	 *     $message = ['delete']( 'delete', $key, $value, $_REQUEST );
	 * reset => reset function for 'custom' options; returns nothing. Usage:
	 *     $message = ['reset']( 'reset', $key, $value, $_REQUEST );
	 */
	private static $mla_options = array (
		/*
		 * This option records the highest MLA version so-far installed
		 */
		self::MLA_VERSION_OPTION =>
			array('tab' => '',
				'type' => 'hidden', 
				'std' => '0'),
		
		/* 
		 * These checkboxes are no longer used;
		 * they are retained for the database version/update check
		 */
		'attachment_category' =>
			array('tab' => '',
				'name' => 'Attachment Categories',
				'type' => 'hidden', // checkbox',
				'std' => 'checked',
				'help' => 'Check this option to add support for Attachment Categories.'),
		
		'attachment_tag' =>
			array('tab' => '',
				'name' => 'Attachment Tags',
				'type' => 'hidden', // checkbox',
				'std' => 'checked',
				'help' => 'Check this option to add support for Attachment Tags.'),
	
		'where_used_heading' =>
			array('tab' => 'general',
				'name' => 'Where-used Reporting',
				'type' => 'header'),
		
		'exclude_revisions' =>
			array('tab' => 'general',
				'name' => 'Exclude Revisions',
				'type' => 'checkbox',
				'std' => 'checked',
				'help' => 'Check this option to exclude revisions from where-used reporting.'),
	
		'taxonomy_heading' =>
			array('tab' => 'general',
				'name' => 'Taxonomy Support',
				'type' => 'header'),
		
		'taxonomy_support' =>
			array('tab' => 'general',
				'help' => 'Check the "Support" box to add the taxonomy to the Assistant.<br>Check the "Inline Edit" box to display the taxonomy in the Quick Edit and Bulk Edit areas.<br>Use the "List Filter" option to select the taxonomy on which to filter the Assistant table listing.',
				'std' =>  array (
					'tax_support' => array (
				    	'attachment_category' => 'checked',
					    'attachment_tag' => 'checked',
					  ),
					'tax_quick_edit' => array (
						'attachment_category' => 'checked',
						'attachment_tag' => 'checked',
					),
					'tax_filter' => 'attachment_category'
					), 
				'type' => 'custom',
				'render' => '_taxonomy_handler',
				'update' => '_taxonomy_handler',
				'delete' => '_taxonomy_handler',
				'reset' => '_taxonomy_handler'),
	
		'orderby_heading' =>
			array('tab' => 'general',
				'name' => 'Default Table Listing Sort Order',
				'type' => 'header'),
		
		'default_orderby' =>
			array('tab' => 'general',
				'name' => 'Order By',
				'type' => 'select',
				'std' => 'title_name',
				'options' => array('title_name'),
				'texts' => array('Title/Name'),
				'help' => 'Select the column for the sort order of the Assistant table listing.'),
	
		'default_order' =>
			array('tab' => 'general',
				'name' => 'Order',
				'type' => 'radio',
				'std' => 'ASC',
				'options' => array('ASC', 'DESC'),
				'texts' => array('Ascending', 'Descending'),
				'help' => 'Choose the sort order.'),

		/*
		 * Managed by _get_style_templates and _put_style_templates
		 */
		'style_templates' =>
			array('tab' => '',
				'type' => 'hidden',
				'std' => array( )),
	
		/*
		 * Managed by _get_markup_templates and _put_markup_templates
		 */
		'markup_templates' =>
			array('tab' => '',
				'type' => 'hidden',
				'std' => array( )),
	
		/* Here are examples of the other option types
		'testvalues' =>
			array('name' => 'Test Values',
				'type' => 'header'),
	
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
	 *
	 * @return	void
	 */
	public static function initialize( ) {
		global $table_prefix;
		
		self::$mla_alt_text_view = $table_prefix . MLA_OPTION_PREFIX . self::MLA_ALT_TEXT_VIEW_SUFFIX;
		add_action( 'admin_menu', 'MLASettings::mla_admin_menu_action' );
		self::_version_upgrade();
		self::_create_alt_text_view();
		self::_load_templates();
	}
	
	/**
	 * Database and option update check, for installing new versions
	 *
	 * @since 0.30
	 *
	 * @return	void
	 */
	private static function _version_upgrade( ) {
		$current_version = self::mla_get_option( self::MLA_VERSION_OPTION );
		
		if ( ((float)'.30') > ((float)$current_version) ) {
			/*
			 * Convert attachment_category and _tag to taxonomy_support;
			 * change the default if either option is unchecked
			 */
			$category_option = self::mla_get_option( 'attachment_category' );
			$tag_option = self::mla_get_option( 'attachment_tag' );
			if ( ! ( ( 'checked' == $category_option ) && ( 'checked' == $tag_option ) ) ) {
				$tax_option = self::mla_get_option( 'taxonomy_support' );
				if ( 'checked' != $category_option ) {
					if ( isset( $tax_option['tax_support']['attachment_category'] ) )
						unset( $tax_option['tax_support']['attachment_category'] );
				}

				if ( 'checked' != $tag_option )  {
					if ( isset( $tax_option['tax_support']['attachment_tag'] ) )
						unset( $tax_option['tax_support']['attachment_tag'] );
				}

				self::_taxonomy_handler( 'update', 'taxonomy_support', self::$mla_options['taxonomy_support'], $tax_option );
			} // one or both options unchecked

		self::mla_delete_option( 'attachment_category' );
		self::mla_delete_option( 'attachment_tag' );
		} // version is less than .30
		
		self::mla_update_option( self::MLA_VERSION_OPTION, MLA::CURRENT_MLA_VERSION );
	}
	
	/**
	 * Add a view to the database to support sorting the listing on 'ALT Text'
	 *
	 * This function is called on each plugin invocation because the plugin upgrade process
	 * does not call the activation hook.
	 *
	 * @since 0.50
	 *
	 * @return	void
	 */
	private static function _create_alt_text_view( ) {
		global $wpdb, $table_prefix;
		
		$view_name = $table_prefix . MLA_OPTION_PREFIX . self::MLA_ALT_TEXT_VIEW_SUFFIX;
		$table_name = $table_prefix . 'postmeta';
		$result = $wpdb->query(
				"
				SHOW TABLES LIKE '{$view_name}'
				"
		);

		if ( 0 == $result ) {
			$result = $wpdb->query(
					"
					CREATE OR REPLACE VIEW {$view_name} AS
					SELECT post_id, meta_value
					FROM {$table_name}
					WHERE {$table_name}.meta_key = '_wp_attachment_image_alt'
					"
			);
		}
	}
	
	/**
	 * Perform one-time actions on plugin activation
	 *
	 * Adds a view to the database to support sorting the listing on 'ALT Text'.
	 *
	 * @since 0.40
	 *
	 * @return	void
	 */
	public static function mla_activation_hook( ) {
		self::_create_alt_text_view();
	}
	
	/**
	 * Perform one-time actions on plugin deactivation
	 *
	 * Removes a view from the database that supports sorting the listing on 'ALT Text'.
	 *
	 * @since 0.40
	 *
	 * @return	void
	 */
	public static function mla_deactivation_hook( ) {
		global $wpdb, $table_prefix;
		
		$view_name = $table_prefix . MLA_OPTION_PREFIX . self::MLA_ALT_TEXT_VIEW_SUFFIX;
		$result = $wpdb->query(
				"
				SHOW TABLES LIKE '{$view_name}'
				"
		);

		if ( $result) {		
			$result = $wpdb->query(
					"
					DROP VIEW {$view_name}
					"
			);
		}
	}
	
	/**
	 * Add settings page in the "Settings" section,
	 * add settings link in the Plugins section entry for MLA.
	 *
	 * @since 0.1
	 *
	 * @return	void
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
	 * Style and Markup templates
	 *
	 * @since 0.80
	 *
	 * @var	array
	 */
	private static $mla_template_array = null;
	
	/**
	 * Load style and markup templates to $mla_templates
	 *
	 * @since 0.80
	 *
	 * @return	void
	 */
	private static function _load_templates() {
		self::$mla_template_array = MLAData::mla_load_template( MLA_PLUGIN_PATH . 'tpls/mla-gallery-templates.tpl' );

		/* 	
		 * Load the default templates
		 */
		if( is_null( self::$mla_template_array ) ) {
			self::$mla_debug_messages .= '<p><strong>_load_templates()</strong> error loading tpls/mla-gallery-templates.tpl';
			return;
		}
		elseif( !self::$mla_template_array ) {
			self::$mla_debug_messages .= '<p><strong>_load_templates()</strong>tpls/mla-gallery-templates.tpl not found';
			$mla_template_array = null;
			return;
		}

		/*
		 * Add user-defined Style and Markup templates
		 */
		$templates = self::mla_get_option( 'style_templates' );
		if ( is_array(	$templates ) ) {
			foreach ( $templates as $name => $value ) {
				self::$mla_template_array[ $name . '-style' ] = $value;
			} // foreach $templates
		} // is_array

		$templates = self::mla_get_option( 'markup_templates' );
		if ( is_array(	$templates ) ) {
			foreach ( $templates as $name => $value ) {
				self::$mla_template_array[ $name . '-open-markup' ] = $value['open'];
				self::$mla_template_array[ $name . '-row-open-markup' ] = $value['row-open'];
				self::$mla_template_array[ $name . '-item-markup' ] = $value['item'];
				self::$mla_template_array[ $name . '-row-close-markup' ] = $value['row-close'];
				self::$mla_template_array[ $name . '-close-markup' ] = $value['close'];
			} // foreach $templates
		} // is_array
	}

	/**
	 * Fetch style or markup template from $mla_templates
	 *
	 * @since 0.80
	 *
	 * @param	string	Template name
	 * @param	string	Template type; 'style' (default) or 'markup'
	 *
	 * @return	string|boolean|null	requested template, false if not found or null if no templates
	 */
	public static function mla_fetch_gallery_template( $key, $type = 'style' ) {
		if ( ! is_array( self::$mla_template_array ) ) {
			self::$mla_debug_messages .= '<p><strong>_fetch_template()</strong> no templates exist';
			return null;
		}
		
		$array_key = $key . '-' . $type;
		if ( array_key_exists( $array_key, self::$mla_template_array ) )
			return self::$mla_template_array[ $array_key ];
		else {
			self::$mla_debug_messages .= "<p><strong>_fetch_template( {$key}, {$type} )</strong> not found";
			return false;
		}
	}

	/**
	 * Get ALL style templates from $mla_templates, including 'default'
	 *
	 * @since 0.80
	 *
	 * @return	array|null	name => value for all style templates or null if no templates
	 */
	public static function _get_style_templates() {
		if ( ! is_array( self::$mla_template_array ) ) {
			self::$mla_debug_messages .= '<p><strong>_fetch_template()</strong> no templates exist';
			return null;
		}
		
		$templates = array( );
		foreach ( self::$mla_template_array as $key => $value ) {
				$tail = strrpos( $key, '-style' );
				if ( ! ( false === $tail ) ) {
					$name = substr( $key, 0, $tail );
					$templates[ $name ] = $value;
				}
		} // foreach
		
		return $templates;
	}

	/**
	 * Put user-defined style templates to $mla_templates and database
	 *
	 * @since 0.80
	 *
	 * @param	array	name => value for all user-defined style templates
	 * @return	boolean	true if success, false if failure
	 */
	public static function _put_style_templates( $templates ) {
		if ( self::mla_update_option( 'style_templates', $templates ) ) {
			self::_load_templates();
			return true;
		}
		
		return false;
	}

	/**
	 * Get ALL markup templates from $mla_templates, including 'default'
	 *
	 * @since 0.80
	 *
	 * @return	array|null	name => value for all markup templates or null if no templates
	 */
	public static function _get_markup_templates() {
		if ( ! is_array( self::$mla_template_array ) ) {
			self::$mla_debug_messages .= '<p><strong>_fetch_template()</strong> no templates exist';
			return null;
		}
		
		$templates = array( );
		foreach ( self::$mla_template_array as $key => $value ) {
				$tail = strrpos( $key, '-row-open-markup' );
				if ( ! ( false === $tail ) ) {
					$name = substr( $key, 0, $tail );
					$templates[ $name ]['row-open'] = $value;
					continue;
				}
					
				$tail = strrpos( $key, '-open-markup' );
				if ( ! ( false === $tail ) ) {
					$name = substr( $key, 0, $tail );
					$templates[ $name ]['open'] = $value;
					continue;
				}
					
				$tail = strrpos( $key, '-item-markup' );
				if ( ! ( false === $tail ) ) {
					$name = substr( $key, 0, $tail );
					$templates[ $name ]['item'] = $value;
					continue;
				}
					
				$tail = strrpos( $key, '-row-close-markup' );
				if ( ! ( false === $tail ) ) {
					$name = substr( $key, 0, $tail );
					$templates[ $name ]['row-close'] = $value;
					continue;
				}
					
				$tail = strrpos( $key, '-close-markup' );
				if ( ! ( false === $tail ) ) {
					$name = substr( $key, 0, $tail );
					$templates[ $name ]['close'] = $value;
				}
		} // foreach
		
		return $templates;
	}

	/**
	 * Put user-defined markup templates to $mla_templates and database
	 *
	 * @since 0.80
	 *
	 * @param	array	name => value for all user-defined markup templates
	 * @return	boolean	true if success, false if failure
	 */
	public static function _put_markup_templates( $templates ) {
		if ( self::mla_update_option( 'markup_templates', $templates ) ) {
			self::_load_templates();
			return true;
		}
		
		return false;
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
	 * Update or delete a single MLA option value
	 *
	 * @since 0.80
	 * @uses $_REQUEST
 	 *
	 * @param	string	HTML id/name attribute and option database key (OMIT MLA_OPTION_PREFIX)
	 * @param	array	Option parameters, e.g., 'type', 'std'
	 *
	 * @return	string	HTML markup for the option's table row
	 */
	private static function _update_option_row( $key, $value ) {
		if ( isset( $_REQUEST[ MLA_OPTION_PREFIX . $key ] ) ) {
			$message = '<br>update_option(' . $key . ")\r\n";
			switch ( $value['type'] ) {
				case 'checkbox':
					self::mla_update_option( $key, 'checked' );
					break;
				case 'header':
					$message = '';
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
				case 'custom':
					$message = self::$value['update']( 'update', $key, $value, $_REQUEST );
					break;
				case 'hidden':
					break;
				default:
					error_log( 'ERROR: _save_settings unknown type(1): ' . var_export( $value, true ), 0 );
			} // $value['type']
		}  // isset $key
		else {
			$message = '<br>delete_option(' . $key . ')';
			switch ( $value['type'] ) {
				case 'checkbox':
					self::mla_update_option( $key, 'unchecked' );
					break;
				case 'header':
					$message = '';
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
				case 'custom':
					$message = self::$value['delete']( 'delete', $key, $value, $_REQUEST );
					break;
				case 'hidden':
					break;
				default:
					error_log( 'ERROR: _save_settings unknown type(2): ' . var_export( $value, true ), 0 );
			} // $value['type']
		}  // ! isset $key
			
		return $message;
	}
	
	/**
	 * Compose the table row for a single MLA option
	 *
	 * @since 0.80
	 * @uses $page_template_array contains option and option-item templates
 	 *
	 * @param	string	HTML id/name attribute and option database key (OMIT MLA_OPTION_PREFIX)
	 * @param	array	Option parameters, e.g., 'type', 'std'
	 *
	 * @return	string	HTML markup for the option's table row
	 */
	private static function _compose_option_row( $key, $value ) {
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
				
				return MLAData::mla_parse_template( self::$page_template_array['checkbox'], $option_values );
			case 'header':
				$option_values = array(
					'key' => MLA_OPTION_PREFIX . $key,
					'value' => $value['name'] 
				);
				
				return MLAData::mla_parse_template( self::$page_template_array['header'], $option_values );
			case 'radio':
				$radio_options = '';
				foreach ( $value['options'] as $optid => $option ) {
					$option_values = array(
						'key' => MLA_OPTION_PREFIX . $key,
						'option' => $option,
						'checked' => '',
						'value' => $value['texts'][$optid] 
					);
					
					if ( $option == self::mla_get_option( $key ) )
						$option_values['checked'] = 'checked="checked"';
					
					$radio_options .= MLAData::mla_parse_template( self::$page_template_array['radio-option'], $option_values );
				}
				
				$option_values = array(
					'value' => $value['name'],
					'options' => $radio_options,
					'help' => $value['help'] 
				);
				
				return MLAData::mla_parse_template( self::$page_template_array['radio'], $option_values );
			case 'select':
				$select_options = '';
				foreach ( $value['options'] as $optid => $option ) {
					$option_values = array(
						'selected' => '',
						'value' => $option,
						'text' => $value['texts'][$optid]
					);
					
					if ( $option == self::mla_get_option( $key ) )
						$option_values['selected'] = 'selected="selected"';
					
					$select_options .= MLAData::mla_parse_template( self::$page_template_array['select-option'], $option_values );
				}
				
				$option_values = array(
					'key' => MLA_OPTION_PREFIX . $key,
					'value' => $value['name'],
					'options' => $select_options,
					'help' => $value['help'] 
				);
				
				return MLAData::mla_parse_template( self::$page_template_array['select'], $option_values );
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
				
				return MLAData::mla_parse_template( self::$page_template_array['text'], $option_values );
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
				
				return MLAData::mla_parse_template( self::$page_template_array['textarea'], $option_values );
			case 'custom':
				if ( isset( $value['render'] ) )
					return self::$value['render']( 'render', $key, $value );

				break;
			case 'hidden':
				break;
			default:
				error_log( 'ERROR: mla_render_settings_page unknown type: ' . var_export( $value, true ), 0 );
		} //switch
		
		return '';
	}
	
	/**
	 * Template file for the Settings page(s) and parts
	 *
	 * This array contains all of the template parts for the Settings page(s). The array is built once
	 * each page load and cached for subsequent use.
	 *
	 * @since 0.80
	 *
	 * @var	array
	 */
	private static $page_template_array = null;

	/**
	 * Definitions for Settings page tab ids, titles and handlers
	 * Each tab is defined by an array with the following elements:
	 *
	 * array key => HTML id/name attribute and option database key (OMIT MLA_OPTION_PREFIX)
	 *
	 * title => tab label / heading text
	 * render => rendering function for tab messages and content. Usage:
	 *     $tab_content = ['render']( );
	 *
	 * @since 0.80
	 *
	 * @var	array
	 */
	private static $mla_tablist = array(
		'general' => array( 'title' => 'General', 'render' => '_compose_general_tab' ),
		'mla-gallery' => array( 'title' => 'MLA Gallery', 'render' => '_compose_mla_gallery_tab' ),
		'documentation' => array( 'title' => 'Documentation', 'render' => '_compose_documentation_tab' )
	);

	/**
	 * Compose the navigation tabs for the Settings subpage
	 *
	 * @since 0.80
	 * @uses $page_template_array contains tablist and tablist-item templates
 	 *
	 * @param	string	Optional data-tab-id value for the active tab, default 'general'
	 *
	 * @return	string	HTML markup for the Settings subpage navigation tabs
	 */
	private static function _compose_settings_tabs( $active_tab = 'general' ) {
		$tablist_item = self::$page_template_array['tablist-item'];
		$tabs = '';
		foreach ( self::$mla_tablist as $key => $item ) {
			$item_values = array(
				'data-tab-id' => $key,
				'nav-tab-active' => ( $active_tab == $key ) ? 'nav-tab-active' : '',
				'settings-page' => self::MLA_SETTINGS_SLUG,
				'title' => $item['title']
			);
			
			$tabs .= MLAData::mla_parse_template( $tablist_item, $item_values );
		} // foreach $item
		
		$tablist_values = array( 'tablist' => $tabs );
		return MLAData::mla_parse_template( self::$page_template_array['tablist'], $tablist_values );
	}
	
	/**
	 * Compose the General tab content for the Settings subpage
	 *
	 * @since 0.80
	 * @uses $page_template_array contains tab content template(s)
 	 *
	 * @return	array	'message' => status/error messages, 'body' => tab content
	 */
	private static function _compose_general_tab( ) {
		/*
		 * Check for submit buttons to change or reset settings.
		 * Initialize page messages and content.
		 */
		if ( !empty( $_REQUEST['mla-general-options-save'] ) ) {
			check_admin_referer( MLA::MLA_ADMIN_NONCE, '_wpnonce' );
			$page_content = self::_save_general_settings( );
		} elseif ( !empty( $_REQUEST['mla-general-options-reset'] ) ) {
			check_admin_referer( MLA::MLA_ADMIN_NONCE, '_wpnonce' );
			$page_content = self::_reset_general_settings( );
		} else {
			$page_content = array(
				 'message' => '',
				'body' => '' 
			);
		}
		
		if ( !empty( $page_content['body'] ) ) {
			return $page_content;
		}
		
		$page_values = array(
			'shortcode_list' => '',
			'options_list' => '',
			'_wpnonce' => wp_nonce_field( MLA::MLA_ADMIN_NONCE, '_wpnonce', true, false ),
			'_wp_http_referer' => wp_referer_field( false )
		);
		
		/*
		 * $custom_fields documents the name and description of custom fields
		 */
		$custom_fields = array( 
			// array("name" => "field_name", "description" => "field description.")
		);
		
		/* 
		 * $shortcodes documents the name and description of plugin shortcodes
		 */
		$shortcodes = array( 
			// array("name" => "shortcode", "description" => "This shortcode...")
			array( 'name' => 'mla_attachment_list', 'description' => 'renders a complete list of all attachments and references to them.' ),
			array( 'name' => 'mla_gallery', 'description' => 'enhanced version of the WordPress [gallery] shortcode. For complete documentation <a href="?page=' . self::MLA_SETTINGS_SLUG . '&amp;mla_tab=documentation">click here</a>.' )
		);
		
		$shortcode_list = '';
		foreach ( $shortcodes as $shortcode ) {
			$shortcode_values = array ( 'name' => $shortcode['name'], 'description' => $shortcode['description'] );
			$shortcode_list .= MLAData::mla_parse_template( self::$page_template_array['shortcode-item'], $shortcode_values );
		}
		
		if ( ! empty( $shortcode_list ) ) {
			$shortcode_values = array ( 'shortcode_list' => $shortcode_list );
			$page_values['shortcode_list'] = MLAData::mla_parse_template( self::$page_template_array['shortcode-list'], $shortcode_values );
		}
		
		/*
		 * Fill in the current list of sortable columns
		 */
		$default_orderby = MLA_List_Table::mla_get_sortable_columns( );
		foreach ($default_orderby as $key => $value ) {
			self::$mla_options['default_orderby']['options'][] = $value[0];
			self::$mla_options['default_orderby']['texts'][] = $value[1];
		}
		
		$options_list = '';
		foreach ( self::$mla_options as $key => $value ) {
			if ( 'general' == $value['tab'] )
				$options_list .= self::_compose_option_row( $key, $value );
		}
		
		$page_values['options_list'] = $options_list;
		$page_content['body'] = MLAData::mla_parse_template( self::$page_template_array['general-tab'], $page_values );
		return $page_content;
	}
	
	/**
	 * Compose the MLA Gallery tab content for the Settings subpage
	 *
	 * @since 0.80
	 * @uses $page_template_array contains tab content template(s)
 	 *
	 * @return	array	'message' => status/error messages, 'body' => tab content
	 */
	private static function _compose_mla_gallery_tab( ) {
		/*
		 * Check for submit buttons to change or reset settings.
		 * Initialize page messages and content.
		 */
		if ( !empty( $_REQUEST['mla-gallery-options-save'] ) ) {
			check_admin_referer( MLA::MLA_ADMIN_NONCE, '_wpnonce' );
			$page_content = self::_save_gallery_settings( );
		} else {
			$page_content = array(
				 'message' => '',
				'body' => '' 
			);
		}
		
		if ( !empty( $page_content['body'] ) ) {
			return $page_content;
		}
		
		$page_values = array(
			'options_list' => '',
			'style_options_list' => '',
			'markup_options_list' => '',
			'_wpnonce' => wp_nonce_field( MLA::MLA_ADMIN_NONCE, '_wpnonce', true, false ),
			'_wp_http_referer' => wp_referer_field( false )
		);
		
		/*
		 * Start with any page-level options
		 */
		$options_list = '';
		foreach ( self::$mla_options as $key => $value ) {
			if ( 'mla-gallery' == $value['tab'] )
				$options_list .= self::_compose_option_row( $key, $value );
		}
		
		$page_values['options_list'] = $options_list;

		/*
		 * Add style templates; default goes first
		 */
		$style_options_list = '';
		$templates = self::_get_style_templates();
		
		$name = 'default';
		$value =$templates['default'];
		if ( ! empty( $value ) ) {
			$template_values = array (
				'help' => 'The default template cannot be altered or deleted, but you can copy the styles.'
			);
			$control_cells = MLAData::mla_parse_template( self::$page_template_array['mla-gallery-default'], $template_values );
			
			$template_values = array (
				'readonly' => 'readonly="readonly"',
				'name_name' => 'mla_style_templates_name[default]',
				'name_id' => 'mla_style_templates_name_default',
				'name_text' => 'default',
				'control_cells' => $control_cells,
				'value_name' => 'mla_style_templates_value[default]',
				'value_id' => 'mla_style_templates_value_default',
				'value_text' => esc_textarea( $value ),
				'value_help' => 'List of substitution parameters, e.g., [+selector+], on Documentation tab.'
			);

			$style_options_list .= MLAData::mla_parse_template( self::$page_template_array['mla-gallery-style'], $template_values );
		} // $value
		
		foreach ( $templates as $name => $value ) {
			$slug = sanitize_title( $name );

			if ( 'default' == $name )
				continue; // already handled above
				
			$template_values = array (
				'name' => 'mla_style_templates_delete[' . $slug . ']',
				'id' => 'mla_style_templates_delete_' . $slug,
				'value' => 'Delete this template',
				'help' => 'Check the box to delete this template when you press Update at the bottom of the page.'
			);
			$control_cells = MLAData::mla_parse_template( self::$page_template_array['mla-gallery-delete'], $template_values );
			
			$template_values = array (
				'readonly' => '',
				'name_name' => 'mla_style_templates_name[' . $slug . ']',
				'name_id' => 'mla_style_templates_name_' . $slug,
				'name_text' => $slug,
				'control_cells' => $control_cells,
				'value_name' => 'mla_style_templates_value[' . $slug . ']',
				'value_id' => 'mla_style_templates_value_' . $slug,
				'value_text' => esc_textarea( $value ),
				'value_help' => 'List of substitution parameters, e.g., [+selector+], on Documentation tab.'
			);

			$style_options_list .= MLAData::mla_parse_template( self::$page_template_array['mla-gallery-style'], $template_values );
		} // foreach $templates
		
		/*
		 * Add blank style template for additions
		 */
		if ( ! empty( $value ) ) {
			$template_values = array (
				'help' => 'Fill in a name and styles to add a new template.'
			);
			$control_cells = MLAData::mla_parse_template( self::$page_template_array['mla-gallery-default'], $template_values );
			
			$template_values = array (
				'readonly' => '',
				'name_name' => 'mla_style_templates_name[blank]',
				'name_id' => 'mla_style_templates_name_blank',
				'name_text' => '',
				'control_cells' => $control_cells,
				'value_name' => 'mla_style_templates_value[blank]',
				'value_id' => 'mla_style_templates_value_blank',
				'value_text' => '',
				'value_help' => 'List of substitution parameters, e.g., [+selector+], on Documentation tab.'
			);

			$style_options_list .= MLAData::mla_parse_template( self::$page_template_array['mla-gallery-style'], $template_values );
		} // $value
		
		$page_values['style_options_list'] = $style_options_list;
		
		/*
		 * Add markup templates; default goes first
		 */
		$markup_options_list = '';
		$templates = self::_get_markup_templates();
		
		$name = 'default';
		$value =$templates['default'];
		if ( ! empty( $value ) ) {
			$template_values = array (
				'help' => 'The default template cannot be altered or deleted, but you can copy the markup.'
			);
			$control_cells = MLAData::mla_parse_template( self::$page_template_array['mla-gallery-default'], $template_values );
			
			$template_values = array (
				'readonly' => 'readonly="readonly"',
				'name_name' => 'mla_markup_templates_name[default]',
				'name_id' => 'mla_markup_templates_name_default',
				'name_text' => 'default',
				'control_cells' => $control_cells,

				'open_name' => 'mla_markup_templates_open[default]',
				'open_id' => 'mla_markup_templates_open_default',
				'open_text' => esc_textarea( $value['open'] ),
				'open_help' => 'Markup for the beginning of the gallery. List of parameters, e.g., [+selector+], on Documentation tab.',

				'row_open_name' => 'mla_markup_templates_row_open[default]',
				'row_open_id' => 'mla_markup_templates_row_open_default',
				'row_open_text' => esc_textarea( $value['row-open'] ),
				'row_open_help' => 'Markup for the beginning of each row in the gallery.',

				'item_name' => 'mla_markup_templates_item[default]',
				'item_id' => 'mla_markup_templates_item_default',
				'item_text' => esc_textarea( $value['item'] ),
				'item_help' => 'Markup for each item/cell of the gallery.',

				'row_close_name' => 'mla_markup_templates_row_close[default]',
				'row_close_id' => 'mla_markup_templates_row_close_default',
				'row_close_text' => esc_textarea( $value['row-close'] ),
				'row_close_help' => 'Markup for the end of each row in the gallery.',

				'close_name' => 'mla_markup_templates_close[default]',
				'close_id' => 'mla_markup_templates_close_default',
				'close_text' => esc_textarea( $value['close'] ),
				'close_help' => 'Markup for the end of the gallery.'
			);

			$markup_options_list .= MLAData::mla_parse_template( self::$page_template_array['mla-gallery-markup'], $template_values );
		} // $value
		
		foreach ( $templates as $name => $value ) {
			$slug = sanitize_title( $name );

			if ( 'default' == $name )
				continue; // already handled above
				
			$template_values = array (
				'name' => 'mla_markup_templates_delete[' . $slug . ']',
				'id' => 'mla_markup_templates_delete_' . $slug,
				'value' => 'Delete this template',
				'help' => 'Check the box to delete this template when you press Update at the bottom of the page.'
			);
			$control_cells = MLAData::mla_parse_template( self::$page_template_array['mla-gallery-delete'], $template_values );
			
			$template_values = array (
				'readonly' => '',
				'name_name' => 'mla_markup_templates_name[' . $slug . ']',
				'name_id' => 'mla_markup_templates_name_' . $slug,
				'name_text' => $slug,
				'control_cells' => $control_cells,

				'open_name' => 'mla_markup_templates_open[' . $slug . ']',
				'open_id' => 'mla_markup_templates_open_' . $slug,
				'open_text' => esc_textarea( $value['open'] ),
				'open_help' => 'Markup for the beginning of the gallery. List of parameters, e.g., [+selector+], on Documentation tab.',

				'row_open_name' => 'mla_markup_templates_row_open[' . $slug . ']',
				'row_open_id' => 'mla_markup_templates_row_open_' . $slug,
				'row_open_text' => esc_textarea( $value['row-open'] ),
				'row_open_help' => 'Markup for the beginning of each row.',

				'item_name' => 'mla_markup_templates_item[' . $slug . ']',
				'item_id' => 'mla_markup_templates_item_' . $slug,
				'item_text' => esc_textarea( $value['item'] ),
				'item_help' => 'Markup for each item/cell.',

				'row_close_name' => 'mla_markup_templates_row_close[' . $slug . ']',
				'row_close_id' => 'mla_markup_templates_row_close_' . $slug,
				'row_close_text' => esc_textarea( $value['row-close'] ),
				'row_close_help' => 'Markup for the end of each row.',

				'close_name' => 'mla_markup_templates_close[' . $slug . ']',
				'close_id' => 'mla_markup_templates_close_' . $slug,
				'close_text' => esc_textarea( $value['close'] ),
				'close_help' => 'Markup for the end of the gallery.'
			);

			$markup_options_list .= MLAData::mla_parse_template( self::$page_template_array['mla-gallery-markup'], $template_values );
		} // foreach $templates
		
		/*
		 * Add blank markup template for additions
		 */
		if ( ! empty( $value ) ) {
			$template_values = array (
				'help' => 'Fill in a name and markup to add a new template.'
			);
			$control_cells = MLAData::mla_parse_template( self::$page_template_array['mla-gallery-default'], $template_values );
			
			$template_values = array (
				'readonly' => '',
				'name_name' => 'mla_markup_templates_name[blank]',
				'name_id' => 'mla_markup_templates_name_blank',
				'name_text' => '',
				'control_cells' => $control_cells,

				'open_name' => 'mla_markup_templates_open[blank]',
				'open_id' => 'mla_markup_templates_open_blank',
				'open_text' => '',
				'open_help' => 'Markup for the beginning of the gallery. List of parameters, e.g., [+selector+], on Documentation tab.',

				'row_open_name' => 'mla_markup_templates_row_open[blank]',
				'row_open_id' => 'mla_markup_templates_row_open_blank',
				'row_open_text' => '',
				'row_open_help' => 'Markup for the beginning of each row in the gallery.',

				'item_name' => 'mla_markup_templates_item[blank]',
				'item_id' => 'mla_markup_templates_item_blank',
				'item_text' => '',
				'item_help' => 'Markup for each item/cell of the gallery.',

				'row_close_name' => 'mla_markup_templates_row_close[blank]',
				'row_close_id' => 'mla_markup_templates_row_close_blank',
				'row_close_text' => '',
				'row_close_help' => 'Markup for the end of each row in the gallery.',

				'close_name' => 'mla_markup_templates_close[blank]',
				'close_id' => 'mla_markup_templates_close_blank',
				'close_text' => '',
				'close_help' => 'Markup for the end of the gallery.'
				
			);

			$markup_options_list .= MLAData::mla_parse_template( self::$page_template_array['mla-gallery-markup'], $template_values );
		} // $value
		
		$page_values['markup_options_list'] = $markup_options_list;
		
		$page_content['body'] = MLAData::mla_parse_template( self::$page_template_array['mla-gallery-tab'], $page_values );
		return $page_content;
	}
	
	/**
	 * Compose the Documentation tab content for the Settings subpage
	 *
	 * @since 0.80
	 * @uses $page_template_array contains tab content template(s)
 	 *
	 * @return	array	'message' => status/error messages, 'body' => tab content
	 */
	private static function _compose_documentation_tab( ) {
		$page_values = array(
			'phpDocs_url' => MLA_PLUGIN_URL . 'phpDocs/index.html'
		);
		
		return array(
			'message' => '',
			'body' => MLAData::mla_parse_template( self::$page_template_array['documentation-tab'], $page_values ) 
		);
	}
	
	/**
	 * Render (echo) the "Media Library Assistant" subpage in the Settings section
	 *
	 * @since 0.1
	 *
	 * @return	void Echoes HTML markup for the Settings subpage
	 */
	public static function mla_render_settings_page( ) {
		if ( !current_user_can( 'manage_options' ) ) {
			echo "Media Library Assistant - Error</h2>\r\n";
			wp_die( __( 'You do not have permission to manage plugin settings.' ) );
		}
		
		/*
		 * Load template array and initialize page-level values.
		 */
		self::$page_template_array = MLAData::mla_load_template( MLA_PLUGIN_PATH . 'tpls/admin-display-settings-page.tpl' );
		$current_tab = isset( $_REQUEST['mla_tab'] ) ? $_REQUEST['mla_tab']: 'general';
		$page_values = array(
			'version' => 'v' . MLA::CURRENT_MLA_VERSION,
			'messages' => '',
			'tablist' => self::_compose_settings_tabs( $current_tab ),
			'tab_content' => ''
		);
		
		/*
		 * Compose tab content
		 */
		if ( array_key_exists( $current_tab, self::$mla_tablist ) ) {
			if ( isset( self::$mla_tablist[ $current_tab ]['render'] ) ) {
				$handler = self::$mla_tablist[ $current_tab ]['render'];
				$page_content = self::$handler(  );
			} else {
				$page_content = array( 'message' => 'ERROR: cannot render content tab', 'body' => '' );
			}
		} else {
			$page_content = array( 'message' => 'ERROR: unknown content tab', 'body' => '' );
		}

		if ( ! empty( $page_content['message'] ) )
			$page_values['messages'] = MLAData::mla_parse_template( self::$page_template_array['messages'], array(
				 'messages' => $page_content['message'] 
			) );

		$page_values['tab_content'] = $page_content['body'];
		echo MLAData::mla_parse_template( self::$page_template_array['page'], $page_values );
	} // mla_render_settings_page
	
	/**
	 * Determine MLA support for a taxonomy, handling the special case where the
	 * settings are being updated or reset.
 	 *
	 * @since 0.30
	 *
	 * @param	string	Taxonomy name, e.g., attachment_category
	 * @param	string	Optional. 'support' (default), 'quick-edit' or 'filter'
	 *
	 * @return	boolean|string
	 *			true if the taxonomy is supported in this way else false
	 *			string if $tax_name is '' and $support_type is 'filter', returns the taxonomy to filter by
	 */
	public static function mla_taxonomy_support($tax_name, $support_type = 'support') {
		$tax_options =  MLASettings::mla_get_option( 'taxonomy_support' );
		
		switch ( $support_type ) {
			case 'support': 
				$tax_support = isset( $tax_options['tax_support'] ) ? $tax_options['tax_support'] : array ();
				$is_supported = array_key_exists( $tax_name, $tax_support );
				
				if ( !empty( $_REQUEST['mla-general-options-save'] ) ) {
					$is_supported = isset( $_REQUEST['tax_support'][ $tax_name ] );
				} elseif ( !empty( $_REQUEST['mla-general-options-reset'] ) ) {
					switch ( $tax_name ) {
						case 'attachment_category':
						case 'attachment_tag':
							$is_supported = true;
							break;
						default:
							$is_supported = false;
					}
				}
		
				return $is_supported;
			case 'quick-edit':
				$tax_quick_edit = isset( $tax_options['tax_quick_edit'] ) ? $tax_options['tax_quick_edit'] : array ();
				$is_supported = array_key_exists( $tax_name, $tax_quick_edit );
				
				if ( !empty( $_REQUEST['mla-general-options-save'] ) ) {
					$is_supported = isset( $_REQUEST['tax_quick_edit'][ $tax_name ] );
				} elseif ( !empty( $_REQUEST['mla-general-options-reset'] ) ) {
					switch ( $tax_name ) {
						case 'attachment_category':
						case 'attachment_tag':
							$is_supported = true;
							break;
						default:
							$is_supported = false;
					}
				}
		
				return $is_supported;
			case 'filter':
				$tax_filter = isset( $tax_options['tax_filter'] ) ? $tax_options['tax_filter'] : '';
				if ( '' == $tax_name )
					return $tax_filter;
				else
					$is_supported = ( $tax_name == $tax_filter );
				
				if ( !empty( $_REQUEST['mla-general-options-save'] ) ) {
					$tax_filter = isset( $_REQUEST['tax_filter'] ) ? $_REQUEST['tax_filter'] : '';
					$is_supported = ( $tax_name == $tax_filter );
				} elseif ( !empty( $_REQUEST['mla-general-options-reset'] ) ) {
					if ( 'attachment_category' == $tax_name )
						$is_supported = true;
					else
						$is_supported = false;
				}
		
				return $is_supported;
			default:
				return false;
		} // $support_type
	} // mla_taxonomy_support
	
	/**
	 * Save MLA Gallery settings to the options table
 	 *
	 * @since 0.80
	 *
	 * @uses $_REQUEST
	 *
	 * @return	array	Message(s) reflecting the results of the operation
	 */
	private static function _save_gallery_settings( ) {
		$settings_changed = false;
		$message_list = '';
		$error_list = '';
		
		/*
		 * Start with any page-level options
		 */
		foreach ( self::$mla_options as $key => $value ) {
			if ( 'mla-gallery' == $value['tab'] )
				$message_list .= self::_update_option_row( $key, $value );
		} // foreach mla_options
		
		/*
		 * Get the current style contents for comparison
		 */
		$old_templates = self::_get_style_templates();
		$new_templates = array( );
		$new_names = $_REQUEST['mla_style_templates_name'];
		$new_values = stripslashes_deep( $_REQUEST['mla_style_templates_value'] );
		$new_deletes = isset( $_REQUEST['mla_style_templates_delete'] ) ? $_REQUEST['mla_style_templates_delete']: array( );
		
		/*
		 * Build new style template array, noting changes
		 */
		$templates_changed = false;
		foreach ( $new_names as $name => $new_name ) {
			if ( 'default' == $name )
				continue;

			if( array_key_exists( $name, $new_deletes ) ) {
				$message_list .= "<br>Deleting style template '{$name}'.";
				$templates_changed = true;
				continue;
			}

			$new_slug = sanitize_title( $new_name );
			if ( 'blank' == $name ) {
				if ( '' == $new_slug )
					continue;
				elseif ( 'blank' == $new_slug ) {
					$error_list .= "<br>Error: reserved name '{$new_slug}', new style template discarded.";
					continue;
				}
				
				if( array_key_exists( $new_slug, $old_templates ) ) {
					$error_list .= "<br>Error: duplicate name '{$new_slug}', new style template discarded.";
					continue;
				}
				else {
					$message_list .= "<br>Adding new style template '{$new_slug}'.";
					$templates_changed = true;
				}
			} // 'blank' - reserved name
			
			/*
			 * Handle name changes, check for duplicates
			 */
			if ( '' == $new_slug ) {
				$error_list .= "<br>Error: blank style template name value, reverting to '{$name}'.";
				$new_slug = $name;
			}
			
			if ( $new_slug != $name ) {
				if( array_key_exists( $new_slug, $old_templates ) ) {
					$error_list .= "<br>Error: duplicate new style template name '{$new_slug}', reverting to '{$name}'.";
					$new_slug = $name;
				}
				elseif ( 'blank' != $name ) {
					$message_list .= "<br>Changing style template name from '{$name}' to '{$new_slug}'.";
					$templates_changed = true;
				}
			} // name changed
			
			if ( ( 'blank' != $name ) && ( $new_values[ $name ] != $old_templates[ $name ] ) ) {
				$message_list .= "<br>Updating contents of style template '{$new_slug}'.";
				$templates_changed = true;
			}
			
			$new_templates[ $new_slug ] = $new_values[ $name ];
		} // foreach $name
		
		if ( $templates_changed ) {
			$settings_changed = true;
			if ( false == self::_put_style_templates( $new_templates ) )
				$error_list .= "<br>Error: update of style templates failed.";
		}
		
		/*
		 * Get the current markup contents for comparison
		 */
		$old_templates = self::_get_markup_templates();
		$new_templates = array( );
		$new_names = $_REQUEST['mla_markup_templates_name'];
		$new_values['open'] = stripslashes_deep( $_REQUEST['mla_markup_templates_open'] );
		$new_values['row-open'] = stripslashes_deep( $_REQUEST['mla_markup_templates_row_open'] );
		$new_values['item'] = stripslashes_deep( $_REQUEST['mla_markup_templates_item'] );
		$new_values['row-close'] = stripslashes_deep( $_REQUEST['mla_markup_templates_row_close'] );
		$new_values['close'] = stripslashes_deep( $_REQUEST['mla_markup_templates_close'] );
		$new_deletes = isset( $_REQUEST['mla_markup_templates_delete'] ) ? $_REQUEST['mla_markup_templates_delete']: array( );
		
		/*
		 * Build new markup template array, noting changes
		 */
		$templates_changed = false;
		foreach ( $new_names as $name => $new_name ) {
			if ( 'default' == $name )
				continue;

			if( array_key_exists( $name, $new_deletes ) ) {
				$message_list .= "<br>Deleting markup template '{$name}'.";
				$templates_changed = true;
				continue;
			}

			$new_slug = sanitize_title( $new_name );
			if ( 'blank' == $name ) {
				if ( '' == $new_slug )
					continue;
					
				if ( 'blank' == $new_slug ) {
					$error_list .= "<br>Error: reserved name '{$new_slug}', new markup template discarded.";
					continue;
				}
				
				if( array_key_exists( $new_slug, $old_templates ) ) {
					$error_list .= "<br>Error: duplicate name '{$new_slug}', new markup template discarded.";
					continue;
				}
				else {
					$message_list .= "<br>Adding new markup template '{$new_slug}'.";
					$templates_changed = true;
				}
			} // 'blank' - reserved name
			
			/*
			 * Handle name changes, check for duplicates
			 */
			if ( '' == $new_slug ) {
				$error_list .= "<br>Error: blank markup template name value, reverting to '{$name}'.";
				$new_slug = $name;
			}
			
			if ( $new_slug != $name ) {
				if( array_key_exists( $new_slug, $old_templates ) ) {
					$error_list .= "<br>Error: duplicate new markup template name '{$new_slug}', reverting to '{$name}'.";
					$new_slug = $name;
				}
				
				if( array_key_exists( $new_slug, $old_templates ) ) {
					$error_list .= "<br>Error: duplicate new markup template name '{$new_slug}', reverting to '{$name}'.";
					$new_slug = $name;
				}
				elseif ( 'blank' != $name ) {
					$message_list .= "<br>Changing markup template name from '{$name}' to '{$new_slug}'.";
					$templates_changed = true;
				}
			} // name changed
			
			if ( 'blank' != $name ) {
				if ( $new_values['open'][ $name ] != $old_templates[ $name ]['open'] ) {
					$message_list .= "<br>Updating open markup for '{$new_slug}'.";
					$templates_changed = true;
				}
				
				if ( $new_values['row-open'][ $name ] != $old_templates[ $name ]['row-open'] ) {
					$message_list .= "<br>Updating row open markup for '{$new_slug}'.";
					$templates_changed = true;
				}
				
				if ( $new_values['item'][ $name ] != $old_templates[ $name ]['item'] ) {
					$message_list .= "<br>Updating item markup for '{$new_slug}'.";
					$templates_changed = true;
				}
				
				if ( $new_values['row-close'][ $name ] != $old_templates[ $name ]['row-close'] ) {
					$message_list .= "<br>Updating row close markup for '{$new_slug}'.";
					$templates_changed = true;
				}
				
				if ( $new_values['close'][ $name ] != $old_templates[ $name ]['close'] ) {
					$message_list .= "<br>Updating close markup for '{$new_slug}'.";
					$templates_changed = true;
				}
			} // ! 'blank'
			
			$new_templates[ $new_slug ]['open'] = $new_values['open'][ $name ];
			$new_templates[ $new_slug ]['row-open'] = $new_values['row-open'][ $name ];
			$new_templates[ $new_slug ]['item'] = $new_values['item'][ $name ];
			$new_templates[ $new_slug ]['row-close'] = $new_values['row-close'][ $name ];
			$new_templates[ $new_slug ]['close'] = $new_values['close'][ $name ];
		} // foreach $name
		
		if ( $templates_changed ) {
			$settings_changed = true;
			if ( false == self::_put_markup_templates( $new_templates ) )
				$error_list .= "<br>Error: update of markup templates failed.";
		}
		
		if ( $settings_changed )
			$message = "MLA Gallery settings saved.\r\n";
		else
			$message = "MLA Gallery no changes detected.\r\n";
		
		$page_content = array(
			'message' => $message . $error_list,
			'body' => '' 
		);
		
		/*
		 * Uncomment this for debugging.
		 */
		// $page_content['message'] .= $message_list;

		return $page_content;
	} // _save_gallery_settings
	
	/**
	 * Save General settings to the options table
 	 *
	 * @since 0.1
	 *
	 * @uses $_REQUEST
	 *
	 * @return	array	Message(s) reflecting the results of the operation
	 */
	private static function _save_general_settings( ) {
		$message_list = '';
		
		foreach ( self::$mla_options as $key => $value ) {
			if ( 'general' == $value['tab'] )
				$message_list .= self::_update_option_row( $key, $value );
		} // foreach mla_options
		
		$page_content = array(
			'message' => "General settings saved.\r\n",
			'body' => '' 
		);
		
		/*
		 * Uncomment this for debugging.
		 */
		// $page_content['message'] .= $message_list;

		return $page_content;
	} // _save_general_settings
	
	/**
	 * Delete saved settings, restoring default values
 	 *
	 * @since 0.1
	 *
	 * @return	array	Message(s) reflecting the results of the operation
	 */
	private static function _reset_general_settings( ) {
		$message_list = '';
		
		foreach ( self::$mla_options as $key => $value ) {
			if ( 'custom' == $value['type'] ) {
				$message = self::$value['reset']( 'reset', $key, $value, $_REQUEST );
			}
			elseif ( ('header' == $value['type']) || ('hidden' == $value['type']) ) {
				$message = '';
			}
			else {
				self::mla_delete_option( $key );
				$message = '<br>delete_option(' . $key . ')';
			}
			
			$message_list .= $message;
		}
		
		$page_content = array(
			'message' => 'Settings reset to default values.',
			'body' => '' 
		);

		/*
		 * Uncomment this for debugging.
		 */
		// $page_content['message'] .= $message_list;
		
		return $page_content;
	} // _reset_general_settings
	
	/**
	 * Render and manage other taxonomy support options, e.g., Categories and Post Tags
 	 *
	 * @since 0.30
	 * @uses $page_template_array contains taxonomy-row and taxonomy-table templates
	 *
	 * @param	string 	'render', 'update', 'delete', or 'reset'
	 * @param	string 	option name, e.g., 'taxonomy_support'
	 * @param	array 	option parameters
	 * @param	array 	Optional. null (default) for 'render' else $_REQUEST
	 *
	 * @return	string	HTML table row markup for 'render' else message(s) reflecting the results of the operation.
	 */
	private static function _taxonomy_handler( $action, $key, $value, $args = null ) {
		switch ( $action ) {
			case 'render':
				$taxonomies = get_taxonomies( array ( 'show_ui' => 'true' ), 'objects' );
				$current_values = self::mla_get_option( $key );
				$tax_support = isset( $current_values['tax_support'] ) ? $current_values['tax_support'] : array ();
				$tax_quick_edit = isset( $current_values['tax_quick_edit'] ) ? $current_values['tax_quick_edit'] : array ();
				$tax_filter = isset( $current_values['tax_filter'] ) ? $current_values['tax_filter'] : '';
				
				/*
				 * Always display our own taxonomies, even if not registered.
				 * Otherwise there's no way to turn them back on.
				 */
				if ( ! array_key_exists( 'attachment_category', $taxonomies ) ) {
					$taxonomies['attachment_category'] = (object) array( 'labels' => (object) array( 'name' => 'Attachment Categories' ) );
					if ( isset( $tax_support['attachment_category'] ) )
						unset( $tax_support['attachment_category'] );
						
					if ( isset( $tax_quick_edit['attachment_category'] ) )
						unset( $tax_quick_edit['attachment_category'] );
						
					if ( $tax_filter == 'attachment_category' )
						$tax_filter = '';
				}

				if ( ! array_key_exists( 'attachment_tag', $taxonomies ) ) {
					$taxonomies['attachment_tag'] = (object) array( 'labels' => (object) array( 'name' => 'Attachment Tags' ) );

					if ( isset( $tax_support['attachment_tag'] ) )
						unset( $tax_support['attachment_tag'] );
						
					if ( isset( $tax_quick_edit['attachment_tag'] ) )
						unset( $tax_quick_edit['attachment_tag'] );
						
					if ( $tax_filter == 'attachment_tag' )
						$tax_filter = '';
				}

				$taxonomy_row = self::$page_template_array['taxonomy-row'];
				$row = '';
				
				foreach ($taxonomies as $tax_name => $tax_object) {
					$option_values = array (
						'key' => $tax_name,
						'name' => $tax_object->labels->name,
						'support_checked' => array_key_exists( $tax_name, $tax_support ) ? 'checked=checked' : '',
						'quick_edit_checked' => array_key_exists( $tax_name, $tax_quick_edit ) ? 'checked=checked' : '',
						'filter_checked' => ( $tax_name == $tax_filter ) ? 'checked=checked' : ''
					);
					
					$row .= MLAData::mla_parse_template( $taxonomy_row, $option_values );
				}

				$option_values = array (
					'taxonomy_rows' => $row,
					'help' => $value['help']
				);
				
				return MLAData::mla_parse_template( self::$page_template_array['taxonomy-table'], $option_values );
			case 'update':
			case 'delete':
				$tax_support = isset( $args['tax_support'] ) ? $args['tax_support'] : array ();
				$tax_quick_edit = isset( $args['tax_quick_edit'] ) ? $args['tax_quick_edit'] : array ();
				$tax_filter = isset( $args['tax_filter'] ) ? $args['tax_filter'] : '';

				$msg = '';
				
				if ( !empty($tax_filter) && !array_key_exists( $tax_filter, $tax_support ) ) {
					$msg .= "<br>List Filter ignored; {$tax_filter} not supported.\r\n";
					$tax_filter = '';
				}

				foreach ($tax_quick_edit as $tax_name => $tax_value) {				
					if ( !array_key_exists( $tax_name, $tax_support ) ) {
						$msg .= "<br>Quick Edit ignored; {$tax_name} not supported.\r\n";
						unset( $tax_quick_edit[ $tax_name ] );
					}
				}
				
				$value = array (
					'tax_support' => $tax_support,
					'tax_quick_edit' => $tax_quick_edit,
					'tax_filter' => $tax_filter
					);
				
				self::mla_update_option( $key, $value );
				
				if ( empty( $msg ) )
					$msg = "<br>Update custom {$key}\r\n";

				return $msg;
			case 'reset':
				self::mla_delete_option( $key );
				return "<br>Reset custom {$key}\r\n";
			default:
				error_log( 'ERROR: _save_settings unknown type(3): ' . var_export( $value, true ), 0 );
				return '';
		}
	} // _taxonomy_handler
} // class MLASettings
?>