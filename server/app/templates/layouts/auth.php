<!DOCTYPE html>
<html>
<head>
    <title><?php echo $title ?? 'My App'; ?></title>
    <link rel="stylesheet" href="/css/style.css">
</head>
<body>
    <header>
        <h1>Welcome to My Login Layout</h1>
        <?php if (isset($_SESSION['user'])): ?>
            <nav>
                <ul>
                    <li><a href="/">Home</a></li>
                    <li><a href="/logout">Logout</a></li>
                </ul>
            </nav>
        <?php endif; ?>
    </header>

    <main>
        <?php if (Session::hasFlash('success')): ?>
            <div class="flash-message success">
                <?php echo Session::getFlash('success'); ?>
            </div>
        <?php endif; ?>

        <?php if (Session::hasFlash('error')): ?>
            <div class="flash-message error">
                <?php echo Session::getFlash('error'); ?>
            </div>
        <?php endif; ?>

        <?php echo $content; ?>
    </main>

    <footer>
        <p>&copy; 2024 My App</p>
    </footer>
</body>
</html>
