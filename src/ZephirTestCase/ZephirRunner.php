<?php

namespace ZephirTestCase;

use Zephir\Commands\CommandBuild;
use Zephir\Config;
use Zephir\Logger as ZephirLogger;
use Zephir\Commands\CommandFullClean;
use Zephir\BaseBackend;

class ZephirRunner
{
    /**
     * @var ZephirExtensionBuilder
     */
    private $zephirExtensionBuilder;
    /**
     * @var \PHPUnit_Util_PHP
     */
    private $phpRunner;

    /**
     * @param ZephirExtensionBuilder $zephirExtensionBuilder
     * @param \PHPUnit_Util_PHP $phpRunner
     */
    public function __construct(ZephirExtensionBuilder $zephirExtensionBuilder, \PHPUnit_Util_PHP $phpRunner)
    {
        $this->zephirExtensionBuilder = $zephirExtensionBuilder;
        $this->phpRunner = $phpRunner;
    }

    /**
     * @param string $zephirCode
     *
     * @throws \Exception
     */
    public function run($zephir, $phpcode, $silent)
    {
        $extensionPath = $this->zephirExtensionBuilder->build($zephir, $silent);
        
        if (is_file($extensionPath) === false) {
            throw new \InvalidArgumentException(sprintf('Extension should be in "%s" but the file does not exist', $extensionPath));
        }

        return $this->phpRunner->runJob(
            $phpcode, 
            array('extension=' . $extensionPath)
        );
    }
}
