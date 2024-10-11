<?php

namespace App\Http\Controllers\Moder;

use App\Http\Controllers\Controller;
use App\Models\Page;
use App\MyClasses\Upload\UploadFile;
use Illuminate\Http\Request;

class MapController extends Controller
{
    public function index()
    {
        return view('admin_manikur.moder_pages.map');
    }

    public function go(Request $request, Page $pages)
    {
        $res = '';

        if (isset($request->map_iframe) || !empty($request->file('map_img'))) {
            if (!empty($request->map_iframe)) {
                $valid = (
                    preg_match('<iframe\s*src="https\:\/\/www\.google\.com\/maps\/embed\?[^\"]+\"*\s*[^\>]+\>*\<\/iframe>', $request->map_iframe)
                    || str_contains($request->map_iframe, 'src="https://api-maps.yandex.ru')
                    || str_contains($request->map_iframe, 'src="https://enterprise.api-maps.yandex.ru')
                    || str_contains($request->map_iframe, 'src="http://www.openstreetmap.org/export/embed')
                );

                if ($valid) {
                    $map_iframe = htmlentities(mb_substr($request->map_iframe, 0, 5000));
                    $map = $pages->where('alias', 'map')->update(['content' => $map_iframe]);
                    if ($map > 0) {
                        $res .= 'Ссылка на карту обновлена.<br />';
                    } else {
                        $res .= 'ОШИБКА! Ссылку на карту не удалось обновить.<br />';
                    }
                } else {
                    $res .= 'Ссылка на карту НЕ изменена, потому что ресурс по ссылке недоступен. Проверьте правильность ссылки.<br />';
                }
            }

            if (!empty($_FILES['map_img']) && empty($_FILES['map_img']['error'])) {
                // PROCESSING $_FILES
                $load = new UploadFile();
                if ($load->issetData()) {
                    foreach ($load->files as $input => $input_array) {
                        foreach ($input_array as $key => $file) {
                            // SET the vars for class
                            if ($input === 'map_img') {
                                $load->dest_dir = storage_path('app'.DIRECTORY_SEPARATOR.'public'.DIRECTORY_SEPARATOR.'images'.DIRECTORY_SEPARATOR.'map');
                                $load->create_dir = true;
                                // $load->tmp_dir = storage_path('app'.DIRECTORY_SEPARATOR.'public'.DIRECTORY_SEPARATOR.'images'.DIRECTORY_SEPARATOR.'map'.DIRECTORY_SEPARATOR.'tmp');
                                $load->file_size = 3 * 1024 * 1024; // 3MB
                                $load->file_mimetype = ['image/jpeg', 'image/pjpeg', 'image/png', 'image/webp'];
                                $load->file_ext = ['.jpg', '.jpeg', '.png', '.webp'];
                                $load->new_file_name = 'map';
                                $load->replace_old_file = true;
                            }
                            // PROCESSING DATA
                            if ($load->execute($input_array, $key, $file)) {
                                if (!empty($load->message)) {
                                    $res .= $load->message;
                                }
                            } else {
                                if (!empty($load->error)) {
                                    $res .= $load->error.'<br>';
                                }
                                continue;
                            }
                            // image postprocessing
                            // some action

                            // CLEAR TMP FOLDER
                            /*
                            if (!$load->delFilesInDir($load->tmp_dir)) {
                                if (!empty($load->error)) {
                                    $res .= $load->error;
                                }
                            }
                            */
                        }
                    }
                }
            } else {
                $res .= 'Файл изображения не загружался.';
            }
        } else {
            $res .= 'Отправлена пустая форма.';
        }

        return view('admin_manikur.moder_pages.map', ['res' => $res]);
    }
}
