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
		/*
		 * The remaining filters are only useful for the admin section; exit in the front-end posts/pages
		 */
		if ( ! is_admin() ) {
			 /*
			  * Defined in /media-library-assistant/includes/class-mla-shortcodes.php
			  */
			add_filter( 'mla_get_terms_query_arguments', 'MLA_WPML::mla_get_terms_query_arguments', 10, 1 );
			add_filter( 'mla_get_terms_clauses', 'MLA_WPML::mla_get_terms_clauses', 10, 1 );
	
			return;
		}

		add_action( 'admin_init', 'MLA_WPML::admin_init' );

		/*
		 * Defined in wp-admin/edit-form-advanced.php
		 */
		add_filter( 'post_updated_messages', 'MLA_WPML::post_updated_messages', 10, 1 );

		/*
		 * Defined in wp-includes/post.php function wp_insert_post
		 */
		add_filter( 'wp_insert_post_empty_content', 'MLA_WPML::wp_insert_post_empty_content', 10, 2 );
		add_action( 'add_attachment', 'MLA_WPML::add_attachment', 10, 1 );
		add_action( 'edit_attachment', 'MLA_WPML::edit_attachment', 10, 1 );

		/*
		 * Defined in /media-library-assistant/includes/class-mla-main.php
		 */
		add_filter( 'mla_list_table_new_instance', 'MLA_WPML_Table::mla_list_table_new_instance', 10, 1 );
		add_action( 'mla_list_table_custom_admin_action', 'MLA_WPML::mla_list_table_custom_admin_action', 10, 2 );
		add_filter( 'mla_list_table_inline_action', 'MLA_WPML::mla_list_table_inline_action', 10, 2 );
		add_filter( 'mla_list_table_bulk_action_initial_request', 'MLA_WPML::mla_list_table_bulk_action_initial_request', 10, 3 );
		add_filter( 'mla_list_table_bulk_action_item_request', 'MLA_WPML::mla_list_table_bulk_action_item_request', 10, 4 );
		//add_filter( 'mla_list_table_bulk_action', 'MLA_WPML::mla_list_table_bulk_action', 10, 3 );
		
		/*
		 * Defined in /media-library-assistant/includes/class-mla-settings.php
		 */
		add_filter( 'mla_get_options_tablist', 'MLA_WPML::mla_get_options_tablist', 10, 3 );
		
		/*
		 * Defined in /wpml-media/inc/wpml-media-class.php
		 */
		add_action( 'wpml_media_create_duplicate_attachment', 'MLA_WPML::wpml_media_create_duplicate_attachment', 10, 2 );
	}

	/**
	 * MLA Tag Cloud Query Arguments
	 *
	 * Saves [mla_tag_cloud] query parameters for use in MLA_WPML::mla_get_terms_clauses.
	 *
	 * @since 2.11
	 * @uses MLA_WPML::$all_query_parameters
	 *
	 * @param	array	shortcode arguments merged with attachment selection defaults, so every possible parameter is present
	 *
	 * @return	array	updated attachment query arguments
	 */
	public static function mla_get_terms_query_arguments( $all_query_parameters ) {
		//error_log( 'MLA_WPML::mla_get_terms_query_arguments_filter $all_query_parameters = ' . var_export( $all_query_parameters, true ), 0 );

		self::$all_query_parameters = $all_query_parameters;
		return $all_query_parameters;
	} // mla_get_terms_query_arguments

	/**
	 * Save the query arguments
	 *
	 * @since 2.11
	 *
	 * @var	array
	 */
	private static $all_query_parameters = array();

	/**
	 * MLA Tag Cloud Query Clauses
	 *
	 * Adds language-specific clauses to filter the cloud terms.
	 *
	 * @since 2.11
	 *
	 * @param	array	SQL clauses ( 'fields', 'join', 'where', 'order', 'orderby', 'limits' )
	 *
	 * @return	array	updated SQL clauses
	 */
	public static function mla_get_terms_clauses( $clauses ) {
		global $sitepress;
		//error_log( 'MLA_WPML::mla_get_terms_clauses $clauses = ' . var_export( $clauses, true ), 0 );

		if ( 'all' != ( $current_language = $sitepress->get_current_language() ) ) {
			$clauses['join'] = preg_replace( '/(^.* AS tt ON t.term_id = tt.term_id)/m', '${1}' . ' JOIN `jholland_icl_translations` AS icl_t ON icl_t.element_id = tt.term_taxonomy_id', $clauses['join'] );
			
			$clauses['where'] .= " AND icl_t.language_code = '" . $current_language . "'";

			$taxonomies = array();
			foreach ( self::$all_query_parameters['taxonomy'] as $taxonomy) {
				$taxonomies[] = 'tax_' . $taxonomy;
			}
	
			$clauses['where'] .= "AND icl_t.element_type IN ( '" . join( "','", $taxonomies ) . "' )";
		}

		return $clauses;
	} // mla_get_terms_clauses

	/**
	 * Add the plugin's admin-mode filter/action handlers
	 *
	 * @since 2.11
	 *
	 * @return	void
	 */
	public static function admin_init() {
		/*
		 * Add styles for the language management column
		 */
		if ( isset( $_REQUEST['page'] ) && ( MLA::ADMIN_PAGE_SLUG == $_REQUEST['page'] ) ) {
			add_action( 'admin_print_styles', 'MLA_WPML_Table::mla_list_table_add_icl_styles' );
		}
		
		/*
		 * Localize $mla_language_option_definitions array
		 */
		self::mla_localize_language_option_definitions();
	}

	/**
	 * Captures the Quick Edit "before update" term assignments
	 *
	 * @since 2.11
	 *
	 * @param	array	$item_content	NULL, to indicate no handler.
	 * @param	integer	$post_id		the affected attachment.
	 *
	 * @return	object	updated $item_content. NULL if no handler, otherwise
	 *					( 'message' => error or status message(s), 'body' => '',
	 *					  'prevent_default' => true to bypass the MLA handler )
	 */
	public static function mla_list_table_inline_action( $item_content, $post_id ) {
//error_log( __LINE__ . " MLA_WPML::mla_list_table_inline_action post_id = " . var_export( $post_id, true ), 0 );
		self::_build_terms_before( $post_id );
		
		return $item_content;
	} // mla_list_table_inline_action

	/**
	 * Captures the Bulk Edit parameters during "Upload New Media"
	 *
	 * @since 2.11
	 *
	 * @param	array	$request		bulk action request parameters, including ['mla_bulk_action_do_cleanup'].
	 * @param	string	$bulk_action	the requested action.
	 * @param	array	$custom_field_map	[ slug => field_name ]
	 *
	 * @return	array	updated bulk action request parameters
	 */
	public static function mla_list_table_bulk_action_initial_request( $request, $bulk_action, $custom_field_map ) {
//error_log( __LINE__ . " MLA_WPML::mla_list_table_bulk_action_initial_request request = " . var_export( $request, true ), 0 );
		/*
		 * Check for Bulk Edit processing during Upload New Media
		 */
		if ( ( NULL == self::$upload_bulk_edit_args ) && ( 'edit' == $bulk_action ) && ! empty( $_REQUEST['mlaAddNewBulkEdit']['formString'] ) ) {
			/*
			 * Suppress WPML processing in wpml-media.class.php function save_attachment_actions,
			 * which wipes out attachment meta data.
			 */
			global $action;
			$action = 'upload-plugin';
			
			self::$upload_bulk_edit_args = $request;
			self::$upload_bulk_edit_map = $custom_field_map;
		}
		
		return $request;
	} // mla_list_table_bulk_action_initial_request

	/**
	 * Custom Field Map during "Upload New Media"
	 *
	 * @since 2.11
	 *
	 * @var	array	[ id ] => field name
	 */
	private static $upload_bulk_edit_map = NULL;
	
	/**
	 * Bulk Edit parameters during "Upload New Media"
	 *
	 * @since 2.11
	 *
	 * @var	array	[ field ] => new value
	 */
	private static $upload_bulk_edit_args = NULL;
	
	/**
	 * Converts Bulk Edit taxonomy inputs to language-specific values
	 *
	 * @since 2.11
	 *
	 * @param	array	$request		bulk action request parameters, including ['mla_bulk_action_do_cleanup'].
	 * @param	string	$bulk_action	the requested action.
	 * @param	integer	$post_id		the affected attachment.
	 * @param	array	$custom_field_map	[ slug => field_name ]
	 *
	 * @return	array	updated bulk action request parameters
	 */
	public static function mla_list_table_bulk_action_item_request( $request, $bulk_action, $post_id, $custom_field_map ) {
//error_log( __LINE__ . " MLA_WPML::mla_list_table_bulk_action_item_request( {$bulk_action}, {$post_id} ) request = " . var_export( $request, true ), 0 );

		if ( 'edit' == $bulk_action && ( ! empty( $request['tax_input'] ) ) ) {
			self::_build_terms_before( $post_id );
			self::_build_tax_input( $post_id, $request['tax_input'], $request['tax_action'] );
			$request['tax_input'] = self::_apply_tax_input( $post_id );
		}
		
		return $request;
	} // mla_list_table_bulk_action_item_request

	/**
	 * Captures the Bulk Edit "before update" term assignments
	 *
	 * @since 2.11
	 *
	 * @param	array	$item_content	NULL, to indicate no handler.
	 * @param	string	$bulk_action	the requested action.
	 * @param	integer	$post_id		the affected attachment.
	 *
	 * @return	object	updated $item_content. NULL if no handler, otherwise
	 *					( 'message' => error or status message(s), 'body' => '',
	 *					  'prevent_default' => true to bypass the MLA handler )
	 */
	public static function mla_list_table_bulk_action( $item_content, $bulk_action, $post_id ) {
//error_log( __LINE__ . " MLA_WPML::mla_list_table_bulk_action [{$post_id}] bulk_action = " . var_export( $bulk_action, true ), 0 );

		if ( 'edit' == $bulk_action ) {
			//self::_build_terms_before( $post_id );
		}
		
		return $item_content;
	} // mla_list_table_bulk_action

	/**
	 * Add a duplicate translation for an item, then redirect to the Media/Edit Media screen
	 *
	 * @since 2.11
	 *
	 * @param	string	$mla_admin_action	the requested action.
	 * @param	integer	$mla_item_ID		zero (0), or the affected attachment.
	 */
	public static function mla_list_table_custom_admin_action( $mla_admin_action, $mla_item_ID ) {
//error_log( __LINE__ . " MLA_WPML::mla_list_table_custom_admin_action( {$mla_admin_action}, {$mla_item_ID} )", 0 );
		if ( 'wpml_create_translation' == $mla_admin_action ) {
			$new_item = WPML_Media::create_duplicate_attachment( $mla_item_ID, $_REQUEST['mla_parent_ID'], $_REQUEST['lang'] );
			$view_args = isset( $_REQUEST['mla_source'] ) ? array( 'mla_source' => $_REQUEST['mla_source']) : array();
			wp_redirect( add_query_arg( $view_args, admin_url( 'post.php' ) . '?action=edit&post=' . $new_item . '&message=201' ), 302 );
			exit;
		}
	} // mla_list_table_custom_admin_action

	/**
	 * Adds translation update message for display at the top of the Edit Media screen
	 *
	 * @since 2.11
	 *
	 * @param	array	messages for the Edit screen
	 *
	 * @return	array	updated messages
	 */
	public static function post_updated_messages( $messages ) {
	if ( isset( $messages['attachment'] ) ) {
		$messages['attachment'][201] = __( 'Duplicate translation created; update as desired.', 'media-library-assistant' );
	}

	return $messages;
	} // mla_post_updated_messages_filter

	/**
	 * Taxonomy terms and translations
	 *
	 * @since 2.11
	 *
	 * @var	array	[ $term_taxonomy_id ] => array( $term, $details, $translations )
	 */
	private static $relevant_terms = array();
	
	/**
	 * Adds a term and its translations to $relevant_terms
	 *
	 * @since 2.11
	 *
	 * @param	object	WordPress term object
	 * @param	object	Sitepress translations object; optional
	 */
	private static function _add_relevant_term( $term, $translations = NULL ) {
		global $sitepress;
		
		if ( ! array_key_exists( $term->term_taxonomy_id, self::$relevant_terms ) ) {
			$taxonomy_name = 'tax_' . $term->taxonomy;
			$details = $sitepress->get_element_language_details( $term->term_taxonomy_id, $taxonomy_name );
			
			if ( empty( $translations ) ) {
				$translations = $sitepress->get_element_translations( $details->trid, $taxonomy_name );
			}
			
			self::$relevant_terms[ $term->term_taxonomy_id ]['term'] = $term;
			self::$relevant_terms[ $term->term_taxonomy_id ]['details'] = $details;
			self::$relevant_terms[ $term->term_taxonomy_id ]['translations'] = $translations;
		}
		
		return self::$relevant_terms[ $term->term_taxonomy_id ];
	} // mla_post_updated_messages_filter

	/**
	 * Finds a $relevant_term (if defined) given a key and (optional) a language
	 *
	 * @since 2.11
	 *
	 * @param	string	$field to search in; 'id', 'name', or 'term_taxonomy_id'
	 * @param	mixed	$value to search for; integer, string or integer
	 * @param	string	$taxonomy to search in; slug
	 * @param	string	$language code; string; optional
	 */
	private static function _get_relevant_term( $field, $value, $taxonomy, $language = NULL ) {
		global $sitepress;
		
		/*
		 * WordPress encodes special characters, e.g., "&" as HTML entities in term names
		 */
		if ( 'name' == $field ) {
			$value = _wp_specialchars( $value );
		}

		$relevant_term = false;
		foreach( self::$relevant_terms as $term_taxonomy_id => $candidate ) {
			if ( $taxonomy != $candidate['term']->taxonomy ) {
				continue;
			}
			
			switch ( $field ) {
				case 'id':
					if ( $value == $candidate['term']->term_id ) {
						$relevant_term = $candidate;
					}
					break;
				case 'name':
					if ( $value == $candidate['term']->name ) {
						$relevant_term = $candidate;
					}
					break;
				case 'term_taxonomy_id':
					if ( $value == $term_taxonomy_id ) {
						$relevant_term = $candidate;
					}
					break;
			} // field
			
			if ( ! empty( $relevant_term ) ) {
				break;
			}
		} // relevant term

		/*
		 * If no match; try to add it and its translations
		 */
 		if ( ( false === $relevant_term ) && $candidate = get_term_by( $field, $value, $taxonomy ) ) {
			$relevant_term =  self::_add_relevant_term( $candidate );
			
			foreach ( $relevant_term['translations'] as $translation ) {
				if ( array_key_exists( $translation->element_id, self::$relevant_terms ) ) {
					continue;
				}
				
				$term_object = get_term_by( 'term_taxonomy_id', $translation->element_id, $taxonomy );
				self::_add_relevant_term( $term_object, $relevant_term['translations'] );
			} // translation
		} // new term
		
		/*
		 * Find the language-specific value, if requested
		 */
		if ( $relevant_term && ! empty( $language ) ) {
			if ( $relevant_term && array_key_exists( $language, $relevant_term['translations'] ) ) {
				$relevant_term = self::$relevant_terms[ $relevant_term['translations'][ $language ]->element_id ];
			} else {
				$relevant_term = false;
			}
		}
		
		return $relevant_term;
	}

	/**
	 * Taxonomy terms for the current item translation before any changes
	 *
	 * @since 2.11
	 *
	 * @var	array	['element_id'] => $post_id;
	 * 				[ language_details ]
	 * 				[ $language ][ translation_details ]
	 * 				[ $language ][ $taxonomy ][ $term_taxonomy_id ] => $term
	 */
	private static $terms_before = array( 'element_id' => 0 );
	
	/**
	 * Build the $terms_before array
	 *
	 * Takes each translatable taxonomy and builds an array of
	 * language-specific term_id to term_id/term_name mappings 
	 * for terms already assigned to the item translation.
	 *
	 * @since 2.11
	 * @uses MLA_WPML::$terms_before
	 *
	 * @param	integer	$post_id ID of the current post
	 *
	 */
	private static function _build_terms_before( $post_id ) {
		global $sitepress;

		if ( $post_id == self::$terms_before['element_id'] ) {
			return;
		}
		
		$language_details = (array) $sitepress->get_element_language_details( $post_id, 'post_attachment' );
		$translations = array();
		foreach ( $sitepress->get_element_translations( $language_details['trid'], 'post_attachment' ) as $language_code => $translation ) {
			$translations[ $language_code ] = (array) $translation;
		}
		
		self::$terms_before = array_merge( array( 'element_id' => $post_id ), $language_details, $translations );
		$taxonomies = $sitepress->get_translatable_taxonomies( true, 'attachment' );

		/*
		 * Find all assigned terms and build term_master array
		 */		
		foreach ( $translations as $language_code => $translation ) {
			foreach ( $taxonomies as $taxonomy_name ) {
				if ( $terms = get_the_terms( $translation['element_id'], $taxonomy_name ) ) {
					foreach ( $terms as $term ) {
						self::_add_relevant_term( $term );
						self::$terms_before[ $language_code ][ $taxonomy_name ][ $term->term_taxonomy_id ] = $term;
					} // term
				} else {
					self::$terms_before[ $language_code ][ $taxonomy_name ] = array();
				}
			} // taxonomy
		} // translation
		
		/*
		 * Add missing translated terms to the term_master array
		 */		
		foreach ( self::$relevant_terms as $term ) {
			foreach ( $term['translations'] as $translation ) {
				if ( array_key_exists( $translation->element_id, self::$relevant_terms ) ) {
					continue;
				}
				
				$term_object = get_term_by( 'term_taxonomy_id', $translation->element_id, $term['term']->taxonomy );
				self::_add_relevant_term( $term_object, $term['translations'] );
			} // translation
		} // term
		
		return;
	}
	
	/**
	 * Replacement tax_input values in all languages
	 *
	 * @since 2.11
	 *
	 * @var	array	['tax_input_post_id'] => $post_id;
	 * 				[ $language ][ $taxonomy ] => array of integer term_ids (hierarchical)
	 * 				[ $language ][ $taxonomy ] => comma-delimited string of term names (flat)
	 */
	private static $tax_input = array( 'tax_input_post_id' => 0 );
	
	/**
	 * Build the $tax_input array
	 *
	 * Takes each term from the $tax_inputs parameter and builds an array of
	 * language-specific term_id to term_id/term_name mappings for all languages.
	 *
	 * @since 2.11
	 * @uses MLA_WPML::$tax_input
	 *
	 * @param	integer	$post_id ID of the current post
	 * @param	array	$tax_inputs 'tax_input' request parameter
	 * @param	array	$tax_actions 'tax_action' request parameter
	 */
	private static function _build_tax_input( $post_id, $tax_inputs = NULL, $tax_actions = NULL ) {
		global $sitepress;

		if ( $post_id == self::$tax_input['tax_input_post_id'] ) {
			return;
		}

		self::$tax_input = array( 'tax_input_post_id' => $post_id );
		$active_languages = $sitepress->get_active_languages();

		/*
		 * See if we are cloning the existing assignments
		 */
		if ( ( NULL == $tax_inputs ) && ( NULL == $tax_actions ) && isset( self::$terms_before['element_id'] ) && ($post_id == self::$terms_before['element_id'] ) ) {
			$translation = self::$terms_before[ self::$terms_before['language_code'] ];
			$taxonomies = $sitepress->get_translatable_taxonomies( true, 'attachment' );
			$tax_inputs = array();
			$no_terms = true;
			foreach ( $taxonomies as $taxonomy_name ) {
				$terms = isset( $translation[ $taxonomy_name ] ) ? $translation[ $taxonomy_name ] : array();
				if ( ! empty( $terms ) ) {
					$no_terms = false;
					$taxonomy = get_taxonomy( $taxonomy_name );
					$input_terms = array();
					foreach ( $terms as $term ) {
						if ( $taxonomy->hierarchical ) {
							$input_terms[] = $term->term_id;
						} else {
							$input_terms[] = $term->name;
						}
					} // term
					
					if ( $taxonomy->hierarchical ) {
						$tax_inputs[ $taxonomy_name ] = $input_terms;
					} else {
						$tax_inputs[ $taxonomy_name ] = implode( ',', $input_terms );
					}
				} else {
					$tax_inputs[ $taxonomy_name ] = array();
				}
			} // taxonomy_name
			
			if ( $no_terms ) {
				foreach( $active_languages as $language => $language_details ) {
					self::$tax_input[ $language ] = array();
				}
				
				return;
			}
		} // cloning

		foreach ( $tax_inputs as $taxonomy => $terms ) {
			$tax_action = isset( $tax_actions[ $taxonomy ] ) ? $tax_actions[ $taxonomy ] : 'replace'; 
			$input_terms = array();
			// hierarchical taxonomy => array of term_id integer values; flat => comma-delimited string of names
			if ( $hierarchical = is_array( $terms ) ) {
				
				foreach( $terms as $term ) {
					if ( 0 == $term ) {
						continue;
					}
					
					$relevant_term = self::_get_relevant_term( 'term_id', $term, $taxonomy );
					if ( isset( $relevant_term['translations'] ) ) {
						foreach ( $relevant_term['translations'] as $language => $translation ) {
							if ($translated_term = self::_get_relevant_term( 'term_taxonomy_id', $translation->element_id, $taxonomy ) ) {
								$input_terms[ $language ][ $translation->element_id ] = $translated_term['term'];
							}
						} // for each language
					} // translations exist
				} // foreach term
			} else {
				// Convert names to an array
				$term_names = array_map( 'trim', explode( ',', $terms ) );

				foreach ( $term_names as $term_name ) {
					if ( ! empty( $term_name ) ) {
						$relevant_term = self::_get_relevant_term( 'name', $term_name, $taxonomy );
						if ( isset( $relevant_term['translations'] ) ) {
							foreach ( $relevant_term['translations'] as $language => $translation ) {
								if ( $translated_term = self::_get_relevant_term( 'term_taxonomy_id', $translation->element_id, $taxonomy ) ) {
									$input_terms[ $language ][ $translation->element_id ] = $translated_term['term'];
								}
							} // for each language
						} // translations exist
					} // not empty
				} // foreach name
			} // flat taxonomy

			foreach( $active_languages as $language => $language_details ) {
				/*
				 * Apply the tax_action to the terms_before to find the terms_after
				 */
				$term_changes = isset( $input_terms[ $language ] ) ? $input_terms[ $language ] : array();
				if ( 'replace' == $tax_action ) {
					$terms_after = $term_changes;
				} else {
					$terms_after = isset( self::$terms_before[ $language ][ $taxonomy ] ) ? self::$terms_before[ $language ][ $taxonomy ] : array();
	
					foreach( $term_changes as $term_taxonomy_id => $input_term ) {
						if ( 'add' == $tax_action ) {
							$terms_after[ $term_taxonomy_id ] = $input_term;
						} else {
							unset( $terms_after[ $term_taxonomy_id ] );
						}
					} // input_term
				}

				/*
				 * Convert terms_after to tax_input format
				 */
				$term_changes = array();
				foreach( $terms_after as $input_term ) {
					if ( $hierarchical ) {
						$term_changes[] = $input_term->term_id;
					} else {
						$term_changes[] = $input_term->name;
					}
				} // input_term
					
				if ( $hierarchical ) {
					self::$tax_input[ $language ][ $taxonomy ] = $term_changes;
				} else {
					self::$tax_input[ $language ][ $taxonomy ] = implode( ',', $term_changes );
				}
			} // language
			
		} // foreach taxonomy
	} // _build_tax_input

	/**
	 * Filter the $tax_input array to a specific language
	 *
	 * @since 2.11
	 *
	 * @param	integer	$post_id ID of the current post
	 * @param	array	$tax_inputs 'tax_input' request parameter
	 * @param	array	$tax_actions 'tax_actions' request parameter
	 *
	 * @return	array	updated $tax_inputs
	 */
	private static function _apply_tax_input( $post_id, $post_language = NULL ) {
		global $sitepress;
		
		if ( NULL == $post_language ) {
			if ( isset( self::$terms_before['element_id'] ) && $post_id == self::$terms_before['element_id'] ) {
				$post_language = self::$terms_before['language_code'];
			} else {
				$post_language = $sitepress->get_element_language_details( $post_id, 'post_attachment' );
				$post_language = $post_language->language_code;
			}
		}
		
		return self::$tax_input[ $post_language ];
	} // _apply_tax_input

	/**
	 * Captures "before update" term assignments from the Media/Edit Media screen
	 *
	 * @since 2.11
	 *
	 * @param bool  $maybe_empty Whether the post should be considered "empty".
	 * @param array $postarr     Array of post data.
	 */
	public static function wp_insert_post_empty_content( $maybe_empty, $postarr ) {
		global $sitepress;
//error_log( __LINE__ . ' MLA_WPML::wp_insert_post_empty_content $postarr = ' . var_export( $postarr, true ), 0 );
		
		if ( isset( $_REQUEST['action'] ) && 'editpost' ==  $_REQUEST['action'] && isset( $_REQUEST['post_ID'] ) ) {
			self::_build_terms_before( $_REQUEST['post_ID'] );
		}

		return $maybe_empty;
	} // wp_insert_post_empty_content

	/**
	 * Filters taxonomy updates by language.
	 *
	 * @since 2.11
	 *
	 * @param	integer	ID of the current post
	 */
	public static function add_attachment( $post_id ) {
		static $already_adding = 0;
		
//error_log( __LINE__ . " MLA_WPML::add_attachment( {$post_id} ) \$already_adding = " . var_export( $already_adding, true ), 0 );
		
		if ( $already_adding == $post_id ) {
			return;
		} else {
			$already_adding = $post_id;
		}
	} // add_attachment

	/**
	 * Duplicates created during media upload
	 *
	 * @since 2.11
	 *
	 * @var	array	[ $post_id ] => $language;
	 */
	private static $duplicate_attachments = array();
	
	/**
	 * Copies taxonomy terms from the source item to the new translated item
	 *
	 * @since 2.11
	 *
	 * @param	integer	ID of the source item
	 * @param	integer	ID of the new item
	 */
	public static function wpml_media_create_duplicate_attachment( $attachment_id, $duplicated_attachment_id ) {
		global $sitepress;
		static $already_adding = 0;
		
//error_log( __LINE__ . " MLA_WPML::wpml_media_create_duplicate_attachment( {$attachment_id}, {$duplicated_attachment_id} ) \$already_adding = " . var_export( $already_adding, true ), 0 );
		
		if ( $already_adding == $duplicated_attachment_id ) {
			return;
		} else {
			$already_adding = $duplicated_attachment_id;
		}

		$language_details = $sitepress->get_element_language_details( $duplicated_attachment_id, 'post_attachment' );
		self::$duplicate_attachments [ $duplicated_attachment_id ] = $language_details->language_code;
		
		if ( isset( $_REQUEST['mla_admin_action'] ) && 'wpml_create_translation' ==  $_REQUEST['mla_admin_action'] ) {
			if ( 'checked' == MLAOptions::mla_get_option( 'term_synchronization', false, false, MLA_WPML::$mla_language_option_definitions ) ) {
				self::_build_terms_before( $attachment_id );
				self::_build_tax_input( $attachment_id );
				$tax_inputs = self::_apply_tax_input( 0, $language_details->language_code );
			} else {
				$tax_inputs = NULL;
			}
			
			if ( !empty( $tax_inputs ) ) {
				MLAData::mla_update_single_item( $duplicated_attachment_id, array(), $tax_inputs );
			}

			self::$terms_before = array( 'element_id' => 0 );
			self::$relevant_terms = array();
		} // wpml_create_translation
	} // wpml_media_create_duplicate_attachment

	/**
	 * Filters taxonomy updates by language.
	 *
	 * @since 2.11
	 *
	 * @param	integer	ID of the current post
	 */
	public static function edit_attachment( $post_id ) {
		static $already_updating = 0;
		
//error_log( __LINE__ . " MLA_WPML::edit_attachment( {$post_id} ) \$already_updating = " . var_export( $already_updating, true ), 0 );
		
		/*
		 * mla_update_single_item eventually calls this action again
		 */
		if ( $already_updating == $post_id ) {
			return;
		} else {
			$already_updating = $post_id;
		}
		
		/*
		 * Check for Bulk Edit during Add New Media
		 */
		if ( is_array( self::$upload_bulk_edit_args ) ) {
			if ( ! empty( self::$upload_bulk_edit_args['tax_input'] ) ) {
				$tax_inputs = self::$upload_bulk_edit_args['tax_input'];
				if ( 'checked' == MLAOptions::mla_get_option( 'term_assignment', false, false, MLA_WPML::$mla_language_option_definitions ) ) {
					self::_build_tax_input( $post_id, $tax_inputs, self::$upload_bulk_edit_args['tax_action'] );
					$tax_inputs = self::_apply_tax_input( $post_id );
				}
			} else {
				$tax_inputs = NULL;
			}
			
			$updates = 	MLA::mla_prepare_bulk_edits( $post_id, self::$upload_bulk_edit_args, self::$upload_bulk_edit_map );
			unset( $updates['tax_input'] );
			unset( $updates['tax_action'] );

			MLAData::mla_update_single_item( $post_id, $updates, $tax_inputs );

			/*
			 * Synchronize the changes to all other translations
			 */
			if ( 'checked' == MLAOptions::mla_get_option( 'term_synchronization', false, false, MLA_WPML::$mla_language_option_definitions ) ) {
				foreach( self::$tax_input as $language => $tax_inputs ) {
					/*
					 * Skip 'tax_input_post_id' and the language we've already updated
					 */
					if ( ( ! isset( self::$terms_before[ $language ] ) ) || ( self::$terms_before[ 'language_code' ] == $language ) ) {
						continue;
					}
					
					$translation = self::$terms_before[ $language ];
					$tax_inputs = self::_apply_tax_input( $translation['element_id'], $language );
					$already_updating = $translation['element_id']; // prevent recursion
					MLAData::mla_update_single_item( $translation['element_id'], $updates, $tax_inputs );
					$already_updating = $post_id;
				} // translation
			} // do synchronization
			
			return;
		} // Upload New Media Bulk Edit

		/*
		 * The category taxonomy (edit screens) is a special case because 
		 * post_categories_meta_box() changes the input name
		 */
		if ( isset( $_REQUEST['tax_input'] ) ) {
			$tax_inputs = $_REQUEST['tax_input'];
		} else {
			$tax_inputs = array();
		}

		if ( isset( $_REQUEST['post_category'] ) ) {
			$tax_inputs['category'] = $_REQUEST['post_category'];
		}

		if ( isset( $_REQUEST['tax_action'] ) ) {
			$tax_actions = $_REQUEST['tax_action'];
		} else {
			$tax_actions = NULL;
		}

		if ( ( ! empty( $tax_inputs ) ) && ( 'checked' == MLAOptions::mla_get_option( 'term_assignment', false, false, MLA_WPML::$mla_language_option_definitions ) ) ) {
			self::_build_tax_input( $post_id, $tax_inputs, $tax_actions );
			$tax_inputs = self::_apply_tax_input( $post_id );
		}
		
		if ( ! empty( $tax_inputs ) ) {
			MLAData::mla_update_single_item( $post_id, array(), $tax_inputs );
			
			/*
			 * Synchronize the changes to all other translations
			 */
			if ( 'checked' == MLAOptions::mla_get_option( 'term_synchronization', false, false, MLA_WPML::$mla_language_option_definitions ) ) {
				foreach( self::$tax_input as $language => $tax_inputs ) {
					/*
					 * Skip 'tax_input_post_id' and the language we've already updated
					 */
					if ( ( ! isset( self::$terms_before[ $language ] ) ) || ( self::$terms_before[ 'language_code' ] == $language ) ) {
						continue;
					}
					
					$translation = self::$terms_before[ $language ];
					$tax_inputs = self::_apply_tax_input( $translation['element_id'], $language );
					$already_updating = $translation['element_id']; // prevent recursion
					MLAData::mla_update_single_item( $translation['element_id'], array(), $tax_inputs );
					$already_updating = $post_id;
				} // translation
			} // do synchronization
		}
	} // edit_attachment

	/**
	 * Adds the "Language" tab to the Settings/Media Library Assistant list
	 *
	 * @since 2.11
	 *
	 * @param	array|false	The entire tablist ( $tab = NULL ), a single tab entry or false if not found/not allowed.
	 * @param	array		The entire default tablist
	 * @param	string|NULL	tab slug for single-element return or NULL to return entire tablist
	 *
	 * @return	array	updated tablist or single tab element
	 */
	public static function mla_get_options_tablist( $results, $mla_tablist, $tab ) {
		$language_key = 'language';
		$language_value = array( 'title' => __( 'Language', 'media-library-assistant' ), 'render' => array( 'MLA_WPML', 'mla_render_language_tab' ) );

		if ( $language_key == $tab ) {
			return $language_value;
		}
		
		return array_merge( $results, array( $language_key => $language_value ) );
	}
	
	/**
	 * $mla_language_option_definitions defines the language-specific database options and
	 * admin page areas for setting/updating them
	 *
	 * The array must be populated at runtime in MLA_WPML::mla_localize_language_option_definitions(),
	 * because localization calls cannot be placed in the "public static" array definition itself.
	 *
	 * Each option is defined by an array with the elements documented in class-mla-options.php
	 */
	 
	public static $mla_language_option_definitions = array ();
	
	/**
	 * Localize $mla_language_option_definitions array
	 *
	 * Localization must be done at runtime, and these calls cannot be placed
	 * in the "public static" array definition itself.
	 *
	 * @since 2.11
	 *
	 * @return	void
	 */
	public static function mla_localize_language_option_definitions() {
		MLA_WPML::$mla_language_option_definitions = array (
			'media_assistant_table_header' =>
				array('tab' => 'language',
					'name' => __( 'Media/Assistant submenu table', 'media-library-assistant' ),
					'type' => 'header'),

			'language_column' =>
				array('tab' => 'language',
					'name' => __( 'Language Column', 'media-library-assistant' ),
					'type' => 'checkbox',
					'std' => 'checked',
					'help' => __( 'Check this option to add a Language column to the Media/Assistant submenu table.', 'media-library-assistant' )),

			'translations_column' =>
				array('tab' => 'language',
					'name' => __( 'Translations Column', 'media-library-assistant' ),
					'type' => 'checkbox',
					'std' => 'checked',
					'help' => __( 'Check this option to add a Translation Status column to the Media/Assistant submenu table.', 'media-library-assistant' )),

			'term_translation_header' =>
				array('tab' => 'language',
					'name' => __( 'Term Management', 'media-library-assistant' ),
					'type' => 'header'),

			'term_assignment' =>
				array('tab' => 'language',
					'name' => __( 'Term Assignment', 'media-library-assistant' ),
					'type' => 'checkbox',
					'std' => 'checked',
					'help' => __( 'Check this option to assign language-specific terms when items are updated.'), 'media-library-assistant' ),

			'term_synchronization' =>
				array('tab' => 'language',
					'name' => __( 'Term Synchronization', 'media-library-assistant' ),
					'type' => 'checkbox',
					'std' => 'checked',
					'help' => __( 'Check this option to synchronize common terms among all item translations.'), 'media-library-assistant' ),
		);
	}

	/**
	 * Renders the Settings/Media Library Assistant "Language" tab
	 *
	 * @since 2.11
	 *
	 * @return	array	( 'message' => '', 'body' => '' )
	 */
	public static function mla_render_language_tab() {
		$page_content = array(
			'message' => '',
			'body' => '<h3>Language</h3>' 
		);
		
		/*
		 * Check for submit buttons to change or reset settings.
		 * Initialize page messages and content.
		 */
		if ( !empty( $_REQUEST['mla-language-options-save'] ) ) {
			check_admin_referer( MLA::MLA_ADMIN_NONCE, '_wpnonce' );
			$page_content = self::_save_language_settings( );
		} elseif ( !empty( $_REQUEST['mla-language-options-reset'] ) ) {
			check_admin_referer( MLA::MLA_ADMIN_NONCE, '_wpnonce' );
			$page_content = self::_reset_language_settings( );
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
			'Language Options' => __( 'Language Options', 'media-library-assistant' ),
			/* translators: 1: - 4: page subheader values */
			'In this tab' => sprintf( __( 'In this tab you can find a number of options for controlling WPML-specific operations. Scroll down to find options for %1$s and %2$s. Be sure to click "Save Changes" at the bottom of the tab to save any changes you make.', 'media-library-assistant' ), '<strong>' . __( 'Media/Assistant submenu table', 'media-library-assistant' ) . '</strong>', '<strong>' . __( 'Term Management', 'media-library-assistant' ) . '</strong>' ),
			'Save Changes' => __( 'Save Changes', 'media-library-assistant' ),
			'Delete Language options' => __( 'Delete Language options and restore default settings', 'media-library-assistant' ),
			'_wpnonce' => wp_nonce_field( MLA::MLA_ADMIN_NONCE, '_wpnonce', true, false ),
			'_wp_http_referer' => wp_referer_field( false ),
			'Go to Top' => __( 'Go to Top', 'media-library-assistant' ),
			'form_url' => admin_url( 'options-general.php' ) . '?page=mla-settings-menu-language&mla_tab=language',
			'options_list' => '',
		);

		$options_list = '';
		foreach ( MLA_WPML::$mla_language_option_definitions as $key => $value ) {
			if ( 'language' == $value['tab'] ) {
				$options_list .= MLASettings::mla_compose_option_row( $key, $value, MLA_WPML::$mla_language_option_definitions );
			}
		}

		$page_values['options_list'] = $options_list;
		$page_template = MLAData::mla_load_template( 'admin-display-language-tab.tpl' );
		$page_content['body'] = MLAData::mla_parse_template( $page_template, $page_values );
		return $page_content;
	}

	/**
	 * Save Language settings to the options table
 	 *
	 * @since 2.11
	 *
	 * @uses $_REQUEST
	 *
	 * @return	array	Message(s) reflecting the results of the operation
	 */
	private static function _save_language_settings( ) {
		$message_list = '';

		foreach ( MLA_WPML::$mla_language_option_definitions as $key => $value ) {
			if ( 'language' == $value['tab'] ) {
				$message_list .= MLASettings::mla_update_option_row( $key, $value, MLA_WPML::$mla_language_option_definitions );
			} // language option
		} // foreach mla_options

		$page_content = array(
			'message' => __( 'Language settings saved.', 'media-library-assistant' ) . "\n",
			'body' => '' 
		);

		/*
		 * Uncomment this for debugging.
		 */
		//$page_content['message'] .= $message_list;

		return $page_content;
	} // _save_language_settings

	/**
	 * Delete saved settings, restoring default values
 	 *
	 * @since 2.11
	 *
	 * @return	array	Message(s) reflecting the results of the operation
	 */
	private static function _reset_language_settings( ) {
		$message_list = '';

		foreach ( MLA_WPML::$mla_language_option_definitions as $key => $value ) {
			if ( 'language' == $value['tab'] ) {
				if ( 'custom' == $value['type'] && isset( $value['reset'] ) ) {
					$message = self::$value['reset']( 'reset', $key, $value, $_REQUEST );
				} elseif ( ('header' == $value['type']) || ('hidden' == $value['type']) ) {
					$message = '';
				} else {
					MLAOptions::mla_delete_option( $key, MLA_WPML::$mla_language_option_definitions );
					/* translators: 1: option name */
					$message = '<br>' . sprintf( _x( 'delete_option "%1$s"', 'message_list', 'media-library-assistant'), $key );
				}

				$message_list .= $message;
			}
		}

		$page_content = array(
			'message' => __( 'Language settings reset to default values.', 'media-library-assistant' ) . "\n",
			'body' => '' 
		);

		/*
		 * Uncomment this for debugging.
		 */
		//$page_content['message'] .= $message_list;

		return $page_content;
	} // _reset_language_settings
} // Class MLA_WPML

