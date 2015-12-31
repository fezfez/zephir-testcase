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
        $dto->setClassName($this->findAttribut('/class (\\w+)/', "Unable to find class name", $zephir));
        $dto->setNamespace($this->findAttribut("/namespace ([A-z0-9\\\\]+)/", "Unable to find namespace", $zephir));

        return $dto;
    }

    /**
     * @param string $regex
     * @param string $errorMessage
     * @param string $zephir
     * @throws ZephirClassInfoException
     * @return string
     */
    private function findAttribut($regex, $errorMessage, $zephir)
    {
        preg_match($regex, $zephir, $info);

        if (empty($info[1])) {
            throw new ZephirClassInfoException($errorMessage);
        }

        return $info[1];
    }
}
