<?php
$pageTitle = "Edit Application | Student Task Board";
$activePage = "my_applications";
require __DIR__ . "/../partials/header.php";
?>

<section class="form-card" aria-labelledby="edit-application-title">
    <h1 id="edit-application-title">Edit Application Message</h1>

    <?php if ($message !== ""): ?>
        <p class="notice" role="status"><?= htmlspecialchars($message, ENT_QUOTES, "UTF-8") ?></p>
    <?php else: ?>
        <h2><?= htmlspecialchars($application["title"], ENT_QUOTES, "UTF-8") ?></h2>
        <p>Task deadline: <?= htmlspecialchars($application["deadline"], ENT_QUOTES, "UTF-8") ?></p>
        <p>You can edit your message even after the task deadline. The task and applicant cannot be changed.</p>
        <?php if (!empty($errors)): ?>
            <div class="notice notice-error" role="alert">
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
            <p class="form-field">
                <label for="message">Message (required, up to 1,000 characters)</label>
                <textarea class="form-control" id="message" name="message" rows="6" cols="50"
                    maxlength="1000" required><?= htmlspecialchars($applicationMessage, ENT_QUOTES, "UTF-8") ?></textarea>
            </p>
            <div class="form-actions">
                <button class="button" type="submit">Save Changes</button>
            </div>
        </form>
    <?php endif; ?>

    <p><a href="my_applications.php">Cancel and return to My Applications</a></p>
</section>

<?php require __DIR__ . "/../partials/footer.php"; ?>
