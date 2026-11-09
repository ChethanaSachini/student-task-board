<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Task Applicants | Student Task Board</title>
</head>
<body>
    <?php require __DIR__ . "/../partials/nav.php"; ?>

    <h1>Task Applicants</h1>

    <?php if ($message !== ""): ?>
        <p role="status"><?= htmlspecialchars($message, ENT_QUOTES, "UTF-8") ?></p>
    <?php else: ?>
        <h2><?= htmlspecialchars($task["title"], ENT_QUOTES, "UTF-8") ?></h2>

        <?php if (empty($applications)): ?>
            <p>No students have applied for this task yet.</p>
        <?php else: ?>
            <?php foreach ($applications as $application): ?>
                <article>
                    <h3><?= htmlspecialchars($application["applicant_name"], ENT_QUOTES, "UTF-8") ?></h3>
                    <p><?= nl2br(htmlspecialchars($application["message"], ENT_QUOTES, "UTF-8")) ?></p>
                    <p>Submitted: <?= htmlspecialchars($application["created_at"], ENT_QUOTES, "UTF-8") ?></p>
                    <p>Updated: <?= htmlspecialchars($application["updated_at"], ENT_QUOTES, "UTF-8") ?></p>
                </article>
            <?php endforeach; ?>
        <?php endif; ?>

        <p><a href="task.php?id=<?= (int) $task["id"] ?>">Back to task details</a></p>
    <?php endif; ?>

    <p><a href="my_tasks.php">My Tasks</a></p>
</body>
</html>
