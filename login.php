<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Login | Student Task Board</title>
</head>
<body>
    <nav>
        <a href="index.php">Home</a> |
        <a href="tasks.php">Browse Tasks</a> |
        <a href="signup.php">Sign Up</a> |
        <a href="login.php">Login</a>
    </nav>

    <h1>Login</h1>

    <form method="post" action="login.php">
        <p>
            <label for="email">Email address</label><br>
            <input type="email" id="email" name="email"
                   autocomplete="username" required>
        </p>

        <p>
            <label for="password">Password</label><br>
            <input type="password" id="password" name="password"
                   autocomplete="current-password" required>
        </p>

        <button type="submit">Login</button>
    </form>

    <p>Don't have an account? <a href="signup.php">Sign up</a>.</p>
</body>
</html>