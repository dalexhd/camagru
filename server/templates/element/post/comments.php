<?php

/**
 * @var \core\View $this
 */
$comments = [
	[
		'author' => 'Author 1',
		'avatar' => 'https://picsum.photos/24',
		'text' => 'First comment, hello world!',
		'created_at' => date(strtotime('-1 hour'))
	],
	[
		'author' => 'Author 2',
		'avatar' => 'https://picsum.photos/24',
		'text' => 'Second large comment, hello world! Lorem ipsum dolor sit amet.',
		'created_at' => date(strtotime('-2 hours'))
	],
	[
		'author' => 'Author 3',
		'avatar' => 'https://picsum.photos/24',
		'text' => 'Third comment, hello world!',
		'created_at' => date(strtotime('-1 day'))
	],
	[
		'author' => 'Author 4',
		'avatar' => 'https://picsum.photos/24',
		'text' => 'Fourth comment, hello world!',
		'created_at' => date(strtotime('-1 week'))
	],
];

$posts = [
	[
		'author' => 'Author 1',
		'avatar' => 'https://picsum.photos/24',
		'text' => 'First post, hello world!',
		'created_at' => date(strtotime('-1 hour')),
		'comments' => $comments
	],
	[
		'author' => 'Author 2',
		'avatar' => 'https://picsum.photos/24',
		'text' => 'Second post, hello world!',
		'created_at' => date(strtotime('-2 hour')),
		'comments' => $comments
	]
];
?>
<div id="post-comments">
	<div id="post-comments-header">
		<h5 class="title is-5 m-0">Comments</h5>
		<div class="is-hidden-desktop close-comments">
			<span class="icon is-small">
				<i class="fas fa-times"></i>
			</span>
		</div>
	</div>
	<div id="post-comments-content">
		<?php foreach ($comments as $comment) : ?>
			<?php $this->element('post/comment', ['comment' => $comment]); ?>
		<?php endforeach; ?>
	</div>
	<div id="post-comments-footer">
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
