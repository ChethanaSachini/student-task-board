<?php
if (!isset($administrator) || $administrator["role"] !== "admin") {
    http_response_code(403);
    exit("Administrator access required.");
}

$pageTitle = "Manage Users | Student Task Board";
$activePage = "admin";
require __DIR__ . "/../partials/header.php";
?>

<h1>Manage Users</h1>
<p>Read-only list of registered accounts.</p>
<div class="form-field">
    <nav class="form-actions" aria-label="Admin management navigation">
        <a class="button button-secondary" href="admin_dashboard.php">Admin Dashboard</a>
        <a class="button button-secondary" href="admin_users.php" aria-current="page">Manage Users</a>
        <a class="button button-secondary" href="admin_tasks.php">Manage Tasks</a>
    </nav>
</div>

<?php if ($message !== ""): ?>
    <p class="notice notice-error" role="alert"><?= htmlspecialchars($message, ENT_QUOTES, "UTF-8") ?></p>
<?php elseif (empty($adminUsers)): ?>
    <p class="notice">No registered accounts found.</p>
<?php else: ?>
    <div class="form-field">
        <div class="table-scroll" role="region" aria-label="Registered accounts" tabindex="0">
            <table class="data-table">
                <thead>
                    <tr>
                        <th scope="col">Name</th>
                        <th scope="col">Email</th>
                        <th scope="col">Role</th>
                        <th scope="col">Registered</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($adminUsers as $account): ?>
                        <tr>
                            <td><?= htmlspecialchars($account["name"], ENT_QUOTES, "UTF-8") ?></td>
                            <td><?= htmlspecialchars($account["email"], ENT_QUOTES, "UTF-8") ?></td>
                            <td><?= htmlspecialchars($account["role"], ENT_QUOTES, "UTF-8") ?></td>
                            <td><?= htmlspecialchars($account["created_at"], ENT_QUOTES, "UTF-8") ?></td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>
<?php endif; ?>

<section class="card" aria-labelledby="account-preview-title">
    <h2 id="account-preview-title">Account management preview</h2>
    <p id="account-preview-help">Preview only. Role changes and account deletion are not implemented. No account is selected and these controls cannot change records.</p>
    <div class="form-actions">
        <button class="button" type="button" disabled aria-describedby="account-preview-help">Change Role — unavailable</button>
        <button class="button button-danger" type="button" disabled aria-describedby="account-preview-help">Delete Account — unavailable</button>
    </div>
</section>

<?php require __DIR__ . "/../partials/footer.php"; ?>
