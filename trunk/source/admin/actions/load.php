<?php
//-------------------------------------------------------------------------------------
//	JibberBook v2.3
//	(c) 2009 Chris Jaure
//	license: MIT License
//	website: http://www.jibberbook.com/
//
//	admin/actions/load.php
//-------------------------------------------------------------------------------------

function loadHam($num = null) {
  load(0, $num);
}

function loadSpam($num = null) {
  load(1, $num);
}

function load($type, $show) {
    $pages = '';
	$max = 50;
	$storage = new Comments();
    if ($show == 'paginate') {
        $count = $storage->getCount($type);
        $page = (isset($_GET['page'])) ? (int) $_GET['page'] : 1;
        $offset = array(
            'lower' => $count - $page * $max,
            'upper' => $count - ($page - 1) * $max
        );
        
        $pages = '<div class="result_nav">';
        if ($page > 1) {
            $pages .= '<a href="?page=1"><img src="media/resultset_first.png" alt="' . __('First') . '" title="' . __('First') . '" /></a><a href="?page=' . ($page - 1) . '"><img src="media/resultset_previous.png" alt="' . __('Previous') . '" title="' . __('Previous') . '" /></a>';
        }
        if ($count > $max * $page) {
            $pages .= '<a href="?page=' . ($page + 1) . '"><img src="media/resultset_next.png" alt="' . __('Next') . '" title="' . __('Next') . '" /></a><a href="?page=' . ceil($count / $max) . '"><img src="media/resultset_last.png" alt="' . __('Last') . '" title="' . __('Last') . '" /></a>';
        }
        $pages .= '</div>';
    }
    else $offset = $show;
    $messages = $storage->getComments($type, $offset);
    if (count($messages) == 0) {
        echo '<p>' . __('No comments to load.') . '</p>';
    }

    echo $pages;
    foreach ($messages as $message) {
        if ($message['name'] == '') continue;
        transformXML($message, $type);
    }
    echo $pages;
}
?>