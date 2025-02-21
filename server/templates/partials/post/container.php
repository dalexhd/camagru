<?php

/**
 * @var \core\View $this
 */

?>
<div class="post-container" data-id="{{ id }}">
	<div class="post-media">
		<img src="{{ media_src }}" alt="Post media">
	</div>
	<div class="post-actions">
		<div class="buttons">
			<button class="button is-rounded" data-needs-auth>
				<span class="icon">
					<i class="fas fa-heart"></i>
				</span>
			</button>
			<button class="button is-rounded is-hidden-desktop open-comments">
				<span class="icon">
					<i class="fas fa-comment"></i>
				</span>
			</button>
			<button class="button is-rounded post-share">
				<span class="icon">
					<i class="fas fa-share"></i>
				</span>
			</button>
		</div>
	</div>
	<div class="post-content">
		<div class="author">
			<figure class="image is-48x48">
				<img class="is-rounded" src="{{ author.avatar }}" alt="Author avatar">
			</figure>
			<span>{{ author.name }}</span>
		</div>
		<p class="post-text">
			{{ body }}
		</p>
	</div>
</div>