<?php

/**
 * @var \core\View $this
 */

$this->setTitle('Login');

$redirect = isset($_GET['redirect']) ? $_GET['redirect'] : null;
$link = $this->Url->link('login') . ($redirect ? '?redirect=' . urlencode($redirect) : '');
?>


<div class="columns m-0">
	<div class="column is-half is-offset-one-quarter">
		<form action="<?= $link ?>" method="post" class="box">
			<h1 class="title">Login</h1>
			<input type="hidden" name="csrf_token" value="<?= \core\Security::generateCSRFToken() ?>">
			<div class="field">
				<label class="label">Email</label>
				<div class="control">
					<input class="input" type="email" name="email" required>
				</div>
			</div>
			<div class="field">
				<label class="label">Password</label>
				<div class="control">
					<input class="input" type="password" name="password" required>
				</div>
			</div>
			<div class="field">
				<div class="control">
					<div class="columns">
						<div class="column">
							<button class="button is-primary">Login</button>
						</div>
						<div class="column has-text-right">
							<a href="<?= $this->Url->link('recover') ?>">Forgot your password?</a>
						</div>
						<div class="column has-text-right">
							<a href="<?= $this->Url->link('register') ?>">Don't have an account?</a>
						</div>
					</div>
				</div>
			</div>
		</form>
	</div>
</div>