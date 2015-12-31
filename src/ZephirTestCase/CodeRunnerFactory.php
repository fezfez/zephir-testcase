<?php

namespace ZephirTestCase;

use Zephir\Commands\CommandBuild;
use Zephir\Config;
use Zephir\Logger as ZephirLogger;
use Zephir\Commands\CommandFullClean;
use Zephir\BaseBackend;

class CodeRunnerFactory
{
    /**
     * @return \ZephirTestCase\CodeRunner
     */
    public static function getInstance()
    {
        return new CodeRunner(
            ZephirExtensionBuilderFactory::getInstance(),
            \PHPUnit_Util_PHP::factory()
        );
    }
}
