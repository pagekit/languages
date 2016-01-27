<?php

/*
 * Run this script from the terminal to fetch new translations from Transifex.
 */

require __DIR__.'/vendor/autoload.php';

use Pagekit\Tools\Fetcher;

if (PHP_SAPI !== 'cli') {
    die("Error: Please execute from command line\n");
}

if(!file_exists('config.php')) {
    die("Error: config.php not found. Please refer to the README.\n");
}

$config = require_once __DIR__.'/config.php';

try {

    $username = $config['username'];
    $password = $config['password'];

    (new Fetcher($username, $password, __DIR__))->fetch();

} catch (\Exception $e) {
    die("ERROR: " . $e);
}
