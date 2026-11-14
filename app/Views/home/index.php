<?php
$pageTitle = "Student Task Board";
$activePage = "home";
require __DIR__ . "/../partials/header.php";
?>

<section class="home-hero" aria-labelledby="hero-title">
    <div class="container hero-inner">
        <div class="hero-copy">
            <?php if (isset($_SESSION["user_id"])): ?>
                <p class="hero-welcome">Welcome back, <?= htmlspecialchars($_SESSION["user_name"], ENT_QUOTES, "UTF-8") ?>!</p>
            <?php endif; ?>

            <h1 id="hero-title">Get Tasks Done <span>Together<svg class="hero-spark" width="40" height="58" viewBox="0 0 40 58" fill="none" stroke="currentColor" stroke-width="3" stroke-linecap="round" aria-hidden="true" focusable="false"><path d="M5 15 20 5M8 29l26 1M5 43l15 12" /></svg></span></h1>
            <p class="hero-description" id="home-about">A simple task board for university students to post tasks, find opportunities and collaborate with peers.</p>

            <div class="hero-actions">
                <a class="hero-button" href="<?= isset($_SESSION["user_id"]) ? 'create_task.php' : 'signup.php' ?>">
                    <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" aria-hidden="true" focusable="false"><path d="M12 5v14M5 12h14" /></svg>
                    <?= isset($_SESSION["user_id"]) ? 'Post a Task' : 'Sign Up to Post' ?>
                </a>
                <a class="hero-button hero-button-secondary" href="tasks.php">
                    <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" aria-hidden="true" focusable="false"><circle cx="10" cy="10" r="6.5" /><path d="m15 15 5 5" /></svg>
                    Browse Tasks
                </a>
            </div>
        </div>
    </div>
    <img class="hero-illustration" src="public/assets/images/home-student.png"
        width="1536" height="1024" alt="University student working at a laptop beside books and plants in a sunlit study space."
        fetchpriority="high">
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
