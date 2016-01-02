<?php

namespace ZephirTestCaseTest\ZephirExtensionBuilderFactory;

use ZephirTestCase\ZephirExtensionBuilderFactory;

class GetInstanceTest extends \PHPUnit_Framework_TestCase
{
    public function testInstanceIsOk()
    {
        $this->assertInstanceOf('ZephirTestCase\ZephirExtensionBuilder', ZephirExtensionBuilderFactory::getInstance());
    }
}
