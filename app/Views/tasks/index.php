<?php
$pageTitle = "Browse Tasks | Student Task Board";
$activePage = "tasks";
require __DIR__ . "/../partials/header.php";
?>

    <h1>Browse Tasks</h1>
    <p>Find a task you can help with.</p>

    <div class="form-field">
        <form class="form-card" method="get" action="tasks.php">
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
        <?php if ($category === ""): ?>
            <p class="notice">No available tasks yet.</p>
        <?php else: ?>
            <p class="notice">No available tasks in this category.</p>
        <?php endif; ?>
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
