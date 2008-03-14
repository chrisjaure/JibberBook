<?php
//-------------------------------------------------------------------------------------
//	JibberBook v2.0
//	(c) 2007 Chris Jaure
//	license: MIT License
//	website: http://www.chromasynthetic.com/
//
//	admin/index.php
//-------------------------------------------------------------------------------------

require_once('inc/secure.php');
$_SESSION['referer'] = 'http://' . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI'];
require_once('inc/header.php');
?>

<h2>Recent Ham</h2>
<?php 
require_once('actions/load.php');
loadHam(5);  
?>
<hr />
<h2>Recent Spam</h2>
<?php 
require_once('actions/load.php');
loadSpam(5);  
?>

<?php require_once('inc/footer.php');
