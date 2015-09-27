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

$silent = false;

if (!empty($argv[2]) && $argv[2] === '--silent') {
    $silent = true;
}

$testSuite = new \ZephirTestCase\ZeptTestSuite(getcwd() . '/' . $argv[1], $silent);
$result    = \PHPUnit_TextUI_TestRunner::run($testSuite);

if (isset($result) && $result->wasSuccessful()) {
    $ret = PHPUnit_TextUI_TestRunner::SUCCESS_EXIT;
} elseif (!isset($result) || $result->errorCount() > 0 || $result->failureCount() > 0) {
    $ret = PHPUnit_TextUI_TestRunner::EXCEPTION_EXIT;
}

exit($ret);