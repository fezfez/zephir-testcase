<?php

chdir(realpath('./'));

if (is_file(__DIR__.'/../vendor/autoload.php') === true) {
    include_once __DIR__.'/../vendor/autoload.php';
} elseif (is_file(__DIR__.'/../../../autoload.php') === true) {
    include_once __DIR__.'/../../../autoload.php';
} else {
    throw new RuntimeException('Error: vendor/autoload.php could not be found. Did you run php composer.phar install?');
}

if (empty($argv[1])) {
    throw new RuntimeException('Error: first parametter must be a directory');
} elseif (!is_dir(getcwd() . '/' . $argv[1])) {
    throw new RuntimeException(
        sprintf(
            'Error: first parametter must be a directory. given (%s)',
            getcwd() . '/' . $argv[1]
        )
    );
}

$testSuite = new \ZephirTestCase\ZeptTestSuite(getcwd() . '/' . $argv[1]);

\PHPUnit_TextUI_TestRunner::run($testSuite);