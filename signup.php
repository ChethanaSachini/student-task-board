<?php
$message = "";

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $name = trim($_POST["name"] ?? "");
    $email = trim($_POST["email"] ?? "");
    $password = $_POST["password"] ?? "";

    if ($name === "" || mb_strlen($name) > 100) {
        $message = "Please enter a name of up to 100 characters.";
    } elseif (
        !filter_var($email, FILTER_VALIDATE_EMAIL)
        || strlen($email) > 254
    ) {
        $message = "Please enter a valid email address.";
    } elseif (strlen($password) < 8) {
        $message = "Your password must contain at least 8 characters.";
    } else {
        require __DIR__ . "/db.php";

        $password_hash = password_hash($password, PASSWORD_DEFAULT);

        $statement = $connection->prepare(
            "INSERT INTO users (name, email, password_hash)
             VALUES (?, ?, ?)"
        );

        $statement->bind_param("sss", $name, $email, $password_hash);

        try {
            $statement->execute();
            $message = "Account created successfully!";
        } catch (mysqli_sql_exception $error) {
            if ($error->getCode() === 1062) {
                $message = "This email address is already registered.";
            } else {
                error_log($error->getMessage());
                $message = "Unable to create your account. Please try again.";
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
    <?php require __DIR__ . "/nav.php"; ?>

    <h1>Create an Account</h1>

    <form method="post" action="signup.php">
        <?php if ($message !== ""): ?>
            <p role="status">
                <?= htmlspecialchars($message, ENT_QUOTES, "UTF-8") ?>
            </p>
        <?php endif; ?>

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