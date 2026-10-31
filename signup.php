<?php
if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $name = trim($_POST["name"] ?? "");
    $email = trim($_POST["email"] ?? "");
    $password = $_POST["password"] ?? "";

    if ($name === "" || mb_strlen($name) > 100) {
        echo "Please enter a name of up to 100 characters.";
    } elseif (
        !filter_var($email, FILTER_VALIDATE_EMAIL)
        || strlen($email) > 254
    ) {
        echo "Please enter a valid email address.";
    } elseif (strlen($password) < 8) {
        echo "Your password must contain at least 8 characters.";
    } else {
        require "db.php";

        $password_hash = password_hash($password, PASSWORD_DEFAULT);

        $statement = $connection->prepare(
            "INSERT INTO users (name, email, password_hash)
            VALUES (?, ?, ?)"
        );

        $statement->bind_param("sss", $name, $email, $password_hash);

        try {
            $statement->execute();
            echo "Account created successfully!";
        } catch (mysqli_sql_exception $error) {
            if ($error->getCode() === 1062) {
                echo "This email address is already registered.";
            } else {
                error_log($error->getMessage());
                echo "Unable to create your account. Please try again.";
            }
        }

        $statement->close();
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Sign Up | Student Task Board</title>
</head>
<body>
    <nav>
        <a href="index.php">Home</a> |
        <a href="tasks.php">Browse Tasks</a> |
        <a href="signup.php">Sign Up</a> |
        <a href="login.php">Login</a>
    </nav>

    <h1>Create an Account</h1>

    <form method="post" action="signup.php">
        <p>
            <label for="name">Full name</label><br>
            <input type="text" id="name" name="name"
                   maxlength="100" required>
        </p>

        <p>
            <label for="email">Email address</label><br>
            <input type="email" id="email" name="email"
                   maxlength="254" required>
        </p>

        <p>
            <label for="password">Password (at least 8 characters)</label><br>
            <input type="password" id="password" name="password"
                   minlength="8" required>
        </p>

        <button type="submit">Sign Up</button>
    </form>
</body>
</html>