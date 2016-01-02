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

class ZephirClassInfo
{
    /**
     * Return the class name and the namespace of a zephir code
     *
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
     * Run regex on string, run the the first element
     * or throw exception with provided message
     *
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
