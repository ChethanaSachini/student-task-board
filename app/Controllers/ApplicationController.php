<?php

function showWithdrawApplication()
{
    if (empty($_SESSION["user_id"])) {
        header("Location: login.php");
        exit;
    }

    $applicationId = $_GET["id"] ?? "";
    $application = null;
    $message = "";
    $errors = [];

    if (!is_string($applicationId)) {
        $applicationId = false;
    } else {
        $applicationId = filter_var($applicationId, FILTER_VALIDATE_INT, [
            "options" => ["min_range" => 1, "max_range" => 2147483647],
        ]);
    }

    if ($applicationId === false) {
        http_response_code(400);
        $message = "Please provide a valid application ID.";
    } else {
        try {
            require __DIR__ . "/../../db.php";
            require_once __DIR__ . "/../Models/ApplicationModel.php";
            $application = findApplicationByIdAndUser($connection, $applicationId, $_SESSION["user_id"]);

            if (!$application) {
                http_response_code(404);
                $message = "Application not found or you do not have permission to withdraw it.";
            }
        } catch (mysqli_sql_exception $error) {
            error_log($error->getMessage());
            http_response_code(503);
            $message = "Unable to load the withdrawal confirmation. Please try again later.";
        }
    }

    require __DIR__ . "/../Views/applications/withdraw.php";
}

function processWithdrawApplication()
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
        exit("Invalid request. Return to the withdrawal confirmation and try again.");
    }

    $applicationId = $_GET["id"] ?? "";
    $application = null;
    $message = "";
    $errors = [];
    $transactionStarted = false;

    if (!is_string($applicationId)) {
        $applicationId = false;
    } else {
        $applicationId = filter_var($applicationId, FILTER_VALIDATE_INT, [
            "options" => ["min_range" => 1, "max_range" => 2147483647],
        ]);
    }

    if ($applicationId === false) {
        http_response_code(400);
        $message = "Please provide a valid application ID.";
    } else {
        try {
            require __DIR__ . "/../../db.php";
            require_once __DIR__ . "/../Models/ApplicationModel.php";
            require_once __DIR__ . "/../Models/TaskModel.php";
            $application = findApplicationByIdAndUser($connection, $applicationId, $_SESSION["user_id"]);

            if (!$application) {
                http_response_code(404);
                $message = "Application not found or you do not have permission to withdraw it.";
            } elseif (($_POST["confirm_withdrawal"] ?? "") !== "1") {
                http_response_code(422);
                $errors[] = "Please confirm that you want to permanently withdraw this application.";
            } else {
                $connection->begin_transaction();
                $transactionStarted = true;

                // Lock the task before deleting its application, matching task-edit locking order.
                $task = findTaskForUpdate($connection, $application["task_id"]);
                if (!$task) {
                    http_response_code(404);
                    $message = "Application not found or you do not have permission to withdraw it.";
                } else {
                    $deletedRows = deleteApplication($connection, $applicationId, $_SESSION["user_id"]);
                    if ($deletedRows !== 1) {
                        http_response_code(404);
                        $message = "Application not found or you do not have permission to withdraw it.";
                    } else {
                        $connection->commit();
                        $transactionStarted = false;
                        $_SESSION["application_success"] = "Application withdrawn successfully.";
                        header("Location: my_applications.php", true, 303);
                        exit;
                    }
                }
            }
        } catch (mysqli_sql_exception $error) {
            error_log($error->getMessage());
            http_response_code(503);
            if ($application) {
                $errors[] = "Unable to withdraw your application. Please try again later.";
            } else {
                $message = "Unable to load the withdrawal confirmation. Please try again later.";
            }
        } finally {
            if ($transactionStarted) {
                $connection->rollback();
            }
        }
    }

    require __DIR__ . "/../Views/applications/withdraw.php";
}

function showEditApplication()
{
    if (empty($_SESSION["user_id"])) {
        header("Location: login.php");
        exit;
    }

    $applicationId = $_GET["id"] ?? "";
    $application = null;
    $message = "";
    $applicationMessage = "";
    $errors = [];

    if (!is_string($applicationId)) {
        $applicationId = false;
    } else {
        $applicationId = filter_var($applicationId, FILTER_VALIDATE_INT, [
            "options" => ["min_range" => 1, "max_range" => 2147483647],
        ]);
    }

    if ($applicationId === false) {
        http_response_code(400);
        $message = "Please provide a valid application ID.";
    } else {
        try {
            require __DIR__ . "/../../db.php";
            require_once __DIR__ . "/../Models/ApplicationModel.php";
            $application = findApplicationByIdAndUser($connection, $applicationId, $_SESSION["user_id"]);

            if (!$application) {
                http_response_code(404);
                $message = "Application not found or you do not have permission to edit it.";
            } else {
                $applicationMessage = $application["message"];
            }
        } catch (mysqli_sql_exception $error) {
            error_log($error->getMessage());
            http_response_code(503);
            $message = "Unable to load your application. Please try again later.";
        }
    }

    require __DIR__ . "/../Views/applications/edit.php";
}

