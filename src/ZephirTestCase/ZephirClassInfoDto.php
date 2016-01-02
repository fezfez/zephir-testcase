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
 * Represent a zephir file
 */
class ZephirClassInfoDto
{
    /**
     * @var string
     */
    private $className;
    /**
     * @var string
     */
    private $namespace;

    /**
     * @param string $value
     */
    public function setClassName($value)
    {
        $this->className = $value;
    }

    /**
     * @param string $value
     */
    public function setNamespace($value)
    {
        $this->namespace = $value;
    }

    /**
     * @return string
     */
    public function getClassName()
    {
        return $this->className;
    }

    /**
     * @return string
     */
    public function getNamespace()
    {
        return $this->namespace;
    }

    /**
     * @return string
     */
    public function getFilePath()
    {
        return strtolower(str_replace('\\', '/', $this->namespace) . '/' . $this->className);
    }

    /**
     * @return string
     */
    public function getBaseNamespace()
    {
        return strpos($this->namespace, '\\') ? strstr($this->namespace, '\\', true) : $this->namespace;
    }

    /**
     * @return string
     */
    public function getBaseDir()
    {
        return strtolower($this->getBaseNamespace());
    }

    /**
     * @return string
     */
    public function getDir()
    {
        return strtolower(str_replace('\\', '/', $this->namespace) . '/');
    }

    /**
     * @return string
     */
    public function getExtensionName()
    {
        return $this->getBaseDir();
    }
}
