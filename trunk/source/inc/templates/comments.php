<div id="jb_comments">
    <?php // get the comments from the xml file and render them
    $more = loadComments(1);
    if ($more) : ?>
    <p id="jb_loading_message">
        <?php _e('Only the most recent comments are showing. You need JavaScript to view them all.'); ?>
    </p>
    <?php endif; ?>
</div>