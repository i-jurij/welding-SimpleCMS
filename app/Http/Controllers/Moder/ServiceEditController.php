<?php

namespace App\Http\Controllers\Moder;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\Service;
use App\Models\ServiceCategory;
use App\MyClasses\Upload\UploadFile;
use Illuminate\Http\Request;

class ServiceEditController extends Controller
{
    use \App\Traits\DeleteFile;
    use \App\Traits\Upload;
    public array $data;

    public string $page_id;
    public string $cat_id;
    public string $serv_id;

    public string $page_name;
    public string $cat_name;
    public string $serv_name;

    public string $page_img;
    public string $cat_img;
    public string $serv_img;

    public function go(Request $request)
    {
        // if (!empty($_POST['page_for_edit']) && !empty($_POST['action'])) {
        if (!empty($request->input('page_for_edit')) && !empty($request->input('action'))) {
            $ar = explode('#', test_input($request->input('page_for_edit')));
            $this->data['page_id'] = $ar[0];
            $this->data['page_title'] = $ar[1];
            $this->data['action'] = test_input($_POST['action']);
            if ($this->data['action'] === 'cats_add') {
                $this->data['name'] = 'Добавить категории';
            } elseif ($this->data['action'] === 'serv_add') {
                $this->data['name'] = 'Добавить услуги';
            } elseif ($this->data['action'] === 'cats_del') {
                $this->data['name'] = 'Удалить категории';
            } elseif ($this->data['action'] === 'serv_del') {
                $this->data['name'] = 'Удалить услуги';
            }

            $this->data['page_cats'] = ServiceCategory::where('page_id', $this->data['page_id'])
                ->select('id', 'page_id', 'name', 'image')
                ->get();

            $this->data['page_cats_serv'] = Service::where('page_id', $this->data['page_id'])
                ->whereNotNull('category_id')
                ->orWhere('category_id', '<>', '')
                ->select('id', 'page_id', 'category_id', 'name')
                ->get();

            $this->data['page_serv'] = Service::where('page_id', $this->data['page_id'])
            ->whereNull('category_id')
            ->orWhere('category_id', '=', '')
            ->select('id', 'page_id', 'name', 'image')
            ->get();
        } elseif (!empty($request->input('cats_name'))) { // CAT ADD
            $this->data['name'] = 'Добавить категории';

            if (!empty($request->input('page_id'))) {
                $ar = explode('#', test_input($request->input('page_id')));
                $page_id = $ar[0];
                $page_title = $ar[1];
                $this->data['res'][] = 'Страница "'.$page_title.'":';
                $post = array_map('test_input', $request->input('cats_name'));
                $post_desc = $request->input('cats_desc');
                // PROCESSING $_FILES
                $load = new UploadFile();
                if ($load->issetData()) {
                    foreach ($load->files as $input => $input_array) {
                        foreach ($input_array as $key => $file) {
                            // SET the vars for class
                            if ($input === 'cats_img') {
                                $load->defaultVars();
                                $load->create_dir = true; // let create dest dir if not exists
                                $load->dest_dir = storage_path('app'.DIRECTORY_SEPARATOR.'public'.DIRECTORY_SEPARATOR.'images'.DIRECTORY_SEPARATOR.'categories'.DIRECTORY_SEPARATOR.$page_id);
                                // $load->tmp_dir = public_path('tmp');
                                $load->file_size = 3 * 1024 * 1024; // 3MB
                                $load->file_mimetype = ['image/jpeg', 'image/pjpeg', 'image/png', 'image/webp'];
                                $load->file_ext = ['.jpg', '.jpeg', '.png', '.webp'];
                                $load->new_file_name = $post[$key];
                                $load->replace_old_file = true;
                            }
                            // PROCESSING DATA
                            if ($load->execute($input_array, $key, $file)) {
                                if (!empty($load->message)) {
                                    $this->data['res'][] = $load->message;
                                }
                                // sql insert
                                $cat_name = $post[$key];
                                $cat_descr = (!empty($post_desc[$key])) ? $post_desc[$key] : '';
                                $cat_img = 'categories'.DIRECTORY_SEPARATOR.$page_id.DIRECTORY_SEPARATOR.$load->new_file_name;
                                $iscat = ServiceCategory::where('page_id', $page_id)
                                    ->where(function ($query) use ($cat_name, $cat_img) {
                                        $query->where('name', $cat_name)
                                            ->orWhere('image', $cat_img);
                                    })
                                    ->first();

                                if ($iscat) {
                                    $this->data['res'][] = 'Категория с таким именем "'.$cat_name.'" уже существует в базе.';
                                } else {
                                    $insert = [
                                        'page_id' => $page_id,
                                        'image' => $cat_img,
                                        'name' => $cat_name,
                                        'description' => $cat_descr,
                                    ];

                                    if (ServiceCategory::insert($insert)) {
                                        $this->data['res'][] = 'Данные категории "'.$cat_name.'" внесены в базу.';
                                    } else {
                                        $this->data['res'][] = 'Ошибка! Данные категории "'.$cat_name.'" НЕ внесены в базу.';
                                    }
                                }
                            } else {
                                if (!empty($load->error)) {
                                    $this->data['res'][] = $load->error;
                                }
                                continue;
                            }
                            // CLEAR TMP FOLDER
                            if (!$load->delFilesInDir($load->tmp_dir)) {
                                if (!empty($load->error)) {
                                    $this->data['res'][] = $load->error;
                                }
                            }
                        }
                        $this->data['res'][] = '';
                    }
                } else {
                    $this->data['res'][] = 'Фото для загрузки не были выбраны.';
                }
            } else {
                $this->data['res'] = 'Не выбрана страница для редактирования.';
            }
        } elseif (!empty($request->serv_name)) {
            // SERV ADD
            $this->services_add($request);
        } elseif (!empty($request->cat_del)) {
            // CAT DEL
            $this->data['name'] = 'Удалить категории';
            $this->data['res'] = [];
            foreach ($request->cat_del as $value) {
                if (!empty($value)) {
                    $this->get_cats_data($value);
                    // del serv img
                    Service::where('category_id', $this->cat_id)->select('image')->get()->each(function ($item) {
                        if (!empty($item->image)) {
                            $this->img_del($item->image);
                        }
                    });
                    // del cat img
                    $this->img_del($this->cat_img);
                    // sql del services of category
                    $this->cat_sql_del();
                    $this->del_empty_cat_dir();
                } else {
                    $this->data['res'][] = 'Пустые входные данные.';
                }
                $this->data['res'][] = '';
            }
            $this->del_empty_pageimg_dir();
        } elseif (!empty($request->serv_del)) { // SERV DEL
            $this->data['name'] = 'Удаление услуг';
            $this->data['res'] = [];
            foreach ($request->serv_del as $serv) {
                $this->get_serv4page_data($serv);
                $this->img_del($this->serv_img);
                $this->services_sql_del();
                $this->data['res'][] = '';
            }
            $this->del_empty_pageimg_dir();
        } else {
            $this->data['name'] = 'Нет данных';
            $this->data['res'] = 'Пустые входные данные.';
        }

        return $this->data;
    }

