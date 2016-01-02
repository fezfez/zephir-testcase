<?php

namespace ZephirTestCaseTest\ZeptHydratorFactory;

use ZephirTestCase\ZeptHydratorFactory;

class GetInstanceTest extends \PHPUnit_Framework_TestCase
{
    public function testInstanceIsOk()
    {
        $this->assertInstanceOf('ZephirTestCase\ZeptHydrator', ZeptHydratorFactory::getInstance());
    }
}
