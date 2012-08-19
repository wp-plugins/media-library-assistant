<?php
/**
 * Media Library Assistant extended List Table class
 *
 * @package Media Library Assistant
 * @since 0.1
 */

/* 
 * The WP_List_Table class isn't automatically available to plugins.
 */
if ( !class_exists( 'WP_List_Table' ) ) {
	require_once( ABSPATH . 'wp-admin/includes/class-wp-list-table.php' );
}

/**
 * Class MLA (Media Library Assistant) List Table extends the core WP_List_Table class
 *
 * @package Media Library Assistant
 * @since 0.1
 */
class MLA_List_Table extends WP_List_Table {
	/*
	 * These variables are used to assign row_actions to exactly one visible column
	 */

	/**
	 * Records assignment of row-level actions to a table row
	 *
	 * Set to the current Post-ID when row-level actions are output for the row.
	 *
	 * @since 0.1
	 *
	 * @var	int
	 */
	private $rollover_id = 0;

	/**
	 * Currently hidden columns
	 *
	 * Records hidden columns so row-level actions are not assigned to them.
	 *
	 * @since 0.1
	 *
	 * @var	array
	 */
	private $currently_hidden = array( );
	
	/*
	 * These arrays help define the table columns.
	 */
	
	/**
	 * Table column definitions
	 *
	 * This array defines table columns and titles where the key is the column slug (and class)
	 * and the value is the column's title text. If you need a checkbox for bulk actions,
	 * use the special slug "cb".
	 * 
	 * The 'cb' column is treated differently than the rest. If including a checkbox
	 * column in your table you must create a column_cb() method. If you don't need
	 * bulk actions or checkboxes, simply leave the 'cb' entry out of your array.
	 *
	 * @since 0.1
	 *
	 * @var	array
	 */
	private static $default_columns = array(
		'cb'     => '<input type="checkbox" />', //Render a checkbox instead of text
		'icon'   => '',
		'ID_parent'     => 'ID/Parent',
		'title_name'  => 'Title/Name',
		'post_title'  => 'Title',
		'post_name'  => 'Name',
		'parent'  => 'Parent ID',
		'featured'   => 'Featured in',
		'inserted' => 'Inserted in',
		'alt_text' => 'ALT Text',
		'caption' => 'Caption',
		'description' => 'Description',
		'base_file' => 'Base File',
		'date' => 'Date',
		'author' => 'Author',
		'attached_to' => 'Attached to',
		'categories'=> 'Att. Categories',
		'tags' => 'Att. Tags'
	);
	
	/**
	 * Default values for hdden columns
	 *
	 * This array is used when the user-level option is not set, i.e.,
	 * the user has not altered the selection of hidden columns.
	 *
	 * The value on the right-hand side must match the column slug, e.g.,
	 * array(0 => 'ID_parent, 1 => 'title_name').
	 *
	 * @since 0.1
	 *
	 * @var	array
	 */
	private static $default_hidden_columns	= array(
		// 'ID_parent',
		// 'title_name',
		0 => 'post_title',
		1 => 'post_name',
		2 => 'parent',
		// 'featured',
		// 'inserted,
		3 => 'alt_text',
		4 => 'caption',
		5 => 'description',
		6 => 'base_file',
		7 => 'date',
		8 => 'author',
		9 => 'attached_to',
		10 => 'categories',
		11 => 'tags'
	);
	
	/**
	 * Sortable column definitions
	 *
	 * This array defines the table columns that can be sorted. The array key
	 * is the column slug that needs to be sortable, and the value is database column
	 * to sort by. Often, the key and value will be the same, but this is not always
	 * the case (as the value is a column name from the database, not the list table).
	 *
	 * The array value also contains a boolean which is 'true' if the data is currently
	 * sorted by that column. This is computed each time the table is displayed.
	 *
	 * @since 0.1
	 *
	 * @var	array
	 */
	private static $default_sortable_columns = array(
		'ID_parent'   => array('ID',false),
		'title_name'  => array('title_name',false),
		'post_title'  => array('post_title',false),
		'post_name'  => array('name',false),
		'parent'  => array('parent',false),
		// 'featured'   => array('featured',false),
		// 'inserted' => array('inserted',false),
		// 'alt_text' => array('alt_text',false),
		'caption' => array('post_excerpt',false),
		'description' => array('post_content',false),
		// 'base_file' => array('base_file',false),
		'date' => array('post_date',false),
		'author' => array('post_author',false),
		'attached_to' => array('post_parent',false),
		// 'categories' => array('categories',false),
		// 'tags' => array('tags',false),
        );

