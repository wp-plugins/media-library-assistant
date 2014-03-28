<?php
/**
 * Media Library Assistant Media Manager enhancements
 *
 * @package Media Library Assistant
 * @since 1.20
 */

/**
 * Class MLA (Media Library Assistant) Modal contains enhancements for the WordPress 3.5+ Media Manager
 *
 * @package Media Library Assistant
 * @since 1.20
 */
class MLAModal {
	/**
	 * Slug for localizing and enqueueing CSS - Add Media and related dialogs
	 *
	 * @since 1.20
	 *
	 * @var	string
	 */
	const JAVASCRIPT_MEDIA_MODAL_STYLES = 'mla-media-modal-style';

	/**
	 * Slug for localizing and enqueueing JavaScript - Add Media and related dialogs
	 *
	 * @since 1.20
	 *
	 * @var	string
	 */
	const JAVASCRIPT_MEDIA_MODAL_SLUG = 'mla-media-modal-scripts';

	/**
	 * Object name for localizing JavaScript - Add Media and related dialogs
	 *
	 * @since 1.20
	 *
	 * @var	string
	 */
	const JAVASCRIPT_MEDIA_MODAL_OBJECT = 'mla_media_modal_vars';

	/**
	 * Slug for the "query attachments" action - Add Media and related dialogs
	 *
	 * @since 1.80
	 *
	 * @var	string
	 */
	const JAVASCRIPT_QUERY_ATTACHMENTS_ACTION = 'mla-query-attachments';

	/**
	 * Slug for the "fill compat-attachment-fields" action - Add Media and related dialogs
	 *
	 * @since 1.80
	 *
	 * @var	string
	 */
	const JAVASCRIPT_FILL_COMPAT_ACTION = 'mla-fill-compat-fields';

	/**
	 * Slug for the "update compat-attachment-fields" action - Add Media and related dialogs
	 *
	 * @since 1.80
	 *
	 * @var	string
	 */
	const JAVASCRIPT_UPDATE_COMPAT_ACTION = 'mla-update-compat-fields';

	/**
	 * Initialization function, similar to __construct()
	 *
	 * @since 1.20
	 *
	 * @return	void
	 */
	public static function initialize() {
		/*
		 * WordPress 3.5's new Media Manager is supported on the server by
		 * /wp-includes/media.php function wp_enqueue_media(), which contains:
		 *
		 * $settings = apply_filters( 'media_view_settings', $settings, $post );
		 * $strings  = apply_filters( 'media_view_strings',  $strings,  $post );
		 *
		 * wp_enqueue_media() then contains a require_once for
		 * /wp-includes/media-template.php, which contains:
		 * do_action( 'print_media_templates' );
		 *
 		 * Finally wp_enqueue_media() contains:
		 * do_action( 'wp_enqueue_media' );
		 */
		if ( MLATest::$wordpress_3point5_plus && ( 'checked' == MLAOptions::mla_get_option( MLAOptions::MLA_MEDIA_MODAL_TOOLBAR ) ) ) {
			add_filter( 'get_media_item_args', 'MLAModal::mla_get_media_item_args_filter', 10, 1 );
			add_filter( 'attachment_fields_to_edit', 'MLAModal::mla_attachment_fields_to_edit_filter', 0x7FFFFFFF, 2 );

			add_filter( 'media_view_settings', 'MLAModal::mla_media_view_settings_filter', 10, 2 );
			add_filter( 'media_view_strings', 'MLAModal::mla_media_view_strings_filter', 10, 2 );
			add_action( 'wp_enqueue_media', 'MLAModal::mla_wp_enqueue_media_action', 10, 0 );
			add_action( 'print_media_templates', 'MLAModal::mla_print_media_templates_action', 10, 0 );
			add_action( 'admin_init', 'MLAModal::mla_admin_init_action' );

			add_action( 'wp_ajax_' . self::JAVASCRIPT_QUERY_ATTACHMENTS_ACTION, 'MLAModal::mla_query_attachments_action' );
			add_action( 'wp_ajax_' . self::JAVASCRIPT_FILL_COMPAT_ACTION, 'MLAModal::mla_fill_compat_fields_action' );
			add_action( 'wp_ajax_' . self::JAVASCRIPT_UPDATE_COMPAT_ACTION, 'MLAModal::mla_update_compat_fields_action' );
		} // $wordpress_3point5_plus
	}

	/**
	 * Saves the get_media_item_args array for the attachment_fields_to_edit filter
	 *
	 * Declared public because it is a filter.
	 *
	 * @since 1.71
	 *
	 * @param	array	arguments for the get_media_item function in /wp-admin/includes/media.php
	 *
	 * @return	array	arguments for the get_media_item function (unchanged)
	 */
	public static function mla_get_media_item_args_filter( $args ) {
		self::$media_item_args = $args;
		return $args;
	} // mla_get_media_item_args_filter

	/**
	 * The get_media_item_args array
	 *
	 * @since 1.71
	 *
	 * @var	array ( 'errors' => array of strings, 'in_modal => boolean )
	 */
	private static $media_item_args = array( 'errors' => null, 'in_modal' => false );

