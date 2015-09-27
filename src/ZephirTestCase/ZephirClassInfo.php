<?php

namespace ZephirTestCase;

class ZephirClassInfo
{
    /**
     * @param string $zephir
     * @return array
     */
    public function getZephirCodeInfo($zephir)
    {
        $dto = new ZephirClassInfoDto();
        $dto->setClassName($this->getClass($zephir));
        $dto->setNamespace($this->getNamespace($zephir));

        return $dto;
    }

    /**
     * @param string $zephir
     * @throws ZephirClassInfoException
     * @return string
     */
    private function getClass($zephir)
    {
        preg_match('/class (\\w+)/', $zephir, $classesName);

        if (empty($classesName[1])) {
            throw new ZephirClassInfoException("Unable to find class name");
        }

        return $classesName[1];
    }

    /**
     * @param string $zephir
     * @throws ZephirClassInfoException
     * @return string
     */
    private function getNamespace($zephir)
    {
        preg_match("/namespace ([A-z0-9\\\\]+)/", $zephir, $namespaces);

        if (empty($namespaces[1])) {
            throw new ZephirClassInfoException("Unable to find namespace");
        }

        return $namespaces[1];
    }
}