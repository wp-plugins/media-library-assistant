<?php
/**
 * Database and template file access for MLA needs
 *
 * @package Media Library Assistant
 * @since 0.1
 */
 
/**
 * Class MLA (Media Library Assistant) Data provides database and template file access for MLA needs
 *
 * The _template functions are inspired by the book "WordPress 3 Plugin Development Essentials."
 * Templates separate HTML markup from PHP code for easier maintenance and localization.
 *
 * @package Media Library Assistant
 * @since 0.1
 */
class MLAData {
	/**
	 * Provides a unique suffix for the ALT Text SQL VIEW
	 *
	 * @since 0.40
	 */
	const MLA_ALT_TEXT_VIEW_SUFFIX = 'alt_text_view';
	
	/**
	 * Provides a unique name for the ALT Text SQL VIEW
	 *
	 * @since 0.40
	 *
	 * @var	array
	 */
	private static $mla_alt_text_view = NULL;
	
	/**
	 * Initialization function, similar to __construct()
	 *
	 * @since 0.1
	 */
	public static function initialize() {
		global $table_prefix;
		self::$mla_alt_text_view = $table_prefix . MLA_OPTION_PREFIX . self::MLA_ALT_TEXT_VIEW_SUFFIX;

		add_action( 'save_post', 'MLAData::mla_save_post_action', 10, 1);
		add_action( 'edit_attachment', 'MLAData::mla_save_post_action', 10, 1);
		add_action( 'add_attachment', 'MLAData::mla_save_post_action', 10, 1);
	}
	
	/**
	 * Load an HTML template from a file
	 *
	 * Loads a template to a string or a multi-part template to an array.
	 * Multi-part templates are divided by comments of the form <!-- template="key" -->,
	 * where "key" becomes the key part of the array.
	 *
	 * @since 0.1
	 *
	 * @param	string 	Complete path and name of the template file, option name or the raw template
	 * @param	string 	Optional type of template source; 'file' (default), 'option', 'string'
	 *
	 * @return	string|array|false|NULL
	 *  		string for files that do not contain template divider comments,
	 * 			array for files containing template divider comments,
	 *			false if file or option does not exist,
	 *			NULL if file could not be loaded.
	 */
	public static function mla_load_template( $source, $type = 'file' ) {
		switch ( $type ) {
			case 'file':
				if ( !file_exists( $source ) )
					return false;
				
				$template = file_get_contents( $source, true );
				if ( $template == false ) {
					error_log( 'ERROR: mla_load_template file not found ' . var_export( $source, true ), 0 );
					return NULL;
				}
				break;
			case 'option':
				$template =  MLAOptions::mla_get_option( $source );
				if ( $template == false ) {
					return false;
				}
				break;
			case 'string':
				$template = $source;
				if ( empty( $template ) ) {
					return false;
				}
				break;
			default:
				error_log( 'ERROR: mla_load_template bad source type ' . var_export( $type, true ), 0 );
				return NULL;
		}
		
		$match_count = preg_match_all( '#\<!-- template=".+" --\>#', $template, $matches, PREG_OFFSET_CAPTURE );
		
		if ( ( $match_count == false ) || ( $match_count == 0 ) )
			return $template;
		
		$matches = array_reverse( $matches[0] );
		
		$template_array = array();
		$current_offset = strlen( $template );
		foreach ( $matches as $key => $value ) {
			$template_key = preg_split( '#"#', $value[0] );
			$template_key = $template_key[1];
			$template_value = substr( $template, $value[1] + strlen( $value[0] ), $current_offset - ( $value[1] + strlen( $value[0] ) ) );
			/*
			 * Trim exactly one newline sequence from the start of the value
			 */
			if ( 0 === strpos( $template_value, "\r\n" ) )
				$offset = 2;
			elseif ( 0 === strpos( $template_value, "\n\r" ) )
				$offset = 2;
			elseif ( 0 === strpos( $template_value, "\n" ) )
				$offset = 1;
			elseif ( 0 === strpos( $template_value, "\r" ) )
				$offset = 1;
			else
				$offset = 0;

			$template_value = substr( $template_value, $offset );
				
			/*
			 * Trim exactly one newline sequence from the end of the value
			 */
			$length = strlen( $template_value );
			if ( $length > 2)
				$postfix = substr( $template_value, ($length - 2), 2 );
			else
				$postfix = $template_value;
				
			if ( 0 === strpos( $postfix, "\r\n" ) )
				$length -= 2;
			elseif ( 0 === strpos( $postfix, "\n\r" ) )
				$length -= 2;
			elseif ( 0 === strpos( $postfix, "\n" ) )
				$length -= 1;
			elseif ( 0 === strpos( $postfix, "\r" ) )
				$length -= 1;
				
			$template_array[ $template_key ] = substr( $template_value, 0, $length );
			$current_offset = $value[1];
		} // foreach $matches
		
		return $template_array;
	}
	
	/**
	 * Expand a template, replacing place holders with their values
	 *
	 * A simple parsing function for basic templating.
	 *
	 * @since 0.1
	 *
	 * @param	string	A formatting string containing [+placeholders+]
	 * @param	array	An associative array containing keys and values e.g. array('key' => 'value')
	 *
	 * @return	string	Placeholders corresponding to the keys of the hash will be replaced with their values
	 */
	public static function mla_parse_template( $tpl, $hash ) {
		foreach ( $hash as $key => $value ) {
			if ( is_scalar( $value ) )
				$tpl = str_replace( '[+' . $key . '+]', $value, $tpl );
		}
		
		return $tpl;
	}
	
	/**
	 * Analyze a template, returning an array of the place holders it contains
	 *
	 * @since 0.90
	 *
	 * @param	string	A formatting string containing [+placeholders+]
	 *
	 * @return	array	Placeholder information: each entry is an array with
	 * 					['prefix'] => string, ['value'] => string, ['single'] => boolean
	 */
	public static function mla_get_template_placeholders( $tpl ) {
		$results = array();
		$match_count = preg_match_all( '/\[\+[^+]+\+\]/', $tpl, $matches );
		if ( ( $match_count == false ) || ( $match_count == 0 ) )
			return $results;
			
		foreach ( $matches[0] as $match ) {
			$key = substr( $match, 2, (strlen( $match ) - 4 ) );
			$result = array( 'prefix' => '', 'value' => '', 'single' => false);
			$match_count = preg_match( '/\[\+(.+):(.+)/', $match, $matches );
			if ( 1 == $match_count ) {
				$result['prefix'] = $matches[1];
				$tail = $matches[2];
			}
			else {
				$tail = substr( $match, 2);
			}
			
			$match_count = preg_match( '/([^,]+)(,single)\+\]/', $tail, $matches );
			if ( 1 == $match_count ) {
				$result['single'] = true;
				$result['value'] = $matches[1];
			}
			else {
				$result['value'] = substr( $tail, 0, (strlen( $tail ) - 2 ) );
			}
			
		$results[ $key ] = $result;
		} // foreach
		
		return $results;
	}
	
	/**
	 * Get the total number of attachment posts
	 *
	 * @since 0.30
	 *
	 * @param	array	Query variables, e.g., from $_REQUEST
	 *
	 * @return	integer	Number of attachment posts
	 */
	public static function mla_count_list_table_items( $request )
	{
		$request = self::_prepare_list_table_query( $request );
		$results = self::_execute_list_table_query( $request );
		return $results->found_posts;
	}
	