	/**
	 * Access the default list of hidden columns
	 *
	 * @since 0.1
	 *
	 * @return	array	default list of hidden columns
	 */
	private static function _default_hidden_columns( )
	{
		return MLA_List_Table::$default_hidden_columns;
	}
	
	/**
	 * Handler for filter 'get_user_option_managemedia_page_mla-menucolumnshidden'
	 *
	 * Required because the screen.php get_hidden_columns function only uses
	 * the get_user_option result. Set when the file is loaded because the object
	 * is not created in time for the call from screen.php.
	 *
	 * @since 0.1
	 *
	 * @param	string	current list of hidden columns, if any
	 * @param	string	'managemedia_page_mla-menucolumnshidden'
	 * @param	object	WP_User object, if logged in
	 *
	 * @return	array	updated list of hidden columns
	 */
	public static function mla_manage_hidden_columns( $result, $option, $user_data ) {
		if ( $result )
			return $result;
		else
			return self::_default_hidden_columns();
	}
	
	/**
	 * Handler for filter 'manage_media_page_mla-menu_columns'
	 *
	 * This required filter dictates the table's columns and titles. Set when the
	 * file is loaded because the list_table object isn't created in time
	 * to affect the "screen options" setup.
	 *
	 * @since 0.1
	 *
	 * @return	array	list of table columns
	 */
	public static function mla_manage_columns( )
	{
		return MLA_List_Table::$default_columns;
	}
	
	/**
	 * Initializes some properties from $_REQUEST vairables, then
	 * calls the parent constructor to set some default configs.
	 *
	 * @since 0.1
	 */
	function __construct( ) {
		$this->detached = isset( $_REQUEST['detached'] ) || isset( $_REQUEST['find_detached'] );
		$this->is_trash = isset( $_REQUEST['status'] ) && $_REQUEST['status'] == 'trash';
		
		//Set parent defaults
		parent::__construct( array(
			 'singular' => 'attachment', //singular name of the listed records
			'plural' => 'attachments', //plural name of the listed records
			'ajax' => true //does this table support ajax?
		) );
		
		$this->currently_hidden = self::get_hidden_columns();
	}
	
	/**
	 * Called when the parent class can't find a method specifically built
	 * for a given column. All our columns should have a specific method,
	 * so this function returns a troubleshooting message.
	 *
	 * @since 0.1
	 *
	 * @param	array	A singular item (one full row's worth of data)
	 * @param	array	The name/slug of the column to be processed
	 * @return	string	Text or HTML to be placed inside the column
	 */
	function column_default( $item, $column_name ) {
		//Show the whole array for troubleshooting purposes
		return 'column_default: ' . $column_name . ', ' . print_r( $item, true );
	}
	
	/**
	 * Displays checkboxes for using bulk actions. The 'cb' column
	 * is given special treatment when columns are processed.
	 *
	 * @since 0.1
	 * 
	 * @param	array	A singular attachment (post) object
	 * @return	string	HTML markup to be placed inside the column
	 */
	function column_cb( $item )
	{
		return sprintf( '<input type="checkbox" name="cb_%1$s[]" value="%2$s" />',
		/*%1$s*/ $this->_args['singular'], //Let's simply repurpose the table's singular label ("attachment")
		/*%2$s*/ $item->ID //The value of the checkbox should be the object's id
		);
	}
	
	/**
	 * Supply the content for a custom column
	 *
	 * @since 0.1
	 * 
	 * @param	array	A singular attachment (post) object
	 * @return	string	HTML markup to be placed inside the column
	 */
	function column_icon( $item )
	{
		return wp_get_attachment_image( $item->ID, array( 80, 60 ), true );
	}
	
