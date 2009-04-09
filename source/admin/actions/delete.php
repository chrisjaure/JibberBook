<?php
//-------------------------------------------------------------------------------------
//	JibberBook v2.3
//	(c) 2009 Chris Jaure
//	license: MIT License
//	website: http://www.jibberbook.com/
//
//	admin/actions/delete.php
//-------------------------------------------------------------------------------------

require_once('../inc/secure.php');
require_once('../../inc/includes.php');

$storage = new Comments();

if (isset($_GET['id'])) {
  $id = $_GET['id'];
  $message = __('The comment has been deleted.');
  $storage->deleteComment($id);
}

if (isset($_GET['type'])) {
  $id = 'all';
  $message = __('All spam comments have been deleted.');
  $storage->deleteSpam();
}

if (isset($_GET['_ajax'])) {
  echo "{'value':'1', 'message':'$message', 'id':'$id'}";
} else {
  $_SESSION['message'] = $message;
  $_SESSION['message_type'] = 'confirm';
  header("Location: {$_SESSION['referer']}");
}
?>