	/**
	 * Add/change custom fields to the Edit Media screen and Modal Window
	 *
	 * Called from /wp-admin/includes/media.php, function get_compat_media_markup();
	 * If "get_media_item_args"['in_modal'] => false ) its the Edit Media screen.
	 * If "get_media_item_args"['in_modal'] => true ) its the Media Manager Modal Window.
	 * For the Modal Window, $form_fields contains all the "compat-attachment-fields"
	 * including the taxonomies, which we want to enhance.
	 * Declared public because it is a filter.
	 *
	 * @since 1.71
	 *
	 * @param	array	descriptors for the "compat-attachment-fields" 
	 * @param	object	the post to be edited
	 *
	 * @return	array	updated descriptors for the "compat-attachment-fields"
	 */
	public static function mla_attachment_fields_to_edit_filter( $form_fields, $post ) {
		/*
		 * This logic is only required for the Media Manager Modal Window.
		 * For the non-Modal Media/Edit Media screen, the MLAEdit::mla_add_meta_boxes_action
		 * function changes the default meta box to the MLA searchable meta box.
		 */
		if ( isset( self::$media_item_args['in_modal'] ) && self::$media_item_args['in_modal'] ) {
			foreach ( get_taxonomies( array ( 'show_ui' => true ), 'objects' ) as $key => $value ) {
				if ( MLAOptions::mla_taxonomy_support( $key ) ) {
					if ( isset( $form_fields[ $key ] ) ) {
						$field = $form_fields[ $key ];
					} else {
						continue;
					}

					if ( ! $use_checklist = $value->hierarchical ) {
						$use_checklist =  MLAOptions::mla_taxonomy_support( $key, 'flat-checklist' );
					}

					if ( $use_checklist ) {
						if ( 'checked' == MLAOptions::mla_get_option( MLAOptions::MLA_MEDIA_MODAL_DETAILS_CATEGORY_METABOX ) ) {
							/*
							 * Remove "Media Categories" meta box, if present.
							 */
							if ( isset( $form_fields[ $key . '_metabox' ] ) ) {
								unset( $form_fields[ $key . '_metabox' ] );
							}

							/*
							 * Simulate the default text box, but with term id values
							 */
							$post_id = $post->ID;
							$label = $field['labels']->name;
							$terms = get_object_term_cache( $post_id, $key );

							if ( false === $terms ) {
								$terms = wp_get_object_terms( $post_id, $key );
							}

							if ( is_wp_error( $terms ) || empty( $terms ) ) {
								$terms = array();
							}

							$list = array();
							foreach ( $terms as $term ) {
								$list[] = $term->term_id;
							} // foreach $term

							sort( $list );
							$list = join( ',', $list );

							$row  = "\t\t<tr class='compat-field-{$key} mla-taxonomy-row'>\n";
							$row .= "\t\t<th class='label' valign='top' scope='row'>\n";
							$row .= "\t\t<label for='mla-attachments-{$post_id}-{$key}'>\n";
							$row .= "\t\t<span title='" . __( 'Click to toggle', 'media-library-assistant' ) . "' class='alignleft'>{$label}</span><br class='clear'>\n";
							$row .= "\t\t</label></th>\n";
							$row .= "\t\t<td class='field'>\n";
							$row .= "\t\t<div class='mla-taxonomy-field'>\n";
							$row .= "\t\t<input name='mla_attachments[{$post_id}][{$key}]' class='text' id='mla-attachments-{$post_id}-{$key}' type='hidden' value='{$list}'>\n";
							$row .= "\t\t<div id='mla-taxonomy-{$key}' class='categorydiv'>\n";
							$row .= '&lt;- ' . __( 'Click to toggle', 'media-library-assistant' ) . "\n";
							$row .= "\t\t</div>\n";
							$row .= "\t\t</div>\n";
							$row .= "\t\t</td>\n";
							$row .= "\t\t</tr>\n";
							$form_fields[ $key ] = array( 'tr' => $row );
						} // checked
					} /* use_checklist */ else { // flat
						if ( 'checked' == MLAOptions::mla_get_option( MLAOptions::MLA_MEDIA_MODAL_DETAILS_TAG_METABOX ) ) {
							/*
							 * Simulate the default text box
							 */
							$post_id = $post->ID;
							$label = $field['labels']->name;
							$terms = get_object_term_cache( $post_id, $key );

							if ( false === $terms ) {
								$terms = wp_get_object_terms( $post_id, $key );
							}

							if ( is_wp_error( $terms ) || empty( $terms ) ) {
								$terms = array();
							}

							$list = array();
							foreach ( $terms as $term ) {
								$list[] = $term->name;
							} // foreach $term

							sort( $list );
							$list = join( ', ', $list );

							$row  = "\t\t<tr class='compat-field-{$key} mla-taxonomy-row'>\n";
							$row .= "\t\t<th class='label' valign='top' scope='row'>\n";
							$row .= "\t\t<label for='mla-attachments-{$post_id}-{$key}'>\n";
							$row .= "\t\t<span title='" . __( 'Click to toggle', 'media-library-assistant' ) . "' class='alignleft'>{$label}</span><br class='clear'>\n";
							$row .= "\t\t</label></th>\n";
							$row .= "\t\t<td class='field'>\n";
							$row .= "\t\t<div class='mla-taxonomy-field'>\n";
							$row .= "\t\t<input name='mla_attachments[{$post_id}][{$key}]' class='text' id='mla-attachments-{$post_id}-{$key}' type='hidden' value='{$list}'>\n";
							$row .= "\t\t<div class='tagsdiv' id='mla-taxonomy-{$key}'>\n";
							$row .= '&lt;- ' . __( 'Click to toggle', 'media-library-assistant' ) . "\n";
							$row .= "\t\t</div>\n";
							$row .= "\t\t</div>\n";
							$row .= "\t\t</td>\n";
							$row .= "\t\t</tr>\n";
							$form_fields[ $key ] = array( 'tr' => $row );
						} // checked
					} // flat
				} // is supported
			} // foreach
		} // in_modal

		self::$media_item_args = array( 'errors' => null, 'in_modal' => false );
		return $form_fields;
	} // mla_attachment_fields_to_edit_filter

