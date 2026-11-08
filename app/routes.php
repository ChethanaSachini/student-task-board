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
    "/tasks/show" => [
        "GET" => "showTask",
    ],
    "/tasks/mine" => [
        "GET" => "showMyTasks",
    ],
];
