<?php

/**
 * @var \core\View $this
 */
?>
<div class="post-comment-wrapper">
	<div class="comment-avatar">
		<img src="/{{ avatar }}" alt="Author avatar">
	</div>
	<div class="comment-author">
		<span>{{ nickname }}</span>
	</div>
	<div class="comment-text">
		<p>{{ comment }}</p>
	</div>
	<!-- 	<div class="comment-actions">
		<div class="comment-action-labels">
			<p class="is-size-7 has-text-grey">
				<time datetime="{{ comment.created_at }}">
					{{ created_at }}
				</time>
			</p>
			<p class="is-size-7 has-text-grey has-text-weight-bold" data-needs-auth>
				Reply
			</p>
		</div>
		<div class="comment-action-icons">
			<span class="icon" data-needs-auth>
				<i class="far fa-heart"></i>
			</span>
			<span class="icon" data-needs-auth>
				<i class="far fa-thumbs-down"></i>
			</span>
		</div>
	</div> -->
</div>