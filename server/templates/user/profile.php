<?php

/**
 * @var \core\View $this
 */

$this->setTitle('Profile');
?>

<h1>
	Profile Page for <?= $this->Session->get('user_name') ?>
</h1>
