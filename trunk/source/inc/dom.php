<?php
//-------------------------------------------------------------------------------------
//	JibberBook v2.3
//	(c) 2009 Chris Jaure
//	license: MIT License
//	website: http://www.jibberbook.com/
//
//	inc/dom.php
//	these are simple xml manipulator functions I wrote
//-------------------------------------------------------------------------------------

function loadFile($file){
  $newFile=new DOMDocument();
  $newFile->validateOnParse=true;
  $newFile->load($file);
  
  return $newFile;
}
function add($file, $parentName, $children){
  $xml=loadFile($file);
  
  $id=uniqid('m' . rand(1,5), true);
  $parentNode=$xml->createElement($parentName);
  $parentNode->setAttribute('mID', $id);
  foreach($children as $child => $value){
    $childNode=$xml->createElement($child, $value);
    $parentNode->appendChild($childNode);
  }
  $xml->documentElement->appendChild($parentNode);
  $xml->save($file);
  return $id;
}
function delete($file, $id){
  $xml=loadFile($file);
  $ids=explode(",", $id);
  foreach ($ids as $oldNodeID){
    $oldNode=$xml->getElementById($oldNodeID);
    $parentNode=$oldNode->parentNode;
    $parentNode->removeChild($oldNode);
  }
  $xml->save($file);
}
function edit($file, $id, $child, $value){
  $xml=loadFile($file);
  
  $parentNode=$xml->getElementById($id);
  $childNode=$parentNode->childNodes->item($child);
  $textNode=$childNode->childNodes->item(0);
  $textNode->nodeValue=$value;
  
  $xml->save($file);
}
function move($file, $moveid, $refid=NULL){
  $xml=loadFile($file);
  
  $moveNode=$xml->getElementById($moveid);
  $parentNode=$moveNode->parentNode;
  if ($refid!=NULL) {
    $refNode=$xml->getElementById($refid);
    if(!$parentNode->isSameNode($refNode->parentNode)) return false;
  }
  else $refNode=NULL;
  $moveNode=$parentNode->removeChild($moveNode);
  $parentNode->insertBefore($moveNode,$refNode);
  
  $xml->save($file);
}
?>
