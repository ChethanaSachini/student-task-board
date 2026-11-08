<?php

function findTaskById($connection, $taskId)
{
    $statement = $connection->prepare(
        "SELECT tasks.id, tasks.title, tasks.description, tasks.category,
                tasks.deadline, tasks.reward, tasks.created_at,
                users.name AS poster_name
         FROM tasks
         INNER JOIN users ON tasks.user_id = users.id
         WHERE tasks.id = ?"
    );
    $statement->bind_param("i", $taskId);

    try {
        $statement->execute();
        return $statement->get_result()->fetch_assoc();
    } finally {
        $statement->close();
    }
}

function getTasksByUser($connection, $userId)
{
    $statement = $connection->prepare(
        "SELECT id, title, category, deadline, reward, created_at
         FROM tasks
         WHERE user_id = ?
         ORDER BY created_at DESC, id DESC"
    );
    $statement->bind_param("i", $userId);

    try {
        $statement->execute();
        return $statement->get_result()->fetch_all(MYSQLI_ASSOC);
    } finally {
        $statement->close();
    }
}

function getAvailableTasks($connection, $today, $category)
{
    if ($category === "") {
        $statement = $connection->prepare(
            "SELECT id, title, category, deadline, reward, created_at
             FROM tasks
             WHERE deadline >= ?
             ORDER BY created_at DESC, id DESC"
        );
        $statement->bind_param("s", $today);
    } else {
        $statement = $connection->prepare(
            "SELECT id, title, category, deadline, reward, created_at
             FROM tasks
             WHERE deadline >= ? AND category = ?
             ORDER BY created_at DESC, id DESC"
        );
        $statement->bind_param("ss", $today, $category);
    }

    try {
        $statement->execute();
        return $statement->get_result()->fetch_all(MYSQLI_ASSOC);
    } finally {
        $statement->close();
    }
}
