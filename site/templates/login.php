<?php
/**
 * Authentication page
 * Displays the login form inside an auth section.
 * Uses shared snippets for head, header, footer, and foot.
 */
?>

<?= snippet('head'); ?>
<?= snippet('header') ?>

<section class="auth">
    <div class="divider divider--auth">
        <h2 class="divider__label">Authentification</h2>
    </div>
    <div class="login-modal">
        <?= snippet("form") ?>
    </div>
</section>

<?= snippet('footer'); ?>
<?= snippet('foot') ?>