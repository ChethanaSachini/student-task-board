<?php
$pageTitle = "Student Task Board";
$activePage = "home";
require __DIR__ . "/../partials/header.php";
?>

<section class="home-content">
    <svg class="home-waves" viewBox="0 0 900 500" preserveAspectRatio="none"
        aria-hidden="true" focusable="false">
        <path fill="#E4EAD6" d="M0 500C170 350 245 440 430 300S720 80 900 45V500Z" />
        <path fill="#D9E3CD" d="M70 500C245 395 330 475 525 370S760 250 900 270V500Z" />
    </svg>
    <div class="container">
    <p class="home-eyebrow">A student-to-student task platform</p>
    <?php if (isset($_SESSION["user_id"])): ?>
        <p class="welcome">
            Welcome back,
            <?= htmlspecialchars(
                $_SESSION["user_name"],
                ENT_QUOTES,
                "UTF-8"
            ) ?>!
        </p>
    <?php endif; ?>

    <h1>Find a Task or <span>Post Your Own</span></h1>
    <p class="home-description">Connect with fellow students to get help with small tasks or contribute your skills.</p>

    <div class="home-actions">
        <a class="button" href="tasks.php">
            <svg class="button-icon" width="28" height="28" viewBox="0 0 24 24"
                fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                aria-hidden="true" focusable="false">
                <circle cx="10" cy="10" r="7" />
                <path d="m15 15 6 6" />
            </svg>
            Browse Tasks
        </a>
        <?php if (isset($_SESSION["user_id"])): ?>
            <a class="button button-secondary" href="create_task.php">
                <svg class="button-icon" width="28" height="28" viewBox="0 0 24 24"
                    fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                    aria-hidden="true" focusable="false">
                    <rect x="3" y="3" width="18" height="18" rx="4" />
                    <path d="M12 7v10M7 12h10" />
                </svg>
                Post Task
            </a>
        <?php else: ?>
            <a class="button button-secondary" href="signup.php">
                <svg class="button-icon" width="28" height="28" viewBox="0 0 24 24"
                    fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                    aria-hidden="true" focusable="false">
                    <circle cx="9" cy="6" r="4" />
                    <path d="M2 21v-2a7 7 0 0 1 12-5M19 13v8M15 17h8" />
                </svg>
                Create an Account
            </a>
        <?php endif; ?>
    </div>
    </div>
</section>

<section class="how-it-works" aria-labelledby="how-it-works-title">
    <div class="container">
    <h2 id="how-it-works-title">How It Works</h2>
    <div class="steps">
        <div class="step-card">
            <svg class="step-icon" width="64" height="64" viewBox="0 0 48 48"
                fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round"
                aria-hidden="true" focusable="false">
                <path d="M18 13h20M18 24h20M18 35h20" />
                <circle cx="9" cy="13" r="1.5" fill="currentColor" />
                <circle cx="9" cy="24" r="1.5" fill="currentColor" />
                <circle cx="9" cy="35" r="1.5" fill="currentColor" />
            </svg>
            <div>
            <h3>Browse tasks</h3>
            <p>Find available tasks and filter by category.</p>
            </div>
        </div>
        <div class="step-card">
            <svg class="step-icon" width="64" height="64" viewBox="0 0 48 48"
                fill="none" stroke="currentColor" stroke-width="2.5" stroke-linejoin="round"
                aria-hidden="true" focusable="false">
                <path d="m6 21 35-14-13 35-6-15-16-6Z" />
                <path d="M22 27 41 7" />
            </svg>
            <div>
            <h3>Apply with a message</h3>
            <p>Log in and explain how you can help.</p>
            </div>
        </div>
        <div class="step-card">
            <svg class="step-icon" width="64" height="64" viewBox="0 0 48 48"
                fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round"
                aria-hidden="true" focusable="false">
                <rect x="8" y="8" width="32" height="32" rx="7" />
                <path d="M24 16v16M16 24h16" />
            </svg>
            <div>
            <h3>Post your own</h3>
            <p>Describe your task and view applications from other students.</p>
            </div>
        </div>
    </div>
    </div>
</section>

<?php require __DIR__ . "/../partials/footer.php"; ?>
