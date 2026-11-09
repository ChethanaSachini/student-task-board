<?php

return [
    "/" => [
        "GET" => "showHome",
    ],
    "/signup" => [
        "GET" => "showSignup",
        "POST" => "processSignup",
    ],
    "/login" => [
        "GET" => "showLogin",
        "POST" => "processLogin",
    ],
    "/logout" => [
        "POST" => "processLogout",
    ],
    "/tasks" => [
        "GET" => "showTasks",
    ],
    "/tasks/create" => [
        "GET" => "showCreateTask",
        "POST" => "processCreateTask",
    ],
    "/tasks/show" => [
        "GET" => "showTask",
    ],
    "/tasks/mine" => [
        "GET" => "showMyTasks",
    ],
    "/tasks/edit" => [
        "GET" => "showEditTask",
        "POST" => "processEditTask",
    ],
    "/tasks/delete" => [
        "GET" => "showDeleteTask",
        "POST" => "processDeleteTask",
    ],
    "/applications/mine" => [
        "GET" => "showMyApplications",
    ],
    "/applications/create" => [
        "GET" => "showApplicationForm",
        "POST" => "processCreateApplication",
    ],
    "/applications/task" => [
        "GET" => "showTaskApplications",
    ],
];
