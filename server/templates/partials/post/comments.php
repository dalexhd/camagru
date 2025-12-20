<?php

/**
 * @var \core\View $this
 */
?>
<div class="post-comments" data-id="{{ id }}">
	<div class="post-comments-header">
		<h5 class="title is-5 m-0">Comments {{ comments.length }}</h5>
		<div class="is-hidden-desktop close-comments">
			<span class="icon is-small">
				<i class="fas fa-times"></i>
			</span>
		</div>
	</div>
	<div class="post-comments-content">
		{{#each comments}}
		{{> comment}}
		{{/each}}
	</div>
	<div class="post-comments-footer">
		<form class="field is-grouped" data-post-id="{{ id }}"
			action="<?= $this->Url->link('post_comment_create', ['id' => '{{ id }}']) ?>" method="POST">
			<input type="hidden" name="post_id" value="{{ id }}">
			<input type="hidden" name="csrf_token" value="<?= \core\Security::generateCSRFToken() ?>">
			<p class="control is-expanded">
				<input class="input" type="text" name="comment" placeholder="Write a comment..."
					id="comment-input-{{ id }}" required>
			</p>
			<p class="control">
				<button class="button is-info add-comment" data-needs-auth>
					Send
				</button>
			</p>
		</form>
	</div>
</div>
</div>