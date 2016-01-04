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
