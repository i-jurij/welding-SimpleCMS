<?php

namespace App\Http\Controllers\Moder;

use App\Http\Controllers\Controller;
use App\Models\Page;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Schema;

class ServicePageEditController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(string $res = null)
    {
        $page = (Page::all()->toArray()) ? Page::all()->toArray() : 'No pages in DB';

        return view('admin_manikur.moder_pages.pages', ['res' => $res, 'pages' => $page]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(Page $page)
    {
        $columns = Schema::getConnection()->getDoctrineSchemaManager()->listTableColumns($page->getTable());

        return view('admin_manikur.moder_pages.pages_create_form', ['fields' => $columns]);
    }

    /**
     * Display the specified resource.
     */
    public function services_edit()
    {
        $data['service_page'] = (Page::all()->toArray()) ? Page::all()->toArray() : 'No pages in DB';

        return view('admin_manikur.moder_pages.service_edit', ['data' => $data]);
    }

    public function go(Request $request)
    {
        $class = new ServiceEditController();
        $data = $class->go($request);

        return view('admin_manikur.moder_pages.service_edit', ['data' => $data]);
    }

    public function content(Request $request)
    {
        $content = new ContentEditController();
        $data = $content->index($request) ?? null;

        return view('admin_manikur.moder_pages.content_edit', ['data' => $data]);
    }
}
