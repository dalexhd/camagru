<?php

/**
 * @var \core\View $this
 */
?>
<div class="post-comment-wrapper">
	<div class="comment-avatar">
		<img src="<?php echo $comment['avatar']; ?>" alt="Author avatar">
	</div>
	<div class="comment-author">
		<?php echo $comment['author']; ?>
	</div>
	<div class="comment-text">
		<?php echo $comment['text']; ?>
	</div>
	<div class="comment-actions">
		<div class="comment-action-labels">
			<p class="is-size-7 has-text-grey">
				<time datetime="<?php echo date('c', $comment['created_at']); ?>">
					<?php echo date('d-m-y H:i', $comment['created_at']); ?>
				</time>
			</p>
			<p class="is-size-7 has-text-grey has-text-weight-bold">
				Reply
			</p>
		</div>
		<div class="comment-action-icons">
			<span class="icon">
				<i class="far fa-heart"></i>
			</span>
			<span class="icon">
				<i class="far fa-thumbs-down"></i>
			</span>
		</div>
	</div>
</div>
