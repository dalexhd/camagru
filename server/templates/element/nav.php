<?php

/**
 * @var \core\View $this
 */
?>
<!-- <header>
	<h1>Welcome to <? echo $this->name; ?></h1>
	<?php if ($this->Session->has('user_id')) : ?>
		<nav>
			<ul>
				<li><a href="/">Home</a></li>
				<li><a href="/logout">Logout</a></li>
			</ul>
		</nav>
	<?php else : ?>
		<nav>
			<ul>
				<li><a href="/login">Login</a></li>
				<li><a href="/register">Register</a></li>
			</ul>
		</nav>
	<?php endif; ?>
</header> -->

<nav class="navbar is-dark is-fixed-top" role="navigation" aria-label="main navigation">
	<div class="nav-container column is-paddingless is-12-tablet is-10-desktop is-10-widescreen is-8-fullhd">
		<div class="navbar-brand">
			<a class="navbar-item" href="/">
				<svg width="512" height="512" viewBox="0 0 512 512" fill="none" xmlns="http://www.w3.org/2000/svg">
					<path d="M256 0 C268.1 0 279.9 0.8 291.5 2.4 L163.6 224 L76.6 73.3 C122.8 28 186.1 0 256 0 z M0 256 C0 196.6 20.2 141.9 54.2 98.5 L182.1 320 L8.1 320 C2.8 299.5 0 278.1 0 256 z M187.5 502.7 C110.6 481.4 48.2 425.1 18.6 352 L274.5 352 L187.5 502.7 z M256 512 C243.9 512 232.1 511.2 220.5 509.6 L348.4 288 L435.4 438.7 C389.2 484 325.9 512 256 512 z M512 256 C512 315.4 491.8 370.1 457.8 413.5 L329.9 192 L503.9 192 C509.2 212.5 512 233.9 512 256 z M324.5 9.3 C401.4 30.6 463.8 86.9 493.4 160 L237.5 160 L324.5 9.300003 z" fill="#fff"></path>
				</svg>
				<span class="has-text-weight-bold ml-2"><? echo $this->name; ?></span>
			</a>
			<a role="button" class="navbar-burger" aria-label="menu" aria-expanded="false" data-target="navbarBasicExample">
				<span aria-hidden="true"></span>
				<span aria-hidden="true"></span>
				<span aria-hidden="true"></span>
				<span aria-hidden="true"></span>
			</a>
		</div>
		<div id="navbarBasicExample" class="navbar-menu">
			<div class="navbar-start">
				<a class="navbar-item">
					Home
				</a>
				<a class="navbar-item">
					Documentation
				</a>
			</div>
			<div class="navbar-end">
				<div class="navbar-item">
					<div class="buttons">
						<a class="button is-primary">
							<strong>Sign up</strong>
						</a>
						<a class="button is-light">
							Log in
						</a>
					</div>
				</div>
			</div>
		</div>
	</div>
</nav>
