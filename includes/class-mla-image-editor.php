<?php
/**
 * MLA Imagick Image Editor
 *
 * @package Media Library Assistant
 * @since 2.10
 */

/**
 * MLA Image Editor Class for Image Manipulation through Imagick PHP Module
 *
 * @since 2.10
 * @package Media Library Assistant
 * @uses WP_Image_Editor_Imagick Extends class
 */
class MLA_Image_Editor extends WP_Image_Editor_Imagick {
	/**
	 * Direct Ghostscript file conversion
	 *
	 * @since 2.10
	 *
	 * @return	boolean	true if conversion succeeds else false
	 */
	private function _ghostscript_convert( $file, $frame, $resolution, $output_type ) {
		/*
		 * Look for exec() - from http://stackoverflow.com/a/12980534/866618
		 */
		if ( ini_get('safe_mode') ) {
			return false;
		}

		$blacklist = preg_split( '/,\s*/', ini_get('disable_functions') . ',' . ini_get('suhosin.executor.func.blacklist') );
		if ( in_array('exec', $blacklist) ) {
			return false;
		}

		/*
		 * Look for the Ghostscript executable
		 */
		$ghostscript_path = NULL;
		do {

			if ( 'WIN' === strtoupper( substr( PHP_OS, 0, 3) ) ) {
				if ( $ghostscript_path = getenv('GSC') ) {
					break;
				}

				$ghostscript_path = exec('where gswin*c.exe');
				if ( ! empty( $ghostscript_path ) ) {
					break;
				}

				$ghostscript_path = exec('dir /o:n/s/b "C:\Program Files\gs\*gswin*c.exe"');
				if ( ! empty( $ghostscript_path ) ) {
					break;
				}

				$ghostscript_path = exec('dir /o:n/s/b "C:\Program Files (x86)\gs\*gswin32c.exe"');
				if ( ! empty( $ghostscript_path ) ) {
					break;
				}

				$ghostscript_path = NULL;
				break;
			} // Windows platform

			$ghostscript_path = exec('which gs');
			if ( ! empty( $ghostscript_path ) ) {
				break;
			}

			$test_path = '/usr/bin/gs';
			exec('test -e ' . $test_path, $dummy, $ghostscript_path);

			if ( $test_path !== $ghostscript_path ) {
				$ghostscript_path = NULL;
			}
		} while ( false );

		if ( isset( $ghostscript_path ) ) {
			if ( 'image/jpeg' == $output_type ) {
				$device = 'jpeg';
				$extension = '.jpg';
			} else {
				$device = 'png16m';
				$extension = '.png';
			}

			/*
			 * Generate a unique temporary file name
			 */
			$output_path = get_temp_dir();
			$output_file = $output_path . wp_unique_filename( $output_path, 'mla-ghostscript' . $extension);

			$cmd = escapeshellarg( $ghostscript_path ) . ' -sDEVICE=%1$s -r%2$dx%2$d -dFirstPage=%3$d -dLastPage=%3$d -dPDFFitPage -o %4$s %5$s 2>&1';
			$cmd = sprintf( $cmd, $device, $resolution, ( $frame + 1 ), escapeshellarg( $output_file ), escapeshellarg( $file ) );
			exec( $cmd, $stdout, $return );
			if ( 0 != $return ) {
				error_log( "ERROR: _ghostscript_convert exec returned '{$return}, cmd = " . var_export( $cmd, true ), 0 );
				error_log( "ERROR: _ghostscript_convert exec returned '{$return}, details = " . var_export( $stdout, true ), 0 );
				return false;
			}

			try {
				$this->image->readImage( $output_file );
			}
			catch ( Exception $e ) {
				error_log( "ERROR: _ghostscript_convert readImage Exception = " . var_export( $e->getMessage(), true ), 0 );
				return false;
			}

			$this->file = $output_file;
			@unlink( $output_file );
			return true;
		} // found Ghostscript

		return false;
	}


