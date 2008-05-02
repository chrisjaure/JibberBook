<?php
//-------------------------------------------------------------------------------------
//	JibberBook v2.1
//	(c) 2008 Chris Jaure
//	license: MIT License
//	website: http://www.jibberbook.com/
//
//	admin/actions/login.php
//-------------------------------------------------------------------------------------

require_once('../../inc/includes.php');

$password = $_POST['jbpassword'];
session_start();
if ($password == JB_PASSWORD) {
  $_SESSION['admin'] = true;
} else {
  $_SESSION['message_type'] = 'error';
  $_SESSION['message'] = __('Incorrect password.');
}
$url = 'http://' . $_SERVER['HTTP_HOST'] . dirname(dirname($_SERVER['REQUEST_URI'] . 'x')) . '/index.php';
header("Location: $url");
exit();
?>
