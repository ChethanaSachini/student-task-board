<?php

require __DIR__ . "/../app/bootstrap.php";

$routes = require __DIR__ . "/../app/routes.php";
$requestMethod = $_SERVER["REQUEST_METHOD"];

// Keep relative navigation links working when this file is opened directly.
if (!isset($routePath)) {
    if ($requestMethod === "GET") {
        header("Location: ../index.php");
        exit;
    }

    $routePath = "/";
}

if (!isset($routes[$routePath])) {
    http_response_code(404);
    exit("Page not found.");
}

if (!isset($routes[$routePath][$requestMethod])) {
    http_response_code(405);
    header("Allow: " . implode(", ", array_keys($routes[$routePath])));
    exit("Method not allowed.");
}

$handler = $routes[$routePath][$requestMethod];
$handler();