	/**
	 * Loads image from $this->file into new Imagick Object.
	 *
	 * Sets image resolution and frame from $imagick_args before loading the file.
	 *
	 * @since 2.10
	 * @access protected
	 *
	 * @return boolean|WP_Error True if loaded; WP_Error on failure.
	 */
	public function load() {
		if ( $this->image instanceof Imagick )
			return true;

		if ( ! is_file( $this->file ) && ! preg_match( '|^https?://|', $this->file ) )
			return new WP_Error( 'error_loading_image', __('File doesn&#8217;t exist?'), $this->file );

		/** This filter is documented in wp-includes/class-wp-image-editor-imagick.php */
		// Even though Imagick uses less PHP memory than GD, set higher limit for users that have low PHP.ini limits
		@ini_set( 'memory_limit', apply_filters( 'image_memory_limit', WP_MAX_MEMORY_LIMIT ) );

		try {
			$this->image = new imagick();
 
 			if ( class_exists( 'MLAStreamImage' ) ) {
				$mla_imagick_args = MLAStreamImage::$mla_imagick_args;
			} else {
				$mla_imagick_args = array();
			}

			/*
			 * this must be called before reading the image, otherwise has no effect - 
			 * "-density {$x_resolution}x{$y_resolution}"
			 * this is important to give good quality output, otherwise text might be unclear
			 * default resolution is 72,72
			 */
			if ( isset( $mla_imagick_args['resolution'] ) ) {
				$resolution = absint( $mla_imagick_args['resolution'] );
				if ( $resolution ) {
					$this->image->setResolution( $resolution, $resolution );
				}
			} else {
				$resolution = 72;
			}

			if ( isset( $mla_imagick_args['frame'] ) ) {
				$frame = $mla_imagick_args['frame'];
			} else {
				$frame = 0;
			}

			if ( isset( $mla_imagick_args['type'] ) ) {
				$type = $mla_imagick_args['type'];
			} else {
				$type = 'image/jpeg';
			}

			//$result = false;
			$result = $this->_ghostscript_convert( $this->file, $frame, $resolution, $type );

			if ( false === $result ) {
				try {
					$this->image->readImage( $this->file . '[' . $frame . ']' );
				}
				catch ( Exception $e ) {
					$this->image->readImage( $this->file . '[0]' );
				}

				$this->image->setImageFormat( strtoupper( $this->get_extension( $type ) ) );
			}

			if( ! $this->image->valid() )
				return new WP_Error( 'invalid_image', __('File is not an image.'), $this->file);

			// Select the first frame to handle animated images properly
			if ( is_callable( array( $this->image, 'setIteratorIndex' ) ) )
				$this->image->setIteratorIndex(0);

			$this->mime_type = $this->get_mime_type( $this->image->getImageFormat() );
		}
		catch ( Exception $e ) {
			return new WP_Error( 'invalid_image', $e->getMessage(), $this->file );
		}

		$result = $this->update_size();
		if ( is_wp_error( $result ) ) {
			return $result;
		}

		return $this->set_quality();
	}

	/**
	 * Set the iterator position
	 *
	 * @since 2.10
	 * @access public
	 *
	 * @param	integer	frame/page number
	 *
	 * @return boolean	success/failure
	 */
	public function mla_setIteratorIndex( $index ) {
		if ( is_callable( array( $this->image, 'setIteratorIndex' ) ) ) {
			return $this->image->setIteratorIndex( absint( $index ) );
		}

		return false;
	}

	/**
	 * Prepare the image for output, scaling and flattening as required
	 *
	 * @since 2.10
	 * @access public
	 *
	 * @param	integer	zero or new width
	 * @param	integer	zero or new height
	 * @param	boolean	proportional fit (true) or exact fit (false)
	 * @param	string	output MIME type
	 * @param	integer	compression quality; 1 - 100
	 *
	 * @return void
	 */
	public function mla_prepare_image( $width, $height, $best_fit, $type, $quality ) {
		if ( is_callable( array( $this->image, 'scaleImage' ) ) ) {
			if ( 0 < $width && 0 < $height ) {
				// Both are set; use them as-is
				$this->image->scaleImage( $width, $height, $best_fit );
			} elseif ( 0 < $width || 0 < $height ) {
				// One is set; scale the other one proportionally if reducing
				$image_size = $this->get_size();
				if ( $width && isset( $image_size['width'] ) && $width < $image_size['width'] ) {
					$this->image->scaleImage( $width, 0 );
				} elseif ( isset( $image_size['height'] ) && $height < $image_size['height'] ) {
					$this->image->scaleImage( 0, $height );
				}
			} else {
				// Neither is specified, apply defaults
				$this->image->scaleImage( 150, 0 );
			}

			$this->update_size();
		}

		if ( 0 < $quality && 101 > $quality ) {
			$this->set_quality( $quality );
		}

		if ( 'image/jpeg' == $type ) {				
			if ( is_callable( array( $this->image, 'setImageBackgroundColor' ) ) ) {				
				$this->image->setImageBackgroundColor('white');
			}

			if ( is_callable( array( $this->image, 'mergeImageLayers' ) ) ) {
				$this->image = $this->image->mergeImageLayers( imagick::LAYERMETHOD_FLATTEN );
			} elseif ( is_callable( array( $this->image, 'flattenImages' ) ) ) {				
				$this->image = $this->image->flattenImages();
			}
		}
	}

	/**
	 * Streams current image to browser.
	 *
	 * @since 2.10
	 * @access public
	 *
	 * @param string $mime_type
	 * @return boolean|WP_Error
	 */
	public function stream( $mime_type = null ) {
		try {
			// Output stream of image content
			header( "Content-Type: $mime_type" );
			print $this->image->getImageBlob();
		}
		catch ( Exception $e ) {
			return new WP_Error( 'image_stream_error', $e->getMessage() );
		}

		return true;
	}
}
