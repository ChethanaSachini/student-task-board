<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Edit Application | Student Task Board</title>
</head>
<body>
    <?php require __DIR__ . "/../partials/nav.php"; ?>

    <h1>Edit Application Message</h1>

    <?php if ($message !== ""): ?>
        <p role="status"><?= htmlspecialchars($message, ENT_QUOTES, "UTF-8") ?></p>
    <?php else: ?>
        <h2><?= htmlspecialchars($application["title"], ENT_QUOTES, "UTF-8") ?></h2>
        <p>Task deadline: <?= htmlspecialchars($application["deadline"], ENT_QUOTES, "UTF-8") ?></p>
        <p>You can edit your message even after the task deadline. The task and applicant cannot be changed.</p>
        <?php if (!empty($errors)): ?>
            <div role="alert">
                <ul>
                    <?php foreach ($errors as $error): ?>
                        <li><?= htmlspecialchars($error, ENT_QUOTES, "UTF-8") ?></li>
                    <?php endforeach; ?>
                </ul>
            </div>
        <?php endif; ?>

        <form method="post" action="edit_application.php?id=<?= (int) $application["id"] ?>">
            <input type="hidden" name="csrf_token"
                value="<?= htmlspecialchars($_SESSION["csrf_token"], ENT_QUOTES, "UTF-8") ?>">
            <p>
                <label for="message">Message (required, up to 1,000 characters)</label><br>
                <textarea id="message" name="message" rows="6" cols="50"
                    maxlength="1000" required><?= htmlspecialchars($applicationMessage, ENT_QUOTES, "UTF-8") ?></textarea>
            </p>
            <button type="submit">Save Changes</button>
        </form>
    <?php endif; ?>

    <p><a href="my_applications.php">Cancel and return to My Applications</a></p>
</body>
</html>
