<?php
/**
 * Manages the plugin option settings
 *
 * @package Media Library Assistant
 * @since 1.00
 */

/**
 * Class MLA (Media Library Assistant) Options manages the plugin option settings
 * and provides functions to get and put them from/to WordPress option variables
 *
 * Separated from class MLASettings in version 1.00
 *
 * @package Media Library Assistant
 * @since 1.00
 */
class MLAOptions {
	/**
	 * Provides a unique name for the current version option
	 */
	const MLA_VERSION_OPTION = 'current_version';
	
	/**
	 * Provides a unique name for a database tuning option
	 */
	const MLA_FEATURED_IN_TUNING = 'featured_in_tuning';

	/**
	 * Provides a unique name for a database tuning option
	 */
	const MLA_INSERTED_IN_TUNING = 'inserted_in_tuning';

	/**
	 * Provides a unique name for a database tuning option
	 */
	const MLA_GALLERY_IN_TUNING = 'gallery_in_tuning';

	/**
	 * Provides a unique name for a database tuning option
	 */
	const MLA_MLA_GALLERY_IN_TUNING = 'mla_gallery_in_tuning';
	
	/**
	 * Provides a unique name for the Custom Field "new field" key
	 */
	const MLA_NEW_CUSTOM_FIELD = '__NEW FIELD__';
	
	/**
	 * Option setting for "Featured in" reporting
	 *
	 * This setting is false if the "Featured in" database access setting is "disabled", else true.
	 *
	 * @since 1.00
	 *
	 * @var	boolean
	 */
	public static $process_featured_in = true;

	/**
	 * Option setting for "Inserted in" reporting
	 *
	 * This setting is false if the "Inserted in" database access setting is "disabled", else true.
	 *
	 * @since 1.00
	 *
	 * @var	boolean
	 */
	public static $process_inserted_in = true;

	/**
	 * Option setting for "Gallery in" reporting
	 *
	 * This setting is false if the "Gallery in" database access setting is "disabled", else true.
	 *
	 * @since 1.00
	 *
	 * @var	boolean
	 */
	public static $process_gallery_in = true;

	/**
	 * Option setting for "MLA Gallery in" reporting
	 *
	 * This setting is false if the "MLA Gallery in" database access setting is "disabled", else true.
	 *
	 * @since 1.00
	 *
	 * @var	boolean
	 */
	public static $process_mla_gallery_in = true;

	/**
	 * $mla_option_definitions defines the database options and admin page areas for setting/updating them.
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
	public static $mla_option_definitions = array (
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
	
		'where_used_subheading' =>
			array('tab' => 'general',
				'name' => 'Where-used database access tuning',
				'type' => 'subheader'),
		
		self::MLA_FEATURED_IN_TUNING =>
			array('tab' => 'general',
				'name' => 'Featured in',
				'type' => 'select',
				'std' => 'enabled',
				'options' => array('enabled', 'disabled'),
				'texts' => array('Enabled', 'Disabled'),
				'help' => 'Search database posts and pages for Featured Image attachments.'),
	
		self::MLA_INSERTED_IN_TUNING =>
			array('tab' => 'general',
				'name' => 'Inserted in',
				'type' => 'select',
				'std' => 'enabled',
				'options' => array('enabled', 'disabled'),
				'texts' => array('Enabled', 'Disabled'),
				'help' => 'Search database posts and pages for attachments embedded in content.'),
	
		self::MLA_GALLERY_IN_TUNING =>
			array('tab' => 'general',
				'name' => 'Gallery in',
				'type' => 'select',
				'std' => 'cached',
				'options' => array('dynamic', 'refresh', 'cached', 'disabled'),
				'texts' => array('Dynamic', 'Refresh', 'Cached', 'Disabled'),
				'help' => 'Search database posts and pages for [gallery] shortcode results.<br>&nbsp;&nbsp;Dynamic = once every page load, Cached = once every login, Disabled = never.<br>&nbsp;&nbsp;Refresh = update references, then set to Cached.'),
	
		self::MLA_MLA_GALLERY_IN_TUNING =>
			array('tab' => 'general',
				'name' => 'MLA Gallery in',
				'type' => 'select',
				'std' => 'cached',
				'options' => array('dynamic', 'refresh', 'cached', 'disabled'),
				'texts' => array('Dynamic', 'Refresh', 'Cached', 'Disabled'),
				'help' => 'Search database posts and pages for [mla_gallery] shortcode results.<br>&nbsp;&nbsp;Dynamic = once every page load, Cached = once every login, Disabled = never.<br>&nbsp;&nbsp;Refresh = update references, then set to Cached.'),
	
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
				'render' => 'mla_taxonomy_option_handler',
				'update' => 'mla_taxonomy_option_handler',
				'delete' => 'mla_taxonomy_option_handler',
				'reset' => 'mla_taxonomy_option_handler'),
	
		'orderby_heading' =>
			array('tab' => 'general',
				'name' => 'Default Table Listing Sort Order',
				'type' => 'header'),
		
		'default_orderby' =>
			array('tab' => 'general',
				'name' => 'Order By',
				'type' => 'select',
				'std' => 'title_name',
				'options' => array('none', 'title_name'),
				'texts' => array('None', 'Title/Name'),
				'help' => 'Select the column for the sort order of the Assistant table listing.'),
	
		'default_order' =>
			array('tab' => 'general',
				'name' => 'Order',
				'type' => 'radio',
				'std' => 'ASC',
				'options' => array('ASC', 'DESC'),
				'texts' => array('Ascending', 'Descending'),
				'help' => 'Choose the sort order.'),

		'template_heading' =>
			array('tab' => 'mla-gallery',
				'name' => 'Default [mla_gallery] Templates',
				'type' => 'header'),
		
		'default_style' =>
			array('tab' => 'mla-gallery',
				'name' => 'Style Template',
				'type' => 'select',
				'std' => 'default',
				'options' => array(),
				'texts' => array(),
				'help' => 'Select the default style template for your [mla_gallery] shortcodes.'),
	
		'default_markup' =>
			array('tab' => 'mla-gallery',
				'name' => 'Markup Template',
				'type' => 'select',
				'std' => 'default',
				'options' => array(),
				'texts' => array(),
				'help' => 'Select the default markup template for your [mla_gallery] shortcodes.'),
	
		/*
		 * Managed by mla_get_style_templates and mla_put_style_templates
		 */
		'style_templates' =>
			array('tab' => '',
				'type' => 'hidden',
				'std' => array( )),
	
