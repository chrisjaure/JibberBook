<?php
//-------------------------------------------------------------------------------------
//	JibberBook v2.3
//	(c) 2009 Chris Jaure
//	license: MIT License
//	website: http://www.jibberbook.com/
//
//	actions/transformxml.php
//-------------------------------------------------------------------------------------

function transformXML($input){
	
    if (JB_EMOTICONS) {
        $input['comment'] = str_replace(array("\r\n", "\n", "\r"), '<br /> ', htmlspecialchars_decode($input['comment'], ENT_QUOTES));
        $input['comment'] = preg_replace_callback("%\w?[O>}\])]?[:;8D=X][',\"]?-?(?:[:SDPCOX#@*$|3<>\\\\]|\)\)?|\(\(?)=?\w?%iS", replaceEmoticons, $input['comment']);
        $input['comment'] = str_replace("'", "&#039;", $input['comment']);
    } else {
        $input['comment'] = str_replace(array("\r\n", "\n", "\r"), '<br /> ', htmlspecialchars_decode($input['comment'], ENT_COMPAT));
    }
	?><div class="comment" id="<?php echo $input['id']; ?>"><div class="header"><?php if (!isset($input['website']{0})) : ?><?php echo $input['name']; ?><?php else : ?><a href="<?php echo $input['website']; ?>" rel="nofollow"><?php echo $input['name']; ?></a><?php endif; ?> <?php _e('writes...'); ?></div><div class="content"><?php echo $input['comment']; ?></div><div class="date"><?php _e('Posted on'); ?> <?php echo date(JB_DATE_FORMAT, (int) $input['date']); ?></div></div><?php
}

function replaceEmoticons($matches) {
    global $EMOTICONS;
    $string = $matches[0];
    if ($EMOTICONS[$string]) {
        $safe = htmlspecialchars($string);
        $emote = '<img src="' . JB_EMOTICONS . $EMOTICONS[$string] . '" title="' . $safe . '" alt="' . $safe . '" />';
        return $emote;
    }
    else {
        return $matches[0];
    }
}
?>
