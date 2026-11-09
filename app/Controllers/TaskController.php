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

function readTaskFormValues()
{
    $values = [];
    foreach (["title", "description", "category", "deadline", "reward"] as $field) {
        $submittedValue = $_POST[$field] ?? "";
        if (!is_string($submittedValue)) {
            http_response_code(400);
            exit("Invalid form data. Please enter text values in the task fields.");
        }
        $values[$field] = trim($submittedValue);
    }
    return $values;
}

function normalizeTaskReward($value)
{
    if ($value === "") {
        return null;
    }
    if (!preg_match('/\A[0-9]{1,8}(?:\.[0-9]{1,2})?\z/', $value)) {
        return false;
    }

    $parts = explode(".", $value);
    $whole = ltrim($parts[0], "0");
    if ($whole === "") {
        $whole = "0";
    }
    return $whole . "." . str_pad($parts[1] ?? "", 2, "0");
}

function validateTaskValues($values, $originalDeadline = null)
{
    $errors = [];
    $timezone = new DateTimeZone("Asia/Colombo");
    $today = new DateTimeImmutable("today", $timezone);

    if ($values["title"] === "" || mb_strlen($values["title"]) > 150) {
        $errors[] = "Please enter a title of up to 150 characters.";
    }
    if ($values["description"] === "" || mb_strlen($values["description"]) > 5000) {
        $errors[] = "Please enter a description of up to 5,000 characters.";
    }
    if (!in_array($values["category"], getTaskCategories(), true)) {
        $errors[] = "Please select a valid task category.";
    }

    $deadline = false;
    if (preg_match('/\A[0-9]{4}-[0-9]{2}-[0-9]{2}\z/', $values["deadline"])) {
        $deadline = DateTimeImmutable::createFromFormat("!Y-m-d", $values["deadline"], $timezone);
    }
    // A round-trip check rejects impossible dates such as February 30.
    if (!$deadline || $deadline->format("Y-m-d") !== $values["deadline"]) {
        $errors[] = "Please enter a valid application deadline.";
    } elseif ($values["deadline"] !== $originalDeadline && $deadline < $today) {
        $errors[] = "The application deadline must be today or a later date in Sri Lanka.";
    }

    $reward = normalizeTaskReward($values["reward"]);
    if ($reward === false) {
        $errors[] = "Enter a reward from LKR 0.00 to LKR 99,999,999.99 with no more than two decimal places, or leave it blank.";
        $reward = null;
    }

    return ["errors" => $errors, "reward" => $reward];
}

function showDeleteTask()
{
    if (empty($_SESSION["user_id"])) {
        header("Location: login.php");
        exit;
    }

    $taskId = $_GET["id"] ?? "";
    $task = null;
    $message = "";
    $applicationCount = 0;

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
            } elseif ((int) $task["user_id"] !== (int) $_SESSION["user_id"]) {
                http_response_code(403);
                $message = "You can only delete your own tasks.";
                $task = null;
            } else {
                $applicationCount = getTaskApplicationCount($connection, $taskId);
            }
        } catch (mysqli_sql_exception $error) {
            error_log($error->getMessage());
            http_response_code(503);
            $message = "Unable to load the deletion confirmation. Please try again later.";
        }
    }

    require __DIR__ . "/../Views/tasks/delete.php";
}

function processDeleteTask()
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
        exit("Invalid request. Return to the task deletion page and try again.");
    }

    if (($_POST["confirm_delete"] ?? "") !== "1") {
        http_response_code(400);
        exit("Please confirm that you want to permanently delete this task and all its applications.");
    }

    $taskId = $_GET["id"] ?? "";
    $task = null;
    $message = "";
    $applicationCount = 0;
    $transactionStarted = false;

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

            $connection->begin_transaction();
            $transactionStarted = true;
            $task = findTaskForUpdate($connection, $taskId);

            if (!$task) {
                http_response_code(404);
                $message = "Task not found.";
            } elseif ((int) $task["user_id"] !== (int) $_SESSION["user_id"]) {
                http_response_code(403);
                $message = "You can only delete your own tasks.";
                $task = null;
            } else {
                $deletedRows = deleteTask($connection, $taskId, $_SESSION["user_id"]);

                if ($deletedRows === 1) {
                    // The database also deletes related applications through its foreign key.
                    $connection->commit();
                    $transactionStarted = false;
                    $_SESSION["task_success"] = "Task and any related applications deleted successfully.";
                    header("Location: my_tasks.php", true, 303);
                    exit;
                }

                http_response_code(404);
                $message = "Task not found.";
            }
        } catch (mysqli_sql_exception $error) {
            error_log($error->getMessage());
            http_response_code(503);
            $message = "Unable to delete this task. Please try again later.";
        } finally {
            if ($transactionStarted) {
                $connection->rollback();
            }
        }
    }

    require __DIR__ . "/../Views/tasks/delete.php";
}

