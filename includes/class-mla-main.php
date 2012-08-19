<?php
/**
 * Top-level functions for the Media Library Assistant
 *
 * @package Media Library Assistant
 * @since 0.1
 */

/* 
 * The Meta Boxes functions are't automatically available to plugins.
 */
if ( !function_exists( 'post_categories_meta_box' ) ) {
	require_once( ABSPATH . 'wp-admin/includes/meta-boxes.php' );
}

/**
 * Class MLA (Media Library Assistant) provides several enhancements to the handling
 * of images and files held in the WordPress Media Library.
 *
 * @package Media Library Assistant
 * @since 0.1
 */
class MLA {

	/**
	 * Display name for this plugin
	 *
	 * @since 0.1
	 *
	 * @var	string
	 */
	const PLUGIN_NAME = 'Media Library Assistant';

	/**
	 * Current version number
	 *
	 * @since 0.1
	 *
	 * @var	string
	 */
	const CURRENT_MLA_VERSION = '0.11';

	/**
	 * Minimum version of PHP required for this plugin
	 *
	 * @since 0.1
	 *
	 * @var	string
	 */
	const MIN_PHP_VERSION = '5.2';

	/**
	 * Minimum version of WordPress required for this plugin
	 *
	 * @since 0.1
	 *
	 * @var	string
	 */
	const MIN_WORDPRESS_VERSION = '3.3';

	/**
	 * Slug for registering and enqueueing plugin style sheet
	 *
	 * @since 0.1
	 *
	 * @var	string
	 */
	const STYLESHEET_SLUG = 'mla-style';

	/**
	 * Slug for localizing and enqueueing plugin JavaScript
	 *
	 * @since 0.1
	 *
	 * @var	string
	 */
	const JAVASCRIPT_SLUG = 'mla-scripts';

	/**
	 * Slug for adding plugin submenu
	 *
	 * @since 0.1
	 *
	 * @var	string
	 */
	const ADMIN_PAGE_SLUG = 'mla-menu';
	
	/**
	 * Holds screen ids to match help text to corresponding screen
	 *
	 * @since 0.1
	 *
	 * @var	array
	 */
	private static $page_hooks = array( );
	
	/**
	 * Initialization function, similar to __construct()
	 *
	 * @since 0.1
	 */
	public static function initialize( )
	{
		MLATest::min_php_version( self::MIN_PHP_VERSION, self::PLUGIN_NAME );
		MLATest::min_WordPress_version( self::MIN_WORDPRESS_VERSION, self::PLUGIN_NAME );
		
		add_action( 'admin_enqueue_scripts', 'MLA::mla_admin_enqueue_scripts_action' );
		add_action( 'admin_menu', 'MLA::mla_admin_menu_action' );
		add_filter( 'set-screen-option', 'MLA::mla_set_screen_option_filter', 10, 3 ); // $status, $option, $value
		add_filter( 'screen_options_show_screen', 'MLA::mla_screen_options_show_screen_filter', 10, 2 ); // $show_screen, $this
	}
	
	/**
	 * Load the plugin's Style Sheet and Javascript files
	 *
	 * @since 0.1
	 *
	 * @param	string	Name of the page being loaded
	 *
	 * @return	nothing
	 */
	public static function mla_admin_enqueue_scripts_action( $page_hook ) {
		if ( 'media_page_mla-menu' != $page_hook )
			return;
		
		wp_register_style( self::STYLESHEET_SLUG, MLA_PLUGIN_URL . 'css/mla-style.css', false, self::CURRENT_MLA_VERSION );
		wp_enqueue_style( self::STYLESHEET_SLUG );
		
		if ( isset( $_REQUEST['mla_admin_action'] ) && ( $_REQUEST['mla_admin_action'] == self::MLA_ADMIN_SINGLE_EDIT_DISPLAY ) ) {
			wp_enqueue_script( self::JAVASCRIPT_SLUG, MLA_PLUGIN_URL . 'js/mla-scripts.js', array(
				 'wp-lists',
				'suggest',
				'jquery' 
			), self::CURRENT_MLA_VERSION, false );
			$script_variables = array(
				 'Ajax_Url' => admin_url( 'admin-ajax.php' ) 
			);
			wp_localize_script( self::JAVASCRIPT_SLUG, 'mlascripts', $script_variables );
		}
	}
	
