<?php
//-------------------------------------------------------------------------------------
//	JibberBook v2.0
//	(c) 2007 Chris Jaure
//	license: MIT License
//	website: http://www.chromasynthetic.com/
//
//	index.php
//-------------------------------------------------------------------------------------

session_start(); // start the session so we can get any form values or errors set by non-ajax users
require_once('inc/config.php');

?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>JibberBook - Free AJAX Guestbook</title>
<link rel="stylesheet" type="text/css" href="theme/<?php echo JB_THEME ?>/style.css" />
<script type="text/javascript" src="inc/mootools.v1.11.js"></script>
<script type="text/javascript" src="inc/jbscript_min.js"></script>
<script type="text/javascript">
window.addEvent('load', Guestbook.initialize.pass(['jb_addComment', 'jb_comments', 'jb_message', 'jb_loading_message'], Guestbook));
new Asset.css('theme/<?php echo JB_THEME; ?>/style_js.css');
</script>
</head>
<body>
<div id="container">
	<div id="header">
		<h1><img src="theme/<?php echo JB_THEME; ?>/images/logo.png" alt="" /><strong>Jibber</strong>Book</h1>
		<div id="info">&mdash; A Free AJAX Guestbook</div>
	</div>
	<div id="jb_message" class="<?php echo $_SESSION['message_type']; ?>">
    <p>
		  <?php echo $_SESSION['message']; ?>
		</p>
	</div>
	<div id="content">
  	<div id="primary">
    	<form id="jb_addComment" method="post" action="actions/add.php">
        <h3>Add Comment</h3>
        <fieldset>
          <input type="hidden" id="_ajax" name="_ajax" value="false" />
          <label for="name">Name: <span class="required">(required)</span></label>
      		<input id="name" name="name" type="text" value="<?php echo $_SESSION['form_name']; ?>" />
      		<label for="website">Website:</label>
      		<input id="website" name="website" type="text" value="<?php echo $_SESSION['form_website']; ?>" />
      		<label for="comment">Comment: <span class="required">(required)</span> <i>(basic HTML allowed)</i></label>
      		<textarea id="comment" rows="" cols="" name="comment"><?php echo $_SESSION['form_comment']; ?></textarea>
      		<label class="hidden" for="jbemail">Do not fill this field out:</label>
          <input class="hidden" id="jbemail" name="jbemail" type="text" value="" />
      		<input type="submit" class="button" value="Add it!" />
    		</fieldset>
      </form>
    </div>
    <div id="secondary">
      <h3>Comments</h3>
      <div id="jb_comments">
        <?php // get the comments from the xml file and render them
        require_once('actions/loadcomments.php');
        $more = loadComments(1);
        if ($more) : ?>
          <p id="jb_loading_message">
            Only the most recent comments are showing. You need JavaScript to view all the comments.
          </p>
        <?php endif; ?>
      </div>
    </div>
    <div style="clear:both;"></div>
  </div>
  <div id="footer">
    <p>
      <strong>Jibber</strong>Book created by <a href="http://www.chromasynthetic.com/blog/">chromaSYNTHETIC</a> | Powered by <a href="http://mootools.net/">MooTools</a>, <a href="http://htmlpurifier.org/">HTML Purifier</a>, and <a href="http://akismet.com/">Akismet</a>
    </p>
  </div>
</div>
</body>
</html>
<?php
unset($_SESSION['message']);
unset($_SESSION['message_type']);
?>
