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
 * Suite for .zept test cases.
 */
class ZeptTestSuite extends \PHPUnit_Framework_TestSuite
{
    /**
     * Constructs a new TestSuite for .zept test cases.
     *
     * @param  string                      $directory
     * @throws PHPUnit_Framework_Exception
     */
    public function __construct($directory, $silent)
    {
        if (is_string($directory) && is_dir($directory)) {
            $this->setName($directory);

            $facade = new \File_Iterator_Facade();
            $files  = $facade->getFilesAsArray($directory, '.zept');

            foreach ($files as $file) {
                $this->addTest(
                    new \ZephirTestCase\ZeptTestCase($file, $silent)
                );
            }
        } else {
            throw PHPUnit_Util_InvalidArgumentHelper::factory(1, 'directory name');
        }
    }
}