function processEditApplication()
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
        exit("Invalid request. Return to the application edit form and try again.");
    }

    $applicationMessage = $_POST["message"] ?? "";
    if (!is_string($applicationMessage)) {
        http_response_code(400);
        exit("Invalid form data. Please enter text in the message field.");
    }

    $applicationId = $_GET["id"] ?? "";
    $application = null;
    $message = "";
    $errors = [];

    if (!is_string($applicationId)) {
        $applicationId = false;
    } else {
        $applicationId = filter_var($applicationId, FILTER_VALIDATE_INT, [
            "options" => ["min_range" => 1, "max_range" => 2147483647],
        ]);
    }

    if ($applicationId === false) {
        http_response_code(400);
        $message = "Please provide a valid application ID.";
    } else {
        try {
            require __DIR__ . "/../../db.php";
            require_once __DIR__ . "/../Models/ApplicationModel.php";
            $application = findApplicationByIdAndUser($connection, $applicationId, $_SESSION["user_id"]);

            if (!$application) {
                http_response_code(404);
                $message = "Application not found or you do not have permission to edit it.";
            } else {
                $trimmedMessage = trim($applicationMessage);
                if ($trimmedMessage === "" || mb_strlen($trimmedMessage) > 1000) {
                    http_response_code(422);
                    $errors[] = "Please enter a message of up to 1,000 characters.";
                } else {
                    $updatedRows = updateApplicationMessage(
                        $connection, $applicationId, $_SESSION["user_id"], $trimmedMessage
                    );

                    // Zero changed rows can mean an unchanged message or a deleted application.
                    if ($updatedRows === 0 && !findApplicationByIdAndUser($connection, $applicationId, $_SESSION["user_id"])) {
                        http_response_code(404);
                        $message = "Application not found or you do not have permission to edit it.";
                    } else {
                        $_SESSION["application_success"] = "Application message saved successfully.";
                        header("Location: my_applications.php", true, 303);
                        exit;
                    }
                }
            }
        } catch (mysqli_sql_exception $error) {
            error_log($error->getMessage());
            http_response_code(503);
            if ($application) {
                $errors[] = "Unable to save your application. Please try again later.";
            } else {
                $message = "Unable to load your application. Please try again later.";
            }
        }
    }

    require __DIR__ . "/../Views/applications/edit.php";
}

function showApplicationForm()
{
    if (empty($_SESSION["user_id"])) {
        header("Location: login.php");
        exit;
    }

    $taskId = $_GET["task_id"] ?? "";
    $task = null;
    $existingApplication = null;
    $message = "";
    $applicationMessage = "";
    $errors = [];

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
            require_once __DIR__ . "/../Models/ApplicationModel.php";
            $task = findTaskById($connection, $taskId);

            if (!$task) {
                http_response_code(404);
                $message = "Task not found.";
            } elseif ((int) $task["user_id"] === (int) $_SESSION["user_id"]) {
                http_response_code(403);
                $message = "You cannot apply for your own task.";
            } else {
                $existingApplication = findApplicationForTaskAndUser($connection, $taskId, $_SESSION["user_id"]);
                $today = new DateTimeImmutable("today", new DateTimeZone("Asia/Colombo"));

                if ($existingApplication) {
                    http_response_code(409);
                    $message = "You have already applied for this task.";
                } elseif ($task["deadline"] < $today->format("Y-m-d")) {
                    http_response_code(403);
                    $message = "The application deadline has passed. New applications are closed.";
                }
            }
        } catch (mysqli_sql_exception $error) {
            error_log($error->getMessage());
            http_response_code(503);
            $message = "Unable to load the application form. Please try again later.";
        }
    }

    require __DIR__ . "/../Views/applications/create.php";
}

