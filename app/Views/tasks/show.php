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
        <?php if (isset($_SESSION["user_id"]) && (int) $_SESSION["user_id"] === (int) $task["user_id"]): ?>
            <p><a href="task_applications.php?task_id=<?= (int) $task["id"] ?>">View Applicants</a></p>
            <p><a href="edit_task.php?id=<?= (int) $task["id"] ?>">Edit Task</a></p>
            <p><a href="delete_task.php?id=<?= (int) $task["id"] ?>">Delete Task</a></p>
        <?php elseif ($existingApplication): ?>
            <p>You have already applied for this task. <a href="my_applications.php">View My Applications</a></p>
        <?php elseif (!$isExpired): ?>
            <?php if (isset($_SESSION["user_id"])): ?>
                <p><a href="apply_task.php?task_id=<?= (int) $task["id"] ?>">Apply for Task</a></p>
            <?php else: ?>
                <p><a href="login.php">Log in to apply</a></p>
            <?php endif; ?>
        <?php endif; ?>
    <?php endif; ?>

    <p><a href="tasks.php">Back to Browse Tasks</a></p>
</body>
</html>
