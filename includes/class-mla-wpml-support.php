<?php
/**
 * Media Library Assistant WPML Support classes
 *
 * This file is conditionally loaded in MLA::initialize after a check for WPML presence.
 *
 * @package Media Library Assistant
 * @since 2.11
 */

/**
 * Class MLA (Media Library Assistant) WPML provides support for the WPML Multilingual CMS
 * family of plugins, including WPML Media
 *
 * @package Media Library Assistant
 * @since 2.11
 */
class MLA_WPML {
	/**
	 * Initialization function, similar to __construct()
	 *
	 * This function contains add_action and add_filter calls.
	 *
	 * @since 2.11
	 *
	 * @return	void
	 */
	public static function initialize() {

		add_action( 'admin_init', 'MLA_WPML::mla_wpml_admin_init_action' );

		/*
		 * Defined in wp-admin/edit-form-advanced.php
		 */
		add_filter( 'post_updated_messages', 'MLA_WPML::mla_wpml_post_updated_messages', 10, 1 );

		/*
		 * Defined in wp-includes/post.php function wp_insert_post
		 */
		//add_filter( 'edit_attachment', 'MLA_WPML::edit_attachment', 10, 2 );

		/*
		 * Defined in /media-library-assistant/includes/class-mla-main.php
		 */
		add_action( 'mla_list_table_custom_admin_action', 'MLA_WPML::mla_list_table_custom_admin_action', 10, 2 );
	}

	/**
	 * Add the plugin's filter/action handlers
	 *
	 * @since 2.11
	 *
	 * @return	void
	 */
	public static function mla_wpml_admin_init_action() {
		/*
		 * Add styles for the language management column
		 */
		if ( isset( $_REQUEST['page'] ) && ( MLA::ADMIN_PAGE_SLUG == $_REQUEST['page'] ) ) {
			add_action( 'admin_print_styles', 'MLA_WPML_Table::mla_list_table_add_icl_styles' );
		}
	}

	/**
	 * Process an MLA_List_Table custom single action
	 *
	 * Add a duplicate translation for an item, then redirect to the Media/Edit Media screen.
	 *
	 * @since 2.11
	 *
	 * @param	string	$mla_admin_action	the requested action.
	 * @param	integer	$mla_item_ID		zero (0), or the affected attachment.
	 *
	 * @return	void
	 */
	public static function mla_list_table_custom_admin_action( $mla_admin_action, $mla_item_ID ) {
		if ( 'wpml_create_translation' == $mla_admin_action ) {
			$new_item = WPML_Media::create_duplicate_attachment( $mla_item_ID, $_REQUEST['mla_parent_ID'], $_REQUEST['lang'] );
			$view_args = isset( $_REQUEST['mla_source'] ) ? array( 'mla_source' => $_REQUEST['mla_source']) : array();
			wp_redirect( add_query_arg( $view_args, admin_url( 'post.php' ) . '?action=edit&post=' . $new_item . '&message=201' ), 302 );
			exit;
		}
	} // mla_list_table_custom_admin_action

	/**
	 * Adds mapping update messages for display at the top of the Edit Media screen.
	 * Declared public because it is a filter.
	 *
	 * @since 2.11
	 *
	 * @param	array	messages for the Edit screen
	 *
	 * @return	array	updated messages
	 */
	public static function mla_wpml_post_updated_messages( $messages ) {
	if ( isset( $messages['attachment'] ) ) {
		$messages['attachment'][201] = __( 'Duplicate translation created; update as desired.', 'media-library-assistant' );
	}

	return $messages;
	} // mla_post_updated_messages_filter

	/**
	 * Taxonmy terms in all languages
	 *
	 * @since 2.11
	 *
	 * @var	array
	 */
	private static $tax_input = array();
	
