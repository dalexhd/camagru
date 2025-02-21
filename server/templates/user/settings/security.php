<?php

/**
 * @var \core\View $this
 */

$this->setTitle('Settings');
?>
<div class="columns m-0" id="settings-wrapper">
    <div class="column">
        <div class="block pt-5 is-hidden-mobile">
            <h3 class="title is-3 m-0">
                Settings
            </h3>
        </div>
        <div class="block settings-tabs">
            <div class="tabs is-medium">
                <ul>
                    <li class="<?= $this->Url->isActive('accountSettings') ? 'is-active' : '' ?>"><a href="<?php echo $this->Url->link('accountSettings'); ?>">Account</a>
                    <li class="<?= $this->Url->isActive('securitySettings') ? 'is-active' : '' ?>"><a href="<?php echo $this->Url->link('securitySettings'); ?>">Security</a>
                </ul>
            </div>
        </div>
        <div class="block">
            <div class="p-2">
                <h5 class="title is-5">Security</h5>
                <hr>
                <form action="<?= $this->Url->link('securitySettings') ?>" method="post">
                    <div class="field columns">
                        <label class="label column is-one-quarter">Current Password</label>
                        <div class="control column">
                            <input class="input" name="current_password" type="password" placeholder="Current Password">
                        </div>
                    </div>
                    <hr>
                    <div class="field columns">
                        <label class="label column is-one-quarter">New Password</label>
                        <div class="control column">
                            <input class="input" name="password" type="password" placeholder="New Password">
                        </div>
                    </div>
                    <hr>
                    <div class="field columns">
                        <label class="label column is-one-quarter">Confirm Password</label>
                        <div class="control column">
                            <input class="input" name="confirm_password" type="password" placeholder="Confirm Password">
                        </div>
                    </div>
                    <div class="field is-grouped is-justify-content-end">
                        <div class="control">
                            <button class="button is-link">Submit</button>
                        </div>
                        <div class="control">
                            <button type="reset" class="button is-link is-light">Reset</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>