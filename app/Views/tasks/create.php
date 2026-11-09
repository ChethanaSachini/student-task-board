<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Post Task | Student Task Board</title>
</head>
<body>
    <?php require __DIR__ . "/../partials/nav.php"; ?>

    <h1>Post Task</h1>
    <p>All fields are required except the reward.</p>

    <?php if (!empty($errors)): ?>
        <div role="alert">
            <p>Please check the following:</p>
            <ul>
                <?php foreach ($errors as $error): ?>
                    <li><?= htmlspecialchars($error, ENT_QUOTES, "UTF-8") ?></li>
                <?php endforeach; ?>
            </ul>
        </div>
    <?php endif; ?>

    <form method="post" action="create_task.php">
        <input type="hidden" name="csrf_token"
            value="<?= htmlspecialchars(
                $_SESSION["csrf_token"],
                ENT_QUOTES,
                "UTF-8"
            ) ?>">

        <p>
            <label for="title">Title</label><br>
            <input type="text" id="title" name="title" maxlength="150"
                value="<?= htmlspecialchars($values["title"], ENT_QUOTES, "UTF-8") ?>" required>
        </p>

        <p>
            <label for="description">Description</label><br>
            <textarea id="description" name="description" rows="6" cols="50"
                maxlength="5000" required><?= htmlspecialchars($values["description"], ENT_QUOTES, "UTF-8") ?></textarea>
        </p>

        <p>
            <label for="category">Category</label><br>
            <select id="category" name="category" required>
                <option value="">Select a category</option>
                <?php foreach ($categories as $category): ?>
                    <option value="<?= htmlspecialchars($category, ENT_QUOTES, "UTF-8") ?>"
                        <?= $values["category"] === $category ? "selected" : "" ?>>
                        <?= htmlspecialchars($category, ENT_QUOTES, "UTF-8") ?>
                    </option>
                <?php endforeach; ?>
            </select>
        </p>

        <p>
            <label for="deadline">Application deadline</label><br>
            <input type="date" id="deadline" name="deadline"
                value="<?= htmlspecialchars($values["deadline"], ENT_QUOTES, "UTF-8") ?>"
                min="<?= htmlspecialchars($minimumDeadline, ENT_QUOTES, "UTF-8") ?>" required>
            <br>Choose today or a later date. Applications will be accepted through the end of that date in Sri Lanka.
        </p>

        <p>
            <label for="reward">Reward (LKR, optional)</label><br>
            <input type="number" id="reward" name="reward"
                value="<?= htmlspecialchars($values["reward"], ENT_QUOTES, "UTF-8") ?>"
                min="0" max="99999999.99" step="0.01">
            <br>Leave blank for no reward, or enter an amount of zero or more with up to two decimal places.
        </p>

        <button type="submit">Post Task</button>
    </form>

    <p><a href="tasks.php">Back to Browse Tasks</a></p>
</body>
</html>
