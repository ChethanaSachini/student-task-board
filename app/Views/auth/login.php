<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Login | Student Task Board</title>
</head>
<body>
    <?php require __DIR__ . "/../partials/nav.php"; ?>

    <h1>Login</h1>

    <form method="post" action="login.php">
        <input type="hidden" name="csrf_token"
            value="<?= htmlspecialchars(
                $_SESSION["csrf_token"],
                ENT_QUOTES,
                "UTF-8"
            ) ?>">
        <?php if ($message !== ""): ?>
            <p role="status">
                <?= htmlspecialchars($message, ENT_QUOTES, "UTF-8") ?>
            </p>
        <?php endif; ?>

        <p>
            <label for="email">Email address</label><br>
            <input type="email" id="email" name="email"
                   autocomplete="username" required>
        </p>

        <p>
            <label for="password">Password</label><br>
            <input type="password" id="password" name="password"
                   autocomplete="current-password" required>
        </p>

        <button type="submit">Login</button>
    </form>

    <p>Don't have an account? <a href="signup.php">Sign up</a>.</p>
</body>
</html>
