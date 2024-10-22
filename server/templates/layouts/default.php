<?php

/**
 * @var \core\View $this
 */
?>

<!DOCTYPE html>
<html class="has-navbar-fixed-top" lang="en">

<head>
    <title><?php echo $this->title ?? 'My App'; ?></title>

    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <?php echo $this->Html->css('style.css'); ?>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bulma/1.0.1/css/bulma.min.css" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css" />
</head>

<body>
    <div id="app"></div>
    <?php $this->element('nav'); ?>
    <main class="container">
        <?php if ($this->Session->hasFlash('success')) : ?>
            <div class="notification success m-2">
                <button class="delete"></button>
                <?php echo $this->Session->getFlash('success'); ?>
            </div>
        <?php endif; ?>
        <?php if ($this->Session->hasFlash('info')) : ?>
            <div class="notification is-info m-2">
                <button class="delete"></button>
                <?php echo $this->Session->getFlash('info'); ?>
            </div>
        <?php endif; ?>
        <?php if ($this->Session->hasFlash('error')) : ?>
            <div class="notification is-danger m-2">
                <button class="delete"></button>
                <?php echo $this->Session->getFlash('error'); ?>
            </div>
        <?php endif; ?>
        <?php echo $content; ?>
    </main>
    <?php $this->element('footer'); ?>
    <script>
        const isLoggedIn = <?php echo $this->Session->has('user_id') ? 'true' : 'false'; ?>;
    </script>
    <?php echo $this->Html->js('main.js', ['defer' => 'defer', 'type' => 'module']); ?>
</body>

</html>