	/**
	 * Retrieve attachment objects for list table display
	 *
	 * Supports prepare_items in class-mla-list-table.php.
	 * Modeled after wp_edit_attachments_query in wp-admin/post.php
	 *
	 * @since 0.1
	 *
	 * @param	array	query parameters from web page, usually found in $_REQUEST
	 * @param	int		number of rows to skip over to reach desired page
	 * @param	int		number of rows on each page
	 *
	 * @return	array	attachment objects (posts) including parent data, meta data and references
	 */
	public static function mla_query_list_table_items( $request, $offset, $count ) {
		$request = self::_prepare_list_table_query( $request, $offset, $count );
		$results = self::_execute_list_table_query( $request );
		$attachments = $results->posts;
		
		foreach ( $attachments as $index => $attachment ) {
			/*
			 * Add parent data
			 */
			$parent_data = self::mla_fetch_attachment_parent_data( $attachment->post_parent );
			foreach ( $parent_data as $parent_key => $parent_value ) {
				$attachments[ $index ]->$parent_key = $parent_value;
			}
			
			/*
			 * Add meta data
			 */
			$meta_data = self::mla_fetch_attachment_metadata( $attachment->ID );
			foreach ( $meta_data as $meta_key => $meta_value ) {
				$attachments[ $index ]->$meta_key = $meta_value;
			}
			/*
			 * Add references
			 */
			$references = self::mla_fetch_attachment_references( $attachment->ID, $attachment->post_parent );
			$attachments[ $index ]->mla_references = $references;
		}
		
		return $attachments;
	}
	
	/**
	 * WP_Query filter "parameters"
	 *
	 * This array defines parameters for the query's join, where and orderby filters.
	 * The parameters are set up in the _prepare_list_table_query function, and
	 * any further logic required to translate those values is contained in the filters.
	 *
	 * @since 0.30
	 *
	 * @var	array
	 */
	private static $query_parameters = array();

	/**
	 * Sanitize and expand query arguments from request variables
	 *
	 * Prepare the arguments for WP_Query.
	 * Modeled after wp_edit_attachments_query in wp-admin/post.php
	 *
	 * @since 0.1
	 *
	 * @param	array	query parameters from web page, usually found in $_REQUEST
	 * @param	int		Optional number of rows (default 0) to skip over to reach desired page
	 * @param	int		Optional number of rows on each page (0 = all rows, default)
	 *
	 * @return	array	revised arguments suitable for WP_Query
	 */
	private static function _prepare_list_table_query( $raw_request, $offset = 0, $count = 0 ) {
		/*
		 * Go through the $raw_request, take only the arguments that are used in the query and
		 * sanitize or validate them.
		 */
		if ( ! is_array( $raw_request ) ) {
			error_log( 'ERROR: _prepare_list_table_query $raw_request = ' . var_export( $raw_request, true ), 0 );
			return null;
		}
		
		$clean_request = array (
			'm' => 0,
			'orderby' => MLAOptions::mla_get_option( 'default_orderby' ),
			'order' => MLAOptions::mla_get_option( 'default_order' ),
			'post_type' => 'attachment',
			'post_status' => 'inherit',
			'mla-search-connector' => 'AND',
			'mla-search-fields' => array()
		);
		
		foreach ( $raw_request as $key => $value ) {
			switch ( $key ) {
				/*
				 * 'sentence' and 'exact' modify the keyword search ('s')
				 * Their value is not important, only their presence.
				 */
				case 'sentence':
				case 'exact':
				case 'mla-tax':
				case 'mla-term':
					$clean_request[ $key ] = sanitize_key( $value );
					break;
				case 'orderby':
					if ( 'none' == $value )
						$clean_request[ $key ] = $value;
					else {
						$sortable_columns = MLA_List_Table::mla_get_sortable_columns( );
						foreach ($sortable_columns as $sort_key => $sort_value ) {
							if ( $value == $sort_value[0] ) {
								$clean_request[ $key ] = $value;
								break;
							}
						} // foreach
					}
					break;
				case 'post_mime_type':
					if ( array_key_exists( $value, MLA_List_Table::mla_get_attachment_mime_types( ) ) )
						$clean_request[ $key ] = $value;
					break;
				case 'parent':
					$clean_request[ 'post_parent' ] = absint( $value );
					break;
				/*
				 * ['m'] - filter by year and month of post, e.g., 201204
				 */
				case 'author':
				case 'm':
					$clean_request[ $key ] = absint( $value );
					break;
				/*
				 * ['mla_filter_term'] - filter by category or tag ID; -1 allowed
				 */
				case 'mla_filter_term':
					$clean_request[ $key ] = intval( $value );
					break;
				case 'order':
					switch ( $value = strtoupper ($value ) ) {
						case 'ASC':
						case 'DESC':
							$clean_request[ $key ] = $value;
							break;
						default:
							$clean_request[ $key ] = 'ASC';
					}
					break;
				case 'detached':
					if ( '1' == $value )
						$clean_request['detached'] = '1';
					break;
				case 'status':
					if ( 'trash' == $value )
						$clean_request['post_status'] = 'trash';
					break;
				/*
				 * ['s'] - Search Media by one or more keywords
				 * ['mla-search-connector'], ['mla-search-fields'] - Search Media options
				 */
				case 's':
					$clean_request[ $key ] = stripslashes( trim( $value ) );
					break;
				case 'mla-search-connector':
				case 'mla-search-fields':
					$clean_request[ $key ] = $value;
					break;
				default:
					// ignore anything else in $_REQUEST
			} // switch $key
		} // foreach $raw_request
		
		/*
		 * Pass query parameters to the filters for _execute_list_table_query
		 */
		self::$query_parameters = array( 'use_alt_text_view' => false );
		self::$query_parameters['detached'] = isset( $clean_request['detached'] );
		self::$query_parameters['orderby'] = $clean_request['orderby'];
		self::$query_parameters['order'] = $clean_request['order'];
		
		/*
		 * We will handle keyword search in the mla_query_posts_search_filter.
		 * There must be at least one search field to do a search.
		 */
		if ( isset( $clean_request['s'] ) ) {
			if ( ! empty( $clean_request['mla-search-fields'] ) ) {
				self::$query_parameters['s'] = $clean_request['s'];
				self::$query_parameters['mla-search-connector'] = $clean_request['mla-search-connector'];
				self::$query_parameters['mla-search-fields'] = $clean_request['mla-search-fields'];
				self::$query_parameters['sentence'] = isset( $clean_request['sentence'] );
				self::$query_parameters['exact'] = isset( $clean_request['exact'] );
				
			 	if ( in_array( 'alt-text', self::$query_parameters['mla-search-fields'] ) )
					self::$query_parameters['use_alt_text_view'] = true;
			} // !empty
			
			unset( $clean_request['s'] );
			unset( $clean_request['mla-search-connector'] );
			unset( $clean_request['mla-search-fields'] );
			unset( $clean_request['sentence'] );
			unset( $clean_request['exact'] );
		}

		/*
		 * We have to handle custom field/post_meta values here
		 * because they need a JOIN clause supplied by WP_Query
		 */
		if ( 'c_' == substr( self::$query_parameters['orderby'], 0, 2 ) ) {
			$option_value = MLAOptions::mla_custom_field_option_value( self::$query_parameters['orderby'] );
			if ( isset( $option_value['name'] ) ) {
				$clean_request['meta_key'] = $option_value['name'];
				$clean_request['orderby'] = 'meta_value';
				$clean_request['order'] = self::$query_parameters['order'];
			}
		} // custom field
		else {
			switch ( self::$query_parameters['orderby'] ) {
				/*
				 * '_wp_attachment_image_alt' is special; we'll handle it in the JOIN and ORDERBY filters
				 */
				case '_wp_attachment_image_alt':
					self::$query_parameters['use_alt_text_view'] = true;
					if ( isset($clean_request['orderby']) )
						unset($clean_request['orderby']);
					if ( isset($clean_request['order']) )
						unset($clean_request['order']);
					break;
				case '_wp_attached_file':
					$clean_request['meta_key'] = '_wp_attached_file';
					$clean_request['orderby'] = 'meta_value';
					$clean_request['order'] = self::$query_parameters['order'];
					break;
			} // switch $orderby
		}

		/*
		 * Ignore incoming paged value; use offset and count instead
		 */
		if ( ( (int) $count ) > 0 ) {
			$clean_request['offset'] = $offset;
			$clean_request['posts_per_page'] = $count;
		}
		
		/*
		 * ['mla_filter_term'] - filter by taxonomy
		 *
		 * cat =  0 is "All Categories", i.e., no filtering
		 * cat = -1 is "No Categories"
		 */
		if ( isset( $clean_request['mla_filter_term'] ) ) {
			if ( $clean_request['mla_filter_term'] != 0 ) {
				$tax_filter =  MLAOptions::mla_taxonomy_support('', 'filter');
				if ( $clean_request['mla_filter_term'] == -1 ) {
					$term_list = get_terms( $tax_filter, array(
						'fields' => 'ids',
						'hide_empty' => false
					) );
					$clean_request['tax_query'] = array(
						array(
							'taxonomy' => $tax_filter,
							'field' => 'id',
							'terms' => $term_list,
							'operator' => 'NOT IN' 
						) 
					);
				}  // mla_filter_term == -1
				else {
					$clean_request['tax_query'] = array(
						array(
							'taxonomy' => $tax_filter,
							'field' => 'id',
							'terms' => array(
								(int) $clean_request['mla_filter_term'] 
							) 
						) 
					);
				} // mla_filter_term != -1
			} // mla_filter_term != 0
			
			unset( $clean_request['mla_filter_term'] );
		} // isset mla_filter_term
		
		if ( isset( $clean_request['mla-tax'] ) ) {
			$clean_request['tax_query'] = array(
				array(
					'taxonomy' => $clean_request['mla-tax'],
					'field' => 'slug',
					'terms' => $clean_request['mla-term'],
					'include_children' => false 
				) 
			);
			
			unset( $clean_request['mla-tax'] );
			unset( $clean_request['mla-term'] );
		} // isset mla_tax
		
		return $clean_request;
	}

