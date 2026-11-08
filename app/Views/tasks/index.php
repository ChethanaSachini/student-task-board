<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Browse Tasks | Student Task Board</title>
</head>
<body>
    <?php require __DIR__ . "/../partials/nav.php"; ?>

    <h1>Browse Tasks</h1>
    <p>Find a task you can help with.</p>

    <form method="get" action="tasks.php">
        <label for="category">Category</label>
        <select id="category" name="category">
            <option value="">All categories</option>
            <?php foreach ($categories as $taskCategory): ?>
                <option value="<?= htmlspecialchars($taskCategory, ENT_QUOTES, "UTF-8") ?>"
                    <?= $category === $taskCategory ? "selected" : "" ?>>
                    <?= htmlspecialchars($taskCategory, ENT_QUOTES, "UTF-8") ?>
                </option>
            <?php endforeach; ?>
        </select>
        <button type="submit">Filter</button>
    </form>

    <?php if ($message !== ""): ?>
        <p role="status"><?= htmlspecialchars($message, ENT_QUOTES, "UTF-8") ?></p>
    <?php elseif (empty($tasks)): ?>
        <?php if ($category === ""): ?>
            <p>No available tasks yet.</p>
        <?php else: ?>
            <p>No available tasks in this category.</p>
        <?php endif; ?>
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
                        <td><?= htmlspecialchars($task["deadline"], ENT_QUOTES, "UTF-8") ?></td>
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
</body>
</html>
