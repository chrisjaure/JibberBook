<?php
//-------------------------------------------------------------------------------------
//	JibberBook v2.0
//	(c) 2007 Chris Jaure
//	license: MIT License
//	website: http://www.chromasynthetic.com/
//
//	admin/login_form.php
//-------------------------------------------------------------------------------------

session_start();
if (isset($_SESSION['admin'])) {
  header("Location: index.php");
  exit(1);
}
require('inc/header.php');
?>
<h2>Log In</h2>
<form action="actions/login.php" method="post">
  <label for="jbpassword">Password:</label> <input type="password" id="jbpassword" name="jbpassword" />
  <input class="button" type="image" alt="Log in" src="media/button.gif" />
</form>
<?php require('inc/footer.php'); ?>
