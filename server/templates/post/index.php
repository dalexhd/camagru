<?php

/**
 * @var \core\View $this
 */

$this->setTitle('Home');
?>
<div class="columns m-0" id="post-wrapper">
	<div class="column is-three-quarters" id="post-container-wrapper">
		<?php $this->element('post/container'); ?>
	</div>
	<div class="column" id="post-comments-wrapper">
		<?php $this->element('post/comments'); ?>
	</div>
</div>
