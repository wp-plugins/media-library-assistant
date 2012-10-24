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

if (!defined('MLA_OPTION_PREFIX'))
	/**
	 * Gives a unique prefix for plugin options; can be set in wp-config.php
	 */
	define('MLA_OPTION_PREFIX', 'mla_');

/*
 * Basic library of run-time tests.
 */
require_once( MLA_PLUGIN_PATH . 'tests/class-mla-tests.php' );

add_action( 'init', 'MLATest::initialize' );

/*
 * Template file and database access functions.
 */
require_once( MLA_PLUGIN_PATH . 'includes/class-mla-data.php' );

add_action( 'init', 'MLAData::initialize' );

/*
 * Custom Taxonomies and WordPress objects.
 */
require_once( MLA_PLUGIN_PATH . 'includes/class-mla-objects.php' );

add_action('init', 'MLAObjects::initialize');

/*
 * Shortcodes
 */
require_once( MLA_PLUGIN_PATH . 'includes/class-mla-shortcodes.php');

add_action('init', 'MLAShortcodes::initialize');

/*
 * Plugin settings and management page
 */
require_once( MLA_PLUGIN_PATH . 'includes/class-mla-settings.php' );

add_action( 'init', 'MLASettings::initialize' );
 
/*
 * Custom list table package that extends the core WP_List_Table class.
 */
require_once( MLA_PLUGIN_PATH . 'includes/class-mla-list-table.php' );

/*
 * Main program
 */
require_once( MLA_PLUGIN_PATH . 'includes/class-mla-main.php');

add_action('init', 'MLA::initialize');
?>