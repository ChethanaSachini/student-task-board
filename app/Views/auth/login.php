<?php
$pageTitle = "Login | Student Task Board";
$activePage = "login";
require __DIR__ . "/../partials/header.php";
?>

<section class="form-card" aria-labelledby="login-title">
    <h1 id="login-title">Login</h1>

    <form method="post" action="login.php">
        <input type="hidden" name="csrf_token"
            value="<?= htmlspecialchars(
                $_SESSION["csrf_token"],
                ENT_QUOTES,
                "UTF-8"
            ) ?>">
        <?php if ($message !== ""): ?>
            <p class="notice" role="status">
                <?= htmlspecialchars($message, ENT_QUOTES, "UTF-8") ?>
            </p>
        <?php endif; ?>

        <p class="form-field">
            <label for="email">Email address</label>
            <input class="form-control" type="email" id="email" name="email"
                   autocomplete="username" required>
        </p>

        <p class="form-field">
            <label for="password">Password</label>
            <input class="form-control" type="password" id="password" name="password"
                   autocomplete="current-password" required>
        </p>

        <div class="form-actions">
            <button class="button" type="submit">Login</button>
        </div>
    </form>

    <p>Don't have an account? <a href="signup.php">Sign up</a>.</p>
</section>

<?php require __DIR__ . "/../partials/footer.php"; ?>
