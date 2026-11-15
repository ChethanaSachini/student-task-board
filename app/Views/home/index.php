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
            <p class="hero-description">A simple task board for university students to post tasks, find opportunities and collaborate with peers.</p>

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

<div class="home-benefits" role="region" aria-label="Platform benefits">
    <svg class="benefits-wave" viewBox="0 0 1200 120" preserveAspectRatio="none" aria-hidden="true" focusable="false">
        <path fill="#F0E3F5" d="M0 0C250 40 260 120 570 100S990 20 1200 55V120H0Z" />
    </svg>
    <ul class="container benefit-list">
        <li>
            <svg width="36" height="36" viewBox="0 0 32 32" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true" focusable="false">
                <circle cx="13" cy="10" r="5" />
                <path d="M3 27v-3a10 10 0 0 1 20 0v3ZM23 5a5 5 0 0 1 0 10M25 19a8 8 0 0 1 4 7" />
            </svg>
            <span>For University Students</span>
        </li>
        <li>
            <svg width="36" height="36" viewBox="0 0 32 32" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true" focusable="false">
                <rect x="6" y="5" width="21" height="24" rx="3" />
                <path d="m10 13 2 2 3-4M19 13h4M10 21h13M12 5V3h9v2" />
            </svg>
            <span>Manage Your Tasks</span>
        </li>
        <li>
            <svg width="36" height="36" viewBox="0 0 32 32" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true" focusable="false">
                <path d="m18 2-13 17h10l-1 11 13-18H17Z" />
            </svg>
            <span>Peer Collaboration</span>
        </li>
    </ul>
</div>

<section class="how-it-works" aria-labelledby="how-it-works-title">
    <div class="container">
        <h2 id="how-it-works-title">
            <svg class="section-spark" width="32" height="40" viewBox="0 0 32 40" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" aria-hidden="true" focusable="false"><path d="m8 4 11 7M3 20h17M8 36l11-7" /></svg>
            <span>How It Works</span>
            <svg class="section-spark" width="32" height="40" viewBox="0 0 32 40" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" aria-hidden="true" focusable="false"><path d="m24 4-11 7M29 20H12M24 36l-11-7" /></svg>
        </h2>
        <p class="steps-introduction">Get started in three simple steps.</p>

        <div class="steps-wrapper">
            <svg class="steps-connector" viewBox="0 0 1000 70" preserveAspectRatio="none" fill="none" aria-hidden="true" focusable="false">
                <path d="M0 35Q125-25 250 35T500 35T750 35T1000 35" stroke="#B7AEBE" stroke-width="2" stroke-dasharray="7 8" />
            </svg>
            <ol class="steps">
                <li class="step-card">
                    <svg class="step-icon step-icon-post" width="80" height="80" viewBox="0 0 48 48" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true" focusable="false">
                        <path d="M21 10H10a3 3 0 0 0-3 3v25a3 3 0 0 0 3 3h25a3 3 0 0 0 3-3V26" />
                        <path d="m19 29 2-9L36 5l7 7-15 15ZM33 8l7 7" />
                    </svg>
                    <h3>1. Post a Task</h3>
                    <p>Sign up or log in to share a task you need help with.</p>
                </li>
                <li class="step-card">
                    <svg class="step-icon step-icon-apply" width="80" height="80" viewBox="0 0 48 48" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true" focusable="false">
                        <circle cx="20" cy="15" r="7" />
                        <path d="M6 39v-3a14 14 0 0 1 28 0v3ZM33 9a7 7 0 0 1 0 13M37 28a12 12 0 0 1 6 10" />
                    </svg>
                    <h3>2. Apply for Tasks</h3>
                    <p>Browse available tasks and log in to apply with a message.</p>
                </li>
                <li class="step-card">
                    <svg class="step-icon step-icon-view" width="80" height="80" viewBox="0 0 48 48" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true" focusable="false">
                        <rect x="10" y="6" width="28" height="36" rx="4" />
                        <path d="M18 15h12M18 22h12m-12 9 4 4 8-8" />
                    </svg>
                    <h3>3. View Applications</h3>
                    <p>Task posters can review applications to their own tasks.</p>
                </li>
            </ol>
        </div>
    </div>
</section>

<section class="home-about" id="home-about" aria-labelledby="home-about-title">
    <div class="container">
        <h2 id="home-about-title">About Student Task Board</h2>
        <p>Student Task Board helps university students find and share small tasks. Students can use one account to post their own tasks, apply to other students' tasks, and manage their tasks and applications.</p>
    </div>
</section>

<?php require __DIR__ . "/../partials/footer.php"; ?>
