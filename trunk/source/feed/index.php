<?php
//-------------------------------------------------------------------------------------
//	JibberBook v2.3
//	(c) 2009 Chris Jaure
//	license: MIT License
//	website: http://www.jibberbook.com/
//
//	feed/index.php
//-------------------------------------------------------------------------------------

require_once('../inc/includes.php');
header('Content-type: text/xml');
echo '<?xml version="1.0" encoding="UTF-8"?>';
?>
<rss version="2.0">
  <channel>
    <title><?php _e('JibberBook Comments'); ?></title>
    <link><?php echo 'test' ?></link>
    <description><?php _e('Comments for your JibberBook'); ?></description>
    <ttl>5</ttl>

<?php
    $storage = new Comments();
    foreach ($storage->getHam(10) as $comment) :
?>
    <item>
      <title><?php echo $comment['name'], ' ', __('writes...'); ?></title>
      <description><?php echo $comment['comment']; ?></description>
      <pubDate><?php echo date(DATE_RSS, (int) $comment['date']); ?></pubDate>
    </item>
<?php
    endforeach;
?>
  </channel>
</rss>
