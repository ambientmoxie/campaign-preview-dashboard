<?php
$message = $message ?? 'No campaigns are currently available. Please check back later.';
?>
<div class="feedback-card">
    <div class="feedback-card__wrapper">
        <p class="feedback-card__label">
            *** EMPTY ***
        </p>---
        <p class="feedback-card__description">
            <?= $message ?>
        </p>---
    </div>
</div>