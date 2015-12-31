<?php

namespace ZephirTestCase;

class ZeptHydrator
{
    /**
     * @var CodeRunner $codeRunner
     */
    private $codeRunner;

    /**
     * @param CodeRunner $codeRunner
     */
    public function __construct(CodeRunner $codeRunner)
    {
        $this->codeRunner = $codeRunner;
    }

    /**
     * @param string $fileName
     * @return \ZephirTestCase\Zept
     */
    public function zeptToDto($fileName, array $defaultIni = array())
    {
        $sections = $this->parseSection($fileName);

        if (!isset($sections['FILE']) || (!isset($sections['EXPECT']) && !isset($sections['EXPECTF']))) {
            throw new \PHPUnit_Framework_Exception('Invalid ZEPT file');
        }
        if (!isset($sections['USAGE'])) {
            throw new \PHPUnit_Framework_Exception('Invalid ZEPT file');
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
     * @param Zept $file
     * @param array $sections
     * @return \ZephirTestCase\Zept
     */
    private function hydrateSkip(Zept $zept, array $sections)
    {
        if (isset($sections['SKIPIF'])) {
            $jobResult = $this->codeRunner->runPhp($sections['SKIPIF'], $zept->getIni());
            if (!strncasecmp('skip', ltrim($jobResult['stdout']), 4)) {
                $message = '';

                if (preg_match('/^\s*skip\s*(.+)\s*/i', $jobResult['stdout'], $message)) {
                    $message = substr($message[1], 2);
                }

                $zept->setSkipMessage($message);
            }
        }

        return $zept;
    }

    /**
     * @param Zept $zept
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
     * @return array
     * @throws \PHPUnit_Framework_Exception
     */
    private function parseSection($fileName)
    {
        $sections = array();
        $section  = '';

        foreach (file($fileName) as $line) {
            if (preg_match('/^--([_A-Z]+)--/', $line, $result)) {
                $section            = $result[1];
                $sections[$section] = '';
                continue;
            } elseif (empty($section)) {
                throw new \PHPUnit_Framework_Exception('Invalid ZEPT file');
            }

            $sections[$section] .= $line;
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
            return preg_split('/\n|\r/', $sections['INI'], -1, PREG_SPLIT_NO_EMPTY);
        }

        return array();
    }

    /**
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
