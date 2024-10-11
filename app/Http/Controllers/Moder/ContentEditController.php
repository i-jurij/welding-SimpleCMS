<?php

namespace App\Http\Controllers\Moder;

use App\Http\Controllers\Controller;
use App\Models\Page;
use App\Models\Service;
use App\Models\ServiceCategory;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class ContentEditController extends Controller
{
    use \App\Traits\DeleteFile;
    use \App\Traits\Upload;

    public function index(Request $request)
    {
        if ($request->isMethod('get')) {
            return $this->start();
        }

        if ($request->isMethod('post')) {
            if ($request->content_add) {
                return $this->add($request);
            }
            if ($request->content_remove) {
                if (!empty($request->usluga)) {
                    return (string) $this->deleteFile(
                        storage_path(
                            'app'.DIRECTORY_SEPARATOR.
                            'public'.DIRECTORY_SEPARATOR.
                            'services_content'.DIRECTORY_SEPARATOR.
                            'Content_'.$request->usluga.'.odt'
                        ));
                } else {
                    return 'The service for deleting content is not selected!';
                }
            }
        }
    }

    protected function start()
    {
        $thisdata = [];
        $data['service_page'] = Page::where('publish', 'yes')
            ->where('service_page', 'yes')
            ->select('id', 'title', 'img')
            ->get()
            ->toArray();
        foreach ($data['service_page'] as $value) {
            $thisdata[$value['title']] = $value['id'];
        }

        $data['page_cats'] = ServiceCategory::select('id', 'page_id', 'name')
            ->get()
            ->toArray();
        $data['page_cats_serv'] = Service::whereNotNull('category_id')
            ->select('id', 'page_id', 'category_id', 'name')
            ->get()
            ->toArray();
        $data['page_serv'] = Service::whereNull('category_id')
            ->select('id', 'page_id', 'category_id', 'name')
            ->get()
            ->toArray();

        foreach ($data['service_page'] as $page) {
            foreach ($data['page_cats'] as $cat) {
                if ($cat['page_id'] === $page['id']) {
                    foreach ($data['page_cats_serv'] as $cat_serv) {
                        if ($cat_serv['category_id'] === $cat['id']) {
                            $thisdata['serv'][$page['title']][$cat['name']][$cat_serv['name']] = $cat_serv['id'];
                        }
                    }
                }
            }
            foreach ($data['page_serv'] as $serv) {
                if ($serv['page_id'] === $page['id']) {
                    $thisdata['serv'][$page['title']]['page_serv'][$serv['name']] = $serv['id'];
                }
            }
        }

        return $thisdata;
    }

    protected function add($request)
    {
        // add and upload odt files
        if (!empty($request->hasfile('file_content_add')) && !empty($request->usluga)) {
            $this->folder = 'services_content';
            $this->filename = 'Content_'.$request->usluga;

            $rules = [
                'file_content_add' => 'mimes:odt|max:3000',
            ];
            $messages = [
                'file_content_add.mimes' => 'Only "ODF" document format (odt extension) are allowed',
                'file_content_add.max' => 'Sorry! Maximum allowed size for document is 3MB',
            ];

            $validator = Validator::make($request->all(), $rules, $messages);

            if ($validator->fails()) {
                $res['res'] = $validator->messages();
            }

            if ($request->file('file_content_add')->isValid()) {
                $this->extension = $request->file('file_content_add')->extension();
                if ($this->uploadFile($request->file('file_content_add')) !== false) {
                    $res['res'] = 'File upload and save as "'.storage_path('public/services_content/'.$this->filename.'.'.$this->extension).'"';
                } else {
                    $res['res'] = 'ERROR! File NOT upload.';
                }
            }
        } else {
            $res['res'] = 'Input file is empty!';
        }

        return $res;
    }
}