	/**
	 * Add filters, run query, remove filters
	 *
	 * @since 0.30
	 *
	 * @param	array	query parameters from web page, usually found in $_REQUEST
	 *
	 * @return	object	WP_Query object with query results
	 */
	private static function _execute_list_table_query( $request ) {
		global $wpdb, $table_prefix;
		
		/*
		 * ALT Text is special; we have to use an SQL VIEW to build 
		 * an intermediate table and modify the JOIN to include posts
		 * with no value for this metadata field.
		 */
		if ( self::$query_parameters['use_alt_text_view'] ) {
			$view_name = self::$mla_alt_text_view;
			$table_name = $table_prefix . 'postmeta';

			$result = $wpdb->query(
					"
					CREATE OR REPLACE VIEW {$view_name} AS
					SELECT post_id, meta_value
					FROM {$table_name}
					WHERE {$table_name}.meta_key = '_wp_attachment_image_alt'
					"
			);
		}

		add_filter( 'posts_search', 'MLAData::mla_query_posts_search_filter', 10, 2 ); // $search, &$this
		add_filter( 'posts_join', 'MLAData::mla_query_posts_join_filter' );
		add_filter( 'posts_where', 'MLAData::mla_query_posts_where_filter' );
		add_filter( 'posts_orderby', 'MLAData::mla_query_posts_orderby_filter' );

		$results = new WP_Query( $request );

		remove_filter( 'posts_orderby', 'MLAData::mla_query_posts_orderby_filter' );
		remove_filter( 'posts_where', 'MLAData::mla_query_posts_where_filter' );
		remove_filter( 'posts_join', 'MLAData::mla_query_posts_join_filter' );
		remove_filter( 'posts_search', 'MLAData::mla_query_posts_search_filter' );

		if ( self::$query_parameters['use_alt_text_view'] ) {
			$result = $wpdb->query( "DROP VIEW {$view_name}" );
		}

		return $results;
	}
	
	/**
	 * Adds a keyword search to the WHERE clause, if required
	 * 
	 * Defined as public because it's a filter.
	 *
	 * @since 0.60
	 *
	 * @param	string	query clause before modification
	 * @param	object	WP_Query object
	 *
	 * @return	string	query clause after keyword search addition
	 */
	public static function mla_query_posts_search_filter( $search_string, &$query_object ) {
		global $table_prefix, $wpdb;

		/*
		 * Process the keyword search argument, if present.
		 */
		$search_clause = '';
		if ( isset( self::$query_parameters['s'] ) ) {
			if (  self::$query_parameters['sentence'] ) {
				$search_terms = array( self::$query_parameters['s'] );
			} else {
				preg_match_all('/".*?("|$)|((?<=[\r\n\t ",+])|^)[^\r\n\t ",+]+/', self::$query_parameters['s'], $matches);
				$search_terms = array_map('_search_terms_tidy', $matches[0]);
			}
			
			$fields = self::$query_parameters['mla-search-fields'];
			$percent = self::$query_parameters['exact'] ? '' : '%';
			$connector = '';
			foreach ( $search_terms as $term ) {
				$term = esc_sql( like_escape( $term ) );
				$inner_connector = '';
				$search_clause .= "{$connector}(";
				
				if ( in_array( 'content', $fields ) ) {
					$search_clause .= "{$inner_connector}({$wpdb->posts}.post_content LIKE '{$percent}{$term}{$percent}')";
					$inner_connector = ' OR ';
				}
				
				if ( in_array( 'title', $fields ) ) {
					$search_clause .= "{$inner_connector}({$wpdb->posts}.post_title LIKE '{$percent}{$term}{$percent}')";
					$inner_connector = ' OR ';
				}
				
				if ( in_array( 'excerpt', $fields ) ) {
					$search_clause .= "{$inner_connector}({$wpdb->posts}.post_excerpt LIKE '{$percent}{$term}{$percent}')";
					$inner_connector = ' OR ';
				}
				
				if ( in_array( 'alt-text', $fields ) ) {
					$view_name = self::$mla_alt_text_view;
					$search_clause .= "{$inner_connector}({$view_name}.meta_value LIKE '{$percent}{$term}{$percent}')";
					$inner_connector = ' OR ';
				}
				
				if ( in_array( 'name', $fields ) ) {
					$search_clause .= "{$inner_connector}({$wpdb->posts}.post_name LIKE '{$percent}{$term}{$percent}')";
				}
				
				$search_clause .= ")";
				$connector = ' ' . self::$query_parameters['mla-search-connector'] . ' ';
			} // foreach

			if ( !empty($search_clause) ) {
				$search_clause = " AND ({$search_clause}) ";
				if ( !is_user_logged_in() )
					$search_clause .= " AND ($wpdb->posts.post_password = '') ";
			}
		} // isset 's'
		
		return $search_clause;
	}