	/**
	 * Add rollover actions to exactly one of the following displayed columns:
	 * 'ID_parent', 'title_name', 'post_title', 'post_name'
	 *
	 * @since 0.1
	 * 
	 * @param	object	A singular attachment (post) object
	 * @param	string	Current column name
	 *
	 * @return	array	Names and URLs of row-level actions
	 */
	private function _build_rollover_actions( $item, $column ) {
		$actions = array( );
		
		if ( ( $this->rollover_id != $item->ID ) && !in_array( $column, $this->currently_hidden ) ) {
			/*
			 * Build rollover actions
			 */
			
			$view_args = array(
				 'page' => $_REQUEST['page'],
				'mla_item_ID' => $item->ID 
			);
			
			if ( isset( $_REQUEST['detached'] ) )
				$view_args['detached'] = $_REQUEST['detached'];
			elseif ( isset( $_REQUEST['status'] ) )
				$view_args['status'] = $_REQUEST['status'];
			elseif ( isset( $_REQUEST['post_mime_type'] ) )
				$view_args['post_mime_type'] = $_REQUEST['post_mime_type'];
			
			if ( isset( $_REQUEST['m'] ) )
				$view_args['m'] = $_REQUEST['m'];
			
			if ( isset( $_REQUEST['att_cat'] ) )
				$view_args['att_cat'] = $_REQUEST['att_cat'];
			
			if ( current_user_can( 'edit_post', $item->ID ) ) {
				if ( $this->is_trash )
					$actions['restore'] = '<a class="submitdelete" href="' . add_query_arg( $view_args, wp_nonce_url( '?mla_admin_action=' . MLA::MLA_ADMIN_SINGLE_RESTORE, MLA::MLA_ADMIN_NONCE ) ) . '">Restore</a>';
				else
					$actions['edit'] = '<a href="' . add_query_arg( $view_args, wp_nonce_url( '?mla_admin_action=' . MLA::MLA_ADMIN_SINGLE_EDIT_DISPLAY, MLA::MLA_ADMIN_NONCE ) ) . '">Edit</a>';
			} // edit_post
			
			if ( current_user_can( 'delete_post', $item->ID ) ) {
				if ( !$this->is_trash && EMPTY_TRASH_DAYS && MEDIA_TRASH )
					$actions['trash'] = '<a class="submitdelete" href="' . add_query_arg( $view_args, wp_nonce_url( '?mla_admin_action=' . MLA::MLA_ADMIN_SINGLE_TRASH, MLA::MLA_ADMIN_NONCE ) ) . '">Move to Trash</a>';
				else {
					// If using trash for posts and pages but not for attachments, warn before permanently deleting 
					$delete_ays = EMPTY_TRASH_DAYS && !MEDIA_TRASH ? ' onclick="return showNotice.warn();"' : '';
					
					$actions['delete'] = '<a class="submitdelete"' . $delete_ays . ' href="' . add_query_arg( $view_args, wp_nonce_url( '?mla_admin_action=' . MLA::MLA_ADMIN_SINGLE_DELETE, MLA::MLA_ADMIN_NONCE ) ) . '">Delete Permanently</a>';
				}
			} // delete_post
			
			$this->rollover_id = $item->ID;
		} // $this->rollover_id != $item->ID
		
		return $actions;
	}
	
	/**
	 * Supply the content for a custom column
	 *
	 * @since 0.1
	 * 
	 * @param	array	A singular attachment (post) object
	 * @return	string	HTML markup to be placed inside the column
	 */
	function column_ID_parent( $item ) {
		$row_actions = self::_build_rollover_actions( $item, 'ID_parent' );
		
		if ( !empty( $row_actions ) ) {
			return sprintf( '%1$s<br><span style="color:silver">(parent:%2$s)</span><br>%3$s', /*%1$s*/ $item->ID, /*%2$s*/ $item->post_parent, /*%3$s*/ $this->row_actions( $row_actions ) );
		} else {
			return sprintf( '%1$s<br><span style="color:silver">(parent:%2$s)</span>', /*%1$s*/ $item->ID, /*%2$s*/ $item->post_parent );
		}
	}
	
	/**
	 * Supply the content for a custom column
	 *
	 * @since 0.1
	 * 
	 * @param	array	A singular attachment (post) object
	 * @return	string	HTML markup to be placed inside the column
	 */
	function column_title_name( $item ) {
		$errors = '';
		if ( !$item->references['found_reference'] )
			$errors .= '(ORPHAN) ';
		
		if ( $item->references['is_unattached'] )
			$errors .= '(UNATTACHED) ';
		else {
			if ( !$item->references['found_parent'] )
				$errors .= '(BAD PARENT) ';
		}
		
		$row_actions = self::_build_rollover_actions( $item, 'title_name' );
		
		if ( !empty( $row_actions ) ) {
			return sprintf( '%1$s<br>%2$s<br>%3$s%4$s', /*%1$s*/ $item->post_title, /*%2$s*/ $item->post_name, /*%3$s*/ $errors, /*%4$s*/ $this->row_actions( $row_actions ) );
		} else {
			return sprintf( '%1$s<br>%2$s<br>%3$s', /*%1$s*/ $item->post_title, /*%2$s*/ $item->post_name, /*%3$s*/ $errors );
		}
	}
	
	/**
	 * Supply the content for a custom column
	 *
	 * @since 0.1
	 * 
	 * @param	array	A singular attachment (post) object
	 * @return	string	HTML markup to be placed inside the column
	 */
	function column_post_title( $item ) {
		$row_actions = self::_build_rollover_actions( $item, 'post_title' );
		
		if ( !empty( $row_actions ) ) {
			return sprintf( '%1$s<br>%2$s', /*%1$s*/ $item->post_title, /*%2$s*/ $this->row_actions( $row_actions ) );
		} else {
			return $item->post_title;
		}
	}
	
