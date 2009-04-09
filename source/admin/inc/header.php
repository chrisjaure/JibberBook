<?php
//-------------------------------------------------------------------------------------
//	JibberBook v2.3
//	(c) 2009 Chris Jaure
//	license: MIT License
//	website: http://www.jibberbook.com/
//
//	admin/inc/header.php
//-------------------------------------------------------------------------------------

header('Content-type: text/html; charset=utf-8');
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">
    <head>
        <title>JibberBook <?php _e('Dashboard'); ?></title>
        <script type="text/javascript" src="http://ajax.googleapis.com/ajax/libs/mootools/1.11/mootools-yui-compressed.js"></script>
        <script type="text/javascript" src="inc/jbascript.js"></script>
        <script type="text/javascript">
            var lang = {
                ERROR: "<?php _e('An error has occurred.'); ?>",
                LOADING: "<?php _e('Loading...'); ?>"
            };
            window.addEvent('load', guestbookAdmin.initialize.pass(lang, guestbookAdmin));
        </script>
        <link rel="stylesheet" type="text/css" href="inc/jbastyle.css"/>
    </head>
    <body>
        <div id="container">
            <div id="header">
                <h1><img src="media/logo.png" alt=""/><strong>Jibber</strong>Book
                    <br/>
                    <span><?php _e('Dashboard'); ?></span>
                </h1>
            </div>
            <div id="nav">
                <?php if (isset($loggedin)) : ?>
                <ul>
                    <li>
                        <a id="recent_link" href="index.php"><?php _e('Recent'); ?></a>
                    </li>
                    <li>
                        <a id="ham_link" href="ham.php"><?php _e('Ham'); ?></a>
                    </li>
                    <li>
                        <a id="spam_link" href="spam.php"><?php _e('Spam'); ?></a>
                    </li>
                    <li>
                        <a id="logout_link" href="actions/logout.php"><?php _e('Logout'); ?></a>
                    </li>
                </ul>
                <?php else : ?>
                <a href="../">&#171; <?php _e('View Guestbook'); ?></a>
                <?php endif; ?>
            </div>
            <div id="content">
                <div id="message" class="<?php echo $_SESSION['message_type']; ?>">
                    <p><?php echo $_SESSION['message']; ?></p>
                </div>
