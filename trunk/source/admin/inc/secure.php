<?php
//-------------------------------------------------------------------------------------
//	JibberBook v2.3
//	(c) 2009 Chris Jaure
//	license: MIT License
//	website: http://www.jibberbook.com/
//
//	admin/inc/secure.php
//-------------------------------------------------------------------------------------

session_start();
if (!isset($_SESSION['admin']))
{
  if (is_file(realpath('login_form.php'))) {
    $url = 'http://' . $_SERVER['HTTP_HOST'] . dirname($_SERVER['REQUEST_URI'] . 'x') . '/login_form.php';
  } else {
    $url = 'http://' . $_SERVER['HTTP_HOST'] . dirname(dirname($_SERVER['REQUEST_URI'] . 'x')) . '/login_form.php';
  }
  header("Location: $url");
  exit();
} else {
  $loggedin = true;
}
?>
