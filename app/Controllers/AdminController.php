<?php

// Every admin handler must call this guard before loading any protected content.
function requireAdministrator()
{
    if (empty($_SESSION["user_id"])) {
        header("Location: login.php");
        return null;
    }

    $userId = filter_var($_SESSION["user_id"], FILTER_VALIDATE_INT, [
        "options" => ["min_range" => 1],
    ]);

    if ($userId === false) {
        unset($_SESSION["user_role"]);
        http_response_code(403);
        echo "Administrator access required.";
        return null;
    }

    try {
        require __DIR__ . "/../../db.php";
        require_once __DIR__ . "/../Models/UserModel.php";

        // A session role or a hidden navigation link is not authorization.
        $user = findUserById($connection, $userId);
    } catch (mysqli_sql_exception $error) {
        unset($_SESSION["user_role"]);
        error_log($error->getMessage());
        http_response_code(503);
        echo "Unable to verify administrator access. Please try again later.";
        return null;
    }

    if (!$user || $user["role"] !== "admin") {
        unset($_SESSION["user_role"]);
        http_response_code(403);
        echo "Administrator access required.";
        return null;
    }

    $_SESSION["user_role"] = $user["role"];
    return $user;
}

function showAdminDashboard()
{
    $administrator = requireAdministrator();
    if ($administrator === null) {
        return;
    }

    require __DIR__ . "/../Views/admin/dashboard.php";
}

function showAdminUsers()
{
    $administrator = requireAdministrator();
    if ($administrator === null) {
        return;
    }

    $adminUsers = [];
    $message = "";

    try {
        require __DIR__ . "/../../db.php";
        require_once __DIR__ . "/../Models/UserModel.php";

        $adminUsers = getAdminUsers($connection);
    } catch (mysqli_sql_exception $error) {
        error_log($error->getMessage());
        http_response_code(503);
        $message = "Unable to load registered accounts. Please try again later.";
    }

    require __DIR__ . "/../Views/admin/users.php";
}

function showAdminTasks()
{
    $administrator = requireAdministrator();
    if ($administrator === null) {
        return;
    }

    $adminTasks = [];
    $message = "";

    try {
        require __DIR__ . "/../../db.php";
        require_once __DIR__ . "/../Models/TaskModel.php";

        $adminTasks = getAdminTasks($connection);
        $today = new DateTimeImmutable("today", new DateTimeZone("Asia/Colombo"));
        foreach ($adminTasks as $index => $task) {
            // Like the student pages, a deadline remains valid for all of today.
            $adminTasks[$index]["is_expired"] = $task["deadline"] < $today->format("Y-m-d");
        }
    } catch (mysqli_sql_exception $error) {
        error_log($error->getMessage());
        http_response_code(503);
        $message = "Unable to load task posts. Please try again later.";
    }

    require __DIR__ . "/../Views/admin/tasks.php";
}
