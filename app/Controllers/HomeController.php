<?php

function showHome()
{
    $recentTasks = [];
    $recentTasksError = "";
    $today = new DateTimeImmutable("today", new DateTimeZone("Asia/Colombo"));

    try {
        require __DIR__ . "/../../db.php";
        require_once __DIR__ . "/../Models/TaskModel.php";

        $recentTasks = getRecentAvailableTasks($connection, $today->format("Y-m-d"));
    } catch (mysqli_sql_exception $error) {
        error_log($error->getMessage());
        http_response_code(503);
        $recentTasksError = "Unable to load recent tasks. Please try again later.";
    }

    require __DIR__ . "/../Views/home/index.php";
}
