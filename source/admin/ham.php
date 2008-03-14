<?php
//-------------------------------------------------------------------------------------
//	JibberBook v2.0
//	(c) 2007 Chris Jaure
//	license: MIT License
//	website: http://www.chromasynthetic.com/
//
//	admin/ham.php
//-------------------------------------------------------------------------------------

require_once('inc/secure.php');
$_SESSION['referer'] = 'http://' . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI'];
require_once('inc/header.php');
?>

<h2>All Ham</h2>
<?php 
require_once('actions/load.php');
loadHam();  
?>

<?php require_once('inc/footer.php'); ?>
