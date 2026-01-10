<?php

/**
 * @var \core\View $this
 */

$this->setTitle('Login');

$redirect = isset($_GET['redirect']) ? $_GET['redirect'] : null;
$link = $this->Url->link('login') . ($redirect ? '?redirect=' . urlencode($redirect) : '');
?>

<div class="columns m-0" id="login-wrapper">
	<div class="column">
		<div class="block pt-5 is-hidden-mobile">
			<h3 class="title is-3 m-0">
				Login
			</h3>
		</div>
		<div class="block">
			<div class="p-2">
				<form action="<?= $link ?>" method="post">
					<input type="hidden" name="csrf_token" value="<?= \core\Security::generateCSRFToken() ?>">
					<div class="field columns">
						<label class="label column is-one-quarter" for="nickname">Nickname</label>
						<div class="control column">
							<input class="input" type="text" name="nickname" id="nickname"
								placeholder="Enter your nickname" required>
						</div>
					</div>
					<hr>
					<div class="field columns">
						<label class="label column is-one-quarter" for="password">Password</label>
						<div class="control column">
							<input class="input" type="password" name="password" id="password"
								placeholder="Enter your password" required>
						</div>
					</div>
					<div class="field is-grouped is-justify-content-end">
						<div class="control">
							<button class="button is-link">Login</button>
						</div>
						<div class="control">
							<a href="<?= $this->Url->link('recover') ?>" class="button is-link is-light">Forgot
								Password?</a>
						</div>
						<div class="control">
							<a href="<?= $this->Url->link('register') ?>" class="button is-link is-light">Register</a>
						</div>
					</div>
				</form>
			</div>
		</div>
	</div>
</div>