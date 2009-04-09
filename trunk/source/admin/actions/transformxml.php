<?php
//-------------------------------------------------------------------------------------
//	JibberBook v2.3
//	(c) 2009 Chris Jaure
//	license: MIT License
//	website: http://www.jibberbook.com/
//
//	admin/actions/transformxml.php
//-------------------------------------------------------------------------------------

function transformXML($input, $type){
  if ($type == 'ham'){
    $reclassify = '<a class="mark_spam" href="actions/reclassify.php?type=spam&id=' . $input['id'] . '">' . __('Spam') . '</a>';
  } else {
    $reclassify = '<a class="mark_ham" href="actions/reclassify.php?type=ham&id=' . $input['id'] . '">' . __('Not Spam') . '</a>';
  }
  $input['comment'] = str_replace(array("\r\n", "\n", "\r"), '<br />', htmlspecialchars_decode($input['comment'], ENT_COMPAT));
  ?>
  <div class="comment" id="<?php echo $input['id']; ?>">
    <div class="header">
      <b><?php echo '', $input['name']; ?></b>
      <?php if ((string) $input['website'] != '') : ?>
        | <a href="<?php echo $input['website']; ?>"><?php echo $input['website']; ?></a>
      <?php endif; ?> 
    </div>
    <div class="content">
      <?php echo (string) $input['comment']; ?>
    </div>
    <div class="edit">
      <?php echo date('j M y, H:i:s', (int) $input['date']); ?> - [ 
      <!-- <a class="edit_comment" href="actions/edit.php?id=<?php echo $input['id']; ?>">Edit</a> | --> 
      <a class="delete_comment" href="actions/delete.php?id=<?php echo $input['id']; ?>"><?php _e('Delete'); ?></a> | 
      <?php echo $reclassify; ?> ]
    </div>
  </div>
  <?php
}
?>
