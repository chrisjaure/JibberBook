<?php
//-------------------------------------------------------------------------------------
//	JibberBook v2.0
//	(c) 2007 Chris Jaure
//	license: MIT License
//	website: http://www.chromasynthetic.com/
//
//	inc/config.php
//-------------------------------------------------------------------------------------

// misc settings
define('JB_XML_FILENAME', 'comments.xml');
define('JB_INDEX', 'index.php');
define('JB_SHOW', 20);
define('JB_DATE_FORMAT', 'F j, Y \a\t H:i:s');
define('JB_THEME', 'default');
define('JB_LANGUAGE', 'en');

// password for admin area
define('JB_PASSWORD', 'password');

// For more information on HTML Purifier go to http://www.htmlpurifier.org/
define('JB_ENABLE_HTML_PURIFIER', false);
define('JB_ENCODING', 'UTF-8');
define('JB_DOCTYPE', 'XHTML 1.0 Strict');
define('JB_ALLOWED_ELEMENTS', 'a[href|title],blockquote,p,em,strong,i,b,br,cite');

// Akismet settings
define('JB_AKISMET_KEY', '');
define('JB_GUESTBOOK_URL', '');


// STOP EDITING -----------------------------------------------------------------------

// version numbers
define('JB_VERSION', '2.1');
define('JB_MA_VERSION', '1.2');
?>
