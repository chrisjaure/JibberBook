<?php
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
