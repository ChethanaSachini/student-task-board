<?php
session_start();
if (empty($_SESSION["csrf_token"])) {
    $_SESSION["csrf_token"] = bin2hex(random_bytes(32));
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Student Task Board</title>
</head>
<body>
    <?php require __DIR__ . "/nav.php"; ?>

    <h1>Student Task Board</h1>
    <p>Post a task or find a task you can help with.</p>

    <?php if (isset($_SESSION["user_id"])): ?>
        <p>
            Welcome,
            <?= htmlspecialchars(
                $_SESSION["user_name"],
                ENT_QUOTES,
                "UTF-8"
            ) ?>!
        </p>
    <?php else: ?>
        <p>Please log in to manage your tasks.</p>
    <?php endif; ?>
    <?php if (isset($_SESSION["user_id"])): ?>
        <form method="post" action="logout.php">
            <input type="hidden" name="csrf_token"
                value="<?= htmlspecialchars(
                    $_SESSION["csrf_token"],
                    ENT_QUOTES,
                    "UTF-8"
                ) ?>">
            <button type="submit">Logout</button>
        </form>
    <?php endif; ?>
</body>
</html>