	/**
	 * Add the submenu pages
	 *
	 * Add a submenu page in the "Media" section,
	 * add submenu page(s) for attachment taxonomies,
	 * add filter to clean up taxonomy submenu labels
	 * add settings page in the "Settings" section,
	 * add settings link in the Plugins section entry for MLA.
	 *
	 * @since 0.1
	 */
	public static function mla_admin_menu_action( ) {
		$hook = add_submenu_page( 'upload.php', 'Media Library Assistant', 'Assistant', 'upload_files', self::ADMIN_PAGE_SLUG, 'MLA::mla_render_admin_page' );
		add_action( 'load-' . $hook, 'MLA::mla_add_menu_options' );
		add_action( 'load-' . $hook, 'MLA::mla_add_help_tab' );
		self::$page_hooks[ $hook ] = $hook;
		
		$taxonomies = get_object_taxonomies( 'attachment', 'objects' );
		if ( !empty( $taxonomies ) ) {
			foreach ( $taxonomies as $tax_name => $tax_object ) {
				$hook = add_submenu_page( 'upload.php', $tax_object->label, $tax_object->label, 'manage_categories', 'mla-edit-tags-' . $tax_name, 'MLA::mla_edit_tags_redirect' );
				add_action( 'load-' . $hook, 'MLA::mla_edit_tags_redirect' );
				/*
				 * The hook we need for taxonomy edits is slightly different
				 */
				$hook = 'edit-' . $tax_name;
				self::$page_hooks[ $hook ] = $hook;
			}
			
			add_action( 'load-edit-tags.php', 'MLA::mla_add_help_tab' );
		}
		
		add_filter( 'parent_file', 'MLA::mla_modify_parent_menu', 10, 1 );
	}
	
	/**
	 * Add the "XX Entries per page" filter to the Screen Options tab
	 *
	 * @since 0.1
	 */
	public static function mla_add_menu_options( ) {
		$option = 'per_page';
		
		$args = array(
			 'label' => 'Entries per page',
			'default' => 10,
			'option' => 'mla_entries_per_page' 
		);
		
		add_screen_option( $option, $args );
	}
	
	/**
	 * Only show screen options on the table-list screen
	 *
	 * @since 0.1
	 *
	 * @param	boolean	True to display "Screen Options", false to suppress them
	 * @param	string	Name of the page being loaded
	 *
	 * @return	boolean	True to display "Screen Options", false to suppress them
	 */
	public static function mla_screen_options_show_screen_filter( $show_screen, $this_screen ) {
		if ( isset( $_REQUEST['mla_admin_action'] ) && ( $_REQUEST['mla_admin_action'] == self::MLA_ADMIN_SINGLE_EDIT_DISPLAY ) )
			return false;
		else
			return $show_screen;
	}
	
	/**
	 * Save the "Entries per page" option set by this user
	 *
	 * @since 0.1
	 *
	 * @param	boolean	Unknown - always false?
	 * @param	string	Name of the option being changed
	 * @param	string	New value of the option
	 *
	 * @return	string	New value if this is our option, otherwise nothing
	 */
	public static function mla_set_screen_option_filter( $status, $option, $value )
	{
		if ( 'mla_entries_per_page' == $option )
			return $value;
	}
	
	/**
	 * Action name; uniquely identifies the nonce
	 *
	 * @since 0.1
	 *
	 * @var	string
	 */
	const MLA_ADMIN_NONCE = 'mla_admin';
	
	/**
	 * mla_admin_action value for permanently deleting a single item
	 *
	 * @since 0.1
	 *
	 * @var	string
	 */
	const MLA_ADMIN_SINGLE_DELETE = 'single_item_delete';
	
	/**
	 * mla_admin_action value for displaying a single item
	 *
	 * @since 0.1
	 *
	 * @var	string
	 */
	const MLA_ADMIN_SINGLE_EDIT_DISPLAY = 'single_item_edit_display';
	
	/**
	 * mla_admin_action value for updating a single item
	 *
	 * @since 0.1
	 *
	 * @var	string
	 */
	const MLA_ADMIN_SINGLE_EDIT_UPDATE = 'single_item_edit_update';
	