	/**
	 * Adds a JOIN clause, if required, to handle sorting/searching on ALT Text
	 * 
	 * Defined as public because it's a filter.
	 *
	 * @since 0.30
	 *
	 * @param	string	query clause before modification
	 *
	 * @return	string	query clause after "LEFT JOIN view ON post_id" item modification
	 */
	public static function mla_query_posts_join_filter( $join_clause ) {
		global $table_prefix;
		/*
		 * '_wp_attachment_image_alt' is special; we have to use an SQL VIEW to
		 * build an intermediate table and modify the JOIN to include posts with
		 * no value for this metadata field.
		 */
		if ( self::$query_parameters['use_alt_text_view'] ) {
			$view_name = self::$mla_alt_text_view;
			$join_clause .= " LEFT JOIN {$view_name} ON ({$table_prefix}posts.ID = {$view_name}.post_id)";
		}

		return $join_clause;
	}

	/**
	 * Adds a WHERE clause for detached items
	 * 
	 * Modeled after _edit_attachments_query_helper in wp-admin/post.php.
	 * Defined as public because it's a filter.
	 *
	 * @since 0.1
	 *
	 * @param	string	query clause before modification
	 *
	 * @return	string	query clause after "detached" item modification
	 */
	public static function mla_query_posts_where_filter( $where_clause ) {
		global $table_prefix;

		if ( self::$query_parameters['detached'] )
			$where_clause .= " AND {$table_prefix}posts.post_parent < 1";
			
		return $where_clause;
	}

	/**
	 * Adds a ORDERBY clause, if required
	 * 
	 * Expands the range of sort options because the logic in WP_Query is limited.
	 * Defined as public because it's a filter.
	 *
	 * @since 0.30
	 *
	 * @param	string	query clause before modification
	 *
	 * @return	string	updated query clause
	 */
	public static function mla_query_posts_orderby_filter( $orderby_clause ) {
		global $table_prefix;

		if ( isset( self::$query_parameters['orderby'] ) ) {
			if ( 'c_' == substr( self::$query_parameters['orderby'], 0, 2 ) ) {
				$orderby = $table_prefix . 'postmeta.meta_value';
			} // custom field sort
			else {
				switch ( self::$query_parameters['orderby'] ) {
					case 'none':
						$orderby = '';
						break;
					/*
					 * There are two columns defined that end up sorting on post_title,
					 * so we can't use the database column to identify the column but
					 * we actually sort on the database column.
					 */
					case 'title_name':
						$orderby = "{$table_prefix}posts.post_title";
						break;
					/*
					 * The _wp_attached_file meta data value is present for all attachments, and the
					 * sorting on the meta data value is handled by WP_Query
					 */
					case '_wp_attached_file':
						$orderby = '';
						break;
					/*
					 * The _wp_attachment_image_alt value is only present for images, so we have to
					 * use the view we prepared to get attachments with no meta data value
					 */
					case '_wp_attachment_image_alt':
						$orderby = self::$mla_alt_text_view . '.meta_value';
						break;
					default:
						$orderby = "{$table_prefix}posts." . self::$query_parameters['orderby'];
				} // $query_parameters['orderby']
			}
			
			if ( ! empty( $orderby ) )
				$orderby_clause = $orderby . ' ' . self::$query_parameters['order'];
		} // isset

		return $orderby_clause;
	}
	
	/** 
	 * Retrieve an Attachment array given a $post_id
	 *
	 * The (associative) array will contain every field that can be found in
	 * the posts and postmeta tables, and all references to the attachment.
	 * 
	 * @since 0.1
	 * @uses $post WordPress global variable
	 * 
	 * @param	int		The ID of the attachment post
	 * @return	NULL|array NULL on failure else associative array
	 */
	function mla_get_attachment_by_id( $post_id ) {
		global $post;
		
		$item = get_post( $post_id );
		if ( empty( $item ) ) {
			error_log( "ERROR: mla_get_attachment_by_id(" . $post_id . ") not found", 0 );
			return NULL;
		}
		
		if ( $item->post_type != 'attachment' ) {
			error_log( "ERROR: mla_get_attachment_by_id(" . $post_id . ") wrong post_type: " . $item->post_type, 0 );
			return NULL;
		}
		
		$post_data = (array) $item;
		$post = $item;
		setup_postdata( $item );
		
		/*
		 * Add parent data
		 */
		$post_data = array_merge( $post_data, self::mla_fetch_attachment_parent_data( $post_data['post_parent'] ) );
		
		/*
		 * Add meta data
		 */
		$post_data = array_merge( $post_data, self::mla_fetch_attachment_metadata( $post_id ) );
		
		/*
		 * Add references
		 */
		$post_data['mla_references'] = self::mla_fetch_attachment_references( $post_id, $post_data['post_parent'] );
		
		return $post_data;
	}
	
	/**
	 * Returns information about an attachment's parent, if found
	 *
	 * @since 0.1
	 *
	 * @param	int		post ID of attachment's parent, if any
	 *
	 * @return	array	Parent information; post_date, post_title and post_type
	 */
	public static function mla_fetch_attachment_parent_data( $parent_id ) {
		$parent_data = array();
		if ( $parent_id ) {
			$parent = get_post( $parent_id );
			if ( isset( $parent->post_date ) )
				$parent_data['parent_date'] = $parent->post_date;
			if ( isset( $parent->post_title ) )
				$parent_data['parent_title'] = $parent->post_title;
			if ( isset( $parent->post_type ) )
				$parent_data['parent_type'] = $parent->post_type;
		}
		
		return $parent_data;
	}
	
	/**
	 * Fetch and filter meta data for an attachment
	 * 
	 * Returns a filtered array of a post's meta data. Internal values beginning with '_'
	 * are stripped out or converted to an 'mla_' equivalent. Array data is replaced with
	 * a string containing the first array element.
	 *
	 * @since 0.1
	 *
	 * @param	int		post ID of attachment
	 *
	 * @return	array	Meta data variables
	 */
	public static function mla_fetch_attachment_metadata( $post_id ) {
		$attached_file = NULL;
		$results = array();
		$post_meta = get_metadata( 'post', $post_id );

		if ( is_array( $post_meta ) ) {
			foreach ( $post_meta as $post_meta_key => $post_meta_value ) {
				if ( empty( $post_meta_key ) )
					continue;
					
				if ( '_' == $post_meta_key{0} ) {
					if ( stripos( $post_meta_key, '_wp_attached_file' ) === 0 ) {
						$key = 'mla_wp_attached_file';
						$attached_file = $post_meta_value[0];
					} elseif ( stripos( $post_meta_key, '_wp_attachment_metadata' ) === 0 ) {
						$key = 'mla_wp_attachment_metadata';
						$post_meta_value = unserialize( $post_meta_value[0] );
					} elseif ( stripos( $post_meta_key, '_wp_attachment_image_alt' ) === 0 ) {
						$key = 'mla_wp_attachment_image_alt';
					} else {
						continue;
					}
				} else {
					if ( stripos( $post_meta_key, 'mla_' ) === 0 )
						$key = $post_meta_key;
					else
						$key = 'mla_item_' . $post_meta_key;
				}
				
				if ( is_array( $post_meta_value ) && count( $post_meta_value ) == 1 )
					$value = $post_meta_value[0];
				else
					$value = $post_meta_value;
				
				$results[ $key ] = $value;
			} // foreach $post_meta

			if ( !empty( $attached_file ) ) {
				$last_slash = strrpos( $attached_file, '/' );
				if ( false === $last_slash ) {
					$results['mla_wp_attached_path'] = '';
					$results['mla_wp_attached_filename'] = $attached_file;
				}
				else {
					$results['mla_wp_attached_path'] = substr( $attached_file, 0, $last_slash + 1 );
					$results['mla_wp_attached_filename'] = substr( $attached_file, $last_slash + 1 );
				}
			} // $attached_file
		} // is_array($post_meta)
		
		return $results;
	}
	
