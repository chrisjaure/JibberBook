<?php
//------------------------------------------------------------------------------
//	JibberBook v2.3
//	(c) 2009 Chris Jaure
//	license: MIT License
//	website: http://www.jibberbook.com/
//
//	inc/config.php
//------------------------------------------------------------------------------

// misc settings
define('JB_STORAGE', 'xml');
define('JB_INDEX', 'index.php');
define('JB_SHOW', 20);
define('JB_DATE_FORMAT', 'F j, Y - H:i:s');
define('JB_THEME', 'default');
#define('JB_LANGUAGE', 'en');
define('JB_EMOTICONS', 'emoticons/');
define('JB_EMAIL', false);
define('JB_KEEP_SPAM', true);
define('JB_CHAR_LIMIT', false);

// XML settings
define('JB_XML_FILENAME', 'comments.xml');

// MySQL settings
define('JB_MYSQL_HOST', '');
define('JB_MYSQL_USERNAME', '');
define('JB_MYSQL_PASSWORD', '');
define('JB_MYSQL_DATABASE', '');
define('JB_MYSQL_TABLE', '');

// password for admin area
define('JB_PASSWORD', 'password');
define('JB_PWD_ENCODED', false);

// For more information on HTML Purifier go to http://www.htmlpurifier.org/
define('JB_ENABLE_HTML_PURIFIER', true);
define('JB_ENCODING', 'UTF-8');
define('JB_DOCTYPE', 'XHTML 1.0 Strict');
define('JB_ALLOWED_ELEMENTS', 'a[href|title],img[src|title|alt],blockquote,p,em,strong,i,b,br,cite');

// Akismet settings
define('JB_AKISMET_KEY', '');
define('JB_GUESTBOOK_URL', '');

// emoticons
$EMOTICONS = array(
    ':)' =>    'smile.gif',
    ':-)' =>   'smile.gif',
    ':))' =>   'lol.gif',
    ':(' =>    'sad.gif',
    ':-(' =>   'sad.gif',
    ':((' =>   'cry.gif',
    ":'(" =>   'cry.gif',
    'XD' =>    'yesh.gif',
    ':D' =>    'happy.gif',
    '=D' =>    'biggrin.gif',
    ';)' =>    'wink.gif',
    'X(' =>    'angry.gif',
    'DX' =>    'yell.gif',
    'D:' =>    'jawdrop.gif',
    ':\\' =>   'befuddled.gif',
    ':|' =>    'blank.gif',
    'X|' =>    'dead.gif',
    ':p' =>    'razz.gif',
    ':P' =>    'razz.gif',
    ':S' =>    'squiggle.gif',
    ':s' =>    'squiggle.gif',
    '=3' =>    'numnum.gif',
    ':o' =>    'gasp.gif',
    ':O' =>    'gasp.gif',
    ':<' =>    'sigh.gif',
    ':">' =>   'blush.gif',
);


// STOP EDITING ----------------------------------------------------------------

// version numbers
define('JB_VERSION', '2.3');
define('JB_MA_VERSION', '1.2');
?>
