<?php

/**
 * @var \core\View $this
 */

$this->setTitle('Home');
?>

<style>
	#post-index {
		height: 100%;
	}

	#post-index .column {
		height: 100%;
	}
</style>

<div class="columns m-0" id="post-index">
	<div class="column is-three-quarters">
		<?php $this->element('post/container'); ?>
	</div>
	<div class="column is-hidden-mobile">
		<?php $this->element('post/comments'); ?>
	</div>
</div>
