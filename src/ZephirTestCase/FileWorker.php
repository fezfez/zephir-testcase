<?php

namespace ZephirTestCase;

class FileWorker
{
    public function rmdirRecursive($dir)
    {
        if (is_dir($dir) === false) {
            return;
        }

        foreach(new \RecursiveIteratorIterator(new \RecursiveDirectoryIterator($dir, \FilesystemIterator::SKIP_DOTS), \RecursiveIteratorIterator::CHILD_FIRST) as $path) {
            $path->isDir() && !$path->isLink() ? rmdir($path->getPathname()) : unlink($path->getPathname());
        }

        rmdir($dir);
    }

    public function writeZephirFile(ZephirClassInfoDto $dto, $code)
    {
        $this->rmdirRecursive($dto->getBaseDir());
        mkdir($dto->getBaseDir());
        file_put_contents($dto->getFilePath() . '.zep', $code);
    }
}
