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
	 *     $options_list .= ['render']( 'render', $key, $value, $page_template_array );
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
			array('type' => 'hidden', 
				'std' => '0'),
		
		/* 
		 * These checkboxes are no longer used;
		 * they are retained for the database version/update check
		 */
		'attachment_category' =>
			array('name' => 'Attachment Categories',
				'type' => 'hidden', // checkbox',
				'std' => 'checked',
				'help' => 'Check this option to add support for Attachment Categories.'),
		
		'attachment_tag' =>
			array('name' => 'Attachment Tags',
				'type' => 'hidden', // checkbox',
				'std' => 'checked',
				'help' => 'Check this option to add support for Attachment Tags.'),
	
		'where_used_heading' =>
			array('name' => 'Where-used Reporting',
				'type' => 'header'),
		
		'exclude_revisions' =>
			array('name' => 'Exclude Revisions',
				'type' => 'checkbox',
				'std' => 'checked',
				'help' => 'Check this option to exclude revisions from where-used reporting.'),
	
		'taxonomy_heading' =>
			array('name' => 'Taxonomy Support',
				'type' => 'header'),
		
		'taxonomy_support' =>
			array('help' => 'Check the "Support" box to add the taxonomy to the Assistant.<br>Check the "Inline Edit" box to display the taxonomy in the Quick Edit and Bulk Edit areas.<br>Use the "List Filter" option to select the taxonomy on which to filter the Assistant table listing.',
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
			array('name' => 'Default Table Listing Sort Order',
				'type' => 'header'),
		
		'default_orderby' =>
			array('name' => 'Order By',
				'type' => 'select',
				'std' => 'title_name',
				'options' => array('title_name'),
				'texts' => array('Title/Name'),
				'help' => 'Select the column for the sort order of the Assistant table listing.'),
	
		'default_order' =>
			array('name' => 'Order',
				'type' => 'radio',
				'std' => 'ASC',
				'options' => array('ASC', 'DESC'),
				'texts' => array('Ascending', 'Descending'),
				'help' => 'Choose the sort order.'),
	
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
		self::mla_activation_hook();
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
		global $wpdb, $table_prefix;
		
		$view_name = $table_prefix . MLA_OPTION_PREFIX . self::MLA_ALT_TEXT_VIEW_SUFFIX;
		$table_name = $table_prefix . 'postmeta';
		$result = $wpdb->query(
			$wpdb->prepare(
				"
				SHOW TABLES LIKE '{$view_name}'
				"
			)
		);

		if ( 0 == $result ) {
			$result = $wpdb->query(
				$wpdb->prepare(
					"
					CREATE OR REPLACE VIEW {$view_name} AS
					SELECT post_id, meta_value
					FROM {$table_name}
					WHERE {$table_name}.meta_key = '_wp_attachment_image_alt'
					"
				)
			);
		}
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
			$wpdb->prepare(
				"
				SHOW TABLES LIKE '{$view_name}'
				"
			)
		);

		if ( $result) {		
			$result = $wpdb->query(
				$wpdb->prepare(
					"
					DROP VIEW {$view_name}
					"
				)
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
	 * Render (echo) the "Media Library Assistant" subpage in the Settings section
	 *
	 * @since 0.1
	 *
	 * @return	void Echoes HTML markup for the settings subpage
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
			'version' => 'v' . MLA::CURRENT_MLA_VERSION,
			'messages' => '',
			'shortcode_list' => '',
			'options_list' => '',
			'mla_admin_action' => MLA::MLA_ADMIN_SINGLE_EDIT_UPDATE,
			'page' => self::MLA_SETTINGS_SLUG,
			'_wpnonce' => wp_nonce_field( MLA::MLA_ADMIN_NONCE, '_wpnonce', true, false ),
			'_wp_http_referer' => wp_referer_field( false ),
			'phpDocs_url' => MLA_PLUGIN_URL . 'phpDocs/index.html'
		);
		
		/*
		 * Check for submit buttons to change or reset settings.
		 */
		if ( !empty( $_REQUEST['mla-options-save'] ) ) {
			check_admin_referer( MLA::MLA_ADMIN_NONCE, '_wpnonce' );
			$page_content = self::_save_settings( $page_template_array );
		} elseif ( !empty( $_REQUEST['mla-options-reset'] ) ) {
			check_admin_referer( MLA::MLA_ADMIN_NONCE, '_wpnonce' );
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
		 * $shortcodes documents the name and description of plugin shortcodes
		 */
		$shortcodes = array( 
			// array("name" => "shortcode", "description" => "This shortcode...")
			array( 'name' => 'mla_attachment_list', 'description' => 'renders a complete list of all attachments and references to them.' )
		);
		
		$shortcode_list = '';
		foreach ( $shortcodes as $shortcode ) {
			$shortcode_values = array ( 'name' => $shortcode['name'], 'description' => $shortcode['description'] );
			$shortcode_list .= MLAData::mla_parse_template( $page_template_array['shortcodeitem'], $shortcode_values );
		}
		
		if ( ! empty( $shortcode_list ) ) {
			$shortcode_values = array ( 'shortcode_list' => $shortcode_list );
			$page_values['shortcode_list'] = MLAData::mla_parse_template( $page_template_array['shortcodelist'], $shortcode_values );
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
							'option' => $option,
							'checked' => '',
							'value' => $value['texts'][$optid] 
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
					$select_options = '';
					foreach ( $value['options'] as $optid => $option ) {
						$option_values = array(
							'selected' => '',
							'value' => $option,
							'text' => $value['texts'][$optid]
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
				case 'custom':
					if ( isset( $value['render'] ) )
						$options_list .= self::$value['render']( 'render', $key, $value, $page_template_array );
					break;
				case 'hidden':
					break;
				default:
					error_log( 'ERROR: mla_render_settings_page unknown type: ' . var_export( $value, true ), 0 );
			}
		}
		
		if ( ! empty( $page_content['message'] ) )
			$page_values['messages'] = MLAData::mla_parse_template( $page_template_array['messages'], array(
				 'messages' => $page_content['message'] 
			) );
		
		$page_values['options_list'] = $options_list;
		echo MLAData::mla_parse_template( $page_template_array['page'], $page_values );
	} // mla_render_settings_page
	
	/**
	 * Determine MLA support for a taxonomy, handling the special case where the
	 * settings are being updated or reset.
 	 *
	 * @since 0.30
	 *
	 * @param	string	Taxonomy name, e.g., attachment_category
	 * @param	string	'support' (the default), 'quick-edit' or 'filter'
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
				
				if ( !empty( $_REQUEST['mla-options-save'] ) ) {
					$is_supported = isset( $_REQUEST['tax_support'][ $tax_name ] );
				} elseif ( !empty( $_REQUEST['mla-options-reset'] ) ) {
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
				
				if ( !empty( $_REQUEST['mla-options-save'] ) ) {
					$is_supported = isset( $_REQUEST['tax_quick_edit'][ $tax_name ] );
				} elseif ( !empty( $_REQUEST['mla-options-reset'] ) ) {
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
				
				if ( !empty( $_REQUEST['mla-options-save'] ) ) {
					$tax_filter = isset( $_REQUEST['tax_filter'] ) ? $_REQUEST['tax_filter'] : '';
					$is_supported = ( $tax_name == $tax_filter );
				} elseif ( !empty( $_REQUEST['mla-options-reset'] ) ) {
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
	 * Save settings to the options table
 	 *
	 * @since 0.1
	 *
	 * @param	array 	HTML template(s) for the settings page
	 *
	 * @return	array	Message(s) reflecting the results of the operation
	 */
	private static function _save_settings( $template_array ) {
		$message_list = '';
		
		foreach ( self::$mla_options as $key => $value ) {
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
				}
				
				$message_list .= $message;
			} else {
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
				}
				
				$message_list .= $message;
			}
		}
		
		$page_content = array(
			'message' => "Settings saved.\r\n",
			'body' => '' 
		);
		
		/*
		 * Uncomment this for debugging.
		 */
		// $page_content['message'] .= $message_list;

		return $page_content;
	} // _save_settings
	
	/**
	 * Delete saved settings, restoring default values
 	 *
	 * @since 0.1
	 *
	 * @param	array 	HTML template(s) for the settings page
	 *
	 * @return	array	Message(s) reflecting the results of the operation
	 */
	private static function _reset_settings( $template_array ) {
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
	} // _reset_settings
	
	/**
	 * Render and manage other taxonomy support options, e.g., Categories and Post Tags
 	 *
	 * @since 0.30
	 *
	 * @param	string 	'render', 'update', 'delete', or 'reset'
	 * @param	string 	option name, e.g., 'taxonomy_support'
	 * @param	array 	option parameters
	 * @param	array 	The $page_template_array for 'render' else $_REQUEST
	 *
	 * @return	string	HTML table row markup for 'render' else message(s) reflecting the results of the operation.
	 */
	private static function _taxonomy_handler( $action, $key, $value, $args ) {
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

				$taxonomy_row = $args['taxonomyrow'];
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
				
				return MLAData::mla_parse_template( $args['taxonomytable'], $option_values );
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
				error_log( 'ERROR: _save_settings unknown type(2): ' . var_export( $value, true ), 0 );
				return '';
		}
	} // _taxonomy_handler
} // class MLASettings
?>