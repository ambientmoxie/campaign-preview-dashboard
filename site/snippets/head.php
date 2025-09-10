<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= $site->title() ?></title>

    <meta name="robots" content="noindex, nofollow">

    <!-- Google fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=IBM+Plex+Mono:ital,wght@0,100;0,200;0,300;0,400;0,500;0,600;0,700;1,100;1,200;1,300;1,400;1,500;1,600;1,700&family=IBM+Plex+Sans:ital,wght@0,100..700;1,100..700&family=Inter:ital,opsz,wght@0,14..32,100..900;1,14..32,100..900&display=swap" rel="stylesheet">

    <!-- Assets files dynamic integration -->
    <?php if ($_ENV['VITE_ENV_MODE'] === "dev"): ?>
        <!-- Include Vite dev server for HMR -->
        <script type="module" src="http://localhost:3000/@vite/client"></script>
        <script type="module" src="http://localhost:3000/assets/js/main.js" defer></script>
    <?php elseif ($_ENV['VITE_ENV_MODE'] === "host"): ?>
        <!-- Include Vite dev server for HMR -->
        <script type="module" src="http://<?= $_ENV['VITE_LOCAL_IP'] ?>:3000/@vite/client"></script>
        <script type="module" src="http://<?= $_ENV['VITE_LOCAL_IP'] ?>:3000/assets/js/main.js" defer></script>
    <?php else: ?>
        <!-- Include the production build files -->
        <link rel="stylesheet" href="<?= AssetHelper::hashedAssetURL("css") ?>">
        <script src="<?= AssetHelper::hashedAssetURL("js") ?>" type="module" defer></script>
    <?php endif; ?>
</head>

<body class="theme-light">

    <!-- Start of page wrapper -->
    <div id="<?= $page->intendedTemplate()->name() ?>" class="page page--<?= $page->intendedTemplate()->name() ?>">