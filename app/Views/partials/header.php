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
                <svg class="site-logo" width="44" height="44" viewBox="0 0 48 48"
                    fill="none" stroke="currentColor" stroke-width="3.5" stroke-linejoin="round"
                    aria-hidden="true" focusable="false">
                    <path d="M24 4 44 14 24 24 4 14Z" />
                    <path d="m12 20-8 4 20 10 20-10-8-4" />
                    <path d="m12 30-8 4 20 10 20-10-8-4" />
                </svg>
                <span>Student Task Board</span>
            </a>
            <?php require __DIR__ . "/nav.php"; ?>
        </div>
    </header>
    <main class="container">