/**
 * Class MLA (Media Library Assistant) WPML List Table adds a reference to an MLA_WPML object
 *
 * Extends the MLA_List_Table class.
 *
 * @package Media Library Assistant
 * @since 2.11
 */
class MLA_WPML_List_Table extends MLA_List_Table {
	/**
	 * The MLA_WPML_Table support object
	 *
	 * @since 2.11
	 *
	 * @var	object
	 */
	protected $mla_wpml_table = NULL;
}

/**
 * Class MLA (Media Library Assistant) WPML Table provides support for the WPML Multilingual CMS
 * family of plugins, including WPML Media, for an MLA_List_Table object.
 *
 * An instance of this class is created in the class MLA_List_Table constructor (class-mla-list-table.php).
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
		 * Defined in /wp-admin/includes/class-wp-list-table.php
		 */
		// filter "views_{$this->screen->id}"
		add_filter( 'views_media_page_mla-menu', 'MLA_WPML_Table::mla_views_media_page_mla_menu_filter', 10, 1 );

		 /*
		  * Defined in /media-library-assistant/includes/class-mla-list-table.php
		  */
		add_filter( 'mla_list_table_submenu_arguments', array( $this, 'mla_list_table_submenu_arguments' ), 10, 2 );
		add_filter( 'mla_list_table_get_columns', array( $this, 'mla_list_table_get_columns' ), 10, 1 );
		add_filter( 'mla_list_table_column_default', array( $this, 'mla_list_table_column_default' ), 10, 3 );

		/*
		 * Defined in /plugins/wpml-media/inc/wpml-media.class.php
		 */
		add_filter( 'wpml-media_view-upload-count', array( $this, 'mla_wpml_media_view_upload_count_filter' ), 10, 4 );
		add_filter( 'wpml-media_view-upload-page-count', array( $this, 'mla_wpml_media_view_upload_page_count_filter' ), 10, 2 );
	}

	/**
	 * Handler for filter "views_{$this->screen->id}" in /wp-admin/includes/class-wp-list-table.php
	 *
	 * Filter the list of available list table views, calling the WPML filter that adds language-specific views.
	 *
	 * @since 2.11
	 *
	 * @param	array	A list of available list table views
	 *
	 * @return	array	Updated list of available list table views
	 */
	public static function mla_views_media_page_mla_menu_filter( $views ) {
		// hooked by WPML Media in wpml-media.class.php
		$views = apply_filters( 'views_upload', $views );
		return $views;
	}

	/**
	 * Extend the MLA_List_Table class
	 *
	 * Adds a protected variable holding a reference to the WPML_List_Table object,
	 * then creates the WPML_List_Table passing it a reference to the new "parent" object.
	 *
	 * @since 2.11
	 *
	 * @param	object	$mla_list_table NULL, to indicate no extension/use the base class.
	 *
	 * @return	object	updated mla_list_table object.
	 */
	public static function mla_list_table_new_instance( $mla_list_table ) {
		$mla_list_table = new MLA_WPML_List_Table;
		$mla_list_table->mla_wpml_table = new MLA_WPML_Table( $mla_list_table );

		return $mla_list_table;
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
	 * @param	array	$submenu_arguments An array of query arguments.
	 *					format: attribute => value
	 * @param	boolean	Include the "click filter" values in the results
	 *
	 * @return	array	updated array of query arguments.
	 */
	public static function mla_list_table_submenu_arguments( $submenu_arguments, $include_filters ) {
		global $sitepress;
		
		if ( isset( $_REQUEST['lang'] ) ) {
			$submenu_arguments['lang'] = $_REQUEST['lang'];
		} elseif ( is_object( $sitepress ) ) {		 
			$submenu_arguments['lang'] = $sitepress->get_current_language();
		}

		return $submenu_arguments;
	}
	
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
			$show_language = 'checked' == MLAOptions::mla_get_option( 'language_column', false, false, MLA_WPML::$mla_language_option_definitions );
			$show_translations = 'checked' == MLAOptions::mla_get_option( 'translations_column', false, false, MLA_WPML::$mla_language_option_definitions );

			if ( $show_language && 'all' == $current_language ) {
				self::$language_columns[ 'language' ] = __( 'Language', 'wpml-media' );	
			}
			
			if ( $show_translations && 1 < count( $languages ) && $view_status != 'trash' ) {
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
					if ( isset( $flags[ $language[ 'code' ] ] ) ) {
						$flags_column .= $flags[ $language[ 'code' ] ];
					}
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
			$translations = $sitepress->get_element_translations( $trid, 'post_attachment' );

			$content = '';
			foreach( $languages as $language ) {
				if ( $language[ 'code' ] == $current_language ) {
					continue;
				}
				
				if ( isset( $translations[ $language[ 'code' ] ] ) && $translations[ $language[ 'code' ] ]->element_id == $item->ID ) {
					// The item's own language
					$img = 'yes.png';
					$alt = sprintf( __( 'Edit the %s translation', 'sitepress' ), $language[ 'display_name' ] );
					
					$link = 'post.php?action=edit&amp;mla_source=edit&amp;post=' . $translations[ $language[ 'code' ] ]->element_id . '&amp;lang=' . $language[ 'code' ];
				} elseif ( isset( $translations[ $language[ 'code' ] ] ) && $translations[ $language[ 'code' ] ]->element_id ) {
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