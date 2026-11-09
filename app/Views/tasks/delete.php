<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Delete Task | Student Task Board</title>
</head>
<body>
    <?php require __DIR__ . "/../partials/nav.php"; ?>

    <h1>Delete Task</h1>

    <?php if ($message !== ""): ?>
        <p role="status"><?= htmlspecialchars($message, ENT_QUOTES, "UTF-8") ?></p>
    <?php else: ?>
        <h2><?= htmlspecialchars($task["title"], ENT_QUOTES, "UTF-8") ?></h2>
        <p>Number of applications: <?= (int) $applicationCount ?></p>
        <p><strong>Permanently deleting this task will also permanently delete all its applications. This cannot be undone.</strong></p>
        <p>Opening this page does not delete anything. Deletion happens only after you confirm and submit the form.</p>

        <form method="post" action="delete_task.php?id=<?= (int) $task["id"] ?>">
            <input type="hidden" name="csrf_token"
                value="<?= htmlspecialchars($_SESSION["csrf_token"], ENT_QUOTES, "UTF-8") ?>">
            <p>
                <label>
                    <input type="checkbox" name="confirm_delete" value="1" required>
                    I understand that this task and all its applications will be permanently deleted.
                </label>
            </p>
            <button type="submit">Permanently Delete Task</button>
        </form>

        <p><a href="task.php?id=<?= (int) $task["id"] ?>">Cancel and return to task details</a></p>
    <?php endif; ?>

    <p><a href="my_tasks.php">Back to My Tasks</a></p>
</body>
</html>
