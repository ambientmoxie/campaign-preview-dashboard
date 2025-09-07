<footer class="footer">

    <?php
    $session = kirby()->session();
    $role    = $session->get('role');

    if ($role && $role !== 'portfolio' || kirby()->user()) {
        snippet('button', ['label' => 'log out']);
    }
    ?>

    <div class="footer__item">
        To see how product images resize in responsive banners —
        <a class="footer__link footer__link--sandbox" href="<?= $site->url() ?>/sandbox" target="_blank">
            try our sandbox test banner.
        </a>
    </div>

    <div class="footer__item">
        Source code can be found on
        <a class="footer__link footer__link--github " href="<?= $site->url() ?>/sandbox" target="_blank">github</a>.
        Contact me at: <a href="mailto:contact@maximebenoit.work">contact@maximebenoit.work</a>
    </div>
</footer>