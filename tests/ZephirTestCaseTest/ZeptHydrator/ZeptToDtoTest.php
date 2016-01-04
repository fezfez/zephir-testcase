<?php

namespace ZephirTestCaseTest\ZeptHydrator;

use ZephirTestCase\ZephirExtensionBuilderFactory;
use ZephirTestCase\ZeptHydrator;

class ZeptToDtoTest extends \PHPUnit_Framework_TestCase
{
    public function failFile()
    {
        return array(
            array(
                ''
            ),
            array(
                "hi !\n--otherthing--"
            ),
            array(
                "--something--\n--otherthing--",
            ),
            array(
                "--something--\nhi !",
            ),
            array(
                "--FILE--\nhi !",
            ),
            array(
                "--FILE--",
            ),
            array(
                "--FILE--\n",
            ),
            array(
                "--FILE--\n-hi !'\n--USAGE--\n-hi !",
            ),
            array(
                "--FILE--\n-'hi !\n--EXPECT--\n-hi !",
            ),
            array(
                "--FILE--\n-'hi !\n--EXPECTF--\n-hi !",
            ),
            array(
                "--USAGE--\n-hi !\n--EXPECT--\nhi !",
            ),
            array(
                "--USAGE--\n'hi !\n'--EXPECTF--\nhi !",
            )
        );
    }

    public function okFile()
    {
        return array(
            array(
                "--FILE--\nhi zephir !\n--EXPECT--\nhi !\n--USAGE--\nhi php !",
                'assertEquals',
                'hi !',
                array(),
                'hi php !',
                null,
                'hi zephir !'
            ),
            array(
                "--FILE--\nhi zephir !\n--EXPECTF--\nhi !\n--USAGE--\nhi php !",
                'assertStringMatchesFormat',
                'hi !',
                array(),
                'hi php !',
                null,
                'hi zephir !'
            ),
            array(
                "--FILE--\nhi zephir !\n--EXPECTF--\nhi !\n--USAGE--\nhi php !\n--SKIPIF--\npme",
                'assertStringMatchesFormat',
                'hi !',
                array(),
                'hi php !',
                null,
                'hi zephir !'
            ),
            array(
                "--FILE--\nhi zephir !\n--EXPECTF--\nhi !\n--USAGE--\nhi php !\n--SKIPIF--\nskip: me",
                'assertStringMatchesFormat',
                'hi !',
                array(),
                'hi php !',
                'skip: me',
                'hi zephir !',
                'me'
            ),
            array(
                "--FILE--\nhi zephir !\n--EXPECTF--\nhi !\n--USAGE--\nhi php !\n--SKIPIF--\nskip: me",
                'assertStringMatchesFormat',
                'hi !',
                array(),
                'hi php !',
                'skip: me',
                'hi zephir !',
                'me'
            ),
            array(
                "--FILE--\nhi zephir !\n--EXPECTF--\nhi !\n--USAGE--\nhi php !\n--SKIPIF--\nskip: me\n--INI--\nhi ini !",
                'assertStringMatchesFormat',
                'hi !',
                array('hi ini !'),
                'hi php !',
                'skip: me',
                'hi zephir !',
                'me'
            ),
            array(
                "--FILE--\nhi zephir !\n--EXPECTF--\nhi !\n--USAGE--\nhi php !\n--SKIPIF--\nskip: me\n--INI--\nhi ini !\ntwo",
                'assertStringMatchesFormat',
                'hi !',
                array('hi ini !', 'two'),
                'hi php !',
                'skip: me',
                'hi zephir !',
                'me'
            )
        );
    }

    /**
     * @dataProvider failFile
     */
    public function testFail($file)
    {
        $codeRunnerMock = $this->getMockBuilder('ZephirTestCase\CodeRunner')->disableOriginalConstructor()->getMock();
        $fileWorkerMock = $this->getMock('ZephirTestCase\FileWorker');

        $fileWorkerMock->expects($this->once())->method('file')->with('myFile')->willReturn(explode("\n", $file));

        $sUT = new ZeptHydrator($codeRunnerMock, $fileWorkerMock);

        $this->setExpectedException('PHPUnit_Framework_Exception');

        $sUT->zeptToDto('myFile');
    }

    /**
     * @dataProvider okFile
     */
    public function testOk($file, $assertion, $expected, $ini, $phpCode, $skipMessage, $zephirCode, $skipAssert = null)
    {
        $codeRunnerMock = $this->getMockBuilder('ZephirTestCase\CodeRunner')->disableOriginalConstructor()->getMock();
        $fileWorkerMock = $this->getMock('ZephirTestCase\FileWorker');

        if ($skipMessage !== null) {
            $codeRunnerMock->expects($this->once())->method('runPhp')->with($skipMessage)->willReturn(array('stdout' => $skipMessage));
        }

        $fileWorkerMock->expects($this->once())->method('file')->with('myFile')->willReturn(explode("\n", $file));

        $sUT = new ZeptHydrator($codeRunnerMock, $fileWorkerMock);

        $dto = $sUT->zeptToDto('myFile');

        $this->assertEquals($assertion, $dto->getAssertion());
        $this->assertEquals($expected, $dto->getExpected());
        $this->assertEquals($ini, $dto->getIni());
        $this->assertEquals($phpCode, $dto->getPhpCode());
        $this->assertEquals($skipAssert, $dto->getSkipMessage());
        $this->assertEquals($zephirCode, $dto->getZephirCode());
        $this->assertEquals($skipAssert === null ? false : true, $dto->isSkip());
    }
}
