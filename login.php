<?php
session_start();

$message = "";

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    require __DIR__ . "/db.php";
    require_once __DIR__ . "/app/Models/UserModel.php";

    $email = trim($_POST["email"] ?? "");
    $password = $_POST["password"] ?? "";

    $user = findUserByEmail($connection, $email);

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

require __DIR__ . "/app/Views/auth/login.php";
