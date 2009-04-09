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
 * @version		1.2
 * @date			2007-01-29
 * @license		http://vanhegan.net/software/akismet/LICENSE.txt
 *
 * USAGE:
 * For full information on how to use this script, please visit the website:
 *
 *		http://vanhegan.net/software/akismet/
 *
 */

class MicroAkismet {
	
	/**
	 * @var	string	Akismet host
	 */
	var $akismet_host	= "rest.akismet.com";

	/**
	 * @var	string	Akismet version/url
	 */
	var $akismet_url	= "1.1";

	/**
	 * @var	string	Wordpress API key
	 */
	var $akismet_key	= false;

	/**
	 * @var	string	Homepage of blog being protected
	 */
	var $akismet_home	= false;

	/**
	 * @var	string	User-Agent string to prepend
	 */
	var $akismet_ua	= false;
	
	/**
	 * @var	string	Error message
	 */
	var $error			= false;
	
	/**
	 * Create a new MicroAkismet handler
	 * @param	string	$key		Wordpress API key
	 * @param	string	$home		Page being protected
	 * @param	string	$ua		User-Agent string to prepend
	 * @return 							False on error
	 * @access	public
	 */
	public function MicroAkismet ( $key = false, $home = false, $ua = false ) {
		
		// All three are required
		if ( $key  == false ) { 
			$this->error = "No API key given";
			return false; 
		}
		if ( $home == false ) { 
			$this->error = "No homepage given";
			return false; 
		}
		if ( $ua   == false ) { 
			$this->error = "No user agent given";
			return false;
		}
		
		// Store the information
		$this->akismet_key	= $key;
		$this->akismet_home	= $home;
		$this->akismet_ua		= $ua;
	}

	/**
	 * Check the given message and server parameters against Akismet
	 * @param	string	$vars		Info about the comment, in key/val pairs
	 * @return 	boolean				True if it's spam, false if not
	 * @access	public
	 */
	public function check ( $vars ) {
		if ( !( $this->_login() ) ) { return false; }
		$vars["blog"]	= $this->akismet_home;
		$host				= $this->akismet_key . "." . $this->akismet_host;
		$url				= "http://$host/" . $this->akismet_url 
							. "/comment-check";
		$result			= $this->_send( $vars, $host, $url );
		if ( $result == "false" ) { return false; }
		else                      { return true;  }
	}

	/**
	 * Mark the given message as spam
	 * @param	string	$vars		Info about the comment, in key/val pairs
	 * @return 	boolean				True on success
	 * @access	public
	 */
	public function spam ( $vars ) {
		$vars["blog"]	= $this->akismet_home;
		$host				= $this->akismet_key . "." . $this->akismet_host;
		$url				= "http://$host/" . $this->akismet_url 
							. "/submit-spam";
		return $this->_send( $vars, $host, $url );
	}

	/**
	 * Mark the given message as ham
	 * @param	string	$vars		Info about the comment, in key/val pairs
	 * @return 	boolean				True on success
	 * @access	public
	 */
	public function ham ( $vars ) {
		$vars["blog"]	= $this->akismet_home;
		$host				= $this->akismet_key . "." . $this->akismet_host;
		$url				= "http://$host/" . $this->akismet_url 
							. "/submit-ham";
		return $this->_send( $vars, $host, $url );
	}

	/**
	 * Login to the Akismet system using the given API key
	 * @return 	boolean				True on successful key verification
	 * @access	private
	 */
	private function _login ( ) {
		$args		= array( "key"  => $this->akismet_key,
								"blog" => $this->akismet_home );
		$host		= $this->akismet_host;
		$url		= "http://$host/" . $this->akismet_url . "/verify-key";
		$valid	= $this->_send( $args, $host, $url );	
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
	private function _send ( $args = "", $host = "", $url = "" ) {

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
			. "User-Agent: " . $this->akismet_ua . " | vanhegan.net-akismet.inc.php/1.0\r\n"
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
}

?>