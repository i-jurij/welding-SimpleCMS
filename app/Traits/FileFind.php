<?php
/**
 * Find file by filename without extension.
 *
 * @param string $dir for find
 * @param string filename
 *
 * @return string or false
 */

namespace App\Traits;

trait FileFind
{
    use FilesInDir;

    public function migrationFind($dir, $page_alias): array
    {
        $res = [];
        // list all filenames in given path
        $allFiles = $this->filesindir($dir);
        // iterate through files and echo their content
        foreach ($allFiles as $file) {
            if (mb_strpos($file, $page_alias.'_table') || mb_strpos($file, $page_alias.'s_table')) {
                $res[] = realpath($dir.DIRECTORY_SEPARATOR.$file);
            }
        }

        return $res;
    }

    public static function find_by_filename($path, $filename)
    {
        if (is_readable($path)) {
            $files = scandir($path);
            if (!empty($files)) {
                foreach ($files as $k => $v) {
                    $fname = pathinfo($v, PATHINFO_FILENAME);
                    $only_name[$k] = $fname;
                }
                $name_key_name = array_search($filename, $only_name);
                if (!empty($name_key_name)) {
                    return $path.DIRECTORY_SEPARATOR.$files[$name_key_name];
                } else {
                    return false;
                }
            } else {
                return false;
            }
        } else {
            return false;
        }
    }
}
