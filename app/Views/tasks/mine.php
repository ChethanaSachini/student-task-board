<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>My Tasks | Student Task Board</title>
</head>
<body>
    <?php require __DIR__ . "/../partials/nav.php"; ?>

    <h1>My Tasks</h1>

    <?php if ($message !== ""): ?>
        <p role="status"><?= htmlspecialchars($message, ENT_QUOTES, "UTF-8") ?></p>
    <?php elseif (empty($tasks)): ?>
        <p>You have not posted any tasks yet.</p>
    <?php else: ?>
        <table>
            <thead>
                <tr>
                    <th scope="col">Title</th>
                    <th scope="col">Category</th>
                    <th scope="col">Deadline</th>
                    <th scope="col">Reward</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($tasks as $task): ?>
                    <tr>
                        <td>
                            <a href="task.php?id=<?= (int) $task["id"] ?>">
                                <?= htmlspecialchars($task["title"], ENT_QUOTES, "UTF-8") ?>
                            </a>
                        </td>
                        <td><?= htmlspecialchars($task["category"], ENT_QUOTES, "UTF-8") ?></td>
                        <td>
                            <?= htmlspecialchars($task["deadline"], ENT_QUOTES, "UTF-8") ?>
                            <?php if ($task["deadline"] < $today): ?>
                                <strong>— Expired</strong>
                            <?php endif; ?>
                        </td>
                        <td>
                            <?php if ($task["reward"] === null): ?>
                                No reward specified
                            <?php else: ?>
                                LKR <?= number_format((float) $task["reward"], 2) ?>
                            <?php endif; ?>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    <?php endif; ?>

    <p><a href="tasks.php">Browse Tasks</a></p>
</body>
</html>
