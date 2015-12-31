<?php

namespace ZephirTestCase;

class Zept
{
    /**
     * @var array
     */
    private $ini;
    /**
     * @var string
     */
    private $assertion;
    /**
     * @var string
     */
    private $expected;
    /**
     * @var string
     */
    private $skipMessage;
    /**
     * @var string
     */
    private $zephirCode;
    /**
     * @var string
     */
    private $phpCode;

    /**
     * @param array $ini
     */
    public function __construct(array $ini = array())
    {
        $this->ini = $ini;
    }

    /**
     * @param array $value
     */
    public function addIni(array $value)
    {
        $this->ini = array_merge($this->ini, $value);
    }

    /**
     * @param string $value
     */
    public function setAssertion($value)
    {
        $this->assertion = $value;
    }

    /**
     * @param string $value
     */
    public function setExpected($value)
    {
        $this->expected = $value;
    }

    /**
     * @param string $value
     */
    public function setSkipMessage($value)
    {
        $this->skipMessage = $value;
    }

    /**
     * @param string $value
     */
    public function setZephirCode($value)
    {
        $this->zephirCode = $value;
    }

    /**
     * @param string $value
     */
    public function setPhpCode($value)
    {
        $this->phpCode = $value;
    }

    /**
     * @return array
     */
    public function getIni()
    {
        return $this->ini;
    }

    /**
     * @return string
     */
    public function getAssertion()
    {
        return $this->assertion;
    }

    /**
     * @return string
     */
    public function getExpected()
    {
        return $this->expected;
    }

    /**
     * @return string
     */
    public function getSkipMessage()
    {
        return $this->skipMessage;
    }

    /**
     * @return string
     */
    public function getZephirCode()
    {
        return $this->zephirCode;
    }

    /**
     * @return string
     */
    public function getPhpCode()
    {
        return $this->phpCode;
    }

    /**
     * @return bool
     */
    public function isSkip()
    {
        return $this->skipMessage !== null;
    }
}
