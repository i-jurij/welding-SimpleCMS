<?php

namespace App\Http\Controllers\Moder;

use App\Http\Controllers\Controller;
use App\Models\Page;
use App\Models\Service;
use Illuminate\Http\Request;

class PriceEditController extends Controller
{
    public function edit(Page $page)
    {
        $data = [];
        $data['service_page'] = $page->where('service_page', 'yes')->get()->toArray();

        return view('admin_manikur.moder_pages.price', ['data' => $data]);
    }

    public function post_edit(Request $request, Page $page)
    {
        if (!empty($request->id)) {
            $pages = Page::select('id', 'alias', 'title')
            ->where('id', $request->id)
            ->with('categories')
            ->with('services')
            ->get()
            ->toArray();

            foreach ($pages as $page) {
                $data['id'] = $page['id'];
                $data['alias'] = $page['alias'];
                $data['title'] = $page['title'];

                foreach ($page['categories'] as $cat) {
                    foreach ($page['services'] as $cat_serv) {
                        if ($cat_serv['category_id'] === $cat['id']) {
                            $data['serv'][$cat['name']][$cat_serv['name']] = $cat_serv['id'].'#'.$cat_serv['price'];
                        }
                    }
                }
                foreach ($page['services'] as $serv) {
                    if (empty($serv['category_id'])) {
                        $data['serv']['page_serv'][$serv['name']] = $serv['id'].'#'.$serv['price'];
                    }
                }
            }
        } else {
            $data['res'] = 'Отсутствуют входные данные.';
        }

        return view('admin_manikur.moder_pages.price', ['data' => $data]);
    }

    public function update(Request $request, Service $service)
    {
        $data['res'] = '';
        if (!empty($request->serv_id)) {
            foreach ($request->serv_id as $id => $price) {
                $ids[] = test_input($id);
                $re = "/^-?(?:\d+|\d*\.\d+|\d*\,\d+)$/";
                if (preg_match($re, $price)) {
                    $price_end[]['price'] = $price;
                } else {
                    $price_end[]['price'] = '';
                }
            }

            $rules = [
                'serv_id.*' => ["regex:/^-?(?:\d+|\d*\.\d+|\d*\,\d+)$/"],
            ];
            $messages = [
                'serv_id.*.regex' => ['Type of inputs field must be decimal.'],
            ];
            $valid = $request->validate($rules, $messages);

            foreach ($ids as $key => $id) {
                $service->where('id', $id)->update($price_end[$key]);
            }
            $data['res'] = 'Изменения внесены в базу.';
        } else {
            $data['res'] = 'Отсутствуют входные данные.';
        }

        return view('admin_manikur.moder_pages.price', ['data' => $data]);
    }
}
