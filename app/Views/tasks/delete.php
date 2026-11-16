<?php
$pageTitle = "Delete Task | Student Task Board";
$activePage = "my_tasks";
$campusTheme = true;
require __DIR__ . "/../partials/header.php";
?>

<section class="form-card campus-form" aria-labelledby="delete-task-title">
    <?php
    $pageHeading = [
        "id" => "delete-task-title",
        "title" => "Delete Task",
        "description" => "Review the task and deletion warning before confirming.",
        "icon" => "document",
    ];
    require __DIR__ . "/../partials/page-heading.php";
    ?>

    <?php if ($message !== ""): ?>
        <p class="notice" role="status"><?= htmlspecialchars($message, ENT_QUOTES, "UTF-8") ?></p>
    <?php else: ?>
        <h2><?= htmlspecialchars($task["title"], ENT_QUOTES, "UTF-8") ?></h2>
        <p>Number of applications: <?= (int) $applicationCount ?></p>
        <p class="notice notice-error"><strong>Permanently deleting this task will also permanently delete all its applications. This cannot be undone.</strong></p>
        <p>Opening this page does not delete anything. Deletion happens only after you confirm and submit the form.</p>

        <form method="post" action="delete_task.php?id=<?= (int) $task["id"] ?>&amp;from=<?= htmlspecialchars($taskNavigation["source"], ENT_QUOTES, "UTF-8") ?>">
            <input type="hidden" name="csrf_token"
                value="<?= htmlspecialchars($_SESSION["csrf_token"], ENT_QUOTES, "UTF-8") ?>">
            <p class="form-field">
                <label>
                    <input type="checkbox" name="confirm_delete" value="1" required>
                    I understand that this task and all its applications will be permanently deleted.
                </label>
            </p>
            <div class="form-actions">
                <button class="button button-danger" type="submit">Permanently Delete Task</button>
            </div>
        </form>

        <p class="campus-form-links"><a href="task.php?id=<?= (int) $task["id"] ?>&amp;from=<?= htmlspecialchars($taskNavigation["source"], ENT_QUOTES, "UTF-8") ?>">Cancel and return to task details</a></p>
    <?php endif; ?>

    <p class="campus-form-links"><a href="<?= htmlspecialchars($taskNavigation["url"], ENT_QUOTES, "UTF-8") ?>"><?= htmlspecialchars($taskNavigation["label"], ENT_QUOTES, "UTF-8") ?></a></p>
</section>

<?php require __DIR__ . "/../partials/footer.php"; ?>
