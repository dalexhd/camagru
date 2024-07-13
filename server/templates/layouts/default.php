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
            <div class="column is-12-tablet is-10-desktop is-10-widescreen is-8-fullhd is-offset-1-widescreen is-offset-2-fullhd">
                <?php if ($this->Session->hasFlash('success')) : ?>
                    <div class="notification success">
                        <button class="delete"></button>
                        <?php echo $this->Session->getFlash('success'); ?>
                    </div>
                <?php endif; ?>
                <?php if ($this->Session->hasFlash('info')) : ?>
                    <div class="notification is-info">
                        <button class="delete"></button>
                        <?php echo $this->Session->getFlash('info'); ?>
                    </div>
                <?php endif; ?>
                <?php if ($this->Session->hasFlash('error')) : ?>
                    <div class="notification is-danger">
                        <button class="delete"></button>
                        <?php echo $this->Session->getFlash('error'); ?>
                    </div>
                <?php endif; ?>
                <?php echo $content; ?>
            </div>
        </div>
        <a class="button is-primary is-hidden-mobile" id="create-post" href="<?php echo $this->Url->link('create'); ?>">
            <span class="icon">
                <i class="fas fa-plus"></i>
            </span>
            <span>Upload</span>
        </a>
    </main>
    <?php $this->element('footer'); ?>
    <script>
        document.addEventListener('DOMContentLoaded', () => {
            // Close notification
            (document.querySelectorAll('.notification .delete') || []).forEach(($delete) => {
                const $notification = $delete.parentNode;

                $delete.addEventListener('click', () => {
                    $notification.parentNode.removeChild($notification);
                });
            });

            // Auto-hide notification
            (document.querySelectorAll('.notification') || []).forEach(($notification) => {
                setTimeout(() => {
                    $notification.parentNode.removeChild($notification);
                }, 3000);
            });
        });
    </script>
</body>

</html>