    protected function services_add($request)
    {
        $this->data['name'] = 'Добавить услуги';
        if (!empty($request->page_id)) {
            $ar = explode('#', $request->page_id);
            $page_id = $ar[0];
            $page_title = $ar[1];
            $this->data['res'][] = 'Страница "'.$page_title.'":';

            if (!empty($request->cat_id)) {
                $cat_ar = explode('#', $request->cat_id);
                $cat_id = $cat_ar[0];
                $cat_title = $cat_ar[1];
                $this->data['res'][] = 'Категория "'.$cat_title.'":';
            }
            // POST processing
            $serv_name = $request->serv_name;
            $serv_desc = $request->serv_desc;
            $price = $request->price;
            $duration = $request->duration;
            // services for category
            if (!empty($cat_id)) {
                // upload serv image
                $this->disk = 'public';
                $this->folder = 'images'.DIRECTORY_SEPARATOR.'services'.DIRECTORY_SEPARATOR.$page_id.DIRECTORY_SEPARATOR.$cat_id;

                if ($request->hasfile('serv_img')) {
                    $request->validate([
                        'serv_img.*' => 'mimes:jpg,png,webp|max:3145728',
                    ]);

                    foreach ($request->file('serv_img') as $k => $image) {
                        $serv = $serv_name[$k];
                        $descr = (!empty($serv_desc[$k])) ? $serv_desc[$k] : '';
                        $this->filename = pathinfo(mb_strtolower(sanitize(translit_to_lat($serv))), PATHINFO_FILENAME);
                        $img = mb_str_replace('images/', '', $this->uploadFile($image));
                        if ((bool) $img) {
                            $this->data['res'][] = 'Image of service '.$serv.' was uploaded.';
                        } else {
                            $this->data['res'][] = 'Image of service '.$serv.' have been NOT uploaded.';
                        }

                        $re = "/^-?(?:\d+|\d*\.\d+|\d*\,\d+)$/";
                        if (preg_match($re, $price[$k])) {
                            $price_end = $price[$k];
                        } else {
                            $price_end = '';
                        }

                        $isserv = Service::where(['name' => $serv, 'category_id' => $cat_id, 'page_id' => $page_id])->first();
                        if ((bool) $isserv) {
                            $this->data['res'][] = 'Услуга с таким именем "'.$serv.'" уже существует в базе.';
                        } else {
                            $insert = [
                                'page_id' => $page_id,
                                'category_id' => $cat_id,
                                'name' => $serv,
                                'image' => (!empty($img)) ? $img : '',
                                'price' => $price_end,
                                'duration' => $duration[$k],
                                'description' => $descr,
                            ];

                            try {
                                Service::insert($insert);
                                $this->data['res'][] = 'Данные услуги "'.$serv.'" внесены в базу.';
                            } catch (\Throwable $th) {
                                $this->data['res'][] = 'Ошибка! Данные услуги "'.$serv.'" НЕ внесены в базу.<br />'.$th;
                            }
                        }
                        $this->data['res'][] = '';
                    }
                }
            } else { // service for page
                // PROCESSING $_FILES
                $load = new UploadFile();
                if ($load->issetData()) {
                    foreach ($load->files as $input => $input_array) {
                        foreach ($input_array as $key => $file) {
                            // SET the vars for class
                            if ($input === 'serv_img') {
                                $load->defaultVars();
                                $load->create_dir = true; // let create dest dir if not exists
                                $load->dest_dir = storage_path('app'.DIRECTORY_SEPARATOR.'public'.DIRECTORY_SEPARATOR.'images'.DIRECTORY_SEPARATOR.'services'.DIRECTORY_SEPARATOR.$page_id);
                                // $load->tmp_dir = public_path('tmp');
                                $load->file_size = 3 * 1024 * 1024; // 3MB
                                $load->file_mimetype = ['image/jpeg', 'image/pjpeg', 'image/png', 'image/webp'];
                                $load->file_ext = ['.jpg', '.jpeg', '.png', '.webp'];
                                $load->new_file_name = $serv_name[$key];
                                $load->replace_old_file = true;
                            }
                            // PROCESSING DATA
                            if ($load->execute($input_array, $key, $file)) {
                                if (!empty($load->message)) {
                                    $this->data['res'][] = $load->message;
                                }

                                // sql data insert
                                $re = "/^-?(?:\d+|\d*\.\d+|\d*\,\d+)$/";
                                if (preg_match($re, $price[$key])) {
                                    $price_end = $price[$key];
                                } else {
                                    $price_end = '';
                                }

                                $isserv = Service::where('name', $serv_name[$key])
                                    ->where('page_id', $page_id)
                                    ->first();

                                if ((bool) $isserv) {
                                    $this->data['res'][] = 'Услуга с таким именем "'.$serv_name[$key].'" уже существует в базе.<br />';
                                } else {
                                    $insert = [
                                        'page_id' => $page_id,
                                        'name' => $serv_name[$key],
                                        'image' => 'services'.DIRECTORY_SEPARATOR.$page_id.DIRECTORY_SEPARATOR.$load->new_file_name,
                                        'description' => $serv_desc[$key],
                                        'price' => $price_end,
                                        'duration' => $duration[$key],
                                    ];

                                    try {
                                        Service::insert($insert);
                                        $this->data['res'][] = 'Данные услуги "'.$serv_name[$key].'" внесены в базу.<br />';
                                    } catch (\Throwable $th) {
                                        $this->data['res'][] = 'Ошибка! Данные услуги "'.$serv_name[$key].'" НЕ внесены в базу.<br />'.$th.'<br>';
                                    }
                                }
                            } else {
                                if (!empty($load->error)) {
                                    $this->data['res'][] = $load->error;
                                }
                                continue;
                            }
                            // CLEAR TMP FOLDER
                            if (!$load->delFilesInDir($load->tmp_dir)) {
                                if (!empty($load->error)) {
                                    $this->data['res'][] = $load->error;
                                }
                            }
                        }
                        $this->data['res'][] = '';
                    }
                } else {
                    $this->data['res'][] = 'Фото для загрузки не были выбраны.<br />';
                }
            }
        } else {
            $this->data['res'] = 'Не выбрана страница для редактирования.';
        }
    }

