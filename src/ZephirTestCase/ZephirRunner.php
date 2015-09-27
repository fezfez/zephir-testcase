<?php

namespace ZephirTestCase;

use Zephir\Commands\CommandBuild;
use Zephir\Config;
use Zephir\Logger as ZephirLogger;
use Zephir\Commands\CommandFullClean;
use Zephir\BaseBackend;

class ZephirRunner
{
    /**
     * @param string $zephirCode
     *
     * @throws \Exception
     */
    public function run($zephir, $phpcode)
    {
        $phpRunnder = \PHPUnit_Util_PHP::factory();
        return $phpRunnder->runJob($phpcode, array($this->buildZephirExtension($zephir)));
    }
    
    private function getBackend()
    {
        return 'Zephir\\Backends\\'.BaseBackend::getActiveBackend().'\\Backend';
    }
    
    private function getZephirCodeInfo($zephir)
    {
        preg_match('/class (\\w+)/', $zephir, $classesName);
        $className = $classesName[1];
        preg_match("/namespace ([A-z0-9\\\\]+)/", $zephir, $namespaces);
        $namespace = $namespaces[1];
        $baseNamespace = strpos($namespace, '\\') ? strstr($namespace, '\\') : $namespace;

        return array(
            'className' => $className,
            'namespace' => $namespace,
            'baseNamespace' => $baseNamespace,
            'filePath' => strtolower(str_replace('\\', '/', $namespace) . '/' . $className)
        );
    }
    
    private function buildZephirExtension($zephir)
    {
        $infos = $this->getZephirCodeInfo($zephir);
        $namespace = strtolower($infos['baseNamespace']);
        $this->delTree($namespace);
        mkdir($namespace);
        file_put_contents($infos['filePath'] . '.zep', $zephir);

        if (!defined('ZEPHIRPATH'))
            define('ZEPHIRPATH', realpath(__DIR__.'/../../vendor/phalcon/zephir').'/');

        $generateCommand = new CommandBuild();
        $cleanCommand = new CommandFullClean();

        try {
            $config = new Config();
            $config->set('namespace', strtolower($namespace));
            $config->set('silent', true);
        
            if (is_dir('ext')) {
                $cleanCommand->execute($config, new ZephirLogger($config));
            }
            $generateCommand->execute($config, new ZephirLogger($config));
        } catch (Exception $e) {
            $this->delTree($namespace);
            throw new \Exception(sprintf('Error on %s', $e->getMessage()));
        }
        
        $this->delTree($namespace);
        return 'extension='. ini_get('extension_dir') . '/' . $namespace .'.so';
    }
    

    private function delTree($dir)
    {
        if (is_dir($dir) === false) {
            return;
        }
        foreach(new \RecursiveIteratorIterator(new \RecursiveDirectoryIterator($dir, \FilesystemIterator::SKIP_DOTS), \RecursiveIteratorIterator::CHILD_FIRST) as $path) {
            $path->isDir() && !$path->isLink() ? rmdir($path->getPathname()) : unlink($path->getPathname());
        }
        
        rmdir($dir);
    }
}