	/**
	 * Display a monthly dropdown for filtering items
	 *
	 * Adapted from /wp-admin/includes/class-wp-list-table.php function months_dropdown()
	 *
	 * @since 1.20
	 *
	 * @param	string	post_type, e.g., 'attachment'
	 *
	 * @return	array	( value => label ) pairs
	 */
	private static function _months_dropdown( $post_type ) {
		global $wpdb, $wp_locale;

		$months = $wpdb->get_results( $wpdb->prepare( "
			SELECT DISTINCT YEAR( post_date ) AS year, MONTH( post_date ) AS month
			FROM $wpdb->posts
			WHERE post_type = %s
			ORDER BY post_date DESC
		", $post_type ) );

		$month_count = count( $months );
		$month_array = array( '0' => __( 'Show all dates', 'media-library-assistant' ) );

		if ( !$month_count || ( 1 == $month_count && 0 == $months[0]->month ) ) {
			return $month_array;
		}

		foreach ( $months as $arc_row ) {
			if ( 0 == $arc_row->year ) {
				continue;
			}

			$month = zeroise( $arc_row->month, 2 );
			$year = $arc_row->year;
			$month_array[ esc_attr( $arc_row->year . $month ) ] = 
				/* translators: 1: month name, 2: 4-digit year */
				sprintf( __( '%1$s %2$d', 'media-library-assistant' ), $wp_locale->get_month( $month ), $year );
		}

		return $month_array;
	}

	/**
	 * Extract value and text elements from Dropdown HTML option tags
	 *
	 * @since 1.20
	 *
	 * @param	string	HTML markup for taxonomy terms dropdown <select> tag
	 *
	 * @return	array	( value => label ) pairs
	 */
	private static function _terms_options( $markup ) {
		$match_count = preg_match_all( "#\<option(( class=\"([^\"]+)\" )|( ))value=((\'([^\']+)\')|(\"([^\"]+)\"))([^\>]*)\>([^\<]*)\<.*#", $markup, $matches );
		if ( ( $match_count == false ) || ( $match_count == 0 ) ) {
			return array( 'class' => array( '' ), 'value' => array( '0' ), 'text' => array( 'Show all terms' ) );
		}

		$class_array = array();
		$value_array = array();
		$text_array = array();

		foreach ( $matches[11] as $index => $text ) {
			$class_array[ $index ] = $matches[3][ $index ];
			$value_array[ $index ] = ( ! '' == $matches[6][ $index ] )? $matches[7][ $index ] : $matches[9][ $index ];

			$current_version = get_bloginfo( 'version' );
			if ( version_compare( $current_version, '3.9', '<' ) && version_compare( $current_version, '3.6', '>=' ) ) {
				$text_array[ $index ] = str_replace( '&nbsp;', '-', $text);
			} else {
				$text_array[ $index ] = $text;
			}

		} // foreach

		return array( 'class' => $class_array, 'value' => $value_array, 'text' => $text_array );
	}

	/**
	 * Share the settings values between mla_media_view_settings_filter
	 * and mla_print_media_templates_action
	 *
	 * @since 1.20
	 *
	 * @var	array
	 */
	private static $mla_media_modal_settings = array(
			'ajaxQueryAttachmentsAction' => self::JAVASCRIPT_QUERY_ATTACHMENTS_ACTION,
			'ajaxFillCompatAction' => self::JAVASCRIPT_FILL_COMPAT_ACTION,
			'ajaxUpdateCompatAction' => self::JAVASCRIPT_UPDATE_COMPAT_ACTION,
			'ajaxFillCompatNonce' => '',
			'ajaxUpdateCompatNonce' => '',
			'enableMimeTypes' => false,
			'enableMonthsDropdown' => false,
			'enableTermsDropdown' => false,
			'enableSearchBox' => false,
			'enableDetailsCategory' => false,
			'enableDetailsTag' => false,
			'mimeTypes' => '',
			'months' => '',
			'termsClass' => array(),
			'termsValue' => array(),
			'termsText' => array(),
			'searchValue' => '',
			'searchFields' => array( 'title', 'content' ),
			'searchConnector' => 'AND'
			);

	/**
	 * Adds settings values to be passed to the Media Manager in /wp-includes/js/media-views.js.
	 * Declared public because it is a filter.
	 *
	 * @since 1.20
	 *
	 * @param	array	associative array with setting => value pairs
	 * @param	object || NULL	current post object, if available
	 *
	 * @return	array	updated $settings array
	 */
	public static function mla_media_view_settings_filter( $settings, $post ) {
		self::$mla_media_modal_settings['ajaxNonce'] = wp_create_nonce( MLA::MLA_ADMIN_NONCE );
		self::$mla_media_modal_settings['mimeTypes'] = MLAMime::mla_pluck_table_views();
		self::$mla_media_modal_settings['mimeTypes']['detached'] = MLAOptions::$mla_option_definitions[ MLAOptions::MLA_POST_MIME_TYPES ]['std']['unattached']['plural'];
		self::$mla_media_modal_settings['months'] = self::_months_dropdown('attachment');

		$terms_options = self::_terms_options( MLA_List_Table::mla_get_taxonomy_filter_dropdown() );
		self::$mla_media_modal_settings['termsClass'] = $terms_options['class'];
		self::$mla_media_modal_settings['termsValue'] = $terms_options['value'];
		self::$mla_media_modal_settings['termsText'] = $terms_options['text'];

		self::$mla_media_modal_settings['enableMimeTypes'] = ( 'checked' == MLAOptions::mla_get_option( MLAOptions::MLA_MEDIA_MODAL_MIMETYPES ) );
		self::$mla_media_modal_settings['enableMonthsDropdown'] = ( 'checked' == MLAOptions::mla_get_option( MLAOptions::MLA_MEDIA_MODAL_MONTHS ) );
		self::$mla_media_modal_settings['enableTermsDropdown'] = ( 'checked' == MLAOptions::mla_get_option( MLAOptions::MLA_MEDIA_MODAL_TERMS ) );
		self::$mla_media_modal_settings['enableSearchBox'] = ( 'checked' == MLAOptions::mla_get_option( MLAOptions::MLA_MEDIA_MODAL_SEARCHBOX ) );
		self::$mla_media_modal_settings['enableDetailsCategory'] = ( 'checked' == MLAOptions::mla_get_option( MLAOptions::MLA_MEDIA_MODAL_DETAILS_CATEGORY_METABOX ) );
		self::$mla_media_modal_settings['enableDetailsTag'] = ( 'checked' == MLAOptions::mla_get_option( MLAOptions::MLA_MEDIA_MODAL_DETAILS_TAG_METABOX ) );

		/*
		 * These will be passed back to the server in the query['s'] field.
		 */ 
		self::$mla_media_modal_settings['filterMonth'] = 0;
		self::$mla_media_modal_settings['filterTerm'] = 0;
		self::$mla_media_modal_settings['searchConnector'] = 'AND';
		self::$mla_media_modal_settings['searchFields'] = array( 'title', 'content' );
		self::$mla_media_modal_settings['searchValue'] = '';

		$settings = array_merge( $settings, array( 'mla_settings' => self::$mla_media_modal_settings ) );
		return $settings;
	} // mla_mla_media_view_settings_filter

	/**
	 * Adds strings values to be passed to the Media Manager in /wp-includes/js/media-views.js.
	 * Declared public because it is a filter.
	 *
	 * @since 1.20
	 *
	 * @param	array	associative array with string => value pairs
	 * @param	object || NULL	current post object, if available
	 *
	 * @return	array	updated $strings array
	 */
	public static function mla_media_view_strings_filter( $strings, $post ) {
		$mla_strings = array(
			'searchBoxPlaceholder' => __( 'Search Box', 'media-library-assistant' ),
			'loadingText' => __( 'Loading...', 'media-library-assistant' )
			);

		$strings = array_merge( $strings, array( 'mla_strings' => $mla_strings ) );
		return $strings;
	} // mla_mla_media_view_strings_filter

	/**
	 * Enqueues the mla-media-modal-scripts.js file, adding it to the Media Manager scripts.
	 * Declared public because it is an action.
	 *
	 * @since 1.20
	 *
	 * @return	void
	 */
	public static function mla_wp_enqueue_media_action( ) {
		$suffix = defined('SCRIPT_DEBUG') && SCRIPT_DEBUG ? '' : '.min';

		// replaced by inline styles for now
		wp_register_style( self::JAVASCRIPT_MEDIA_MODAL_STYLES, MLA_PLUGIN_URL . 'css/mla-media-modal-style.css', false, MLA::CURRENT_MLA_VERSION );
		wp_enqueue_style( self::JAVASCRIPT_MEDIA_MODAL_STYLES );

		wp_enqueue_script( self::JAVASCRIPT_MEDIA_MODAL_SLUG, MLA_PLUGIN_URL . "js/mla-media-modal-scripts{$suffix}.js", array( 'media-views' ), MLA::CURRENT_MLA_VERSION, false );
	} // mla_wp_enqueue_media_action

	/**
	 * Prints the templates used in the MLA Media Manager enhancements.
	 * Declared public because it is an action.
	 *
	 * @since 1.20
	 *
	 * @return	void	echoes HTML script tags for the templates
	 */
	public static function mla_print_media_templates_action( ) {
		/*
		 * Adjust the toolbar styles based on which controls are present
		 */
		if ( self::$mla_media_modal_settings['enableSearchBox'] ) {
			if ( self::$mla_media_modal_settings['enableMonthsDropdown'] && self::$mla_media_modal_settings['enableTermsDropdown'] ) {
				$height = '120px';
			} else {
				$height = '80px';
			}
		} else {
			$height = '50px';
		}

		/*
		 * Compose the Search Media box
		 */
		if ( isset( $_REQUEST['query']['mla_search_value'] ) ) {
			$search_value = esc_attr( stripslashes( trim( $_REQUEST['query']['mla_search_value'] ) ) );
		} else {
			$search_value = '';
		}

		if ( isset( $_REQUEST['query']['mla_search_fields'] ) ) {
			$search_fields = $_REQUEST['query']['mla_search_fields'];
		} else {
			$search_fields = array ( 'title', 'content' );
		}

		if ( isset( $_REQUEST['query']['mla_search_connector'] ) ) {
			$search_connector = $_REQUEST['query']['mla_search_connector'];
		} else {
			$search_connector = 'AND';
		}

		// Include mla javascript templates
		require_once MLA_PLUGIN_PATH . '/includes/mla-media-modal-js-template.php';
	} // mla_print_media_templates_action

	/**
	 * Adjust ajax handler for Media Manager queries 
	 *
	 * Replace 'query-attachments' with our own handler if the request is coming from the "Assistant" tab.
	 * Clean up the 'save-attachment-compat' values, removing the taxonomy updates MLS already handled.
	 *
	 * @since 1.20
	 *
	 * @return	void	
	 */
	public static function mla_admin_init_action() {
		/*
		 * Build a list of enhanced taxonomies for later $_REQUEST/$_POST cleansing.
		 * Remove "Media Categories" instances, if present.
		 */
		$enhanced_taxonomies = array();
		foreach ( get_taxonomies( array ( 'show_ui' => true ), 'objects' ) as $key => $value ) {
			if ( MLAOptions::mla_taxonomy_support( $key ) ) {
				if ( ! $use_checklist = $value->hierarchical ) {
					$use_checklist = MLAOptions::mla_taxonomy_support( $key, 'flat-checklist' );
				}

				if ( $use_checklist ) {
					if ( 'checked' == MLAOptions::mla_get_option( MLAOptions::MLA_MEDIA_MODAL_DETAILS_CATEGORY_METABOX ) ) {
						$enhanced_taxonomies[] = $key;

						if ( class_exists( 'Media_Categories' ) && is_array( Media_Categories::$instances ) ) {
							foreach( Media_Categories::$instances as $index => $instance ) {
								if ( $instance->taxonomy == $key ) {
									// unset( Media_Categories::$instances[ $index ] );
									Media_Categories::$instances[ $index ]->taxonomy = 'MLA-has-disabled-this-instance';
								}
							}
						} // class_exists
					} // checked
				} // use_checklist
			} // supported
		} // foreach taxonomy 

		if ( ( defined('WP_ADMIN') && WP_ADMIN ) && ( defined('DOING_AJAX') && DOING_AJAX ) ) {
//error_log( 'DEBUG: mla_admin_init_action Ajax $_REQUEST = ' . var_export( $_REQUEST, true ), 0 );

			/*
			 * If there's no action variable, we have nothing to do
			 */
			if ( ! isset( $_POST['action'] ) ) {
				return;
			}

 			/*
			 * The 'query-attachments' action fills the Modal Window thumbnail pane with media items.
			 * If the 's' value is an array, the MLA Enhanced elements are present; unpack the arguments
			 * and substitute our handler for the WordPress default handler.
			 */
			if ( ( $_POST['action'] == 'query-attachments' ) && isset( $_POST['query']['s'] ) && is_array( $_POST['query']['s'] ) ){
				foreach ( $_POST['query']['s'] as $key => $value ) {
					$_POST['query'][ $key ] = $value;
					$_REQUEST['query'][ $key ] = $value;
				}

				unset( $_POST['query']['s'] );
				unset( $_REQUEST['query']['s'] );
				$_POST['action'] = self::JAVASCRIPT_QUERY_ATTACHMENTS_ACTION;
				$_REQUEST['action'] = self::JAVASCRIPT_QUERY_ATTACHMENTS_ACTION;
				return;
			} // query-attachments

 			/*
			 * The 'save-attachment-compat' action updates taxonomy and custom field
			 * values for an item. Remove any MLA-enhanced taxonomy data from the
			 * incoming data. The other taxonomies will be processed by
			 * /wp-admin/includes/ajax-actions.php, function wp_ajax_save_attachment_compat().
			 */
			if ( ( $_POST['action'] == 'save-attachment-compat' ) ){
				if ( empty( $_REQUEST['id'] ) || ! $id = absint( $_REQUEST['id'] ) ) {
					wp_send_json_error();
				}

				if ( empty( $_REQUEST['attachments'] ) || empty( $_REQUEST['attachments'][ $id ] ) ) {
					wp_send_json_error();
				}

				/*
				 * Media Categories uses this
				 */
				if ( isset( $_REQUEST['category-filter'] ) ) {
					unset( $_REQUEST['category-filter'] );
					unset( $_POST['category-filter'] );
				}

				if ( isset( $_REQUEST['mla_attachments'] ) ) {
					unset( $_REQUEST['mla_attachments'] );
					unset( $_POST['mla_attachments'] );
				}

				if ( isset( $_REQUEST['tax_input'] ) ) {
					unset( $_REQUEST['tax_input'] );
					unset( $_POST['tax_input'] );
				}

				foreach( $enhanced_taxonomies as $taxonomy ) {
					if ( isset( $_REQUEST['attachments'][ $id ][ $taxonomy ] ) ) {
						unset( $_REQUEST['attachments'][ $id ][ $taxonomy ] );
						unset( $_POST['attachments'][ $id ][ $taxonomy ] );
					}

					if ( isset( $_REQUEST[ $taxonomy ] ) ) {
						unset( $_REQUEST[ $taxonomy ] );
						unset( $_POST[ $taxonomy ] );
					}

					if ( ( 'category' == $taxonomy ) && isset( $_REQUEST['post_category'] ) ) {
						unset( $_REQUEST['post_category'] );
						unset( $_POST['post_category'] );
					}

					if ( isset( $_REQUEST[ 'new' . $taxonomy ] ) ) {
						unset( $_REQUEST[ 'new' . $taxonomy ] );
						unset( $_POST[ 'new' . $taxonomy ] );
						unset( $_REQUEST[ 'new' . $taxonomy . '_parent' ] );
						unset( $_POST[ 'new' . $taxonomy . '_parent' ] );
						unset( $_REQUEST[ '_ajax_nonce-add-' . $taxonomy ] );
						unset( $_POST[ '_ajax_nonce-add-' . $taxonomy ] );
					}

					if ( isset( $_REQUEST[ 'search-' . $taxonomy ] ) ) {
						unset( $_REQUEST[ 'search-' . $taxonomy ] );
						unset( $_POST[ 'search-' . $taxonomy ] );
						unset( $_REQUEST[ '_ajax_nonce-search-' . $taxonomy ] );
						unset( $_POST[ '_ajax_nonce-search-' . $taxonomy ] );
					}
				} // foreach taxonomy
			} // save-attachment-compat
		}
	} // mla_admin_init_action

	/**
	 * Ajax handler for Media Manager "fill compat-attachment-fields" queries 
	 *
	 * Prepares an array of (HTML) taxonomy meta boxes with attachment-specific values.
	 *
	 * @since 1.80
	 *
	 * @return	void	passes array of results to wp_send_json_success() for JSON encoding and transmission
	 */
	public static function mla_fill_compat_fields_action() {
		if ( empty( $_REQUEST['query'] ) || ! $requested = $_REQUEST['query'] ) {
			wp_send_json_error();
		}

		if ( empty( $_REQUEST['id'] ) || ! $post_id = absint( $_REQUEST['id'] ) ) {
			wp_send_json_error();
		}

		if ( null == ( $post = get_post( $post_id ) ) ) {
			wp_send_json_error();
		}

		$results = array();

		/*
		 * Match all supported taxonomies against the requested list
		 */
		foreach ( get_taxonomies( array ( 'show_ui' => true ), 'objects' ) as $key => $value ) {
			if ( MLAOptions::mla_taxonomy_support( $key ) ) {
				if ( is_integer( $index = array_search( $key, $requested ) ) ) {
					$request = $requested[ $index ];
				} else {
					continue;
				}

				if ( ! $use_checklist = $value->hierarchical ) {
					$use_checklist = MLAOptions::mla_taxonomy_support( $key, 'flat-checklist' );
				}

				if ( $use_checklist ) {
					if ( 'checked' == MLAOptions::mla_get_option( MLAOptions::MLA_MEDIA_MODAL_DETAILS_CATEGORY_METABOX ) ) {
						unset( $requested[ $index ] );
						$label = $value->label;
						$terms = get_object_term_cache( $post_id, $key );

						if ( false === $terms ) {
							$terms = wp_get_object_terms( $post_id, $key );
						}

						if ( is_wp_error( $terms ) || empty( $terms ) ) {
							$terms = array();
						}

						$list = array();
						foreach ( $terms as $term ) {
							$list[] = $term->term_id;
						} // foreach $term

						sort( $list );
						$list = join( ',', $list );

						/*
						 * Simulate the 'add_meta_boxes' callback
						 */
						$box = array (
							'id' => $key . 'div',
							'title' => $label,
							'callback' => 'MLAEdit::mla_checklist_meta_box',
							'args' => array ( 'taxonomy' => $key, 'in_modal' => true ),

						);

						ob_start();
						MLAEdit::mla_checklist_meta_box( $post, $box );
						$row_content = ob_get_clean();

						$row = "\t\t<th class='label' valign='top' scope='row' style='width: 99%;'>\n";
						$row .= "\t\t<label for='mla-attachments-{$post_id}-{$key}'>\n";
						$row .= "\t\t<span title='" . __( 'Click to toggle', 'media-library-assistant' ) . "' class='alignleft' style='width: 99%; text-align: left;'>{$label}</span><br class='clear'>\n";
						$row .= "\t\t</label></th>\n";
						$row .= "\t\t<td class='field' style='width: 99%; display: none'>\n";
						$row .= "\t\t<div class='mla-taxonomy-field'>\n";
						$row .= "\t\t<input name='attachments[{$post_id}][{$key}]' class='text' id='mla-attachments-{$post_id}-{$key}' type='hidden' value='{$list}'>\n";
						$row .= $row_content;
						$row .= "\t\t</div>\n";
						$row .= "\t\t</td>\n";
						$results[ $key ] = $row;
					} // checked
				} /* use_checklist */ else { // flat
					if ( 'checked' == MLAOptions::mla_get_option( MLAOptions::MLA_MEDIA_MODAL_DETAILS_TAG_METABOX ) ) {
						unset( $requested[ $index ] );
						$label = $value->label;
						$terms = get_object_term_cache( $post_id, $key );

						if ( false === $terms ) {
							$terms = wp_get_object_terms( $post_id, $key );
						}

						if ( is_wp_error( $terms ) || empty( $terms ) ) {
							$terms = array();
						}

						$list = array();
						foreach ( $terms as $term ) {
							$list[] = $term->name;
						} // foreach $term

						sort( $list );
						$hidden_list = join( ',', $list );

						$row = "\t\t<th class='label' valign='top' scope='row' style='width: 99%;'>\n";
						$row .= "\t\t<label for='mla-attachments-{$post_id}-{$key}'>\n";
						$row .= "\t\t<span title='" . __( 'Click to toggle', 'media-library-assistant' ) . "' class='alignleft' style='width: 99%; text-align: left;'>{$label}</span><br class='clear'>\n";
						$row .= "\t\t</label></th>\n";
						$row .= "\t\t<td class='field' style='width: 99%; display: none'>\n";
						$row .= "\t\t<div class='mla-taxonomy-field'>\n";
						$row .= "\t\t<div class='tagsdiv' id='mla-taxonomy-{$key}'>\n";
						$row .= "\t\t<div class='jaxtag'>\n";
						$row .= "\t\t<div class='nojs-tags hide-if-js'>\n";
						$row .= "\t\t<input name='attachments[{$post_id}][{$key}]' class='the-tags' id='mla-attachments-{$post_id}-{$key}' type='hidden' value='{$hidden_list}'>\n";
						$row .= "\t\t<input name='mla_tags[{$post_id}][{$key}]' class='server-tags' id='mla-tags-{$post_id}-{$key}' type='hidden' value='{$hidden_list}'>\n";
						$row .= "\t\t</div>\n"; // nojs-tags
						$row .= "\t\t<div class='ajaxtag'>\n";
						$row .= "\t\t<label class='screen-reader-text' for='new-tag-{$key}'>" . __( 'Tags', 'media-library-assistant' ) . "</label>\n";
						/* translators: %s: add new taxonomy label */
						$row .= "\t\t<div class='taghint'>" . sprintf( __( 'Add New %1$s', 'media-library-assistant' ), $label ) . "</div>\n";
						$row .= "\t\t<p>\n";
						$row .= "\t\t<input name='newtag[{$key}]' class='newtag form-input-tip' id='new-tag-{$key}' type='text' size='16' value='' autocomplete='off'>\n";
						$row .= "\t\t<input class='button tagadd' type='button' value='Add'>\n";
						$row .= "\t\t</p>\n";
						$row .= "\t\t</div>\n"; // ajaxtag
						$row .= "\t\t<p class='howto'>Separate tags with commas</p>\n";
						$row .= "\t\t</div>\n"; // jaxtag
						$row .= "\t\t<div class='tagchecklist'>\n";

						foreach ( $list as $index => $term ) {
							$row .= "\t\t<span><a class='ntdelbutton' id='post_tag-check-num-{$index}'>X</a>&nbsp;{$term}</span>\n";
						}

						$row .= "\t\t</div>\n"; // tagchecklist
						$row .= "\t\t</div>\n"; // tagsdiv
						$row .= "\t\t<p><a class='tagcloud-link' id='mla-link-{$key}' href='#titlediv'>" . __( 'Choose from the most used tags', 'media-library-assistant' ) . "</a></p>\n";
						$row .= "\t\t</div>\n"; // mla-taxonomy-field
						$row .= "\t\t</td>\n";
						$results[ $key ] = $row;
					} // checked
				} // flat
			} // is supported
		} // foreach

		/*
		 * Any left-over requests are for unsupported taxonomies
		 */
		foreach( $requested as $key ) {
			$row  = "\t\t<tr class='compat-field-{$key} mla-taxonomy-row'>\n";
			$row .= "\t\t<th class='label' valign='top' scope='row'>\n";
			$row .= "\t\t<label for='mla-attachments-{$post_id}-{$key}'>\n";
			$row .= "\t\t<span title='" . __( 'Click to toggle', 'media-library-assistant' ) . "' class='alignleft'>{$label}</span><br class='clear'>\n";
			$row .= "\t\t</label></th>\n";
			$row .= "\t\t<td class='field' style='display: none'>\n";
			$row .= "\t\t<div class='mla-taxonomy-field'>\n";
			$row .= "\t\t<input name='attachments[{$post_id}][{$key}]' class='text' id='mla-attachments-{$post_id}-{$key}' type='hidden' value=''>\n";
			$row .= "\t\t<div id='taxonomy-{$key}' class='categorydiv'>\n";
			$row .= __( 'Not Supported', 'media-library-assistant' ) . ".\n";
			$row .= "\t\t</div>\n";
			$row .= "\t\t</div>\n";
			$row .= "\t\t</td>\n";
			$row .= "\t\t</tr>\n";
			$results[ $key ] = $row;
		}

		wp_send_json_success( $results );
	} // mla_fill_compat_fields_action

	/**
	 * Ajax handler for Media Manager "update compat-attachment-fields" queries 
	 *
	 * Updates one (or more) supported taxonomy and returns updated checkbox or tag/term lists
	 *
	 * @since 1.80
	 *
	 * @return	void	passes array of results to wp_send_json_success() for JSON encoding and transmission
	 */
	public static function mla_update_compat_fields_action() {
		global $post;

		if ( empty( $_REQUEST['id'] ) || ! $id = absint( $_REQUEST['id'] ) ) {
			wp_send_json_error();
		}

		$results = array();

		foreach ( get_taxonomies( array ( 'show_ui' => true ), 'objects' ) as $key => $value ) {
			if ( isset( $_REQUEST[ $key ] ) && MLAOptions::mla_taxonomy_support( $key ) ) {
				if ( ! $use_checklist = $value->hierarchical ) {
					$use_checklist =  MLAOptions::mla_taxonomy_support( $key, 'flat-checklist' );
				}

				if ( $use_checklist ) {
					$terms = array_map( 'absint', preg_split( '/,+/', $_REQUEST[ $key ] ) );
				} else {
					$terms = array_map( 'trim', preg_split( '/,+/', $_REQUEST[ $key ] ) );
				}

				wp_set_object_terms( $id, $terms, $key, false );

				if ( $use_checklist ) {
					if ( empty( $post ) ) {
						$post = get_post( $id ); // for wp_popular_terms_checklist
					}

					ob_start();
					$popular_ids = wp_popular_terms_checklist( $key );
					$results[$key]["mla-{$key}-checklist-pop"] = ob_get_clean();

					ob_start();

					if ( $use_checklist ) {
						wp_terms_checklist( $id, array( 'taxonomy' => $key, 'popular_cats' => $popular_ids ) );
					} else {
						$checklist_walker = new MLA_Checklist_Walker;
						wp_terms_checklist( $id, array( 'taxonomy' => $key, 'popular_cats' => $popular_ids, 'walker' => $checklist_walker ) );
					}

					$results[$key]["mla-{$key}-checklist"] = ob_get_clean();
				} else {
					$terms = wp_get_object_terms( $id, $key );
					if ( is_wp_error( $terms ) || empty( $terms ) ) {
						$terms = array();
					}

					$list = array();
					foreach ( $terms as $term ) {
						$list[] = $term->name;
					} // foreach $term

					sort( $list );
					$hidden_list = join( ',', $list );

					$results[$key]["mla-attachments-{$id}-{$key}"] = "\t\t<input name='attachments[{$id}][{$key}]' class='the-tags' id='mla-attachments-{$id}-{$key}' type='hidden' value='{$hidden_list}'>\n";
					$results[$key]["mla-tags-{$id}-{$key}"] = "\t\t<input name='mla_tags[{$id}][{$key}]' class='server-tags' id='mla-tags-{$id}-{$key}' type='hidden' value='{$hidden_list}'>\n";
				}
			} // set and supported
		} // foreach taxonomy

		wp_send_json_success( $results );
	} // mla_update_compat_fields_action

	/**
	 * Ajax handler for Media Manager "Query Attachments" queries 
	 *
	 * Adapted from wp_ajax_query_attachments in /wp-admin/includes/ajax-actions.php
	 *
	 * @since 1.20
	 *
	 * @return	void	passes array of post arrays to wp_send_json_success() for JSON encoding and transmission
	 */
	public static function mla_query_attachments_action() {
		if ( ! current_user_can( 'upload_files' ) ) {
			wp_send_json_error();
		}

		/*
		 * Pick out and clean up the query terms we can process
		 */
		$query = isset( $_REQUEST['query'] ) ? (array) $_REQUEST['query'] : array();
		$query = array_intersect_key( $query, array_flip( array(
			'order', 'orderby', 'posts_per_page', 'paged', 'post_mime_type',
			'post_parent', 'post__in', 'post__not_in', 'mla_filter_month', 'mla_filter_term',
			'mla_search_value', 's', 'mla_search_fields', 'mla_search_connector'
		) ) );

		if ( isset( $query['post_mime_type'] ) ) {
			if ( 'detached' == $query['post_mime_type'] ) {
				$query['detached'] = '1';
				unset( $query['post_mime_type'] );
			} else {
				$view = $query['post_mime_type'];
				unset( $query['post_mime_type'] );
				$query = array_merge( $query, MLAMime::mla_prepare_view_query( 'view', $view ) );
			}
		}

		/*
		 * Convert mla_filter_month back to the WordPress "m" parameter
		 */
		if ( isset( $query['mla_filter_month'] ) ) {
			if ( '0' != $query['mla_filter_month'] ) {
				$query['m'] = $query['mla_filter_month'];
			}

			unset( $query['mla_filter_month'] );
		}

		/*
		 * Process the enhanced search box OR fix up the default search box
		 */
		if ( isset( $query['mla_search_value'] ) ) {
			if ( ! empty( $query['mla_search_value'] ) ) {
				$query['s'] = $query['mla_search_value'];
			}

			unset( $query['mla_search_value'] );
		}

		if ( isset( $query['posts_per_page'] ) ) {
			$count = $query['posts_per_page'];
			$offset = $count * (isset( $query['paged'] ) ? $query['paged'] - 1 : 0);
		} else {
			$count = 0;
			$offset = 0;
		}

		/*
		 * Check for sorting override
		 */
		$option =  MLAOptions::mla_get_option( MLAOptions::MLA_MEDIA_MODAL_ORDERBY );
		if ( 'default' != $option ) {
			/*
			 * Make sure the current orderby choice still exists or revert to default.
			 */
			$default_orderby = array_merge( array( 'none' => array('none',false) ), MLA_List_Table::mla_get_sortable_columns( ) );
			$found_current = false;
			foreach ($default_orderby as $key => $value ) {
				if ( $option == $value[0] ) {
					$found_current = true;
					break;
				}
			}

			if ( ! $found_current ) {
				MLAOptions::mla_delete_option( MLAOptions::MLA_DEFAULT_ORDERBY );
				$option = MLAOptions::mla_get_option( MLAOptions::MLA_DEFAULT_ORDERBY );
			}

			$query['orderby'] = $option;
		}

		$option = MLAOptions::mla_get_option( MLAOptions::MLA_MEDIA_MODAL_ORDER );
		if ( 'default' != $option ) {
			$query['order'] = $option;
		}

		$query['post_type'] = 'attachment';
		$query['post_status'] = 'inherit';
		if ( current_user_can( get_post_type_object( 'attachment' )->cap->read_private_posts ) ) {
			$query['post_status'] .= ',private';
		}

		$query = MLAData::mla_query_media_modal_items( $query, $offset, $count );
		$posts = array_map( 'wp_prepare_attachment_for_js', $query->posts );
		$posts = array_filter( $posts );

		wp_send_json_success( $posts );
	}
} //Class MLAModal
?>