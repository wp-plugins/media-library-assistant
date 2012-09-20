<?php
/**
 * Provides basic run-time tests to ensure the plugin can run in the current WordPress envrionment
 *
 * @package Media Library Assistant
 * @since 0.1
 */

/**
 * Class MLA (Media Library Assistant) Test provides basic run-time tests
 * to ensure the plugin can run in the current WordPress envrionment.
 *
 * @package Media Library Assistant
 * @since 0.1
 */
class MLATest {
	/**
	 * Test that your PHP version is at least that of the $min_php_version
	 *
	 * @since 0.1
	 *
	 * @param 	string	representing the minimum required version of PHP, e.g. '5.3.2'
	 * @param  	string	Name of the plugin for messaging purposes
	 *
	 * @return void		Exit with messaging if PHP version is too old
	 */
	public static function min_php_version( $min_version, $plugin_name )
	{
		$current_version = phpversion();
		$exit_msg = sprintf( 'The "%1$s" plugin requires PHP %2$s or newer; you have %3$s.<br />Contact your system administrator about updating your version of PHP.', /*$1%s*/ $plugin_name, /*$2%s*/ $min_version, /*$3%s*/ $current_version );
		
		if ( version_compare( $current_version, $min_version, '<' ) ) {
			wp_die( $exit_msg );
		}
	}
	
	/**
	 * Test that your WordPress version is at least that of the $min_version
	 *
	 * @since 0.1
	 *
	 * @param string	representing the minimum required version of WordPress, e.g. '3.3.0'
	 * @param string	Name of the plugin for messaging purposes
	 *
	 * @return void		Exit with messaging if version is too old
	 */
	public static function min_WordPress_version( $min_version, $plugin_name )
	{
		$current_version = get_bloginfo( 'version' );
		$exit_msg = sprintf( 'The "%1$s" plugin requires WordPress %2$s or newer; you have %3$s.<br />Contact your system administrator about updating your version of WordPress.', /*$1%s*/ $plugin_name, /*$2%s*/ $min_version, /*$3%s*/ $current_version );
		
		if ( version_compare( $current_version, $min_version, '<' ) ) {
			wp_die( $exit_msg );
		}
	}
	
} // class MLATest
?>