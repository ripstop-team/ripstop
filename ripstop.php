#!/usr/bin/env php
<?php

/**
 * If we're running from phar load the phar autoload file.
 */
$pharPath = \Phar::running(true);
if ($pharPath) {
    require_once "$pharPath/vendor/autoload.php";
} else {
    if (file_exists(__DIR__ . '/vendor/autoload.php')) {
        require_once __DIR__ . '/vendor/autoload.php';
    } elseif (file_exists(__DIR__ . '/../../autoload.php')) {
        require_once __DIR__ . '/../../autoload.php';
    }
}

$output = new \Symfony\Component\Console\Output\ConsoleOutput();

$commandClasses = [\Ripstop\Ripstop::class];
$statusCode     = \Robo\Robo::run(
    $_SERVER['argv'],
    $commandClasses,
    'Ripstop',
    '0.0.1',
    $output,
    'schlessera/ripstop'
);
exit($statusCode);
