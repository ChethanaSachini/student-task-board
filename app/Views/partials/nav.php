<nav aria-label="Main navigation">
    <a href="index.php"<?= ($activePage ?? "") === "home" ? ' aria-current="page"' : "" ?>>Home</a> <span class="nav-separator">|</span>
    <a href="tasks.php">Browse Tasks</a> <span class="nav-separator">|</span>
    <?php if (isset($_SESSION["user_id"])): ?>
        <a href="create_task.php">Post Task</a> <span class="nav-separator">|</span>
        <a href="my_tasks.php">My Tasks</a> <span class="nav-separator">|</span>
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
        <a href="signup.php">Sign Up</a> <span class="nav-separator">|</span>
        <a href="login.php">Login</a>
    <?php endif; ?>
</nav>
