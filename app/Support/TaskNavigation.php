<?php

// A source token selects a fixed internal link; it never grants access.
function getTaskNavigation($source, $fallback = "browse")
{
    $destinations = [
        "browse" => ["url" => "tasks.php", "label" => "Back to Browse Tasks"],
        "my_tasks" => ["url" => "my_tasks.php", "label" => "Back to My Tasks"],
        "my_applications" => ["url" => "my_applications.php", "label" => "Back to My Applications"],
        "home" => ["url" => "index.php", "label" => "Back to Home"],
    ];

    if (!is_string($fallback) || !isset($destinations[$fallback])) {
        $fallback = "browse";
    }
    if (!is_string($source) || !isset($destinations[$source])) {
        $source = $fallback;
    }

    return ["source" => $source] + $destinations[$source];
}
