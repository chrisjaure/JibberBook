<?php
//-------------------------------------------------------------------------------------
//	JibberBook v2.3
//	(c) 2009 Chris Jaure
//	license: MIT License
//	website: http://www.jibberbook.com/
//
//	admin/actions/reclassify.php
//-------------------------------------------------------------------------------------

require_once('../inc/secure.php');
require_once('../../inc/includes.php');
includes(array('libraries/microakismet/class.microakismet.inc.php'));

$id = $_GET['id'];
$storage = new Comments();
$storage->reclassifyComment($id);
$message = __('The comment has been reclassified.');
if (isset($_GET['_ajax'])) {
  echo "{'value':'1', 'message':'$message', 'id':'$id'}";
} else {
  $_SESSION['message'] = $message;
  $_SESSION['message_type'] = 'confirm';
  header("Location: {$_SESSION['referer']}");
}

?>
