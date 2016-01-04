<?php

namespace ZephirTestCaseTest\ZephirClassInfo;

use ZephirTestCase\ZephirClassInfo;
use ZephirTestCase\ZephirClassInfoDto;

class GetZephirCodeInfoTest extends \PHPUnit_Framework_TestCase
{
    public function provideFail()
    {
        return array(
            array('namespace test', "Unable to find class name"),
            array('class test', "Unable to find namespace"),
            array('namespacetestclass test', "Unable to find namespace"),
            array("namespace\n test class test", "Unable to find namespace"),
            array("namespace test class \ntest", "Unable to find class name")
        );
    }

    public function provideOk()
    {
        return array(
            array("namespace namespacee \nclass teste", 'namespacee', 'teste')
        );
    }

    /**
     * @dataProvider provideFail
     */
    public function testFail($zephir, $message)
    {
        $sUT = new ZephirClassInfo();

        $this->setExpectedException('ZephirTestCase\ZephirClassInfoException', $message);

        $sUT->getZephirCodeInfo($zephir);
    }

    /**
     * @dataProvider provideOk
     */
    public function testOk($zephir, $namespace, $class)
    {
        $sUT = new ZephirClassInfo();

        $zephirClassInfoDto = $sUT->getZephirCodeInfo($zephir);

        $this->assertInstanceOf('ZephirTestCase\ZephirClassInfoDto', $zephirClassInfoDto);

        $expected = new ZephirClassInfoDto();
        $expected->setNamespace($namespace);
        $expected->setClassName($class);

        $this->assertEquals($expected, $zephirClassInfoDto);
    }
}
