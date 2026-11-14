<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title><?= htmlspecialchars($pageTitle ?? "Student Task Board", ENT_QUOTES, "UTF-8") ?></title>
    <link rel="stylesheet" href="public/assets/css/style.css">
</head>
<body>
    <header class="site-header">
        <div class="container header-inner">
            <a class="site-name" href="index.php">
                <svg class="site-logo" width="58" height="48" viewBox="0 0 58 48"
                    fill="currentColor"
                    aria-hidden="true" focusable="false">
                    <path d="M29 3 57 16 29 29 1 16Z" />
                    <path d="M13 25v12c9 8 23 8 32 0V25l-16 7Z" />
                    <path d="M53 18v17" fill="none" stroke="currentColor" stroke-width="3" />
                    <circle cx="53" cy="36" r="3" />
                </svg>
                <span>Student<br>Task Board</span>
            </a>
            <?php require __DIR__ . "/nav.php"; ?>
        </div>
    </header>
    <main class="container">
