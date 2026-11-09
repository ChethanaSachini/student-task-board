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

function showCreateTask()
{
    if (empty($_SESSION["user_id"])) {
        header("Location: login.php");
        exit;
    }

    $categories = getTaskCategories();
    $today = new DateTimeImmutable("today", new DateTimeZone("Asia/Colombo"));
    $minimumDeadline = $today->format("Y-m-d");
    $values = [
        "title" => "",
        "description" => "",
        "category" => "",
        "deadline" => "",
        "reward" => "",
    ];
    $errors = [];

    require __DIR__ . "/../Views/tasks/create.php";
}

function processCreateTask()
{
    if (empty($_SESSION["user_id"])) {
        header("Location: login.php");
        exit;
    }

    $token = $_POST["csrf_token"] ?? "";
    if (
        !is_string($token)
        || empty($_SESSION["csrf_token"])
        || !hash_equals($_SESSION["csrf_token"], $token)
    ) {
        http_response_code(403);
        exit("Invalid request. Return to the task creation page and try again.");
    }

    $categories = getTaskCategories();
    $timezone = new DateTimeZone("Asia/Colombo");
    $today = new DateTimeImmutable("today", $timezone);
    $minimumDeadline = $today->format("Y-m-d");
    $values = [
        "title" => "",
        "description" => "",
        "category" => "",
        "deadline" => "",
        "reward" => "",
    ];
    $errors = [];

    foreach ($values as $field => $value) {
        $submittedValue = $_POST[$field] ?? "";
        if (!is_string($submittedValue)) {
            http_response_code(400);
            exit("Invalid form data. Please enter text values in the task fields.");
        }
        $values[$field] = trim($submittedValue);
    }

    if ($values["title"] === "" || mb_strlen($values["title"]) > 150) {
        $errors[] = "Please enter a title of up to 150 characters.";
    }

    if ($values["description"] === "" || mb_strlen($values["description"]) > 5000) {
        $errors[] = "Please enter a description of up to 5,000 characters.";
    }

    if (!in_array($values["category"], $categories, true)) {
        $errors[] = "Please select a valid task category.";
    }

    $deadline = false;
    if (preg_match('/\A[0-9]{4}-[0-9]{2}-[0-9]{2}\z/', $values["deadline"])) {
        $deadline = DateTimeImmutable::createFromFormat("!Y-m-d", $values["deadline"], $timezone);
    }

    // Comparing the formatted date also rejects impossible dates such as February 30.
    if (!$deadline || $deadline->format("Y-m-d") !== $values["deadline"]) {
        $errors[] = "Please enter a valid application deadline.";
    } elseif ($deadline < $today) {
        $errors[] = "The application deadline must be today or a later date in Sri Lanka.";
    }

    $reward = null;
    if ($values["reward"] !== "") {
        if (!preg_match('/\A[0-9]{1,8}(?:\.[0-9]{1,2})?\z/', $values["reward"])) {
            $errors[] = "Enter a reward from LKR 0.00 to LKR 99,999,999.99 with no more than two decimal places, or leave it blank.";
        } else {
            $rewardParts = explode(".", $values["reward"]);
            $reward = $rewardParts[0] . "." . str_pad($rewardParts[1] ?? "", 2, "0");
        }
    }

    if (empty($errors)) {
        try {
            require __DIR__ . "/../../db.php";
            require_once __DIR__ . "/../Models/TaskModel.php";

            $taskId = createTask(
                $connection,
                $_SESSION["user_id"],
                $values["title"],
                $values["description"],
                $values["category"],
                $values["deadline"],
                $reward
            );
        } catch (mysqli_sql_exception $error) {
            error_log($error->getMessage());
            http_response_code(503);
            $errors[] = "Unable to create your task. Please try again later.";
        }

        if (empty($errors)) {
            header("Location: task.php?id=" . $taskId, true, 303);
            exit;
        }
    } else {
        http_response_code(422);
    }

    require __DIR__ . "/../Views/tasks/create.php";
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
