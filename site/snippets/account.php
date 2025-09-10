<div class="account">
    <h1 class="account__name"><?= $project->title()->esc() ?></h1>
    <div class="account__data">
        <div class="account__index"><?= str_pad($i, 3, '0', STR_PAD_LEFT) ?></div>
        <ul class="account__list">
            <!-- Creation date -->
            <li class="account__data account__data--creation">
                <span class="account__label">creation date</span>
                <span class="account__entry">
                    <?= $project->created()->toDate('M d Y') ?>
                </span>
            </li>

            <!-- Last updated -->
            <li class="account__data account__data--update">
                <span class="account__label">last updated</span>
                <span class="account__entry">
                    <?= $project->modified('M d Y') ?>
                </span>
            </li>

            <!-- Campaigns count (children of project) -->
            <li class="account__data account__data--campaigns">
                <span class="account__label">campaigns</span>
                <span class="account__entry"><?= $project->children()->count() ?></span>
            </li>

            <!-- Development status -->
            <li class="account__data account__data--dev">
                <span class="account__label">in development</span>
                <span class="account__entry">
                    <?= $project->indevelopment()->toBool() ? 'yes' : 'no' ?>
                </span>
            </li>

            <!-- Portfolio status -->
            <li class="account__data account__data--portfolio">
                <span class="account__label">in portfolio</span>
                <span class="account__entry">
                    <?= $project->showinportfolio()->toBool() ? 'yes' : 'no' ?>
                </span>
            </li>

            <!-- Project password -->
            <li class="account__data account__data--password">
                <span class="account__label">password</span>
                <span class="account__entry"><?= $project->projectpassword()->esc() ?></span>
            </li>

            <!-- Redirect -->
            <li class="account__data account__data--redirect">
                <span class="account__label">redirect</span>
                <a href="<?= $project->url() ?>" class="account__entry" target="_blank">access dashboard</a>
            </li>
        </ul>
    </div>