	/**
	 * mla_admin_action value for restoring a single item from the trash
	 *
	 * @since 0.1
	 *
	 * @var	string
	 */
	const MLA_ADMIN_SINGLE_RESTORE = 'single_item_restore';
	
	/**
	 * mla_admin_action value for moving a single item to the trash
	 *
	 * @since 0.1
	 *
	 * @var	string
	 */
	const MLA_ADMIN_SINGLE_TRASH = 'single_item_trash';
	
	/**
	 * Redirect to the Edit Tags/Categories page
	 *
	 * The custom taxonomy add/edit submenu entries go to "upload.php" by default.
	 * This filter is the only way to redirect them to the correct WordPress page.
	 *
	 * @since 0.1
	 */
	public static function mla_edit_tags_redirect( )
	{
		if ( isset( $_REQUEST['page'] ) && ( substr( $_REQUEST['page'], 0, 14 ) == 'mla-edit-tags-' ) ) {
			$taxonomy = substr( $_REQUEST['page'], 14 );
			wp_redirect( admin_url( 'edit-tags.php?taxonomy=' . $taxonomy . '&post_type=attachment' ), 302 );
			exit;
		}
	}
	
	/**
	 * Add contextual help tabs to all the MLA pages
	 *
	 * @since 0.1
	 */
	public static function mla_add_help_tab( )
	{
		$screen = get_current_screen();
		
		/*
		 * Is this one of our pages?
		 */
		if ( !array_key_exists( $screen->id, self::$page_hooks ) ) {
			return;
		}
		
		$file_suffix = $screen->id;
		
		/*
		 * Override the screen suffix if we are going to display something other than the attachment table
		 */
		if ( isset( $_REQUEST['mla_admin_action'] ) ) {
			switch ( $_REQUEST['mla_admin_action'] ) {
				case self::MLA_ADMIN_SINGLE_EDIT_DISPLAY:
					$file_suffix = self::MLA_ADMIN_SINGLE_EDIT_DISPLAY;
					break;
			} // switch
		}
		
		$template_array = MLAData::mla_load_template( MLA_PLUGIN_PATH . 'tpls/help-for-' . $file_suffix . '.tpl' );
		if ( empty( $template_array ) ) {
			return;
		}
		
		if ( !empty( $template_array['sidebar'] ) ) {
			$screen->set_help_sidebar( $template_array['sidebar'] );
			unset( $template_array['sidebar'] );
		}
		
		/*
		 * Provide explicit control over tab order
		 */
		$tab_array = array( );
		
		foreach ( $template_array as $id => $content ) {
			$match_count = preg_match( '#\<!-- title="(.+)" order="(.+)" --\>#', $content, $matches, PREG_OFFSET_CAPTURE );
			
			if ( $match_count > 0 ) {
				$tab_array[ $matches[ 2 ][ 0 ] ] = array(
					 'id' => $id,
					'title' => $matches[ 1 ][ 0 ],
					'content' => $content 
				);
			} else {
				// error_log('mla_add_help_tab discarding '.var_export($id, true), 0);
			}
		}
		
		ksort( $tab_array, SORT_NUMERIC );
		foreach ( $tab_array as $indx => $value ) {
			$screen->add_help_tab( $value );
		}
	}
	
	/**
	 * Cleanup menus for Edit Tags/Categories page
	 *
	 * The submenu entries for custom taxonomies under the "Media" menu are not set up
	 * correctly by WordPress, so this function cleans them up, redirecting the request
	 * to the right WordPress page for editing/adding taxonomy terms.
	 *
	 * @since 0.1
	 *
	 * @param	array	The top-level menu page
	 *
	 * @return	string	The updated top-level menu page
	 */
	public static function mla_modify_parent_menu( $parent_file ) {
		global $submenu;
		
		if ( isset( $_REQUEST['taxonomy'] ) ) {
			$taxonomies = get_object_taxonomies( 'attachment', 'objects' );
			
			foreach ( $taxonomies as $tax_name => $tax_object ) {
				if ( $_REQUEST['taxonomy'] == $tax_name ) {
					$mla_page = 'mla-edit-tags-' . $tax_name;
					$real_page = 'edit-tags.php?taxonomy=' . $tax_name . '&post_type=attachment';
					
					foreach ( $submenu['upload.php'] as $submenu_index => $submenu_entry ) {
						if ( $submenu_entry[ 2 ] == $mla_page ) {
							$submenu['upload.php'][ $submenu_index ][ 2 ] = $real_page;
							return 'upload.php';
						}
					}
				}
			}
		}
		
		return $parent_file;
	}
	
