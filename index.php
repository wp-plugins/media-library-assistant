<?php
/**
 * Provides several enhancements to the handling of images and files held in the WordPress Media Library
 *
 * @package Media Library Assistant
 * @version 0.11
 */

/*
Plugin Name: Media Library Assistant
Plugin URI: http://home.comcast.net/~dlingren/
Description: Provides several enhancements to the handling of images and files held in the WordPress Media Library.
Author: David Lingren
Version: 0.11
Author URI: http://home.comcast.net/~dlingren/
*/

/**
 * Provides path information to the plugin root in file system format.
 */
define('MLA_PLUGIN_PATH', plugin_dir_path(__FILE__));

/**
 * Provides path information to the plugin root in URL format.
 */
define('MLA_PLUGIN_URL', plugin_dir_url(__FILE__));

if (!defined('MLA_OPTION_PREFIX'))
	/**
	 * Gives a unique prefix for plugin options; can be set in wp-config.php
	 */
	define('MLA_OPTION_PREFIX', 'mla_');

/*
 * Template file and database access functions.
 */
require_once('includes/class-mla-data.php');

add_action('init', 'MLAData::initialize');

/*
 * Custom list table package that extends the core WP_List_Table class.
 */
require_once('includes/class-mla-list-table.php');

/*
 * Custom Taxonomies and WordPress objects.
 */
require_once('includes/mla-objects.php');

/*
 * Shortcodes
 */
require_once('includes/mla-shortcodes.php');

/*
 * Basic library of run-time tests.
 */
require_once('tests/class-mla-tests.php');

/*
 * Plugin settings and management page
 */
require_once('includes/class-mla-settings.php');

add_action('init', 'MLASettings::initialize');
 
/*
 * Main program
 */
require_once('includes/class-mla-main.php');

add_action('init', 'MLA::initialize');
?>
