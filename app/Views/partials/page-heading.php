<?php
// Views supply heading text and a fixed icon name; no file paths come from input.
?>
<header class="page-heading">
    <span class="page-heading-icon" aria-hidden="true">
        <svg width="36" height="36" viewBox="0 0 32 32" fill="none"
             stroke="currentColor" stroke-width="1.8" stroke-linecap="round"
             stroke-linejoin="round" focusable="false">
            <?php if (($pageHeading["icon"] ?? "") === "login"): ?>
                <rect x="7" y="14" width="18" height="14" rx="3" />
                <path d="M11 14V9a5 5 0 0 1 10 0v5M16 20v3" />
            <?php elseif (($pageHeading["icon"] ?? "") === "signup"): ?>
                <circle cx="13" cy="9" r="5" />
                <path d="M3 28v-3a10 10 0 0 1 16-8M25 17v12M19 23h12" />
            <?php else: ?>
                <path d="M9 3h10l6 6v20H7V3h2M19 3v7h6M12 16h8M12 22h8" />
            <?php endif; ?>
        </svg>
    </span>
    <h1 id="<?= htmlspecialchars($pageHeading["id"], ENT_QUOTES, "UTF-8") ?>"><?= htmlspecialchars($pageHeading["title"], ENT_QUOTES, "UTF-8") ?></h1>
    <p><?= htmlspecialchars($pageHeading["description"], ENT_QUOTES, "UTF-8") ?></p>
</header>