	/**
	 * Render the "Assistant" subpage in the Media section, using the list_table package
	 *
	 * @since 0.1
	 */
	public static function mla_render_admin_page( ) {
		$bulk_action = self::_current_bulk_action();
		
		echo "<div class=\"wrap\">\r\n";
		echo "<div id=\"icon-upload\" class=\"icon32\"><br/></div>\r\n";
		echo "<h2>Media Library Assistant"; // omit trailing </h2> for now
		
		if ( !current_user_can( 'upload_files' ) ) {
			echo " - Error</h2>\r\n";
			wp_die( __( 'You do not have permission to manage attachments.' ) );
		}
		
		$page_content = array(
			 'message' => '',
			'body' => '' 
		);
		
		/*
		 * Process bulk actions that affect an array of items
		 */
		if ( $bulk_action && ( $bulk_action != 'none' ) ) {
			echo "</h2>\r\n";
			
			if ( isset( $_REQUEST['cb_attachment'] ) ) {
				foreach ( $_REQUEST['cb_attachment'] as $index => $post_id ) {
					switch ( $bulk_action ) {
						//case 'attach':
						//case 'catagorize':
						case 'delete':
							$item_content = self::_delete_single_item( $post_id );
							break;
						//case 'edit':
						case 'restore':
							$item_content = self::_restore_single_item( $post_id );
							break;
						//case 'tag':
						case 'trash':
							$item_content = self::_trash_single_item( $post_id );
							break;
						default:
							$item_content = array(
								 'message' => sprintf( 'Unknown bulk action %s', $bulk_action ),
								'body' => '' 
							);
					} // switch $bulk_action
					
					$page_content['message'] .= $item_content['message'] . '<br>';
				} // foreach cb_attachment
			} // isset cb_attachment
			else {
				$page_content['message'] = 'Bulk Action ' . $bulk_action . ' - no items selected.';
			}
		} // $bulk_action
		
		/*
		 * Process row-level actions that affect a single item
		 */
		if ( !empty( $_REQUEST['mla_admin_action'] ) ) {
			check_admin_referer( self::MLA_ADMIN_NONCE );
			
			switch ( $_REQUEST['mla_admin_action'] ) {
				case self::MLA_ADMIN_SINGLE_DELETE:
					echo "</h2>\r\n";
					$page_content = self::_delete_single_item( $_REQUEST['mla_item_ID'] );
					break;
				case self::MLA_ADMIN_SINGLE_EDIT_DISPLAY:
					echo " - Edit single item</h2>\r\n";
					$page_content = self::_display_single_item( $_REQUEST['mla_item_ID'] );
					break;
				case self::MLA_ADMIN_SINGLE_EDIT_UPDATE:
					echo "</h2>\r\n";
					if ( !empty( $_REQUEST['update'] ) ) {
						$page_content = self::_update_single_item( $_REQUEST['mla_item_ID'], $_REQUEST['attachments'][ $_REQUEST['mla_item_ID'] ], $_REQUEST['tax_input'] );
					} else {
						$page_content = array(
							 'message' => 'Item: ' . $_REQUEST['mla_item_ID'] . ' cancelled.',
							'body' => '' 
						);
					}
					break;
				case self::MLA_ADMIN_SINGLE_RESTORE:
					echo "</h2>\r\n";
					$page_content = self::_restore_single_item( $_REQUEST['mla_item_ID'] );
					break;
				case self::MLA_ADMIN_SINGLE_TRASH:
					echo "</h2>\r\n";
					$page_content = self::_trash_single_item( $_REQUEST['mla_item_ID'] );
					break;
				default:
					echo "</h2>\r\n";
					$page_content = array(
						 'message' => sprintf( 'Unknown mla_admin_action - "%1$s"', $_REQUEST['mla_admin_action'] ),
						'body' => '' 
					);
					break;
			} // switch ($_REQUEST['mla_admin_action'])
		} // (!empty($_REQUEST['mla_admin_action'])
		
		if ( !empty( $page_content['message'] ) ) {
			echo "  <div style=\"background:#ECECEC;border:1px solid #CCC;padding:0 10px;margin-top:5px;border-radius:5px;-moz-border-radius:5px;-webkit-border-radius:5px;\"><p>\r\n";
			echo '    ' . $page_content['message'] . "\r\n";
			echo "  </p></div>\r\n"; // id="message"
		}
		
		if ( !empty( $page_content['body'] ) ) {
			echo $page_content['body'];
		} else {
			/*
			 * Display Attachments list
			 */
			
			$_SERVER['REQUEST_URI'] = remove_query_arg( array(
				'mla_admin_action',
				'mla_item_ID',
				'_wpnonce',
				'_wp_http_referer',
				'action',
				'action2',
				'cb_attachment' 
			), $_SERVER['REQUEST_URI'] );
			
			if ( !empty( $_REQUEST['heading_suffix'] ) ) {
				echo ' - ' . $_REQUEST['heading_suffix'] . "</h2>\r\n";
			} else
				echo "</h2>\r\n";
			
			//	Create an instance of our package class...
			$MLAListTable = new MLA_List_Table();
			
			//	Fetch, prepare, sort, and filter our data...
			$MLAListTable->prepare_items();
			$MLAListTable->views();
			
			//	 Forms are NOT created automatically, so you need to wrap the table in one to use features like bulk actions
			echo '<form id="mla-filter" action="' . admin_url( 'upload.php' ) . "\" method=\"get\">\r\n";
			
			/*
			 * We also need to ensure that the form posts back to our current page and remember all the view arguments
			 */
			echo sprintf( '<input type="hidden" name="page" value="%1$s" />', $_REQUEST['page'] ) . "\r\n";
			
			if ( isset( $_REQUEST['detached'] ) ) // Unattached items
				echo sprintf( '<input type="hidden" name="detached" value="%1$s" />', $_REQUEST['detached'] ) . "\r\n";
			
			if ( isset( $_REQUEST['status'] ) ) // Trash items
				echo sprintf( '<input type="hidden" name="status" value="%1$s" />', $_REQUEST['status'] ) . "\r\n";
			
			if ( isset( $_REQUEST['post_mime_type'] ) ) // e.g., Images
				echo sprintf( '<input type="hidden" name="post_mime_type" value="%1$s" />', $_REQUEST['post_mime_type'] ) . "\r\n";
			
			if ( isset( $_REQUEST['m'] ) ) // filter by date
				echo sprintf( '<input type="hidden" name="m" value="%1$s" />', $_REQUEST['m'] ) . "\r\n";
			
			if ( isset( $_REQUEST['att_cat'] ) ) // filter by category
				echo sprintf( '<input type="hidden" name="att_cat" value="%1$s" />', $_REQUEST['att_cat'] ) . "\r\n";
			
			//	 Now we can render the completed list table
			$MLAListTable->display();
			echo "</form>\r\n</div>\r\n";
		}
	}
	
