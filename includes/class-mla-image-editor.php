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
			}
			
			if ( isset( $mla_imagick_args['frame'] ) ) {
				try {
					$this->image->readImage( $this->file . '[' . $mla_imagick_args['frame'] . ']' );
				}
				catch ( Exception $e ) {
					$this->image->readImage( $this->file . '[0]' );
				}
			} else {
				$this->image->readImage( $this->file . '[0]' );
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

		$updated_size = $this->update_size();
		if ( is_wp_error( $updated_size ) ) {
			return $updated_size;
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
	 *
	 * @return void
	 */
	public function mla_prepare_image( $width, $height, $best_fit, $type ) {
		if ( is_callable( array( $this->image, 'scaleImage' ) ) ) {
			if ( 0 < $width && 0 < $height ) {
				// Both are set; use them as-is
				$this->image->scaleImage( $width, $height, $best_fit );
				$this->update_size();
			} elseif ( 0 < $width || 0 < $height ) {
				// One is set; scale the other one proportionally if reducing
				$image_size = $this->get_size();
				if ( $width && isset( $image_size['width'] ) && $width < $image_size['width'] ) {
					$this->image->scaleImage( $width, 0 );
					$this->update_size();
				} elseif ( isset( $image_size['height'] ) && $height < $image_size['height'] ) {
					$this->image->scaleImage( 0, $height );
					$this->update_size();
				}
			}
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
	 * Return the output format
	 *
	 * @since 2.10
	 * @access public
	 *
	 * @param	string	$filename
	 * @param	string	$mime_type
	 *
	 * @return	array { filename|null, extension, mime-type }
	 */
	public function mla_output_format( $filename = null, $mime_type = null ) {
		if ( is_callable( array( $this, 'get_output_format' ) ) ) {
			return $this->get_output_format( $filename, $mime_type );
		}

		return false;
	}
}
