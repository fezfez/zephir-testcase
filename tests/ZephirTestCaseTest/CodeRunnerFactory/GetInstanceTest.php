<?php

namespace ZephirTestCaseTest\CodeRunnerFactory;

use ZephirTestCase\CodeRunnerFactory;

class GetInstanceTest extends \PHPUnit_Framework_TestCase
{
    public function testInstanceIsOk()
    {
        $this->assertInstanceOf('ZephirTestCase\CodeRunner', CodeRunnerFactory::getInstance());
    }
}
