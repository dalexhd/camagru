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
                    <li class="<?= $this->Url->isActive('accountSettings') ? 'is-active' : '' ?>"><a
                            href="<?php echo $this->Url->link('accountSettings'); ?>">Account</a>
                    <li class="<?= $this->Url->isActive('securitySettings') ? 'is-active' : '' ?>"><a
                            href="<?php echo $this->Url->link('securitySettings'); ?>">Security</a>
                </ul>
            </div>
        </div>
        <div class="block">
            <div class="p-2">
                <h5 class="title is-5">Your Profile</h5>
                <h5 class="subtitle is-5">Please update yout profile settings here</h5>
                <hr>
                <form action="<?= $this->Url->link('accountSettings') ?>" method="post" enctype="multipart/form-data">
                    <input type="hidden" name="csrf_token" value="<?= \core\Security::generateCSRFToken() ?>">
                    <div class="field columns">
                        <label class="label column is-one-quarter" for="email">Email</label>
                        <div class="control column">
                            <input class="input" name="email" type="text" id="email" placeholder="Email"
                                value="<?= $this->escape($this->Session->get('user_email')); ?>" required>
                        </div>
                    </div>
                    <hr>
                    <div class="field columns">
                        <label class="label column is-one-quarter" for="nickname">Nickname</label>
                        <div class="control column">
                            <input class="input" name="nickname" type="text" id="nickname" placeholder="Nickname"
                                value="<?= $this->escape($this->Session->get('user_nickname')); ?>" required>
                        </div>
                    </div>
                    <hr>
                    <div class="field columns">
                        <label class="label column is-one-quarter" for="name">Name</label>
                        <div class="control column">
                            <input class="input" name="name" type="text" id="name" placeholder="Name"
                                value="<?= $this->escape($this->Session->get('user_name')); ?>" required>
                        </div>
                    </div>
                    <hr>
                    <div class="field columns">
                        <div class="column is-one-quarter is-flex is-justify-content-space-between">
                            <label class="label" for="avatar">Avatar</label>
                            <?php if ($this->Session->has('user_avatar')) { ?>
                                <figure class="image is-128x128 pt-5">
                                    <img class="is-rounded" src="<?= $this->escape($this->Session->get('user_avatar')); ?>"
                                        alt="User avatar">
                                </figure>
                            <?php } ?>
                        </div>
                        <div class="control column">
                            <div id="file-js-example" class="file has-name">
                                <label class="file-label">
                                    <input class="file-input" type="file" name="avatar" id="avatar">
                                    <span class="file-cta">
                                        <span class="file-icon">
                                            <i class="fas fa-upload"></i>
                                        </span>
                                        <span class="file-label"> Choose a file… </span>
                                    </span>
                                    <span class="file-name"> No file uploaded </span>
                                </label>
                            </div>
                        </div>
                    </div>
                    <hr>
                    <div class="field columns">
                        <label class="label column is-one-quarter" for="notifications_enabled">Notifications</label>
                        <div class="control column">
                            <label class="checkbox">
                                <input type="checkbox" name="notifications_enabled" id="notifications_enabled" value="1"
                                    <?= $this->Session->get('user_notifications_enabled') ? 'checked' : '' ?>>
                                Receive comment notifications
                            </label>
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