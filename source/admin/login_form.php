<?php
//-------------------------------------------------------------------------------------
//	JibberBook v2.0
//	(c) 2007 Chris Jaure
//	license: MIT License
//	website: http://www.chromasynthetic.com/
//
//	admin/login_form.php
//-------------------------------------------------------------------------------------

require_once('../inc/config.php');
require_once('../localization/' . JB_LANGUAGE . '.php');
session_start();
if (isset($_SESSION['admin'])) {
  header("Location: index.php");
  exit(1);
}
require('inc/header.php');
?>
<h2><?php echo JB_T_ADMIN_LOGIN; ?></h2>
<form action="actions/login.php" method="post">
  <label for="jbpassword"><?php echo JB_T_ADMIN_PASSWORD; ?>:</label> <input type="password" id="jbpassword" name="jbpassword" />
  <input class="button" type="image" alt="Log in" src="media/button.gif" />
</form>
<?php require('inc/footer.php'); ?>
