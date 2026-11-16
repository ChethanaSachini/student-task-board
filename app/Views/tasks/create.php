<?php
$pageTitle = "Post Task | Student Task Board";
$activePage = "create_task";
$campusTheme = true;
require __DIR__ . "/../partials/header.php";
?>

<section class="form-card task-form campus-form" aria-labelledby="create-task-title">
    <?php
    $pageHeading = [
        "id" => "create-task-title",
        "title" => "Post Task",
        "description" => "Describe what you need help with so other students can decide whether to apply.",
        "icon" => "document",
    ];
    require __DIR__ . "/../partials/page-heading.php";
    ?>
    <p>All fields are required.</p>

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

    <form method="post" action="create_task.php?from=<?= htmlspecialchars($taskNavigation["source"], ENT_QUOTES, "UTF-8") ?>">
        <input type="hidden" name="csrf_token"
            value="<?= htmlspecialchars(
                $_SESSION["csrf_token"],
                ENT_QUOTES,
                "UTF-8"
            ) ?>">

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
                    <option value="">Select a category</option>
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
                    min="<?= htmlspecialchars($minimumDeadline, ENT_QUOTES, "UTF-8") ?>" required>
                <span class="form-help">Choose today or a later date. Applications will be accepted through the end of that date in Sri Lanka.</span>
            </p>
        </div>

        <p class="form-field">
            <label for="reward">Reward (LKR)</label>
            <input class="form-control" type="number" id="reward" name="reward"
                value="<?= htmlspecialchars($values["reward"], ENT_QUOTES, "UTF-8") ?>"
                min="0.01" max="99999999.99" step="0.01" required>
            <span class="form-help">Enter a monetary reward greater than LKR 0.00, with up to two decimal places.</span>
        </p>

        <div class="form-actions">
            <button class="button" type="submit">Post Task</button>
        </div>
    </form>

    <p class="page-links campus-form-links"><a href="<?= htmlspecialchars($taskNavigation["url"], ENT_QUOTES, "UTF-8") ?>"><?= htmlspecialchars($taskNavigation["label"], ENT_QUOTES, "UTF-8") ?></a></p>
</section>

<?php require __DIR__ . "/../partials/footer.php"; ?>
