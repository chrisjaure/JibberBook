<?php
//-------------------------------------------------------------------------------------
//	JibberBook v2.1
//	(c) 2008 Chris Jaure
//	license: MIT License
//	website: http://www.jibberbook.com/
//
//	inc/functions.php
//-------------------------------------------------------------------------------------

// translation functions
function __($str) {
    global $lang;
    
    if ($lang[$str] != "")
        return $lang[$str];
    else
        return $str;
}

function _e($str) {
    echo __($str);
}
?>
