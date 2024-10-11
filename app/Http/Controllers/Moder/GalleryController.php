<?php

namespace App\Http\Controllers\Moder;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use Intervention\Image\Facades\Image;

class GalleryController extends Controller
{
    use \App\Traits\DeleteFile;
    public string $disk;
    /**
     * @param $this->folder - path to directory into laravel disk
     */
    public string $folder;
    /**
     * @param $this->filename - name of file without extension
     */
    public string $filename;
    public string $extension;

    public function index()
    {
        return view('admin_manikur.moder_pages.gallery');
    }

    public function go(Request $request)
    {
        // name point for menu navigation
        $res = '';
        if ($request->isMethod('post') && (!empty($request['gallery_add']) || !empty($request['gallery_del']) || !empty($request['photo_link']))) {
            // PROCESSING $_FILES
            $this->disk = 'public';
            $this->folder = 'images'.DIRECTORY_SEPARATOR.'gallery';

            if ($request->hasfile('gallery_add')) {
                $rules = [
                    'gallery_add.*' => 'mimes:jpg,jpeg,png,webp|max:3000',
                ];
                $messages = [
                    'gallery_add.*.mimes' => 'Only jpg,jpeg,png and webp images are allowed',
                    'gallery_add.*.max' => 'Sorry! Maximum allowed size for an image is 3MB',
                ];

                $validator = Validator::make($request->all(), $rules, $messages);

                if ($validator->fails()) {
                    /*
                    return redirect('admin_manikur.moder_pages.gallery')->withErrors($validator)->withInput();
                    */
                    $res .= $validator->messages().'<br>';
                }

                foreach ($request->file('gallery_add') as $k => $image) {
                    if ($image->isValid()) {
                        $this->filename = pathinfo(mb_strtolower(sanitize(translit_to_lat($image->getClientOriginalName()))), PATHINFO_FILENAME);
                        $this->extension = $image->extension();

                        $img = Image::make($image);
                        $img->insert(Storage::get('public'.DIRECTORY_SEPARATOR.'images'.DIRECTORY_SEPARATOR.'watermark.png'), 'bottom-right');
                        if (Storage::put('public'.DIRECTORY_SEPARATOR.'images'.DIRECTORY_SEPARATOR.'gallery'.DIRECTORY_SEPARATOR.$this->filename.'.'.$this->extension, $img->encode())) {
                            $res .= 'Image '.$image->getClientOriginalName().' was uploaded.<br>';
                        } else {
                            $res .= 'Image '.$image->getClientOriginalName().' have been NOT uploaded.<br>';
                        }
                    } else {
                        $res .= 'ERROR! Image '.$image->getClientOriginalName().' is large as 3MB.<br>';
                    }
                }
            }

            if (!empty($request['gallery_del'])) {
                foreach ($request['gallery_del'] as $value) {
                    $res .= self::deleteFile($value).'<br />';
                }
            }
            if (!empty($request['photo_link'])) {
                $photo_link = htmlentities($request['photo_link']);
                $file = realpath(app_path().DIRECTORY_SEPARATOR.'..'.DIRECTORY_SEPARATOR.'resources'.DIRECTORY_SEPARATOR.'views'.DIRECTORY_SEPARATOR.'client_manikur'.DIRECTORY_SEPARATOR.'client_pages'.DIRECTORY_SEPARATOR.'gallery.blade.php');

                if (getResponseCode($photo_link)) {
                    $new_string = '$photo_link = "'.my_sanitize_url($request['photo_link']).'";';
                    if (!empty($new_string) && replace_string($file, $new_string, 1)) {
                        $res .= 'Ссылка на фотоальбом изменена на '.$photo_link.'<br />';
                    } else {
                        $res .= 'Ссылка на фотольбом НЕ изменена. Проверьте права доступа к файлу resources/views/client_manikur/client_pages/gallery.blade.php.<br />';
                    }
                } else {
                    $res .= 'Введена неправильная ссылка на фотоальбом.<br />';
                }
            }
        } else {
            $res .= 'Отправлена пустая форма.<br />';
        }

        return view('admin_manikur.moder_pages.gallery', ['res' => $res]);
    }
}
