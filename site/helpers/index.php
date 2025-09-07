<?php

// Loads environment variables from a .env file into process.env.
// To use a variable from the .env file in a PHP file, use $_ENV["VAR"].
require_once __DIR__ . '/../../vendor/autoload.php';
$dotenv = Dotenv\Dotenv::createImmutable(__DIR__ . "/../..");
$dotenv->load();

require_once __DIR__ . '/lib/config-helper.php';
require_once __DIR__ . '/lib/asset-helper.php';
require_once __DIR__ . '/lib/panel-builder.php';
