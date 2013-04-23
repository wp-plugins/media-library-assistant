<?php
/**
 * Manages the settings page to edit the plugin option settings
 *
 * @package Media Library Assistant
 * @since 0.1
 */

/**
 * Class MLA (Media Library Assistant) Settings provides the settings page to edit the plugin option settings
 *
 * @package Media Library Assistant
 * @since 0.1
 */
class MLASettings {
	/**
	 * Provides a unique name for the settings page
	 */
	const MLA_SETTINGS_SLUG = 'mla-settings-menu';
	
	/**
	 * Initialization function, similar to __construct()
	 *
	 * @since 0.1
	 *
	 * @return	void
	 */
	public static function initialize( ) {
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
		$current_version = MLAOptions::mla_get_option( MLAOptions::MLA_VERSION_OPTION );
		
		if ( ((float)'.30') > ((float)$current_version) ) {
			/*
			 * Convert attachment_category and _tag to taxonomy_support;
			 * change the default if either option is unchecked
			 */
			$category_option = MLAOptions::mla_get_option( 'attachment_category' );
			$tag_option = MLAOptions::mla_get_option( 'attachment_tag' );
			if ( ! ( ( 'checked' == $category_option ) && ( 'checked' == $tag_option ) ) ) {
				$tax_option = MLAOptions::mla_get_option( 'taxonomy_support' );
				if ( 'checked' != $category_option ) {
					if ( isset( $tax_option['tax_support']['attachment_category'] ) )
						unset( $tax_option['tax_support']['attachment_category'] );
				}

				if ( 'checked' != $tag_option )  {
					if ( isset( $tax_option['tax_support']['attachment_tag'] ) )
						unset( $tax_option['tax_support']['attachment_tag'] );
				}

				MLAOptions::mla_taxonomy_option_handler( 'update', 'taxonomy_support', MLAOptions::$mla_option_definitions['taxonomy_support'], $tax_option );
			} // one or both options unchecked

		MLAOptions::mla_delete_option( 'attachment_category' );
		MLAOptions::mla_delete_option( 'attachment_tag' );
		} // version is less than .30
		
		if ( ((float)'1.13') > ((float)$current_version) ) {
			/*
			 * Add quick_edit and bulk_edit values to custom field mapping rules
			 */
			$new_values = array();
			
			foreach( MLAOptions::mla_get_option( 'custom_field_mapping' ) as $key => $value ) {
				$value['quick_edit'] = ( isset( $value['quick_edit'] ) && $value['quick_edit'] ) ? true : false;
				$value['bulk_edit'] = ( isset( $value['bulk_edit'] ) && $value['bulk_edit'] ) ? true : false;
				$new_values[ $key ] = $value;
			}

			MLAOptions::mla_update_option( 'custom_field_mapping', $new_values );
		} // version is less than 1.13
		
		if ( ((float)'1.30') > ((float)$current_version) ) {
			/*
			 * Add metadata values to custom field mapping rules
			 */
			$new_values = array();
			
			foreach( MLAOptions::mla_get_option( 'custom_field_mapping' ) as $key => $value ) {
				$value['meta_name'] = isset( $value['meta_name'] ) ? $value['meta_name'] : '';
				$value['meta_single'] = ( isset( $value['meta_single'] ) && $value['meta_single'] ) ? true : false;
				$value['meta_export'] = ( isset( $value['meta_export'] ) && $value['meta_export'] ) ? true : false;
				$new_values[ $key ] = $value;
			}

			MLAOptions::mla_update_option( 'custom_field_mapping', $new_values );
		} // version is less than 1.30
		
		MLAOptions::mla_update_option( MLAOptions::MLA_VERSION_OPTION, MLA::CURRENT_MLA_VERSION );
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
		// self::_create_alt_text_view(); DELETED v1.10, NO LONGER REQUIRED
	}
	
