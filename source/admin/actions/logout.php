<?php
//-------------------------------------------------------------------------------------
//	JibberBook v2.3
//	(c) 2009 Chris Jaure
//	license: MIT License
//	website: http://www.jibberbook.com/
//
//	admin/actions/logout.php
//-------------------------------------------------------------------------------------

session_start();
session_unset();
session_destroy();
header("Location: ../index.php");
?>
