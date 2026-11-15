<?php
if (!isset($administrator) || $administrator["role"] !== "admin") {
    http_response_code(403);
    exit("Administrator access required.");
}

$pageTitle = "Manage Tasks | Student Task Board";
$activePage = "admin";
require __DIR__ . "/../partials/header.php";
?>

<h1>Manage Tasks</h1>
<p>Read-only list of all task posts, including expired tasks. Expand a row's details to read the full task.</p>
<div class="form-field">
    <nav class="form-actions" aria-label="Admin management navigation">
        <a class="button button-secondary" href="admin_dashboard.php">Admin Dashboard</a>
        <a class="button button-secondary" href="admin_users.php">Manage Users</a>
        <a class="button button-secondary" href="admin_tasks.php" aria-current="page">Manage Tasks</a>
    </nav>
</div>

<?php if ($message !== ""): ?>
    <p class="notice notice-error" role="alert"><?= htmlspecialchars($message, ENT_QUOTES, "UTF-8") ?></p>
<?php elseif (empty($adminTasks)): ?>
    <p class="notice">No task posts found.</p>
<?php else: ?>
    <div class="form-field">
        <div class="table-scroll" role="region" aria-label="All task posts" tabindex="0">
            <table class="data-table">
                <thead>
                    <tr>
                        <th scope="col">Title and details</th>
                        <th scope="col">Posted by</th>
                        <th scope="col">Category</th>
                        <th scope="col">Deadline</th>
                        <th scope="col">Deadline state</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($adminTasks as $task): ?>
                        <tr>
                            <td>
                                <strong><?= htmlspecialchars($task["title"], ENT_QUOTES, "UTF-8") ?></strong>
                                <details>
                                    <summary>View task details</summary>
                                    <p><?= nl2br(htmlspecialchars($task["description"], ENT_QUOTES, "UTF-8")) ?></p>
                                    <dl class="task-facts">
                                        <dt>Reward</dt>
                                        <dd>
                                            <?php if ($task["reward"] === null): ?>
                                                No reward specified
                                            <?php else: ?>
                                                LKR <?= number_format((float) $task["reward"], 2) ?>
                                            <?php endif; ?>
                                        </dd>
                                        <dt>Created</dt>
                                        <dd><?= htmlspecialchars($task["created_at"], ENT_QUOTES, "UTF-8") ?></dd>
                                    </dl>
                                </details>
                            </td>
                            <td><?= htmlspecialchars($task["poster_name"], ENT_QUOTES, "UTF-8") ?></td>
                            <td><?= htmlspecialchars($task["category"], ENT_QUOTES, "UTF-8") ?></td>
                            <td><?= htmlspecialchars($task["deadline"], ENT_QUOTES, "UTF-8") ?></td>
                            <td><?= $task["is_expired"] ? "Expired" : "Within deadline" ?></td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>
<?php endif; ?>

<section class="card" aria-labelledby="moderation-preview-title">
    <h2 id="moderation-preview-title">Task moderation preview</h2>
    <p id="moderation-preview-help">Preview only. Moderation and task removal are not implemented. No task is selected and these controls cannot change records.</p>
    <div class="form-actions">
        <button class="button" type="button" disabled aria-describedby="moderation-preview-help">Moderate Task — unavailable</button>
        <button class="button button-danger" type="button" disabled aria-describedby="moderation-preview-help">Remove Task — unavailable</button>
    </div>
</section>

<?php require __DIR__ . "/../partials/footer.php"; ?>