	/**
	 * Get the current action selected from the bulk actions dropdown.
	 *
	 * @since 0.1
	 *
	 * @return string|bool The action name or False if no action was selected
	 */
	private static function _current_bulk_action( )
	{
		$action = false;
		
		if ( isset( $_REQUEST['action'] ) ) {
			if ( -1 != $_REQUEST['action'] )
				return $_REQUEST['action'];
			else
				$action = 'none';
		} // isset action
		
		if ( isset( $_REQUEST['action2'] ) ) {
			if ( -1 != $_REQUEST['action2'] )
				return $_REQUEST['action2'];
			else
				$action = 'none';
		} // isset action2
		
		return $action;
	}
	
	/**
	 * Delete a single item permanently.
	 * 
	 * @since 0.1
	 * 
	 * @param	array The form POST data.
	 * @return	array success/failure message and null content
	 */
	private static function _delete_single_item( $post_id ) {
		if ( !current_user_can( 'delete_post', $post_id ) )
			return array(
				'message' => 'ERROR: You are not allowed to delete this item.',
				'body' => '' 
			);
		
		if ( !wp_delete_attachment( $post_id, true ) )
			return array(
				'message' => 'ERROR: Item ' . $post_id . ' could NOT be deleted.',
				'body' => '' 
			);
		
		return array(
			'message' => 'Item: ' . $post_id . ' permanently deleted.',
			'body' => '' 
		);
	}
	
