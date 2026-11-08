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
        require_once __DIR__ . "/app/Models/UserModel.php";

        $password_hash = password_hash($password, PASSWORD_DEFAULT);

        try {
            createUser($connection, $name, $email, $password_hash);
            $message = "Account created successfully!";
        } catch (mysqli_sql_exception $error) {
            if ($error->getCode() === 1062) {
                $message = "This email address is already registered.";
            } else {
                error_log($error->getMessage());
                $message = "Unable to create your account. Please try again.";
            }
        }
    }
}

require __DIR__ . "/app/Views/auth/signup.php";
