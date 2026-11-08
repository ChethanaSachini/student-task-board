<?php

session_start();
if (empty($_SESSION["csrf_token"])) {
    $_SESSION["csrf_token"] = bin2hex(random_bytes(32));
}

require_once __DIR__ . "/Controllers/HomeController.php";
require_once __DIR__ . "/Controllers/AuthController.php";
require_once __DIR__ . "/Controllers/TaskController.php";
