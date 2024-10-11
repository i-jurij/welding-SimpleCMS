<?php

namespace App\Traits;

use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

trait Upload
{
    /**
     * @param $this->disk - from laravel filesystem
     */
    public string $disk = 'public';
    /**
     * @param $this->folder - path to directory into laravel disk
     */
    public string $folder = '';
    /**
     * @param $this->filename - name of file without extension
     */
    public string $filename = 'filename';
    public string $extension = '';

    /**
     * can be set:
     * $this->disk (from laravel filesystem),
     * $this->folder (path to directory into laravel disk),
     * $this->filename (without extension);
     * default $disk = 'public';
     * default $folder = '';
     * default $filename = random string (length 10 symbols).
     *
     * @return string or false
     */
    public function uploadFile(UploadedFile $file)
    {
        $name_of_file = !is_null($this->filename) ? $this->filename : Str::random(10);
        $folder = !is_null($this->folder) ? $this->folder : null;
        $disk = !is_null($this->disk) ? $this->disk : 'public';
        // Determine the file's extension based on the file's MIME type...
        $extension = !is_null($this->extension) ? $this->extension : $file->extension();

        return $file->storeAs(
            $folder,
            $name_of_file.'.'.$extension,
            $disk
        );
    }

    public function removeFile($path, $disk = 'public')
    {
        if (Storage::disk($disk)->delete($path)) {
            return true;
        } else {
            return false;
        }
    }
}
