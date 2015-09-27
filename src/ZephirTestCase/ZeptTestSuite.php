<?php
namespace ZephirTestCase;


/**
 * Suite for .zept test cases.
 */
class ZeptTestSuite extends \PHPUnit_Framework_TestSuite
{
    /**
     * Constructs a new TestSuite for .phpt test cases.
     *
     * @param  string                      $directory
     * @throws PHPUnit_Framework_Exception
     */
    public function __construct($directory)
    {
        if (is_string($directory) && is_dir($directory)) {
            $this->setName($directory);

            $facade = new \File_Iterator_Facade;
            $files  = $facade->getFilesAsArray($directory, '.zept');

            foreach ($files as $file) {
                $this->addTest(
                    new \ZephirTestCase\ZeptTestCase($file)
                );
            }
        } else {
            throw PHPUnit_Util_InvalidArgumentHelper::factory(1, 'directory name');
        }
    }
}