function showEditTask()
{
    if (empty($_SESSION["user_id"])) {
        header("Location: login.php");
        exit;
    }

    $taskId = $_GET["id"] ?? "";
    $task = null;
    $message = "";
    $categories = getTaskCategories();
    $isExpired = false;
    $values = [];
    $errors = [];
    $termsLocked = false;

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
            } elseif ((int) $task["user_id"] !== (int) $_SESSION["user_id"]) {
                http_response_code(403);
                $message = "You can only edit your own tasks.";
                $task = null;
            } else {
                $today = new DateTimeImmutable("today", new DateTimeZone("Asia/Colombo"));
                $isExpired = $task["deadline"] < $today->format("Y-m-d");
                $values = $task;
                $values["reward"] = $task["reward"] ?? "";
                $termsLocked = taskHasApplications($connection, $taskId);
            }
        } catch (mysqli_sql_exception $error) {
            error_log($error->getMessage());
            http_response_code(503);
            $message = "Unable to load this task. Please try again later.";
        }
    }

    require __DIR__ . "/../Views/tasks/edit.php";
}

function processEditTask()
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
        exit("Invalid request. Return to the task edit page and try again.");
    }

    $taskId = $_GET["id"] ?? "";
    $task = null;
    $message = "";
    $categories = getTaskCategories();
    $isExpired = false;
    $values = [];
    $errors = [];
    $termsLocked = false;
    $transactionStarted = false;

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
        // Reject malformed fields before starting a database transaction.
        $values = readTaskFormValues();

        try {
            require __DIR__ . "/../../db.php";
            require_once __DIR__ . "/../Models/TaskModel.php";

            $connection->begin_transaction();
            $transactionStarted = true;
            $task = findTaskForUpdate($connection, $taskId);

            if (!$task) {
                http_response_code(404);
                $message = "Task not found.";
            } elseif ((int) $task["user_id"] !== (int) $_SESSION["user_id"]) {
                http_response_code(403);
                $message = "You can only edit your own tasks.";
                $task = null;
            } else {
                $today = new DateTimeImmutable("today", new DateTimeZone("Asia/Colombo"));
                $isExpired = $task["deadline"] < $today->format("Y-m-d");
                $termsLocked = taskHasApplications($connection, $taskId);

                if ($termsLocked) {
                    if (array_key_exists("deadline", $_POST) && $values["deadline"] !== $task["deadline"]) {
                        $errors[] = "The deadline cannot be changed because applications already exist.";
                    }
                    if (
                        array_key_exists("reward", $_POST)
                        && normalizeTaskReward($values["reward"]) !== normalizeTaskReward($task["reward"] ?? "")
                    ) {
                        $errors[] = "The reward cannot be changed because applications already exist.";
                    }

                    // Disabled fields are omitted by the browser. Always use stored terms.
                    $values["deadline"] = $task["deadline"];
                    $values["reward"] = $task["reward"] ?? "";
                }

                $validation = validateTaskValues($values, $task["deadline"]);
                $errors = array_merge($errors, $validation["errors"]);

                if (!empty($errors)) {
                    http_response_code(422);
                } else {
                    updateTask(
                        $connection, $taskId, $_SESSION["user_id"],
                        $values["title"], $values["description"], $values["category"],
                        $values["deadline"], $validation["reward"]
                    );

                    // The locked task cannot disappear; unchanged values are also success.
                    $connection->commit();
                    $transactionStarted = false;
                    header("Location: task.php?id=" . $taskId, true, 303);
                    exit;
                }
            }
        } catch (mysqli_sql_exception $error) {
            error_log($error->getMessage());
            http_response_code(503);
            if ($task && !empty($values)) {
                $errors[] = "Unable to save this task. Please try again later.";
            } else {
                $message = "Unable to load this task. Please try again later.";
            }
        } finally {
            // Release locks on validation failures, permission failures and database errors.
            if ($transactionStarted) {
                $connection->rollback();
            }
        }
    }

    require __DIR__ . "/../Views/tasks/edit.php";
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
    $values = readTaskFormValues();
    $validation = validateTaskValues($values);
    $errors = $validation["errors"];
    $reward = $validation["reward"];

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

    $successMessage = $_SESSION["task_success"] ?? "";
    unset($_SESSION["task_success"]);
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
