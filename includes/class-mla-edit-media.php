<?php
/**
 * Media Library Assistant Edit Media screen enhancements
 *
 * @package Media Library Assistant
 * @since 0.80
 */

/**
 * Class MLA (Media Library Assistant) Edit contains meta boxes for the Edit Media (advanced-form-edit.php) screen
 *
 * @package Media Library Assistant
 * @since 0.80
 */
class MLAEdit {
	/**
	 * Slug for localizing and enqueueing CSS - Add Media and related dialogs
	 *
	 * @since 1.20
	 *
	 * @var	string
	 */
	const JAVASCRIPT_EDIT_MEDIA_STYLES = 'mla-edit-media-style';

	/**
	 * Slug for localizing and enqueueing JavaScript - Add Media and related dialogs
	 *
	 * @since 1.20
	 *
	 * @var	string
	 */
	const JAVASCRIPT_EDIT_MEDIA_SLUG = 'mla-edit-media-scripts';

	/**
	 * Object name for localizing JavaScript - Add Media and related dialogs
	 *
	 * @since 1.20
	 *
	 * @var	string
	 */
	const JAVASCRIPT_EDIT_MEDIA_OBJECT = 'mla_edit_media_vars';

	/**
	 * Initialization function, similar to __construct()
	 *
	 * @since 0.80
	 *
	 * @return	void
	 */
	public static function initialize() {
		/*
		 * WordPress 3.5 uses the edit-form-advanced.php file for the Edit Media
		 * page. This supports all the standard meta-boxes for post types.
		 */
		if ( MLATest::$wordpress_3point5_plus ) {
			add_action( 'admin_init', 'MLAEdit::mla_admin_init_action' );

			add_action( 'admin_enqueue_scripts', 'MLAEdit::mla_admin_enqueue_scripts_action' );

			add_action( 'add_meta_boxes', 'MLAEdit::mla_add_meta_boxes_action', 10, 2 );

			// apply_filters( 'post_updated_messages', $messages ) in wp-admin/edit-form-advanced.php
			add_filter( 'post_updated_messages', 'MLAEdit::mla_post_updated_messages_filter', 10, 1 );

			// do_action in wp-admin/includes/meta-boxes.php function attachment_submit_meta_box
			add_action( 'attachment_submitbox_misc_actions', 'MLAEdit::mla_attachment_submitbox_action' );

			// do_action in wp-includes/post.php function wp_insert_post
			add_action( 'edit_attachment', 'MLAEdit::mla_edit_attachment_action', 10, 1 );

			// apply_filters( 'admin_title', $admin_title, $title ) in /wp-admin/admin-header.php
			add_filter( 'admin_title', 'MLAEdit::mla_edit_add_help_tab', 10, 2 );
		} // $wordpress_3point5_plus
	}

	/**
	 * Adds Custom Field support to the Edit Media screen.
	 * Declared public because it is an action.
	 *
	 * @since 0.80
	 *
	 * @return	void	echoes the HTML markup for the label and value
	 */
	public static function mla_admin_init_action( ) {
		static $mc_att_category_metabox = array();

		/*
		 * Enable the enhanced "Media Categories" searchable metaboxes for hiearchical taxonomies
		 */
		if ( class_exists( 'Media_Categories' ) &&
			( ( 'checked' == MLAOptions::mla_get_option( MLAOptions::MLA_MEDIA_MODAL_DETAILS_CATEGORY_METABOX ) ) ||
			  ( 'checked' == MLAOptions::mla_get_option( MLAOptions::MLA_MEDIA_MODAL_DETAILS_TAG_METABOX ) ) ) ) {
			$taxonomies = get_taxonomies( array ( 'show_ui' => true ), 'objects' );

			foreach ( $taxonomies as $key => $value ) {
				if ( MLAOptions::mla_taxonomy_support( $key ) ) {
					if ( $value->hierarchical ) {
						if ( 'checked' == MLAOptions::mla_get_option( MLAOptions::MLA_MEDIA_MODAL_DETAILS_CATEGORY_METABOX ) ) {
							$mc_att_category_metabox[] = new Media_Categories( $key );
						}
					} else { // hierarchical
						if ( 'checked' == MLAOptions::mla_get_option( MLAOptions::MLA_MEDIA_MODAL_DETAILS_TAG_METABOX ) ) {
							$mc_att_category_metabox[] = new Media_Categories( $key );
						}
					} // flat
				} // is supported
			} // foreach
		} // class_exists

		add_post_type_support( 'attachment', 'custom-fields' );
	}