	/**
	 * Build the $tax_input array
	 *
	 * Takes each term from the $taxonomies parameter and builds an array of
	 * language-specific term_id to term_id/term_name mappings for all languages.
	 *
	 * @since 2.11
	 * @uses MLA_WPML::$tax_input
	 *
	 * @param	array	$taxonomies	'tax_input' request parameter
	 *
	 */
	private static function _build_tax_input( $taxonomies ) {
		global $polylang;
		
		foreach ( $taxonomies as $taxonomy => $terms ) {
			// hierarchical taxonomy => array of term_id values; flat => string
			if ( is_array( $terms ) ) {
				self::$tax_input[ $taxonomy ]['taxonomy_type'] = 'hierarchical';
				foreach( $terms as $term ) {
					if ( 0 == $term ) {
						continue;
					}
					
					$translations = $polylang->model->get_translations( 'term', $term );
					foreach ( $translations as $language => $term_id ) {
						self::$tax_input[ $taxonomy ][ $language ][ $term_id ] = $term_id;
					} // for each language
				} // foreach term
			} else {
				self::$tax_input[ $taxonomy ]['taxonomy_type'] = 'flat';
				/*
				 * Convert names to IDs, get ID translations, convert back to names
				 */
				$term_names = array_map( 'trim', explode( ',', $terms ) );
				foreach ( $term_names as $term_name ) {
					if ( ! empty( $term_name ) ) {
						$term = get_term_by( 'name', $term_name, $taxonomy );
						$translations = $polylang->model->get_translations( 'term', $term->term_id );

						foreach ( $translations as $language => $term_id ) {
							$term = get_term_by( 'id', $term_id, $taxonomy );
							self::$tax_input[ $taxonomy ][ $language ][ $term_id ] = $term->name;
						} // for each language
					} // not empty
				} // foreach name
			}
		} // foreach taxonomy
	} // _build_tax_input

	/**
	 * Filter the $tax_input array to a specific language
	 *
	 * @since 2.11
	 *
	 * @param	array	$taxonomies	'tax_input' request parameter
	 * @param	integer	$post_id ID of the current post
	 *
	 * @return	array	updated $taxonomies
	 */
	private static function _apply_tax_input( $taxonomies, $post_id ) {
		global $polylang;
		
		$post_language = $polylang->model->get_post_language( $post_id );
		$post_language = $post_language->slug;
		
		$translated_taxonomies = array();
		foreach ( $taxonomies as $taxonomy => $terms ) {
			if ( isset( self::$tax_input[ $taxonomy ][ $post_language ] ) ) {
				$terms = self::$tax_input[ $taxonomy ][ $post_language ];
				if ( 'flat' == self::$tax_input[ $taxonomy ]['taxonomy_type'] ) {
					$terms = implode( ',', $terms );
				} else {
					// renumber the keys
					$terms = array_merge( $terms );
				}
				
				$translated_taxonomies[ $taxonomy ] = $terms;
			} // found language
		} // foreach taxonomy 

		return $translated_taxonomies;
	} // _apply_tax_input

	/**
	 * Filters taxonomy updates by language.
	 *
	 * @since 2.11
	 *
	 * @param	integer	ID of the current post
	 */
	public static function edit_attachment( $post_id ) {
		static $already_updating = false;
		
		//error_log( 'MLA_WPML::edit_attachment $post_id = ' . var_export( $post_id, true ), 0 );
		//error_log( 'MLA_WPML::edit_attachment $already_updating = ' . var_export( $already_updating, true ), 0 );
		//error_log( 'MLA_WPML::edit_attachment $_REQUEST = ' . var_export( $_REQUEST, true ), 0 );

		/*
		 * mla_update_single_item eventually calls this action again
		 */
		if ( $already_updating ) {
			return;
		}
		
		/*
		 * The category taxonomy (edit screens) is a special case because 
		 * post_categories_meta_box() changes the input name
		 */
		if ( isset( $_REQUEST['tax_input'] ) ) {
			$taxonomies = $_REQUEST['tax_input'];
		} else {
			$taxonomies = array();
		}

		if ( isset( $_REQUEST['post_category'] ) ) {
			$taxonomies['category'] = $_REQUEST['post_category'];
		}

		if ( ! empty( $taxonomies ) ) {
			self::_build_tax_input( $taxonomies );
			$taxonomies = self::_apply_tax_input( $taxonomies, $post_id );
		}
		
		//error_log( 'MLA_WPML::edit_attachment $taxonomies = ' . var_export( $taxonomies, true ), 0 );
		if ( !empty( $taxonomies ) ) {
			$already_updating = true; // prevent recursion
			MLAData::mla_update_single_item( $post_id, array(), $taxonomies );
			$already_updating = false;
		}
	} // edit_attachment

} // Class MLA_WPML

