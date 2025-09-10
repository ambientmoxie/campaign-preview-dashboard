<?php

/**
 * Authentication page
 * Displays the login form inside an auth section.
 * Uses shared snippets for head, header, footer, and foot.
 */
?>

<?= snippet('head'); ?>

<header class="header">
    <h1 class="header__title">banner(dot)hub</h1>
    <ul class="features">
        <li class="features__item">real-time previews</li>
        <li class="features__item">structured workflow</li>
        <li class="features__item">secure access</li>
    </ul>
    <p class="header__description">preview campaigns with filters and instant updates for a smooth, reliable collaboration.</p>
</header>
<?= snippet("form") ?>

<div class="credit">
    <a href="" class="credit__item credit__item--sandbox-link">try our test banner</a>
    <div class="credit__item credit__item--copyright">last updated 2025</div>
</div>

<?= snippet('foot') ?>