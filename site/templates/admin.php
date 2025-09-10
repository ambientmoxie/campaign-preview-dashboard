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

<?php
// Collect all project pages
$projects = site()->index()->filterBy('intendedTemplate', 'project');
?>

<?php if ($projects->isEmpty()): ?>
    <?= snippet('feedback') ?>
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


<?= snippet('logout-btn') ?>
<?= snippet('foot') ?>