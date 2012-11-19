<?php
/**
 * Media Library Assistant Custom Taxonomy and Post Type objects
 *
 * @package Media Library Assistant
 * @since 0.1
 */

/**
 * Class MLA (Media Library Assistant) Objects defines and manages custom taxonomies for Attachment Categories and Tags
 *
 * @package Media Library Assistant
 * @since 0.20
 */
class MLAObjects {
	/**
	 * Initialization function, similar to __construct()
	 *
	 * @since 0.20
	 *
	 * @return	void
	 */
	public static function initialize() {
		self::_build_taxonomies();
	}

	/**
	 * Registers Attachment Categories and Attachment Tags custom taxonomies, adds taxonomy-related filters
	 *
	 * @since 0.1
	 *
	 * @return	void
	 */
	private static function _build_taxonomies( ) {
		if ( MLASettings::mla_taxonomy_support('attachment_category') ) {
			$labels = array(
				'name' => _x( 'Att. Categories', 'taxonomy general name' ),
				'singular_name' => _x( 'Att. Category', 'taxonomy singular name' ),
				'search_items' => __( 'Search Att. Categories' ),
				'all_items' => __( 'All Att. Categories' ),
				'parent_item' => __( 'Parent Att. Category' ),
				'parent_item_colon' => __( 'Parent Att. Category:' ),
				'edit_item' => __( 'Edit Att. Category' ),
				'update_item' => __( 'Update Att. Category' ),
				'add_new_item' => __( 'Add New Att. Category' ),
				'new_item_name' => __( 'New Att. Category Name' ),
				'menu_name' => __( 'Att. Category' ) 
			);
			
			register_taxonomy(
				'attachment_category',
				array( 'attachment' ),
				array(
				  'hierarchical' => true,
				  'labels' => $labels,
				  'show_ui' => true,
				  'query_var' => true,
				  'rewrite' => true 
				)
			);
		}
		
		if ( MLASettings::mla_taxonomy_support('attachment_tag') ) {
			$labels = array(
				'name' => _x( 'Att. Tags', 'taxonomy general name' ),
				'singular_name' => _x( 'Att. Tag', 'taxonomy singular name' ),
				'search_items' => __( 'Search Att. Tags' ),
				'all_items' => __( 'All Att. Tags' ),
				'parent_item' => __( 'Parent Att. Tag' ),
				'parent_item_colon' => __( 'Parent Att. Tag:' ),
				'edit_item' => __( 'Edit Att. Tag' ),
				'update_item' => __( 'Update Att. Tag' ),
				'add_new_item' => __( 'Add New Att. Tag' ),
				'new_item_name' => __( 'New Att. Tag Name' ),
				'menu_name' => __( 'Att. Tag' ) 
			);
			
			register_taxonomy(
				'attachment_tag',
				array( 'attachment' ),
				array(
				  'hierarchical' => false,
				  'labels' => $labels,
				  'show_ui' => true,
				  'update_count_callback' => '_update_post_term_count',
				  'query_var' => true,
				  'rewrite' => true 
				)
			);
		}
	
		$taxonomies = get_taxonomies( array ( 'show_ui' => 'true' ), 'names' );
		foreach ( $taxonomies as $tax_name ) {
			if ( MLASettings::mla_taxonomy_support( $tax_name ) ) {
				register_taxonomy_for_object_type( $tax_name, 'attachment');
				add_filter( "manage_edit-{$tax_name}_columns", 'MLAObjects::mla_taxonomy_get_columns_filter', 10, 1 ); // $columns
				add_filter( "manage_{$tax_name}_custom_column", 'MLAObjects::mla_taxonomy_column_filter', 10, 3 ); // $place_holder, $column_name, $tag->term_id
			}
		} // foreach
	} // _build_taxonomies
	
	/**
	 * WordPress Filter for edit taxonomy "Attachments" column,
	 * which replaces the "Posts" column with an equivalent "Attachments" column.
	 *
	 * @since 0.30
	 *
	 * @param	array	column definitions for the edit taxonomy list table
	 *
	 * @return	array	updated column definitions for the edit taxonomy list table
	 */
	public static function mla_taxonomy_get_columns_filter( $columns ) {
		$screen = get_current_screen();
		
		if ( 'attachment' == $screen->post_type ) {
			if ( isset ( $columns[ 'posts' ] ) )
				unset( $columns[ 'posts' ] );
				
			$columns[ 'attachments' ] = 'Attachments';
		}
		
		return $columns;
	}
	
	/**
	 * WordPress Filter for edit taxonomy "Attachments" column,
	 * which returns a count of the attachments assigned a given term
	 *
	 * @since 0.30
	 *
	 * @param	string	current column value; always ''
	 * @param	array	name of the column
	 * @param	array	ID of the term for which the count is desired
	 *
	 * @return	array	HTML markup for the column content; number of attachments in the category
	 *					and alink to retrieve a list of them
	 */
	public static function mla_taxonomy_column_filter( $place_holder, $column_name, $term_id ) {
		$screen = get_current_screen();
		$tax_object = get_taxonomy( $screen->taxonomy );
		
		$term = get_term( $term_id, $tax_object->name );
		
		if ( is_wp_error( $term ) ) {
			error_log( "ERROR: mla_taxonomy_column_filter( {$tax_object->name} ) - get_term " . $objects->get_error_message(), 0 );
			return 0;
		}
		
		$request = array (
			'post_type' => 'attachment', 
			'post_status' => 'inherit',
			'tax_query' => array(
				array(
					'taxonomy' => $tax_object->name,
					'field' => 'slug',
					'terms' => $term->slug,
					'include_children' => false 
				) )
				);
				
		$results = new WP_Query( $request );
		if ( ! empty( $results->error ) ){
			error_log( "ERROR: mla_taxonomy_column_filter( {$tax_object->name} ) - WP_Query " . $results->error, 0 );
			return 0;
		}
		
		return sprintf( '<a href="%s">%d</a>', esc_url( add_query_arg(
				array( 'page' => 'mla-menu', 'mla-tax' => $tax_object->name, 'mla-term' => $term->slug, 'heading_suffix' => urlencode( $tax_object->label . ':' . $term->name ) ), 'upload.php' ) ), $results->post_count );
	}
} //Class MLAObjects
?>