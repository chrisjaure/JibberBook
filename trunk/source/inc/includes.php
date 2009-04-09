<?php
//-------------------------------------------------------------------------------------
//	JibberBook v2.3
//	(c) 2009 Chris Jaure
//	license: MIT License
//	website: http://www.jibberbook.com/
//
//	inc/includes.php
//-------------------------------------------------------------------------------------

// make it easier to include stuff
$dir = realpath(dirname(__FILE__) . DIRECTORY_SEPARATOR . '..' . DIRECTORY_SEPARATOR ) . '/';

require_once($dir . 'inc/config.php');
require_once($dir . 'inc/functions.php');
if (defined('JB_LANGUAGE')) {
    require_once($dir . 'localization/' . JB_LANGUAGE . '.php');
}
require_once($dir . 'data_layer/datalayer.class.php');
require_once($dir . 'data_layer/' . JB_STORAGE . '/comments.class.php');

function includes($files) {
    global $dir;
    
    foreach($files as $file) {
        require_once($dir . $file);
    }
}
?>
