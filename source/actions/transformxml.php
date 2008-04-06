<?php
//-------------------------------------------------------------------------------------
//	JibberBook v2.0
//	(c) 2007 Chris Jaure
//	license: MIT License
//	website: http://www.chromasynthetic.com/
//
//	actions/transformxml.php
//-------------------------------------------------------------------------------------

function transformXML($input){
  $input['comment'] = str_replace(array("\r\n", "\n", "\r"), '<br />', htmlspecialchars_decode($input['comment'], ENT_COMPAT));
  ?><div class="comment" id="<?php echo $input['id']; ?>"><div class="header"><?php if (!isset($input['website']{0})) : ?><?php echo $input['name']; ?><?php else : ?><a href="<?php echo $input['website']; ?>" rel="nofollow"><?php echo $input['name']; ?></a><?php endif; ?> <?php _e('writes...'); ?></div><div class="content"><?php echo $input['comment']; ?></div><div class="date"><?php _e('Posted on'); ?> <?php echo date(JB_DATE_FORMAT, (int) $input['date']); ?></div></div><?php
}
?>
