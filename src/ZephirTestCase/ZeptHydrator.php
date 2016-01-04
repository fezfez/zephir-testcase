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
 * Convert ZephirTestCase file into dto
 */
class ZeptHydrator
{
    /**
     * @var CodeRunner
     */
    private $codeRunner;
    /**
     * @var FileWorker
     */
    private $fileWorker;

    /**
     * Construct.
     *
     * @param CodeRunner $codeRunner
     * @param FileWorker $fileWorker
     */
    public function __construct(CodeRunner $codeRunner, FileWorker $fileWorker)
    {
        $this->codeRunner = $codeRunner;
        $this->fileWorker = $fileWorker;
    }

    /**
     * Convert zept file into dto
     *
     * @param string $fileName
     * @return \ZephirTestCase\Zept
     */
    public function zeptToDto($fileName, array $defaultIni = array())
    {
        $sections = $this->parse($fileName);

        if (!isset($sections['EXPECT']) && !isset($sections['EXPECTF'])) {
            throw new \PHPUnit_Framework_Exception('Invalid ZEPT file, need a expectation (EXPECT or EXPECTF) section');
        }
        if (!isset($sections['FILE']) || !isset($sections['USAGE'])) {
            throw new \PHPUnit_Framework_Exception('Invalid ZEPT file, need FILE and USAGE section');
        }

        $file = new Zept($defaultIni);

        $file->addIni($this->parseIniSection($sections));
        $file = $this->parseAssertion($file, $sections);
        $file = $this->hydrateSkip($file, $sections);
        $file->setZephirCode($this->render($sections['FILE'], $fileName));
        $file->setPhpCode($this->render($sections['USAGE'], $fileName));

        return $file;
    }

    /**
     * Parse the skip section
     * - if skip
     * - run the code
     * - return the skip reason
     *
     * @param Zept  $zept
     * @param array $sections
     * @return \ZephirTestCase\Zept
     */
    private function hydrateSkip(Zept $zept, array $sections)
    {
        if (isset($sections['SKIPIF'])) {
            $jobResult = $this->codeRunner->runPhp($sections['SKIPIF'], $zept->getIni());
            if (!strncasecmp('skip', ltrim($jobResult['stdout']), 4)) {
                $message = '';

                if (preg_match('/^\s*skip\s*(.+)\s*/i', $jobResult['stdout'], $rawMessage)) {
                    $message = substr($rawMessage[1], 2);
                }

                $zept->setSkipMessage($message);
            }
        }

        return $zept;
    }

    /**
     * Parse the assertion
     *
     * @param Zept  $zept
     * @param array $sections
     * @return \ZephirTestCase\Zept
     */
    private function parseAssertion(Zept $zept, array $sections)
    {
        if (isset($sections['EXPECT'])) {
            $zept->setAssertion('assertEquals');
            $zept->setExpected($sections['EXPECT']);

            return $zept;
        }

        $zept->setAssertion('assertStringMatchesFormat');
        $zept->setExpected($sections['EXPECTF']);

        return $zept;
    }

    /**
     * Parse file into section
     *
     * @return array
     * @throws \PHPUnit_Framework_Exception
     */
    private function parse($fileName)
    {
        $sections = array();
        $section  = '';

        foreach ($this->fileWorker->file($fileName) as $line) {
            if (preg_match('/^--([_A-Z]+)--/', $line, $result)) {
                $section            = $result[1];
                $sections[$section] = '';
                continue;
            } elseif (empty($section)) {
                throw new \PHPUnit_Framework_Exception('Invalid ZEPT file');
            }

            $sections[$section] .= ($section === 'INI') ? $line . "\n" : $line;
        }

        return $sections;
    }

    /**
     * Parse --INI-- section key value pairs and return as array.
     *
     * @param string
     * @return array
     */
    private function parseIniSection(array $sections)
    {
        if (isset($sections['INI'])) {
            return preg_split('/\n|\r/', $sections['INI'], null, PREG_SPLIT_NO_EMPTY);
        }

        return array();
    }

    /**
     * Replace magic const by filename
     *
     * @param string $code
     * @param string $fileName
     * @return string
     */
    private function render($code, $fileName)
    {
        return str_replace(
            array(
                '__DIR__',
                '__FILE__'
            ),
            array(
                "'" . dirname($fileName) . "'",
                "'" . $fileName . "'"
            ),
            $code
        );
    }
}
