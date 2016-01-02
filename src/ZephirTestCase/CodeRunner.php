<?php
/**
 * This file is part of the Zephir testcase package.
 *
 * (c) Stéphane Demonchaux <demonchaux.stephane@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace ZephirTestCase;

use Zephir\Commands\CommandBuild;
use Zephir\Config;
use Zephir\Logger as ZephirLogger;
use Zephir\Commands\CommandFullClean;
use Zephir\BaseBackend;

/**
 * Run php and zephir code
 */
class CodeRunner
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
     * Construct.
     *
     * @param ZephirExtensionBuilder $zephirExtensionBuilder
     * @param \PHPUnit_Util_PHP      $phpRunner
     */
    public function __construct(ZephirExtensionBuilder $zephirExtensionBuilder, \PHPUnit_Util_PHP $phpRunner)
    {
        $this->zephirExtensionBuilder = $zephirExtensionBuilder;
        $this->phpRunner              = $phpRunner;
    }

    /**
     * Compile zephir code, and test it with provided php code
     *
     * @param string $zephir
     * @param string $phpcode
     * @param bool   $silent
     * @throws \InvalidArgumentException
     */
    public function run($zephir, $phpcode, $silent)
    {
        $extensionPath = $this->zephirExtensionBuilder->build($zephir, $silent);

        if (is_file($extensionPath) === false) {
            throw new \InvalidArgumentException(
                sprintf('Extension should be in "%s" but the file does not exist', $extensionPath)
            );
        }

        return $this->runPhp($phpcode, array('extension=' . $extensionPath, 'error_reporting=-1', 'display_errors=1'));
    }

    /**
     * Run php code
     *
     * @param string $phpcode
     * @param array  $settings
     * @return array
     */
    public function runPhp($phpcode, array $settings)
    {
        return $this->phpRunner->runJob($phpcode, $settings);
    }
}
