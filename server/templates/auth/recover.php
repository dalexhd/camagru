<?php

/**
 * @var \core\View $this
 */

$this->setTitle('Register');
?>


<div class="columns m-0">
	<div class="column is-half is-offset-one-quarter">
		<form action="<?php echo $this->Url->link('recover'); ?>" method="post" class="box">
			<h1 class="title">Recover Password</h1>
			<input type="hidden" name="csrf_token" value="<?= \core\Security::generateCSRFToken() ?>">
			<div class="field">
				<label class="label">Email</label>
				<div class="control">
					<input class="input" type="email" name="email" required>
				</div>
			</div>
			<div class="field">
				<div class="control">
					<div class="columns">
						<div class="column">
							<button class="button is-primary">Recover</button>
						</div>
						<div class="column has-text-right">
							<a href="<?php echo $this->Url->link('login'); ?>">Back to Login</a>
						</div>
					</div>
				</div>
			</div>
		</form>
	</div>
</div>