	/**
	 * Find Featured Image and inserted image/link references to an attachment
	 * 
	 * Searches all post and page content to see if the attachment is used 
	 * as a Featured Image or inserted in the post as an image or link.
	 *
	 * @since 0.1
	 *
	 * @param	int	post ID of attachment
	 * @param	int	post ID of attachment's parent, if any
	 *
	 * @return	array	Reference information; see $references array comments
	 */
	public static function mla_fetch_attachment_references( $ID, $parent ) {
		global $wpdb;
		
		/*
		 * tested_reference	true if any of the four where-used types was processed
		 * found_reference	true if any where-used array is not empty()
		 * found_parent		true if $parent matches a where-used post ID
		 * is_unattached	true if $parent is zero (0)
		 * base_file		relative path and name of the uploaded file, e.g., 2012/04/image.jpg
		 * path				path to the file, relative to the "uploads/" directory, e.g., 2012/04/
		 * file				The name portion of the base file, e.g., image.jpg
		 * files			base file and any other image size files. Array key is path and file name.
		 *					Non-image file value is a string containing file name without path
		 *					Image file value is an array with file name, width and height
		 * features			Array of objects with the post_type and post_title of each post
		 *					that has the attachment as a "Featured Image"
		 * inserts			Array of specific files (i.e., sizes) found in one or more posts/pages
		 *					as an image (<img>) or link (<a href>). The array key is the path and file name.
		 *					The array value is an array with the ID, post_type and post_title of each reference
		 * mla_galleries	Array of objects with the post_type and post_title of each post
		 *					that was returned by an [mla_gallery] shortcode
		 * galleries		Array of objects with the post_type and post_title of each post
		 *					that was returned by a [gallery] shortcode
		 * parent_type		'post' or 'page' or the custom post type of the attachment's parent
		 * parent_title		post_title of the attachment's parent
		 * parent_errors	UNATTACHED, ORPHAN, BAD/INVALID PARENT
		 */
		$references = array(
			'tested_reference' => false,
			'found_reference' => false,
			'found_parent' => false,
			'is_unattached' => ( ( (int) $parent ) === 0 ),
			'base_file' => '',
			'path' => '',
			'file' => '',
			'files' => array(),
			'features' => array(),
			'inserts' => array(),
			'mla_galleries' => array(),
			'galleries' => array(),
			'parent_type' => '',
			'parent_title' => '',
			'parent_errors' => ''
		);
		
		/*
		 * Fill in Parent data
		 */
		$parent_data = self::mla_fetch_attachment_parent_data( $parent );
		if ( isset( $parent_data['parent_type'] ) ) 
			$references['parent_type'] =  $parent_data['parent_type'];
		if ( isset( $parent_data['parent_title'] ) ) 
			$references['parent_title'] =  $parent_data['parent_title'];

		$attachment_metadata = get_post_meta( $ID, '_wp_attachment_metadata', true );
		if ( empty( $attachment_metadata ) ) {
			$references['base_file'] = get_post_meta( $ID, '_wp_attached_file', true );
		} // empty( $attachment_metadata )
		else {
			$references['base_file'] = $attachment_metadata['file'];
			$sizes = isset( $attachment_metadata['sizes'] ) ? $attachment_metadata['sizes'] : NULL;
			if ( !empty( $sizes ) ) {
				/* Using the name as the array key ensures each name is added only once */
				foreach ( $sizes as $size ) {
					$references['files'][ $references['path'] . $size['file'] ] = $size;
				}
				
			}
		} // ! empty( $attachment_metadata )
		
		$references['files'][ $references['base_file'] ] = $references['base_file'];
		$last_slash = strrpos( $references['base_file'], '/' );
		if ( false === $last_slash ) {
			$references['path'] = '';
			$references['file'] = $references['base_file'];
		}
		else {
			$references['path'] = substr( $references['base_file'], 0, $last_slash + 1 );
			$references['file'] = substr( $references['base_file'], $last_slash + 1 );
		}

		/*
		 * Process the where-used settings option
		 */
		if ('checked' == MLAOptions::mla_get_option( 'exclude_revisions' ) )
			$exclude_revisions = "(post_type <> 'revision') AND ";
		else
			$exclude_revisions = '';

		/*
		 * Accumulate reference test types, e.g.,  0 = no tests, 4 = all tests
		 */
		$reference_tests = 0;

		/*
		 * Look for the "Featured Image(s)", if enabled
		 */
		if ( MLAOptions::$process_featured_in ) {
			$reference_tests++;
			$features = $wpdb->get_results( 
					"
					SELECT post_id
					FROM {$wpdb->postmeta}
					WHERE meta_key = '_thumbnail_id' AND meta_value = {$ID}
					"
			);
			
			if ( !empty( $features ) ) {
				foreach ( $features as $feature ) {
					$feature_results = $wpdb->get_results(
							"
							SELECT post_type, post_title
							FROM {$wpdb->posts}
							WHERE {$exclude_revisions}(ID = {$feature->post_id})
							"
					);
						
					if ( !empty( $feature_results ) ) {
						$references['found_reference'] = true;
						$references['features'][ $feature->post_id ] = $feature_results[0];
					
						if ( $feature->post_id == $parent ) {
							$references['found_parent'] = true;
						}
					} // !empty
				} // foreach $feature
			}
		} // $process_featured_in
		
		/*
		 * Look for item(s) inserted in post_content
		 */
		if ( MLAOptions::$process_inserted_in ) {
			$reference_tests++;
			foreach ( $references['files'] as $file => $file_data ) {
				$like = like_escape( $file );
				$inserts = $wpdb->get_results(
					$wpdb->prepare(
						"
						SELECT ID, post_type, post_title 
						FROM {$wpdb->posts}
						WHERE {$exclude_revisions}(
							CONVERT(`post_content` USING utf8 )
							LIKE %s)
						", "%{$like}%"
					)
				);
				
				if ( !empty( $inserts ) ) {
					$references['found_reference'] = true;
					$references['inserts'][ $file ] = $inserts;
					
					foreach ( $inserts as $insert ) {
						if ( $insert->ID == $parent ) {
							$references['found_parent'] = true;
						}
					} // foreach $insert
				} // !empty
			} // foreach $file
		} // $process_inserted_in
		
		/*
		 * Look for [mla_gallery] references
		 */
		if ( MLAOptions::$process_mla_gallery_in ) {
			$reference_tests++;
			if ( self::_build_mla_galleries( MLAOptions::MLA_MLA_GALLERY_IN_TUNING, self::$mla_galleries, '[mla_gallery', $exclude_revisions ) ) {
				$galleries = self::_search_mla_galleries( self::$mla_galleries, $ID );
				if ( !empty( $galleries ) ) {
					$references['found_reference'] = true;
					$references['mla_galleries'] = $galleries;
	
					foreach ( $galleries as $post_id => $gallery ) {
						if ( $post_id == $parent ) {
							$references['found_parent'] = true;
						}
					} // foreach $gallery
				} // !empty
				else
					$references['mla_galleries'] = array();
			}
		} // $process_mla_gallery_in
		
		/*
		 * Look for [gallery] references
		 */
		if ( MLAOptions::$process_gallery_in ) {
			$reference_tests++;
			if ( self::_build_mla_galleries( MLAOptions::MLA_GALLERY_IN_TUNING, self::$galleries, '[gallery', $exclude_revisions ) ) {
				$galleries = self::_search_mla_galleries( self::$galleries, $ID );
				if ( !empty( $galleries ) ) {
					$references['found_reference'] = true;
					$references['galleries'] = $galleries;
	
					foreach ( $galleries as $post_id => $gallery ) {
						if ( $post_id == $parent ) {
							$references['found_parent'] = true;
						}
					} // foreach $gallery
				} // !empty
				else
					$references['galleries'] = array();
			}
		} // $process_gallery_in
		
		/*
		 * Evaluate and summarize reference tests
		 */
		$errors = '';
		if ( 0 == $reference_tests ) {
			$references['tested_reference'] = false;
			$errors .= '(NO REFERENCE TESTS)';
		}
		else {
			$references['tested_reference'] = true;
			$suffix = ( 4 == $reference_tests ) ? '' : '?';

			if ( !$references['found_reference'] )
				$errors .= "(ORPHAN{$suffix}) ";
			
			if ( !$references['found_parent'] && !empty( $references['parent_title'] ) )
				$errors .= "(BAD PARENT{$suffix})";
		}
		
		if ( $references['is_unattached'] )
			$errors .= '(UNATTACHED) ';
		elseif ( empty( $references['parent_title'] ) ) 
			$errors .= '(INVALID PARENT) ';

		$references['parent_errors'] = trim( $errors );
		return $references;
	}
	