	/**
	 * Load the plugin's Style Sheet and Javascript files
	 *
	 * @since 1.71
	 *
	 * @param	string	Name of the page being loaded
	 *
	 * @return	void
	 */
	public static function mla_admin_enqueue_scripts_action( $page_hook ) {
		if ( ( 'post.php' != $page_hook ) || ( ! isset( $_REQUEST['post'] ) ) || ( ! isset( $_REQUEST['action'] ) ) || ( 'edit' != $_REQUEST['action'] ) ) {
			return;
		}
		
		$post = get_post( $_REQUEST['post'] );
		if ( 'attachment' != $post->post_type ) {
			return;
		}

		/*
		 * Register and queue the style sheet, if needed
		 */
		//wp_register_style( self::JAVASCRIPT_EDIT_MEDIA_STYLES, MLA_PLUGIN_URL . 'css/mla-edit-media-style.css', false, MLA::CURRENT_MLA_VERSION );
		//wp_enqueue_style( self::JAVASCRIPT_EDIT_MEDIA_STYLES );

		$suffix = defined('SCRIPT_DEBUG') && SCRIPT_DEBUG ? '' : '.min';
		wp_enqueue_script( self::JAVASCRIPT_EDIT_MEDIA_SLUG, MLA_PLUGIN_URL . "js/mla-edit-media-scripts{$suffix}.js", 
			array( 'post', 'wp-lists', 'suggest', 'jquery' ), MLA::CURRENT_MLA_VERSION, false );
		$script_variables = array(
			'comma' => _x( ',', 'tag_delimiter', 'media-library-assistant' ),
			'Ajax_Url' => admin_url( 'admin-ajax.php' ) 
		);
		wp_localize_script( self::JAVASCRIPT_EDIT_MEDIA_SLUG, self::JAVASCRIPT_EDIT_MEDIA_OBJECT, $script_variables );
	}

	/**
	 * Adds mapping update messages for display at the top of the Edit Media screen.
	 * Declared public because it is a filter.
	 *
	 * @since 1.10
	 *
	 * @param	array	messages for the Edit screen
	 *
	 * @return	array	updated messages
	 */
	public static function mla_post_updated_messages_filter( $messages ) {
	if ( isset( $messages['attachment'] ) ) {
		$messages['attachment'][101] = __( 'Custom field mapping updated.', 'media-library-assistant' );
		$messages['attachment'][102] = __('IPTC/EXIF mapping updated.', 'media-library-assistant' );
	}

	return $messages;
	} // mla_post_updated_messages_filter

	/**
	 * Adds Last Modified date to the Submit box on the Edit Media screen.
	 * Declared public because it is an action.
	 *
	 * @since 0.80
	 *
	 * @return	void	echoes the HTML markup for the label and value
	 */
	public static function mla_attachment_submitbox_action( ) {
		global $post;

		/* translators: date_i18n format for last modified date and time */
		$date = date_i18n( __( 'M j, Y @ G:i', 'media-library-assistant' ), strtotime( $post->post_modified ) );
		echo '<div class="misc-pub-section curtime">' . "\r\n";
		echo '<span id="timestamp">' . sprintf(__( 'Last modified', 'media-library-assistant' ) . ": <b>%1\$s</b></span>\r\n", $date);
		echo "</div><!-- .misc-pub-section -->\r\n";
		echo '<div class="misc-pub-section mla-links">' . "\r\n";

		$view_args = array( 'page' => MLA::ADMIN_PAGE_SLUG, 'mla_item_ID' => $post->ID );
		if ( isset( $_REQUEST['mla_source'] ) ) {
			$view_args['mla_source'] = $_REQUEST['mla_source'];
		}

		echo '<span id="mla_metadata_links" style="font-weight: bold; line-height: 2em">';

		echo '<a href="' . add_query_arg( $view_args, wp_nonce_url( 'upload.php?mla_admin_action=' . MLA::MLA_ADMIN_SINGLE_CUSTOM_FIELD_MAP, MLA::MLA_ADMIN_NONCE ) ) . '" title="' . __( 'Map Custom Field metadata for this item', 'media-library-assistant' ) . '">' . __( 'Map Custom Field Metadata', 'media-library-assistant' ) . '</a><br>';

		echo '<a href="' . add_query_arg( $view_args, wp_nonce_url( 'upload.php?mla_admin_action=' . MLA::MLA_ADMIN_SINGLE_MAP, MLA::MLA_ADMIN_NONCE ) ) . '" title="' . __( 'Map IPTC/EXIF metadata for this item', 'media-library-assistant' ) . '">' . __( 'Map IPTC/EXIF Metadata', 'media-library-assistant' ) . '</a>';

		echo "</span>\r\n";
		echo "</div><!-- .misc-pub-section -->\r\n";
	} // mla_attachment_submitbox_action

