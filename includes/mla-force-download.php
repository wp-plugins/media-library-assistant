<?php
/**
 * Provides Ajax support for downloading a file to the client
 *
 * @package Media Library Assistant
 * @since 1.95
 */

if ( isset( $_REQUEST['mla_download_file'] ) && isset( $_REQUEST['mla_download_type'] ) ) {
	if( ini_get( 'zlib.output_compression' ) ) { 
		ini_set( 'zlib.output_compression', 'Off' );
	}
	
	$file_name = $_REQUEST['mla_download_file'];
	$pathinfo = pathinfo( $file_name );

	header('Pragma: public'); 	// required
	header('Expires: 0');		// no cache
	header('Cache-Control: must-revalidate, post-check=0, pre-check=0');
	header('Last-Modified: '.gmdate ('D, d M Y H:i:s', filemtime ($file_name)).' GMT');
	header('Cache-Control: private',false);
	header('Content-Type: '.$_REQUEST['mla_download_type']);
	header('Content-Disposition: attachment; filename="'.basename($file_name).'"');
	header('Content-Transfer-Encoding: binary');
	header('Content-Length: '.filesize($file_name));	// provide file size
	header('Connection: close');

	readfile( $_REQUEST['mla_download_file'] );
	exit();
} else {
	$message = 'ERROR: download argument(s) not set.';
}

echo '<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">';
echo '<html xmlns="http://www.w3.org/1999/xhtml">';
echo '<head>';
echo '<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />';
echo '<title>Download Error</title>';
echo '</head>';
echo '';
echo '<body>';
echo $message;
echo '</body>';
echo '</html> ';
exit();
?>