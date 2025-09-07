<?php

/**
 * Admin page
 * Displays the project accounts list.
 * Accessible to logged-in Kirby panel users and users with role "pm".
 */

$session = kirby()->session();
if (!kirby()->user() && $session->get('role') !== 'pm') {
    go(page('login')->url());
}
?>

<?= snippet('head') ?>
<?= snippet('header') ?>

<section class="<?= strtolower($page->intendedTemplate()) ?>">
    <div class="divider divider--<?= strtolower($page->intendedTemplate()) ?>">
        <h2 class="divider__label"><?= ucfirst($page->intendedTemplate()) ?></h2>
    </div>

    <div class="accounts">
        <h3 class="accounts__title">Overview of all projects currently available:</h3>

        <?php
        // Collect all project pages
        $projects = site()->index()->filterBy('intendedTemplate', 'project');
        ?>

        <?php if ($projects->isEmpty()): ?>
            <?= snippet('feedback-card') ?>
        <?php else: ?>
            <ul class="account-list">
                <?php
                $i = 1;
                foreach ($projects as $project):
                ?>
                    <li class="account-list__item">
                        <?= snippet("account", ["project" => $project, "i" => $i]) ?>
                    </li>
                <?php
                    $i++;
                endforeach;
                ?>
            </ul>
        <?php endif; ?>
    </div>
</section>

<?= snippet('footer') ?>
<?= snippet('foot') ?>
