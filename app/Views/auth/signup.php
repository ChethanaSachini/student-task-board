<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Sign Up | Student Task Board</title>
</head>
<body>
    <?php require __DIR__ . "/../partials/nav.php"; ?>

    <h1>Create an Account</h1>

    <form method="post" action="signup.php">
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
            <label for="name">Full name</label><br>
            <input type="text" id="name" name="name"
                   maxlength="100" required>
        </p>

        <p>
            <label for="email">Email address</label><br>
            <input type="email" id="email" name="email"
                   maxlength="254" required>
        </p>

        <p>
            <label for="password">Password (at least 8 characters)</label><br>
            <input type="password" id="password" name="password"
                   minlength="8" required>
        </p>

        <button type="submit">Sign Up</button>
    </form>
</body>
</html>
