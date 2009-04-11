<?php
//-------------------------------------------------------------------------------------
//	JibberBook v2.3
//	(c) 2009 Chris Jaure
//	license: MIT License
//	website: http://www.jibberbook.com/
//
//	actions/validateform.php
//-------------------------------------------------------------------------------------

function validateForm(&$formData){
    global $ajax, $message, $value; // $ajax set in add.php
    $errornum = null;
    $errordesc = '';
    if ($formData['website'] != '') {
        if (!preg_match('#((http://)|(www\.))+(([a-zA-Z0-9\._-]+\.[a-zA-Z]{2,6})|([0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}))(/[a-zA-Z0-9\&amp;%_\./-~-]*)?#', $formData['website'])) {
            $errornum = '4';
            $errordesc = __('Website is not a valid URL.');
        }
        if (substr($formData['website'], 0, 7) != 'http://') {
            $formData['website'] = 'http://' . $formData['website'];
        }
    }
    if ($formData['name'] == '' || $formData['comment'] == ''){
        $errornum = '3';
        $errordesc = __('Name and Comment fields are required.');
    }
    if ($formData['jbemail'] != ''){ // if this field is filled in, it's probably spam
        $formData['spam'] = 1;
    }
    unset($formData['jbemail']);
    
    if (JB_AKISMET_KEY != '' && !$formData['spam']) {
        require_once('../libraries/microakismet/class.microakismet.inc.php');
        $vars = array();
        // required
        $vars['user_ip'] = $formData['user_ip'];
        $vars['user_agent'] = $formData['user_agent'];
        // optional
        $vars['comment_content'] = $formData['comment'];
        $vars['comment_author'] = $formData['name'];
        $vars['comment_author_url'] = $formData['website'];
        $vars['referrer'] = $_SERVER['HTTP_REFERER'];
        
        $akismet = new MicroAkismet(JB_AKISMET_KEY, JB_GUESTBOOK_URL, 'JibberBook/' . JB_VERSION . ' | microakismet/' . JB_MA_VERSION);
        
        $formData['spam'] = ($akismet->check($vars)) ? 1 : 0;
    }
    
    if (isset($_SESSION['time']) && !isset($errornum)) {
        if ($formData['spam']) {
            if ($_SESSION['time'] + 60 > time()) {
                $errornum = '5';
                $errordesc = __('Your comment was discarded because it was flagged as spam.');
            }
        } elseif ($_SESSION['time'] + 20 > time()) {
            $errornum = '5';
            $errordesc = __('Please wait a bit before adding another comment.');
        }
    }
    
    if (!JB_KEEP_SPAM && $formData['spam']) {
      $errornum = '6';
      $errordesc = __('Your comment was discarded because it was flagged as spam.');
    }
    
    // if there's an error, return json object or set session vars, and then stop further actions
    if (isset($errornum)) {
        if ($ajax) echo "{'value':'$errornum', 'message':'$errordesc'}";
        else {
            $_SESSION['message_type'] = 'error';
            $_SESSION['message'] = $errordesc;
            $_SESSION['form_name'] = $formData['name'];
            $_SESSION['form_website'] = $formData['website'];
            $_SESSION['form_comment'] = $formData['comment'];
            $url = "Location: ../" . JB_INDEX;
            header($url);
        }
        exit(1);
    }
    
    if ($formData['spam']) {
        $value = '2';
        $message = __('This comment has been flagged as spam and has been added to the moderation queue.');
    }
}
?>