	/**
	 * Supply the content for a custom column
	 *
	 * @since 0.1
	 * 
	 * @param	array	A singular attachment (post) object
	 * @return	string	HTML markup to be placed inside the column
	 */
	function column_post_name( $item ) {
		$row_actions = self::_build_rollover_actions( $item, 'post_name' );
		
		if ( !empty( $row_actions ) ) {
			return sprintf( '%1$s<br>%2$s', /*%1$s*/ $item->post_name, /*%2$s*/ $this->row_actions( $row_actions ) );
		} else {
			return $item->post_name;
		}
	}
	
	/**
	 * Supply the content for a custom column
	 *
	 * @since 0.1
	 * 
	 * @param	array	A singular attachment (post) object
	 * @return	string	HTML markup to be placed inside the column
	 */
	function column_parent( $item ) {
		return (string) $item->post_parent;
	}
	
	/**
	 * Supply the content for a custom column
	 *
	 * @since 0.1
	 * 
	 * @param	array	A singular attachment (post) object
	 * @return	string	HTML markup to be placed inside the column
	 */
	function column_featured( $item ) {
		$value = '';
		
		foreach ( $item->references['features'] as $feature_id => $feature ) {
			//			error_log("\$feature = ".var_export($feature, true), 0);
			
			if ( $feature_id == $item->post_parent )
				$parent = 'PARENT ';
			else
				$parent = '';
			
			$value .= sprintf( '%1$s (%2$s %3$s), %4$s', /*%1$s*/ $parent, /*%2$s*/ $feature->post_type, /*%3$s*/ $feature_id, /*%4$s*/ $feature->post_title ) . "<br>\r\n";
		} // foreach $feature
		
		return $value;
	}
	
	/**
	 * Supply the content for a custom column
	 *
	 * @since 0.1
	 * 
	 * @param	array	A singular attachment (post) object
	 * @return	string	HTML markup to be placed inside the column
	 */
	function column_inserted( $item ) {
		$value = '';
		
		foreach ( $item->references['inserts'] as $file => $inserts ) {
			//			error_log("\$file = ".var_export($file, true), 0);
			//			error_log("\$inserts = ".var_export($inserts, true), 0);
			$value .= sprintf( '<strong>%1$s</strong><br>', $file );
			
			foreach ( $inserts as $insert ) {
				if ( $insert->ID == $item->post_parent )
					$parent = '&nbsp;&nbsp;PARENT ';
				else
					$parent = '&nbsp;&nbsp;';
				
				$value .= sprintf( '%1$s (%2$s %3$s), %4$s', /*%1$s*/ $parent, /*%2$s*/ $insert->post_type, /*%3$s*/ $insert->ID, /*%4$s*/ $insert->post_title ) . "<br>\r\n";
			} // foreach $insert
		} // foreach $file
		
		return $value;
	}
	
	/**
	 * Supply the content for a custom column
	 *
	 * @since 0.1
	 * 
	 * @param	array	A singular attachment (post) object
	 * @return	string	HTML markup to be placed inside the column
	 */
	function column_alt_text( $item ) {
		if ( isset( $item->mla_wp_attachment_image_alt ) )
			return $item->mla_wp_attachment_image_alt;
		else
			return '';
	}
	
	/**
	 * Supply the content for a custom column
	 *
	 * @since 0.1
	 * 
	 * @param	array	A singular attachment (post) object
	 * @return	string	HTML markup to be placed inside the column
	 */
	function column_caption( $item ) {
		return $item->post_excerpt;
	}
	
	/**
	 * Supply the content for a custom column
	 *
	 * @since 0.1
	 * 
	 * @param	array	A singular attachment (post) object
	 * @return	string	HTML markup to be placed inside the column
	 */
	function column_description( $item ) {
		return $item->post_content;
	}
	
	/**
	 * Supply the content for a custom column
	 *
	 * @since 0.1
	 * 
	 * @param	array	A singular attachment (post) object
	 * @return	string	HTML markup to be placed inside the column
	 */
	function column_base_file( $item ) {
		return $item->references['base_file'];
	}
	
	/**
	 * Supply the content for a custom column
	 *
	 * @since 0.1
	 * 
	 * @param	array	A singular attachment (post) object
	 * @return	string	HTML markup to be placed inside the column
	 */
	function column_date( $item ) {
		if ( '0000-00-00 00:00:00' == $item->post_date ) {
			$t_time = $h_time = __( 'Unpublished' );
		} else {
			$t_time = get_the_time( __( 'Y/m/d g:i:s A' ), $item );
			$m_time = $item->post_date;
			$time = get_post_time( 'G', true, $item, false );
			
			if ( ( abs( $t_diff = time() - $time ) ) < 86400 ) {
				if ( $t_diff < 0 )
					$h_time = sprintf( __( '%s from now' ), human_time_diff( $time ) );
				else
					$h_time = sprintf( __( '%s ago' ), human_time_diff( $time ) );
			} else {
				$h_time = mysql2date( __( 'Y/m/d' ), $m_time );
			}
		}
		
		return $h_time;
	}
	
