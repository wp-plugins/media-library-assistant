<?php
/**
 * Media Library Assistant Plugin Loader
 *
 * Defines constants and loads all of the classes and functions required to run the plugin.
 * This file is only loaded if the naming conflict tests in index.php are passed.
 *
 * @package Media Library Assistant
 * @since 0.20
 */

defined( 'ABSPATH' ) or die();

if ( ! defined('MLA_OPTION_PREFIX') ) {
	/**
	 * Gives a unique prefix for plugin options; can be set in wp-config.php
	 */
	define('MLA_OPTION_PREFIX', 'mla_');
}

if ( ! defined('MLA_DEBUG_LEVEL') ) {
	/**
	 * Activates debug options; can be set in wp-config.php
	 */
	define('MLA_DEBUG_LEVEL', 0);
}

/**
 * Accumulates error messages from name conflict tests
 *
 * @since 1.14
 */
$mla_plugin_loader_error_messages = '';
 
/**
 * Displays version conflict error messages at the top of the Dashboard
 *
 * @since 1.14
 */
function mla_plugin_loader_reporting_action () {
	global $mla_plugin_loader_error_messages;

	echo '<div class="error"><p><strong>' . __( 'The Media Library Assistant cannot load.', 'media-library-assistant' ) . '</strong></p>'."\r\n";
	echo "<ul>{$mla_plugin_loader_error_messages}</ul>\r\n";
	echo '<p>' . __( 'You must resolve these conflicts before this plugin can safely load.', 'media-library-assistant' ) . '</p></div>'."\r\n";
}
//error_log( __LINE__ . ' MEMORY mla-plugin-loader.php ' . number_format( memory_get_peak_usage( true ) ), 0);

/*
 * Basic library of run-time tests.
 */
require_once( MLA_PLUGIN_PATH . 'tests/class-mla-tests.php' );
//error_log( __LINE__ . ' MEMORY mla-plugin-loader.php class-mla-tests.php ' . number_format( memory_get_peak_usage( true ) ), 0);

$mla_plugin_loader_error_messages .= MLATest::min_php_version( '5.2' );
$mla_plugin_loader_error_messages .= MLATest::min_WordPress_version( '3.5.0' );

