<?php

namespace App\Http\Controllers\Client;

use App\Http\Controllers\Controller;
use App\Models\About;
use App\Models\Master;
use Illuminate\Database\Eloquent\Factories\Sequence;

class AboutController extends Controller
{
    public function index($content, $page_data = '', $method_and_params = '')
    {
        if (About::exists()) {
            $abouts = About::all()->toArray();
        } else {
            $abouts = [];
            /*
            About::factory()
            ->count(5)
            ->create();
            $abouts = About::all()->toArray();
            */
        }

        if (Master::exists()) {
            $masters = Master::whereNull('data_uvoln')->get()->toArray();
        } else {
            $masters = [];
            /*
            Master::factory()
            ->count(6)
            ->state(new Sequence(
                ['data_uvoln' => null],
                ['data_uvoln' => date('Y-m-d H:i:s', time())],
            ))
            ->create();
            $masters = Master::all()->toArray();
            */
        }

        return view('client_manikur.client_pages.about', ['page_data' => $page_data, 'content' => $content, 'masters' => $masters, 'abouts' => $abouts]);
    }
}
