<?php
//-------------------------------------------------------------------------------------
//	JibberBook v2.0
//	(c) 2007 Chris Jaure
//	license: MIT License
//	website: http://www.chromasynthetic.com/
//
//	admin/actions/login.php
//-------------------------------------------------------------------------------------

require_once('../../inc/config.php');
require_once('../../localization/' . JB_LANGUAGE . '.php');
$password = $_POST['jbpassword'];
session_start();
if ($password == JB_PASSWORD) {
  $_SESSION['admin'] = true;
} else {
  $_SESSION['message_type'] = 'error';
  $_SESSION['message'] = JB_T_ADMIN_PASSWORD_ERROR;
}
$url = 'http://' . $_SERVER['HTTP_HOST'] . dirname(dirname($_SERVER['REQUEST_URI'] . 'x')) . '/index.php';
header("Location: $url");
exit();
?>
