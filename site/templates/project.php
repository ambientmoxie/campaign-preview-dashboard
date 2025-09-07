<?php

/**
 * /**
 * Dashboard page
 * Displays the main dashboard for managing and previewing banners.
 * Provides selectors, view mode switch, and ratio options via PanelBuilder.
 */
?>


<?= snippet('head') ?>

<div id="dashboard" data-brand="<?= $page->id() ?>">
    <?= snippet('button', ['label' => "settings"]) ?>
    <aside id="panel">

        <?= snippet("header") ?>
        <div class="controls">
            <div class="controls__item controls__item--filters">
                <div class="divider divider--filters">
                    <h2 class="divider__label">Filters</h2>
                </div>
                <div class="selectors">
                    <?= PanelBuilder::createSelectors($page) ?>
                </div>
            </div>

            <div class="controls__item controls__item--manager">
                <div class="divider divider--filters">
                    <h2 class="divider__label">Ratio Manager</h2>
                </div>
                <div class="controls__group">
                    <div class="controls__item controls__item--modes">
                        <div class="view-mode-switch">
                            <button class="view-mode-switch__button" data-mode="multi">
                                <span class="view-mode-switch__box">
                                </span>
                                <span class="view-mode-switch__values">Multi-View</span>
                            </button>
                            <button class="view-mode-switch__button" data-mode="single">
                                <span class="view-mode-switch__box">
                                </span>
                                <span class="view-mode-switch__values">Single-View</span>
                            </button>
                            <?= snippet('button', ['label' => "toggler"]); ?>
                        </div>
                    </div>
                    <div class="controls__item controls__item--ratios">
                        <ul class="ratio-list">
                            <?= PanelBuilder::createRadioButtons($page) ?>
                        </ul>
                    </div>
                </div>
            </div>
        </div>

        <div class="panel-footer">
            <div class="divider divider--filters">
                <h2 class="divider__label">Footer</h2>
            </div>
            <?= snippet("footer") ?>
        </div>

    </aside>
    <main id="board">
        <?= PanelBuilder::createBanners($page) ?>
    </main>
</div>