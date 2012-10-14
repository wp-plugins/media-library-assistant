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
			$wpdb->prepare(
				"
				SELECT ID, post_title, post_name, post_parent
				FROM {$wpdb->posts}
				WHERE {$exclude_revisions}post_type = 'attachment' 
				"
			)
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
				if ( !$references['found_parent'] )
					$errors .= '(BAD PARENT) ';
			}
			
			if ( !empty( $errors ) )
				echo '&nbsp;&nbsp;&nbsp;&nbsp;' . $errors . "<br>\r\n";
		} // foreach attachment
		
		echo "<br>----- End of Report -----\r\n";
	}
	
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

		static $instance = 0;
		$instance++;
	
		$default_arguments = array(
			'order' => 'ASC', // or 'DESC' or 'RAND'
			'orderby' => 'menu_order ID',
			'id' => NULL,
			'itemtag' => 'dl',
			'icontag' => 'dt',
			'captiontag' => 'dd',
			'link' => 'permalink', // or 'file'
			'columns' => 3,
			'size' => 'thumbnail',
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
		 * $query_arguments has been initialized in the taxonomy code above.
		 */
		 
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
			$attr['orderby'] = sanitize_sql_orderby( $arguments['orderby'] );
			if ( ! $arguments['orderby'] )
				unset( $arguments['orderby'] );
		}
	
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
					$value = $post->ID;
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
				$query_arguments['post_parent'] = $post->ID;
			else				
				$query_arguments['post_parent'] = $query_arguments['id'];

			unset( $query_arguments['id'] );
		}

		if ( isset( $query_arguments['posts_per_page'] ) || isset( $query_arguments['posts_per_archive_page'] ) ||
			isset( $query_arguments['paged'] ) || isset( $query_arguments['offset'] ) ) {
			unset ($query_arguments['nopaging'] );
		}

		if ( isset( $query_arguments['orderby'] ) && ('rand' == $query_arguments['orderby'] ) )
			unset ($query_arguments['order'] );

		if ( isset( $query_arguments['order'] ) && ('rand' == $query_arguments['order'] ) )
			unset ($query_arguments['orderby'] );

		if ( isset( $query_arguments['post_mime_type'] ) && ('all' == strtolower( $query_arguments['post_mime_type'] ) ) )
			unset ($query_arguments['post_mime_type'] );

		$attachments = self::_get_attachments( $query_arguments );
		if ( empty($attachments) )
			return '';
	
		if ( is_feed() ) {
			$output = "\n";
			foreach ( $attachments as $att_id => $attachment )
				$output .= wp_get_attachment_link($att_id, $arguments['size'], true) . "\n";
			return $output;
		}
	
		$id = $post->ID;
		$itemtag = tag_escape( $arguments['itemtag'] );
		$icontag = tag_escape( $arguments['icontag'] );
		$captiontag = tag_escape( $arguments['captiontag'] );
		$columns = intval( $arguments['columns']);
		$itemwidth = $columns > 0 ? floor(100/$columns) : 100;
		$float = is_rtl() ? 'right' : 'left';

		$size = $arguments['size'];
		if ( 'icon'	 == strtolower( $size) ) {
			$size = array( 60, 60 );
			$show_icon = true;
		}
		else
			$show_icon = false;
			
		$selector = "gallery-{$instance}";
	
		$gallery_style = $gallery_div = '';
		if ( apply_filters( 'use_default_gallery_style', true ) )
			$gallery_style = "
			<style type='text/css'>
				#{$selector} {
					margin: auto;
				}
				#{$selector} .gallery-item {
					float: {$float};
					margin-top: 10px;
					text-align: center;
					width: {$itemwidth}%;
				}
				#{$selector} img {
					border: 2px solid #cfcfcf;
				}
				#{$selector} .gallery-caption {
					margin-left: 0;
				}
			</style>
			<!-- see gallery_shortcode() in wp-includes/media.php -->";
		$size_class = sanitize_html_class( $size );
		$gallery_div = "<div id='$selector' class='gallery galleryid-{$id} gallery-columns-{$columns} gallery-size-{$size_class}'>";
		$output = apply_filters( 'gallery_style', $gallery_style . "\n\t\t" . $gallery_div );
	
		$i = 0;
		foreach ( $attachments as $id => $attachment ) {
			$link = isset($arguments['link']) && 'file' == $arguments['link'] ? wp_get_attachment_link($attachment->ID, $size, false, $show_icon) : wp_get_attachment_link($attachment->ID, $size, true, $show_icon);
	
			$output .= "<{$itemtag} class='gallery-item'>";
			$output .= "
				<{$icontag} class='gallery-icon'>
					$link
				</{$icontag}>";
				$post_excerpt = esc_attr( trim( $attachment->post_excerpt ) );
			if ( $captiontag && ! empty( $post_excerpt ) ) {
				$output .= "
					<{$captiontag} class='wp-caption-text gallery-caption'>
					" . wptexturize($post_excerpt) . "
					</{$captiontag}>";
			}
			$output .= "</{$itemtag}>";
			if ( $columns > 0 && ++$i % $columns == 0 )
				$output .= '<br style="clear: both" />';
		}
	
		$output .= "
				<br style='clear: both;' />
			</div>\n";
	
		return $output;
	}
	
	/**
	 * Replaces /wp-includes/post.php function get_posts()
	 *
	 * @since .50
	 *
	 * @param array Attributes of the shortcode
	 *
	 * @return array List of attachments returned from WP_Query
	 */
	private static function _get_attachments( $args ) {
		if ( ! empty($args['include']) ) {
			$incposts = wp_parse_id_list( $args['include'] );
			$args['posts_per_page'] = count($incposts);  // only the number of posts included
			$args['post__in'] = $incposts;
		} elseif ( ! empty($args['exclude']) )
			$args['post__not_in'] = wp_parse_id_list( $args['exclude'] );
	
		$args['ignore_sticky_posts'] = true;
		$args['no_found_rows'] = true;
	
		$get_posts = new WP_Query;
		return $get_posts->query($args);
	
	}
} // Class MLAShortcodes
?>