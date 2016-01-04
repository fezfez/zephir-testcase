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
use Zephir\Commands\CommandFullClean;

/**
 * Zephir extension builder
 */
class ZephirExtensionBuilderFactory
{
    /**
     * Factory.
     *
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
