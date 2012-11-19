<?php
/**
 * Media Library Assistant Shortcode handler(s)
 *
 * @package Media Library Assistant
 * @since 0.1
 */

/**
 * Class MLA (Media Library Assistant) Shortcodes defines the shortcodes available to MLA users
 *
 * @package Media Library Assistant
 * @since 0.20
 */
class MLAShortcodes {
	/**
	 * Initialization function, similar to __construct()
	 *
	 * @since 0.20
	 *
	 * @return	void
	 */
	public static function initialize() {
		add_shortcode( 'mla_attachment_list', 'MLAShortcodes::mla_attachment_list_shortcode' );
		add_shortcode( 'mla_gallery', 'MLAShortcodes::mla_gallery_shortcode' );
	}

	/**
	 * WordPress Shortcode; renders a complete list of all attachments and references to them
	 *
	 * @since 0.1
	 *
	 * @return	void	echoes HTML markup for the attachment list
	 */
	public static function mla_attachment_list_shortcode( /* $atts */ ) {
		global $wpdb;
		
		/*	extract(shortcode_atts(array(
		'item_type'=>'attachment',
		'organize_by'=>'title',
		), $atts)); */
		
		/*
		 * Process the where-used settings option
		 */
		if ('checked' == MLASettings::mla_get_option( 'exclude_revisions' ) )
			$exclude_revisions = "(post_type <> 'revision') AND ";
		else
			$exclude_revisions = '';
				
		$attachments = $wpdb->get_results(
				"
				SELECT ID, post_title, post_name, post_parent
				FROM {$wpdb->posts}
				WHERE {$exclude_revisions}post_type = 'attachment' 
				"
		);
		
		foreach ( $attachments as $attachment ) {
			$references = MLAData::mla_fetch_attachment_references( $attachment->ID, $attachment->post_parent );
			
			echo '&nbsp;<br><h3>' . $attachment->ID . ', ' . esc_attr( $attachment->post_title ) . ', Parent: ' . $attachment->post_parent . '<br>' . esc_attr( $attachment->post_name ) . '<br>' . esc_html( $references['base_file'] ) . "</h3>\r\n";
			
			/*
			 * Look for the "Featured Image(s)"
			 */
			if ( empty( $references['features'] ) ) {
				echo "&nbsp;&nbsp;&nbsp;&nbsp;not featured in any posts.<br>\r\n";
			} else {
				echo "&nbsp;&nbsp;&nbsp;&nbsp;Featured in<br>\r\n";
				foreach ( $references['features'] as $feature_id => $feature ) {
					echo '&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;';
					
					if ( $feature_id == $attachment->post_parent ) {
						echo 'PARENT ';
						$found_parent = true;
					}
					
					echo $feature_id . ' (' . $feature->post_type . '), ' . esc_attr( $feature->post_title ) . "<br>\r\n";
				}
			}
			
			/*
			 * Look for item(s) inserted in post_content
			 */
			if ( empty( $references['inserts'] ) ) {
				echo "&nbsp;&nbsp;&nbsp;&nbsp;no inserts in any post_content.<br>\r\n";
			} else {
				foreach ( $references['inserts'] as $file => $inserts ) {
					echo '&nbsp;&nbsp;&nbsp;&nbsp;' . $file . " inserted in<br>\r\n";
					foreach ( $inserts as $insert ) {
						echo '&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;';
						
						if ( $insert->ID == $attachment->post_parent ) {
							echo 'PARENT ';
							$found_parent = true;
						}
						
						echo $insert->ID . ' (' . $insert->post_type . '), ' . esc_attr( $insert->post_title ) . "<br>\r\n";
					} // foreach $insert
				} // foreach $file
			}
			
			$errors = '';
			
			if ( !$references['found_reference'] )
				$errors .= '(ORPHAN) ';
			
			if ( $references['is_unattached'] )
				$errors .= '(UNATTACHED) ';
			else {
				if ( !$references['found_parent'] ) {
					if ( isset( $references['parent_title'] ) )
						$errors .= '(BAD PARENT) ';
					else
						$errors .= '(INVALID PARENT) ';
				}
			}
			
			if ( !empty( $errors ) )
				echo '&nbsp;&nbsp;&nbsp;&nbsp;' . $errors . "<br>\r\n";
		} // foreach attachment
		
		echo "<br>----- End of Report -----\r\n";
	}
	
