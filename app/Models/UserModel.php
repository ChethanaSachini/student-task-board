<?php

function createUser($connection, $name, $email, $password_hash)
{
    $statement = $connection->prepare(
        "INSERT INTO users (name, email, password_hash)
         VALUES (?, ?, ?)"
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
        "SELECT id, name, password_hash
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
