<?php

/**
 * @var \core\View $this
 */

$this->setTitle('Recover Password');
?>

<div class="columns m-0" id="recover-wrapper">
	<div class="column">
		<div class="block pt-5 is-hidden-mobile">
			<h3 class="title is-3 m-0">
				Recover Password
			</h3>
		</div>
		<div class="block">
			<div class="p-2">
				<form action="<?php echo $this->Url->link('recover'); ?>" method="post">
					<input type="hidden" name="csrf_token" value="<?= \core\Security::generateCSRFToken() ?>">
					<div class="field columns">
						<label class="label column is-one-quarter">Email</label>
						<div class="control column">
							<input class="input" type="email" name="email" placeholder="Enter your email" required>
						</div>
					</div>
					<div class="field is-grouped is-justify-content-end">
						<div class="control">
							<button class="button is-link">Send Recovery Link</button>
						</div>
						<div class="control">
							<a href="<?php echo $this->Url->link('login'); ?>" class="button is-link is-light">Back to
								Login</a>
						</div>
					</div>
				</form>
			</div>
		</div>
	</div>
</div>