	/**
	 * Accumulates debug messages
	 *
	 * @since 0.60
	 *
	 * @var	string
	 */
	private static $mla_debug_messages = '';
	
	/**
	 * Turn debug collection and display on or off
	 *
	 * @since 0.70
	 *
	 * @var	boolean
	 */
	private static $mla_debug = false;
	
	/**
	 * The MLA Gallery shortcode.
	 *
	 * This is a superset of the WordPress Gallery shortcode for displaying images on a post,
	 * page or custom post type. It is adapted from /wp-includes/media.php gallery_shortcode.
	 * Enhancements include many additional selection parameters and full taxonomy support.
	 *
	 * @since .50
	 *
	 * @param array $attr Attributes of the shortcode.
	 *
	 * @return string HTML content to display gallery.
	 */
	public static function mla_gallery_shortcode($attr) {
		global $post;

		/*
		 * These are the parameters for gallery display
		 */
		$default_arguments = array(
			'size' => 'thumbnail', // or 'medium', 'large', 'full' or registered size
			'itemtag' => 'dl',
			'icontag' => 'dt',
			'captiontag' => 'dd',
			'columns' => 3,
			'link' => 'permalink', // or 'file' or a registered size
			// MLA-specific
			'mla_style' => 'default',
			'mla_markup' => 'default',
			'mla_link_text' => NULL,
			'mla_rollover_text' => NULL,
			'mla_caption' => NULL,
			'mla_debug' => false
		);
		
		/*
		 * Merge gallery arguments with defaults, pass the query arguments on to mla_get_shortcode_attachments.
		 */
		 
		$arguments = shortcode_atts( $default_arguments, $attr );
		self::$mla_debug = !empty( $arguments['mla_debug'] ) && ( 'true' == strtolower( $arguments['mla_debug'] ) );

		$attachments = self::mla_get_shortcode_attachments( $post->ID, $attr );
		if ( empty($attachments) ) {
			if ( self::$mla_debug ) {
				$output = '<p><strong>mla_debug</strong> empty gallery, query = ' . var_export( $attr, true ) . '</p>';
				$output .= self::$mla_debug_messages;
				self::$mla_debug_messages = '';
				return $output;
			}
			else {
				return '';
			}
		} // empty $attachments
	
		$size = $size_class = $arguments['size'];
		if ( 'icon'	 == strtolower( $size) ) {
			$size = array( 60, 60 );
			$show_icon = true;
		}
		else
			$show_icon = false;
			
		if ( is_feed() ) {
			$output = "\n";
			foreach ( $attachments as $att_id => $attachment )
				$output .= wp_get_attachment_link($att_id, $size, true) . "\n";
			return $output;
		}

		// $instance supports multiple galleries in one page/post	
		static $instance = 0;
		$instance++;
		
		$style_values = array(
			'mla_style' => $arguments['mla_style'],
			'instance' => $instance,
			'id' => $post->ID,
			'itemtag' => tag_escape( $arguments['itemtag'] ),
			'icontag' => tag_escape( $arguments['icontag'] ),
			'captiontag' => tag_escape( $arguments['captiontag'] ),
			'columns' => intval( $arguments['columns']),
			'itemwidth' => $arguments['columns'] > 0 ? floor(100/$arguments['columns']) : 100,
			'float' => is_rtl() ? 'right' : 'left',
			'selector' => "mla_gallery-{$instance}",
			'size_class' => sanitize_html_class( $size_class )
		);
	
		$style_template = $gallery_style = '';
		if ( apply_filters( 'use_mla_gallery_style', true ) ) {
			$style_template = MLASettings::mla_fetch_gallery_template( $style_values['mla_style'], 'style' );
			if ( empty( $style_template ) ) {
				$style_values['mla_style'] = 'default';
				$style_template = MLASettings::mla_fetch_gallery_template( 'default', 'style' );
			}
				
			if ( ! empty ( $style_template ) ) {
				$gallery_style = MLAData::mla_parse_template( $style_template, $style_values );
			} // !empty template
		} // use_mla_gallery_style
		
		$upload_dir = wp_upload_dir();
		$markup_values = array(
			'mla_markup' => $arguments['mla_markup'],
			'instance' => $instance,
			'id' => $post->ID,
			'itemtag' => tag_escape( $arguments['itemtag'] ),
			'icontag' => tag_escape( $arguments['icontag'] ),
			'captiontag' => tag_escape( $arguments['captiontag'] ),
			'columns' => intval( $arguments['columns']),
			'itemwidth' => $arguments['columns'] > 0 ? floor(100/$arguments['columns']) : 100,
			'float' => is_rtl() ? 'right' : 'left',
			'selector' => "mla_gallery-{$instance}",
			'size_class' => sanitize_html_class( $size_class ),
			'base_url' => $upload_dir['baseurl'],
			'base_dir' => $upload_dir['basedir']
		);

		$markup_template = MLASettings::mla_fetch_gallery_template( $markup_values['mla_markup'] . '-open', 'markup' );
		if ( empty( $markup_template ) ) {
			$markup_values['mla_markup'] = 'default';
			$markup_template = MLASettings::mla_fetch_gallery_template( 'default-open', 'markup' );
		}
			
		if ( ! empty( $markup_template ) ) {
			$gallery_div = MLAData::mla_parse_template( $markup_template, $markup_values );

			$row_open_template = MLASettings::mla_fetch_gallery_template( $markup_values['mla_markup'] . '-row-open', 'markup' );
			if ( empty( $row_open_template ) )
				$row_open_template = '';
				
			$item_template = MLASettings::mla_fetch_gallery_template( $markup_values['mla_markup'] . '-item', 'markup' );
			if ( empty( $item_template ) )
				$item_template = '';
				
			$row_close_template = MLASettings::mla_fetch_gallery_template( $markup_values['mla_markup'] . '-row-close', 'markup' );
			if ( empty( $row_close_template ) )
				$row_close_template = '';
				
			$close_template = MLASettings::mla_fetch_gallery_template( $markup_values['mla_markup'] . '-close', 'markup' );
			if ( empty( $close_template ) )
				$close_template = '';
		}
		
		if ( self::$mla_debug ) {
			$output = self::$mla_debug_messages;
			self::$mla_debug_messages = '';
		}
		else
			$output = '';

		$output .= apply_filters( 'mla_gallery_style', $gallery_style .  $gallery_div, $style_values, $markup_values, $style_template, $markup_template );
	
		$i = 0;
		foreach ( $attachments as $id => $attachment ) {
			/*
			 * fill in item-specific elements
			 */
			$markup_values['index'] = (string) 1 + $i;

			$markup_values['excerpt'] = wptexturize( $attachment->post_excerpt );
			$markup_values['attachment_ID'] = $attachment->ID;
			$markup_values['mime_type'] = $attachment->post_mime_type;
			$markup_values['menu_order'] = $attachment->menu_order;
			$markup_values['date'] = $attachment->post_date;
			$markup_values['modified'] = $attachment->post_modified;
			$markup_values['parent'] = $attachment->post_parent;
			$markup_values['parent_title'] = '(unattached)';
			$markup_values['parent_type'] = '';
			$markup_values['parent_date'] = '';
			$markup_values['title'] = wptexturize( $attachment->post_title );
			$markup_values['slug'] = wptexturize( $attachment->post_name );
			$markup_values['width'] = '';
			$markup_values['height'] = '';
			$markup_values['image_meta'] = '';
			$markup_values['image_alt'] = '';
			$markup_values['base_file'] = '';
			$markup_values['path'] = '';
			$markup_values['file'] = '';
			$markup_values['description'] = wptexturize( $attachment->post_content );
			$markup_values['file_url'] = wptexturize( $attachment->guid );
			$markup_values['author_id'] = $attachment->post_author;
		
			$user = get_user_by( 'id', $attachment->post_author );
			if ( isset( $user->data->display_name ) )
				$markup_values['author'] = wptexturize( $user->data->display_name );
			else
				$markup_values['author'] = 'unknown';

			$post_meta = MLAData::mla_fetch_attachment_metadata( $attachment->ID );
			if ( !empty( $post_meta['mla_wp_attachment_metadata'] ) ) {
				$base_file = $post_meta['mla_wp_attachment_metadata']['file'];
				$sizes = $post_meta['mla_wp_attachment_metadata']['sizes'];
				$markup_values['width'] = $post_meta['mla_wp_attachment_metadata']['width'];
				$markup_values['height'] = $post_meta['mla_wp_attachment_metadata']['height'];
				$markup_values['image_meta'] = wptexturize( var_export( $post_meta['mla_wp_attachment_metadata']['image_meta'], true ) );
			}
			else {
				$base_file = $post_meta['mla_wp_attached_file'];
				$sizes = array( );
			}

			if ( isset( $post_meta['mla_wp_attachment_image_alt'] ) )
				$markup_values['image_alt'] = wptexturize( $post_meta['mla_wp_attachment_image_alt'] );

			if ( ! empty( $base_file ) ) {
				$last_slash = strrpos( $base_file, '/' );
				if ( false === $last_slash ) {
					$file_name = $base_file;
					$markup_values['base_file'] = wptexturize( $base_file );
					$markup_values['file'] = wptexturize( $base_file );
				}
				else {
					$file_name = substr( $base_file, $last_slash + 1 );
					$markup_values['base_file'] = wptexturize( $base_file );
					$markup_values['path'] = wptexturize( substr( $base_file, 0, $last_slash + 1 ) );
					$markup_values['file'] = wptexturize( $file_name );
				}
			}
			else
				$file_name = '';

			$parent_info = MLAData::mla_fetch_attachment_parent_data( $attachment->post_parent );
			if ( isset( $parent_info['parent_title'] ) )
				$markup_values['parent_title'] = wptexturize( $parent_info['parent_title'] );
				
			if ( isset( $parent_info['parent_date'] ) )
				$markup_values['parent_date'] = wptexturize( $parent_info['parent_date'] );
				
			if ( isset( $parent_info['parent_type'] ) )
				$markup_values['parent_type'] = wptexturize( $parent_info['parent_type'] );
				
			unset(
				$markup_values['caption'],
				$markup_values['pagelink'],
				$markup_values['filelink'],
				$markup_values['link'],
				$markup_values['pagelink_url'],
				$markup_values['filelink_url'],
				$markup_values['link_url'],
				$markup_values['thumbnail_content'],
				$markup_values['thumbnail_width'],
				$markup_values['thumbnail_height'],
				$markup_values['thumbnail_url']
			);
			
			if ( $markup_values['captiontag'] ) {
				if ( ! empty( $arguments['mla_caption'] ) ) {
					$new_text = str_replace( '{+', '[+', str_replace( '+}', '+]', $arguments['mla_caption'] ) );
					$markup_values['caption'] = wptexturize( MLAData::mla_parse_template( $new_text, $markup_values ) );
				}
				else
					$markup_values['caption'] = wptexturize( $attachment->post_excerpt );
			}
			else
				$markup_values['caption'] = '';
			
			if ( ! empty( $arguments['mla_link_text'] ) ) {
				$link_text = str_replace( '{+', '[+', str_replace( '+}', '+]', $arguments['mla_link_text'] ) );
				$link_text = MLAData::mla_parse_template( $link_text, $markup_values );
			}
			else
				$link_text = false;

			$markup_values['pagelink'] = wp_get_attachment_link($attachment->ID, $size, true, $show_icon, $link_text);
			$markup_values['filelink'] = wp_get_attachment_link($attachment->ID, $size, false, $show_icon, $link_text);
			
			if ( ! empty( $arguments['mla_rollover_text'] ) ) {
				$new_text = str_replace( '{+', '[+', str_replace( '+}', '+]', $arguments['mla_rollover_text'] ) );
				$new_text = MLAData::mla_parse_template( $new_text, $markup_values );
				
				/*
				 * Replace single- and double-quote delimited values
				 */
				$markup_values['pagelink'] = preg_replace('# title=\'([^\']*)\'#', " title='{$new_text}'", $markup_values['pagelink'] );
				$markup_values['pagelink'] = preg_replace('# title=\"([^\"]*)\"#', " title=\"{$new_text}\"", $markup_values['pagelink'] );
				$markup_values['filelink'] = preg_replace('# title=\'([^\']*)\'#', " title='{$new_text}'", $markup_values['filelink'] );
				$markup_values['filelink'] = preg_replace('# title=\"([^\"]*)\"#', " title=\"{$new_text}\"", $markup_values['filelink'] );
			}
			
			switch ( $arguments['link'] ) {
				case 'permalink':
					$markup_values['link'] = $markup_values['pagelink'];
					break;
				case 'file':
				case 'full':
					$markup_values['link'] = $markup_values['filelink'];
					break;
				default:
					$markup_values['link'] = $markup_values['filelink'];

					/*
					 * Check for link to specific (registered) file size
					 */
					if ( array_key_exists( $arguments['link'], $sizes ) ) {
						$target_file = $sizes[ $arguments['link'] ]['file'];
						$markup_values['link'] = str_replace( $file_name, $target_file, $markup_values['filelink'] );
					}
			} // switch 'link'
			
			/*
			 * Extract target and thumbnail fields
			 */
			$match_count = preg_match_all( '#href=\'([^\']+)\' title=\'([^\']*)\'#', $markup_values['pagelink'], $matches, PREG_OFFSET_CAPTURE );
 			if ( ! ( ( $match_count == false ) || ( $match_count == 0 ) ) ) {
				$markup_values['pagelink_url'] = $matches[1][0][0];
			}
			else
				$markup_values['pagelink_url'] = '';

			$match_count = preg_match_all( '#href=\'([^\']+)\'#', $markup_values['filelink'], $matches, PREG_OFFSET_CAPTURE );
			if ( ! ( ( $match_count == false ) || ( $match_count == 0 ) ) ) {
				$markup_values['filelink_url'] = $matches[1][0][0];
			}
			else
				$markup_values['filelink_url'] = '';

			$match_count = preg_match_all( '#href=\'([^\']+)\'#', $markup_values['link'], $matches, PREG_OFFSET_CAPTURE );
			if ( ! ( ( $match_count == false ) || ( $match_count == 0 ) ) ) {
				$markup_values['link_url'] = $matches[1][0][0];
			}
			else
				$markup_values['link_url'] = '';

			$match_count = preg_match_all( '#\<a [^\>]+\>(.*)\</a\>#', $markup_values['link'], $matches, PREG_OFFSET_CAPTURE );
			if ( ! ( ( $match_count == false ) || ( $match_count == 0 ) ) ) {
				$markup_values['thumbnail_content'] = $matches[1][0][0];
			}
			else
				$markup_values['thumbnail_content'] = '';

			$match_count = preg_match_all( '#img width=\"([^\"]+)\" height=\"([^\"]+)\" src=\"([^\"]+)\"#', $markup_values['link'], $matches, PREG_OFFSET_CAPTURE );
			if ( ! ( ( $match_count == false ) || ( $match_count == 0 ) ) ) {
				$markup_values['thumbnail_width'] = $matches[1][0][0];
				$markup_values['thumbnail_height'] = $matches[2][0][0];
				$markup_values['thumbnail_url'] = $matches[3][0][0];
			}
			else {
				$markup_values['thumbnail_width'] = '';
				$markup_values['thumbnail_height'] = '';
				$markup_values['thumbnail_url'] = '';
			}

			/*
			 * Start of row markup
			 */
			if ( $markup_values['columns'] > 0 && $i % $markup_values['columns'] == 0 )
				$output .= MLAData::mla_parse_template( $row_open_template, $markup_values );
			
			/*
			 * item markup
			 */
			$output .= MLAData::mla_parse_template( $item_template, $markup_values );

			/*
			 * End of row markup
			 */
			if ( $markup_values['columns'] > 0 && ++$i % $markup_values['columns'] == 0 )
				$output .= MLAData::mla_parse_template( $row_close_template, $markup_values );
		}
	
		/*
		 * Close out partial row
		 */
		if ( ! ($markup_values['columns'] > 0 && $i % $markup_values['columns'] == 0 ) )
			$output .= MLAData::mla_parse_template( $row_close_template, $markup_values );
			
		$output .= MLAData::mla_parse_template( $close_template, $markup_values );
	
		return $output;
	}
	
