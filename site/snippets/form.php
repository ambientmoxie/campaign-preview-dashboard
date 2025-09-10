<form class="form" action="<?= $page->url() ?>" method="POST">
    <input
        class="form__input"
        type="password"
        id="password"
        name="password"
        placeholder="enter password"
        required>

    <div class="form__buttons">
        <button class="form__button form__button--toggler" type="button">Display Password</button>
        <button class="form__button form__button--submit" type="submit">Submit</button>
    </div>
    <?php if (isset($error) && $error): ?>
        <div class="form__error">
            <?= esc($error) ?>
        </div>
    <?php endif; ?>
</form>