	/**
	 * Registers meta boxes for the Edit Media screen.
	 * Declared public because it is an action.
	 *
	 * @since 0.80
	 *
	 * @param	string	type of the current post, e.g., 'attachment' (optional, default 'unknown') 
	 * @param	object	current post (optional, default (object) array ( 'ID' => 0 ))
	 *
	 * @return	void
	 */
	public static function mla_add_meta_boxes_action( $post_type = 'unknown', $post = NULL ) {
		/*
		 * Plugins call this action with varying numbers of arguments!
		 */
		if ( NULL == $post ) {
			$post = (object) array ( 'ID' => 0 );
		}

		if ( 'attachment' != $post_type ) {
			return;
		}
		
		/*
		 * Use the mla_hierarchical_meta_box callback function for MLA supported taxonomies
		 */
		global $wp_meta_boxes;
		$screen = convert_to_screen( 'attachment' );
		$page = $screen->id;
		$taxonomies = get_taxonomies( array ( 'show_ui' => true ), 'objects' );

		foreach ( $taxonomies as $key => $value ) {
			if ( MLAOptions::mla_taxonomy_support( $key ) ) {
				if ( $value->hierarchical ) {
					if ( 'checked' == MLAOptions::mla_get_option( MLAOptions::MLA_MEDIA_MODAL_DETAILS_CATEGORY_METABOX ) ) {
						foreach ( array_keys( $wp_meta_boxes[$page] ) as $a_context ) {
							foreach ( array('high', 'sorted', 'core', 'default', 'low') as $a_priority ) {
								if ( isset( $wp_meta_boxes[$page][$a_context][$a_priority][ $key . 'div' ] ) ) {
									$box = &$wp_meta_boxes[$page][$a_context][$a_priority][ $key . 'div' ];

									if ( 'post_categories_meta_box' == $box['callback'] ) {
										$box['callback'] = 'MLAEdit::mla_hierarchical_meta_box';
									}
								} // isset $box
							} // foreach priority
						} // foreach context
					}
				} else { // hierarchical
					if ( 'checked' == MLAOptions::mla_get_option( MLAOptions::MLA_MEDIA_MODAL_DETAILS_TAG_METABOX ) ) {
						continue;
					}
				} // flat
			} // is supported
		} // foreach

		add_meta_box( 'mla-parent-info', __( 'Parent Info', 'media-library-assistant' ), 'MLAEdit::mla_parent_info_handler', 'attachment', 'normal', 'core' );
		add_meta_box( 'mla-menu-order', __( 'Menu Order', 'media-library-assistant' ), 'MLAEdit::mla_menu_order_handler', 'attachment', 'normal', 'core' );

		$image_metadata = get_metadata( 'post', $post->ID, '_wp_attachment_metadata', true );
		if ( !empty( $image_metadata ) ) {
			add_meta_box( 'mla-image-metadata', __( 'Attachment Metadata', 'media-library-assistant' ), 'MLAEdit::mla_image_metadata_handler', 'attachment', 'normal', 'core' );
		}

		if ( MLAOptions::$process_featured_in ) {
			add_meta_box( 'mla-featured-in', __( 'Featured in', 'media-library-assistant' ), 'MLAEdit::mla_featured_in_handler', 'attachment', 'normal', 'core' );
		}

		if ( MLAOptions::$process_inserted_in ) {
			add_meta_box( 'mla-inserted-in', __( 'Inserted in', 'media-library-assistant' ), 'MLAEdit::mla_inserted_in_handler', 'attachment', 'normal', 'core' );
		}

		if ( MLAOptions::$process_gallery_in ) {
			add_meta_box( 'mla-gallery-in', __( 'Gallery in', 'media-library-assistant' ), 'MLAEdit::mla_gallery_in_handler', 'attachment', 'normal', 'core' );
		}

		if ( MLAOptions::$process_mla_gallery_in ) {
			add_meta_box( 'mla-mla-gallery-in', __( 'MLA Gallery in', 'media-library-assistant' ), 'MLAEdit::mla_mla_gallery_in_handler', 'attachment', 'normal', 'core' );
		}
	} // mla_add_meta_boxes_action

