@package    MicroAkismet
@author     Gaby Vanhegan
@version    1.2
@date       2007-01-29

A straightforward set of functions for talking to the Akismet blog-spam
protection system.  This is intended as a simple and compact method for
adding Akismet protection to any user-submitted content.  Built using the
information and examples on the Akismet API page:

   http://akismet.com/development/api/

There are two versions, a bunch of static functions or a stand-alone 
class.  Usage is practically identical for either, you can use whichever
one you want.

INSTALLATION:

1. To begin using the functions, download and unzip the script from the 
website:

	http://vanhegan.net/software/akismet/
	
2. Place the php file you want somewhere in your web root, and load
the functions or class using:

 	include_once("func.microakismet.inc.php")

Or:

	include_once("class.microakismet.inc.php")

3. Obtain a valid WordPress API key, if you do not already have one, from:

	http://wordpress.com/

If you don't have an account you will need to create one.  Once you have an
account, your API key can be found at the bottom of this page:

	http://wordpress.com/profile/

4a. If you're using the static functions (func.microakismet.inc.php)  then
you will need to set the global variables at the top of the file; your
WordPress API key and the home page of the blog being protected.  The 
User Agent can be changed to suit your application.  If it is not a blog
that is being protected, change the home page to the comments page or forum
page that is being protected.

4b. If you are using the class file (func.microakismet.inc.php), the API 
key, homepage and user agent are specified in the class constructor.

USAGE:
There are three functions for talking to Akismet.  In the static functions:

   aksimet_check( $vars )        // Check if a comment is spam or not
   aksimet_spam( $vars )         // Re-classify a comment as spam
   aksimet_ham( $vars )          // Re-classify a comment as ham

And the corresponding member functions in the class version:
	
	$akismet	= new MicroAkismet( $api_key, $blog, $user_agent );
	$akismet->check( $vars );
	$akismet->spam( $vars );
	$akismet->ham( $vars );

Each function takes one argument, $vars, which is a list of information
about the comment that is being checked.  $vars *must* contain at least
this information:

   $vars["user_ip"]              // The IP of the comment poster
   $vars["user_agent"]           // The user-agent of the comment poster

The "blog" value (the homepage of the blog that this post came from) is
added automatically by the code.  The following extra information can also
be added, to help Akismet classify the message more accurately:

   $vars["referrer"]             // The content of the HTTP_REFERER header
   $vars["permalink"]            // Permalink to the comment
   $vars["comment_type"]         // May be blank, comment, trackback, etc
   $vars["comment_author"]       // Submitted name with the comment
   $vars["comment_author_email"] // Submitted email address
   $vars["comment_author_url"]   // Commenter URL
   $vars["comment_content"]      // The content that was submitted

In PHP, you can also add the contents of the $_SERVER array to this list,
which gives extra information to Akismet, and further increases the
accuracy of their system:

EXAMPLE:
To check if a message is spam or not:

   <?php

	// The array of data we need
   $vars    = array();

	// Add the contents of the $_SERVER array, to help Akismet out
   foreach ( $_SERVER as $key => $val ) { $vars[ $key ] = $val; }

	// Mandatory fields of information
   $vars["user_ip"]           	= $_SERVER["REMOTE_ADDR"];
   $vars["user_agent"]        	= $_SERVER["HTTP_USER_AGENT"];

	// The body of the message to check, the name of the person who
	// posted it, and their email address
   $vars["comment_content"]   	= $_POST["comment"];
   $vars["comment_author"]			= $_POST["sender_name"];
   $vars["comment_author_email"]	= $_POST["sender_email"];

   // ... Add more fields if you want

	// Check if it's spam
	if ( akismet_check( $vars ) ) {
		die( "The message was spam!" );
	}
	else {
		// ...
		// Do whatever we do if the message was OK
	}

   ?>

Or to do this using the class version:

	<?php

	// The array of data we need
	$vars    = array();

	// ... Add vars as before ...
	$akismet	= new MicroAkismet(  "your.wordpress.api.key",
											"http://homepaeg.com/blog/comments",
											"mysite.com/1.0" );
											
	// Check if it's spam
	if ( $akismet->check( $vars ) ) {
		die( "The message was spam!" );
	}
	else {
		// Do whatever we do if the message was OK
	}

	?>