	/**
	 * Objects containing [gallery] shortcodes
	 *
	 * This array contains all of the objects containing one or more [gallery] shortcodes
	 * and array(s) of which attachments each [gallery] contains. The arrays are built once
	 * each page load and cached for subsequent calls.
	 *
	 * The outer array is keyed by post_id. It contains an array of [gallery] entries numbered from one (1).
	 * Each inner array has these elements:
	 * ['parent_title'] post_title of the gallery parent, 
	 * ['parent_type'] 'post' or 'page' or the custom post_type of the gallery parent,
	 * ['query'] contains a string with the arguments of the [gallery], 
	 * ['results'] contains an array of post_ids for the objects in the gallery.
	 *
	 * @since 0.70
	 *
	 * @var	array
	 */
	private static $galleries = null;

	/**
	 * Objects containing [mla_gallery] shortcodes
	 *
	 * This array contains all of the objects containing one or more [mla_gallery] shortcodes
	 * and array(s) of which attachments each [mla_gallery] contains. The arrays are built once
	 * each page load and cached for subsequent calls.
	 *
	 * @since 0.70
	 *
	 * @var	array
	 */
	private static $mla_galleries = null;

	/**
	 * Invalidates the $mla_galleries or $galleries array and cached values
	 *
	 * @since 1.00
	 *
	 * @param	string name of the gallery's cache/option variable
	 *
	 * @return	void
	 */
	public static function mla_flush_mla_galleries( $option_name ) {
		delete_transient( MLA_OPTION_PREFIX . 't_' . $option_name );

		switch ( $option_name ) {
			case MLAOptions::MLA_GALLERY_IN_TUNING:
				self::$galleries = null;
				break;
			case MLAOptions::MLA_MLA_GALLERY_IN_TUNING:
				self::$mla_galleries = null;
				break;
			default:
				//	ignore everything else
		} // switch
	}
	
	/**
	 * Invalidates $mla_galleries and $galleries arrays and cached values after post, page or attachment updates
	 *
	 * @since 1.00
	 *
	 * @param	integer ID of post/page/attachment; not used at this time
	 *
	 * @return	void
	 */
	public static function mla_save_post_action( $post_id ) {
		self::mla_flush_mla_galleries( MLAOptions::MLA_GALLERY_IN_TUNING );
		self::mla_flush_mla_galleries( MLAOptions::MLA_MLA_GALLERY_IN_TUNING );
	}
	
	/**
	 * Builds the $mla_galleries or $galleries array
	 *
	 * @since 0.70
	 *
	 * @param	string name of the gallery's cache/option variable
	 * @param	array by reference to the private static galleries array variable
	 * @param	string the shortcode to be searched for and processed
	 * @param	boolean true to exclude revisions from the search
	 *
	 * @return	boolean true if the galleries array is not empty
	 */
	private static function _build_mla_galleries( $option_name, &$galleries_array, $shortcode, $exclude_revisions ) {
		global $wpdb, $post;

		if ( is_array( $galleries_array ) ) {
			if ( ! empty( $galleries_array ) ) {
				return true;
			} else {
				return false;
			}
		}

		$option_value = MLAOptions::mla_get_option( $option_name );
		if ( 'disabled' == $option_value )
			return false;
		elseif ( 'cached' == $option_value ) {
			$galleries_array = get_transient( MLA_OPTION_PREFIX . 't_' . $option_name );
			if ( is_array( $galleries_array ) ) {
				if ( ! empty( $galleries_array ) ) {
					return true;
				} else {
					return false;
				}
			}
			else
				$galleries_array = NULL;
		} // cached
		
		/*
		 * $galleries_array is null, so build the array
		 */
		$galleries_array = array();
		
		if ( $exclude_revisions )
			$exclude_revisions = "(post_type <> 'revision') AND ";
		else
			$exclude_revisions = '';
		
		$like = like_escape( $shortcode );
		$results = $wpdb->get_results(
			$wpdb->prepare(
				"
				SELECT ID, post_type, post_title, post_content
				FROM {$wpdb->posts}
				WHERE {$exclude_revisions}(
					CONVERT(`post_content` USING utf8 )
					LIKE %s)
				", "%{$like}%"
			)
		);

		if ( empty( $results ) )
			return false;
			
		foreach ( $results as $result ) {
			$count = preg_match_all( "/\\{$shortcode}(.*)\\]/", $result->post_content, $matches, PREG_PATTERN_ORDER );
			if ( $count ) {
				$result_id = $result->ID;
				$galleries_array[ $result_id ]['parent_title'] = $result->post_title;
				$galleries_array[ $result_id ]['parent_type'] = $result->post_type;
				$galleries_array[ $result_id ]['results'] = array();
				$galleries_array[ $result_id ]['galleries'] = array();
				$instance = 0;
				
				foreach ( $matches[1] as $index => $match ) {
					/*
					 * Filter out shortcodes that are not an exact match
					 */
					if ( empty( $match ) || ( ' ' == substr( $match, 0, 1 ) ) ) {
						$instance++;
						$galleries_array[ $result_id ]['galleries'][ $instance ]['query'] = trim( $matches[1][$index] );
						$galleries_array[ $result_id ]['galleries'][ $instance ]['results'] = array();
						
						$post = $result; // set global variable for mla_gallery_shortcode
						$attachments = MLAShortcodes::mla_get_shortcode_attachments( $result_id, $galleries_array[ $result_id ]['galleries'][ $instance ]['query'] );
						if ( ! empty( $attachments ) )
							foreach ( $attachments as $attachment ) {
								$galleries_array[ $result_id ]['results'][ $attachment->ID ] = $attachment->ID;
								$galleries_array[ $result_id ]['galleries'][ $instance ]['results'][] = $attachment->ID;
							}
					} // exact match
				} // foreach $match
			} // if $count
		} // foreach $result
	
	/*
	 * Maybe cache the results
	 */	
	if ( 'cached' == $option_value ) {
		set_transient( MLA_OPTION_PREFIX . 't_' . $option_name, $galleries_array, 900 ); // fifteen minutes
	}

	return true;
	}
	
