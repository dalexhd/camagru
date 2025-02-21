<?php

/**
 * @var \core\View $this
 */

$this->setTitle('Settings');
?>
<div class="columns m-0" id="settings-wrapper">
	<div class="column">
		<div class="block pt-5">
			<h3 class="title is-3 m-0">
				Settings
			</h3>
		</div>
		<div class="block settings-tabs">
			<div class="tabs is-medium">
				<ul>
					<li class="<?= $this->Url->isActive('settings') ? 'is-active' : '' ?>"><a href="<?php echo $this->Url->link('settings'); ?>">General</a>
					<li class="<?= $this->Url->isActive('accountSettings') ? 'is-active' : '' ?>"><a href="<?php echo $this->Url->link('accountSettings'); ?>">Account</a>
					<li class="<?= $this->Url->isActive('securitySettings') ? 'is-active' : '' ?>"><a href="<?php echo $this->Url->link('securitySettings'); ?>">Security</a>
				</ul>
			</div>
		</div>
		<div class="block">
			<div class="p-2">
				<h5 class="title is-5">General</h5>
				<hr>
			</div>
		</div>
	</div>
</div>