<?php
//-------------------------------------------------------------------------------------
//	JibberBook v2.0
//	(c) 2007 Chris Jaure
//	license: MIT License
//	website: http://www.chromasynthetic.com/
//
//	admin/actions/load.php
//-------------------------------------------------------------------------------------

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
    echo '<p>' . __('No comments to load.') . '</p>';
  }
  foreach ($messages as $message) {
    if ($message['name'] == '') continue;
    transformXML($message, $type);
  }
}
?>
