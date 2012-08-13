<?php
/**
 * Media Library Assistant Shortcode handler(s)
 *
 * @package Media Library Assistant
 * @since 0.1
 */

/**
 * WordPress Shortcode; renders a complete list of all attachments and references to them
 *
 * @since 0.1
 *
 * @return	string	HTML markup for the attachment list
 */
function mla_attachment_list_shortcode( /* $atts */ ) {
	global $wpdb;
	
	/*	extract(shortcode_atts(array(
	'item_type'=>'attachment',
	'organize_by'=>'title',
	), $atts)); */
	
	$attachments = $wpdb->get_results( "
	SELECT ID, post_title, post_name, post_parent
	FROM $wpdb->posts
	WHERE post_type = 'attachment' 
	" );
	
	foreach ( $attachments as $attachment ) {
		$references = MLAData::mla_fetch_attachment_references( $attachment->ID, $attachment->post_parent );
		
		echo '&nbsp;<br><h3>' . $attachment->ID . ', ' . $attachment->post_title . ', Parent: ' . $attachment->post_parent . '<br>' . $attachment->post_name . '<br>' . $references['base_file'] . "</h3>\r\n";
		
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
				
				echo $feature_id . ' (' . $feature->post_type . '), ' . $feature->post_title . "<br>\r\n";
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
					
					echo $insert->ID . ' (' . $insert->post_type . '), ' . $insert->post_title . "<br>\r\n";
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
add_shortcode( 'mla_attachment_list', 'mla_attachment_list_shortcode' );
?>