	/**
	 * Display a single item sub page; prepare the form to 
	 * change the meta data for a single attachment.
	 * 
	 * @since 0.1
	 * 
	 * @param	int		The WordPress Post ID of the attachment item
	 * @return	array	message and/or HTML content. 
	 */
	private static function _display_single_item( $post_id ) {
		global $post;
		
		/*
		 * This function sets the global $post
		 */
		$post_data = MLAData::mla_get_attachment_by_id( $post_id );
		
		if ( !isset( $post_data ) )
			return array(
				 'message' => 'ERROR: Could not retrieve Attachment.',
				'body' => '' 
			);
		
		if ( !empty( $post_data['mla_wp_attachment_metadata'] ) ) {
			$page_template_array = MLAData::mla_load_template( MLA_PLUGIN_PATH . 'tpls/admin-display-single-image.tpl' );
			$width = $post_data['mla_wp_attachment_metadata']['width'];
			$height = $post_data['mla_wp_attachment_metadata']['height'];
			$image_meta = var_export( $post_data['mla_wp_attachment_metadata'], true );
			
			if ( !isset( $post_data['mla_wp_attachment_image_alt'] ) )
				$post_data['mla_wp_attachment_image_alt'] = '';
		} else {
			$page_template_array = MLAData::mla_load_template( MLA_PLUGIN_PATH . 'tpls/admin-display-single-document.tpl' );
			$width = '';
			$height = '';
			$image_meta = '';
		}
		
		if ( array(
			 $page_template_array 
		) ) {
			$page_template = $page_template_array['page'];
			$postbox_template = $page_template_array['postbox'];
		} else {
			error_log( "ERROR: MLA::_display_single_item \$page_template_array = " . var_export( $page_template_array, true ), 0 );
			$page_template = $page_template_array;
			$postbox_template = '';
		}
		
		if ( $post_data['mla_references']['found_parent'] ) {
			$parent_info = sprintf( '(%1$s) %2$s', $post_data['mla_references']['parent_type'], $post_data['mla_references']['parent_title'] );
		} else {
			$parent_info = '';
			if ( !$post_data['mla_references']['found_reference'] )
				$parent_info .= '(ORPHAN) ';
			
			if ( $post_data['mla_references']['is_unattached'] )
				$parent_info .= '(UNATTACHED) ';
			else {
				if ( !$post_data['mla_references']['found_parent'] )
					$parent_info .= '(BAD PARENT) ';
			}
		}
		
		$features = '';
		
		foreach ( $post_data['mla_references']['features'] as $feature_id => $feature ) {
			if ( $feature_id == $post_data['post_parent'] )
				$parent = 'PARENT ';
			else
				$parent = '';
			
			$features .= sprintf( '%1$s (%2$s %3$s), %4$s', /*$1%s*/ $parent, /*$2%s*/ $feature->post_type, /*$3%s*/ $feature_id, /*$4%s*/ $feature->post_title ) . "\r\n";
		} // foreach $feature
		
		$inserts = '';
		
		foreach ( $post_data['mla_references']['inserts'] as $file => $insert_array ) {
			$inserts .= $file . "\r\n";
			
			foreach ( $insert_array as $insert ) {
				if ( $insert->ID == $post_data['post_parent'] )
					$parent = '  PARENT ';
				else
					$parent = '  ';
				
				$inserts .= sprintf( '%1$s (%2$s %3$s), %4$s', /*$1%s*/ $parent, /*$2%s*/ $insert->post_type, /*$3%s*/ $insert->ID, /*$4%s*/ $insert->post_title ) . "\r\n";
			} // foreach $insert
		} // foreach $file
		
		/*
		 * WordPress doesn't look in hidden fields to set the month filter dropdown
		 */
		if ( isset( $_REQUEST['m'] ) )
			$month = '&m=' . $_REQUEST['m'];
		else
			$month = '';
		
		/*
		 * Add the current view arguments
		 */
		if ( isset( $_REQUEST['detached'] ) )
			$view_args = '<input type="hidden" name="detached" value="' . $_REQUEST['detached'] . "\" />\r\n";
		elseif ( isset( $_REQUEST['status'] ) )
			$view_args = '<input type="hidden" name="status" value="' . $_REQUEST['status'] . "\" />\r\n";
		elseif ( isset( $_REQUEST['post_mime_type'] ) )
			$view_args = '<input type="hidden" name="post_mime_type" value="' . $_REQUEST['post_mime_type'] . "\" />\r\n";
		else
			$view_args = '';
		
		if ( isset( $_REQUEST['att_cat'] ) )
			$view_args .= sprintf( '<input type="hidden" name="att_cat" value="%1$s" />', $_REQUEST['att_cat'] ) . "\r\n";
		
		$side_info_column = '';
		$taxonomies = get_object_taxonomies( 'attachment', 'objects' );
		
		foreach ( $taxonomies as $tax_name => $tax_object ) {
			ob_start();
			
			if ( $tax_object->hierarchical ) {
				$box = array(
					 'id' => $tax_name . 'div',
					'title' => $tax_object->labels->name,
					'callback' => 'categories_meta_box',
					'args' => array(
						 'taxonomy' => $tax_name 
					),
					'inside_html' => '' 
				);
				post_categories_meta_box( $post, $box );
			} else {
				$box = array(
					 'id' => 'tagsdiv-' . $tax_name,
					'title' => $tax_object->labels->name,
					'callback' => 'post_tags_meta_box',
					'args' => array(
						 'taxonomy' => $tax_name 
					),
					'inside_html' => '' 
				);
				post_tags_meta_box( $post, $box );
			}
			
			$box['inside_html'] = ob_get_contents();
			ob_end_clean();
			$side_info_column .= MLAData::mla_parse_template( $postbox_template, $box );
		}
		
		$page_values = array(
			'attachment_icon' => wp_get_attachment_image( $post_id, array( 160, 120 ), true ),
			'file_name' => $post_data['mla_references']['file'],
			'width' => $width,
			'height' => $height,
			'image_meta' => $image_meta,
			'parent_info' => $parent_info,
			'features' => $features,
			'inserts' => $inserts,
			'mla_admin_action' => self::MLA_ADMIN_SINGLE_EDIT_UPDATE,
			'form_url' => admin_url( 'upload.php' ) . '?page=' . self::ADMIN_PAGE_SLUG . $month,
			'view_args' => $view_args,
			'_wpnonce' => wp_nonce_field( self::MLA_ADMIN_NONCE, '_wpnonce', true, false ),
			'side_info_column' => $side_info_column 
		);
		
		$page_template = MLAData::mla_parse_template( $page_template, $post_data );
		return array(
			 'message' => '',
			'body' => MLAData::mla_parse_template( $page_template, $page_values ) 
		);
	}
	