function processCreateApplication()
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
        exit("Invalid request. Return to the application form and try again.");
    }

    $applicationMessage = $_POST["message"] ?? "";
    if (!is_string($applicationMessage)) {
        http_response_code(400);
        exit("Invalid form data. Please enter text in the message field.");
    }
    $applicationMessage = trim($applicationMessage);

    $taskId = $_GET["task_id"] ?? "";
    $task = null;
    $existingApplication = null;
    $message = "";
    $errors = [];
    $transactionStarted = false;
    $eligible = false;

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
            require_once __DIR__ . "/../Models/ApplicationModel.php";

            $connection->begin_transaction();
            $transactionStarted = true;
            $lockedTask = findTaskForUpdate($connection, $taskId);

            if (!$lockedTask) {
                http_response_code(404);
                $message = "Task not found.";
            } else {
                // The task is locked; this query also supplies its poster's display name.
                $task = findTaskById($connection, $taskId);

                if ((int) $task["user_id"] === (int) $_SESSION["user_id"]) {
                    http_response_code(403);
                    $message = "You cannot apply for your own task.";
                } else {
                    $existingApplication = findApplicationForTaskAndUser(
                        $connection, $taskId, $_SESSION["user_id"], true
                    );
                    $today = new DateTimeImmutable("today", new DateTimeZone("Asia/Colombo"));

                    if ($existingApplication) {
                        http_response_code(409);
                        $message = "You have already applied for this task.";
                    } elseif ($task["deadline"] < $today->format("Y-m-d")) {
                        http_response_code(403);
                        $message = "The application deadline has passed. New applications are closed.";
                    } else {
                        $eligible = true;

                        if ($applicationMessage === "" || mb_strlen($applicationMessage) > 1000) {
                            http_response_code(422);
                            $errors[] = "Please enter a message of up to 1,000 characters.";
                        } else {
                            createApplication($connection, $taskId, $_SESSION["user_id"], $applicationMessage);
                            $connection->commit();
                            $transactionStarted = false;
                            $_SESSION["application_success"] = "Application submitted successfully.";
                            header("Location: my_applications.php", true, 303);
                            exit;
                        }
                    }
                }
            }
        } catch (mysqli_sql_exception $error) {
            if ($error->getCode() === 1062) {
                http_response_code(409);
                $message = "You have already applied for this task.";
            } else {
                error_log($error->getMessage());
                http_response_code(503);
                if ($eligible) {
                    $errors[] = "Unable to submit your application. Please try again later.";
                } else {
                    $message = "Unable to load the application form. Please try again later.";
                }
            }
        } finally {
            // Rejected submissions and database errors release all locks without saving.
            if ($transactionStarted) {
                $connection->rollback();
            }
        }
    }

    require __DIR__ . "/../Views/applications/create.php";
}

function showMyApplications()
{
    if (empty($_SESSION["user_id"])) {
        header("Location: login.php");
        exit;
    }

    $successMessage = $_SESSION["application_success"] ?? "";
    unset($_SESSION["application_success"]);
    $applications = [];
    $message = "";
    $today = new DateTimeImmutable("today", new DateTimeZone("Asia/Colombo"));
    $today = $today->format("Y-m-d");

    try {
        require __DIR__ . "/../../db.php";
        require_once __DIR__ . "/../Models/ApplicationModel.php";
        $applications = getApplicationsByUser($connection, $_SESSION["user_id"]);
    } catch (mysqli_sql_exception $error) {
        error_log($error->getMessage());
        http_response_code(503);
        $message = "Unable to load your applications. Please try again later.";
    }

    require __DIR__ . "/../Views/applications/mine.php";
}

function showTaskApplications()
{
    if (empty($_SESSION["user_id"])) {
        header("Location: login.php");
        exit;
    }

    $taskId = $_GET["task_id"] ?? "";
    $task = null;
    $applications = [];
    $message = "";

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
            require_once __DIR__ . "/../Models/ApplicationModel.php";
            $task = findTaskById($connection, $taskId);

            if (!$task) {
                http_response_code(404);
                $message = "Task not found.";
            } elseif ((int) $task["user_id"] !== (int) $_SESSION["user_id"]) {
                http_response_code(403);
                $message = "You can only view applicants for your own tasks.";
                $task = null;
            } else {
                $applications = getApplicationsForTask($connection, $taskId, $_SESSION["user_id"]);
            }
        } catch (mysqli_sql_exception $error) {
            error_log($error->getMessage());
            http_response_code(503);
            $message = "Unable to load applicants. Please try again later.";
        }
    }

    require __DIR__ . "/../Views/applications/task.php";
}
