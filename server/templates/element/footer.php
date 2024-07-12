<?php

/**
 * @var \core\View $this
 */

$links = [
	[
		'route' => 'home',
		'icon' => 'fas fa-home',
	],
	[
		'route' => 'search',
		'icon' => 'fas fa-search',
	],
	[
		'route' => 'create',
		'icon' => 'fas fa-plus',
	],
	[
		'route' => 'profile',
		'icon' => 'fas fa-user',
	],
	[
		'route' => 'settings',
		'icon' => 'fas fa-cog',
	]
];
?>
<footer class="footer is-hidden-tablet is-fixed-bottom">
	<div class="tabs is-fullwidth">
		<ul>
			<?php foreach ($links as $link) : ?>
				<li class="<?= $this->Url->isActive($link['route']) ? 'is-active' : '' ?>">
					<a href="<?= $this->Url->link($link['route']) ?>">
						<span class="icon">
							<i class="<?= $link['icon'] ?>"></i>
						</span>
					</a>
				</li>
			<?php endforeach; ?>
		</ul>
	</div>
</footer>
