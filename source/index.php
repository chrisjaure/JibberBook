<?php
//-------------------------------------------------------------------------------------
//	JibberBook v2.1
//	(c) 2008 Chris Jaure
//	license: MIT License
//	website: http://www.jibberbook.com/
//
//	index.php
//-------------------------------------------------------------------------------------

session_start(); // start the session so we can get any form values or errors set by non-ajax users
require_once('inc/includes.php');
includes(array('actions/loadcomments.php', 'actions/transformxml.php'));

?>
 <!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
        <title>JibberBook - Free AJAX Guestbook</title>
        <link rel="stylesheet" type="text/css" href="theme/<?php echo JB_THEME ?>/style.css"/>
        <link rel="alternate" type="application/rss+xml" title="RSS" href="feed/" />
        <script type="text/javascript" src="inc/mootools.v1.11.js">
        </script>
        <script type="text/javascript" src="inc/jbscript.js">
        </script>
        <script type="text/javascript">
            var lang = {
                SERVER_ERROR: "<?php _e('An error has occurred.'); ?>",
                COMMENTS_LOADED: "<?php _e('All comments have been loaded.'); ?>",
                LOADING: "<?php _e('Loading...'); ?>",
                ERROR: "<?php _e('Your comment could not be added. Please try again later.'); ?>",
                COMMENTS_LOADING: "<?php _e('More comments are loading. If you are using the scrollbar, please release your mouse.') ?>"
            };
            window.addEvent('load', Guestbook.initialize.pass(['jb_addComment', 'jb_comments', 'jb_message', 'jb_loading_message', lang], Guestbook));
            new Asset.css('theme/<?php echo JB_THEME; ?>/style_js.css');
        </script>
    </head>
    <body>
        <div id="container">
            <div id="header">
                <h1><img src="theme/<?php echo JB_THEME; ?>/images/logo.png" alt=""/><strong>Jibber</strong>Book</h1>
                <div id="info">
                    &mdash; A Free AJAX Guestbook
                </div>
            </div>
            <div id="jb_message" class="<?php echo $_SESSION['message_type']; ?>">
                <p>
                    <?php echo $_SESSION['message']; ?>
                </p>
            </div>
            <div id="content">
                <div id="primary">
                    <?php include('inc/templates/form.php'); ?>
                </div>
                <div id="secondary">
                    <h3>
                        <?php _e('Comments'); ?>
                    </h3>
                    <?php include('inc/templates/comments.php'); ?>
                </div>
                <div style="clear:both;">
                </div>
            </div>
            <div id="footer">
                <p>
                    <strong>Jibber</strong>Book created by <a href="http:http://www.jibberbook.com/">chromaSYNTHETIC</a>
                    | Powered by <a href="http://mootools.net/">MooTools</a>, <a href="http://htmlpurifier.org/">HTML Purifier</a>, and <a href="http://akismet.com/">Akismet</a>
                </p>
            </div>
        </div>
    </body>
</html>
<?php
unset($_SESSION['message']);
unset($_SESSION['message_type']);
?>
