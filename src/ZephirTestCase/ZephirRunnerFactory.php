<?php

namespace ZephirTestCase;

use Zephir\Commands\CommandBuild;
use Zephir\Config;
use Zephir\Logger as ZephirLogger;
use Zephir\Commands\CommandFullClean;
use Zephir\BaseBackend;

class ZephirRunnerFactory
{
    /**
     * @return \ZephirTestCase\ZephirRunner
     */
    public static function getInstance()
    {
        return new ZephirRunner(
            ZephirExtensionBuilderFactory::getInstance(),
            \PHPUnit_Util_PHP::factory()
        );
    }
}
