<?php

/**
 * @var \core\View $this
 */

$this->setTitle('404 Not Found');
?>

<div class="columns m-0" id="error-wrapper">
    <div class="column is-three-quarters" id="error-container-wrapper">
        <div id="error-content-wrapper">
            <div class="error-content content">
                <h2><?php echo $message; ?></h2>
                <p>Click the button below to go to the home page:</p>
                <a href="<?php echo $this->Url->link('home'); ?>" class="button is-primary">Go to Home</a>
            </div>
        </div>
    </div>
    <div class="column" id="error-content-wrapper"></div>
</div>