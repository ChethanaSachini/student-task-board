<?php
$pageTitle = "Task Details | Student Task Board";
$activePage = "tasks";
require __DIR__ . "/../partials/header.php";
?>

<article class="card">
    <?php if ($message !== ""): ?>
        <h1>Task Details</h1>
        <p class="notice" role="status"><?= htmlspecialchars($message, ENT_QUOTES, "UTF-8") ?></p>
    <?php else: ?>
        <h1><?= htmlspecialchars($task["title"], ENT_QUOTES, "UTF-8") ?></h1>
        <p><?= nl2br(htmlspecialchars($task["description"], ENT_QUOTES, "UTF-8")) ?></p>
        <dl class="task-facts">
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
            <div class="form-actions">
                <a class="button" href="task_applications.php?task_id=<?= (int) $task["id"] ?>&amp;from=<?= htmlspecialchars($taskNavigation["source"], ENT_QUOTES, "UTF-8") ?>">View Applicants</a>
                <a class="button button-secondary" href="edit_task.php?id=<?= (int) $task["id"] ?>&amp;from=<?= htmlspecialchars($taskNavigation["source"], ENT_QUOTES, "UTF-8") ?>">Edit Task</a>
                <a class="button button-danger" href="delete_task.php?id=<?= (int) $task["id"] ?>&amp;from=<?= htmlspecialchars($taskNavigation["source"], ENT_QUOTES, "UTF-8") ?>">Delete Task</a>
            </div>
        <?php elseif ($existingApplication): ?>
            <p class="notice">You have already applied for this task. <a href="my_applications.php">View My Applications</a></p>
        <?php elseif (!$isExpired): ?>
            <?php if (isset($_SESSION["user_id"])): ?>
                <div class="form-actions"><a class="button" href="apply_task.php?task_id=<?= (int) $task["id"] ?>&amp;from=<?= htmlspecialchars($taskNavigation["source"], ENT_QUOTES, "UTF-8") ?>">Apply for Task</a></div>
            <?php else: ?>
                <div class="form-actions"><a class="button" href="login.php">Log in to apply</a></div>
            <?php endif; ?>
        <?php endif; ?>
    <?php endif; ?>
</article>

    <p><a href="<?= htmlspecialchars($taskNavigation["url"], ENT_QUOTES, "UTF-8") ?>"><?= htmlspecialchars($taskNavigation["label"], ENT_QUOTES, "UTF-8") ?></a></p>
<?php require __DIR__ . "/../partials/footer.php"; ?>
