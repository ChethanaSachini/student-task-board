<?php
// This view is rendered only after the controller's current-role check.
if (!isset($administrator) || $administrator["role"] !== "admin") {
    http_response_code(403);
    exit("Administrator access required.");
}

$pageTitle = "Admin Dashboard | Student Task Board";
$activePage = "admin";
require __DIR__ . "/../partials/header.php";
?>

<h1>Admin Dashboard</h1>
<p>Welcome, <?= htmlspecialchars($administrator["name"], ENT_QUOTES, "UTF-8") ?>.</p>
<p class="notice">Review accounts and task posts using the read-only management screens. Account changes and task moderation are not implemented.</p>

<div class="form-field">
    <nav class="form-actions" aria-label="Admin management navigation">
        <a class="button button-secondary" href="admin_users.php">Manage Users</a>
        <a class="button button-secondary" href="admin_tasks.php">Manage Tasks</a>
    </nav>
</div>

<div class="form-field">
    <section class="card" id="user-management" aria-labelledby="user-management-title" style="scroll-margin-top: 12rem;">
        <h2 id="user-management-title">User Management</h2>
        <p>View registered accounts, their roles and registration dates.</p>
        <p>Role changes and account deletion remain unavailable. Opening the list does not change accounts.</p>
    </section>
</div>

<div class="form-field">
    <section class="card" id="task-management" aria-labelledby="task-management-title" style="scroll-margin-top: 12rem;">
        <h2 id="task-management-title">Task Management</h2>
        <p>View all task posts, including expired tasks, and expand their details.</p>
        <p>Moderation and task removal remain unavailable. Existing task ownership and CRUD permissions remain in effect.</p>
    </section>
</div>

<p><a href="index.php">Return to the homepage</a></p>

<?php require __DIR__ . "/../partials/footer.php"; ?>
