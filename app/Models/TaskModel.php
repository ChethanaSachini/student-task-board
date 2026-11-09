<?php

function createTask($connection, $userId, $title, $description, $category, $deadline, $reward)
{
    $statement = $connection->prepare(
        "INSERT INTO tasks (user_id, title, description, category, deadline, reward)
         VALUES (?, ?, ?, ?, ?, ?)"
    );
    // Bind the reward as a decimal string (or NULL), without float conversion.
    $statement->bind_param("isssss", $userId, $title, $description, $category, $deadline, $reward);

    try {
        $statement->execute();
        return $connection->insert_id;
    } finally {
        $statement->close();
    }
}

function findTaskForUpdate($connection, $taskId)
{
    $statement = $connection->prepare(
        "SELECT id, user_id, title, description, category, deadline, reward
         FROM tasks
         WHERE id = ?
         FOR UPDATE"
    );
    $statement->bind_param("i", $taskId);

    try {
        $statement->execute();
        return $statement->get_result()->fetch_assoc();
    } finally {
        $statement->close();
    }
}

function deleteTask($connection, $taskId, $userId)
{
    $statement = $connection->prepare(
        "DELETE FROM tasks WHERE id = ? AND user_id = ?"
    );
    $statement->bind_param("ii", $taskId, $userId);

    try {
        $statement->execute();
        return $statement->affected_rows;
    } finally {
        $statement->close();
    }
}

function getTaskApplicationCount($connection, $taskId)
{
    $statement = $connection->prepare(
        "SELECT COUNT(*) AS application_count FROM applications WHERE task_id = ?"
    );
    $statement->bind_param("i", $taskId);

    try {
        $statement->execute();
        $result = $statement->get_result()->fetch_assoc();
        return (int) $result["application_count"];
    } finally {
        $statement->close();
    }
}

function taskHasApplications($connection, $taskId)
{
    $statement = $connection->prepare(
        "SELECT id FROM applications
         WHERE task_id = ?
         LIMIT 1
         LOCK IN SHARE MODE"
    );
    $statement->bind_param("i", $taskId);

    try {
        $statement->execute();
        // A locking read checks current rows instead of an older transaction snapshot.
        return $statement->get_result()->fetch_assoc() !== null;
    } finally {
        $statement->close();
    }
}

function updateTask($connection, $taskId, $userId, $title, $description, $category, $deadline, $reward)
{
    $statement = $connection->prepare(
        "UPDATE tasks
         SET title = ?, description = ?, category = ?, deadline = ?, reward = ?
         WHERE id = ? AND user_id = ?"
    );
    $statement->bind_param("sssssii", $title, $description, $category, $deadline, $reward, $taskId, $userId);

    try {
        $statement->execute();
        return $statement->affected_rows;
    } finally {
        $statement->close();
    }
}

function findTaskById($connection, $taskId)
{
    $statement = $connection->prepare(
        "SELECT tasks.id, tasks.user_id, tasks.title, tasks.description, tasks.category,
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
