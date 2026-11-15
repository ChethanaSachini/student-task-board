<?php
$pageTitle = "My Tasks | Student Task Board";
$activePage = "my_tasks";
require __DIR__ . "/../partials/header.php";
?>

    <div class="page-intro">
        <h1>My Tasks</h1>
        <p>Review your posts, open task details, or edit and delete your tasks.</p>
        <div class="form-actions">
            <a class="button button-secondary" href="create_task.php">Post Task</a>
        </div>
    </div>

    <?php if ($successMessage !== ""): ?>
        <p class="notice notice-success" role="status"><?= htmlspecialchars($successMessage, ENT_QUOTES, "UTF-8") ?></p>
    <?php endif; ?>

    <?php if ($message !== ""): ?>
        <p class="notice" role="status"><?= htmlspecialchars($message, ENT_QUOTES, "UTF-8") ?></p>
    <?php elseif (empty($tasks)): ?>
        <section class="empty-state" aria-labelledby="tasks-empty-title">
            <h2 id="tasks-empty-title">You have not posted any tasks yet.</h2>
            <p>Use Post Task to describe something you need help with. Your posts will appear here.</p>
            <p>You can also <a href="tasks.php">browse tasks posted by other students</a>.</p>
        </section>
    <?php else: ?>
        <div class="table-scroll" role="region" aria-label="My tasks" tabindex="0">
        <table class="data-table">
            <thead>
                <tr>
                    <th scope="col">Title</th>
                    <th scope="col">Category</th>
                    <th scope="col">Deadline</th>
                    <th scope="col">Reward</th>
                    <th scope="col">Actions</th>
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
                        <td>
                            <a href="edit_task.php?id=<?= (int) $task["id"] ?>">Edit</a> |
                            <a href="delete_task.php?id=<?= (int) $task["id"] ?>">Delete</a>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
        </div>
    <?php endif; ?>

    <p class="page-links"><a href="tasks.php">Browse Tasks</a></p>
<?php require __DIR__ . "/../partials/footer.php"; ?>
