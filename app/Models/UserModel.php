<?php

function createUser($connection, $name, $email, $password_hash)
{
    $statement = $connection->prepare(
        "INSERT INTO users (name, email, password_hash, role)
         VALUES (?, ?, ?, 'student')"
    );

    $statement->bind_param("sss", $name, $email, $password_hash);

    try {
        $statement->execute();
    } finally {
        $statement->close();
    }
}

function findUserByEmail($connection, $email)
{
    $statement = $connection->prepare(
        "SELECT id, name, password_hash, role
         FROM users
         WHERE email = ?"
    );

    $statement->bind_param("s", $email);

    try {
        $statement->execute();
        return $statement->get_result()->fetch_assoc();
    } finally {
        $statement->close();
    }
}

function findUserById($connection, $userId)
{
    $statement = $connection->prepare(
        "SELECT id, name, role
         FROM users
         WHERE id = ?"
    );

    $statement->bind_param("i", $userId);

    try {
        $statement->execute();
        return $statement->get_result()->fetch_assoc();
    } finally {
        $statement->close();
    }
}

function getAdminUsers($connection)
{
    // Only display fields are needed; never load password hashes for this list.
    $statement = $connection->prepare(
        "SELECT id, name, email, role, created_at
         FROM users
         ORDER BY created_at DESC, id DESC"
    );

    try {
        $statement->execute();
        return $statement->get_result()->fetch_all(MYSQLI_ASSOC);
    } finally {
        $statement->close();
    }
}
