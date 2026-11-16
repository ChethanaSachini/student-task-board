<?php
$pageTitle = "Edit Task | Student Task Board";
$activePage = "my_tasks";
$campusTheme = true;
require __DIR__ . "/../partials/header.php";
?>

<section class="form-card campus-form" aria-labelledby="edit-task-title">
    <?php
    $pageHeading = [
        "id" => "edit-task-title",
        "title" => "Edit Task",
        "description" => "Update your task while keeping existing applications and their agreed terms protected.",
        "icon" => "document",
    ];
    require __DIR__ . "/../partials/page-heading.php";
    ?>

    <?php if ($message !== ""): ?>
        <p class="notice" role="status"><?= htmlspecialchars($message, ENT_QUOTES, "UTF-8") ?></p>
    <?php else: ?>
        <p>All editable fields are required.</p>

        <?php if (!empty($errors)): ?>
            <div class="notice notice-error" role="alert">
                <p>Please check the following:</p>
                <ul>
                    <?php foreach ($errors as $error): ?>
                        <li><?= htmlspecialchars($error, ENT_QUOTES, "UTF-8") ?></li>
                    <?php endforeach; ?>
                </ul>
            </div>
        <?php endif; ?>

        <?php if ($isExpired): ?>
            <p class="notice">This task's application deadline has passed. Its existing deadline is shown below.</p>
        <?php endif; ?>

        <?php if ($termsLocked): ?>
            <p class="notice">Applications already exist for this task. Its reward and deadline are locked and cannot be changed. You may still edit its title, description and category.</p>
        <?php endif; ?>

        <form method="post" action="edit_task.php?id=<?= (int) $task["id"] ?>&amp;from=<?= htmlspecialchars($taskNavigation["source"], ENT_QUOTES, "UTF-8") ?>">
            <input type="hidden" name="csrf_token"
                value="<?= htmlspecialchars($_SESSION["csrf_token"], ENT_QUOTES, "UTF-8") ?>">

            <p class="form-field">
                <label for="title">Title</label>
                <input class="form-control" type="text" id="title" name="title" maxlength="150"
                    value="<?= htmlspecialchars($values["title"], ENT_QUOTES, "UTF-8") ?>" required>
            </p>

            <p class="form-field">
                <label for="description">Description</label>
                <textarea class="form-control" id="description" name="description" rows="6" cols="50"
                    maxlength="5000" required><?= htmlspecialchars($values["description"], ENT_QUOTES, "UTF-8") ?></textarea>
            </p>

            <div class="campus-form-row">
                <p class="form-field">
                    <label for="category">Category</label>
                    <select class="form-control" id="category" name="category" required>
                        <?php foreach ($categories as $category): ?>
                            <option value="<?= htmlspecialchars($category, ENT_QUOTES, "UTF-8") ?>"
                                <?= $values["category"] === $category ? "selected" : "" ?>>
                                <?= htmlspecialchars($category, ENT_QUOTES, "UTF-8") ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </p>

                <p class="form-field">
                    <label for="deadline">Application deadline</label>
                    <input class="form-control" type="date" id="deadline" name="deadline"
                        value="<?= htmlspecialchars($values["deadline"], ENT_QUOTES, "UTF-8") ?>"
                        <?= $termsLocked ? "disabled" : "" ?> required>
                    <?php if ($termsLocked): ?>
                        <strong>Locked</strong>
                    <?php else: ?>
                        <br><span class="form-help">An existing expired deadline may be kept. A changed deadline must be today or later in Sri Lanka.</span>
                    <?php endif; ?>
                </p>
            </div>

            <p class="form-field">
                <label for="reward">Reward (LKR)</label>
                <input class="form-control" type="number" id="reward" name="reward"
                    value="<?= htmlspecialchars($values["reward"], ENT_QUOTES, "UTF-8") ?>"
                    min="0.01" max="99999999.99" step="0.01" <?= $termsLocked ? "disabled" : "" ?> required>
                <?php if ($termsLocked): ?>
                    <strong>Locked</strong>
                    <?php if ($task["reward"] === null): ?>
                        No reward specified
                    <?php endif; ?>
                <?php else: ?>
                    <br><span class="form-help">Enter a monetary reward greater than LKR 0.00, with up to two decimal places.</span>
                <?php endif; ?>
            </p>

            <div class="form-actions">
                <button class="button" type="submit">Save Changes</button>
            </div>
        </form>

        <p class="campus-form-links"><a href="task.php?id=<?= (int) $task["id"] ?>&amp;from=<?= htmlspecialchars($taskNavigation["source"], ENT_QUOTES, "UTF-8") ?>">Cancel and return to task details</a></p>
    <?php endif; ?>

    <p class="campus-form-links"><a href="<?= htmlspecialchars($taskNavigation["url"], ENT_QUOTES, "UTF-8") ?>"><?= htmlspecialchars($taskNavigation["label"], ENT_QUOTES, "UTF-8") ?></a></p>
</section>

<?php require __DIR__ . "/../partials/footer.php"; ?>