	/**
	 * Parses shortcode parameters and returns the gallery objects
	 *
	 * @since .50
	 *
	 * @param int Post ID of the parent
	 * @param array Attributes of the shortcode
	 *
	 * @return array List of attachments returned from WP_Query
	 */
	public static function mla_get_shortcode_attachments( $post_parent, $attr ) {
		/*
		 * These are the parameters for the query
		 */
		$default_arguments = array(
			'order' => 'ASC', // or 'DESC' or 'RAND'
			'orderby' => 'menu_order ID',
			'id' => NULL,
			'ids' => array ( ),
			'include' => array ( ),
			'exclude' => array ( ),
			// MLA extensions, from WP_Query
			// Force 'get_children' style query
			'post_parent' => NULL, // post/page ID or 'current' or 'all'
			// Author
			'author' => NULL,
			'author_name' => '',
			// Category
			'cat' => 0,
			'category_name' => '',
			'category__and' => array ( ),
			'category__in' => array ( ),
			'category__not_in' => array ( ),
			// Tag
			'tag' => '',
			'tag_id' => 0,
			'tag__and' => array ( ),
			'tag__in' => array ( ),
			'tag__not_in' => array ( ),
			'tag_slug__and' => array ( ),
			'tag_slug__in' => array ( ),
			// Taxonomy parameters are handled separately
			// {tax_slug} => 'term' | array ( 'term, 'term, ... )
			// 'tax_query' => ''
			// Post 
			'post_type' => 'attachment',
			'post_status' => 'inherit',
			'post_mime_type' => 'image',
			// Pagination - no default for most of these
			'nopaging' => true,
			'posts_per_page' => 0,
			'posts_per_archive_page' => 0,
			'paged' => NULL, // page number or 'current'
			'offset' => NULL,
			// TBD Time
			// Custom Field
			'meta_key' => '',
			'meta_value' => '',
			'meta_value_num' => NULL,
			'meta_compare' => '',
			'meta_query' => '',
			// Search
			's' => ''
		);
		
		/*
		 * Merge input arguments with defaults, then extract the query arguments.
		 */
		 
		if ( is_string( $attr ) )
			$attr = shortcode_parse_atts( $attr );
			
		$arguments = shortcode_atts( $default_arguments, $attr );

		if ( 'RAND' == $arguments['order'] )
			$arguments['orderby'] = 'none';
	
		if ( !empty( $arguments['ids'] ) ) {
			// 'ids' is explicitly ordered
			$arguments['orderby'] = 'post__in';
			$arguments['include'] = $arguments['ids'];
		}
		unset( $arguments['ids'] );
	
		/*
		 * Extract taxonomy arguments
		 */
		$taxonomies = get_taxonomies( array ( 'show_ui' => 'true' ), 'names' ); // 'objects'
		$query_arguments = array ( );
		if ( ! empty( $attr ) ) {
			foreach ( $attr as $key => $value ) {
				if ( 'tax_query' == $key ) {
					if ( is_array( $value ) )
						$query_arguments[ $key ] = $value;
					else {
						$function = @create_function('', 'return ' . $value . ';' );

						if ( is_callable( $function ) )
							$query_arguments[ $key ] = $function();
						else
							return '<p>ERROR: invalid mla_gallery tax_query = ' . var_export( $value, true ) . '</p>';
					} // not array
				}  // tax_query
				elseif ( array_key_exists( $key, $taxonomies ) ) {
					$query_arguments[ $key ] = implode(',', array_filter( array_map( 'trim', explode( ",", $value ) ) ) );
				} // array_key_exists
			} //foreach $attr
		} // ! empty
		
		// We're trusting author input, but let's at least make sure it looks like a valid orderby statement
		if ( isset( $arguments['orderby'] ) ) {
			$arguments['orderby'] = sanitize_sql_orderby( $arguments['orderby'] );
			if ( ! $arguments['orderby'] )
				unset( $arguments['orderby'] );
		}
	
		/*
		 * $query_arguments has been initialized in the taxonomy code above.
		 */
		$use_children = empty( $query_arguments );
		foreach ($arguments as $key => $value ) {
			/*
			 * There are several "fallthru" cases in this switch statement that decide 
			 * whether or not to limit the query to children of a specific post.
			 */
			$children_ok = true;
			switch ( $key ) {
			case 'post_parent':
				if ( 'current' == strtolower( $value ) )
					$value = $post_parent;
				elseif ( 'all' == strtolower( $value ) ) {
					$value = NULL;
					$use_children = false;
				}
				// fallthru
			case 'id':
			case 'posts_per_page':
			case 'posts_per_archive_page':
				if ( is_numeric( $value ) ) {
					$value =  intval( $value );
					if ( ! empty( $value ) ) {
						$query_arguments[ $key ] = $value;
						if ( ! $children_ok )
							$use_children = false;
					}
				}
				unset( $arguments[ $key ] );
				break;
			case 'meta_value_num':
				$children_ok = false;
				// fallthru
			case 'offset':
				if ( is_numeric( $value ) ) {
					$query_arguments[ $key ] = intval( $value );
					if ( ! $children_ok )
						$use_children = false;
				}
				unset( $arguments[ $key ] );
				break;
			case 'paged':
				if ( 'current' == strtolower( $value ) )
					$query_arguments[ $key ] = (get_query_var('paged')) ? get_query_var('paged') : 1;
				elseif ( is_numeric( $value ) )
					$query_arguments[ $key ] = intval( $value );
				unset( $arguments[ $key ] );
				break;
			case 'author':
			case 'cat':
			case 'tag_id':
				if ( ! empty( $value ) ) {
					if ( is_array( $value ) )
						$query_arguments[ $key ] = array_filter( $value );
					else
						$query_arguments[ $key ] = array_filter( array_map( 'intval', explode( ",", $value ) ) );
						
					if ( 1 == count( $query_arguments[ $key ] ) )
						$query_arguments[ $key ] = $query_arguments[ $key ][0];
					else
						$query_arguments[ $key ] = implode(',', $query_arguments[ $key ] );

					$use_children = false;
				}
				unset( $arguments[ $key ] );
				break;
			case 'category__and':
			case 'category__in':
			case 'category__not_in':
			case 'tag__and':
			case 'tag__in':
			case 'tag__not_in':
				$children_ok = false;
				// fallthru
			case 'include':
			case 'exclude':
				if ( ! empty( $value ) ) {
					if ( is_array( $value ) )
						$query_arguments[ $key ] = array_filter( $value );
					else
						$query_arguments[ $key ] = array_filter( array_map( 'intval', explode( ",", $value ) ) );
						
					if ( ! $children_ok )
						$use_children = false;
				}
				unset( $arguments[ $key ] );
				break;
			case 'tag_slug__and':
			case 'tag_slug__in':
				if ( ! empty( $value ) ) {
					if ( is_array( $value ) )
						$query_arguments[ $key ] = $value;
					else
						$query_arguments[ $key ] = array_filter( array_map( 'trim', explode( ",", $value ) ) );

					$use_children = false;
				}
				unset( $arguments[ $key ] );
				break;
			case 'nopaging': // boolean
				if ( ! empty( $value ) && ( 'false' != strtolower( $value ) ) )
					$query_arguments[ $key ] = true;
				unset( $arguments[ $key ] );
				break;
			case 'author_name':
			case 'category_name':
			case 'tag':
			case 'meta_key':
			case 'meta_value':
			case 'meta_compare':
			case 's':
				$children_ok = false;
				// fallthru
			case 'post_type':
			case 'post_status':
			case 'post_mime_type':
			case 'order':
			case 'orderby':
				if ( ! empty( $value ) ) {
					$query_arguments[ $key ] = $value;
					
					if ( ! $children_ok )
						$use_children = false;
				}
				unset( $arguments[ $key ] );
				break;
			case 'meta_query':
				if ( ! empty( $value ) ) {
					if ( is_array( $value ) )
						$query_arguments[ $key ] = $value;
					else {
						$function = @create_function('', 'return ' . $value . ';' );

						if ( is_callable( $function ) )
							$query_arguments[ $key ] = $function();
						else
							return '<p>ERROR: invalid mla_gallery meta_query = ' . var_export( $value, true ) . '</p>';
					} // not array

					$use_children = false;
				}
				unset( $arguments[ $key ] );
				break;
			default:
				// ignore anything else
			} // switch $key
		} // foreach $arguments 

		/*
		 * Decide whether to use a "get_children" style query
		 */
		if ( $use_children && empty( $query_arguments['post_parent'] ) ) {
			if ( empty( $query_arguments['id'] ) )
				$query_arguments['post_parent'] = $post_parent;
			else				
				$query_arguments['post_parent'] = $query_arguments['id'];

			unset( $query_arguments['id'] );
		}

		if ( isset( $query_arguments['posts_per_page'] ) || isset( $query_arguments['posts_per_archive_page'] ) ||
			isset( $query_arguments['paged'] ) || isset( $query_arguments['offset'] ) ) {
			unset ( $query_arguments['nopaging'] );
		}

		if ( isset( $query_arguments['orderby'] ) && ('rand' == $query_arguments['orderby'] ) )
			unset ( $query_arguments['order'] );

		if ( isset( $query_arguments['order'] ) && ('rand' == $query_arguments['order'] ) )
			unset ( $query_arguments['orderby'] );

		if ( isset( $query_arguments['post_mime_type'] ) && ('all' == strtolower( $query_arguments['post_mime_type'] ) ) )
			unset ( $query_arguments['post_mime_type'] );

		if ( ! empty($query_arguments['include']) ) {
			$incposts = wp_parse_id_list( $query_arguments['include'] );
			$query_arguments['posts_per_page'] = count($incposts);  // only the number of posts included
			$query_arguments['post__in'] = $incposts;
		} elseif ( ! empty($query_arguments['exclude']) )
			$query_arguments['post__not_in'] = wp_parse_id_list( $query_arguments['exclude'] );
	
		$query_arguments['ignore_sticky_posts'] = true;
		$query_arguments['no_found_rows'] = true;
	
		add_filter( 'posts_where', 'MLAShortcodes::mla_shortcode_query_posts_where_filter' );
		$get_posts = new WP_Query;
		$attachments = $get_posts->query($query_arguments);
		remove_filter( 'posts_where', 'MLAShortcodes::mla_shortcode_query_posts_where_filter' );
		
		if ( self::$mla_debug ) {
			self::$mla_debug_messages .= '<p><strong>mla_debug</strong> query = ' . var_export( $query_arguments, true ) . '</p>';
			self::$mla_debug_messages .= '<p><strong>mla_debug</strong> request = ' . var_export( $get_posts->request, true ) . '</p>';
			self::$mla_debug_messages .= '<p><strong>mla_debug</strong> query_vars = ' . var_export( $get_posts->query_vars, true ) . '</p>';
		}
		
		return $attachments;
	
	}

	/**
	 * Filters the WHERE clause for shortcode queries
	 * 
	 * Captures debug information. Adds whitespace to the post_type = 'attachment'
	 * phrase to circumvent subsequent Role Scoper modification of the clause.
	 * Defined as public because it's a filter.
	 *
	 * @since 0.70
	 *
	 * @param	string	query clause before modification
	 *
	 * @return	string	query clause after modification
	 */
	public static function mla_shortcode_query_posts_where_filter( $where_clause ) {
		global $table_prefix;

		if ( self::$mla_debug ) {
			self::$mla_debug_messages .= '<p><strong>mla_debug</strong> WHERE filter = ' . var_export( $where_clause, true ) . '</p>';
		}
		
		if ( strpos( $where_clause, "post_type = 'attachment'" ) ) {
			$where_clause = str_replace( "post_type = 'attachment'", "post_type  =  'attachment'", $where_clause );
			
			if ( self::$mla_debug ) 
				self::$mla_debug_messages .= '<p><strong>mla_debug</strong> modified WHERE filter = ' . var_export( $where_clause, true ) . '</p>';
		}

		return $where_clause;
	}
} // Class MLAShortcodes
?>