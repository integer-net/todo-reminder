#!/usr/bin/env php
<?php

$autoloadLocations = [
    getcwd() . '/vendor/autoload.php',
    getcwd() . '/../../autoload.php',
    __DIR__ . '/../vendor/autoload.php',
    __DIR__ . '/../../../autoload.php',
];

$loaded = false;
foreach ($autoloadLocations as $autoload) {
    if (is_file($autoload)) {
        require_once($autoload);
        $loaded = true;
    }
}

if (!$loaded) {
    fwrite(
        STDERR,
        'You must set up the project dependencies, run the following commands:' . PHP_EOL .
        'curl -s http://getcomposer.org/installer | php' . PHP_EOL .
        'php composer.phar install' . PHP_EOL
    );
    exit(1);
}

$app = new \IntegerNet\TodoBlame\Application();
$app->run();
