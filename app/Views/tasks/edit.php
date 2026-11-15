<?php
$pageTitle = "Edit Task | Student Task Board";
$activePage = "my_tasks";
require __DIR__ . "/../partials/header.php";
?>

<section class="form-card" aria-labelledby="edit-task-title">
    <h1 id="edit-task-title">Edit Task</h1>

    <?php if ($message !== ""): ?>
        <p class="notice" role="status"><?= htmlspecialchars($message, ENT_QUOTES, "UTF-8") ?></p>
    <?php else: ?>
        <p>All fields are required except the reward.</p>

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

        <form method="post" action="edit_task.php?id=<?= (int) $task["id"] ?>">
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

            <p class="form-field">
                <label for="reward">Reward (LKR, optional)</label>
                <input class="form-control" type="number" id="reward" name="reward"
                    value="<?= htmlspecialchars($values["reward"], ENT_QUOTES, "UTF-8") ?>"
                    min="0" max="99999999.99" step="0.01" <?= $termsLocked ? "disabled" : "" ?>>
                <?php if ($termsLocked): ?>
                    <strong>Locked</strong>
                    <?php if ($task["reward"] === null): ?>
                        No reward specified
                    <?php endif; ?>
                <?php else: ?>
                    <br><span class="form-help">Leave blank for no reward, or enter an amount of zero or more with up to two decimal places.</span>
                <?php endif; ?>
            </p>

            <div class="form-actions">
                <button class="button" type="submit">Save Changes</button>
            </div>
        </form>

        <p><a href="task.php?id=<?= (int) $task["id"] ?>">Cancel and return to task details</a></p>
    <?php endif; ?>

    <p><a href="my_tasks.php">Back to My Tasks</a></p>
</section>

<?php require __DIR__ . "/../partials/footer.php"; ?>