/**
 * Class MLA (Media Library Assistant) WPML Table provides support for the WPML Multilingual CMS
 * family of plugins, including WPML Media, for an MLA_List_Table object.
 *
 * @package Media Library Assistant
 * @since 2.11
 */
class MLA_WPML_Table {
	/**
	 * Reference to the MLA_List_Table object this object supports
	 *
	 * @since 2.11
	 *
	 * @var	object
	 */
	protected $mla_list_table = NULL;

	/**
	 * The constructor contains add_action and add_filter calls.
	 *
	 * @since 2.11
	 *
	 * @param	object	$table The MLA_List_Table object this object supports
	 *
	 * @return	void
	 */
	function __construct( $table ) {
		/*
		 * Save a reference to the parent MLA_List_Table object
		 */
		$this->mla_list_table = $table;
		
		/*
		 * Defined in /plugins/wpml-media/inc/wpml-media.class.php
		 */
		add_filter( 'wpml-media_view-upload-count', array( $this, 'mla_wpml_media_view_upload_count_filter' ), 10, 4 );
		add_filter( 'wpml-media_view-upload-page-count', array( $this, 'mla_wpml_media_view_upload_page_count_filter' ), 10, 2 );
		
		 /*
		  * Defined in /media-library-assistant/includes/class-mla-list-table.php
		  */
		add_filter( 'mla_list_table_get_columns', array( $this, 'mla_list_table_get_columns' ), 10, 1 );
		add_filter( 'mla_list_table_column_default', array( $this, 'mla_list_table_column_default' ), 10, 3 );
	}

	/**
	 * Handler for filter "wpml-media_view-upload-count" in 
	 * /plugins/wpml-media/inc/wpml-media.class.php
	 *
	 * Computes the number of attachments that satisfy a meta_query specification.
	 * The count is automatically made language-specific by WPML filters.
	 *
	 * @since 2.11
	 *
	 * @param	NULL	default return value if not replacing count
	 * @param	string	key/slug value for the selected view
	 * @param	string	HTML <a></a> tag for the link to the selected view
	 * @param	string	language code, e.g., 'en', 'es'
	 *
	 * @return	mixed	NULL to allow SQL query or replacement count value
	 */
	public function mla_wpml_media_view_upload_count_filter( $count, $key, $view, $lang ) {
		// extract the base URL and query parameters
		$href_count = preg_match( '/(href=["\'])([\s\S]+?)\?([\s\S]+?)(["\'])/', $view, $href_matches );	
		if ( $href_count ) {
			wp_parse_str( $href_matches[3], $href_args );

			// esc_url() converts & to #038;, which wp_parse_str does not strip
			if ( isset( $href_args['meta_query'] ) || isset( $href_args['#038;meta_query'] ) ) {
				$meta_view = $this->mla_list_table->mla_get_view( $key, '' );
				// extract the count value
				$href_count = preg_match( '/class="count">\(([^\)]*)\)/', $meta_view, $href_matches );	
				if ( $href_count ) {
					$count = array( $href_matches[1] );
				}
			}
		}

		return $count;
	}

