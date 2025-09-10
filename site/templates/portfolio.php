<?php

/**
 * Portfolio page
 * Displays a list of projects flagged to appear in the public portfolio.
 * Accessible to all users (public page).
 * Uses shared snippets for head, header, footer, and foot.
 */

?>

<?= snippet("head") ?>
<?php
$projects = site()->index()
    ->filterBy('intendedTemplate', 'project')
    ->filterBy('showinportfolio', 'true');
?>

<h2>selected <br />
    campaigns<br />
    for public<br />
    access</h2>

<?php if ($projects->isEmpty()): ?>
    <?= snippet("feedback") ?>
<?php else: ?>
    <ul class="public-list">
        <?php
        $i = 1;
        foreach ($projects as $project):
        ?>
            <li class="public-list__item">
                <a href="<?= $project->url() ?>" class="account__redirect" target="_blank"><?= $project->title()->esc() ?></a>
            </li>
        <?php
            $i++;
        endforeach;
        ?>
    </ul>
<?php endif; ?>

<?= snippet("logout-btn") ?>
<?= snippet("foot") ?>