<?php
//-------------------------------------------------------------------------------------
//	JibberBook v2.0
//	(c) 2007 Chris Jaure
//	license: MIT License
//	website: http://www.chromasynthetic.com/
//
//	admin/actions/load.php
//-------------------------------------------------------------------------------------

require_once(realpath(dirname(__FILE__) . DIRECTORY_SEPARATOR . '..' . DIRECTORY_SEPARATOR . '..' . DIRECTORY_SEPARATOR . 'inc' . DIRECTORY_SEPARATOR . 'config.php'));
require_once(realpath(dirname(__FILE__) . DIRECTORY_SEPARATOR . '..' . DIRECTORY_SEPARATOR . '..' . DIRECTORY_SEPARATOR . 'inc' . DIRECTORY_SEPARATOR . 'comments.php'));
require_once(realpath(dirname(__FILE__) . DIRECTORY_SEPARATOR . 'transformxml.php'));

function loadHam($num = null) {
  load(0, $num);
}

function loadSpam($num = null) {
  load(1, $num);
}

function load($type, $show = null) {
  $storage = new Comments();
  $messages = $storage->getComments($type, $show);
  if (count($messages) == 0) {
    echo '<p>No messages to load.</p>';
  }
  foreach ($messages as $message) {
    if ($message['name'] == '') continue;
    transformXML($message, $type);
  }
}
?>
