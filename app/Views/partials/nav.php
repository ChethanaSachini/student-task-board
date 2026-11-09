<nav>
    <a href="index.php">Home</a> |
    <a href="tasks.php">Browse Tasks</a> |
    <?php if (isset($_SESSION["user_id"])): ?>
        <a href="create_task.php">Post Task</a> |
        <a href="my_tasks.php">My Tasks</a> |
        <a href="my_applications.php">My Applications</a>
        <form method="post" action="logout.php">
            <input type="hidden" name="csrf_token"
                value="<?= htmlspecialchars(
                    $_SESSION["csrf_token"],
                    ENT_QUOTES,
                    "UTF-8"
                ) ?>">
            <button type="submit">Logout</button>
        </form>
    <?php else: ?>
        <a href="signup.php">Sign Up</a> |
        <a href="login.php">Login</a>
    <?php endif; ?>
</nav>
