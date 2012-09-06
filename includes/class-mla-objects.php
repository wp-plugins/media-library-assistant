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
	 */
	public static function initialize() {
		self::_build_taxonomies();
	}

	/**
	 * Registers Attachment Categories and Attachment Tags custom taxonomies, adds taxonomy-related filters
	 *
	 * @since 0.1
	 */
	private static function _build_taxonomies( ) {
		$labels = array(
			'name' => _x( 'Attachment Categories', 'taxonomy general name' ),
			'singular_name' => _x( 'Attachment Category', 'taxonomy singular name' ),
			'search_items' => __( 'Search Attachment Categories' ),
			'all_items' => __( 'All Attachment Categories' ),
			'parent_item' => __( 'Parent Attachment Category' ),
			'parent_item_colon' => __( 'Parent Attachment Category:' ),
			'edit_item' => __( 'Edit Attachment Category' ),
			'update_item' => __( 'Update Attachment Category' ),
			'add_new_item' => __( 'Add New Attachment Category' ),
			'new_item_name' => __( 'New Attachment Category Name' ),
			'menu_name' => __( 'Attachment Category' ) 
		);
		
		if ( 'checked' == MLASettings::mla_get_option( 'attachment_category' ) ) {
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
			
			add_filter( 'manage_edit-attachment_category_columns', 'MLAObjects::mla_attachment_category_get_columns_filter', 10, 1 ); // $columns
			add_filter( 'manage_attachment_category_custom_column', 'MLAObjects::mla_attachment_category_column_filter', 10, 3 ); // $place_holder, $column_name, $tag->term_id
		}
		
		$labels = array(
			'name' => _x( 'Attachment Tags', 'taxonomy general name' ),
			'singular_name' => _x( 'Attachment Tag', 'taxonomy singular name' ),
			'search_items' => __( 'Search Attachment Tags' ),
			'all_items' => __( 'All Attachment Tags' ),
			'parent_item' => __( 'Parent Attachment Tag' ),
			'parent_item_colon' => __( 'Parent Attachment Tag:' ),
			'edit_item' => __( 'Edit Attachment Tag' ),
			'update_item' => __( 'Update Attachment Tag' ),
			'add_new_item' => __( 'Add New Attachment Tag' ),
			'new_item_name' => __( 'New Attachment Tag Name' ),
			'menu_name' => __( 'Attachment Tag' ) 
		);
		
		if ( 'checked' == MLASettings::mla_get_option( 'attachment_tag' ) ) {
			register_taxonomy(
				'attachment_tag',
				array( 'attachment' ),
				array(
				  'hierarchical' => false,
				  'labels' => $labels,
				  'show_ui' => true,
				  'query_var' => true,
				  'rewrite' => true 
				)
			);
			
			add_filter( 'manage_edit-attachment_tag_columns', 'MLAObjects::mla_attachment_tag_get_columns_filter', 10, 1 ); // $columns
			add_filter( 'manage_attachment_tag_custom_column', 'MLAObjects::mla_attachment_tag_column_filter', 10, 3 ); // $place_holder, $column_name, $tag->term_id
		}
		
	} // mla_build_taxonomies_action
	
	/**
	 * WordPress Filter for Attachment Category "Attachments" column,
	 * which replaces the "Posts" column with an equivalent "Attachments" column.
	 *
	 * @since 0.1
	 *
	 * @param	array	column definitions for the Attachment Category list table.
	 *
	 * @return	array	updated column definitions for the Attachment Category list table.
	 */
	public static function mla_attachment_category_get_columns_filter( $columns ) {
		unset( $columns[ 'posts' ] );
		$columns[ 'attachments' ] = 'Attachments';
		return $columns;
	}
	
	/**
	 * WordPress Filter for Attachment Category "Attachments" column,
	 * which returns a count of the attachments assigned a given category
	 *
	 * @since 0.1
	 *
	 * @param	string	unknown, undocumented parameter.
	 * @param	array	name of the column.
	 * @param	array	ID of the term for which the count is desired.
	 *
	 * @return	array	HTML markup for the column content; number of attachments in the category
	 *					and alink to retrieve a list of them.
	 */
	public static function mla_attachment_category_column_filter( $place_holder, $column_name, $term_id ) {
		$objects = get_objects_in_term( $term_id, 'attachment_category', array( ) );
		$term = get_term( $term_id, 'attachment_category' );
		
		if ( is_wp_error( $term ) ) {
			error_log( 'ERROR: mla_attachment_category_column_filter - get_term ' . $objects->get_error_message(), 0 );
			return 0;
		}
		
		if ( is_wp_error( $objects ) ) {
			error_log( 'ERROR: mla_attachment_category_column_filter - get_objects_in_term ' . $objects->get_error_message(), 0 );
			return 0;
		}
		
		return sprintf( '<a href="%s">%d</a>', esc_url( add_query_arg(
				array( 'page' => 'mla-menu', 'attachment_category' => $term->slug, 'heading_suffix' => urlencode( $term->name ) ), 'upload.php' ) ), count( $objects ) );
	}
	
	/**
	 * WordPress Filter for Attachment Tag "Attachments" column,
	 * which replaces the "Posts" column with an equivalent "Attachments" column.
	 *
	 * @since 0.1
	 *
	 * @param	array	column definitions for the Attachment Category list table.
	 *
	 * @return	array	updated column definitions for the Attachment Category list table.
	 */
	public static function mla_attachment_tag_get_columns_filter( $columns ) {
		unset( $columns[ 'posts' ] );
		$columns[ 'attachments' ] = 'Attachments';
		return $columns;
	}
	
	/**
	 * WordPress Filter for Attachment Tag "Attachments" column,
	 * which returns a count of the attachments assigned a given tag
	 *
	 * @since 0.1
	 *
	 * @param	string	unknown, undocumented parameter
	 * @param	array	name of the column
	 * @param	array	ID of the term for which the count is desired
	 *
	 * @return	array	HTML markup for the column content; number of attachments with the tag
	 *					and alink to retrieve a list of them.
	 */
	public static function mla_attachment_tag_column_filter( $place_holder, $column_name, $term_id ) {
		$objects = get_objects_in_term( $term_id, 'attachment_tag', array( ) );
		$term = get_term( $term_id, 'attachment_tag' );
		if ( is_wp_error( $term ) ) {
			error_log( 'ERROR: mla_attachment_tag_column_filter - get_term ' . $objects->get_error_message(), 0 );
			return 0;
		}
		
		if ( is_wp_error( $objects ) ) {
			error_log( 'ERROR: mla_attachment_tag_column_filter - get_objects_in_term ' . $objects->get_error_message(), 0 );
			return 0;
		}
		
		return sprintf( '<a href="%s">%d</a>', esc_url( add_query_arg(
			array( 'page' => 'mla-menu', 'attachment_tag' => $term->slug, 'heading_suffix' => urlencode( $term->name ) ), 'upload.php' ) ), count( $objects ) );
	}
} //Class MLAObjects
?>