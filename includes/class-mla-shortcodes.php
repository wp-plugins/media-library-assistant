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
		if ('checked' == MLAOptions::mla_get_option( 'exclude_revisions' ) )
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
	public static $mla_debug_messages = '';
	
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
			'mla_style' => MLAOptions::mla_get_option('default_style'),
			'mla_markup' => MLAOptions::mla_get_option('default_markup'),
			'mla_float' => is_rtl() ? 'right' : 'left',
			'mla_itemwidth' => NULL,
			'mla_margin' => '1.5',
			'mla_link_text' => '',
			'mla_rollover_text' => '',
			'mla_caption' => '',
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

		/*
		 * The default style template includes "margin: 1.5%" to put a bit of
		 * minimum space between the columns. "mla_margin" can be used to increase
		 * this. "mla_itemwidth" can be used with "columns=0" to achieve a "responsive"
		 * layout.
		 */
		 
		$margin = absint( 2 * (float) $arguments['mla_margin'] );
		if ( isset ( $arguments['mla_itemwidth'] ) ) {
			$itemwidth = absint( $arguments['mla_itemwidth'] );
		}
		else {
			$itemwidth = $arguments['columns'] > 0 ? (floor(100/$arguments['columns']) - $margin) : 100 - $margin;
		}
		
		$float = strtolower( $arguments['mla_float'] );
		if ( ! in_array( $float, array( 'left', 'none', 'right' ) ) )
			$float = is_rtl() ? 'right' : 'left';
		
		$style_values = array(
			'mla_style' => $arguments['mla_style'],
			'mla_markup' => $arguments['mla_markup'],
			'instance' => $instance,
			'id' => $post->ID,
			'itemtag' => tag_escape( $arguments['itemtag'] ),
			'icontag' => tag_escape( $arguments['icontag'] ),
			'captiontag' => tag_escape( $arguments['captiontag'] ),
			'columns' => intval( $arguments['columns']),
			'itemwidth' => intval( $itemwidth ),
			'margin' => $arguments['mla_margin'],
			'float' => $float,
			'selector' => "mla_gallery-{$instance}",
			'size_class' => sanitize_html_class( $size_class )
		);
	
		$style_template = $gallery_style = '';
		if ( apply_filters( 'use_mla_gallery_style', true ) ) {
			$style_template = MLAOptions::mla_fetch_gallery_template( $style_values['mla_style'], 'style' );
			if ( empty( $style_template ) ) {
				$style_values['mla_style'] = 'default';
				$style_template = MLAOptions::mla_fetch_gallery_template( 'default', 'style' );
			}
				
			if ( ! empty ( $style_template ) ) {
				$gallery_style = MLAData::mla_parse_template( $style_template, $style_values );
			} // !empty template
		} // use_mla_gallery_style
		
		$upload_dir = wp_upload_dir();
		$markup_values = $style_values;
		$markup_values['base_url'] = $upload_dir['baseurl'];
		$markup_values['base_dir'] = $upload_dir['basedir'];

		/*
		 * Variable item-level placeholders
		 */
		$terms_placeholders = array();
		$custom_placeholders = array();
		$iptc_placeholders = array();
		$exif_placeholders = array();

		$markup_template = MLAOptions::mla_fetch_gallery_template( $markup_values['mla_markup'] . '-open', 'markup' );
		if ( empty( $markup_template ) ) {
			$markup_values['mla_markup'] = 'default';
			$markup_template = MLAOptions::mla_fetch_gallery_template( 'default-open', 'markup' );
		}
			
		if ( ! empty( $markup_template ) ) {
			$gallery_div = MLAData::mla_parse_template( $markup_template, $markup_values );

			$row_open_template = MLAOptions::mla_fetch_gallery_template( $markup_values['mla_markup'] . '-row-open', 'markup' );
			if ( empty( $row_open_template ) )
				$row_open_template = '';
				
			$item_template = MLAOptions::mla_fetch_gallery_template( $markup_values['mla_markup'] . '-item', 'markup' );
			if ( empty( $item_template ) )
				$item_template = '';
			else {
				/*
				 * Look for variable item-level placeholders
				 */
				$new_text = str_replace( '{+', '[+', str_replace( '+}', '+]', $arguments['mla_link_text'] . $arguments['mla_rollover_text'] . $arguments['mla_caption'] ) );
				$placeholders = MLAData::mla_get_template_placeholders( $item_template . $new_text );
// error_log( '$placeholders = ' . var_export( $placeholders, true ), 0);				
				foreach ($placeholders as $key => $value ) {
					switch ( $value['prefix'] ) {
						case 'terms':
							$terms_placeholders[ $key ] = $value;
							break;
						case 'custom':
							$custom_placeholders[ $key ] = $value;
							break;
						case 'iptc':
							$iptc_placeholders[ $key ] = $value;
							break;
						case 'exif':
							$exif_placeholders[ $key ] = $value;
							break;
						default:
							// ignore anything else
					} // switch
				} // $placeholders
			} // $item_template
				
			$row_close_template = MLAOptions::mla_fetch_gallery_template( $markup_values['mla_markup'] . '-row-close', 'markup' );
			if ( empty( $row_close_template ) )
				$row_close_template = '';
				
			$close_template = MLAOptions::mla_fetch_gallery_template( $markup_values['mla_markup'] . '-close', 'markup' );
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
				
			/*
			 * Add variable placeholders
			 */
			foreach ( $terms_placeholders as $key => $value ) {
				$text = '';
				$terms = wp_get_object_terms( $attachment->ID, $value['value'] );
			
				if ( is_wp_error( $terms ) || empty( $terms ) )
					$text = '';
				else {
					if ( $value['single'] )
						$text = sanitize_term_field( 'name', $terms[0]->name, $terms[0]->term_id, $value, 'display' );
					else
						foreach ( $terms as $term ) {
							$term_name = sanitize_term_field( 'name', $term->name, $term->term_id, $value, 'display' );
							$text .= strlen( $text ) ? ', ' . $term_name : $term_name;
						}
				}
				
				$markup_values[ $key ] = $text;
			} // $terms_placeholders
			
			foreach ( $custom_placeholders as $key => $value ) {
				$record = get_metadata( 'post', $attachment->ID, $value['value'], $value['single'] );

				if ( is_wp_error( $record ) || empty( $record ) )
					$text = '';
				elseif ( is_scalar( $record ) )
					$text = (string) $record;
				elseif ( is_array( $record ) ) {
					$text = '';
					foreach ( $record as $term ) {
						$term_name = sanitize_text_field( $term );
						$text .= strlen( $text ) ? ', ' . $term_name : $term_name;
					}
				} // is_array
				else
					$text = '';
				
				$markup_values[ $key ] = $text;
			} // $custom_placeholders
			
			if ( !empty( $iptc_placeholders ) || !empty( $iptc_placeholders ) ) {
				$image_metadata = MLAData::mla_fetch_attachment_image_metadata( $attachment->ID );
			}
			
			foreach ( $iptc_placeholders as $key => $value ) {
				// convert friendly name/slug to identifier
				if ( array_key_exists( $value['value'], self::$mla_iptc_keys ) ) {
					$value['value'] = self::$mla_iptc_keys[ $value['value'] ];
				}
				
				$text = '';
				if ( array_key_exists( $value['value'], $image_metadata['mla_iptc_metadata'] ) ) {
					$record = $image_metadata['mla_iptc_metadata'][ $value['value'] ];
					if ( is_array( $record ) ) {
						if ( $value['single'] )
							$text = $record[0];
						else
							foreach ( $record as $term ) {
								$term_name = sanitize_text_field( $term );
								$text .= strlen( $text ) ? ', ' . $term_name : $term_name;
							}
					} // is_array
					else
						$text = $record;
				}
					
				$markup_values[ $key ] = $text;
			} // $iptc_placeholders
			
			foreach ( $exif_placeholders as $key => $value ) {
				$text = '';
				if ( array_key_exists( $value['value'], $image_metadata['mla_exif_metadata'] ) ) {
					$record = $image_metadata['mla_exif_metadata'][ $value['value'] ];
					if ( is_array( $record ) ) {
						$text = var_export( $record, true);
					} // is_array
					else
						$text = $record;
				}
					
				$markup_values[ $key ] = $text;
			} // $exif_placeholders
			
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
			$i++;
			if ( $markup_values['columns'] > 0 && $i % $markup_values['columns'] == 0 )
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

	/**
	 * IPTC Dataset identifiers and names
	 *
	 * This array contains the identifiers and names of Datasets defined in
	 * the "IPTC-NAA Information Interchange Model Version No. 4.1".
	 *
	 * @since 0.90
	 *
	 * @var	array
	 */
	private static $mla_iptc_records = array(
		// Envelope Record
		"1#000" => "Model Version",
		"1#005" => "Destination",
		"1#020" => "File Format",
		"1#022" => "File Format Version",
		"1#030" => "Service Identifier",
		"1#040" => "Envelope Number",
		"1#050" => "Product ID",
		"1#060" => "Envelope Priority",
		"1#070" => "Date Sent",
		"1#080" => "Time Sent",
		"1#090" => "Coded Character Set",
		"1#100" => "UNO",
		"1#120" => "ARM Identifier",
		"1#122" => "ARM Version",
		
		// Application Record
		"2#000" => "Record Version",
		"2#003" => "Object Type Reference",
		"2#004" => "Object Attribute Reference",
		"2#005" => "Object Name",
		"2#007" => "Edit Status",
		"2#008" => "Editorial Update",
		"2#010" => "Urgency",
		"2#012" => "Subject Reference",
		"2#015" => "Category",
		"2#020" => "Supplemental Category",
		"2#022" => "Fixture Identifier",
		"2#025" => "Keywords",
		"2#026" => "Content Location Code",
		"2#027" => "Content Location Name",
		"2#030" => "Release Date",
		"2#035" => "Release Time",
		"2#037" => "Expiration Date",
		"2#038" => "Expiration Time",
		"2#040" => "Special Instructions",
		"2#042" => "Action Advised",
		"2#045" => "Reference Service",
		"2#047" => "Reference Date",
		"2#050" => "Reference Number",
		"2#055" => "Date Created",
		"2#060" => "Time Created",
		"2#062" => "Digital Creation Date",
		"2#063" => "Digital Creation Time",
		"2#065" => "Originating Program",
		"2#070" => "Program Version",
		"2#075" => "Object Cycle",
		"2#080" => "By-line",
		"2#085" => "By-line Title",
		"2#090" => "City",
		"2#092" => "Sub-location",
		"2#095" => "Province or State",
		"2#100" => "Country or Primary Location Code",
		"2#101" => "Country or Primary Location Name",
		"2#103" => "Original Transmission Reference",
		"2#105" => "Headline",
		"2#110" => "Credit",
		"2#115" => "Source",
		"2#116" => "Copyright Notice",
		"2#118" => "Contact",
		"2#120" => "Caption or Abstract",
		"2#122" => "Caption Writer or Editor",
		"2#125" => "Rasterized Caption",
		"2#130" => "Image Type",
		"2#131" => "Image Orientation",
		"2#135" => "Language Identifier",
		"2#150" => "Audio Type",
		"2#151" => "Audio Sampling Rate",
		"2#152" => "Audio Sampling Resolution",
		"2#153" => "Audio Duration",
		"2#154" => "Audio Outcue",
		"2#200" => "ObjectData Preview File Format",
		"2#201" => "ObjectData Preview File Format Version",
		"2#202" => "ObjectData Preview Data",
		
		// Pre ObjectData Descriptor Record
		"7#010"  => "Size Mode",
		"7#020"  => "Max Subfile Size",
		"7#090"  => "ObjectData Size Announced",
		"7#095"  => "Maximum ObjectData Size",
		
		// ObjectData Record
		"8#010"  => "Subfile",
		
		// Post ObjectData Descriptor Record
		"9#010"  => "Confirmed ObjectData Size"
	);

	/**
	 * IPTC Dataset friendly name/slug and identifiers
	 *
	 * This array contains the sanitized names and identifiers of Datasets defined in
	 * the "IPTC-NAA Information Interchange Model Version No. 4.1".
	 *
	 * @since 0.90
	 *
	 * @var	array
	 */
	public static $mla_iptc_keys = array(
		// Envelope Record
		'model-version' => '1#000',
		'destination' => '1#005',
		'file-format' => '1#020',
		'file-format-version' => '1#022',
		'service-identifier' => '1#030',
		'envelope-number' => '1#040',
		'product-id' => '1#050',
		'envelope-priority' => '1#060',
		'date-sent' => '1#070',
		'time-sent' => '1#080',
		'coded-character-set' => '1#090',
		'uno' => '1#100',
		'arm-identifier' => '1#120',
		'arm-version' => '1#122',

		// Application Record
		'record-version' => '2#000',
		'object-type-reference' => '2#003',
		'object-attribute-reference' => '2#004',
		'object-name' => '2#005',
		'edit-status' => '2#007',
		'editorial-update' => '2#008',
		'urgency' => '2#010',
		'subject-reference' => '2#012',
		'category' => '2#015',
		'supplemental-category' => '2#020',
		'fixture-identifier' => '2#022',
		'keywords' => '2#025',
		'content-location-code' => '2#026',
		'content-location-name' => '2#027',
		'release-date' => '2#030',
		'release-time' => '2#035',
		'expiration-date' => '2#037',
		'expiration-time' => '2#038',
		'special-instructions' => '2#040',
		'action-advised' => '2#042',
		'reference-service' => '2#045',
		'reference-date' => '2#047',
		'reference-number' => '2#050',
		'date-created' => '2#055',
		'time-created' => '2#060',
		'digital-creation-date' => '2#062',
		'digital-creation-time' => '2#063',
		'originating-program' => '2#065',
		'program-version' => '2#070',
		'object-cycle' => '2#075',
		'by-line' => '2#080',
		'by-line-title' => '2#085',
		'city' => '2#090',
		'sub-location' => '2#092',
		'province-or-state' => '2#095',
		'country-or-primary-location-code' => '2#100',
		'country-or-primary-location-name' => '2#101',
		'original-transmission-reference' => '2#103',
		'headline' => '2#105',
		'credit' => '2#110',
		'source' => '2#115',
		'copyright-notice' => '2#116',
		'contact' => '2#118',
		'caption-or-abstract' => '2#120',
		'caption-writer-or-editor' => '2#122',
		'rasterized-caption' => '2#125',
		'image-type' => '2#130',
		'image-orientation' => '2#131',
		'language-identifier' => '2#135',
		'audio-type' => '2#150',
		'audio-sampling-rate' => '2#151',
		'audio-sampling-resolution' => '2#152',
		'audio-duration' => '2#153',
		'audio-outcue' => '2#154',
		'objectdata-preview-file-format' => '2#200',
		'objectdata-preview-file-format-version' => '2#201',
		'objectdata-preview-data' => '2#202',
		
		// Pre ObjectData Descriptor Record
		'size-mode' => '7#010',
		'max-subfile-size' => '7#020',
		'objectdata-size-announced' => '7#090',
		'maximum-objectdata-size' => '7#095',
		
		// ObjectData Record
		'subfile' => '8#010',
		
		// Post ObjectData Descriptor Record
		'confirmed-objectdata-size' => '9#010'
);

	/**
	 * IPTC Dataset descriptions
	 *
	 * This array contains the descriptions of Datasets defined in
	 * the "IPTC-NAA Information Interchange Model Version No. 4.1".
	 *
	 * @since 0.90
	 *
	 * @var	array
	 */
	private static $mla_iptc_descriptions = array(
		// Envelope Record
		"1#000" => "2 octet binary IIM version number",
		"1#005" => "Max 1024 characters of Destination (ISO routing information); repeatable",
		"1#020" => "2 octet binary file format number, see IPTC-NAA V4 Appendix A",
		"1#022" => "2 octet binary file format version number",
		"1#030" => "Max 10 characters of Service Identifier and product",
		"1#040" => "8 Character Envelope Number",
		"1#050" => "Max 32 characters subset of provider's overall service; repeatable",
		"1#060" => "1 numeric character of envelope handling priority (not urgency)",
		"1#070" => "8 numeric characters of Date Sent by service - CCYYMMDD",
		"1#080" => "11 characters of Time Sent by service - HHMMSS±HHMM",
		"1#090" => "Max 32 characters of control functions, etc.",
		"1#100" => "14 to 80 characters of eternal, globally unique identification for objects",
		"1#120" => "2 octet binary Abstract Relationship Model Identifier",
		"1#122" => "2 octet binary Abstract Relationship Model Version",
		
		// Application Record
		"2#000" => "2 octet binary Information Interchange Model, Part II version number",
		"2#003" => "3 to 67 Characters of Object Type Reference number and optional text",
		"2#004" => "3 to 67 Characters of Object Attribute Reference number and optional text; repeatable",
		"2#005" => "Max 64 characters of the object name or shorthand reference",
		"2#007" => "Max 64 characters of the status of the objectdata",
		"2#008" => "2 numeric characters of the type of update this object provides",
		"2#010" => "1 numeric character of the editorial urgency of content",
		"2#012" => "13 to 236 characters of a structured definition of the subject matter; repeatable",
		"2#015" => "Max 3 characters of the subject of the objectdata, DEPRECATED",
		"2#020" => "Max 32 characters (each) of further refinement of subject, DEPRECATED; repeatable",
		"2#022" => "Max 32 characters identifying recurring, predictable content",
		"2#025" => "Max 64 characters (each) of tags; repeatable",
		"2#026" => "3 characters of ISO3166 country code or IPTC-assigned code; repeatable",
		"2#027" => "Max 64 characters of publishable country/geographical location name; repeatable",
		"2#030" => "8 numeric characters of Release Date - CCYYMMDD",
		"2#035" => "11 characters of Release Time (earliest use) - HHMMSS±HHMM",
		"2#037" => "8 numeric characters of Expiration Date (latest use) -  CCYYMDD",
		"2#038" => "11 characters of Expiration Time (latest use) - HHMMSS±HHMM",
		"2#040" => "Max 256 Characters of editorial instructions, e.g., embargoes and warnings",
		"2#042" => "2 numeric characters of type of action this object provides to a previous object",
		"2#045" => "Max 10 characters of the Service ID (1#030) of a prior envelope; repeatable",
		"2#047" => "8 numeric characters of prior envelope Reference Date (1#070) - CCYYMMDD; repeatable",
		"2#050" => "8 characters of prior envelope Reference Number (1#040); repeatable",
		"2#055" => "8 numeric characters of intellectual content Date Created - CCYYMMDD",
		"2#060" => "11 characters of intellectual content Time Created - HHMMSS±HHMM",
		"2#062" => "8 numeric characters of digital representation creation date - CCYYMMDD",
		"2#063" => "11 characters of digital representation creation time - HHMMSS±HHMM",
		"2#065" => "Max 32 characters of the program used to create the objectdata",
		"2#070" => "Program Version - Max 10 characters of the version of the program used to create the objectdata",
		"2#075" => "1 character where a=morning, p=evening, b=both",
		"2#080" => "Max 32 Characters of the name of the objectdata creator, e.g., the writer, photographer; repeatable",
		"2#085" => "Max 32 characters of the title of the objectdata creator; repeatable",
		"2#090" => "Max 32 Characters of the city of objectdata origin",
		"2#092" => "Max 32 Characters of the location within the city of objectdata origin",
		"2#095" => "Max 32 Characters of the objectdata origin Province or State",
		"2#100" => "3 characters of ISO3166 or IPTC-assigned code for Country of objectdata origin",
		"2#101" => "Max 64 characters of publishable country/geographical location name of objectdata origin",
		"2#103" => "Max 32 characters of a code representing the location of original transmission",
		"2#105" => "Max 256 Characters of a publishable entry providing a synopsis of the contents of the objectdata",
		"2#110" => "Max 32 Characters that identifies the provider of the objectdata (Vs the owner/creator)",
		"2#115" => "Max 32 Characters that identifies the original owner of the intellectual content",
		"2#116" => "Max 128 Characters that contains any necessary copyright notice",
		"2#118" => "Max 128 characters that identifies the person or organisation which can provide further background information; repeatable",
		"2#120" => "Max 2000 Characters of a textual description of the objectdata",
		"2#122" => "Max 32 Characters that the identifies the person involved in the writing, editing or correcting the objectdata or caption/abstract; repeatable",
		"2#125" => "7360 binary octets of the rasterized caption - 1 bit per pixel, 460x128-pixel image",
		"2#130" => "2 characters of color composition type and information",
		"2#131" => "1 alphabetic character indicating the image area layout - P=portrait, L=landscape, S=square",
		"2#135" => "2 or 3 aphabetic characters containing the major national language of the object, according to the ISO 639:1988 codes",
		"2#150" => "2 characters identifying monaural/stereo and exact type of audio content",
		"2#151" => "6 numeric characters representing the audio sampling rate in hertz (Hz)",
		"2#152" => "2 numeric characters representing the number of bits in each audio sample",
		"2#153" => "6 numeric characters of the Audio Duration - HHMMSS",
		"2#154" => "Max 64 characters of the content of the end of an audio objectdata",
		"2#200" => "2 octet binary file format of the ObjectData Preview",
		"2#201" => "2 octet binary particular version of the ObjectData Preview File Format",
		"2#202" => "Max 256000 binary octets containing the ObjectData Preview data",
		
		// Pre ObjectData Descriptor Record
		"7#010"  => "1 numeric character - 0=objectdata size not known, 1=objectdata size known at beginning of transfer",
		"7#020"  => "4 octet binary maximum subfile dataset(s) size",
		"7#090"  => "4 octet binary objectdata size if known at beginning of transfer",
		"7#095"  => "4 octet binary largest possible objectdata size",
		
		// ObjectData Record
		"8#010"  => "Subfile DataSet containing the objectdata itself; repeatable",
		
		// Post ObjectData Descriptor Record
		"9#010"  => "4 octet binary total objectdata size"
	);

	/**
	 * IPTC file format identifiers and descriptions
	 *
	 * This array contains the file format identifiers and descriptions defined in
	 * the "IPTC-NAA Information Interchange Model Version No. 4.1" for dataset 1#020.
	 *
	 * @since 0.90
	 *
	 * @var	array
	 */
	private static $mla_iptc_formats = array(
		00 => "No ObjectData",
		01 => "IPTC-NAA Digital Newsphoto Parameter Record",
		02 => "IPTC7901 Recommended Message Format",
		03 => "Tagged Image File Format (Adobe/Aldus Image data)",
		04 => "Illustrator (Adobe Graphics data)",
		05 => "AppleSingle (Apple Computer Inc)",
		06 => "NAA 89-3 (ANPA 1312)",
		07 => "MacBinary II",
		08 => "IPTC Unstructured Character Oriented File Format (UCOFF)",
		09 => "United Press International ANPA 1312 variant",
		10 => "United Press International Down-Load Message",
		11 => "JPEG File Interchange (JFIF)",
		12 => "Photo-CD Image-Pac (Eastman Kodak)",
		13 => "Microsoft Bit Mapped Graphics File [*.BMP]",
		14 => "Digital Audio File [*.WAV] (Microsoft & Creative Labs)",
		15 => "Audio plus Moving Video [*.AVI] (Microsoft)",
		16 => "PC DOS/Windows Executable Files [*.COM][*.EXE]",
		17 => "Compressed Binary File [*.ZIP] (PKWare Inc)",
		18 => "Audio Interchange File Format AIFF (Apple Computer Inc)",
		19 => "RIFF Wave (Microsoft Corporation)",
		20 => "Freehand (Macromedia/Aldus)",
		21 => "Hypertext Markup Language - HTML (The Internet Society)",
		22 => "MPEG 2 Audio Layer 2 (Musicom), ISO/IEC",
		23 => "MPEG 2 Audio Layer 3, ISO/IEC",
		24 => "Portable Document File (*.PDF) Adobe",
		25 => "News Industry Text Format (NITF)",
		26 => "Tape Archive (*.TAR)",
		27 => "Tidningarnas Telegrambyrå NITF version (TTNITF DTD)",
		28 => "Ritzaus Bureau NITF version (RBNITF DTD)",
		29 => "Corel Draw [*.CDR]"
	);

	/**
	 * IPTC image type identifiers and descriptions
	 *
	 * This array contains the image type identifiers and descriptions defined in
	 * the "IPTC-NAA Information Interchange Model Version No. 4.1" for dataset 2#130, octet 2.
	 *
	 * @since 0.90
	 *
	 * @var	array
	 */
	private static $mla_iptc_image_types = array(
		"M" => "Monochrome",
		"Y" => "Yellow Component",
		"M" => "Magenta Component",
		"C" => "Cyan Component",
		"K" => "Black Component",
		"R" => "Red Component",
		"G" => "Green Component",
		"B" => "Blue Component",
		"T" => "Text Only",
		"F" => "Full colour composite, frame sequential",
		"L" => "Full colour composite, line sequential",
		"P" => "Full colour composite, pixel sequential",
		"S" => "Full colour composite, special interleaving"
	);
} // Class MLAShortcodes
?>