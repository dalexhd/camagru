<?php

/**
 * @var \core\View $this
 */

?>
<div class="post-container" data-id="{{ id }}">
	<div class="post-media">
		<img src="/{{ media_src }}" alt="Post media">
	</div>
	<div class="post-actions">
		<div class="buttons">
			<form method="post" action="<?= $this->Url->link('post_like_toggle', ['id' => '{{ id }}']); ?>" class="like-form">
				<input type="hidden" name="post_id" value="{{ id }}">
				<button type="submit" class="button is-rounded like-button {{#if liked_by_user}}is-danger{{/if}}" data-needs-auth>
					<div class="d-block likes-count">{{ likes_count }}</div>
					<span class="icon">
						<i class="fas fa-heart"></i>
					</span>
				</button>
			</form>
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
				<img class="is-rounded" src="/{{ author.avatar }}" alt="Author avatar">
			</figure>
			<span>{{ author.name }}</span>
		</div>
		<p class="post-text">
			{{ body }}
		</p>
	</div>
</div>