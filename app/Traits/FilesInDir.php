<?php
namespace App\Traits;

trait FilesInDir
{
    /**
     * @param string $dir - dir for scan
     * @param string $ext - extension of files eg 'png' or 'png, webp, jpg'
     * @return array basename  of files or false
     */
    function filesindir($dir, $ext = '') : array {
        if (file_exists($dir) && is_dir($dir) && is_readable($dir)) {
            foreach (new \DirectoryIterator($dir) as $fileInfo) {
            if($fileInfo->isDot()) continue;
            if (empty($ext)) {
                $files[] = $fileInfo->getBasename();
            } else {
                $arr = explode(',', $ext);
                foreach ($arr as $value) {
                    $extt = mb_strtolower(ltrim(trim($value), '.'));
                    if ($extt === $fileInfo->getExtension() ) {
                        $files[] = $fileInfo->getBasename();
                    }
                }
            }
            }
        }
        return $files;
    }
}