	/**
	 * Supply the content for a custom column
	 *
	 * @since 0.1
	 * 
	 * @param	array	A singular attachment (post) object
	 * @return	string	HTML markup to be placed inside the column
	 */
	function column_author( $item ) {
		$user = get_user_by( 'id', $item->post_author );
		
		if ( isset( $user->data->display_name ) )
			return $user->data->display_name;
		else
			return 'unknown';
	}
	
	/**
	 * Supply the content for a custom column
	 *
	 * @since 0.1
	 * 
	 * @param	array	A singular attachment (post) object
	 * @return	string	HTML markup to be placed inside the column
	 */
	function column_attached_to( $item ) {
		if ( isset( $item->parent_date ) )
			$parent_date = $item->parent_date;
		else
			$parent_date = '';
		
		if ( isset( $item->parent_title ) )
			$parent_title = $item->parent_title;
		else
			$parent_title = '(Unattached)';
		
		if ( isset( $item->parent_type ) )
			$parent_type = '(' . $item->parent_type . ')';
		else
			$parent_type = '';
		
		return sprintf( '%1$s<br>%2$s<br>%3$s', /*%1$s*/ $parent_title, /*%2$s*/ mysql2date( __( 'Y/m/d' ), $parent_date ), /*%3$s*/ $parent_type ) . "<br>\r\n";
	}
	
	/**
	 * Supply the content for a custom column
	 *
	 * @since 0.1
	 * 
	 * @param	array	A singular attachment (post) object
	 * @return	string	HTML markup to be placed inside the column
	 */
	function column_categories( $item ) {
		if ( ( 'checked' == MLASettings::mla_get_option( 'attachment_category' ) ) && ( is_object_in_taxonomy( 'attachment', 'attachment_category' ) ) ) {
			$terms = wp_get_object_terms( $item->ID, 'attachment_category' );
			if ( !is_wp_error( $terms ) ) {
				if ( empty( $terms ) )
					return 'none';
				else {
					$list = array( );
					foreach ( $terms as $term ) {
						$list[ ] = sprintf( '<a href="%s">%s</a>', esc_url( add_query_arg( array(
							 'page' => 'mla-menu',
							'att_cat' => $term->term_id,
							'heading_suffix' => urlencode( $term->name ) 
						), 'upload.php' ) ), esc_html( sanitize_term_field( 'name', $term->name, $term->term_id, 'category', 'display' ) ) );
					} // foreach $term
					
					return join( ', ', $list );
				} // !empty $terms
			} // if !is_wp_error
		} // 'checked'
		else {
			return 'not supported';
		}
	}
	
	/**
	 * Supply the content for a custom column
	 *
	 * @since 0.1
	 * 
	 * @param	array	A singular attachment (post) object
	 * @return	string	HTML markup to be placed inside the column
	 */
	function column_tags( $item ) {
		if ( ( 'checked' == MLASettings::mla_get_option( 'attachment_tag' ) ) && ( is_object_in_taxonomy( 'attachment', 'attachment_tag' ) ) ) {
			$terms = wp_get_object_terms( $item->ID, 'attachment_tag' );
			if ( !is_wp_error( $terms ) ) {
				if ( empty( $terms ) )
					return 'none';
				else {
					$list = array( );
					foreach ( $terms as $term ) {
						$list[ ] = sprintf( '<a href="%s">%s</a>', esc_url( add_query_arg( array(
							 'page' => 'mla-menu',
							'attachment_tag' => $term->slug,
							'heading_suffix' => urlencode( $term->name ) 
						), 'upload.php' ) ), esc_html( sanitize_term_field( 'name', $term->name, $term->term_id, 'category', 'display' ) ) );
					} // foreach $term
					
					return join( ', ', $list );
				} // !empty $terms
			} // if !is_wp_error
		} // 'checked'
		else {
			return 'not supported';
		}
	}
	
	/**
	 * This method dictates the table's columns and titles.
	 *
	 * @since 0.1
	 * 
	 * @return	array	Column information: 'slugs'=>'Visible Titles'
	 */
	function get_columns( ) {
		return $columns = MLA_List_Table::mla_manage_columns();
	}
	
