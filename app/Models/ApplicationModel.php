<?php

function deleteApplication($connection, $applicationId, $userId)
{
    $statement = $connection->prepare(
        "DELETE FROM applications WHERE id = ? AND user_id = ?"
    );
    $statement->bind_param("ii", $applicationId, $userId);

    try {
        $statement->execute();
        return $statement->affected_rows;
    } finally {
        $statement->close();
    }
}

function updateApplicationMessage($connection, $applicationId, $userId, $message)
{
    $statement = $connection->prepare(
        "UPDATE applications SET message = ? WHERE id = ? AND user_id = ?"
    );
    $statement->bind_param("sii", $message, $applicationId, $userId);

    try {
        $statement->execute();
        return $statement->affected_rows;
    } finally {
        $statement->close();
    }
}

function findApplicationByIdAndUser($connection, $applicationId, $userId)
{
    $statement = $connection->prepare(
        "SELECT applications.id, applications.task_id, applications.message,
                applications.created_at, applications.updated_at,
                tasks.title, tasks.deadline
         FROM applications
         INNER JOIN tasks ON applications.task_id = tasks.id
         WHERE applications.id = ? AND applications.user_id = ?"
    );
    $statement->bind_param("ii", $applicationId, $userId);

    try {
        $statement->execute();
        return $statement->get_result()->fetch_assoc();
    } finally {
        $statement->close();
    }
}

function createApplication($connection, $taskId, $userId, $message)
{
    $statement = $connection->prepare(
        "INSERT INTO applications (task_id, user_id, message) VALUES (?, ?, ?)"
    );
    $statement->bind_param("iis", $taskId, $userId, $message);

    try {
        $statement->execute();
        return $connection->insert_id;
    } finally {
        $statement->close();
    }
}

function findApplicationForTaskAndUser($connection, $taskId, $userId, $lockRows = false)
{
    $sql = "SELECT id FROM applications WHERE task_id = ? AND user_id = ?";
    if ($lockRows) {
        $sql .= " LOCK IN SHARE MODE";
    }
    $statement = $connection->prepare(
        $sql
    );
    $statement->bind_param("ii", $taskId, $userId);

    try {
        $statement->execute();
        return $statement->get_result()->fetch_assoc();
    } finally {
        $statement->close();
    }
}

function getApplicationsByUser($connection, $userId)
{
    $statement = $connection->prepare(
        "SELECT applications.id, applications.task_id, applications.message,
                applications.created_at, applications.updated_at,
                tasks.title, tasks.category, tasks.deadline, tasks.reward
         FROM applications
         INNER JOIN tasks ON applications.task_id = tasks.id
         WHERE applications.user_id = ?
         ORDER BY applications.created_at DESC, applications.id DESC"
    );
    $statement->bind_param("i", $userId);

    try {
        $statement->execute();
        return $statement->get_result()->fetch_all(MYSQLI_ASSOC);
    } finally {
        $statement->close();
    }
}

function getApplicationsForTask($connection, $taskId, $ownerId)
{
    $statement = $connection->prepare(
        "SELECT applications.id, applications.message,
                applications.created_at, applications.updated_at,
                users.name AS applicant_name
         FROM applications
         INNER JOIN users ON applications.user_id = users.id
         INNER JOIN tasks ON applications.task_id = tasks.id
         WHERE applications.task_id = ? AND tasks.user_id = ?
         ORDER BY applications.created_at DESC, applications.id DESC"
    );
    $statement->bind_param("ii", $taskId, $ownerId);

    try {
        $statement->execute();
        return $statement->get_result()->fetch_all(MYSQLI_ASSOC);
    } finally {
        $statement->close();
    }
}
