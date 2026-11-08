<?php

function getTaskCategories()
{
    return [
        "Design",
        "Event Support",
        "Photography",
        "Data Entry",
        "Tutoring",
        "Other",
    ];
}

function showTask()
{
    $taskId = $_GET["id"] ?? "";
    $task = null;
    $message = "";
    $isExpired = false;

    if (!is_string($taskId)) {
        $taskId = false;
    } else {
        $taskId = filter_var($taskId, FILTER_VALIDATE_INT, [
            "options" => ["min_range" => 1, "max_range" => 2147483647],
        ]);
    }

    if ($taskId === false) {
        http_response_code(400);
        $message = "Please provide a valid task ID.";
    } else {
        try {
            require __DIR__ . "/../../db.php";
            require_once __DIR__ . "/../Models/TaskModel.php";

            $task = findTaskById($connection, $taskId);

            if (!$task) {
                http_response_code(404);
                $message = "Task not found.";
            } else {
                $today = new DateTimeImmutable("today", new DateTimeZone("Asia/Colombo"));
                $isExpired = $task["deadline"] < $today->format("Y-m-d");
            }
        } catch (mysqli_sql_exception $error) {
            error_log($error->getMessage());
            http_response_code(503);
            $message = "Unable to load this task. Please try again later.";
        }
    }

    require __DIR__ . "/../Views/tasks/show.php";
}

function showMyTasks()
{
    if (empty($_SESSION["user_id"])) {
        header("Location: login.php");
        exit;
    }

    $tasks = [];
    $message = "";
    $today = new DateTimeImmutable("today", new DateTimeZone("Asia/Colombo"));
    $today = $today->format("Y-m-d");

    try {
        require __DIR__ . "/../../db.php";
        require_once __DIR__ . "/../Models/TaskModel.php";

        // Always use the logged-in student's ID, not a URL parameter.
        $tasks = getTasksByUser($connection, $_SESSION["user_id"]);
    } catch (mysqli_sql_exception $error) {
        error_log($error->getMessage());
        http_response_code(503);
        $message = "Unable to load your tasks. Please try again later.";
    }

    require __DIR__ . "/../Views/tasks/mine.php";
}

function showTasks()
{
    $categories = getTaskCategories();
    $category = $_GET["category"] ?? "";
    $tasks = [];
    $message = "";

    if (
        !is_string($category)
        || ($category !== "" && !in_array($category, $categories, true))
    ) {
        http_response_code(400);
        $message = "Please select a valid task category.";
        $category = "";
    } else {
        // Include tasks until the end of their deadline date in Sri Lanka.
        $today = new DateTimeImmutable("today", new DateTimeZone("Asia/Colombo"));

        try {
            require __DIR__ . "/../../db.php";
            require_once __DIR__ . "/../Models/TaskModel.php";

            $tasks = getAvailableTasks($connection, $today->format("Y-m-d"), $category);
        } catch (mysqli_sql_exception $error) {
            error_log($error->getMessage());
            http_response_code(503);
            $message = "Unable to load tasks. Please try again later.";
        }
    }

    require __DIR__ . "/../Views/tasks/index.php";
}
