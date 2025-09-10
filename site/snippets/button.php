<button class="btn btn--<?= str_replace(' ', '', $label) ?>" type="<?= r($label === "submit", "submit", "button") ?>">
    <div class="btn__label">
        <?= ucfirst($label) ?>
    </div>
    <?php
    // Remove whitespaces from input label
    $input  = $label;
    $output = str_replace(' ', '', $input);

    // Using processed ouput to echo specific svg icon
    switch ($output) {
        case "settings":
            echo asset("assets/svg/settings.svg")->read();
            break;
        case "logout":
            echo asset("assets/svg/logout.svg")->read();
            break;
        case "submit":
            echo asset("assets/svg/arrow.svg")->read();
            break;
        case "toggle":
            echo "<span class\"icon\">";
            echo asset("assets/svg/hide.svg")->read();
            echo asset("assets/svg/show.svg")->read();
            echo "<span>";
            break;
        default:
            break;
    }
    ?>
</button>