	/**
	 * Returns the list of currently hidden columns from a user option or
	 * from default values if the option is not set
	 *
	 * @since 0.1
	 * 
	 * @return	array	Column information,e.g., array(0 => 'ID_parent, 1 => 'title_name')
	 */
	function get_hidden_columns( )
	{
		$columns = get_user_option( 'managemedia_page_mla-menucolumnshidden' );

		if ( is_array( $columns ) )
			return $columns;
		else
			return self::_default_hidden_columns();
	}
	
	/**
	 * Returns an array where the  key is the column that needs to be sortable
	 * and the value is db column to sort by. Also notes the current sort column,
	 * if set.
	 *
	 * @since 0.1
	 * 
	 * @return	array	Sortable column information,e.g.,
	 * 					'slugs'=>array('data_values',bool)
	 */
	function get_sortable_columns( ) {
		$columns = MLA_List_Table::$default_sortable_columns;
		
		if ( isset( $_REQUEST['orderby'] ) ) {
			$needle = array(
				 $_REQUEST['orderby'],
				false 
			);
			$key = array_search( $needle, $columns );
			if ( $key ) {
				$columns[ $key ][ 1 ] = true;
			}
		} else {
			$columns['title_name'][ 1 ] = true;
		}
		
		return $columns;
	}
	
	/**
	 * Returns an associative array listing all the views that can be used with this table.
	 * These are listed across the top of the page and managed by WordPress.
	 *
	 * @since 0.1
	 * 
	 * @return	array	View information,e.g., array ( id => link )
	 */
	function get_views( ) {
		global $wpdb;
		
		$type_links = array( );
		$_num_posts = (array) wp_count_attachments();
		$_total_posts = array_sum( $_num_posts ) - $_num_posts['trash'];
		$total_orphans = $wpdb->get_var( "SELECT COUNT( * ) FROM $wpdb->posts WHERE post_type = 'attachment' AND post_status != 'trash' AND post_parent < 1" );
		
		$post_mime_types = $this->_mime_types();
		$avail_post_mime_types = $this->_avail_mime_types( $_num_posts );
		$matches = wp_match_mime_types( array_keys( $post_mime_types ), array_keys( $_num_posts ) );
		
		/*
		 * Remember the view filters
		 */
		$base_url = 'upload.php?page=mla-menu';
		
		if ( isset( $_REQUEST['m'] ) )
			$base_url = add_query_arg( array(
				 'm' => $_REQUEST['m'] 
			), $base_url );
		
		if ( isset( $_REQUEST['att_cat'] ) )
			$base_url = add_query_arg( array(
				 'att_cat' => $_REQUEST['att_cat'] 
			), $base_url );
		
		foreach ( $matches as $type => $reals )
			foreach ( $reals as $real )
				$num_posts[ $type ] = ( isset( $num_posts[ $type ] ) ) ? $num_posts[ $type ] + $_num_posts[ $real ] : $_num_posts[ $real ];
		
		$class = ( empty( $_REQUEST['post_mime_type'] ) && !$this->detached && !$this->is_trash ) ? ' class="current"' : '';
		$type_links['all'] = "<a href='{$base_url}'$class>" . sprintf( _nx( 'All <span class="count">(%s)</span>', 'All <span class="count">(%s)</span>', $_total_posts, 'uploaded files' ), number_format_i18n( $_total_posts ) ) . '</a>';

		foreach ( $post_mime_types as $mime_type => $label ) {
			$class = '';
			
			if ( !wp_match_mime_types( $mime_type, $avail_post_mime_types ) )
				continue;
			
			if ( !empty( $_REQUEST['post_mime_type'] ) && wp_match_mime_types( $mime_type, $_REQUEST['post_mime_type'] ) )
				$class = ' class="current"';
			
			if ( !empty( $num_posts[ $mime_type ] ) ) {
				$type_links[ $mime_type ] = "<a href='" . add_query_arg( array(
					 'post_mime_type' => $mime_type 
				), $base_url ) . "'$class>" . sprintf( translate_nooped_plural( $label[ 2 ], $num_posts[ $mime_type ] ), number_format_i18n( $num_posts[ $mime_type ] ) ) . '</a>';
			}
		} // foreach post_mimie_types
		
		$type_links['detached'] = '<a href="' . add_query_arg( array(
			 'detached' => '1' 
		), $base_url ) . '"' . ( $this->detached ? ' class="current"' : '' ) . '>' . sprintf( _nx( 'Unattached <span class="count">(%s)</span>', 'Unattached <span class="count">(%s)</span>', $total_orphans, 'detached files' ), number_format_i18n( $total_orphans ) ) . '</a>';
		
		if ( !empty( $_num_posts['trash'] ) )
			$type_links['trash'] = '<a href="' . add_query_arg( array(
				 'status' => 'trash' 
			), $base_url ) . '"' . ( $this->is_trash ? ' class="current"' : '' ) . '>' . sprintf( _nx( 'Trash <span class="count">(%s)</span>', 'Trash <span class="count">(%s)</span>', $_num_posts['trash'], 'uploaded files' ), number_format_i18n( $_num_posts['trash'] ) ) . '</a>';
		
		return $type_links;
	}
	
