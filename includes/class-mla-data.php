<?php
/**
 * Database and template file access for MLA needs.
 *
 * @package Media Library Assistant
 * @since 0.1
 */

/**
 * Class MLA (Media Library Assistant) Data provides database and template file access for MLA needs.
 *
 * The _template functions are inspired by the book "WordPress 3 Plugin Development Essentials."
 * Templates separate HTML markup from PHP code for easier maintenance and localization.
 *
 * @package Media Library Assistant
 * @since 0.1
 */
class MLAData {
	/**
	 * Initialization function, similar to __construct()
	 *
	 * @since 0.1
	 */
	public static function initialize() {
		/* Nothing to do at this point. */
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
	 * @param	string 	$filepath	Complete path and name of the template file
	 *
	 * @return	string|array|false|NULL
	 *  		string for files that do not contain template divider comments,
	 * 			array for files containing template divider comments,
	 *			false if file does not exist,
	 *			NULL if file could not be loaded.
	 */
	public static function mla_load_template( $filepath ) {
		if ( !file_exists( $filepath ) )
			return false;
		
		$template = file_get_contents( $filepath, true );
		if ( $template == false ) {
			error_log( 'ERROR: mla_load_template file not found ' . var_export( $filepath, true ), 0 );
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
	 * @param	string	$tpl	A formatting string containing [+placeholders+]
	 * @param	array	$hash	An associative array containing keys and values e.g. array('key' => 'value');
	 *
	 * @return	string	Placeholders corresponding to the keys of the hash will be replaced with their values.
	 */
	public static function mla_parse_template( $tpl, $hash ) {
		foreach ( $hash as $key => $value ) {
			if ( is_scalar( $value ) )
				$tpl = str_replace( '[+' . $key . '+]', $value, $tpl );
		}
		
		return $tpl;
	}
	
	/**
	 * Sanitize and expand query arguments from request variables
	 *
	 * Prepare the arguments for WP_Query.
	 * Modeled after wp_edit_attachments_query in wp-admin/post.php
	 * NOTE: The caller must remove the 'posts_where' filter, if required.
	 *
	 * @since 0.1
	 *
	 * @param	array	$request	query parameters from web page, usually found in $_REQUEST
	 *
	 * @return	array	revised arguments suitable for WP_Query
	 */
	function mla_prepare_list_table_query( $request ) {
		/*
		 * ['m'] - filter by year and month of post, e.g., 201204
		 */
		$request['m'] = isset( $request['m'] ) ? (int) $request['m'] : 0;
		
		/*
		 * ['att_cat'] - filter by attachment_category taxonomy
		 *
		 * cat = '0' is "All Categories", i.e., no filtering
		 * cat = '-1' is "No Categories"
		 */
		if ( isset( $request['att_cat'] ) && ( $request['att_cat'] != '0' ) ) {
			if ( $request['att_cat'] == '-1' ) {
				$term_list = get_terms( 'attachment_category', array(
					'fields' => 'ids',
					'hide_empty' => true 
				) );
				$request['tax_query'] = array(
					array(
						'taxonomy' => 'attachment_category',
						'field' => 'id',
						'terms' => $term_list,
						'operator' => 'NOT IN' 
					) 
				);
			} else {
				$request['tax_query'] = array(
					array(
						'taxonomy' => 'attachment_category',
						'field' => 'id',
						'terms' => array(
							(int) $request['att_cat'] 
						) 
					) 
				);
			}
		}
		
		if ( isset( $request['attachment_category'] ) ) {
			$request['tax_query'] = array(
				array(
					'taxonomy' => 'attachment_category',
					'field' => 'slug',
					'terms' => $request['attachment_category'],
					'include_children' => false 
				) 
			);
			
			unset( $request['attachment_category'] );
		}
		
		$request['post_type'] = 'attachment';
		$states = 'inherit';
		if ( current_user_can( 'read_private_posts' ) )
			$states .= ',private';
		
		$request['post_status'] = isset( $request['status'] ) && 'trash' == $request['status'] ? 'trash' : $states;
		
		/*
		 * The caller must remove this filter if it is added.
		 */
		if ( isset( $request['detached'] ) )
			add_filter( 'posts_where', 'MLAData::mla_query_list_table_items_helper' );
		
		return $request;
	}
	
	/**
	 * Retrieve attachment objects for list table display
	 *
	 * Supports prepare_items in class-mla-list-table.php.
	 * Modeled after wp_edit_attachments_query in wp-admin/post.php
	 *
	 * @since 0.1
	 *
	 * @param	array	$request	query parameters from web page, usually found in $_REQUEST
	 * @param	string	$orderby	database column to sort by
	 * @param	string	$order		ASC or DESC
	 * @param	int		$offset		number of rows to skip over to reach desired page
	 * @param	int		$count		number of rows on each page
	 *
	 * @return	array	attachment objects (posts) including parent data, meta data and references
	 */
	public static function mla_query_list_table_items( $request, $orderby, $order, $offset, $count ) {
		$request = self::mla_prepare_list_table_query( $request );
		
		/*
		 * There are two columns defined that end up sorting on post_title,
		 * so we can't use the database column to identify the column but
		 * we actually sort on the database column.
		 */
		if ( $orderby == 'title_name' )
			$orderby = 'post_title';
		
		$request['orderby'] = $orderby;
		$request['order'] = strtoupper( $order );
		
		if ( ( (int) $count ) > 0 ) {
			$request['offset'] = $offset;
			$request['posts_per_page'] = $count;
		}
		
		/*
		 * Ignore old paged value; use offset
		 */
		if (isset($request['paged']))
			unset($request['paged']);
		
		$results = new WP_Query( $request );
		
		if ( isset( $request['detached'] ) )
			remove_filter( 'posts_where', 'MLAData::mla_query_list_table_items_helper' );
		
		$attachments = $results->posts;
		
		foreach ( $attachments as $index => $attachment ) {
			/*
			 * Add parent data
			 */
			$parent_data = self::_fetch_attachment_parent_data( $attachment->post_parent );
			foreach ( $parent_data as $parent_key => $parent_value ) {
				$attachments[ $index ]->$parent_key = $parent_value;
			}
			
			/*
			 * Add meta data
			 */
			$meta_data = self::_fetch_attachment_metadata( $attachment->ID );
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
	 * Adds a WHERE clause for detached items
	 * 
	 * Modeled after _edit_attachments_query_helper in wp-admin/post.php
	 * Defined as public so callers can remove it after the query
	 *
	 * @since 0.1
	 *
	 * @param	string	$where	query clause before modification
	 *
	 * @return	string	query clause after "detached" item modification
	 */
	public static function mla_query_list_table_items_helper( $where ) {
		global $table_prefix;
		
		return $where .= " AND {$table_prefix}posts.post_parent < 1";
	}
	
	/** 
	 * Retrieve an Attachment array given a $post_id
	 *
	 * The (associative) array will contain every field that can be found in
	 * the posts and postmeta tables, and all references to the attachment.
	 * 
	 * @since 0.1
	 * 
	 * @param	int		$post_id The ID of the attachment post. 
	 * @return	NULL|array NULL on failure else associative array. 
	 */
	function mla_get_attachment_by_id( $post_id ) {
		global $wpdb, $post;
		
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
		$post_data = array_merge( $post_data, self::_fetch_attachment_parent_data( $post_data['post_parent'] ) );
		
		/*
		 * Add meta data
		 */
		$post_data = array_merge( $post_data, self::_fetch_attachment_metadata( $post_id ) );
		
		/*
		 * Add references
		 */
		$post_data['mla_references'] = self::mla_fetch_attachment_references( $post_id, $post_data['post_parent'] );
		
		return $post_data;
	}
	
	/**
	 * Find Featured Image and inserted image/link references to an attachment
	 * 
	 * Searches all post and page content to see if the attachment is used 
	 * as a Featured Image or inserted in the post as an image or link.
	 *
	 * @since 0.1
	 *
	 * @param	int		$ID		post ID of attachment
	 * @param	int		$parent	post ID of attachment's parent, if any
	 *
	 * @return	array	Reference information; see $references array comments
	 */
	public static function mla_fetch_attachment_references( $ID, $parent ) {
		global $wpdb;
		
		/*
		 * found_reference	true if either features or inserts is not empty()
		 * found_parent		true if $parent matches a features or inserts post ID
		 * is_unattached	true if $parent is zero (0)
		 * base_file		relative path and name of the uploaded file, e.g., 2012/04/image.jpg
		 * path				path to the file, relative to the "uploads/" directory
		 * files			base file and any other image size files. Array key is path and file name.
		 *					Non-image file value is a string containing file name without path
		 *					Image file value is an array with file name, width and height
		 * features			Array of objects with the post_type and post_title of each post
		 *					that has the attachment as a "Featured Image"
		 * inserts			Array of specific files (i.e., sizes) found in one or more posts/pages
		 *					as an image (<img>) or link (<a href>). The array key is the path and file name.
		 *					The array value is an array with the ID, post_type and post_title of each reference
		 * file				The name portion of the base file, e.g., image.jpg
		 * parent_type		'post' or 'page' or the custom post type of the attachment's parent
		 * parent_title		post_title of the attachment's parent
		 */
		$references = array(
			'found_reference' => false,
			'found_parent' => false,
			'is_unattached' => ( ( (int) $parent ) === 0 ),
			'base_file' => '',
			'path' => '',
			'files' => array(),
			'features' => array(),
			'inserts' => array(),
			'file' => '',
			'parent_type' => '',
			'parent_title' => '' 
		);
		
		$attachment_metadata = get_post_meta( $ID, '_wp_attachment_metadata', true );
		if ( empty( $attachment_metadata ) ) {
			$references['base_file'] = get_post_meta( $ID, '_wp_attached_file', true );
			$references['files'][ $references['base_file'] ] = $references['base_file'];
			$last_slash = strrpos( $references['base_file'], '/' );
			$references['path'] = substr( $references['base_file'], 0, $last_slash + 1 );
			$references['file'] = substr( $references['base_file'], $last_slash + 1 );
		} else {
			$references['base_file'] = $attachment_metadata['file'];
			$references['files'][ $references['base_file'] ] = $references['base_file'];
			$last_slash = strrpos( $references['base_file'], '/' );
			$references['path'] = substr( $references['base_file'], 0, $last_slash + 1 );
			$references['file'] = substr( $references['base_file'], $last_slash + 1 );
			$sizes = $attachment_metadata['sizes'];
			if ( !empty( $sizes ) ) {
				/* Using the name as the array key ensures each name is added only once */
				foreach ( $sizes as $size ) {
					$references['files'][ $references['path'] . $size['file'] ] = $size;
				}
				
			}
		}
		
		/*
		 * Look for the "Featured Image(s)"
		 */
		$features = $wpdb->get_results( "
		SELECT post_id
		FROM $wpdb->postmeta
		WHERE meta_key = '_thumbnail_id' 
		AND meta_value = $ID
		" );
		
		if ( !empty( $features ) ) {
			$references['found_reference'] = true;
			
			foreach ( $features as $feature ) {
				$feature_results = $wpdb->get_results( "
					SELECT post_type, post_title
					FROM $wpdb->posts
					WHERE ID = $feature->post_id 
					" );
				$references['features'][ $feature->post_id ] = $feature_results[0];
				
				if ( $feature->post_id == $parent ) {
					$references['found_parent'] = true;
					$references['parent_type'] = $feature_results[0]->post_type;
					$references['parent_title'] = $feature_results[0]->post_title;
				}
			} // foreach $feature
		}
		
		/*
		 * Look for item(s) inserted in post_content
		 */
		foreach ( $references['files'] as $file => $file_data ) {
			$inserts = $wpdb->get_results( "
			SELECT ID, post_type, post_title 
			FROM $wpdb->posts
			WHERE (
				CONVERT(`post_content` USING utf8 )
				LIKE '%$file%'
			)
			" );
			
			if ( !empty( $inserts ) ) {
				$references['found_reference'] = true;
				$references['inserts'][ $file ] = $inserts;
				foreach ( $inserts as $insert ) {
					if ( $insert->ID == $parent ) {
						$references['found_parent'] = true;
						$references['parent_type'] = $insert->post_type;
						$references['parent_title'] = $insert->post_title;
					}
				} // foreach $insert
			}
		} // foreach $file
		
		return $references;
	}
	
	/**
	 * Returns information about an attachment's parent, if found
	 *
	 * @since 0.1
	 *
	 * @param	int		$parent_id	post ID of attachment's parent, if any
	 *
	 * @return	array	Parent information; post_date, post_title and post_type
	 */
	private static function _fetch_attachment_parent_data( $parent_id ) {
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
	 * @param	int		$post_id	post ID of attachment
	 *
	 * @return	array	Meta data variables
	 */
	private static function _fetch_attachment_metadata( $post_id ) {
		$results = array();
		$post_meta = get_metadata( 'post', $post_id );
		
		if ( is_array( $post_meta ) ) {
			foreach ( $post_meta as $post_meta_key => $post_meta_value ) {
				if ( '_' == $post_meta_key{0} ) {
					if ( stripos( $post_meta_key, '_wp_attached_file' ) === 0 ) {
						$key = 'mla_wp_attached_file';
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
			}
			/* foreach $post_meta */
		}
		/* is_array($post_meta) */
		
		return $results;
	}
} // class MLAData
?>