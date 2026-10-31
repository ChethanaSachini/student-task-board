<?php
session_start();

$message = "";

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    require __DIR__ . "/db.php";

    $email = trim($_POST["email"] ?? "");
    $password = $_POST["password"] ?? "";

    $statement = $connection->prepare(
        "SELECT id, name, password_hash
         FROM users
         WHERE email = ?"
    );

    $statement->bind_param("s", $email);
    $statement->execute();

    $user = $statement->get_result()->fetch_assoc();
    $statement->close();

    if ($user && password_verify($password, $user["password_hash"])) {
        session_regenerate_id(true);

        $_SESSION["user_id"] = $user["id"];
        $_SESSION["user_name"] = $user["name"];

        header("Location: index.php");
        exit;
    } else {
        $message = "Incorrect email or password.";
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Login | Student Task Board</title>
</head>
<body>
    <?php require __DIR__ . "/nav.php"; ?>

    <h1>Login</h1>

    <form method="post" action="login.php">
        <?php if ($message !== ""): ?>
            <p role="status">
                <?= htmlspecialchars($message, ENT_QUOTES, "UTF-8") ?>
            </p>
        <?php endif; ?>

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