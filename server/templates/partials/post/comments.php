<?php

/**
 * @var \core\View $this
 */
?>
<div class="post-comments" data-id="{{ id }}">
	<div class="post-comments-header">
		<h5 class="title is-5 m-0">Comments {{ id }}</h5>
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
		<div class="field is-grouped">
			<p class="control is-expanded">
				<input class="input" type="text" placeholder="Write a comment...">
			</p>
			<p class="control">
				<button class="button is-info">
					Send
				</button>
			</p>
		</div>
	</div>
</div>
