<?php
//-------------------------------------------------------------------------------------
//	JibberBook v2.3
//	(c) 2009 Chris Jaure
//	license: MIT License
//	website: http://www.jibberbook.com/
//
//	admin/login_form.php
//-------------------------------------------------------------------------------------

require_once('../inc/includes.php');

session_start();
if (isset($_SESSION['admin'])) {
  header("Location: index.php");
  exit(1);
}
require('inc/header.php');
?>
<h2><?php _e('Log In'); ?></h2>
<form action="actions/login.php" method="post">
  <label for="jbpassword"><?php _e('Password'); ?>:</label> <input type="password" id="jbpassword" name="jbpassword" />
  <input class="button" type="image" alt=<?php _e('Log In'); ?>" src="media/button.gif" />
</form>
<?php require('inc/footer.php'); ?>