	/**
	 * Add contextual help tabs to the WordPress Edit Media page
	 *
	 * @since 0.90
	 *
	 * @param	string	title as shown on the screen
	 * @param	string	title as shown in the HTML header
	 *
	 * @return	void
	 */
	public static function mla_edit_add_help_tab( $admin_title, $title ) {
		$screen = get_current_screen();

		if ( ( 'attachment' != $screen->id ) || ( 'attachment' != $screen->post_type ) || ( 'post' != $screen->base ) ) {
			return $admin_title;
		}

		$template_array = MLAData::mla_load_template( 'help-for-edit_attachment.tpl' );
		if ( empty( $template_array ) ) {
			return $admin_title;
		}

		/*
		 * Provide explicit control over tab order
		 */
		$tab_array = array();

		foreach ( $template_array as $id => $content ) {
			$match_count = preg_match( '#\<!-- title="(.+)" order="(.+)" --\>#', $content, $matches, PREG_OFFSET_CAPTURE );

			if ( $match_count > 0 ) {
				$tab_array[ $matches[ 2 ][ 0 ] ] = array(
					 'id' => $id,
					'title' => $matches[ 1 ][ 0 ],
					'content' => $content 
				);
			} else {
				/* translators: 1: function name 2: template key */
				error_log( sprintf( _x( 'ERROR: %1$s discarding "%2$s"; no title/order', 'error_log', 'media-library-assistant' ), 'mla_edit_add_help_tab', $id ), 0 );
			}
		}

		ksort( $tab_array, SORT_NUMERIC );
		foreach ( $tab_array as $indx => $value ) {
			$screen->add_help_tab( $value );
		}

	return $admin_title;
	}

	/**
	 * Where-used values for the current item
	 *
	 * This array contains the Featured/Inserted/Gallery/MLA Gallery references for the item.
	 * The array is built once each page load and cached for subsequent calls.
	 *
	 * @since 0.80
	 *
	 * @var	array
	 */
	private static $mla_references = null;

	/**
	 * Renders the Parent Info meta box on the Edit Media page.
	 * Declared public because it is a callback function.
	 *
	 * @since 0.80
	 *
	 * @param	object	current post
	 *
	 * @return	void	echoes the HTML markup for the meta box content
	 */
	public static function mla_parent_info_handler( $post ) {
		if ( is_null( self::$mla_references ) ) {
			self::$mla_references = MLAData::mla_fetch_attachment_references( $post->ID, $post->post_parent );
		}

		if ( is_array( self::$mla_references ) ) {
			if ( empty(self::$mla_references['parent_title'] ) ) {
				$parent_info = self::$mla_references['parent_errors'];
			} else {
				$parent_info = sprintf( '(%1$s) %2$s %3$s', self::$mla_references['parent_type'], self::$mla_references['parent_title'], self::$mla_references['parent_errors'] );
			}
		} // is_array

		echo '<label class="screen-reader-text" for="mla_post_parent">' . __( 'Post Parent', 'media-library-assistant' ) . '</label><input name="mla_post_parent" type="text" size="4" id="mla_post_parent" value="' . $post->post_parent . "\" />\r\n";
		echo '<label class="screen-reader-text" for="mla_parent_info">' . __( 'Parent Info', 'media-library-assistant' ) . '</label><input class="readonly" name="mla_parent_info" type="text" readonly="readonly" size="60" id="mla_parent_info" value="' . esc_attr( $parent_info ) . "\" />\r\n";
	}

