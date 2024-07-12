<?php

/**
 * @var \core\View $this
 */
?>

<!DOCTYPE html>
<html class="has-navbar-fixed-top has-navbar-fixed-bottom-touch" lang="en">

<head>
    <title><?php echo $this->title ?? 'My App'; ?></title>

    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <?php echo $this->Html->css('style.css'); ?>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bulma/1.0.1/css/bulma.min.css" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css" />
</head>

<body>
    <?php $this->element('nav'); ?>
    <main class="section">
        <div class="columns">
            <div class="column is-hidden-touch is-1-widescreen is-2-fullhd s-fixed-top"></div>
            <div class="column is-12-tablet is-10-desktop is-10-widescreen is-8-fullhd">
                <?php if ($this->Session->hasFlash('success')) : ?>
                    <div class="flash-message success">
                        <?php echo $this->Session->getFlash('success'); ?>
                    </div>
                <?php endif; ?>
                <?php if ($this->Session->hasFlash('error')) : ?>
                    <div class="flash-message error">
                        <?php echo $this->Session->getFlash('error'); ?>
                    </div>
                <?php endif; ?>
                <?php echo $content; ?>
            </div>
        </div>
    </main>
    <?php $this->element('footer'); ?>
</body>

</html>
