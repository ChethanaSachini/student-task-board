<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Student Task Board</title>
</head>
<body>
    <?php require __DIR__ . "/../partials/nav.php"; ?>

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
</body>
</html>
