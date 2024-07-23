<?php

/**
 * @var \core\View $this
 */

$this->setTitle('Home');
?>
<div class="columns m-0" id="post-wrapper">
	<div class="column is-three-quarters" id="post-container-wrapper"></div>
	<div class="column" id="post-comments-wrapper"></div>
</div>

<script id="post-template" type="text/x-camgru-template">
	<?php $this->partial('post/container'); ?>
</script>

<script id="comments-template" type="text/x-camgru-template">
	<?php $this->partial('post/comments'); ?>
</script>

<script id="comment-template" type="text/x-camgru-template">
	<?php $this->partial('post/comment'); ?>
</script>
