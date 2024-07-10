<?php

/**
 * @var \core\View $this
 */

$this->setTitle('Login');
?>

<form action="<?php echo $this->Url->link('login'); ?>" method="post">
	<label for="email">Email:</label>
	<input type="email" id="email" name="email" required>
	<label for="password">Password:</label>
	<input type="password" id="password" name="password" required>
	<button type="submit">Login</button>
</form>