    protected function get_cats_data($cats_from_request)
    {
        $ar = explode('#', $cats_from_request);
        $this->cat_id = $ar[0];
        $this->cat_img = $ar[1];
        $this->cat_name = $ar[2];
        $this->page_id = $ar[3];
    }

    protected function get_serv4page_data($serv_from_request)
    {
        $arr = explode('#', $serv_from_request);
        $this->serv_id = $arr[0];
        $this->serv_name = $arr[1];
        $this->page_id = $arr[2];
        $this->cat_id = $arr[3];
        $this->serv_img = (!empty($arr[4])) ? $arr[4] : '';
    }

    protected function img_del($img_path_from_db)
    {
        if (is_dir($img_path_from_db)) {
            $this->data['res'][] = 'No image for service.';
        } else {
            if (mb_strstr(self::deleteFile(storage_path('app'.DIRECTORY_SEPARATOR.'public'.DIRECTORY_SEPARATOR.'images'.DIRECTORY_SEPARATOR.$img_path_from_db)), 'was removed')) {
                $this->data['res'][] = 'Изображение "'.$img_path_from_db.'" было удалено.';
            } else {
                $this->data['res'][] = 'Изображение "'.$img_path_from_db.'" НЕ было удалено потому что: '.self::deleteFile(storage_path('app'.DIRECTORY_SEPARATOR.'public'.DIRECTORY_SEPARATOR.'images'.DIRECTORY_SEPARATOR.$img_path_from_db));
            }
        }
    }

