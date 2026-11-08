<nav>
    <a href="index.php">Home</a> |
    <a href="tasks.php">Browse Tasks</a> |
    <?php if (isset($_SESSION["user_id"])): ?>
        <a href="my_tasks.php">My Tasks</a> |
    <?php endif; ?>
    <a href="signup.php">Sign Up</a> |
    <a href="login.php">Login</a>
</nav>
