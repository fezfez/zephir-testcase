<?php

namespace ZephirTestCase;

use Zephir\Commands\CommandBuild;
use Zephir\Config;
use Zephir\Logger as ZephirLogger;
use Zephir\Commands\CommandFullClean;
use Zephir\BaseBackend;

class ZephirExtensionBuilderFactory
{
    /**
     * @return \ZephirTestCase\ZephirExtensionBuilder
     */
    public static function getInstance()
    {
        return new ZephirExtensionBuilder(
            new ZephirClassInfo(),
            new FileWorker(),
            new CommandBuild(),
            new CommandFullClean()
        );
    }
}