    protected function del_empty_pageimg_dir()
    {
        if (del_empty_dir(storage_path('app'.DIRECTORY_SEPARATOR.'public'.DIRECTORY_SEPARATOR.'images'.DIRECTORY_SEPARATOR.'services'.DIRECTORY_SEPARATOR.$this->page_id))) {
            $this->data['res'][] = 'Пустой каталог "storage/app/public/images/services/'.$this->page_id.'" удален.';
        }
    }

    protected function services_sql_del()
    {
        try {
            Service::find($this->serv_id)->masters()->detach();
            Order::where('service_id', $this->serv_id)->delete();
            Service::destroy($this->serv_id);
            $this->data['res'][] = 'Данные услуги "'.$this->serv_name.'" удалены из базы.';
        } catch (\Throwable $th) {
            $this->data['res'][] = $th->getMessage();
        }
    }

    protected function cat_sql_del()
    {
        $sql_serv = Service::where('category_id', $this->cat_id)->select('id')->get();
        $sql_serv->each(function ($serv) {
            $serv->masters()->detach();
            Order::where('service_id', $serv->id)->delete();
            $serv->destroy($serv->id);
        });

        $this->data['res'][] = 'Данные услуг категории "'.$this->cat_name.'" удалены из базы.';

        // sql del category
        try {
            ServiceCategory::destroy($this->cat_id);
            $this->data['res'][] = 'Данные категории "'.$this->cat_name.'" удалены из базы.';
        } catch (\Throwable $th) {
            $this->data['res'][] = $th->getMessage();
        }
    }

    protected function del_empty_cat_dir()
    {
        if (del_empty_dir(storage_path('app'.DIRECTORY_SEPARATOR.'public'.DIRECTORY_SEPARATOR.'images'.DIRECTORY_SEPARATOR.'categories'.DIRECTORY_SEPARATOR.$this->page_id))) {
            $this->data['res'][] = 'Пустой каталог "storage/app/public/images/categories/'.$this->page_id.'" удален.';
        }
        if (del_empty_dir(storage_path('app'.DIRECTORY_SEPARATOR.'public'.DIRECTORY_SEPARATOR.'images'.DIRECTORY_SEPARATOR.'services'.DIRECTORY_SEPARATOR.$this->page_id.DIRECTORY_SEPARATOR.$this->cat_id))) {
            $this->data['res'][] = 'Пустой каталог "storage/app/public/images/services/'.$this->page_id.'/'.$this->cat_id.'" удален.';
        }
    }
}
