<?php
//-------------------------------------------------------------------------------------
//	JibberBook v2.0
//	(c) 2007 Chris Jaure
//	license: MIT License
//	website: http://www.chromasynthetic.com/
//
//	actions/loadcomments.php
//-------------------------------------------------------------------------------------

require_once(realpath(dirname(__FILE__) . DIRECTORY_SEPARATOR . '..' . DIRECTORY_SEPARATOR . 'inc' . DIRECTORY_SEPARATOR . 'config.php'));
require_once(realpath(dirname(__FILE__) . DIRECTORY_SEPARATOR . '..' . DIRECTORY_SEPARATOR . 'localization' . DIRECTORY_SEPARATOR . JB_LANGUAGE . '.php'));
require_once(realpath(dirname(__FILE__) . DIRECTORY_SEPARATOR . 'transformxml.php'));
require_once(realpath(dirname(__FILE__) . DIRECTORY_SEPARATOR . '..' . DIRECTORY_SEPARATOR . 'inc' . DIRECTORY_SEPARATOR . 'comments.php'));

$ajax = false;

function loadComments($offset) {
  global $ajax;
  $storage = new Comments();
  $count = $storage->getHamCount();
  $limit = array();
  $limit['upper'] = ($count - (($offset - 1) * JB_SHOW));
  $limit['lower'] = ($count - ($offset * JB_SHOW));
  $messages = $storage->getHam($limit);
  
  if ($ajax) echo "{'content':'";
  
  foreach ($messages as $message) {
    transformXML($message);
  }
  
  if ($ajax) {
    echo "', 'value':'";
    if (count($messages) < JB_SHOW) echo '0';
    else echo '1';
    echo "'}";
  }
  return ($count > JB_SHOW);
}

if (isset($_GET['offset'])) {
  $ajax = true;
  loadComments((int) $_GET['offset']);
}
?>