	/**
	 * Handler for filter "wpml-media_view-upload-page-count" in /plugins/wpml-media/inc/wpml-media.class.php
	 *
	 * Computes the number of language-specific attachments that satisfy a meta_query specification.
	 * The count is made language-specific by WPML filters when the current_language is set.
	 *
	 * @since 2.11
	 *
	 * @param	NULL	default return value if not replacing count
	 * @param	string	language code, e.g., 'en', 'es'
	 *
	 * @return	mixed	NULL to allow SQL query or replacement count value
	 */
	public function mla_wpml_media_view_upload_page_count_filter( $count, $lang ) {
		global $sitepress;

		if ( isset( $_GET['meta_slug'] ) ) {
			$save_lang = $sitepress->get_current_language();
			$sitepress->switch_lang( $lang['code'] );
			$meta_view = $this->mla_list_table->mla_get_view( $_GET['meta_slug'], '' );
			$sitepress->switch_lang( $save_lang );

			// extract the count value
			$href_count = preg_match( '/class="count">\(([^\)]*)\)/', $meta_view, $href_matches );	
			if ( $href_count ) {
				$count = array( $href_matches[1] );
			}
		}

		return $count;
	}

	/**
	 * Table language column definitions
	 *
	 * Defined as static because it is used before the List_Table object is created.
	 *
	 * @since 2.11
	 *
	 * @var	array
	 */
	protected static $language_columns = NULL;

