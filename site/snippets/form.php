<form class="form" action="<?= $page->url() ?>" method="POST">
    <label for="password">Enter password below:</label>
    <input
        class="form__input"
        type="password"
        id="password"
        name="password"
        required>

    <div class="form__buttons">
        <?= snippet('button', ['label' => "toggle"]); ?>
        <?= snippet('button', ['label' => "submit"]); ?>
    </div>
    <?php if (isset($error) && $error): ?>
        <div class="form__error">
            <?= esc($error) ?>
        </div>
    <?php endif; ?>
</form>