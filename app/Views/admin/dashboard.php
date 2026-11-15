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
<p class="notice">Administrator access is active. Management screens and actions are planned for UI8 and are not available yet.</p>

<nav class="form-actions" aria-label="Planned management sections">
    <a class="button button-secondary" href="#user-management">User Management (planned)</a>
    <a class="button button-secondary" href="#task-management">Task Management (planned)</a>
</nav>

<div class="form-field">
    <section class="card" id="user-management" aria-labelledby="user-management-title" style="scroll-margin-top: 12rem;">
        <h2 id="user-management-title">User Management</h2>
        <p>Planned for UI8: review registered users and the proposed account-management interface.</p>
        <p id="user-management-help">User management is not implemented. This dashboard does not change accounts or roles.</p>
        <button class="button" type="button" disabled aria-describedby="user-management-help">Manage Users — unavailable</button>
    </section>
</div>

<div class="form-field">
    <section class="card" id="task-management" aria-labelledby="task-management-title" style="scroll-margin-top: 12rem;">
        <h2 id="task-management-title">Task Management</h2>
        <p>Planned for UI8: review posted tasks and the proposed moderation interface.</p>
        <p id="task-management-help">Task management is not implemented. Existing task ownership and CRUD permissions remain in effect.</p>
        <button class="button" type="button" disabled aria-describedby="task-management-help">Manage Tasks — unavailable</button>
    </section>
</div>

<p><a href="index.php">Return to the homepage</a></p>

<?php require __DIR__ . "/../partials/footer.php"; ?>