	/**
	 * Search the $mla_galleries or $galleries array
	 *
	 * @since 0.70
	 *
	 * @param	array	by reference to the private static galleries array variable
	 * @param	int		the attachment ID to be searched for and processed
	 *
	 * @return	array	All posts/pages with one or more galleries that include the attachment.
	 * 					The array key is the parent_post ID; each entry contains post_title and post_type.
	 */
	private static function _search_mla_galleries( &$galleries_array, $attachment_id ) {
		$gallery_refs = array();
		if ( ! empty( $galleries_array ) ) {
			foreach ( $galleries_array as $parent_id => $gallery ) {
				if ( in_array( $attachment_id, $gallery['results'] ) ) {
					$gallery_refs[ $parent_id ] = array ( 'post_title' => $gallery['parent_title'], 'post_type' => $gallery['parent_type'] );
				}
			} // foreach gallery
		} // !empty
		
		return $gallery_refs;
	}
		
	/**
	 * Fetch and filter IPTC and EXIF meta data for an image attachment
	 * 
	 * Returns 
	 *
	 * @since 0.90
	 *
	 * @param	int		post ID of attachment
	 * @param	string	optional; if $post_id is zero, path to the image file.
	 *
	 * @return	array	Meta data variables
	 */
	public static function mla_fetch_attachment_image_metadata( $post_id, $path = '' ) {
		$results = array(
			'mla_iptc_metadata' => array(),
			'mla_exif_metadata' => array()
			);

//		$post_meta = get_metadata( 'post', $post_id, '_wp_attachment_metadata' );
//		if ( is_array( $post_meta ) && isset( $post_meta[0]['file'] ) ) {
		if ( 0 != $post_id )
			$path = get_attached_file($post_id);
			
		if ( ! empty( $path ) ) {
			$size = getimagesize( $path, $info );
			foreach ( $info as $key => $value ) {
			}
			
			if ( is_callable( 'iptcparse' ) ) {
				if ( !empty( $info['APP13'] ) ) {
					$iptc_values = iptcparse( $info['APP13'] );
					if ( ! is_array( $iptc_values ) )
						$iptc_values = array();
						
					foreach ( $iptc_values as $key => $value ) {
						if ( in_array( $key, array( '1#000', '1#020', '1#022', '1#120', '1#122', '2#000',  '2#200', '2#201' ) ) ) {
							$value = unpack( 'nbinary', $value[0] );
							$results['mla_iptc_metadata'][ $key ] = (string) $value['binary'];
						}
						elseif ( 1 == count( $value ) )
							$results['mla_iptc_metadata'][ $key ] = $value[0];
						else
							$results['mla_iptc_metadata'][ $key ] = $value;
							
					} // foreach $value
				} // !empty
			}
				
			if ( is_callable( 'exif_read_data' ) && in_array( $size[2], array( IMAGETYPE_JPEG, IMAGETYPE_TIFF_II, IMAGETYPE_TIFF_MM ) ) ) {
				$results['mla_exif_metadata'] = exif_read_data( $path );
			}
		}
		
		return $results;
	}
	
