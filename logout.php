<?php
session_start();

if ($_SERVER["REQUEST_METHOD"] !== "POST") {
    http_response_code(405);
    exit("Please use the Logout button.");
}

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