if ( ! empty( $mla_plugin_loader_error_messages ) ) {
	add_action( 'admin_notices', 'mla_plugin_loader_reporting_action' );
} else {
//error_log( __LINE__ . ' MEMORY mla-plugin-loader.php DOING_AJAX = ' . var_export( defined('DOING_AJAX') && DOING_AJAX, true ), 0);
//error_log( __LINE__ . ' MEMORY mla-plugin-loader.php WP_ADMIN = ' . var_export( defined('WP_ADMIN') && WP_ADMIN, true ), 0);
//error_log( __LINE__ . ' MEMORY mla-plugin-loader.php is_admin() = ' . var_export( is_admin(), true ), 0);
	/*
	 * MLATest is loaded above
	 */
	add_action( 'init', 'MLATest::initialize', 0x7FFFFFFF );

	/*
	 * Minimum support functions required by all other components
	 */
	require_once( MLA_PLUGIN_PATH . 'includes/class-mla-core.php' );
	add_action( 'init', 'MLACore::initialize', 0x7FFFFFFF );
//error_log( __LINE__ . ' MEMORY mla-plugin-loader.php class-mla-core.php ' . number_format( memory_get_peak_usage( true ) ), 0);

	if( defined('DOING_AJAX') && DOING_AJAX ) {
		require_once( MLA_PLUGIN_PATH . 'includes/class-mla-data-query.php' );
		add_action( 'init', 'MLAQuery::initialize', 0x7FFFFFFF );
//error_log( __LINE__ . ' MEMORY mla-plugin-loader.php class-mla-data-query.php ' . number_format( memory_get_peak_usage( true ) ), 0);
		
		/*
		 * Ajax handlers
		 */
		require_once( MLA_PLUGIN_PATH . 'includes/class-mla-ajax.php' );
		add_action( 'init', 'MLA_Ajax::initialize', 0x7FFFFFFF );
//error_log( __LINE__ . ' MEMORY mla-plugin-loader.php class-mla-ajax.php ' . number_format( memory_get_peak_usage( true ) ), 0);
	
//return;
	}

	/*
	 * Template file and database access functions.
	 */
	require_once( MLA_PLUGIN_PATH . 'includes/class-mla-data-query.php' );
	add_action( 'init', 'MLAQuery::initialize', 0x7FFFFFFF );
//error_log( __LINE__ . ' MEMORY mla-plugin-loader.php class-mla-data-query.php ' . number_format( memory_get_peak_usage( true ) ), 0);
		
	require_once( MLA_PLUGIN_PATH . 'includes/class-mla-data.php' );
//error_log( __LINE__ . ' MEMORY mla-plugin-loader.php class-mla-data.php ' . number_format( memory_get_peak_usage( true ) ), 0);
	add_action( 'init', 'MLAData::initialize', 0x7FFFFFFF );
	require_once( MLA_PLUGIN_PATH . 'includes/class-mla-data-pdf.php' );
//error_log( __LINE__ . ' MEMORY mla-plugin-loader.php class-mla-data-pdf.php ' . number_format( memory_get_peak_usage( true ) ), 0);

	/*
	 * MIME Type functions.
	 */
	require_once( MLA_PLUGIN_PATH . 'includes/class-mla-mime-types.php' );
//error_log( __LINE__ . ' MEMORY mla-plugin-loader.php class-mla-mime-types.php ' . number_format( memory_get_peak_usage( true ) ), 0);
	add_action( 'init', 'MLAMime::initialize', 0x7FFFFFFF );

	/*
	 * Shortcodes
	 */
	require_once( MLA_PLUGIN_PATH . 'includes/class-mla-shortcodes.php' );
//error_log( __LINE__ . ' MEMORY mla-plugin-loader.php class-mla-shortcodes.php ' . number_format( memory_get_peak_usage( true ) ), 0);
	add_action( 'init', 'MLAShortcodes::initialize', 0x7FFFFFFF );

	/*
	 * Plugin settings management
	 */
	require_once( MLA_PLUGIN_PATH . 'includes/class-mla-options.php' );
//error_log( __LINE__ . ' MEMORY mla-plugin-loader.php class-mla-options.php ' . number_format( memory_get_peak_usage( true ) ), 0);
	add_action( 'init', 'MLAOptions::initialize', 0x7FFFFFFF );
	 
	/*
	 * Plugin settings management page
	 */
	require_once( MLA_PLUGIN_PATH . 'includes/class-mla-settings.php' );
//error_log( __LINE__ . ' MEMORY mla-plugin-loader.php class-mla-settings.php ' . number_format( memory_get_peak_usage( true ) ), 0);
	add_action( 'init', 'MLASettings::initialize', 0x7FFFFFFF );

	/*
	 * Main program
	 */
	require_once( MLA_PLUGIN_PATH . 'includes/class-mla-main.php' );
//error_log( __LINE__ . ' MEMORY mla-plugin-loader.php class-mla-main.php ' . number_format( memory_get_peak_usage( true ) ), 0);
	add_action( 'init', 'MLA::initialize', 0x7FFFFFFF );
	add_action( 'plugins_loaded', 'MLA::mla_plugins_loaded_action', 0x7FFFFFFF );

//if ( ! is_admin() ) return;

	/*
	 * Edit Media screen additions, e.g., meta boxes
	 */
	require_once( MLA_PLUGIN_PATH . 'includes/class-mla-edit-media.php' );
//error_log( __LINE__ . ' MEMORY mla-plugin-loader.php class-mla-edit-media.php ' . number_format( memory_get_peak_usage( true ) ), 0);
	add_action( 'init', 'MLAEdit::initialize', 0x7FFFFFFF );

	/*
	 * Media Manager (Modal window) additions
	 */
	require_once( MLA_PLUGIN_PATH . 'includes/class-mla-media-modal.php' );
//error_log( __LINE__ . ' MEMORY mla-plugin-loader.php class-mla-media-modal.php ' . number_format( memory_get_peak_usage( true ) ), 0);
	add_action( 'init', 'MLAModal::initialize', 0x7FFFFFFF );

	/*
	 * Custom list table package that extends the core WP_List_Table class.
	 * Doesn't need an initialize function; has a constructor.
	 */
	require_once( MLA_PLUGIN_PATH . 'includes/class-mla-list-table.php' );
//error_log( __LINE__ . ' MEMORY mla-plugin-loader.php class-mla-list-table.php ' . number_format( memory_get_peak_usage( true ) ), 0);

	/*
	 * Custom list table package for the Post MIME Type Views.
	 * Doesn't need an initialize function; has a constructor.
	 */
	require_once( MLA_PLUGIN_PATH . 'includes/class-mla-view-list-table.php' );
//error_log( __LINE__ . ' MEMORY mla-plugin-loader.php class-mla-view-list-table.php ' . number_format( memory_get_peak_usage( true ) ), 0);

	/*
	 * Custom list table package for the Optional Upload MIME Type Views.
	 * Doesn't need an initialize function; has a constructor.
	 */
	require_once( MLA_PLUGIN_PATH . 'includes/class-mla-upload-optional-list-table.php' );
//error_log( __LINE__ . ' MEMORY mla-plugin-loader.php class-mla-upload-optional-list-table.php ' . number_format( memory_get_peak_usage( true ) ), 0);

	/*
	 * Custom list table package for the Upoload MIME Type Views.
	 * Doesn't need an initialize function; has a constructor.
	 */
	require_once( MLA_PLUGIN_PATH . 'includes/class-mla-upload-list-table.php' );
//error_log( __LINE__ . ' MEMORY mla-plugin-loader.php class-mla-upload-list-table.php ' . number_format( memory_get_peak_usage( true ) ), 0);
}
?>