	/**
	 * Update a single item; change the meta data 
	 * for a single attachment.
	 * 
	 * @since 0.1
	 * 
	 * @param	int		The ID of the attachment to be updated
	 * @param	array	Field name => value pairs
	 * @param	array	Attachment Category and Tag values
	 * @return	array	success/failure message and null content
	 */
	private static function _update_single_item( $post_id, $new_data, $tax_input = null ) {
		$post_data = MLAData::mla_get_attachment_by_id( $post_id );
		
		if ( !isset( $post_data ) )
			return array(
				'message' => 'ERROR: Could not retrieve Attachment.',
				'body' => '' 
			);
		
		$message = '';
		$updates = array( 'ID' => $post_id );
		
		foreach ( $new_data as $key => $value ) {
			switch ( $key ) {
				case 'post_title':
					if ( $value == $post_data[ $key ] )
						break;
					$message .= sprintf( 'Changing Title from "%1$s" to "%2$s"<br>', $post_data[ $key ], $value );
					$updates[ $key ] = $value;
					break;
				case 'post_name':
					if ( $value == $post_data[ $key ] )
						break;
					
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
						$message .= sprintf( 'Changing Name/Slug from "%1$s" to "%2$s"<br>', $post_data[ $key ], $value );
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
							$message .= sprintf( 'Deleting Alternate Text, was "%1$s"<br>', $post_data[ $key ] );
						else
							$message .= sprintf( 'ERROR: Could not delete Alternate Text, remains "%1$s"<br>', $post_data[ $key ] );
					} else {
						if ( update_post_meta( $post_id, '_wp_attachment_image_alt', $value ) )
							$message .= sprintf( 'Changing Alternate Text from "%1$s" to "%2$s"<br>', $post_data[ $key ], $value );
						else
							$message .= sprintf( 'ERROR: Could not change Alternate Text from "%1$s" to "%2$s"<br>', $post_data[ $key ], $value );
					}
					break;
				case 'post_excerpt':
					if ( $value == $post_data[ $key ] )
						break;
					$message .= sprintf( 'Changing Caption from "%1$s" to "%2$s"<br>', $post_data[ $key ], $value );
					$updates[ $key ] = $value;
					break;
				case 'post_content':
					if ( $value == $post_data[ $key ] )
						break;
					$message .= sprintf( 'Changing Description from "%1$s" to "%2$s"<br>', $post_data[ $key ], $value );
					$updates[ $key ] = $value;
					break;
				case 'post_parent':
					if ( $value == $post_data[ $key ] )
						break;
					$message .= sprintf( 'Changing Parent from "%1$s" to "%2$s"<br>', $post_data[ $key ], $value );
					$updates[ $key ] = $value;
					break;
				default:
			} // switch $key
		} // foreach $new_data
		
