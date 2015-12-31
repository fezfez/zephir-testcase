<?php

namespace ZephirTestCase;

class ZeptTestCase implements \PHPUnit_Framework_Test, \PHPUnit_Framework_SelfDescribing
{
    /**
     * @var string
     */
    private $filename;

    /**
     * @var boolean
     */
    private $silent;

    /**
     * @var CodeRunner
     */
    private $codeRunner;

    /**
     * @var ZeptHydrator
     */
    private $zeptHydrator;

    /**
     * @var array
     */
    private $settings = array(
        'allow_url_fopen=1',
        'auto_append_file=',
        'auto_prepend_file=',
        'disable_functions=',
        'display_errors=1',
        'docref_root=',
        'docref_ext=.html',
        'error_append_string=',
        'error_prepend_string=',
        'error_reporting=-1',
        'html_errors=0',
        'log_errors=0',
        'magic_quotes_runtime=0',
        'output_handler=',
        'open_basedir=',
        'output_buffering=Off',
        'report_memleaks=0',
        'report_zend_debug=0',
        'safe_mode=0',
        'track_errors=1',
        'xdebug.default_enable=0'
    );

    /**
     * Constructs a test case with the given filename.
     *
     * @param  string                      $filename
     * @throws \PHPUnit_Framework_Exception
    */
    public function __construct($filename, $silent)
    {
        if (!is_string($filename)) {
            throw \PHPUnit_Util_InvalidArgumentHelper::factory(1, 'string');
        }

        if (!is_file($filename)) {
            throw new \PHPUnit_Framework_Exception(
                sprintf(
                    'File "%s" does not exist.',
                    $filename
                )
            );
        }

        $this->filename     = $filename;
        $this->silent       = $silent;
        $this->codeRunner   = CodeRunnerFactory::getInstance();
        $this->zeptHydrator = ZeptHydratorFactory::getInstance();
    }

    /**
     * Counts the number of test cases executed by run(TestResult result).
     *
     * @return int
     */
    public function count()
    {
        return 1;
    }

    /**
     * Runs a test and collects its result in a TestResult instance.
     *
     * @param  \PHPUnit_Framework_TestResult $result
     * @return \PHPUnit_Framework_TestResult
     */
    public function run(\PHPUnit_Framework_TestResult $result = null)
    {
        if ($result === null) {
            $result = new \PHPUnit_Framework_TestResult();
        }

        $zept = $this->zeptHydrator->zeptToDto($this->filename, $settings);

        $result->startTest($this);

        if ($zept->isSkip()) {
            $result->addFailure($this, new \PHPUnit_Framework_SkippedTestError($zept->getSkipMessage()), 0);
        } else {
            $result = $this->doRun($result, $zept);
        }

        return $result;
    }

    /**
     * @param \PHPUnit_Framework_TestResult $result
     * @param Zept $zept
     * @return \PHPUnit_Framework_TestResult
     */
    private function doRun(\PHPUnit_Framework_TestResult $result, Zept $zept)
    {
        $time = 0;
        \PHP_Timer::start();

        try {
            $jobResult = $this->codeRunner->run($zept->getZephirCode(), $zept->getPhpCode(), $this->silent);
            $time      = \PHP_Timer::stop();
            $assertion = $zept->getAssertion();

            \PHPUnit_Framework_Assert::$assertion(
                $this->cleanString($zept->getExpected()),
                $this->cleanString($jobResult['stdout'])
            );
        } catch (\Exception $exception) {
            $result->addError($this, $exception, $time);
        } catch (\Throwable $throwable) {
            $result->addError($this, $throwable, $time);
        }

        $result->endTest($this, $time);
        $result->flushListeners();

        return $result;
    }

    /**
     * @param string $string
     * @return string
     */
    private function cleanString($string)
    {
        return preg_replace('/\r\n/', "\n", trim($string));
    }

    /**
     * Returns the name of the test case.
     *
     * @return string
     */
    public function getName()
    {
        return $this->toString();
    }

    /**
     * Returns a string representation of the test case.
     *
     * @return string
     */
    public function toString()
    {
        return $this->filename;
    }
}
