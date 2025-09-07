<?php

/**
 * Portfolio page
 * Displays a list of projects flagged to appear in the public portfolio.
 * Accessible to all users (public page).
 * Uses shared snippets for head, header, footer, and foot.
 */

?>

<?= snippet("head") ?>
<?= snippet("header") ?>

<section class="<?= strtolower($page->intendedTemplate()) ?>">
    <div class="divider divider--<?= strtolower($page->intendedTemplate()) ?>">
        <h2 class="divider__label"><?= ucfirst($page->intendedTemplate()) ?></h2>
    </div>

    <div class="public">
        <p class="public__requirement">This page presents a curated selection of projects that have been marked for public view.
            Each project can be explored in detail through its dashboard, allowing visitors to preview
            the campaign workflow and experience banners in context.</p>
        <h3 class="public__title">Discover selected campaigns available for public access:</h3>
        <?php
        $projects = site()->index()
            ->filterBy('intendedTemplate', 'project')
            ->filterBy('showinportfolio', 'true');
        ?>

        <?php if ($projects->isEmpty()): ?>
            <?= snippet("feedback-card") ?>
        <?php else: ?>
            <ul class="public-list">
                <?php
                $i = 1;
                foreach ($projects as $project):
                ?>
                    <li class="public-list__item">
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

<?= snippet("footer") ?>
<?= snippet("foot") ?>