	/**
	 * Update a single item; change the meta data 
	 * for a single attachment.
	 * 
	 * @since 0.1
	 * 
	 * @param	int		The ID of the attachment to be updated
	 * @param	array	Field name => value pairs
	 * @param	array	Optional taxonomy term values, default null
	 * @param	array	Optional taxonomy actions (add, remove, replace), default null
	 *
	 * @return	array	success/failure message and NULL content
	 */
	public static function mla_update_single_item( $post_id, $new_data, $tax_input = NULL, $tax_actions = NULL ) {
		$post_data = MLAData::mla_get_attachment_by_id( $post_id );
		
		if ( !isset( $post_data ) )
			return array(
				'message' => 'ERROR: Could not retrieve Attachment.',
				'body' => '' 
			);
		
		$message = '';
		$updates = array( 'ID' => $post_id );
		$new_data = stripslashes_deep( $new_data );
		$new_meta = NULL;

		foreach ( $new_data as $key => $value ) {
			switch ( $key ) {
				case 'post_title':
					if ( $value == $post_data[ $key ] )
						break;
						
					$message .= sprintf( 'Changing Title from "%1$s" to "%2$s"<br>', esc_attr( $post_data[ $key ] ), esc_attr( $value ) );
					$updates[ $key ] = $value;
					break;
				case 'post_name':
					if ( $value == $post_data[ $key ] )
						break;
					
					$value = sanitize_title( $value );
					
					/*
					 * Make sure new slug is unique
					 */
					$args = array(
						'name' => $value,
						'post_type' => 'attachment',
						'post_status' => 'inherit',
						'showposts' => 1 
					);
					$my_posts = get_posts( $args );
					
					if ( $my_posts ) {
						$message .= sprintf( 'ERROR: Could not change Name/Slug "%1$s"; name already exists<br>', $value );
					} else {
						$message .= sprintf( 'Changing Name/Slug from "%1$s" to "%2$s"<br>', esc_attr( $post_data[ $key ] ), $value );
						$updates[ $key ] = $value;
					}
					break;
				case 'image_alt':
					$key = 'mla_wp_attachment_image_alt';
					if ( !isset( $post_data[ $key ] ) )
						$post_data[ $key ] = '';
					
					if ( $value == $post_data[ $key ] )
						break;
					
					if ( empty( $value ) ) {
						if ( delete_post_meta( $post_id, '_wp_attachment_image_alt', $value ) )
							$message .= sprintf( 'Deleting Alternate Text, was "%1$s"<br>', esc_attr( $post_data[ $key ] ) );
						else
							$message .= sprintf( 'ERROR: Could not delete Alternate Text, remains "%1$s"<br>', esc_attr( $post_data[ $key ] ) );
					} else {
						if ( update_post_meta( $post_id, '_wp_attachment_image_alt', $value ) )
							$message .= sprintf( 'Changing Alternate Text from "%1$s" to "%2$s"<br>', esc_attr( $post_data[ $key ] ), esc_attr( $value ) );
						else
							$message .= sprintf( 'ERROR: Could not change Alternate Text from "%1$s" to "%2$s"<br>', esc_attr( $post_data[ $key ] ), esc_attr( $value ) );
					}
					break;
				case 'post_excerpt':
					if ( $value == $post_data[ $key ] )
						break;
						
					$message .= sprintf( 'Changing Caption from "%1$s" to "%2$s"<br>', esc_attr( $post_data[ $key ] ), esc_attr( $value ) );
					$updates[ $key ] = $value;
					break;
				case 'post_content':
					if ( $value == $post_data[ $key ] )
						break;
						
					$message .= sprintf( 'Changing Description from "%1$s" to "%2$s"<br>', esc_textarea( $post_data[ $key ] ), esc_textarea( $value ) );
					$updates[ $key ] = $value;
					break;
				case 'post_parent':
					if ( $value == $post_data[ $key ] )
						break;
						
					$value = absint( $value );
					
					$message .= sprintf( 'Changing Parent from "%1$s" to "%2$s"<br>', $post_data[ $key ], $value );
					$updates[ $key ] = $value;
					break;
				case 'menu_order':
					if ( $value == $post_data[ $key ] )
						break;
						
					$value = absint( $value );
					
					$message .= sprintf( 'Changing Menu Order from "%1$s" to "%2$s"<br>', $post_data[ $key ], $value );
					$updates[ $key ] = $value;
					break;
				case 'post_author':
					if ( $value == $post_data[ $key ] )
						break;
						
					$value = absint( $value );
					
					$from_user = get_userdata( $post_data[ $key ] );
					$to_user = get_userdata( $value );
					$message .= sprintf( 'Changing Author from "%1$s" to "%2$s"<br>', $from_user->display_name, $to_user->display_name );
					$updates[ $key ] = $value;
					break;
				case 'taxonomy_updates':
					$tax_input = $value['inputs'];
					$tax_actions = $value['actions'];
					break;
				case 'custom_updates':
					$new_meta = $value;
					break;
				default:
					// Ignore anything else
			} // switch $key
		} // foreach $new_data
		
		if ( !empty( $tax_input ) ) {
			foreach ( $tax_input as $taxonomy => $tags ) {
				if ( !empty( $tax_actions ) ) 
					$tax_action = $tax_actions[ $taxonomy ];
				else
					$tax_action = 'replace';
					
				$taxonomy_obj = get_taxonomy( $taxonomy );

				if ( current_user_can( $taxonomy_obj->cap->assign_terms ) ) {
					$terms_before = wp_get_post_terms( $post_id, $taxonomy, array(
						'fields' => 'ids' // all' 
					) );
					if ( is_array( $tags ) ) // array = hierarchical, string = non-hierarchical.
						$tags = array_filter( $tags );
					
					switch ( $tax_action ) {
						case 'add':
							$action_name = 'Adding';
							$result = wp_set_post_terms( $post_id, $tags, $taxonomy, true );
							break;
						case 'remove':
							$action_name = 'Removing';
							$tags = self::_remove_tags( $terms_before, $tags, $taxonomy_obj );
							$result = wp_set_post_terms( $post_id, $tags, $taxonomy );
							break;
						case 'replace':
							$action_name = 'Replacing';
							$result = wp_set_post_terms( $post_id, $tags, $taxonomy );
							break;
						default:
							$action_name = 'Ignoring';
							$result = NULL;
							// ignore anything else
					}
					
					$terms_after = wp_get_post_terms( $post_id, $taxonomy, array(
						'fields' => 'ids' // all' 
					) );
					
					if ( $terms_before != $terms_after )
						$message .= sprintf( '%1$s "%2$s" terms<br>', $action_name, $taxonomy );
				} // current_user_can
				else {
					$message .= sprintf( 'You cannot assign "%1$s" terms<br>', $action_name, $taxonomy );
				}
			} // foreach $tax_input
		} // !empty $tax_input
		
		if ( is_array( $new_meta ) ) {
			foreach ( $new_meta as $meta_key => $meta_value ) {
				if ( isset( $post_data[ 'mla_item_' . $meta_key ] ) )
					$old_meta_value = $post_data[ 'mla_item_' . $meta_key ];
				else
					$old_meta_value = '';

				if ( $old_meta_value != $meta_value ) {
					$message .= sprintf( 'Changing %1$s from "%2$s" to "%3$s"<br>', $meta_key, $old_meta_value, $meta_value );
					$results = update_post_meta( $post_id, $meta_key, $meta_value );
				}
			} // foreach $new_meta
		}
		
		if ( empty( $message ) )
			return array(
				'message' => 'Item: ' . $post_id . ', no changes detected.',
				'body' => '' 
			);
		else {
			if ( wp_update_post( $updates ) ) {
				$final_message = 'Item: ' . $post_id . ' updated.';
				/*
				 * Uncomment this for debugging.
				 */
				// $final_message .= '<br>' . $message;
				
				return array(
					'message' => $final_message,
					'body' => '' 
				);
			}
			else
				return array(
					'message' => 'ERROR: Item ' . $post_id . ' update failed.',
					'body' => '' 
				);
		}
	}
	
	/**
	 * Remove tags from a term ids list
	 * 
	 * @since 0.40
	 * 
	 * @param	array	The term ids currently assigned
	 * @param	array | string	The term ids (array) or names (string) to remove
	 * @param	object	The taxonomy object
	 *
	 * @return	array	Term ids of the surviving tags
	 */
	private static function _remove_tags( $terms_before, $tags, $taxonomy_obj ) {
		if ( ! is_array( $tags ) ) {
			/*
			 * Convert names to term ids
			 */
			$comma = _x( ',', 'tag delimiter' );
			if ( ',' !== $comma )
				$tags = str_replace( $comma, ',', $tags );
			$terms = explode( ',', trim( $tags, " \n\t\r\0\x0B," ) );

			$tags = array();
			foreach ( (array) $terms as $term) {
				if ( !strlen(trim($term)) )
					continue;

				// Skip if a non-existent term name is passed.
				if ( ! $term_info = term_exists($term, $taxonomy_obj->name ) )
					continue;

				if ( is_wp_error($term_info) )
					continue;

				$tags[] = $term_info['term_id'];
			} // foreach term
		} // not an array
		
		$tags = array_map( 'intval', $tags );
		$tags = array_unique( $tags );
		$terms_after = array_diff( array_map( 'intval', $terms_before ), $tags );

		return $terms_after;
	}
	
	/**
	 * Format printable version of binary data
	 * 
	 * @since 0.90
	 * 
	 * @param	string	Binary data
	 * @param	integer	Bytes to format, default = 0 (all bytes)
	 * @param	intger	Bytes to format on each line
	 *
	 * @return	string	Printable representation of $data
	 */
	private static function _hex_dump( $data, $limit = 0, $bytes_per_row = 16 ) {
		if ( 0 == $limit )
			$limit = strlen( $data );
			
		$position = 0;
		$output = "\r\n";
		
		while ( $position < $limit ) {
			$row_length = strlen( substr( $data, $position ) );
			
			if ( $row_length > ( $limit - $position ) )
				$row_length = $limit - $position;

			if ( $row_length > $bytes_per_row )
				$row_length = $bytes_per_row;
			
			$row_data = substr( $data, $position, $row_length );
			
			$print_string = '';
			$hex_string = '';
			for ( $index = 0; $index < $row_length; $index++ ) {
				$char = ord( substr( $row_data, $index, 1 ) );
				if ( ( 31 < $char ) && ( 127 > $char ) )
					$print_string .= chr($char);
				else
					$print_string .= '.';
					
				$hex_string .= ' ' . bin2hex( chr($char) );
			} // for
			
			$output .= str_pad( $print_string, $bytes_per_row, ' ', STR_PAD_RIGHT ) . $hex_string . "\r\n";
			$position += $row_length;
		} // while
		
		return $output;
	}
} // class MLAData
?>