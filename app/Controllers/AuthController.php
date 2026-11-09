<?php

function showSignup()
{
    if (isset($_SESSION["user_id"])) {
        header("Location: index.php");
        exit;
    }

    $message = "";
    require __DIR__ . "/../Views/auth/signup.php";
}

function showLogin()
{
    if (isset($_SESSION["user_id"])) {
        header("Location: index.php");
        exit;
    }

    $message = "";
    require __DIR__ . "/../Views/auth/login.php";
}

function processLogin()
{
    if (isset($_SESSION["user_id"])) {
        header("Location: index.php", true, 303);
        exit;
    }

    $token = $_POST["csrf_token"] ?? "";

    if (
        !is_string($token)
        || empty($_SESSION["csrf_token"])
        || !hash_equals($_SESSION["csrf_token"], $token)
    ) {
        http_response_code(403);
        exit("Invalid request. Return to the login page and try again.");
    }

    $message = "";
    $email = $_POST["email"] ?? "";
    $password = $_POST["password"] ?? "";

    if (!is_string($email) || !is_string($password)) {
        http_response_code(400);
        exit("Invalid form data.");
    }

    $email = trim($email);

    if (
        !filter_var($email, FILTER_VALIDATE_EMAIL)
        || strlen($email) > 254
        || $password === ""
    ) {
        $message = "Incorrect email or password.";
    } else {
        require __DIR__ . "/../../db.php";
        require_once __DIR__ . "/../Models/UserModel.php";

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

    require __DIR__ . "/../Views/auth/login.php";
}

function processLogout()
{
    $token = $_POST["csrf_token"] ?? "";

    if (
        !is_string($token)
        || empty($_SESSION["csrf_token"])
        || !hash_equals($_SESSION["csrf_token"], $token)
    ) {
        http_response_code(403);
        exit("Invalid request. Return to the home page and try again.");
    }

    $_SESSION = [];
    session_destroy();

    header("Location: index.php");
    exit;
}

function processSignup()
{
    if (isset($_SESSION["user_id"])) {
        header("Location: index.php", true, 303);
        exit;
    }

    $token = $_POST["csrf_token"] ?? "";

    if (
        !is_string($token)
        || empty($_SESSION["csrf_token"])
        || !hash_equals($_SESSION["csrf_token"], $token)
    ) {
        http_response_code(403);
        exit("Invalid request. Return to the sign-up page and try again.");
    }

    $message = "";
    $name = $_POST["name"] ?? "";
    $email = $_POST["email"] ?? "";
    $password = $_POST["password"] ?? "";

    if (!is_string($name) || !is_string($email) || !is_string($password)) {
        http_response_code(400);
        exit("Invalid form data.");
    }

    $name = trim($name);
    $email = trim($email);

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
        require __DIR__ . "/../../db.php";
        require_once __DIR__ . "/../Models/UserModel.php";

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

    require __DIR__ . "/../Views/auth/signup.php";
}