		/*
		 * Managed by mla_get_markup_templates and mla_put_markup_templates
		 */
		'markup_templates' =>
			array('tab' => '',
				'type' => 'hidden',
				'std' => array( )),
	
		'enable_iptc_exif_mapping' =>
			array('tab' => 'iptc-exif',
				'name' => 'Enable IPTC/EXIF Mapping when adding new media',
				'type' => 'checkbox',
				'std' => '',
				'help' => 'Check this option to enable mapping when uploading new media (attachments).<br>&nbsp;&nbsp;Does NOT affect the operation of the "Map" buttons on the bulk edit, single edit and settings screens.'),
		
		'iptc_exif_standard_mapping' =>
			array('tab' => '',
				'help' => 'Update the standard field mapping values above, then click Save Changes to make the updates permanent.<br>You can also make temporary updates and click Map All Attachments Now to apply the updates to all attachments without saving the rule changes.',
				'std' =>  NULL, 
				'type' => 'custom',
				'render' => 'mla_iptc_exif_option_handler',
				'update' => 'mla_iptc_exif_option_handler',
				'delete' => 'mla_iptc_exif_option_handler',
				'reset' => 'mla_iptc_exif_option_handler'),
	
		'iptc_exif_taxonomy_mapping' =>
			array('tab' => '',
				'help' => 'Update the taxonomy term mapping values above, then click Save Changes or Map All Attachments Now.',
				'std' =>  NULL, 
				'type' => 'custom',
				'render' => 'mla_iptc_exif_option_handler',
				'update' => 'mla_iptc_exif_option_handler',
				'delete' => 'mla_iptc_exif_option_handler',
				'reset' => 'mla_iptc_exif_option_handler'),
	
		'iptc_exif_custom_mapping' =>
			array('tab' => '',
				'help' => 'Update the custom field mapping values above.<br>To define a new custom field, enter a field name in the "Field Title" text box at the end of the list and Save Changes.',
				'std' =>  NULL, 
				'type' => 'custom',
				'render' => 'mla_iptc_exif_option_handler',
				'update' => 'mla_iptc_exif_option_handler',
				'delete' => 'mla_iptc_exif_option_handler',
				'reset' => 'mla_iptc_exif_option_handler'),
	
		'iptc_exif_mapping' =>
			array('tab' => '',
				'help' => 'IPTC/EXIF Mapping help',
				'std' =>  array (
					'standard' => array (
				    	'post_title' => array (
							'name' => 'Title',
							'iptc_value' => 'none',
							'exif_value' => '',
							'iptc_first' => true,
							'keep_existing' => true
						),
				    	'post_name' => array (
							'name' => 'Name/Slug',
							'iptc_value' => 'none',
							'exif_value' => '',
							'iptc_first' => true,
							'keep_existing' => true
						),
				    	'image_alt' => array (
							'name' => 'Alternate Text',
							'iptc_value' => 'none',
							'exif_value' => '',
							'iptc_first' => true,
							'keep_existing' => true
						),
				    	'post_excerpt' => array (
							'name' => 'Caption',
							'iptc_value' => 'none',
							'exif_value' => '',
							'iptc_first' => true,
							'keep_existing' => true
						),
				    	'post_content' => array (
							'name' => 'Description',
							'iptc_value' => 'none',
							'exif_value' => '',
							'iptc_first' => true,
							'keep_existing' => true
						),
					),
					'taxonomy' => array (
					),
					'custom' => array (
					)
					), 
				'type' => 'custom',
				'render' => 'mla_iptc_exif_option_handler',
				'update' => 'mla_iptc_exif_option_handler',
				'delete' => 'mla_iptc_exif_option_handler',
				'reset' => 'mla_iptc_exif_option_handler'),
	
		/* Here are examples of the other option types
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
	 * @since 1.00
	 *
	 * @return	void
	 */
	public static function initialize( ) {
		self::_load_option_templates();

		if ( 'disabled' == self::mla_get_option( self::MLA_FEATURED_IN_TUNING ) )
			self::$process_featured_in = false;
		if ( 'disabled' == self::mla_get_option( self::MLA_INSERTED_IN_TUNING ) )
			self::$process_inserted_in = false;
		if ( 'disabled' == self::mla_get_option( self::MLA_GALLERY_IN_TUNING ) )
			self::$process_gallery_in = false;
		if ( 'disabled' == self::mla_get_option( self::MLA_MLA_GALLERY_IN_TUNING ) )
			self::$process_mla_gallery_in = false;
			
		if ( 'checked' == MLAOptions::mla_get_option( 'enable_iptc_exif_mapping' ) )
			add_action( 'add_attachment', 'MLAOptions::mla_add_attachment_action' );
	}
	
	/**
	 * Style and Markup templates
	 *
	 * @since 0.80
	 *
	 * @var	array
	 */
	private static $mla_option_templates = null;
	
