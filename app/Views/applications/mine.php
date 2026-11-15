<?php
$pageTitle = "My Applications | Student Task Board";
$activePage = "my_applications";
require __DIR__ . "/../partials/header.php";
?>

    <h1>My Applications</h1>

    <?php if ($successMessage !== ""): ?>
        <p class="notice notice-success" role="status"><?= htmlspecialchars($successMessage, ENT_QUOTES, "UTF-8") ?></p>
    <?php endif; ?>

    <?php if ($message !== ""): ?>
        <p class="notice" role="status"><?= htmlspecialchars($message, ENT_QUOTES, "UTF-8") ?></p>
    <?php elseif (empty($applications)): ?>
        <p class="notice">You have not applied for any tasks yet.</p>
    <?php else: ?>
        <div class="table-scroll" role="region" aria-label="My applications" tabindex="0">
        <table class="data-table">
            <thead>
                <tr>
                    <th scope="col">Task</th>
                    <th scope="col">Category</th>
                    <th scope="col">Deadline</th>
                    <th scope="col">Reward</th>
                    <th scope="col">Your message</th>
                    <th scope="col">Submitted</th>
                    <th scope="col">Updated</th>
                    <th scope="col">Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($applications as $application): ?>
                    <tr>
                        <td>
                            <a href="task.php?id=<?= (int) $application["task_id"] ?>">
                                <?= htmlspecialchars($application["title"], ENT_QUOTES, "UTF-8") ?>
                            </a>
                        </td>
                        <td><?= htmlspecialchars($application["category"], ENT_QUOTES, "UTF-8") ?></td>
                        <td>
                            <?= htmlspecialchars($application["deadline"], ENT_QUOTES, "UTF-8") ?>
                            <?php if ($application["deadline"] < $today): ?>
                                <strong>— Expired</strong>
                            <?php endif; ?>
                        </td>
                        <td>
                            <?php if ($application["reward"] === null): ?>
                                No reward specified
                            <?php else: ?>
                                LKR <?= number_format((float) $application["reward"], 2) ?>
                            <?php endif; ?>
                        </td>
                        <td><?= nl2br(htmlspecialchars($application["message"], ENT_QUOTES, "UTF-8")) ?></td>
                        <td><?= htmlspecialchars($application["created_at"], ENT_QUOTES, "UTF-8") ?></td>
                        <td><?= htmlspecialchars($application["updated_at"], ENT_QUOTES, "UTF-8") ?></td>
                        <td>
                            <a href="edit_application.php?id=<?= (int) $application["id"] ?>">Edit Message</a> |
                            <a href="withdraw_application.php?id=<?= (int) $application["id"] ?>">Withdraw</a>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
        </div>
    <?php endif; ?>

    <p><a href="tasks.php">Browse Tasks</a></p>
<?php require __DIR__ . "/../partials/footer.php"; ?>
