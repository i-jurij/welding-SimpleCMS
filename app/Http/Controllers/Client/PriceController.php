<?php

namespace App\Http\Controllers\Client;

use App\Http\Controllers\Controller;
use App\Models\Page;

class PriceController extends Controller
{
    public function index($content, $page_data, $path_array)
    {
        $pages = ['empty price'];

        $pages = Page::select('id', 'alias', 'title')->where('service_page', 'yes')
        ->with('categories')
        ->with('services')
        ->get()
        ->toArray();

        foreach ($pages as $page) {
            foreach ($page['categories'] as $cat) {
                foreach ($page['services'] as $cat_serv) {
                    if ($cat_serv['category_id'] === $cat['id']) {
                        $data['serv'][$page['alias'].'#'.$page['title']][$cat['name']][$cat_serv['name']] = $cat_serv['price'];
                    }
                }
            }
            foreach ($page['services'] as $serv) {
                if (empty($serv['category_id'])) {
                    $data['serv'][$page['alias'].'#'.$page['title']]['page_serv'][$serv['name']] = $serv['price'];
                }
            }
        }

        return view('client_manikur.client_pages.price', ['data' => $data, 'content' => $content, 'page_data' => $page_data]);
    }
}
