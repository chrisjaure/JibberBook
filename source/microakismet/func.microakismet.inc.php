<?php
/**
 * A straightforward set of functions for talking to the Akismet blog-spam
 * protection system.  This is intended as a simple and compact method for
 * adding Akismet protection to any user-submitted content.  Built using the
 * information and examples on the Akismet API page:
 *
 *		http://akismet.com/development/api/
 *
 * @package		MicroAkismet
 * @author		Gaby Vanhegan
 * @version		1.1
 * @date			2007-01-15
 * @license		http://vanhegan.net/software/akismet/LICENSE.txt
 *
 * USAGE:
 * For full information on how to use this script, please visit the website:
 *
 *		http://vanhegan.net/software/akismet/
 *
 */

/**
 * User editable data here
 */

// Your WordPress API key
$GLOBALS["akismet_key"]		= "f973db6e91b0";

// The name of the blog you're protecting
$GLOBALS["akismet_home"]	= "http://vanhegan.net/guestbook.php";

// Your User-Agent string
$GLOBALS["akismet_ua"]		= "vanhegan.net/1.0";

/**
 * Advanced settings below, only change these if you know what you're doing
 */

// The Akismet hostname
$GLOBALS["akismet_host"]	= "rest.akismet.com";

// Base URL to append to host and prepend to all queries
$GLOBALS["akismet_url"]		= "1.1";

/**
 * Nothing to edit after this point
 */

/**
 * Check the given message and server parameters against Akismet
 * @param	string	$vars		Info about the comment, in key/val pairs
 * @return 	boolean				True if it's spam, false if not
 * @access	public
 */
function akismet_check ( $vars ) {
	if ( !( _akismet_login() ) ) { return false; }
	$vars["blog"]	= $GLOBALS["akismet_home"];
	$host				= $GLOBALS["akismet_key"] . "." . $GLOBALS["akismet_host"];
	$url				= "http://$host/" . $GLOBALS["akismet_url"] 
						. "/comment-check";
	$result			= _akismet_send( $vars, $host, $url );
	if ( $result == "false" ) { return false; }
	else                      { return true;  }
}

/**
 * Mark the given message as spam
 * @param	string	$vars		Info about the comment, in key/val pairs
 * @return 	boolean				True on success
 * @access	public
 */
function akismet_spam ( $vars ) {
	$vars["blog"]	= $GLOBALS["akismet_home"];
	$host				= $GLOBALS["akismet_key"] . "." . $GLOBALS["akismet_host"];
	$url				= "http://$host/" . $GLOBALS["akismet_url"] 
						. "/submit-spam";
	return _akismet_send( $vars, $host, $url );
}

/**
 * Mark the given message as ham
 * @param	string	$vars		Info about the comment, in key/val pairs
 * @return 	boolean				True on success
 * @access	public
 */
function akismet_ham ( $vars ) {
	$vars["blog"]	= $GLOBALS["akismet_home"];
	$host				= $GLOBALS["akismet_key"] . "." . $GLOBALS["akismet_host"];
	$url				= "http://$host/" . $GLOBALS["akismet_url"] 
						. "/submit-ham";
	return _akismet_send( $vars, $host, $url );
}

/**
 * Login to the Akismet system using the given API key
 * @return 	boolean				True on successful key verification
 * @access	private
 */
function _akismet_login ( ) {
	$args		= array( "key"  => $GLOBALS["akismet_key"],
							"blog" => $GLOBALS["akismet_home"] );
	$host		= $GLOBALS["akismet_host"];
	$url		= "http://$host/" . $GLOBALS["akismet_url"] . "/verify-key";
	$valid	= _akismet_send( $args, $host, $url );	
	if ( $valid == 'valid' ) { return true;  }
	else                     { return false; }
}

/**
 * Make an akismet request
 * @param	array 	$args		Arguments to send to the akismet server
 * @param	string 	$host		Host to talk to
 * @param	array 	$url		URL to send to the host
 * @return 	mixed					False on error or the server response
 * @access	private
 */
function _akismet_send ( $args = "", $host = "", $url = "" ) {

	// All of these are mandatory
	if ( !( is_array( $args ) ) ) { return false; }
	if ( $host == "" )            { return false; }
	if ( $url  == "" )            { return false; }
	
	// The request we wish to send
	$content	= "";
	foreach ( $args as $key => $val ) {
		$content	.= "$key=" . rawurlencode( stripslashes( $val ) ) . "&";
	}

	// The actual HTTP request
	$request	= "POST $url HTTP/1.0\r\n"
		. "Host: $host\r\n"
		. "Content-Type: application/x-www-form-urlencoded\r\n"
		. "User-Agent: " . $GLOBALS["akismet_ua"] . " | vanhegan.net-akismet.inc.php/1.0\r\n"
		. "Content-Length: " . strlen( $content ) . "\r\n\r\n"
		. "$content\r\n";
		
	$port			= 80;
	$response	= "";
	
	// Open a TCP file handle to the server, send our data
	if ( false !== ( $fh = fsockopen( $host, $port, $errno, $errstr, 3 ) ) ) {
		fwrite( $fh, $request );
		while ( !( feof( $fh ) ) ) { $response	.= fgets( $fh, 1160 ); }
		fclose( $fh );	
		// Split header and footer
		$response	= explode( "\r\n\r\n", $response, 2 );
	}
	return $response[ 1 ];
}

?>