	/**
	 * Renders the Menu Order meta box on the Edit Media page.
	 * Declared public because it is a callback function.
	 *
	 * @since 0.80
	 *
	 * @param	object	current post
	 *
	 * @return	void	echoes the HTML markup for the meta box content
	 */
	public static function mla_menu_order_handler( $post ) {

		echo '<label class="screen-reader-text" for="mla_menu_order">' . __( 'Menu Order', 'media-library-assistant' ) . '</label><input name="mla_menu_order" type="text" size="4" id="mla_menu_order" value="' . $post->menu_order . "\" />\r\n";
	}

	/**
	 * Renders the Image Metadata meta box on the Edit Media page.
	 * Declared public because it is a callback function.
	 *
	 * @since 0.80
	 *
	 * @param	object	current post
	 *
	 * @return	void	echoes the HTML markup for the meta box content
	 */
	public static function mla_image_metadata_handler( $post ) {
		$metadata = MLAData::mla_fetch_attachment_metadata( $post->ID );

		if ( isset( $metadata['mla_wp_attachment_metadata'] ) ) {
			$value = var_export( $metadata['mla_wp_attachment_metadata'], true );
		} else {
			$value = '';
		}

		echo '<label class="screen-reader-text" for="mla_image_metadata">' . __( 'Attachment Metadata', 'media-library-assistant' ) . '</label><textarea class="readonly" id="mla_image_metadata" rows="5" cols="80" readonly="readonly" name="mla_image_metadata" >' . esc_textarea( $value ) . "</textarea>\r\n";
	}

	/**
	 * Renders the Featured in meta box on the Edit Media page.
	 * Declared public because it is a callback function.
	 *
	 * @since 0.80
	 *
	 * @param	object	current post
	 *
	 * @return	void	echoes the HTML markup for the meta box content
	 */
	public static function mla_featured_in_handler( $post ) {
		if ( is_null( self::$mla_references ) ) {
			self::$mla_references = MLAData::mla_fetch_attachment_references( $post->ID, $post->post_parent );
		}

		if ( is_array( self::$mla_references ) ) {
			$features = '';

			foreach ( self::$mla_references['features'] as $feature_id => $feature ) {
				if ( $feature_id == $post->post_parent ) {
					$parent = __( 'PARENT', 'media-library-assistant' ) . ' ';
				} else {
					$parent = '';
				}

				$features .= sprintf( '%1$s (%2$s %3$s), %4$s', /*$1%s*/ $parent, /*$2%s*/ $feature->post_type, /*$3%s*/ $feature_id, /*$4%s*/ $feature->post_title ) . "\r\n";
			} // foreach $feature
		}

		echo '<label class="screen-reader-text" for="mla_featured_in">' . __( 'Featured in', 'media-library-assistant' ) . '</label><textarea class="readonly" id="mla_featured_in" rows="5" cols="80" readonly="readonly" name="mla_featured_in" >' . esc_textarea( $features ) . "</textarea>\r\n";
	}

	/**
	 * Renders the Inserted in meta box on the Edit Media page.
	 * Declared public because it is a callback function.
	 *
	 * @since 0.80
	 *
	 * @param	object	current post
	 *
	 * @return	void	echoes the HTML markup for the meta box content
	 */
	public static function mla_inserted_in_handler( $post ) {
		if ( is_null( self::$mla_references ) ) {
			self::$mla_references = MLAData::mla_fetch_attachment_references( $post->ID, $post->post_parent );
		}

		if ( is_array( self::$mla_references ) ) {
			$inserts = '';

			foreach ( self::$mla_references['inserts'] as $file => $insert_array ) {
				$inserts .= $file . "\r\n";

				foreach ( $insert_array as $insert ) {
					if ( $insert->ID == $post->post_parent ) {
						$parent = '  ' . __( 'PARENT', 'media-library-assistant' ) . ' ';
					} else {
						$parent = '  ';
					}

					$inserts .= sprintf( '%1$s (%2$s %3$s), %4$s', /*$1%s*/ $parent, /*$2%s*/ $insert->post_type, /*$3%s*/ $insert->ID, /*$4%s*/ $insert->post_title ) . "\r\n";
				} // foreach $insert
			} // foreach $file
		} // is_array

		echo '<label class="screen-reader-text" for="mla_inserted_in">' . __( 'Inserted in', 'media-library-assistant' ) . '</label><textarea class="readonly" id="mla_inserted_in" rows="5" cols="80" readonly="readonly" name="mla_inserted_in" >' . esc_textarea( $inserts ) . "</textarea>\r\n";
	}