	/**
	 * Get an associative array ( option_name => option_title ) with the list
	 * of bulk actions available on this table.
	 *
	 * @since 0.1
	 * 
	 * @return	array	Contains all the bulk actions: 'slugs'=>'Visible Titles'
	 */
	function get_bulk_actions( )
	{
		$actions = array( );
		
		if ( $this->is_trash ) {
			$actions['restore'] = 'Restore';
			$actions['delete'] = 'Delete Permanently';
		} else {
			// $actions['edit'] = 'Edit';
			// $actions['attach'] = 'Attach';
			// $actions['catagorize'] = 'Catagorize';
			// $actions['tag'] = 'Tag';
			
			if ( EMPTY_TRASH_DAYS && MEDIA_TRASH )
				$actions['trash'] = 'Move to Trash';
			else
				$actions['delete'] = 'Delete Permanently';
		}
		
		return $actions;
	}
	
	/**
	 * Extra controls to be displayed between bulk actions and pagination.
	 * Modeled after class-wp-posts-list-table.php in wp-admin/includes
	 *
	 * @since 0.1
	 * 
	 * @param	string	'top' or 'bottom', i.e., above or below the table rows
	 *
	 * @return	array	Contains all the bulk actions: 'slugs'=>'Visible Titles'
	 */
	function extra_tablenav( $which )
	{
		echo ( '<div class="alignleft actions">' );
		
		if ( 'top' == $which ) {
			$this->months_dropdown( 'attachment' );
			
			if ( ( 'checked' == MLASettings::mla_get_option( 'attachment_category' ) ) && ( is_object_in_taxonomy( 'attachment', 'attachment_category' ) ) ) {
				$dropdown_options = array(
					'show_option_all' => 'All Categories',
					'show_option_none' => 'No Categories',
					'orderby' => 'ID',
					'order' => 'ASC',
					'show_count' => false,
					'hide_empty' => false,
					'child_of' => 0,
					'exclude' => '',
					// 'exclude_tree => '', 
					'echo' => true,
					'depth' => 3,
					'tab_index' => 0,
					'name' => 'att_cat',
					'id' => 'name',
					'class' => 'postform',
					'selected' => isset( $_REQUEST['att_cat'] ) ? $_REQUEST['att_cat'] : 0,
					'hierarchical' => true,
					'pad_counts' => false,
					'taxonomy' => 'attachment_category',
					'hide_if_empty' => false 
				);
				
				wp_dropdown_categories( $dropdown_options );
			}
			
			submit_button( __( 'Filter' ), 'secondary', 'mla_filter', false, array(
				 'id' => 'post-query-submit' 
			) );
		}
		
		if ( $this->is_trash && current_user_can( 'edit_others_posts' ) ) {
			submit_button( __( 'Empty Trash' ), 'button-secondary apply', 'delete_all', false );
		}
		
		echo ( '</div>' );
	}
	
	/**
	 * Prepares the list of items for displaying.
	 *
	 * This is where you prepare your data for display. This method will usually
	 * be used to query the database, sort and filter the data, and generally
	 * get it ready to be displayed. At a minimum, we should set $this->items and
	 * $this->set_pagination_args().
	 *
	 * @since 0.1
	 */
	function prepare_items( ) {
		$this->_column_headers = array(
			$this->get_columns(),
			$this->get_hidden_columns(),
			$this->get_sortable_columns() 
		);
		
		/*
		 * Use sort and pagination data to build a custom query.
		 */
		
		$orderby = ( !empty( $_REQUEST['orderby'] ) ) ? $_REQUEST['orderby'] : 'post_title'; //If no sort, default to title
		$order = ( !empty( $_REQUEST['order'] ) ) ? $_REQUEST['order'] : 'asc'; //If no order, default to asc
		
		/*
		 * REQUIRED for pagination.
		 */
		$current_page = $this->get_pagenum();
		$total_items = $this->_count_list_table_items( $_REQUEST );
		$user = get_current_user_id();
		$screen = get_current_screen();
		$option = $screen->get_option( 'per_page', 'option' );
		$per_page = get_user_meta( $user, $option, true );
		if ( empty( $per_page ) || $per_page < 1 ) {
			$per_page = $screen->get_option( 'per_page', 'default' );
		}
		
		/*
		 * REQUIRED. Assign sorted and paginated data to the items property, where 
		 * it can be used by the rest of the class.
		 */
		$this->items = MLAData::mla_query_list_table_items( $_REQUEST, $orderby, $order, ( ( $current_page - 1 ) * $per_page ), $per_page );
		
		/*
		 * REQUIRED. We also have to register our pagination options & calculations.
		 */
		$this->set_pagination_args( array(
			'total_items' => $total_items, //WE have to calculate the total number of items
			'per_page' => $per_page, //WE have to determine how many items to show on a page
			'total_pages' => ceil( $total_items / $per_page ) //WE have to calculate the total number of pages
		) );
	}
	
