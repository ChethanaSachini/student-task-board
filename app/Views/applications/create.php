<?php
$pageTitle = "Apply for Task | Student Task Board";
$activePage = "my_applications";
require __DIR__ . "/../partials/header.php";
?>

<section class="form-card" aria-labelledby="apply-task-title">
    <h1 id="apply-task-title">Apply for Task</h1>

    <?php if ($message !== ""): ?>
        <p class="notice" role="status"><?= htmlspecialchars($message, ENT_QUOTES, "UTF-8") ?></p>
        <?php if ($existingApplication): ?>
            <p><a href="my_applications.php">View My Applications</a></p>
        <?php endif; ?>
    <?php else: ?>
        <h2><?= htmlspecialchars($task["title"], ENT_QUOTES, "UTF-8") ?></h2>
        <p><?= nl2br(htmlspecialchars($task["description"], ENT_QUOTES, "UTF-8")) ?></p>
        <dl class="task-facts">
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
            <div class="notice notice-error" role="alert">
                <ul>
                    <?php foreach ($errors as $error): ?>
                        <li><?= htmlspecialchars($error, ENT_QUOTES, "UTF-8") ?></li>
                    <?php endforeach; ?>
                </ul>
            </div>
        <?php endif; ?>
        <form method="post" action="apply_task.php?task_id=<?= (int) $task["id"] ?>&amp;from=<?= htmlspecialchars($taskNavigation["source"], ENT_QUOTES, "UTF-8") ?>">
            <input type="hidden" name="csrf_token"
                value="<?= htmlspecialchars($_SESSION["csrf_token"], ENT_QUOTES, "UTF-8") ?>">
            <p class="form-field">
                <label for="message">Message (required, up to 1,000 characters)</label>
                <textarea class="form-control" id="message" name="message" rows="6" cols="50"
                    maxlength="1000" required><?= htmlspecialchars($applicationMessage, ENT_QUOTES, "UTF-8") ?></textarea>
            </p>
            <div class="form-actions">
                <button class="button" type="submit">Submit Application</button>
            </div>
        </form>
    <?php endif; ?>

    <?php if ($task): ?>
        <p><a href="task.php?id=<?= (int) $task["id"] ?>&amp;from=<?= htmlspecialchars($taskNavigation["source"], ENT_QUOTES, "UTF-8") ?>">Cancel and return to task details</a></p>
    <?php else: ?>
        <p><a href="<?= htmlspecialchars($taskNavigation["url"], ENT_QUOTES, "UTF-8") ?>"><?= htmlspecialchars($taskNavigation["label"], ENT_QUOTES, "UTF-8") ?></a></p>
    <?php endif; ?>
</section>

<?php require __DIR__ . "/../partials/footer.php"; ?>