	/**
	 * Renders the Gallery in meta box on the Edit Media page.
	 * Declared public because it is a callback function.
	 *
	 * @since 0.80
	 *
	 * @param	object	current post
	 *
	 * @return	void	echoes the HTML markup for the meta box content
	 */
	public static function mla_gallery_in_handler( $post ) {
		if ( is_null( self::$mla_references ) ) {
			self::$mla_references = MLAData::mla_fetch_attachment_references( $post->ID, $post->post_parent );
		}

		$galleries = '';

		if ( is_array( self::$mla_references ) ) {
			foreach ( self::$mla_references['galleries'] as $gallery_id => $gallery ) {
				if ( $gallery_id == $post->post_parent ) {
					$parent = __( 'PARENT', 'media-library-assistant' ) . ' ';
				} else {
					$parent = '';
				}

				$galleries .= sprintf( '%1$s (%2$s %3$s), %4$s', /*$1%s*/ $parent, /*$2%s*/ $gallery['post_type'], /*$3%s*/ $gallery_id, /*$4%s*/ $gallery['post_title'] ) . "\r\n";
			} // foreach $feature
		}

		echo '<label class="screen-reader-text" for="mla_gallery_in">' . __( 'Gallery in', 'media-library-assistant' ) . '</label><textarea class="readonly" id="mla_gallery_in" rows="5" cols="80" readonly="readonly" name="mla_gallery_in" >' . esc_textarea( $galleries ) . "</textarea>\r\n";
	}

	/**
	 * Renders the Gallery in meta box on the Edit Media page.
	 * Declared public because it is a callback function.
	 *
	 * @since 0.80
	 *
	 * @param	object	current post
	 *
	 * @return	void	echoes the HTML markup for the meta box content
	 */
	public static function mla_mla_gallery_in_handler( $post ) {
		if ( is_null( self::$mla_references ) ) {
			self::$mla_references = MLAData::mla_fetch_attachment_references( $post->ID, $post->post_parent );
		}

		$galleries = '';

		if ( is_array( self::$mla_references ) ) {
			foreach ( self::$mla_references['mla_galleries'] as $gallery_id => $gallery ) {
				if ( $gallery_id == $post->post_parent ) {
					$parent = __( 'PARENT', 'media-library-assistant' ) . ' ';
				} else {
					$parent = '';
				}

				$galleries .= sprintf( '%1$s (%2$s %3$s), %4$s', /*$1%s*/ $parent, /*$2%s*/ $gallery['post_type'], /*$3%s*/ $gallery_id, /*$4%s*/ $gallery['post_title'] ) . "\r\n";
			} // foreach $feature
		}

		echo '<label class="screen-reader-text" for="mla_mla_gallery_in">' . __( 'MLA Gallery in', 'media-library-assistant' ) . '</label><textarea class="readonly" id="mla_mla_gallery_in" rows="5" cols="80" readonly="readonly" name="mla_mla_gallery_in" >' . esc_textarea( $galleries ) . "</textarea>\r\n";
	}

	/**
	 * Saves updates from the Edit Media screen.
	 * Declared public because it is an action.
	 *
	 * @since 0.80
	 *
	 * @param	integer	ID of the current post
	 *
	 * @return	void
	 */
	public static function mla_edit_attachment_action( $post_ID ) {
		$new_data = array();
		if ( isset( $_REQUEST['mla_post_parent'] ) ) {
			$new_data['post_parent'] = $_REQUEST['mla_post_parent'];
		}

		if ( isset( $_REQUEST['mla_menu_order'] ) ) {
			$new_data['menu_order'] = $_REQUEST['mla_menu_order'];
		}

		if ( !empty( $new_data ) ) {
			MLAData::mla_update_single_item( $post_ID, $new_data );
		}
	} // mla_edit_attachment_action
	
