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
 * ZephirTestCase hydrator factory
 */
class ZeptHydratorFactory
{
    /**
     * Factory.
     *
     * @return \ZephirTestCase\ZeptHydrator
     */
    public static function getInstance()
    {
        return new ZeptHydrator(CodeRunnerFactory::getInstance(), new FileWorker());
    }
}
