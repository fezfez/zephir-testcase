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
 * Code runner factory
 */
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
