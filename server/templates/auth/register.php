<?php

/**
 * @var \core\View $this
 */

$this->setTitle('Register');
?>

<div class="columns m-0" id="register-wrapper">
	<div class="column">
		<div class="block pt-5 is-hidden-mobile">
			<h3 class="title is-3 m-0">
				Register
			</h3>
		</div>
		<div class="block">
			<div class="p-2">
				<form action="<?php echo $this->Url->link('register'); ?>" method="post">
					<input type="hidden" name="csrf_token" value="<?= \core\Security::generateCSRFToken() ?>">
					<div class="field columns">
						<label class="label column is-one-quarter" for="email">Email</label>
						<div class="control column">
							<input class="input" type="email" name="email" id="email" placeholder="Enter your email"
								required>
						</div>
					</div>
					<hr>
					<div class="field columns">
						<label class="label column is-one-quarter" for="name">Name</label>
						<div class="control column">
							<input class="input" type="text" name="name" id="name" placeholder="Enter your full name"
								required>
						</div>
					</div>
					<hr>
					<div class="field columns">
						<label class="label column is-one-quarter" for="nickname">Nickname</label>
						<div class="control column">
							<input class="input" type="text" name="nickname" id="nickname"
								placeholder="Choose a nickname" required>
						</div>
					</div>
					<hr>
					<div class="field columns">
						<label class="label column is-one-quarter" for="password">Password</label>
						<div class="control column">
							<input class="input" type="password" name="password" id="password"
								placeholder="Create a password" required>
							<p class="help">Password must be at least 8 characters long and include at least one
								uppercase letter, one lowercase letter, one number, and one special character.</p>
						</div>
					</div>
					<hr>
					<div class="field columns">
						<label class="label column is-one-quarter" for="confirm_password">Confirm Password</label>
						<div class="control column">
							<input class="input" type="password" name="confirm_password" id="confirm_password"
								placeholder="Confirm your password" required>
						</div>
					</div>
					<div class="field is-grouped is-justify-content-end">
						<div class="control">
							<button class="button is-link">Register</button>
						</div>
						<div class="control">
							<a href="<?php echo $this->Url->link('login'); ?>" class="button is-link is-light">Already
								have an account?</a>
						</div>
					</div>
				</form>
			</div>
		</div>
	</div>
</div>