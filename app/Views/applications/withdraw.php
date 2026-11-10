<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Withdraw Application | Student Task Board</title>
</head>
<body>
    <?php require __DIR__ . "/../partials/nav.php"; ?>

    <h1>Withdraw Application</h1>

    <?php if ($message !== ""): ?>
        <p role="status"><?= htmlspecialchars($message, ENT_QUOTES, "UTF-8") ?></p>
    <?php else: ?>
        <h2><?= htmlspecialchars($application["title"], ENT_QUOTES, "UTF-8") ?></h2>
        <dl>
            <dt>Task deadline</dt>
            <dd><?= htmlspecialchars($application["deadline"], ENT_QUOTES, "UTF-8") ?></dd>
            <dt>Your application message</dt>
            <dd><?= nl2br(htmlspecialchars($application["message"], ENT_QUOTES, "UTF-8")) ?></dd>
            <dt>Submitted</dt>
            <dd><?= htmlspecialchars($application["created_at"], ENT_QUOTES, "UTF-8") ?></dd>
            <dt>Updated</dt>
            <dd><?= htmlspecialchars($application["updated_at"], ENT_QUOTES, "UTF-8") ?></dd>
        </dl>

        <p><strong>Withdrawing permanently deletes this application and its message. This cannot be undone.</strong></p>
        <p>The task itself will not be deleted.</p>
        <p>Opening this page does not delete anything. Withdrawal happens only after you confirm and submit the form.</p>

        <?php if (!empty($errors)): ?>
            <div role="alert">
                <ul>
                    <?php foreach ($errors as $error): ?>
                        <li><?= htmlspecialchars($error, ENT_QUOTES, "UTF-8") ?></li>
                    <?php endforeach; ?>
                </ul>
            </div>
        <?php endif; ?>

        <form method="post" action="withdraw_application.php?id=<?= (int) $application["id"] ?>">
            <input type="hidden" name="csrf_token"
                value="<?= htmlspecialchars($_SESSION["csrf_token"], ENT_QUOTES, "UTF-8") ?>">
            <p>
                <label>
                    <input type="checkbox" name="confirm_withdrawal" value="1" required>
                    I understand that withdrawing permanently deletes my application and its message.
                </label>
            </p>
            <button type="submit">Withdraw Application</button>
        </form>
    <?php endif; ?>

    <p><a href="my_applications.php">Cancel and return to My Applications</a></p>
</body>
</html>
