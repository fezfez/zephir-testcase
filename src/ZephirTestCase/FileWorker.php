<?php

namespace ZephirTestCase;

class FileWorker
{
    /**
     * @param string $dir
     * @return void
     */
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

    /**
     * @param ZephirClassInfoDto $dto
     * @param string $code
     * @throws \Exception
     * @return void
     */
    public function writeZephirFile(ZephirClassInfoDto $dto, $code)
    {
        $this->rmdirRecursive($dto->getBaseDir());
        if (@mkdir(getcwd() . '/' . $dto->getDir(), 0777, true) === false) {
            throw new \Exception('could not create dir ' . getcwd() . '/' . $dto->getDir());
        }
        file_put_contents($dto->getFilePath() . '.zep', $code);
    }
}