	/**
	 * Perform one-time actions on plugin deactivation
	 *
	 * Removes (if present) a view from the database that supports sorting the listing on 'ALT Text'.
	 *
	 * @since 0.40
	 *
	 * @return	void
	 */
	public static function mla_deactivation_hook( ) {
		global $wpdb, $table_prefix;
		
		$view_name = $table_prefix . MLA_OPTION_PREFIX . MLAData::MLA_ALT_TEXT_VIEW_SUFFIX;
		$result = $wpdb->query( "SHOW TABLES LIKE '{$view_name}'" );

		if ( $result) {		
			$result = $wpdb->query(	"DROP VIEW {$view_name}" );
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
					MLAOptions::mla_update_option( $key, 'checked' );
					break;
				case 'header':
				case 'subheader':
					$message = '';
					break;
				case 'radio':
					MLAOptions::mla_update_option( $key, $_REQUEST[ MLA_OPTION_PREFIX . $key ] );
					break;
				case 'select':
					MLAOptions::mla_update_option( $key, $_REQUEST[ MLA_OPTION_PREFIX . $key ] );
					break;
				case 'text':
					MLAOptions::mla_update_option( $key, trim( $_REQUEST[ MLA_OPTION_PREFIX . $key ] ) );
					break;
				case 'textarea':
					MLAOptions::mla_update_option( $key, trim( $_REQUEST[ MLA_OPTION_PREFIX . $key ] ) );
					break;
				case 'custom':
					$message = MLAOptions::$value['update']( 'update', $key, $value, $_REQUEST );
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
					MLAOptions::mla_update_option( $key, 'unchecked' );
					break;
				case 'header':
				case 'subheader':
					$message = '';
					break;
				case 'radio':
					MLAOptions::mla_delete_option( $key );
					break;
				case 'select':
					MLAOptions::mla_delete_option( $key );
					break;
				case 'text':
					MLAOptions::mla_delete_option( $key );
					break;
				case 'textarea':
					MLAOptions::mla_delete_option( $key );
					break;
				case 'custom':
					$message = MLAOptions::$value['delete']( 'delete', $key, $value, $_REQUEST );
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
				
				if ( 'checked' == MLAOptions::mla_get_option( $key ) )
					$option_values['checked'] = 'checked="checked"';
				
				return MLAData::mla_parse_template( self::$page_template_array['checkbox'], $option_values );
			case 'header':
			case 'subheader':
				$option_values = array(
					'key' => MLA_OPTION_PREFIX . $key,
					'value' => $value['name'] 
				);
				
				return MLAData::mla_parse_template( self::$page_template_array[ $value['type'] ], $option_values );
			case 'radio':
				$radio_options = '';
				foreach ( $value['options'] as $optid => $option ) {
					$option_values = array(
						'key' => MLA_OPTION_PREFIX . $key,
						'option' => $option,
						'checked' => '',
						'value' => $value['texts'][$optid] 
					);
					
					if ( $option == MLAOptions::mla_get_option( $key ) )
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
					
					if ( $option == MLAOptions::mla_get_option( $key ) )
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
				
				$option_values['text'] = MLAOptions::mla_get_option( $key );
				
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
				
				$option_values['text'] = stripslashes( MLAOptions::mla_get_option( $key ) );
				
				return MLAData::mla_parse_template( self::$page_template_array['textarea'], $option_values );
			case 'custom':
				if ( isset( $value['render'] ) )
					return MLAOptions::$value['render']( 'render', $key, $value );

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
		'custom-field' => array( 'title' => 'Custom Fields', 'render' => '_compose_custom_field_tab' ),
		'iptc-exif' => array( 'title' => 'IPTC/EXIF', 'render' => '_compose_iptc_exif_tab' ),
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
			'donateURL' => MLA_PLUGIN_URL . 'images/DonateButton.jpg',
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
			MLAOptions::$mla_option_definitions['default_orderby']['options'][] = $value[0];
			MLAOptions::$mla_option_definitions['default_orderby']['texts'][] = $value[1];
		}
		
		$options_list = '';
		foreach ( MLAOptions::$mla_option_definitions as $key => $value ) {
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
		 * Build default template selection lists
		 */
		MLAOptions::$mla_option_definitions['default_style']['options'][] = 'none';
		MLAOptions::$mla_option_definitions['default_style']['texts'][] = '-- none --';

		$templates = MLAOptions::mla_get_style_templates();
		ksort($templates);
		foreach ($templates as $key => $value ) {
			MLAOptions::$mla_option_definitions['default_style']['options'][] = $key;
			MLAOptions::$mla_option_definitions['default_style']['texts'][] = $key;
		}

		$templates = MLAOptions::mla_get_markup_templates();
		ksort($templates);
		foreach ($templates as $key => $value ) {
			MLAOptions::$mla_option_definitions['default_markup']['options'][] = $key;
			MLAOptions::$mla_option_definitions['default_markup']['texts'][] = $key;
		}
		
		/*
		 * Start with any page-level options
		 */
		$options_list = '';
		foreach ( MLAOptions::$mla_option_definitions as $key => $value ) {
			if ( 'mla-gallery' == $value['tab'] )
				$options_list .= self::_compose_option_row( $key, $value );
		}
		
		$page_values['options_list'] = $options_list;

		/*
		 * Add style templates; default goes first
		 */
		$style_options_list = '';
		$templates = MLAOptions::mla_get_style_templates();
		
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
		$templates = MLAOptions::mla_get_markup_templates();
		
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
	 * Compose the Custom Field tab content for the Settings subpage
	 *
	 * @since 1.10
	 * @uses $page_template_array contains tab content template(s)
 	 *
	 * @return	array	'message' => status/error messages, 'body' => tab content
	 */
	private static function _compose_custom_field_tab( ) {
		/*
		 * Check for action or submit buttons.
		 * Initialize page messages and content.
		 */
		if ( isset( $_REQUEST['custom_field_mapping'] ) && is_array( $_REQUEST['custom_field_mapping'] ) ) {
			check_admin_referer( MLA::MLA_ADMIN_NONCE, '_wpnonce' );

			/*
			 * Check for page-level submit buttons to change settings or map attachments.
			 * Initialize page messages and content.
			 */
			if ( !empty( $_REQUEST['custom-field-options-save'] ) ) {
				$page_content = self::_save_custom_field_settings( );
			}
			elseif ( !empty( $_REQUEST['custom-field-options-map'] ) ) {
				$page_content = self::_process_custom_field_mapping( );
			}
			else {
				$page_content = array(
					 'message' => '',
					'body' => '' 
				);

				/*
				 * Check for single-rule action buttons
				 */
				foreach( $_REQUEST['custom_field_mapping'] as $key => $value ) {
					if ( isset( $value['action'] ) ) {
						$settings = array( $key => $value );
						foreach ( $value['action'] as $action => $label ) {
							switch( $action ) {
								case 'delete_field':
									$delete_result = self::_delete_custom_field( $value );
								case 'delete_rule':
								case 'add_rule':
								case 'add_field':
								case 'update_rule':
									$page_content = self::_save_custom_field_settings( $settings );
									if ( isset( $delete_result ) )
										$page_content['message'] = $delete_result . $page_content['message'];
									break;
								case 'map_now':
									$page_content = self::_process_custom_field_mapping( $settings );
									break;
								case 'add_rule_map':
								case 'add_field_map':
									$page_content = self::_save_custom_field_settings( $settings );
									$map_content = self::_process_custom_field_mapping( $settings );
									$page_content['message'] .= '<br>&nbsp;<br>' . $map_content['message'];
									break;
								default:
									// ignore everything else
							} //switch action
						} // foreach action
					} /// isset action
				} // foreach rule
			} // specific rule check
		} // isset custom_field_mapping
		else {
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
			'custom_options_list' => '',
			'_wpnonce' => wp_nonce_field( MLA::MLA_ADMIN_NONCE, '_wpnonce', true, false ),
			'_wp_http_referer' => wp_referer_field( false )
		);
		
		/*
		 * Start with any page-level options
		 */
		$options_list = '';
		foreach ( MLAOptions::$mla_option_definitions as $key => $value ) {
			if ( 'custom-field' == $value['tab'] )
				$options_list .= self::_compose_option_row( $key, $value );
		}
		
		$page_values['options_list'] = $options_list;
		
		/*
		 * Add mapping options
		 */
		$page_values['custom_options_list'] = MLAOptions::mla_custom_field_option_handler( 'render', 'custom_field_mapping', MLAOptions::$mla_option_definitions['custom_field_mapping'] );
		
		$page_content['body'] = MLAData::mla_parse_template( self::$page_template_array['custom-field-tab'], $page_values );
		return $page_content;
	}
	
	/**
	 * Compose the IPTC/EXIF tab content for the Settings subpage
	 *
	 * @since 1.00
	 * @uses $page_template_array contains tab content template(s)
 	 *
	 * @return	array	'message' => status/error messages, 'body' => tab content
	 */
	private static function _compose_iptc_exif_tab( ) {
		/*
		 * Initialize page messages and content.
		 * Check for submit buttons to change or reset settings.
		 */
		$page_content = array(
			'message' => '',
			'body' => '' 
		);

		if ( isset( $_REQUEST['iptc_exif_mapping'] ) && is_array( $_REQUEST['iptc_exif_mapping'] ) ) {
			check_admin_referer( MLA::MLA_ADMIN_NONCE, '_wpnonce' );

			if ( !empty( $_REQUEST['iptc-exif-options-save'] ) ) {
				$page_content = self::_save_iptc_exif_settings( );
			}
			elseif ( !empty( $_REQUEST['iptc-exif-options-process-standard'] ) ) {
				$page_content = self::_process_iptc_exif_standard( );
			}
			elseif ( !empty( $_REQUEST['iptc-exif-options-process-taxonomy'] ) ) {
				$page_content = self::_process_iptc_exif_taxonomy( );
			}
			elseif ( !empty( $_REQUEST['iptc-exif-options-process-custom'] ) ) {
				$page_content = self::_process_iptc_exif_custom( );
			}
			else {
				/*
				 * Check for single-rule action buttons
				 */
				foreach( $_REQUEST['iptc_exif_mapping']['custom'] as $key => $value ) {
					if ( isset( $value['action'] ) ) {
						$settings = array( 'custom' => array( $key => $value ) );
						foreach ( $value['action'] as $action => $label ) {
							switch( $action ) {
								case 'delete_field':
									$delete_result = self::_delete_custom_field( $value );
								case 'delete_rule':
								case 'add_rule':
								case 'add_field':
								case 'update_rule':
									$page_content = self::_save_iptc_exif_custom_settings( $settings );
									if ( isset( $delete_result ) )
										$page_content['message'] = $delete_result . $page_content['message'];
									break;
								case 'map_now':
									$page_content = self::_process_iptc_exif_custom( $settings );
									break;
								case 'add_rule_map':
								case 'add_field_map':
									$page_content = self::_save_iptc_exif_custom_settings( $settings );
									$map_content = self::_process_iptc_exif_custom( $settings );
									$page_content['message'] .= '<br>&nbsp;<br>' . $map_content['message'];
									break;
								default:
									// ignore everything else
							} //switch action
						} // foreach action
					} /// isset action
				} // foreach rule
			}
			
			if ( !empty( $page_content['body'] ) ) {
				return $page_content;
			}
		}
		
		$page_values = array(
			'options_list' => '',
			'standard_options_list' => '',
			'taxonomy_options_list' => '',
			'custom_options_list' => '',
			'_wpnonce' => wp_nonce_field( MLA::MLA_ADMIN_NONCE, '_wpnonce', true, false ),
			'_wp_http_referer' => wp_referer_field( false )
		);
		
		/*
		 * Start with any page-level options
		 */
		$options_list = '';
		foreach ( MLAOptions::$mla_option_definitions as $key => $value ) {
			if ( 'iptc-exif' == $value['tab'] )
				$options_list .= self::_compose_option_row( $key, $value );
		}
		
		$page_values['options_list'] = $options_list;
		
		/*
		 * Add mapping options
		 */
		$page_values['standard_options_list'] = MLAOptions::mla_iptc_exif_option_handler( 'render', 'iptc_exif_standard_mapping', MLAOptions::$mla_option_definitions['iptc_exif_standard_mapping'] );
		
		$page_values['taxonomy_options_list'] = MLAOptions::mla_iptc_exif_option_handler( 'render', 'iptc_exif_taxonomy_mapping', MLAOptions::$mla_option_definitions['iptc_exif_taxonomy_mapping'] );
		
		$page_values['custom_options_list'] = MLAOptions::mla_iptc_exif_option_handler( 'render', 'iptc_exif_custom_mapping', MLAOptions::$mla_option_definitions['iptc_exif_custom_mapping'] );
		
		$page_content['body'] = MLAData::mla_parse_template( self::$page_template_array['iptc-exif-tab'], $page_values );
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
		$page_template = MLAData::mla_load_template( MLA_PLUGIN_PATH . 'tpls/documentation-settings-tab.tpl' );
		$page_values = array(
			'phpDocs_url' => MLA_PLUGIN_URL . 'phpDocs/index.html'
		);
		
		return array(
			'message' => '',
			'body' => MLAData::mla_parse_template( $page_template['documentation-tab'], $page_values ) 
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
			'donateURL' => MLA_PLUGIN_URL . 'images/DonateButton.jpg',
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
		foreach ( MLAOptions::$mla_option_definitions as $key => $value ) {
			if ( 'mla-gallery' == $value['tab'] && ( 'select' == $value['type'] ) ) {
				$old_value = MLAOptions::mla_get_option( $key );
				if ( $old_value != $_REQUEST[ MLA_OPTION_PREFIX . $key ] ) {
					$settings_changed = true;
					$message_list .= self::_update_option_row( $key, $value );
				}
			}
		} // foreach mla_options
		
		/*
		 * Get the current style contents for comparison
		 */
		$old_templates = MLAOptions::mla_get_style_templates();
		$new_templates = array();
		$new_names = $_REQUEST['mla_style_templates_name'];
		$new_values = stripslashes_deep( $_REQUEST['mla_style_templates_value'] );
		$new_deletes = isset( $_REQUEST['mla_style_templates_delete'] ) ? $_REQUEST['mla_style_templates_delete']: array();
		
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
					$error_list .= "<br>ERROR: reserved name '{$new_slug}', new style template discarded.";
					continue;
				}
				
				if( array_key_exists( $new_slug, $old_templates ) ) {
					$error_list .= "<br>ERROR: duplicate name '{$new_slug}', new style template discarded.";
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
				$error_list .= "<br>ERROR: blank style template name value, reverting to '{$name}'.";
				$new_slug = $name;
			}
			
			if ( $new_slug != $name ) {
				if( array_key_exists( $new_slug, $old_templates ) ) {
					$error_list .= "<br>ERROR: duplicate new style template name '{$new_slug}', reverting to '{$name}'.";
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
			if ( false == MLAOptions::mla_put_style_templates( $new_templates ) )
				$error_list .= "<br>ERROR: update of style templates failed.";
		}
		
		/*
		 * Get the current markup contents for comparison
		 */
		$old_templates = MLAOptions::mla_get_markup_templates();
		$new_templates = array();
		$new_names = $_REQUEST['mla_markup_templates_name'];
		$new_values['open'] = stripslashes_deep( $_REQUEST['mla_markup_templates_open'] );
		$new_values['row-open'] = stripslashes_deep( $_REQUEST['mla_markup_templates_row_open'] );
		$new_values['item'] = stripslashes_deep( $_REQUEST['mla_markup_templates_item'] );
		$new_values['row-close'] = stripslashes_deep( $_REQUEST['mla_markup_templates_row_close'] );
		$new_values['close'] = stripslashes_deep( $_REQUEST['mla_markup_templates_close'] );
		$new_deletes = isset( $_REQUEST['mla_markup_templates_delete'] ) ? $_REQUEST['mla_markup_templates_delete']: array();
		
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
					$error_list .= "<br>ERROR: reserved name '{$new_slug}', new markup template discarded.";
					continue;
				}
				
				if( array_key_exists( $new_slug, $old_templates ) ) {
					$error_list .= "<br>ERROR: duplicate name '{$new_slug}', new markup template discarded.";
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
				$error_list .= "<br>ERROR: blank markup template name value, reverting to '{$name}'.";
				$new_slug = $name;
			}
			
			if ( $new_slug != $name ) {
				if( array_key_exists( $new_slug, $old_templates ) ) {
					$error_list .= "<br>ERROR: duplicate new markup template name '{$new_slug}', reverting to '{$name}'.";
					$new_slug = $name;
				}
				
				if( array_key_exists( $new_slug, $old_templates ) ) {
					$error_list .= "<br>ERROR: duplicate new markup template name '{$new_slug}', reverting to '{$name}'.";
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
			if ( false == MLAOptions::mla_put_markup_templates( $new_templates ) )
				$error_list .= "<br>ERROR: update of markup templates failed.";
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
	 * Process custom field settings against all image attachments
	 * without saving the settings to the mla_option
 	 *
	 * @since 1.10
	 * @uses $_REQUEST if passed a NULL parameter
	 *
	 * @param	array | NULL	specific custom_field_mapping values 
	 *
	 * @return	array	Message(s) reflecting the results of the operation
	 */
	private static function _process_custom_field_mapping( $settings = NULL ) {
		global $wpdb;
		
		if ( NULL == $settings ) {
			$settings = ( isset( $_REQUEST['custom_field_mapping'] ) ) ? $_REQUEST['custom_field_mapping'] : array();
			if ( isset( $settings[ MLAOptions::MLA_NEW_CUSTOM_FIELD ] ) )
				unset( $settings[ MLAOptions::MLA_NEW_CUSTOM_FIELD ] );
			if ( isset( $settings[ MLAOptions::MLA_NEW_CUSTOM_RULE ] ) )
				unset( $settings[ MLAOptions::MLA_NEW_CUSTOM_RULE ] );
		}
		
		if ( empty( $settings ) )
			return array(
				'message' => 'ERROR: No custom field mapping rules to process.',
				'body' => '' 
			);

		$examine_count = 0;
		$update_count = 0;
		$post_ids = $wpdb->get_col( "SELECT ID FROM {$wpdb->posts} WHERE `post_type` = 'attachment'" );

		foreach( $post_ids as $key => $post_id ) {
			$updates = MLAOptions::mla_evaluate_custom_field_mapping( (integer) $post_id, 'custom_field_mapping', $settings );

			$examine_count += 1;
			if ( ! empty( $updates ) ) {
				$results = MLAData::mla_update_single_item( (integer) $post_id, $updates );
				if ( stripos( $results['message'], 'updated.' ) )
					$update_count += 1;
			}
		} // foreach post
		
		if ( $update_count )
			$message = "Custom field mapping completed; {$examine_count} attachment(s) examined, {$update_count} updated.\r\n";
		else
			$message = "Custom field mapping completed; {$examine_count} attachment(s) examined, no changes detected.\r\n";
		
		return array(
			'message' => $message,
			'body' => '' 
		);
	} // _process_custom_field_mapping
	
	/**
	 * Delete a custom field from the wp_postmeta table
 	 *
	 * @since 1.10
	 *
	 * @param	array specific custom_field_mapping rule
	 *
	 * @return	array	Message(s) reflecting the results of the operation
	 */
	private static function _delete_custom_field( $value ) {
		global $wpdb;

		$post_meta_ids = $wpdb->get_col( $wpdb->prepare( "SELECT meta_id FROM {$wpdb->postmeta} LEFT JOIN {$wpdb->posts} ON ( {$wpdb->posts}.ID = {$wpdb->postmeta}.post_id ) WHERE {$wpdb->postmeta}.meta_key = '%s' AND {$wpdb->posts}.post_type = 'attachment'", $value['name'] ));
		foreach ( $post_meta_ids as $mid )
			delete_metadata_by_mid( 'post', $mid );

		$count = count( $post_meta_ids );
		if ( $count )
			return sprintf( 'Deleted custom field value from ' . _n('%s attachment.<br>', '%s attachments.<br>', $count), $count);
		else
			return 'No attachments contained this custom field.<br>';
	} // _delete_custom_field
	
	/**
	 * Save custom field settings to the options table
 	 *
	 * @since 1.10
	 * @uses $_REQUEST if passed a NULL parameter
	 *
	 * @param	array | NULL	specific custom_field_mapping values 
	 *
	 * @return	array	Message(s) reflecting the results of the operation
	 */
	private static function _save_custom_field_settings( $new_values = NULL ) {
		$message_list = '';
		$option_messages = '';

		if ( NULL == $new_values ) {
			/*
			 * Start with any page-level options
			 */
			foreach ( MLAOptions::$mla_option_definitions as $key => $value ) {
				if ( 'custom-field' == $value['tab'] )
					$option_messages .= self::_update_option_row( $key, $value );
			}
	
			/*
			 * Add mapping options
			 */
			$new_values = ( isset( $_REQUEST['custom_field_mapping'] ) ) ? $_REQUEST['custom_field_mapping'] : array();
		} // NULL

		/*
		 * Uncomment this for debugging.
		 */
		// $message_list = $option_messages . '<br>';
		
		return array(
			'message' => $message_list . MLAOptions::mla_custom_field_option_handler( 'update', 'custom_field_mapping', MLAOptions::$mla_option_definitions['custom_field_mapping'], $new_values ),
			'body' => '' 
		);
	} // _save_custom_field_settings
	
	/**
	 * Process IPTC/EXIF standard field settings against all image attachments
	 * without saving the settings to the mla_option
 	 *
	 * @since 1.00
	 *
	 * @uses $_REQUEST
	 *
	 * @return	array	Message(s) reflecting the results of the operation
	 */
	private static function _process_iptc_exif_standard( ) {
		if ( ! isset( $_REQUEST['iptc_exif_mapping']['standard'] ) )
			return array(
				'message' => 'ERROR: No standard field settings to process.',
				'body' => '' 
			);

		$examine_count = 0;
		$update_count = 0;
		
		$query = array( 'orderby' => 'none', 'post_parent' => 'all' );
		$posts = MLAShortcodes::mla_get_shortcode_attachments( 0, $query );
		
		foreach( $posts as $key => $post ) {
			$updates = MLAOptions::mla_evaluate_iptc_exif_mapping( $post, 'iptc_exif_standard_mapping', $_REQUEST['iptc_exif_mapping'] );

			$examine_count += 1;
			if ( ! empty( $updates ) ) {
				MLAData::mla_update_single_item( $post->ID, $updates );
				$update_count += 1;
			}
		} // foreach post
		
		if ( $update_count )
			$message = "IPTC/EXIF Standard field mapping completed; {$examine_count} attachment(s) examined, {$update_count} updated.\r\n";
		else
			$message = "IPTC/EXIF Standard field mapping completed; {$examine_count} attachment(s) examined, no changes detected.\r\n";
		
		return array(
			'message' => $message,
			'body' => '' 
		);
	} // _process_iptc_exif_standard
	
	/**
	 * Process IPTC/EXIF taxonomy term settings against all image attachments
	 * without saving the settings to the mla_option
 	 *
	 * @since 1.00
	 *
	 * @uses $_REQUEST
	 *
	 * @return	array	Message(s) reflecting the results of the operation
	 */
	private static function _process_iptc_exif_taxonomy( ) {
		if ( ! isset( $_REQUEST['iptc_exif_mapping']['taxonomy'] ) )
			return array(
				'message' => 'ERROR: No taxonomy term settings to process.',
				'body' => '' 
			);

		$examine_count = 0;
		$update_count = 0;
		
		$query = array( 'orderby' => 'none', 'post_parent' => 'all' );
		$posts = MLAShortcodes::mla_get_shortcode_attachments( 0, $query );
		
		foreach( $posts as $key => $post ) {
			$updates = MLAOptions::mla_evaluate_iptc_exif_mapping( $post, 'iptc_exif_taxonomy_mapping', $_REQUEST['iptc_exif_mapping'] );

			$examine_count += 1;
			if ( ! empty( $updates ) ) {
				$results = MLAData::mla_update_single_item( $post->ID, array(), $updates['taxonomy_updates']['inputs'], $updates['taxonomy_updates']['actions'] );
				if ( stripos( $results['message'], 'updated.' ) )
					$update_count += 1;
			}
		} // foreach post
		
		if ( $update_count )
			$message = "IPTC/EXIF Taxonomy term mapping completed; {$examine_count} attachment(s) examined, {$update_count} updated.\r\n";
		else
			$message = "IPTC/EXIF Taxonomy term mapping completed; {$examine_count} attachment(s) examined, no changes detected.\r\n";
		
		return array(
			'message' => $message,
			'body' => '' 
		);
	} // _process_iptc_exif_taxonomy
	
	/**
	 * Process IPTC/EXIF custom field settings against all image attachments
	 * without saving the settings to the mla_option
 	 *
	 * @since 1.00
	 *
	 * @uses $_REQUEST if passed a NULL parameter
	 *
	 * @param	array | NULL	specific iptc_exif_custom_mapping values 
	 *
	 * @return	array	Message(s) reflecting the results of the operation
	 */
	private static function _process_iptc_exif_custom( $settings = NULL ) {
		if ( NULL == $settings ) {
			$settings = ( isset( $_REQUEST['iptc_exif_mapping'] ) ) ? $_REQUEST['iptc_exif_mapping'] : array();
			if ( isset( $settings['custom'][ MLAOptions::MLA_NEW_CUSTOM_FIELD ] ) )
				unset( $settings['custom'][ MLAOptions::MLA_NEW_CUSTOM_FIELD ] );
			if ( isset( $settings['custom'][ MLAOptions::MLA_NEW_CUSTOM_RULE ] ) )
				unset( $settings['custom'][ MLAOptions::MLA_NEW_CUSTOM_RULE ] );
		}
		
		if ( empty( $settings['custom'] ) )
			return array(
				'message' => 'ERROR: No custom field settings to process.',
				'body' => '' 
			);

		$examine_count = 0;
		$update_count = 0;
		
		$query = array( 'orderby' => 'none', 'post_parent' => 'all' );
		$posts = MLAShortcodes::mla_get_shortcode_attachments( 0, $query );
		
		foreach( $posts as $key => $post ) {
			$updates = MLAOptions::mla_evaluate_iptc_exif_mapping( $post, 'iptc_exif_custom_mapping', $settings );

			$examine_count += 1;
			if ( ! empty( $updates ) ) {
				$results = MLAData::mla_update_single_item( $post->ID, $updates );
				if ( stripos( $results['message'], 'updated.' ) )
					$update_count += 1;
			}
		} // foreach post
		
		if ( $update_count )
			$message = "IPTC/EXIF custom field mapping completed; {$examine_count} attachment(s) examined, {$update_count} updated.\r\n";
		else
			$message = "IPTC/EXIF custom field mapping completed; {$examine_count} attachment(s) examined, no changes detected.\r\n";
		
		return array(
			'message' => $message,
			'body' => '' 
		);
	} // _process_iptc_exif_custom
	
	/**
	 * Save IPTC/EXIF custom field settings to the options table
 	 *
	 * @since 1.30
	 *
	 * @param	array	specific iptc_exif_custom_mapping values 
	 *
	 * @return	array	Message(s) reflecting the results of the operation
	 */
	private static function _save_iptc_exif_custom_settings( $new_values ) {
		return array(
			'message' => MLAOptions::mla_iptc_exif_option_handler( 'update', 'iptc_exif_custom_mapping', MLAOptions::$mla_option_definitions['iptc_exif_mapping'], $new_values ),
			'body' => '' 
		);
	} // _save_iptc_exif_custom_settings
	
	/**
	 * Save IPTC/EXIF settings to the options table
 	 *
	 * @since 1.00
	 *
	 * @uses $_REQUEST
	 *
	 * @return	array	Message(s) reflecting the results of the operation
	 */
	private static function _save_iptc_exif_settings( ) {
		$message_list = '';
		$option_messages = '';

		/*
		 * Start with any page-level options
		 */
		foreach ( MLAOptions::$mla_option_definitions as $key => $value ) {
			if ( 'iptc-exif' == $value['tab'] )
				$option_messages .= self::_update_option_row( $key, $value );
		}

		/*
		 * Uncomment this for debugging.
		 */
		// $message_list = $option_messages . '<br>';
		
		/*
		 * Add mapping options
		 */
		$new_values = ( isset( $_REQUEST['iptc_exif_mapping'] ) ) ? $_REQUEST['iptc_exif_mapping'] : array( 'standard' => array(), 'taxonomy' => array(), 'custom' => array() );

		return array(
			'message' => $message_list . MLAOptions::mla_iptc_exif_option_handler( 'update', 'iptc_exif_mapping', MLAOptions::$mla_option_definitions['iptc_exif_mapping'], $new_values ),
			'body' => '' 
		);
	} // _save_iptc_exif_settings
	
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
		
		foreach ( MLAOptions::$mla_option_definitions as $key => $value ) {
			if ( 'general' == $value['tab'] ) {
				switch ( $key ) {
					case MLAOptions::MLA_FEATURED_IN_TUNING:
						MLAOptions::$process_featured_in = ( 'disabled' != $_REQUEST[ MLA_OPTION_PREFIX . $key ] );
						break;
					case MLAOptions::MLA_INSERTED_IN_TUNING:
						MLAOptions::$process_inserted_in = ( 'disabled' != $_REQUEST[ MLA_OPTION_PREFIX . $key ] );
						break;
					case MLAOptions::MLA_GALLERY_IN_TUNING:
						MLAOptions::$process_gallery_in = ( 'disabled' != $_REQUEST[ MLA_OPTION_PREFIX . $key ] );
						
						if ( 'refresh' == $_REQUEST[ MLA_OPTION_PREFIX . $key ] ) {
							MLAData::mla_flush_mla_galleries( MLAOptions::MLA_GALLERY_IN_TUNING );
							$message_list .= "<br>Gallery in - references updated.\r\n";
							$_REQUEST[ MLA_OPTION_PREFIX . $key ] = 'cached';
						}
						break;
					case MLAOptions::MLA_MLA_GALLERY_IN_TUNING:
						MLAOptions::$process_mla_gallery_in = ( 'disabled' != $_REQUEST[ MLA_OPTION_PREFIX . $key ] );
						
						if ( 'refresh' == $_REQUEST[ MLA_OPTION_PREFIX . $key ] ) {
							MLAData::mla_flush_mla_galleries( MLAOptions::MLA_MLA_GALLERY_IN_TUNING );
							$message_list .= "<br>MLA Gallery in - references updated.\r\n";
							$_REQUEST[ MLA_OPTION_PREFIX . $key ] = 'cached';
						}
						break;
					default:
						//	ignore everything else
				} // switch

				$message_list .= self::_update_option_row( $key, $value );
			} // general option
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
		
		foreach ( MLAOptions::$mla_option_definitions as $key => $value ) {
			if ( 'custom' == $value['type'] ) {
				$message = MLAOptions::$value['reset']( 'reset', $key, $value, $_REQUEST );
			}
			elseif ( ('header' == $value['type']) || ('hidden' == $value['type']) ) {
				$message = '';
			}
			else {
				MLAOptions::mla_delete_option( $key );
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
} // class MLASettings
?>