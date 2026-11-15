<?php
$pageTitle = "Browse Tasks | Student Task Board";
$activePage = "tasks";
require __DIR__ . "/../partials/header.php";
?>

    <div class="page-intro">
        <h1>Browse Tasks</h1>
        <p>Find a task you can help with. Choose a category to explore available opportunities.</p>
    </div>

    <div class="form-field">
        <form class="form-card task-filter" method="get" action="tasks.php">
            <div class="form-field">
                <label for="category">Category</label>
                <select class="form-control" id="category" name="category">
                    <option value="">All categories</option>
                    <?php foreach ($categories as $taskCategory): ?>
                        <option value="<?= htmlspecialchars($taskCategory, ENT_QUOTES, "UTF-8") ?>"
                            <?= $category === $taskCategory ? "selected" : "" ?>>
                            <?= htmlspecialchars($taskCategory, ENT_QUOTES, "UTF-8") ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>
            <div class="form-actions">
                <button class="button" type="submit">Filter</button>
            </div>
        </form>
    </div>

    <?php if ($message !== ""): ?>
        <p class="notice" role="status"><?= htmlspecialchars($message, ENT_QUOTES, "UTF-8") ?></p>
    <?php elseif (empty($tasks)): ?>
        <section class="empty-state" aria-labelledby="browse-empty-title">
            <?php if ($category === ""): ?>
                <h2 id="browse-empty-title">No available tasks yet.</h2>
                <p>Check back for new posts, or share a task you need help with.</p>
                <div class="form-actions">
                    <?php if (isset($_SESSION["user_id"])): ?>
                        <a class="button" href="create_task.php">Post Task</a>
                    <?php else: ?>
                        <a class="button" href="signup.php">Sign Up to Post</a>
                    <?php endif; ?>
                </div>
            <?php else: ?>
                <h2 id="browse-empty-title">No available tasks in this category.</h2>
                <p>Try another category or browse all available tasks.</p>
                <div class="form-actions">
                    <a class="button button-secondary" href="tasks.php">View all categories</a>
                </div>
            <?php endif; ?>
        </section>
    <?php else: ?>
        <div class="table-scroll" role="region" aria-label="Available tasks" tabindex="0">
        <table class="data-table">
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
        </div>
    <?php endif; ?>
<?php require __DIR__ . "/../partials/footer.php"; ?>
