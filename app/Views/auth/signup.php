<?php
$pageTitle = "Sign Up | Student Task Board";
$activePage = "signup";
require __DIR__ . "/../partials/header.php";
?>

<section class="form-card" aria-labelledby="signup-title">
    <h1 id="signup-title">Create an Account</h1>

    <form method="post" action="signup.php">
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
            <label for="name">Full name</label>
            <input class="form-control" type="text" id="name" name="name"
                   maxlength="100" required>
        </p>

        <p class="form-field">
            <label for="email">Email address</label>
            <input class="form-control" type="email" id="email" name="email"
                   maxlength="254" required>
        </p>

        <p class="form-field">
            <label for="password">Password (at least 8 characters)</label>
            <input class="form-control" type="password" id="password" name="password"
                   minlength="8" required>
        </p>

        <div class="form-actions">
            <button class="button" type="submit">Sign Up</button>
        </div>
    </form>

    <p>Already have an account? <a href="login.php">Log in</a>.</p>
</section>

<?php require __DIR__ . "/../partials/footer.php"; ?>