	/**
	 * Get possible mime types for view preparation.
	 * Modeled after get_post_mime_types in wp-admin/includes/post.php,
	 * with additional entries.
	 *
	 * @since 0.1
	 *
	 * @return	array	Mime type names and HTML markup for views
	 */
	private function _mime_types( )
	{
		return array(
			'image' => array(
				0 => 'Images',
				1 => 'Manage Images',
				2 => array(
					 0 => 'Image <span class="count">(%s)</span>',
					1 => 'Images <span class="count">(%s)</span>',
					'singular' => 'Image <span class="count">(%s)</span>',
					'plural' => 'Images <span class="count">(%s)</span>',
					'context' => NULL,
					'domain' => NULL 
				) 
			),
			'audio' => array(
				 0 => 'Audio',
				1 => 'Manage Audio',
				2 => array(
					 0 => 'Audio <span class="count">(%s)</span>',
					1 => 'Audio <span class="count">(%s)</span>',
					'singular' => 'Audio <span class="count">(%s)</span>',
					'plural' => 'Audio <span class="count">(%s)</span>',
					'context' => NULL,
					'domain' => NULL 
				) 
			),
			'video' => array(
				 0 => 'Video',
				1 => 'Manage Video',
				2 => array(
					 0 => 'Video <span class="count">(%s)</span>',
					1 => 'Video <span class="count">(%s)</span>',
					'singular' => 'Video <span class="count">(%s)</span>',
					'plural' => 'Video <span class="count">(%s)</span>',
					'context' => NULL,
					'domain' => NULL 
				) 
			),
			'text' => array(
				 0 => 'Text',
				1 => 'Manage Text',
				2 => array(
					 0 => 'Text <span class="count">(%s)</span>',
					1 => 'Text <span class="count">(%s)</span>',
					'singular' => 'Text <span class="count">(%s)</span>',
					'plural' => 'Text <span class="count">(%s)</span>',
					'context' => NULL,
					'domain' => NULL 
				) 
			),
			'application' => array(
				 0 => 'Applications',
				1 => 'Manage Applications',
				2 => array(
					 0 => 'Application <span class="count">(%s)</span>',
					1 => 'Applications <span class="count">(%s)</span>',
					'singular' => 'Application <span class="count">(%s)</span>',
					'plural' => 'Applications <span class="count">(%s)</span>',
					'context' => NULL,
					'domain' => NULL 
				) 
			) 
		);
	}
	
	/**
	 * Get mime types with one or more attachments for view preparation.
	 * Modeled after get_available_post_mime_types in wp-admin/includes/post.php,
	 * with additional entries.
	 *
	 * @since 0.1
	 *
	 * @param	array	Number of posts for each mime type
	 *
	 * @return	array	Mime type names
	 */
	private function _avail_mime_types( $num_posts ) {
		$available = array( );
		
		foreach ( $num_posts as $mime_type => $number ) {
			if ( ( $number > 0 ) && ( $mime_type <> 'trash' ) )
				$available[ ] = $mime_type;
		}
		
		return $available;
	}
	
	/**
	 * Get the total number of attachment posts
	 *
	 * @since 0.1
	 *
	 * @param	array	Query variables, e.g., from $_REQUEST
	 *
	 * @return	int		Number of attachment posts
	 */
	private function _count_list_table_items( $request )
	{
		$request = MLAData::mla_prepare_list_table_query( $request );
		unset( $request['paged'] ); // ignore pagination here - we need the total count
		$results = new WP_Query( $request );
		
		if ( isset( $request['detached'] ) )
			remove_filter( 'posts_where', 'MLAData::mla_query_list_table_items_helper' );
		
		return $results->found_posts;
	}
} // class MLA_List_Table

/*
 * Filters are added here, when the source file is loaded, because the MLA_List_Table
 * object is created too late to be useful.
 */
add_filter( 'get_user_option_managemedia_page_mla-menucolumnshidden', 'MLA_List_Table::mla_manage_hidden_columns', 10, 3 );
add_filter( 'manage_media_page_mla-menu_columns', 'MLA_List_Table::mla_manage_columns', 10, 0 );
?>