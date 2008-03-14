<?php
//-------------------------------------------------------------------------------------
//	JibberBook v2.0
//	(c) 2007 Chris Jaure
//	license: MIT License
//	website: http://www.chromasynthetic.com/
//
//	admin/actions/logout.php
//-------------------------------------------------------------------------------------

session_start();
session_unset();
session_destroy();
header("Location: ../index.php");
?>
