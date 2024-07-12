<?php

/**
 * @var \core\View $this
 */
?>

<nav class="navbar is-dark is-fixed-top" role="navigation" aria-label="main navigation">
	<div class="nav-container column is-paddingless">
		<div class="navbar-brand">
			<a class="navbar-item" href="/">
				<svg width="512" height="512" viewBox="0 0 512 512" fill="none" xmlns="http://www.w3.org/2000/svg">
					<path d="M256 0 C268.1 0 279.9 0.8 291.5 2.4 L163.6 224 L76.6 73.3 C122.8 28 186.1 0 256 0 z M0 256 C0 196.6 20.2 141.9 54.2 98.5 L182.1 320 L8.1 320 C2.8 299.5 0 278.1 0 256 z M187.5 502.7 C110.6 481.4 48.2 425.1 18.6 352 L274.5 352 L187.5 502.7 z M256 512 C243.9 512 232.1 511.2 220.5 509.6 L348.4 288 L435.4 438.7 C389.2 484 325.9 512 256 512 z M512 256 C512 315.4 491.8 370.1 457.8 413.5 L329.9 192 L503.9 192 C509.2 212.5 512 233.9 512 256 z M324.5 9.3 C401.4 30.6 463.8 86.9 493.4 160 L237.5 160 L324.5 9.300003 z" fill="#fff"></path>
				</svg>
				<span class="has-text-weight-bold ml-2"><? echo $this->name; ?></span>
			</a>
			<div class="buttons ml-auto is-hidden-desktop">
				<a class="button is-light is-outlined is-hidden-mobile">
					Upload
				</a>
				<a class="button is-primary" href="/login">
					<strong>Login</strong>
				</a>
			</div>
		</div>
		<div id="navbarBasicExample" class="navbar-menu">
			<div class="navbar-start is-hidden-mobile">
				<a class="navbar-item">
					Profile
				</a>
				<a class="navbar-item">
					Settings
				</a>
			</div>
			<div class="navbar-end">
				<div class="navbar-item">
					<div class="buttons">
						<a class="button is-light is-outlined">
							Upload
						</a>
						<a class="button is-primary" href="/login">
							<strong>Login</strong>
						</a>
					</div>
				</div>
			</div>
		</div>
	</div>
</nav>

<script>
	document.addEventListener('DOMContentLoaded', () => {
		let screenWidth = window.innerWidth;
		if (screenWidth > 1023) {
			document.querySelector('.navbar').classList.remove('is-fixed-bottom');
		}


		// Get all "navbar-burger" elements
		const $navbarBurgers = Array.prototype.slice.call(document.querySelectorAll('.navbar-burger'), 0);

		// Add a click event on each of them
		$navbarBurgers.forEach(el => {
			el.addEventListener('click', () => {

				// Get the target from the "data-target" attribute
				const target = el.dataset.target;
				const $target = document.getElementById(target);

				// Toggle the "is-active" class on both the "navbar-burger" and the "navbar-menu"
				el.classList.toggle('is-active');
				$target.classList.toggle('is-active');

			});
		});

	});
</script>
