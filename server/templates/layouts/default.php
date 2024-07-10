<?php

/**
 * @var \core\View $this
 */
?>

<!DOCTYPE html>
<html>

<head>
    <title><?php echo $this->title ?? 'My App'; ?></title>
    <?php echo $this->Html->css('style.css'); ?>
</head>

<body>
    <?php $this->element('header'); ?>

    <main>
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
    </main>

    <?php $this->element('footer'); ?>
</body>

</html>