	/**
	 * Load style and markup templates to $mla_templates
	 *
	 * @since 0.80
	 *
	 * @return	void
	 */
	private static function _load_option_templates() {
		self::$mla_option_templates = MLAData::mla_load_template( MLA_PLUGIN_PATH . 'tpls/mla-option-templates.tpl' );

		/* 	
		 * Load the default templates
		 */
		if( is_null( self::$mla_option_templates ) ) {
			MLAShortcodes::$mla_debug_messages .= '<p><strong>_load_option_templates()</strong> error loading tpls/mla-option-templates.tpl';
			return;
		}
		elseif( !self::$mla_option_templates ) {
			MLAShortcodes::$mla_debug_messages .= '<p><strong>_load_option_templates()</strong>tpls/mla-option-templates.tpl not found';
			$mla_option_templates = null;
			return;
		}

		/*
		 * Add user-defined Style and Markup templates
		 */
		$templates = self::mla_get_option( 'style_templates' );
		if ( is_array(	$templates ) ) {
			foreach ( $templates as $name => $value ) {
				self::$mla_option_templates[ $name . '-style' ] = $value;
			} // foreach $templates
		} // is_array

		$templates = self::mla_get_option( 'markup_templates' );
		if ( is_array(	$templates ) ) {
			foreach ( $templates as $name => $value ) {
				self::$mla_option_templates[ $name . '-open-markup' ] = $value['open'];
				self::$mla_option_templates[ $name . '-row-open-markup' ] = $value['row-open'];
				self::$mla_option_templates[ $name . '-item-markup' ] = $value['item'];
				self::$mla_option_templates[ $name . '-row-close-markup' ] = $value['row-close'];
				self::$mla_option_templates[ $name . '-close-markup' ] = $value['close'];
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
		if ( ! is_array( self::$mla_option_templates ) ) {
			MLAShortcodes::$mla_debug_messages .= '<p><strong>_fetch_template()</strong> no templates exist';
			return null;
		}
		
		$array_key = $key . '-' . $type;
		if ( array_key_exists( $array_key, self::$mla_option_templates ) )
			return self::$mla_option_templates[ $array_key ];
		else {
			MLAShortcodes::$mla_debug_messages .= "<p><strong>_fetch_template( {$key}, {$type} )</strong> not found";
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
	public static function mla_get_style_templates() {
		if ( ! is_array( self::$mla_option_templates ) ) {
			MLAShortcodes::$mla_debug_messages .= '<p><strong>_fetch_template()</strong> no templates exist';
			return null;
		}
		
		$templates = array( );
		foreach ( self::$mla_option_templates as $key => $value ) {
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
	public static function mla_put_style_templates( $templates ) {
		if ( self::mla_update_option( 'style_templates', $templates ) ) {
			self::_load_option_templates();
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
	public static function mla_get_markup_templates() {
		if ( ! is_array( self::$mla_option_templates ) ) {
			MLAShortcodes::$mla_debug_messages .= '<p><strong>_fetch_template()</strong> no templates exist';
			return null;
		}
		
		$templates = array( );
		foreach ( self::$mla_option_templates as $key => $value ) {
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
	public static function mla_put_markup_templates( $templates ) {
		if ( self::mla_update_option( 'markup_templates', $templates ) ) {
			self::_load_option_templates();
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
		if ( array_key_exists( $option, self::$mla_option_definitions ) ) {
			if ( array_key_exists( 'std', self::$mla_option_definitions[ $option ] ) )
				return get_option( MLA_OPTION_PREFIX . $option, self::$mla_option_definitions[ $option ]['std'] );
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
		if ( array_key_exists( $option, self::$mla_option_definitions ) )
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
		if ( array_key_exists( $option, self::$mla_option_definitions ) ) {
			return delete_option( MLA_OPTION_PREFIX . $option );
		}
		
		return false;
	}
	
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
		$tax_options =  MLAOptions::mla_get_option( 'taxonomy_support' );
		
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
	 * Render and manage taxonomy support options, e.g., Categories and Post Tags
 	 *
	 * @since 0.30
	 * @uses $mla_option_templates contains taxonomy-row and taxonomy-table templates
	 *
	 * @param	string 	'render', 'update', 'delete', or 'reset'
	 * @param	string 	option name, e.g., 'taxonomy_support'
	 * @param	array 	option parameters
	 * @param	array 	Optional. null (default) for 'render' else option data, e.g., $_REQUEST
	 *
	 * @return	string	HTML table row markup for 'render' else message(s) reflecting the results of the operation.
	 */
	public static function mla_taxonomy_option_handler( $action, $key, $value, $args = null ) {
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

				$taxonomy_row = self::$mla_option_templates['taxonomy-row'];
				$row = '';
				
				foreach ( $taxonomies as $tax_name => $tax_object ) {
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
				
				return MLAData::mla_parse_template( self::$mla_option_templates['taxonomy-table'], $option_values );
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

				foreach ( $tax_quick_edit as $tax_name => $tax_value ) {				
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
				return "<br>ERROR: custom {$key} unknown action: {$action}\r\n";
		}
	} // mla_taxonomy_option_handler
	
	/**
	 * Perform ITC/EXIF mapping on just-inserted attachment
 	 *
	 * @since 1.00
	 *
	 * @param	integer	ID of just-inserted attachment
	 *
	 * @return	void
	 */
	public static function mla_add_attachment_action( $post_id ) {
		$item = get_post( $post_id );
		$updates = MLAOptions::mla_evaluate_iptc_exif_mapping( $item, 'iptc_exif_mapping' );
		$item_content = MLAData::mla_update_single_item( $post_id, $updates );
	} // mla_taxonomy_option_handler
	
	/**
	 * Evaluate IPTC/EXIF mapping updates for a post
 	 *
	 * @since 1.00
	 *
	 * @param	object 	post object with current values
	 * @param	string 	category to evaluate against, e.g., iptc_exif_standard_mapping or iptc_exif_mapping
	 * @param	array 	(optional) iptc_exif_mapping values, default - current option value
	 *
	 * @return	array	Updates suitable for MLAData::mla_update_single_item, if any
	 */
	public static function mla_evaluate_iptc_exif_mapping( $post, $category, $settings = NULL ) {
// error_log( '$post = ' . var_export( $post->post_name, true ) , 0 );
		$metadata = MLAData::mla_fetch_attachment_image_metadata( $post->ID );
// error_log( '$metadata = ' . var_export( $metadata, true ) , 0 );
		$iptc_metadata = $metadata['mla_iptc_metadata'];
		$exif_metadata = $metadata['mla_exif_metadata'];
		$updates = array( );
		$update_all = ( 'iptc_exif_mapping' == $category );
		if ( NULL == $settings )
			$settings = self::mla_get_option( 'iptc_exif_mapping' );

		if ( $update_all || ( 'iptc_exif_standard_mapping' == $category ) ) {}{
			foreach( $settings['standard'] as $new_key => $new_value ) {
				$iptc_value = ( isset( $iptc_metadata[ $new_value['iptc_value'] ] ) ) ? $iptc_metadata[ $new_value['iptc_value'] ] : '';
				$exif_value = ( isset( $exif_metadata[ $new_value['exif_value'] ] ) ) ? $exif_metadata[ $new_value['exif_value'] ] : '';
				$keep_existing = (boolean) $new_value['keep_existing'];
				
				if ( $new_value['iptc_first'] )
					if ( ! empty( $iptc_value ) )
						$new_text = $iptc_value;
					else
						$new_text = $exif_value;
				else
					if ( ! empty( $exif_value ) )
						$new_text = $exif_value;
					else
						$new_text = $iptc_value;

				$new_text = trim( convert_chars( $new_text ) );
				if ( !empty( $new_text ) )
					switch ( $new_key ) {
						case 'post_title':
							if ( ( empty( $post->post_title ) || !$keep_existing ) &&
							( trim( $new_text ) && ! is_numeric( sanitize_title( $new_text ) ) ) )
								$updates[ $new_key ] = $new_text;
							break;
						case 'post_name':
							$updates[ $new_key ] = wp_unique_post_slug( sanitize_title( $new_text ), $post->ID, $post->post_status, $post->post_type, $post->post_parent);
							break;
						case 'image_alt':
							$old_text = get_metadata( 'post', $post->ID, '_wp_attachment_image_alt', true );
							if ( empty( $old_text ) || !$keep_existing ) {
								$updates[ $new_key ] = $new_text;							}
							break;
						case 'post_excerpt':
							if ( empty( $post->post_excerpt ) || !$keep_existing )
								$updates[ $new_key ] = $new_text;
							break;
						case 'post_content':
							if ( empty( $post->post_content ) || !$keep_existing )
								$updates[ $new_key ] = $new_text;
							break;
						default:
							// ignore anything else
					} // $new_key
			} // foreach new setting
		} // update standard field mappings
		
		if ( $update_all || ( 'iptc_exif_taxonomy_mapping' == $category ) ) {
			$tax_inputs = array();
			$tax_actions =  array();
			
			foreach( $settings['taxonomy'] as $new_key => $new_value ) {
				$iptc_value = ( isset( $iptc_metadata[ $new_value['iptc_value'] ] ) ) ? $iptc_metadata[ $new_value['iptc_value'] ] : '';
				$exif_value = ( isset( $exif_metadata[ $new_value['exif_value'] ] ) ) ? $exif_metadata[ $new_value['exif_value'] ] : '';
				
				$tax_action = ( $new_value['keep_existing'] ) ? 'add' : 'replace';
				$tax_parent = ( isset( $new_value['parent'] ) && (0 != (integer) $new_value['parent'] ) ) ? (integer) $new_value['parent'] : 0;

				if ( $new_value['iptc_first'] )
					if ( ! empty( $iptc_value ) )
						$new_text = $iptc_value;
					else
						$new_text = $exif_value;
				else
					if ( ! empty( $exif_value ) )
						$new_text = $exif_value;
					else
						$new_text = $iptc_value;

				if ( !empty( $new_text ) ) {
					if ( $new_value['hierarchical'] ) {
						if ( is_string( $new_text ) )
							$new_text = array( $new_text );
						
						$new_terms = array( );
						foreach( $new_text as $new_term ) {
							$term_object = term_exists( $new_term, $new_key );
							if ($term_object !== 0 && $term_object !== null)
								$new_terms[] = $term_object['term_id'];
							else {
								$term_object = wp_insert_term( $new_term, $new_key, array( 'parent' => $tax_parent ) );
								if ( isset( $term_object['term_id'] ) )
									$new_terms[] = $term_object['term_id'];
							}
						} // foreach new_term
						
						$tax_inputs[ $new_key ] = $new_terms;
					} // hierarchical
					else {
						$tax_inputs[ $new_key ] = $new_text;
					}

				$tax_actions[ $new_key ] = $tax_action;
				} // new_text
			} // foreach new setting
			
		if ( ! empty( $tax_inputs ) )
			$updates['taxonomy_updates'] = array ( 'inputs' => $tax_inputs, 'actions' => $tax_actions );
		} // update taxonomy term mappings

		if ( $update_all || ( 'iptc_exif_custom_mapping' == $category ) ) {
			$custom_updates = array();
			
			foreach( $settings['custom'] as $new_key => $new_value ) {
				$iptc_value = ( isset( $iptc_metadata[ $new_value['iptc_value'] ] ) ) ? $iptc_metadata[ $new_value['iptc_value'] ] : '';
				$exif_value = ( isset( $exif_metadata[ $new_value['exif_value'] ] ) ) ? $exif_metadata[ $new_value['exif_value'] ] : '';
				$keep_existing = (boolean) $new_value['keep_existing'];

				if ( $new_value['iptc_first'] )
					if ( ! empty( $iptc_value ) )
						$new_text = $iptc_value;
					else
						$new_text = $exif_value;
				else
					if ( ! empty( $exif_value ) )
						$new_text = $exif_value;
					else
						$new_text = $iptc_value;

				$old_text = get_metadata( 'post', $post->ID, $new_key, true );
				if ( empty( $old_text ) || !$keep_existing ) {
					$custom_updates[ $new_key ] = $new_text;
				}
			} // foreach new setting
			
			if ( ! empty( $custom_updates ) )
				$updates['custom_updates'] = $custom_updates;
		} // update custom field mappings

		return $updates;
	} // mla_evaluate_iptc_exif_mapping
	
	/**
	 * Compose an IPTC Options list with current selection
 	 *
	 * @since 1.00
	 * @uses $mla_option_templates contains row and table templates
	 *
	 * @param	string 	current selection or 'none' (default)
	 *
	 * @return	string	HTML markup with select field options
	 */
	private static function _compose_iptc_option_list( $selection = 'none' ) {
		$option_template = self::$mla_option_templates['iptc-exif-select-option'];
		$option_values = array (
			'selected' => ( 'none' == $selection ) ? 'selected="selected"' : '',
			'value' => 'none',
			'text' => ' -- None (select a value) -- '
		);
		
		$iptc_options = MLAData::mla_parse_template( $option_template, $option_values );					
		foreach ( MLAShortcodes::$mla_iptc_keys as $iptc_name => $iptc_code ) {
			$option_values = array (
				'selected' => ( $iptc_code == $selection ) ? 'selected="selected"' : '',
				'value' => $iptc_code,
				'text' => $iptc_code . ' ' . $iptc_name
			);
			
			$iptc_options .= MLAData::mla_parse_template( $option_template, $option_values );					
		} // foreach iptc_key
		
		return $iptc_options;
	} // _compose_iptc_option_list
	
	/**
	 * Compose an hierarchical taxonomy Parent options list with current selection
 	 *
	 * @since 1.00
	 * @uses $mla_option_templates contains row and table templates
	 *
	 * @param	string 	taxonomy slug
	 * @param	integer	current selection or 0 (zero, default)
	 *
	 * @return	string	HTML markup with select field options
	 */
	private static function _compose_parent_option_list( $taxonomy, $selection = 0 ) {
		$option_template = self::$mla_option_templates['iptc-exif-select-option'];
		$option_values = array (
			'selected' => ( 0 == $selection ) ? 'selected="selected"' : '',
			'value' => 0,
			'text' => ' -- None (select a value) -- '
		);
		
		$parent_options = MLAData::mla_parse_template( $option_template, $option_values );
		
		$terms = get_terms( $taxonomy, array( 'orderby' => 'name', 'get' => 'all' ) ); 
		foreach ( $terms as $term ) {
			$option_values = array (
				'selected' => ( $term->term_id == $selection ) ? 'selected="selected"' : '',
				'value' => $term->term_id,
				'text' => $term->name
			);
			
			$parent_options .= MLAData::mla_parse_template( $option_template, $option_values );					
		} // foreach iptc_key
		
		return $parent_options;
	} // _compose_parent_option_list
	
	/**
	 * Update Standard field portion of IPTC/EXIF mappings
 	 *
	 * @since 1.00
	 *
	 * @param	array 	current iptc_exif_mapping values 
	 * @param	array	new values
	 *
	 * @return	array	( 'message' => HTML message(s) reflecting results, 'values' => updated iptc_exif_mapping values, 'changed' => true if any changes detected else false )
	 */
	private static function _update_iptc_exif_standard_mapping( $current_values, $new_values ) {
		$error_list = '';
		$message_list = '';
		$settings_changed = false;

		foreach ( $new_values['standard'] as $new_key => $new_value ) {
			if ( isset( $current_values['standard'][ $new_key ] ) ) {
				$old_values = $current_values['standard'][ $new_key ];
				$any_setting_changed = false;
			}
			else {
				$error_list .= "<br>ERROR: No old values for {$new_key}.\r\n";
				continue;
			}
			
			if ( $old_values['iptc_value'] != $new_value['iptc_value'] ) {
				$any_setting_changed = true;
				$message_list .= "<br>{$old_values['name']} changing IPTC Value from {$old_values['iptc_value']} to {$new_value['iptc_value']}.\r\n";
				$old_values['iptc_value'] = $new_value['iptc_value'];
			}

			if ( $old_values['exif_value'] != $new_value['exif_value'] ) {
				$any_setting_changed = true;
				$message_list .= "<br>{$old_values['name']} changing EXIF Value from {$old_values['exif_value']} to {$new_value['exif_value']}.\r\n";
				$old_values['exif_value'] = $new_value['exif_value'];
			}

			if ( $new_value['iptc_first'] ) {
				$boolean_value = true;
				$boolean_text = 'EXIF to IPTC';
			}
			else {
				$boolean_value = false;
				$boolean_text = 'IPTC to EXIF';
			}
			if ( $old_values['iptc_first'] != $boolean_value ) {
				$any_setting_changed = true;
				$message_list .= "<br>{$old_values['name']} changing Priority Value from {$boolean_text}.\r\n";
				$old_values['iptc_first'] = $boolean_value;
			}

			if ( $new_value['keep_existing'] ) {
				$boolean_value = true;
				$boolean_text = 'Replace to Keep';
			}
			else {
				$boolean_value = false;
				$boolean_text = 'Keep to Replace';
			}
			if ( $old_values['keep_existing'] != $boolean_value ) {
				$any_setting_changed = true;
				$message_list .= "<br>{$old_values['name']} changing Existing Text Value from {$boolean_text}.\r\n";
				$old_values['keep_existing'] = $boolean_value;
			}
			
			if ( $any_setting_changed ) {
				$settings_changed = true;
				$current_values['standard'][ $new_key ] = $old_values;
			}
		} // new standard value
		
		/*
		 * Uncomment this for debugging.
		 */
		// $error_list .= $message_list;

		return array( 'message' => $error_list, 'values' => $current_values, 'changed' => $settings_changed );
	} // _update_iptc_exif_standard_mapping
	
	/**
	 * Update Taxonomy term portion of IPTC/EXIF mappings
 	 *
	 * @since 1.00
	 *
	 * @param	array 	current iptc_exif_mapping values 
	 * @param	array	new values
	 *
	 * @return	array	( 'message' => HTML message(s) reflecting results, 'values' => updated iptc_exif_mapping values, 'changed' => true if any changes detected else false )
	 */
	private static function _update_iptc_exif_taxonomy_mapping( $current_values, $new_values ) {
		$error_list = '';
		$message_list = '';
		$settings_changed = false;

		foreach ( $new_values['taxonomy'] as $new_key => $new_value ) {
			if ( isset( $current_values['taxonomy'][ $new_key ] ) ) {
				$old_values = $current_values['taxonomy'][ $new_key ];
			}
			else {
				$old_values = array(
					'name' => $new_value['name'],
					'hierarchical' => $new_value['hierarchical'],
					'iptc_value' => 'none',
					'exif_value' => '',
					'iptc_first' => true,
					'keep_existing' => true,
					'parent' => 0
				);
			}
			
			$any_setting_changed = false;
			if ( $old_values['iptc_value'] != $new_value['iptc_value'] ) {
				$any_setting_changed = true;
				$message_list .= "<br>{$old_values['name']} changing IPTC Value from {$old_values['iptc_value']} to {$new_value['iptc_value']}.\r\n";
				$old_values['iptc_value'] = $new_value['iptc_value'];
			}

			if ( $old_values['exif_value'] != $new_value['exif_value'] ) {
				$any_setting_changed = true;
				$message_list .= "<br>{$old_values['name']} changing EXIF Value from {$old_values['exif_value']} to {$new_value['exif_value']}.\r\n";
				$old_values['exif_value'] = $new_value['exif_value'];
			}

			if ( $new_value['iptc_first'] ) {
				$boolean_value = true;
				$boolean_text = 'EXIF to IPTC';
			}
			else {
				$boolean_value = false;
				$boolean_text = 'IPTC to EXIF';
			}
			if ( $old_values['iptc_first'] != $boolean_value ) {
				$any_setting_changed = true;
				$message_list .= "<br>{$old_values['name']} changing Priority Value from {$boolean_text}.\r\n";
				$old_values['iptc_first'] = $boolean_value;
			}

			if ( $new_value['keep_existing'] ) {
				$boolean_value = true;
				$boolean_text = 'Replace to Keep';
			}
			else {
				$boolean_value = false;
				$boolean_text = 'Keep to Replace';
			}
			if ( $old_values['keep_existing'] != $boolean_value ) {
				$any_setting_changed = true;
				$message_list .= "<br>{$old_values['name']} changing Existing Text Value from {$boolean_text}.\r\n";
				$old_values['keep_existing'] = $boolean_value;
			}
			
			if ( isset( $new_value['parent'] ) && ( $old_values['parent'] != $new_value['parent'] ) ) {
				$any_setting_changed = true;
				$message_list .= "<br>{$old_values['name']} changing Parent from {$old_values['parent']} to {$new_value['parent']}.\r\n";
				$old_values['parent'] = $new_value['parent'];
			}

			if ( $any_setting_changed ) {
				$settings_changed = true;
				$current_values['taxonomy'][ $new_key ] = $old_values;
			}
		} // new taxonomy value

		/*
		 * Uncomment this for debugging.
		 */
		// $error_list .= $message_list;

		return array( 'message' => $error_list, 'values' => $current_values, 'changed' => $settings_changed );
	} // _update_iptc_exif_taxonomy_mapping
	
	/**
	 * Update Custom field portion of IPTC/EXIF mappings
 	 *
	 * @since 1.00
	 *
	 * @param	array 	current iptc_exif_mapping values 
	 * @param	array	new values
	 *
	 * @return	array	( 'message' => HTML message(s) reflecting results, 'values' => updated iptc_exif_mapping values, 'changed' => true if any changes detected else false )
	 */
	private static function _update_iptc_exif_custom_mapping( $current_values, $new_values ) {
		$error_list = '';
		$message_list = '';
		$settings_changed = false;

		foreach ( $new_values['custom'] as $new_key => $new_value ) {
			$any_setting_changed = false;
			
			/*
			 * Check for the addition of a new field
			 */
			if ( self::MLA_NEW_CUSTOM_FIELD == $new_key ) {
				$new_key = trim( $new_value['name'] );

				if ( empty( $new_key ) )
					continue;

				$message_list .= "<br>Adding new field {$new_key}.\r\n";
				$any_setting_changed = true;
			}
			
			if ( isset( $current_values['custom'][ $new_key ] ) ) {
				$old_values = $current_values['custom'][ $new_key ];
				$any_setting_changed = false;
			}
			else {
				$old_values = array(
					'name' => $new_key,
					'iptc_value' => 'none',
					'exif_value' => '',
					'iptc_first' => true,
					'keep_existing' => true
				);
			}
			
			if ( $old_values['iptc_value'] != $new_value['iptc_value'] ) {
				$any_setting_changed = true;
				$message_list .= "<br>{$old_values['name']} changing IPTC Value from {$old_values['iptc_value']} to {$new_value['iptc_value']}.\r\n";
				$old_values['iptc_value'] = $new_value['iptc_value'];
			}

			if ( $old_values['exif_value'] != $new_value['exif_value'] ) {
				$any_setting_changed = true;
				$message_list .= "<br>{$old_values['name']} changing EXIF Value from {$old_values['exif_value']} to {$new_value['exif_value']}.\r\n";
				$old_values['exif_value'] = $new_value['exif_value'];
			}

			if ( $new_value['iptc_first'] ) {
				$boolean_value = true;
				$boolean_text = 'EXIF to IPTC';
			}
			else {
				$boolean_value = false;
				$boolean_text = 'IPTC to EXIF';
			}
			if ( $old_values['iptc_first'] != $boolean_value ) {
				$any_setting_changed = true;
				$message_list .= "<br>{$old_values['name']} changing Priority Value from {$boolean_text}.\r\n";
				$old_values['iptc_first'] = $boolean_value;
			}

			if ( $new_value['keep_existing'] ) {
				$boolean_value = true;
				$boolean_text = 'Replace to Keep';
			}
			else {
				$boolean_value = false;
				$boolean_text = 'Keep to Replace';
			}
			if ( $old_values['keep_existing'] != $boolean_value ) {
				$any_setting_changed = true;
				$message_list .= "<br>{$old_values['name']} changing Existing Text Value from {$boolean_text}.\r\n";
				$old_values['keep_existing'] = $boolean_value;
			}
			
			if ( $any_setting_changed ) {
				$settings_changed = true;
				$current_values['custom'][ $new_key ] = $old_values;
			}
		} // new standard value
		
		/*
		 * Uncomment this for debugging.
		 */
		$error_list .= $message_list;

		return array( 'message' => $error_list, 'values' => $current_values, 'changed' => $settings_changed );
	} // _update_iptc_exif_custom_mapping
	
	/**
	 * Generate a list of all (post) Custom Field names
 	 *
	 * @since 1.00
	 *
	 * @return	array	Custom field names from the postmeta table
	 */
	private static function _get_custom_field_names( ) {
		global $wpdb;
		$limit = (int) apply_filters( 'postmeta_form_limit', 30 );
		$keys = $wpdb->get_col( "
			SELECT meta_key
			FROM $wpdb->postmeta
			GROUP BY meta_key
			HAVING meta_key NOT LIKE '\_%'
			ORDER BY meta_key
			LIMIT $limit" );
		if ( $keys )
			natcasesort($keys);
	
		return $keys;
	} // _get_custom_field_names
	
	/**
	 * Render and manage iptc/exif support options
 	 *
	 * @since 1.00
	 * @uses $mla_option_templates contains row and table templates
	 *
	 * @param	string 	'render', 'update', 'delete', or 'reset'
	 * @param	string 	option name, e.g., 'iptc_exif_mapping'
	 * @param	array 	option parameters
	 * @param	array 	Optional. null (default) for 'render' else option data, e.g., $_REQUEST
	 *
	 * @return	string	HTML table row markup for 'render' else message(s) reflecting the results of the operation.
	 */
	public static function mla_iptc_exif_option_handler( $action, $key, $value, $args = null ) {
		$current_values = self::mla_get_option( 'iptc_exif_mapping' );

		switch ( $action ) {
			case 'render':
				
				switch ( $key ) {
					case 'iptc_exif_standard_mapping':
						$row_template = self::$mla_option_templates['iptc-exif-standard-row'];
						$table_rows = '';
						
						foreach ( $current_values['standard'] as $row_name => $row_value ) {
							$row_values = array (
								'key' => $row_name,
								'name' => $row_value['name'],
								'iptc_field_options' => self::_compose_iptc_option_list( $row_value['iptc_value'] ),
								'exif_size' => 20,
								'exif_text' => $row_value['exif_value'],
								'iptc_selected' => '',
								'exif_selected' => '',
								'keep_selected' => '',
								'replace_selected' => ''
							);
							
							if ( $row_value['iptc_first'] )
								$row_values['iptc_selected'] = 'selected="selected"';
							else
								$row_values['exif_selected'] = 'selected="selected"';
								
							if ( $row_value['keep_existing'] )
								$row_values['keep_selected'] = 'selected="selected"';
							else
								$row_values['replace_selected'] = 'selected="selected"';

							$table_rows .= MLAData::mla_parse_template( $row_template, $row_values );
						} // foreach row
		
						$option_values = array (
							'table_rows' => $table_rows,
							'help' => $value['help']
						);
						
						return MLAData::mla_parse_template( self::$mla_option_templates['iptc-exif-standard-table'], $option_values );
					case 'iptc_exif_taxonomy_mapping':
						$row_template = self::$mla_option_templates['iptc-exif-taxonomy-row'];
						$select_template = self::$mla_option_templates['iptc-exif-select'];
						$table_rows = '';
						$taxonomies = get_taxonomies( array ( 'show_ui' => 'true' ), 'objects' );
				
						foreach ( $taxonomies as $row_name => $row_value ) {
							$row_values = array (
								'key' => $row_name,
								'name' => esc_html( $row_value->labels->name ),
								'hierarchical' => (string) $row_value->hierarchical,
								'iptc_field_options' => '',
								'exif_size' => 20,
								'exif_text' => '',
								'iptc_selected' => '',
								'exif_selected' => '',
								'keep_selected' => '',
								'replace_selected' => '',
								'parent_select' => ''
							);
							
							if ( array_key_exists( $row_name, $current_values['taxonomy'] ) ) {
								$current_value = $current_values['taxonomy'][ $row_name ];
								$row_values['iptc_field_options'] = self::_compose_iptc_option_list( $current_value['iptc_value'] );
								$row_values['exif_text'] = $current_value['exif_value'];
								
								if ( $current_value['iptc_first'] )
									$row_values['iptc_selected'] = 'selected="selected"';
								else
									$row_values['exif_selected'] = 'selected="selected"';
									
								if ( $current_value['keep_existing'] )
									$row_values['keep_selected'] = 'selected="selected"';
								else
									$row_values['replace_selected'] = 'selected="selected"';

								if ( $row_value->hierarchical ) {
									$parent = ( isset( $current_value['parent'] ) ) ? (integer) $current_value['parent'] : 0;
									$select_values = array (
										'array' => 'taxonomy',
										'key' => $row_name,
										'element' => 'parent',
										'options' => self::_compose_parent_option_list( $row_name, $parent )
									);
									$row_values['parent_select'] = MLAData::mla_parse_template( $select_template, $select_values );
								}
							}
							else {
								$row_values['iptc_field_options'] = self::_compose_iptc_option_list( 'none' );
								$row_values['iptc_selected'] = 'selected="selected"';
								$row_values['keep_selected'] = 'selected="selected"';

								if ( $row_value->hierarchical ) {
									$select_values = array (
										'array' => 'taxonomy',
										'key' => $row_name,
										'element' => 'parent',
										'options' => self::_compose_parent_option_list( $row_name, 0 )
									);
									$row_values['parent_select'] = MLAData::mla_parse_template( $select_template, $select_values );
								}
							}

							$table_rows .= MLAData::mla_parse_template( $row_template, $row_values );
						} // foreach row
		
						$option_values = array (
							'table_rows' => $table_rows,
							'help' => $value['help']
						);
						
						return MLAData::mla_parse_template( self::$mla_option_templates['iptc-exif-taxonomy-table'], $option_values );
					case 'iptc_exif_custom_mapping':
						$custom_field_names = MLAOptions::_get_custom_field_names();
						$row_template = self::$mla_option_templates['iptc-exif-custom-row'];
						$table_rows = '';

						/*
						 * Add fields defined here but not yet assigned to any attachments
						 */
						foreach ( $current_values['custom'] as $row_name => $row_values ) {
							if ( in_array( $row_name, $custom_field_names ) )
								continue;

							$custom_field_names[] = $row_name;
						}

						foreach ( $custom_field_names as $row_name ) {
							$row_values = array (
								'key' => $row_name,
								'name' => $row_name,
								'iptc_field_options' => '',
								'exif_size' => 20,
								'exif_text' => '',
								'iptc_selected' => '',
								'exif_selected' => '',
								'keep_selected' => '',
								'replace_selected' => ''
							);
							
							if ( array_key_exists( $row_name, $current_values['custom'] ) ) {
								$current_value = $current_values['custom'][ $row_name ];
								$row_values['iptc_field_options'] = self::_compose_iptc_option_list( $current_value['iptc_value'] );
								$row_values['exif_text'] = $current_value['exif_value'];
								
								if ( $current_value['iptc_first'] )
									$row_values['iptc_selected'] = 'selected="selected"';
								else
									$row_values['exif_selected'] = 'selected="selected"';
									
								if ( $current_value['keep_existing'] )
									$row_values['keep_selected'] = 'selected="selected"';
								else
									$row_values['replace_selected'] = 'selected="selected"';
							}
							else {
								$row_values['iptc_field_options'] = self::_compose_iptc_option_list( 'none' );
								$row_values['iptc_selected'] = 'selected="selected"';
								$row_values['keep_selected'] = 'selected="selected"';

							}

							$table_rows .= MLAData::mla_parse_template( $row_template, $row_values );
						} // foreach row

						/*
						 * Add a row for defining a new Custom Field
						 */
						$row_values = array (
							'key' => self::MLA_NEW_CUSTOM_FIELD,
							'name' => '            <input name="iptc_exif_mapping[custom][' . self::MLA_NEW_CUSTOM_FIELD . '][name]" id="iptc_exif_standard_exif_field_' . self::MLA_NEW_CUSTOM_FIELD . '" type="text" size="20" value="" />',
							'iptc_field_options' => self::_compose_iptc_option_list( 'none' ),
							'exif_size' => 20,
							'exif_text' => '',
							'iptc_selected' => 'selected="selected"',
							'exif_selected' => '',
							'keep_selected' => 'selected="selected"',
							'replace_selected' => ''
						);
						$table_rows .= MLAData::mla_parse_template( $row_template, $row_values );
							
						$option_values = array (
							'table_rows' => $table_rows,
							'help' => $value['help']
						);
						
						return MLAData::mla_parse_template( self::$mla_option_templates['iptc-exif-custom-table'], $option_values );
					default:
						return "<br>ERROR: Render unknown custom {$key}\r\n";
				} // switch $key
			case 'update':
			case 'delete':
				$settings_changed = false;
				$messages = '';
				
				switch ( $key ) {
					case 'iptc_exif_standard_mapping':
						$results = self::_update_iptc_exif_standard_mapping( $current_values, $args );
						$messages .= $results['message'];
						$current_values = $results['values'];
						$settings_changed = $results['changed'];
						break;
					case 'iptc_exif_taxonomy_mapping':
						$results = self::_update_iptc_exif_taxonomy_mapping( $current_values, $args );
						$messages .= $results['message'];
						$current_values = $results['values'];
						$settings_changed = $results['changed'];
						break;
					case 'iptc_exif_custom_mapping':
						$results = self::_update_iptc_exif_custom_mapping( $current_values, $args );
						$messages .= $results['message'];
						$current_values = $results['values'];
						$settings_changed = $results['changed'];
						break;
					case 'iptc_exif_mapping':
						$results = self::_update_iptc_exif_standard_mapping( $current_values, $args );
						$messages .= $results['message'];
						$current_values = $results['values'];
						$settings_changed = $results['changed'];
						
						$results = self::_update_iptc_exif_taxonomy_mapping( $current_values, $args );
						$messages .= $results['message'];
						$current_values = $results['values'];
						$settings_changed |= $results['changed'];

						$results = self::_update_iptc_exif_custom_mapping( $current_values, $args );
						$messages .= $results['message'];
						$current_values = $results['values'];
						$settings_changed |= $results['changed'];
						break;
					default:
						return "<br>ERROR: Update/delete unknown custom {$key}\r\n";
				} // switch $key
				
			if ( $settings_changed ) {
				$settings_changed = MLAOptions::mla_update_option( 'iptc_exif_mapping', $current_values );
				if ( $settings_changed ) 
					$results = "IPTC/EXIF mapping settings updated.\r\n";
				else
					$results = "ERROR: IPTC/EXIF settings update failed.\r\n";
			}
			else
				$results = "IPTC/EXIF no mapping changes detected.\r\n";

			return $results . $messages;
			case 'reset':
				switch ( $key ) {
					case 'iptc_exif_standard_mapping':
						$current_values['standard'] = self::$mla_option_definitions['iptc_exif_mapping']['std']['standard'];
						$settings_changed = MLAOptions::mla_update_option( 'iptc_exif_mapping', $current_values );
						if ( $settings_changed ) 
							return "IPTC/EXIF Standard field settings saved.\r\n";
						else
							return "ERROR: IPTC/EXIF Standard field settings update failed.\r\n";
					case 'iptc_exif_taxonomy_mapping':
						$current_values['taxonomy'] = self::$mla_option_definitions['iptc_exif_mapping']['std']['taxonomy'];
						$settings_changed = MLAOptions::mla_update_option( 'iptc_exif_mapping', $current_values );
						if ( $settings_changed ) 
							return "IPTC/EXIF Taxonomy term settings saved.\r\n";
						else
							return "ERROR: IPTC/EXIF Taxonomy term settings update failed.\r\n";
					case 'iptc_exif_custom_mapping':
						$current_values['custom'] = self::$mla_option_definitions['iptc_exif_mapping']['std']['custom'];
						$settings_changed = MLAOptions::mla_update_option( 'iptc_exif_mapping', $current_values );
						if ( $settings_changed ) 
							return "IPTC/EXIF Custom field settings saved.\r\n";
						else
							return "ERROR: IPTC/EXIF Custom field settings update failed.\r\n";
					case 'iptc_exif_mapping':
						self::mla_delete_option( $key );
						return "<br>Reset custom {$key}\r\n";
					default:
						return "<br>ERROR: Reset unknown custom {$key}\r\n";
				} // switch $key
			default:
				return "<br>ERROR: Custom {$key} unknown action: {$action}\r\n";
		} // switch $action
	} // mla_taxonomy_option_handler
} // class MLAOptions
?>