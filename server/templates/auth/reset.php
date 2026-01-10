<?php

/**
 * @var \core\View $this
 */

$this->setTitle('Reset Password');
?>

<div class="columns m-0" id="reset-wrapper">
    <div class="column">
        <div class="block pt-5 is-hidden-mobile">
            <h3 class="title is-3 m-0">
                Reset Password
            </h3>
        </div>
        <div class="block">
            <div class="p-2">
                <form action="<?= $this->Url->link('reset', ['token' => $token]) ?>" method="post">
                    <input type="hidden" name="csrf_token" value="<?= \core\Security::generateCSRFToken() ?>">
                    <div class="field columns">
                        <label class="label column is-one-quarter" for="password">New Password</label>
                        <div class="control column">
                            <input class="input" type="password" name="password" id="password"
                                placeholder="Enter new password" required>
                            <p class="help">Password must be at least 8 characters long and include at least one
                                uppercase letter, one lowercase letter, one number, and one special character.</p>
                        </div>
                    </div>
                    <hr>
                    <div class="field columns">
                        <label class="label column is-one-quarter" for="confirm_password">Confirm Password</label>
                        <div class="control column">
                            <input class="input" type="password" name="confirm_password" id="confirm_password"
                                placeholder="Confirm new password" required>
                        </div>
                    </div>
                    <div class="field is-grouped is-justify-content-end">
                        <div class="control">
                            <button class="button is-link">Update Password</button>
                        </div>
                        <div class="control">
                            <a href="<?= $this->Url->link('login') ?>" class="button is-link is-light">Cancel</a>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>