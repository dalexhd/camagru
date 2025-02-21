<?php

/**
 * @var \core\View $this
 */

$this->setTitle('Register');
?>


<div class="columns m-0">
	<div class="column is-half is-offset-one-quarter">
		<form action="<?php echo $this->Url->link('register'); ?>" method="post" class="box">
			<h1 class="title">Register</h1>
			<div class="field">
				<label class="label">Email</label>
				<div class="control">
					<input class="input" type="email" name="email" required>
				</div>
			</div>
			<div class="field">
				<label class="label">Name</label>
				<div class="control">
					<input class="input" type="text" name="name" required>
				</div>
			</div>
			<div class="field">
				<label class="label">Nickname</label>
				<div class="control">
					<input class="input" type="text" name="nickname" required>
				</div>
			</div>
			<div class="field">
				<label class="label">Password</label>
				<div class="control">
					<input class="input" type="password" name="password" required>
				</div>
			</div>
			<div class="field">
				<label class="label">Confirm Password</label>
				<div class="control">
					<input class="input" type="password" name="confirm_password" required>
				</div>
			</div>
			<div class="field">
				<div class="control">
					<div class="columns">
						<div class="column">
							<button class="button is-primary">Register</button>
						</div>
						<div class="column has-text-right">
							<a href="<?php echo $this->Url->link('login'); ?>">Already have an account?</a>
						</div>
					</div>
				</div>
			</div>
		</form>
	</div>
</div>