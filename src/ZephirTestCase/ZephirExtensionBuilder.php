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

use Zephir\Commands\CommandBuild;
use Zephir\Config;
use Zephir\Logger as ZephirLogger;
use Zephir\Commands\CommandFullClean;
use Zephir\BaseBackend;

/**
 * Can build zephir extension
 */
class ZephirExtensionBuilder
{
    /**
     * @var ZephirClassInfo
     */
    private $zephirClassInfo;
    /**
     * @var FileWorker
     */
    private $fileWorker;
    /**
     * @var CommandBuild
     */
    private $commandBuild;
    /**
     * @var CommandFullClean
     */
    private $commandFullClean;

    /**
     * Construct.
     *
     * @param ZephirClassInfo  $zephirClassInfo
     * @param FileWorker       $fileWorker
     * @param CommandBuild     $commandBuild
     * @param CommandFullClean $commandFullClean
     */
    public function __construct(
        ZephirClassInfo $zephirClassInfo,
        FileWorker $fileWorker,
        CommandBuild $commandBuild,
        CommandFullClean $commandFullClean
    ) {
        $this->zephirClassInfo  = $zephirClassInfo;
        $this->fileWorker       = $fileWorker;
        $this->commandBuild     = $commandBuild;
        $this->commandFullClean = $commandFullClean;
    }

    /**
     * Build a zephir extension and return the extension path
     *
     * @param string  $zephir
     * @param boolean $silent
     * @throws \Exception
     * @return string
     */
    public function build($zephir, $silent)
    {
        $dto = $this->zephirClassInfo->getZephirCodeInfo($zephir);
        $this->fileWorker->writeZephirFile($dto, $zephir);

        $this->defineZephirHome();

        try {
            $config = new Config();
            $config->set('namespace', $dto->getExtensionName());
            $config->set('silent', $silent);

            if (is_dir('ext')) {
                $this->commandFullClean->execute($config, new ZephirLogger($config));
            }
            $this->commandBuild->execute($config, new ZephirLogger($config));
        } catch (\Exception $e) {
            $this->fileWorker->rmdirRecursive($dto->getBaseDir());
            throw new \Exception(sprintf('Error on %s', $e->getMessage()));
        }

        return 'ext/modules/' . $dto->getExtensionName() .'.so';
    }

    /**
     * @param array $data
     * @param callable $callback
     * @throws \Exception
     * @return mixed
     */
    private function arrayFoundValidExpression(array $data, callable $callback)
    {
        foreach ($data as $value) {
            if ($callback($value) !== false) {
                return $value;
            }
        }

        throw new \Exception('No valid expression found');
    }

    /**
     * Define the zephir home
     *
     * @throws \Exception
     * @return void
     */
    private function defineZephirHome()
    {
        if (!defined('ZEPHIRPATH')) {
            try {
                $zephirHome = $this->arrayFoundValidExpression(
                    array(
                        __DIR__.'/../../vendor/phalcon/zephir/', // as both vendor
                        __DIR__.'/../../../../phalcon/zephir/', // as unit test
                        __DIR__.'/../../../../../bin/zephir/' // as zephir
                    ),
                    function ($value) {
                        return is_dir($value);
                    }
                );
            } catch (\Exception $e) {
                throw new \Exception('Zephir home not found');
            }

            define('ZEPHIRPATH', $zephirHome);
        }
    }
}
