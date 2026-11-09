<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Apply for Task | Student Task Board</title>
</head>
<body>
    <?php require __DIR__ . "/../partials/nav.php"; ?>

    <h1>Apply for Task</h1>

    <?php if ($message !== ""): ?>
        <p role="status"><?= htmlspecialchars($message, ENT_QUOTES, "UTF-8") ?></p>
        <?php if ($existingApplication): ?>
            <p><a href="my_applications.php">View My Applications</a></p>
        <?php endif; ?>
    <?php else: ?>
        <h2><?= htmlspecialchars($task["title"], ENT_QUOTES, "UTF-8") ?></h2>
        <p><?= nl2br(htmlspecialchars($task["description"], ENT_QUOTES, "UTF-8")) ?></p>
        <dl>
            <dt>Category</dt>
            <dd><?= htmlspecialchars($task["category"], ENT_QUOTES, "UTF-8") ?></dd>
            <dt>Application deadline</dt>
            <dd><?= htmlspecialchars($task["deadline"], ENT_QUOTES, "UTF-8") ?></dd>
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
        </dl>

        <?php if (!empty($errors)): ?>
            <div role="alert">
                <ul>
                    <?php foreach ($errors as $error): ?>
                        <li><?= htmlspecialchars($error, ENT_QUOTES, "UTF-8") ?></li>
                    <?php endforeach; ?>
                </ul>
            </div>
        <?php endif; ?>
        <form method="post" action="apply_task.php?task_id=<?= (int) $task["id"] ?>">
            <input type="hidden" name="csrf_token"
                value="<?= htmlspecialchars($_SESSION["csrf_token"], ENT_QUOTES, "UTF-8") ?>">
            <p>
                <label for="message">Message (required, up to 1,000 characters)</label><br>
                <textarea id="message" name="message" rows="6" cols="50"
                    maxlength="1000" required><?= htmlspecialchars($applicationMessage, ENT_QUOTES, "UTF-8") ?></textarea>
            </p>
            <button type="submit">Submit Application</button>
        </form>
    <?php endif; ?>

    <?php if ($task): ?>
        <p><a href="task.php?id=<?= (int) $task["id"] ?>">Cancel and return to task details</a></p>
    <?php else: ?>
        <p><a href="tasks.php">Back to Browse Tasks</a></p>
    <?php endif; ?>
</body>
</html>
