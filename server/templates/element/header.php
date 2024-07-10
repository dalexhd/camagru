<?php

/**
 * @var \core\View $this
 */
?>
<header>
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
</header>
