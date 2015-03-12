<?php
/**
 * WordPress Imagick Image Editor
 *
 * @package WordPress
 * @subpackage Image_Editor
 */

/**
 * WordPress Image Editor Class for Image Manipulation through Imagick PHP Module
 *
 * @since 3.5.0
 * @package WordPress
 * @subpackage Image_Editor
 * @uses WP_Image_Editor Extends class
 */
class MLA_Image_Editor extends WP_Image_Editor_Imagick {

	/**
	 * Set the iterator position.
	 *
	 * @since 3.5.0
	 * @access public
	 *
	 * @return boolean
	 */
	public function setIteratorIndex( $index ) {

		if ( is_callable( array( $this->image, 'setIteratorIndex' ) ) ) {
			return $this->image->setIteratorIndex( absint( $index ) );
		}

		return false;
	}
}