	/**
	 * Display taxonomy "checklist" form fields
	 *
	 * Adapted from the WordPress post_categories_meta_box() in /wp-admin/includes/meta-boxes.php.
	 * Includes the "? Search" area to filter the term checklist by entering part
	 * or all of a word/phrase in the term label.
	 * Output to the Media/Edit Media screen and to the Media Manager Modal Window.
	 *
	 * @since 1.71
	 *
	 * @param object The current post
	 * @param array The meta box parameters
	 *
	 * @return void Echoes HTML for the form fields
	 */
	public static function mla_hierarchical_meta_box( $post, $box ) {
		$defaults = array('taxonomy' => 'category', 'in_modal' => false );
		$post_id = $post->ID;
		
		if ( !isset( $box['args'] ) || !is_array( $box['args'] ) ) {
			$args = array();
		} else {
			$args = $box['args'];
		}
		
		extract( wp_parse_args( $args, $defaults ), EXTR_SKIP );
		$tax = get_taxonomy( $taxonomy );
		$name = ( $taxonomy == 'category' ) ? 'post_category' : 'tax_input[' . $taxonomy . ']';

		if ( $in_modal ) {
			$div_taxonomy_id = "taxonomy-{$taxonomy}";
			$tabs_ul_id = "{$taxonomy}-tabs";
			$tab_all_id = "{$taxonomy}-all";
			$tab_all_ul_id = "{$taxonomy}checklist";
			$tab_pop_id = "{$taxonomy}-pop";
			$tab_pop_ul_id = "{$taxonomy}checklist-pop";
			$input_terms_name = "attachments[{$post_id}][{$name}]";
			$input_terms_id = "{$name}-id";
			$div_adder_id = "{$taxonomy}-adder";
			$link_adder_id = "{$taxonomy}-add-toggle";
			$link_adder_p_id = "{$taxonomy}-add";
			$div_search_id = "{$taxonomy}-searcher";
			$link_search_id = "{$taxonomy}-search-toggle";
			$link_search_p_id = "{$taxonomy}-search";
			$input_new_name = "new{$taxonomy}";
			$input_new_id = "new{$taxonomy}";
			$input_new_parent_name = "new{$taxonomy}_parent";
			$input_new_submit_id = "{$taxonomy}-add-submit";
			$span_ajax_id = "{$taxonomy}-ajax-response";
		} else {
			$div_taxonomy_id = "taxonomy-{$taxonomy}";
			$tabs_ul_id = "{$taxonomy}-tabs";
			$tab_all_id = "{$taxonomy}-all";
			$tab_all_ul_id = "{$taxonomy}checklist";
			$tab_pop_id = "{$taxonomy}-pop";
			$tab_pop_ul_id = "{$taxonomy}checklist-pop";
			$input_terms_name = "{$name}[]";
			$input_terms_id = "{$name}-id";
			$div_adder_id = "{$taxonomy}-adder";
			$link_adder_id = "{$taxonomy}-add-toggle";
			$link_adder_p_id = "{$taxonomy}-add";
			$div_search_id = "{$taxonomy}-searcher";
			$link_search_id = "{$taxonomy}-search-toggle";
			$link_search_p_id = "{$taxonomy}-search";
			$input_new_name = "new{$taxonomy}";
			$input_new_id = "new{$taxonomy}";
			$input_new_parent_name = "new{$taxonomy}_parent";
			$input_new_submit_id = "{$taxonomy}-add-submit";
			$span_ajax_id = "{$taxonomy}-ajax-response";
		}
		

		?>
		<div id="<?php echo $div_taxonomy_id; ?>" class="categorydiv">
			<ul id="<?php echo $tabs_ul_id; ?>" class="category-tabs">
				<li class="tabs"><a href="#<?php echo $tab_all_id; ?>"><?php echo $tax->labels->all_items; ?></a></li>
				<li class="hide-if-no-js"><a href="#<?php echo $tab_pop_id; ?>"><?php _e( 'Most Used' ); ?></a></li>
			</ul>
	
			<div id="<?php echo $tab_pop_id; ?>" class="tabs-panel" style="display: none;">
				<ul id="<?php echo $tab_pop_ul_id; ?>" class="categorychecklist form-no-clear" >
					<?php $popular_ids = wp_popular_terms_checklist($taxonomy); ?>
				</ul>
			</div>
	
			<div id="<?php echo $tab_all_id; ?>" class="tabs-panel">
				<?php
				echo "<input type='hidden' name='{$input_terms_name}' id='{$input_terms_id}' value='0' />"; // Allows for an empty term set to be sent. 0 is an invalid Term ID and will be ignored by empty() checks.
				?>
				<ul id="<?php echo $tab_all_ul_id; ?>" data-wp-lists="list:<?php echo $taxonomy?>" class="categorychecklist form-no-clear">
					<?php wp_terms_checklist($post->ID, array( 'taxonomy' => $taxonomy, 'popular_cats' => $popular_ids ) ) ?>
				</ul>
			</div>
		<?php if ( current_user_can($tax->cap->edit_terms) ) : ?>
				<div id="<?php echo $div_adder_id; ?>" class="wp-hidden-children">
					<h4>
						<a id="<?php echo $link_adder_id; ?>" href="#<?php echo $link_adder_p_id; ?>" class="hide-if-no-js">
							<?php
								/* translators: %s: add new taxonomy label */
								printf( __( '+ %s', 'media-library-assistant' ), $tax->labels->add_new_item );
							?>
						</a>
						&nbsp;&nbsp;
						<a id="<?php echo $link_search_id; ?>" href="#<?php echo $link_search_p_id; ?>" class="hide-if-no-js">
							<?php
								echo __( '? Search', 'media-library-assistant' );
							?>
						</a>
					</h4>
					<p id="<?php echo $link_adder_p_id; ?>" class="category-add wp-hidden-child">
						<label class="screen-reader-text" for="<?php echo $input_new_name; ?>"><?php echo $tax->labels->add_new_item; ?></label>
						<input type="text" name="<?php echo $input_new_name; ?>" id="<?php echo $input_new_id; ?>" class="form-required form-input-tip" value="<?php echo esc_attr( $tax->labels->new_item_name ); ?>" aria-required="true"/>
						<label class="screen-reader-text" for="<?php echo $input_new_parent_name; ?>">
							<?php echo $tax->labels->parent_item_colon; ?>
						</label>
						<?php wp_dropdown_categories( array( 'taxonomy' => $taxonomy, 'hide_empty' => 0, 'name' => $input_new_parent_name, 'orderby' => 'name', 'hierarchical' => 1, 'show_option_none' => '&mdash; ' . $tax->labels->parent_item . ' &mdash;' ) ); ?>
						<input type="button" id="<?php echo $input_new_submit_id; ?>" data-wp-lists="add:<?php echo $taxonomy ?>checklist:<?php echo $taxonomy ?>-add" class="button category-add-submit" value="<?php echo esc_attr( $tax->labels->add_new_item ); ?>" />
						<?php wp_nonce_field( 'add-'.$taxonomy, '_ajax_nonce-add-'.$taxonomy, false ); ?>
						<span id="<?php echo $span_ajax_id; ?>"></span>
					</p>
				</div>
				<div id="<?php echo $div_search_id; ?>" class="wp-hidden-children">
					<p id="<?php echo $link_search_p_id; ?>" class="category-add wp-hidden-child">
						<label class="screen-reader-text" for="search-<?php echo $taxonomy; ?>"><?php echo $tax->labels->search_items; ?></label>
						<input type="text" name="search-<?php echo $taxonomy; ?>" id="search-<?php echo $taxonomy; ?>" class="form-required form-input-tip" value="<?php echo esc_attr( $tax->labels->search_items ); ?>" aria-required="true"/>
						<?php wp_nonce_field( 'search-'.$taxonomy, '_ajax_nonce-search-'.$taxonomy, false ); ?>
						<span id="<?php echo $taxonomy; ?>-ajax-response"></span>
					</p>
				</div>
			<?php endif; ?>
		</div>
		<?php
	} // mla_hierarchical_meta_box
} //Class MLAEdit
?>