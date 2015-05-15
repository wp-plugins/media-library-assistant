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
		add_filter( 'wp_insert_post_empty_content', 'MLA_WPML::wp_insert_post_empty_content', 10, 2 );
		add_action( 'add_attachment', 'MLA_WPML::add_attachment', 10, 1 );
		add_action( 'edit_attachment', 'MLA_WPML::edit_attachment', 10, 1 );

		/*
		 * Defined in /media-library-assistant/includes/class-mla-main.php
		 */
		add_action( 'mla_list_table_custom_admin_action', 'MLA_WPML::mla_list_table_custom_admin_action', 10, 2 );
		add_filter( 'mla_list_table_inline_action', 'MLA_WPML::mla_list_table_inline_action', 10, 2 );
		add_filter( 'mla_list_table_bulk_action_initial_request', 'MLA_WPML::mla_list_table_bulk_action_initial_request', 10, 3 );
		add_filter( 'mla_list_table_bulk_action', 'MLA_WPML::mla_list_table_bulk_action', 10, 3 );
		
		/*
		 * Defined in /wpml-media/inc/wpml-media-class.php
		 */
		add_action( 'wpml_media_create_duplicate_attachment', 'MLA_WPML::wpml_media_create_duplicate_attachment', 10, 2 );
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
	 * @param	array	$request	NULL, to indicate no handler.
	 * @param	string	$bulk_action	the requested action.
	 * @param	integer	$custom_field_map		the affected attachment.
	 *
	 * @return	object	updated $item_content. NULL if no handler, otherwise
	 *					( 'message' => error or status message(s), 'body' => '',
	 *					  'prevent_default' => true to bypass the MLA handler )
	 */
	public static function mla_list_table_bulk_action_initial_request( $request, $bulk_action, $custom_field_map ) {
//error_log( __LINE__ . " MLA_WPML::mla_list_table_bulk_action_initial_request [{$bulk_action}] request = " . var_export( $request, true ), 0 );

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
			self::_build_terms_before( $post_id );
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
	public static function mla_wpml_post_updated_messages( $messages ) {
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
		 * No match; try to add it and its translations
		 */
		if ( $candidate = get_term_by( $field, $value, $taxonomy ) ) {
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
					foreach ( $relevant_term['translations'] as $language => $translation ) {
						$translated_term = self::_get_relevant_term( 'term_taxonomy_id', $translation->element_id, $taxonomy );
						$input_terms[ $language ][ $translation->element_id ] = $translated_term['term'];
					} // for each language
				} // foreach term
			} else {
				// Convert names to an array
				$term_names = array_map( 'trim', explode( ',', $terms ) );

				foreach ( $term_names as $term_name ) {
					if ( ! empty( $term_name ) ) {
						$relevant_term = self::_get_relevant_term( 'name', $term_name, $taxonomy );
						foreach ( $relevant_term['translations'] as $language => $translation ) {
							$translated_term = self::_get_relevant_term( 'term_taxonomy_id', $translation->element_id, $taxonomy );
						$input_terms[ $language ][ $translation->element_id ] = $translated_term['term'];
						} // for each language
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
			self::_build_terms_before( $attachment_id );
			self::_build_tax_input( $attachment_id );
			$tax_inputs = self::_apply_tax_input( 0, $language_details->language_code );
			
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
			$tax_inputs = self::$upload_bulk_edit_args['tax_input'];
			self::_build_tax_input( $post_id, $tax_inputs, self::$upload_bulk_edit_args['tax_action'] );
			$tax_inputs = self::_apply_tax_input( $post_id );
			
			$updates = 	MLA::mla_prepare_bulk_edits( $post_id, self::$upload_bulk_edit_args, self::$upload_bulk_edit_map );
			unset( $updates['tax_input'] );
			unset( $updates['tax_action'] );

			MLAData::mla_update_single_item( $post_id, $updates, $tax_inputs );

			/*
			 * Sychronize the changes to all other translations
			 */
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
			}
			
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

		if ( ! empty( $tax_inputs ) ) {
			self::_build_tax_input( $post_id, $tax_inputs, $tax_actions );
			$tax_inputs = self::_apply_tax_input( $post_id );
		}
		
		if ( ! empty( $tax_inputs ) ) {
			MLAData::mla_update_single_item( $post_id, array(), $tax_inputs );
			
			/*
			 * Sychronize the changes to all other translations
			 */
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