<?php
//-------------------------------------------------------------------------------------
//	JibberBook v2.3
//	(c) 2009 Chris Jaure
//	license: MIT License
//	website: http://www.jibberbook.com/
//
//	actions/add.php
//-------------------------------------------------------------------------------------

require_once('../inc/includes.php');
includes(array('actions/transformxml.php'));

// reset session vars
session_start();
unset($_SESSION['message_type']);
unset($_SESSION['message']);
unset($_SESSION['form_name']);
unset($_SESSION['form_website']);
unset($_SESSION['form_comment']);

$data = array();
$data['name'] = $_POST['name'];
$data['website'] = $_POST['website'];
$data['comment'] = $_POST['comment'];
$data['jbemail'] = $_POST['jbemail'];

$ajax = ($_POST['_ajax'] == 'true');

foreach ($data as $key => &$datem){ // clean input
    if (get_magic_quotes_gpc()) {
        $datem = stripslashes($datem);
    }
    $datem = trim($datem);
    $datem = iconv("UTF-8", "UTF-8//IGNORE", $datem);
    if ($key != 'comment') {
        $datem = strip_tags($datem);
        $datem = htmlspecialchars($datem, ENT_QUOTES); 
    }
}

$data['date'] = time();
$data['user_ip'] = $_SERVER['REMOTE_ADDR'];
$data['user_agent'] = $_SERVER['HTTP_USER_AGENT'];
$data['spam'] = 0;

require_once("validateform.php");
$message = __('Your comment has been added.');
$value = '1';
validateForm($data);

if (JB_CHAR_LIMIT) {
    if (strlen($data['comment']) > JB_CHAR_LIMIT) {
        $data['comment'] = substr($data['comment'], 0, JB_CHAR_LIMIT) . '...';
    }
}

if (JB_ENABLE_HTML_PURIFIER) {
    //HTMLPurifier filtering
    include("../libraries/htmlpurifier/HTMLPurifier.standalone.php");
    
    $config = HTMLPurifier_Config::createDefault();
    $config->set('Core', 'Encoding', JB_ENCODING);
    $config->set('Core', 'AggressivelyFixLt', true);
    $config->set('HTML', 'Doctype', JB_DOCTYPE);
    $config->set('HTML', 'Allowed', JB_ALLOWED_ELEMENTS);
    
    $purifier = new HTMLPurifier($config);
    
    $data['comment'] = $purifier->purify($data['comment']);
}
else {
    $data['comment'] = strip_tags($data['comment']);
}

$data['comment'] = htmlspecialchars($data['comment'], ENT_QUOTES);

$storage = new Comments();
$data['id'] = $storage->addComment($data);
$_SESSION['time'] = time();

if (JB_EMAIL && !$data['spam']) {
    $to = JB_EMAIL;
    $subject = "JibberBook comment from {$data['name']}!";
    $comment = wordwrap($data['comment'], 70);
    $headers = 'From: noreply@jibberbook.com';
    $find = "/(content-type|bcc:|cc:|to:)/i";
    if (!preg_match($find, $from) && !preg_match($find, $message)) {
        mail($to, $subject, $comment, $headers);
    }
}

if ($ajax) {
    require_once("transformxml.php");
    echo "{'value':'$value', 'content':'";
    transformXML($data);
    echo "', 'message':'$message'}";
}
else {
    $_SESSION['message_type'] = 'confirm';
    $_SESSION['message'] = $message;
    $url = "Location: ../" . JB_INDEX;
    header($url);
}
?>
