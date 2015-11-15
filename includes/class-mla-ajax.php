<?php
/**
 * Media Library Assistant Ajax Handlers
 *
 * @package Media Library Assistant
 * @since 2.20
 */

/**
 * Class MLA (Media Library Assistant) Ajax contains handlers for simple Ajax requests
 *
 * @package Media Library Assistant
 * @since 2.20
 */
class MLA_Ajax {
	/**
	 * Initialization function, similar to __construct()
	 *
	 * @since 2.20
	 *
	 * @return	void
	 */
	public static function initialize() {
		add_action( 'admin_init', 'MLA_Ajax::mla_admin_init_action' );
	}

	/**
	 * Adds flat checklist taxonomy support to the Media Manager Modal Window.
	 * Declared public because it is an action.
	 *
	 * @since 2.20
	 */
	public static function mla_admin_init_action( ) {
//error_log( 'DEBUG: MLA_Ajax::mla_admin_init_action() $_REQUEST = ' . var_export( $_REQUEST, true ), 0 );

		/*
		 * If there's no action variable, we have nothing more to do
		 */
		if ( ! isset( $_POST['action'] ) ) {
			return;
		}

		/*
		 * For flat taxonomies that use the checklist meta box, substitute our own handler
		 * for /wp-admin/includes/ajax-actions.php function _wp_ajax_add_hierarchical_term().
		 */
		if ( ( defined('DOING_AJAX') && DOING_AJAX ) && ( 'add-' == substr( $_POST['action'], 0, 4 ) ) ) {
			$key = substr( $_POST['action'], 4 );
			if ( MLACore::mla_taxonomy_support( $key, 'flat-checklist' ) ) {
				self::_mla_ajax_add_flat_term( $key );
				/* note: this function sends an Ajax response and then dies; no return */
			}
		}
	}

	/**
	 * Add flat taxonomy term from "checklist" meta box on the Media Manager Modal Window
	 *
	 * Adapted from the WordPress post_categories_meta_box() in /wp-admin/includes/meta-boxes.php.
	 *
	 * @since 2.20
	 *
	 * @param string The taxonomy name, from $_POST['action']
	 *
	 * @return void Sends JSON response with updated HTML for the checklist
	 */
	private static function _mla_ajax_add_flat_term( $key ) {
		$taxonomy = get_taxonomy( $key );
		check_ajax_referer( $_POST['action'], '_ajax_nonce-add-' . $key, true );

		if ( !current_user_can( $taxonomy->cap->edit_terms ) ) {
			wp_die( -1 );
		}

		$new_names = explode( ',', $_POST[ 'new' . $key ] );
		$new_terms_markup = '';
		foreach( $new_names as $name ) {
			if ( '' === sanitize_title( $name ) ) {
				continue;
			}

			if ( ! $id = term_exists( $name, $key ) ) {
				$id = wp_insert_term( $name, $key );
			}

			if ( is_wp_error( $id ) ) {
				continue;
			}

			if ( is_array( $id ) ) {
				$id = absint( $id['term_id'] );
			} else {
				continue;
			}
			$term = get_term( $id, $key );
			$name = $term->name;
			$new_terms_markup .= "<li id='{$key}-{$id}'><label class='selectit'><input value='{$name}' type='checkbox' name='tax_input[{$key}][]' id='in-{$key}-{$id}' checked='checked' />{$name}</label></li>\n";
		} // foreach new_name

		$input_new_parent_name = "new{$key}_parent";
		$supplemental = "<input type='hidden' name='{$input_new_parent_name}' id='{$input_new_parent_name}' value='-1' />";	

		$add = array(
			'what' => $key,
			'id' => $id,
			'data' => $new_terms_markup,
			'position' => -1,
			'supplemental' => array( 'newcat_parent' => $supplemental )
		);

		$x = new WP_Ajax_Response( $add );
		$x->send();
	} // _mla_ajax_add_flat_term
} // Class MLA_Ajax

/*
 * Check for Media Manager Enhancements
 */
if ( ( ( 'checked' == MLACore::mla_get_option( MLACore::MLA_MEDIA_MODAL_TOOLBAR ) ) || ( 'checked' == MLACore::mla_get_option( MLACore::MLA_MEDIA_GRID_TOOLBAR ) ) ) ) {
	require_once( MLA_PLUGIN_PATH . 'includes/class-mla-media-modal-ajax.php' );
	add_action( 'init', 'MLAModal_Ajax::initialize', 0x7FFFFFFF );
//error_log( __LINE__ . ' MEMORY class-mla-ajax.php class-mla-media-modal-ajax.php ' . number_format( memory_get_peak_usage( true ) ), 0);
}
?>