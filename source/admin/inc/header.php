<?php
//-------------------------------------------------------------------------------------
//	JibberBook v2.0
//	(c) 2007 Chris Jaure
//	license: MIT License
//	website: http://www.chromasynthetic.com/
//
//	admin/inc/header.php
//-------------------------------------------------------------------------------------

header('Content-type: text/html; charset=utf-8');
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN"
 "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">
<head>
<title>JibberBook Dashboard</title>
<script type="text/javascript" src="../inc/mootools.v1.11.js"></script>
<script type="text/javascript" src="inc/jbascript_min.js"></script>
<script type="text/javascript">
window.addEvent('load', guestbookAdmin.initialize.bind(guestbookAdmin));
</script>
<link rel="stylesheet" type="text/css" href="inc/jbastyle.css" />
</head>
<body>
<div id="container">
  <div id="header">
    <h1>
      <img src="media/logo.png" alt="" /><strong>Jibber</strong>Book<br />
      <span>Dashboard</span>
    </h1>
  </div>
  <div id="nav">
    <?php if (isset($loggedin)) : ?>
      <ul>
        <li><a id="recent_link" href="index.php">Recent</a></li>
        <li><a id="ham_link" href="ham.php">Ham</a></li>
        <li><a id="spam_link" href="spam.php">Spam</a></li>
        <li><a id="logout_link" href="actions/logout.php">Logout</a></li>
      </ul>
    <?php else : ?>
      <a href="../">&#171; View Guestbook</a>
    <?php endif; ?>
  </div>
  <div id="content">
  <div id="message" class="<?php echo $_SESSION['message_type']; ?>">
    <p><?php echo $_SESSION['message']; ?></p>
  </div>