		if ( !empty( $tax_input ) ) {
			foreach ( $tax_input as $taxonomy => $tags ) {
			  $taxonomy_obj = get_taxonomy( $taxonomy );
			  $terms_before = wp_get_post_terms( $post_id, $taxonomy, array(
				   "fields" => "all" 
			  ) );
			  if ( is_array( $tags ) ) // array = hierarchical, string = non-hierarchical.
				  $tags = array_filter( $tags );
			  
			  if ( current_user_can( $taxonomy_obj->cap->assign_terms ) ) {
				  $result = wp_set_post_terms( $post_id, $tags, $taxonomy );
			  }
			  
			  $terms_after = wp_get_post_terms( $post_id, $taxonomy, array(
				   "fields" => "all" 
			  ) );
			  
			  if ( $terms_before != $terms_after )
				  $message .= sprintf( 'Changing "%1$s" terms<br>', $taxonomy );
			}
		}
		
		if ( empty( $message ) )
			return array(
				'message' => 'Item: ' . $post_id . ', no changes detected.',
				'body' => '' 
			);
		else {
			if ( wp_update_post( $updates ) )
				return array(
					'message' => 'Item: ' . $post_id . ' updated.<br>' . $message,
					'body' => '' 
				);
			else
				return array(
					'message' => 'ERROR: Item ' . $post_id . ' update failed.',
					'body' => '' 
				);
		}
	}
	
	/**
	 * Restore a single item from the Trash
	 * 
	 * @since 0.1
	 * 
	 * @param	array	The form POST data
	 *
	 * @return	array	success/failure message and null content
	 */
	private static function _restore_single_item( $post_id ) {
		if ( !current_user_can( 'delete_post', $post_id ) )
			return array(
				'message' => 'ERROR: You are not allowed to move this item out of the Trash.',
				'body' => '' 
			);
		
		if ( !wp_untrash_post( $post_id ) )
			return array(
				'message' => 'ERROR: Item ' . $post_id . ' could NOT be restored from Trash.',
				'body' => '' 
			);
		
		/*
		 * Posts are restored to "draft" status, so this must be updated.
		 */
		$update_post = array( );
		$update_post['ID'] = $post_id;
		$update_post['post_status'] = 'inherit';
		wp_update_post( $update_post );
		
		return array(
			'message' => 'Item: ' . $post_id . ' restored from Trash.',
			'body' => '' 
		);
	}
	
	/**
	 * Move a single item to Trash
	 * 
	 * @since 0.1
	 * 
	 * @param	array	The form POST data
	 * @return	array	success/failure message and null content
	 */
	private static function _trash_single_item( $post_id ) {
		if ( !current_user_can( 'delete_post', $post_id ) )
			return array(
				'message' => 'ERROR: You are not allowed to move this item to the Trash.',
				'body' => '' 
			);
		
		if ( !wp_trash_post( $post_id, false ) )
			return array(
				'message' => 'ERROR: Item ' . $post_id . ' could NOT be moved to Trash.',
				'body' => '' 
			);
		
		return array(
			'message' => 'Item: ' . $post_id . ' moved to Trash.',
			'body' => '' 
		);
	}
	
} // class MLA
?>