	/**
	 * Filter the MLA_List_Table columns
	 *
	 * Inserts the language columns just after the item thumbnail column.
	 * Defined as static because it is called before the List_Table object is created.
	 * Added as a filter when the file is loaded.
	 *
	 * @since 2.11
	 *
	 * @param	array	$columns An array of columns.
	 *					format: column_slug => Column Label
	 *
	 * @return	array	updated array of columns.
	 */
	public static function mla_list_table_get_columns( $columns ) {
		global $sitepress, $wpdb;

		if ( is_null( self::$language_columns ) && $sitepress->is_translated_post_type( 'attachment' ) ) {
			/*
			 * Build language management columns
			 */
			$current_language = $sitepress->get_current_language();
			$languages = $sitepress->get_active_languages();
			$view_status = isset( $_REQUEST['status'] ) ? $_REQUEST['status'] : '';

			if ( 'all' == $current_language ) {
				self::$language_columns[ 'language' ] = __( 'Language', 'wpml-media' );	
			}
			
			if ( 1 < count( $languages ) && $view_status != 'trash' ) {
				$language_codes = array();
				foreach ( $languages as $language ) {
					if ( $current_language != $language['code'] ) {
						$language_codes[] = $language['code'];
					}
				}

				$results = $wpdb->get_results( $wpdb->prepare("
					SELECT f.lang_code, f.flag, f.from_template, l.name
					FROM {$wpdb->prefix}icl_flags f
						JOIN {$wpdb->prefix}icl_languages_translations l ON f.lang_code = l.language_code
					WHERE l.display_language_code = %s AND f.lang_code IN(" . wpml_prepare_in( $language_codes ) . ")", $sitepress->get_admin_language() ) );

				$wp_upload_dir = wp_upload_dir();
				foreach ( $results as $result ) {
					if ( $result->from_template ) {
						$flag_path = $wp_upload_dir[ 'baseurl' ] . '/flags/';
					} else {
						$flag_path = ICL_PLUGIN_URL . '/res/flags/';
					}
					
					$flags[ $result->lang_code ] = '<img src="' . $flag_path . $result->flag . '" width="18" height="12" alt="' . $result->name . '" title="' . $result->name . '" />';
				}

				$flags_column = '';
				foreach ( $languages as $language ) {
					if ( isset( $flags[ $language[ 'code' ] ] ) )
						$flags_column .= $flags[ $language[ 'code' ] ];
				}

				self::$language_columns[ 'icl_translations' ] = $flags_column;
			} // multi-language not trash
		} // add columns
		
		if ( ! empty( self::$language_columns ) ) {
			$end = array_slice( $columns, 2) ;
			$columns = array_slice( $columns, 0, 2 );
			$columns = array_merge( $columns, self::$language_columns, $end );
		}
		
		return $columns;
	} // mla_list_table_get_columns_filter

	/**
	 * Add styles for the icl_translations table column
	 *
	 * @since 2.11
	 *
	 * @return	void	echoes CSS styles before returning
	 */
	public static function mla_list_table_add_icl_styles() {
		global $sitepress;
		
		$current_language = $sitepress->get_current_language();
		$languages = count( $sitepress->get_active_languages() );
		$view_status = isset( $_REQUEST['status'] ) ? $_REQUEST['status'] : '';

		if ( 1 < $languages && $view_status != 'trash' ) {
			$w = 22 * ( 'all' == $current_language ? $languages : $languages - 1 );
			echo '<style type="text/css">.column-icl_translations{width:' . $w . 'px;}.column-icl_translations img{margin:2px;}</style>';
		}
	}

	/**
	 * Supply a column value if no column-specific function has been defined
	 *
	 * Fills in the Language columns with the item's translation status values.
	 *
	 * @since 2.11
	 *
	 * @param	string	NULL, indicating no default content
	 * @param	array	A singular item (one full row's worth of data)
	 * @param	array	The name/slug of the column to be processed
	 *
	 * @return	string	Text or HTML to be placed inside the column
	 */
	public function mla_list_table_column_default( $content, $item, $column_name ) {
		global $sitepress;
		static $languages = NULL, $default_language, $current_language;

		if ( 'language' == $column_name ) {
			$item_language = $sitepress->get_language_for_element( $item->ID, 'post_attachment' );
			$content = $sitepress->get_display_language_name( $item_language, $sitepress->get_admin_language() );
		} elseif ('icl_translations' == $column_name ) {
			if ( is_null( $languages ) ) {
				$default_language  = $sitepress->get_default_language();
				$current_language = $sitepress->get_current_language();
				$languages = $sitepress->get_active_languages();
			}

			$trid = $sitepress->get_element_trid( $item->ID, 'post_attachment' );
			$translations = $sitepress->get_element_translations( $trid, 'post_attachment' ); // 

			$content = '';
			foreach( $languages as $language ) {
				if ( $language[ 'code' ] == $current_language ) {
					continue;
				}
				
				if ( isset( $translations[ $language[ 'code' ] ] ) && $translations[ $language[ 'code' ] ]->element_id ) {
					// Translation exists
					$img = 'edit_translation.png';
					$alt = sprintf( __( 'Edit the %s translation', 'sitepress' ), $language[ 'display_name' ] );
					
					$link = 'post.php?action=edit&amp;mla_source=edit&amp;post=' . $translations[ $language[ 'code' ] ]->element_id . '&amp;lang=' . $language[ 'code' ];

				} else {
					// Translation does not exist
					$img = 'add_translation.png';
					$alt = sprintf( __( 'Add translation to %s', 'sitepress' ), $language[ 'display_name' ] );
					$src_lang = $current_language;
					
					if ( 'all' == $src_lang ) {
						foreach( $translations as $translation ) {
							if ( $translation->original ) {
								$src_lang = $translation->language_code;
								break;
							}
						}
					}
					
					$args = array ( 'page' => MLA::ADMIN_PAGE_SLUG, 'mla_admin_action' => 'wpml_create_translation', 'mla_item_ID' => $item->ID, 'mla_parent_ID' => $item->post_parent, 'lang' => $language['code'] );
					$link = add_query_arg( $args, wp_nonce_url( 'upload.php', MLA::MLA_ADMIN_NONCE ) );
				}
			
				$link = apply_filters( 'wpml_link_to_translation', $link, false, $language[ 'code' ] );
				$content .= '<a href="' . $link . '" title="' . $alt . '">';
				$content .= '<img style="padding:1px;margin:2px;" border="0" src="' . ICL_PLUGIN_URL . '/res/img/' . $img . '" alt="' . $alt . '" width="16" height="16" />';
				$content .= '</a>';
			} // foreach language
		}
		
		return $content;
	} // mla_list_table_column_default_filter

} // Class MLA_WPML_Table
/*
 * Some actions and filters are added here, when the source file is loaded, because the
 * MLA_List_Table object is created too late to be useful.
 */

 /*
  * Defined in /media-library-assistant/includes/class-mla-list-table.php
  */
add_filter( 'mla_list_table_get_columns', 'MLA_WPML_Table::mla_list_table_get_columns', 10, 1 );
?>