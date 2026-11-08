<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Task Details | Student Task Board</title>
</head>
<body>
    <?php require __DIR__ . "/../partials/nav.php"; ?>

    <?php if ($message !== ""): ?>
        <h1>Task Details</h1>
        <p role="status"><?= htmlspecialchars($message, ENT_QUOTES, "UTF-8") ?></p>
    <?php else: ?>
        <h1><?= htmlspecialchars($task["title"], ENT_QUOTES, "UTF-8") ?></h1>
        <p><?= nl2br(htmlspecialchars($task["description"], ENT_QUOTES, "UTF-8")) ?></p>
        <dl>
            <dt>Category</dt>
            <dd><?= htmlspecialchars($task["category"], ENT_QUOTES, "UTF-8") ?></dd>

            <dt>Deadline</dt>
            <dd>
                <?= htmlspecialchars($task["deadline"], ENT_QUOTES, "UTF-8") ?>
                <?php if ($isExpired): ?>
                    <strong>— Expired: the application deadline has passed.</strong>
                <?php endif; ?>
            </dd>

            <dt>Reward</dt>
            <dd>
                <?php if ($task["reward"] === null): ?>
                    No reward specified
                <?php else: ?>
                    LKR <?= number_format((float) $task["reward"], 2) ?>
                <?php endif; ?>
            </dd>

            <dt>Posted by</dt>
            <dd><?= htmlspecialchars($task["poster_name"], ENT_QUOTES, "UTF-8") ?></dd>

            <dt>Created</dt>
            <dd><?= htmlspecialchars($task["created_at"], ENT_QUOTES, "UTF-8") ?></dd>
        </dl>
    <?php endif; ?>

    <p><a href="tasks.php">Back to Browse Tasks</a></p>
</body>
</html>
