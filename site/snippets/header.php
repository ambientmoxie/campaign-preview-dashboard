<?php
$logo = asset("assets/svg/logo.svg");
$title = "Banner Preview Dashboard";
$features = [
    "real-time banner preview",
    "structured project flow",
    "collaboration made simple",
    "secure client access",
    "lightweight php & kirby"
];
$description = "This platform provides a simple way to review digital campaigns. Project managers and clients can preview banners directly, with filters to switch between campaign, version, and language. The workflow is smooth and efficient, ensuring every update is instantly available. Files are processed automatically, creating a reliable, clear, and user-friendly experience.";
?>

<header class="header">
    <div class="header__content">
        <div class="header__logo"> <?= $logo->read() ?></div>
        <h1 class="header__title"> <?= $title ?></h1>
        <span class="header__divider">---</span>
        <ul class="features">
            <?php foreach ($features as $feature): ?>
                <li class="features__item"> <?= $feature ?></li>
            <?php endforeach; ?>
        </ul>
    </div>
    <p class="header__description"><?= $description ?></p>
</header>