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
	 *		mla_stream_resolution, mla_stream_quality, mla_stream_type
	 *
	 * @since 2.10
	 *
	 * @return	void	echos image content and calls exit();
	 */
	private static function _process_mla_stream_image() {
//error_log( __LINE__ . " _process_mla_stream_image \$_REQUEST =  " . var_export( $_REQUEST, true ), 0 );
//error_log( __LINE__ . " _process_mla_stream_image memory_get_usage =  " . var_export( memory_get_usage ( true ), true ), 0 );
		if ( isset( $_REQUEST['mla_stream_file'] ) ) {
//$microtime = microtime( true );
//error_log( __LINE__ . " _process_mla_stream_image {$_REQUEST['mla_stream_file']}", 0 );
			if( ini_get( 'zlib.output_compression' ) ) { 
				ini_set( 'zlib.output_compression', 'Off' );
			}
			
			$file = stripslashes( $_REQUEST['mla_stream_file'] );
			$width = isset( $_REQUEST['mla_stream_width'] ) ? absint( $_REQUEST['mla_stream_width'] ) : 0;
			$height = isset( $_REQUEST['mla_stream_height'] ) ? absint( $_REQUEST['mla_stream_height'] ) : 0;
			$type = isset( $_REQUEST['mla_stream_type'] ) ? stripslashes( $_REQUEST['mla_stream_type'] ) : 'image/jpeg';
			$quality = isset( $_REQUEST['mla_stream_quality'] ) ? absint( $_REQUEST['mla_stream_quality'] ) : 0;

			/*
			 * Frame, resolution and type are required in the load() function, which we
			 * can't explicitly pass parameters to.
			 */
			if ( isset( $_REQUEST['mla_stream_frame'] ) ) {
				self::$mla_imagick_args['frame'] = absint( $_REQUEST['mla_stream_frame'] );
			}
			
			if ( isset( $_REQUEST['mla_stream_resolution'] ) ) {
				self::$mla_imagick_args['resolution'] = absint( $_REQUEST['mla_stream_resolution'] );
			}
			
			self::$mla_imagick_args['type'] = $type;
			
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
				/* * * * * * * * *
				$upload_dir = wp_upload_dir();
				$upload_dir = $upload_dir['basedir'] . '/';
				
				$mutex = new MLAMutex();
				$mutex->init( 1, $upload_dir . 'mla-mutex.txt' );
				$mutex->acquire();
				 * * * * * * * * */
//$acquired = microtime( true ) - $microtime;
//error_log( __LINE__ . " _process_mla_stream_image {$_REQUEST['mla_stream_file']} acquired at {$acquired}", 0 );
				
				add_filter( 'wp_image_editors', 'MLAStreamImage::_wp_image_editors_filter', 10, 1 );
				$image_editor = wp_get_image_editor( $file );
				remove_filter( 'wp_image_editors', 'MLAStreamImage::_wp_image_editors_filter', 10 );
				
				//$mutex->release();
//$elapsed = ( $released = microtime( true ) - $microtime ) - $acquired;
//error_log( __LINE__ . " _process_mla_stream_image {$_REQUEST['mla_stream_file']} released at {$released} after {$elapsed}", 0 );
				
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
				
				$image_editor->mla_prepare_image( $width, $height, $best_fit, $type, $quality );
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
//$elapsed = ( $unset = microtime( true ) - $microtime ) - $released;
//error_log( __LINE__ . " _process_mla_stream_image {$_REQUEST['mla_stream_file']} unset at {$unset} after {$elapsed}", 0 );
			exit();
		} else {
			wp_die( 'mla_stream_file not set', '', array( 'response' => 404 ) );
		}

		// Should not be possible
		wp_die( '_process_mla_stream_image error', '', array( 'response' => 500 ) );
	}
} // Class MLAStreamImage

class MLAMutex {
	private $id;
	private $sem_id;
	private $is_acquired = false;
	private $is_windows = false;
	private $filename = '';
	private $filepointer;
	
	function __construct() 	{
		// sem_ functions not available, always use file locking
		if ( true || 'WIN' == substr( PHP_OS, 0, 3 ) ) {
			$this->is_windows = true;
		}
	}
	
	public function init( $id, $filename = '' ) {
		$this->id = $id;
		
		if( $this->is_windows ) {
			if( empty( $filename ) ) {
				error_log( __LINE__ . ' MLAMutex::init no Windows filename specified', 0 );
				return false;
			} else {
				$this->filename = $filename;
			}
		} else {
			if( ! ( $this->sem_id = sem_get( $this->id, 1) ) ) {
				error_log( __LINE__ . ' MLAMutex::init error getting semaphore', 0 );
				return false;
			}
		}
		
		return true;
	}
	
	public function acquire() {
		if( $this->is_windows ) {
			if ( empty( $this->filename ) ) {
				return true;
			}
			
			if( false == ( $this->filepointer = @fopen( $this->filename, "w+" ) ) ) {
				error_log( __LINE__ . ' MLAMutex::acquire error opening mutex file', 0 );
				return false;
			}
		
			if( false == flock( $this->filepointer, LOCK_EX ) ) {
				error_log( __LINE__ . ' MLAMutex::acquire error locking mutex file', 0 );
				return false;
			}
		} else {
			if ( ! sem_acquire( $this->sem_id ) ) {
				error_log( __LINE__ . ' MLAMutex::acquire error acquiring semaphore', 0 );
				return false;
			}
		}
		
		$this->is_acquired = true;
		return true;
	}
	
	public function release() {
		if( ! $this->is_acquired ) {
			return true;
		}
		
		if( $this->is_windows ) {
			if( false == flock( $this->filepointer, LOCK_UN ) ) {
				error_log( __LINE__ . ' MLAMutex::release error unlocking mutex file', 0 );
				return false;
			}
		
			fclose( $this->filepointer );
		} else {
			if ( ! sem_release( $this->sem_id ) ) {
				error_log( __LINE__ . ' MLAMutex::release error releasing semaphore', 0 );
				return false;
			}
		}
		
		$this->is_acquired = false;
		return true;
	}
	
	public function getId() {
		return $this->sem_id;
	}
} // MLAMutex
?>