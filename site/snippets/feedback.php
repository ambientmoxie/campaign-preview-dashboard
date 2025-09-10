<?php
$message = $message ?? 'No campaigns are currently available. <br/> Please check back later.';
?>
<div class="feedback">
    <h2 class="feedback__message">
        <?= $message ?>
    </h2>
</div>