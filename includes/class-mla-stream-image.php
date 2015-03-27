<?php
/**
 * Ajax handler for the mla_viewer
 *
 * @package Media Library Assistant
 * @since 2.10
 */

/**
 * Class MLA (Media Library Assistant) Stream Image provides PDF thumbnails.
 *
 * @package Media Library Assistant
 * @since 2.10
 */
class MLAStreamImage {
	/**
	 * Action name; uniquely identifies the nonce
	 *
	 * @since 2.10
	 *
	 * @var	string
	 */
	const MLA_ADMIN_NONCE = 'mla_admin';

	/**
	 * Load the plugin's Ajax handler
	 *
	 * @since 2.10
	 *
	 * @return	void
	 */
	public static function initialize() {
		/*
		 * Process mla_viewer image stream requests
		 */
		if ( isset( $_REQUEST['mla_stream_file'] ) ) {
			check_admin_referer( self::MLA_ADMIN_NONCE );
			self::_process_mla_stream_image();
			exit();
		}
	}
	
	/**
	 * Process Imagick image stream request, e.g., for a PDF thumbnail
	 *
	 * Requires _wpnonce and mla_stream_file (relative to wp_upload_dir ) in $_REQUEST; optional parameters are:
	 * 		mla_stream_width, mla_stream_height, mla_stream_frame, mla_stream_type
	 *
	 * @since 2.10
	 *
	 * @return	void	echos image content and calls exit();
	 */
	public static function _wp_image_editors_filter( $editors ) {
		array_unshift( $editors, "MLA_Image_Editor" );

		return $editors;
	}

	/**
	 * Holds Imagick image resolution for class MLA_Image_Editor
	 *
	 * @since 2.10
	 *
	 * @var	array
	 */
	public static $mla_imagick_args = array();

	/**
	 * Process Imagick image stream request, e.g., for a PDF thumbnail
	 *
	 * Requires _wpnonce and mla_stream_file (relative to wp_upload_dir ) in $_REQUEST;
	 * optional $_REQUEST parameters are:
	 * 		mla_stream_width, mla_stream_height, mla_stream_frame,
	 *		mla_stream_resolution, mla_stream_type
	 *
	 * @since 2.10
	 *
	 * @return	void	echos image content and calls exit();
	 */
	private static function _process_mla_stream_image() {
//error_log( __LINE__ . " _process_mla_stream_image \$_REQUEST =  " . var_export( $_REQUEST, true ), 0 );
//error_log( __LINE__ . " _process_mla_stream_image memory_get_usage =  " . var_export( memory_get_usage ( true ), true ), 0 );
		if ( isset( $_REQUEST['mla_stream_file'] ) ) {
			if( ini_get( 'zlib.output_compression' ) ) { 
				ini_set( 'zlib.output_compression', 'Off' );
			}
			
			$file = stripslashes( $_REQUEST['mla_stream_file'] );
			$width = isset( $_REQUEST['mla_stream_width'] ) ? absint( $_REQUEST['mla_stream_width'] ) : 0;
			$height = isset( $_REQUEST['mla_stream_height'] ) ? absint( $_REQUEST['mla_stream_height'] ) : 0;
			$type = isset( $_REQUEST['mla_stream_type'] ) ? stripslashes( $_REQUEST['mla_stream_type'] ) : 'image/jpeg';

			/*
			 * Frame and resolution are handled in the load() function, which we
			 * can't explicitly pass parameters to.
			 */
			if ( isset( $_REQUEST['mla_stream_frame'] ) ) {
				self::$mla_imagick_args['frame'] = absint( $_REQUEST['mla_stream_frame'] );
			}
			
			if ( isset( $_REQUEST['mla_stream_resolution'] ) ) {
				self::$mla_imagick_args['resolution'] = absint( $_REQUEST['mla_stream_resolution'] );
			}
			
			$upload_dir = wp_upload_dir();
			$file = $upload_dir['basedir'] . '/' . $file;

			if ( ! file_exists( $file ) ) {
				wp_die( 'File not found', '', array( 'response' => 404 ) );
			}
			
			/*
			 * Supplementary Imagick functions for the image editor
			 */
			require_once ABSPATH . WPINC . '/class-wp-image-editor.php';
			require_once ABSPATH . WPINC . '/class-wp-image-editor-gd.php';
			require_once ABSPATH . WPINC . '/class-wp-image-editor-imagick.php';

			require_once( MLA_PLUGIN_PATH . 'includes/class-mla-image-editor.php' );
			
			try {
				add_filter( 'wp_image_editors', 'MLAStreamImage::_wp_image_editors_filter', 10, 1 );
				$image_editor = wp_get_image_editor( $file );
				remove_filter( 'wp_image_editors', 'MLAStreamImage::_wp_image_editors_filter', 10 );
				
				if ( is_wp_error( $image_editor ) ) {
error_log( 'Image load failure = ' . var_export( $image_editor->get_error_messages(), true ), 0 );
					wp_die( 'File not loaded', '', array( 'response' => 404 ) );
				}
				
				/*
				 * Prepare the output image; resize and flatten, if necessary
				 */
				if ( isset( $_REQUEST['mla_stream_fit'] ) ) {
					$best_fit = ( '1' == $_REQUEST['mla_stream_fit'] );
				} else {
					$best_fit = false;
				}
				
				$image_editor->mla_prepare_image( $width, $height, $best_fit, $type );
				}
			catch ( Exception $e ) {
error_log( 'Image load Exception = ' . var_export( $e->getMessage(), true ), 0 );
				wp_die( 'Image load exception', '', array( 'response' => 404 ) );
			}

			try {
				$image_editor->stream( $type );
			}
			catch ( Exception $e ) {
error_log( 'stream Exception = ' . var_export( $e->getMessage(), true ), 0 );
				wp_die( 'Image stream exception', '', array( 'response' => 404 ) );
			}
			
			unset( $image_editor );
			exit();
		} else {
			wp_die( 'mla_stream_file not set', '', array( 'response' => 404 ) );
		}

		// Should not be possible
		wp_die( '_process_mla_stream_image error', '', array( 'response' => 500 ) );
	}
} // Class MLAStreamImage
?>