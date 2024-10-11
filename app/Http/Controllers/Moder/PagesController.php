<?php

namespace App\Http\Controllers\Moder;

use App\Http\Controllers\Controller;
use App\Http\Requests\StorePageRequest;
use App\Http\Requests\UpdatePageRequest;
use App\Models\Page;
use App\Models\Service;
use App\Models\ServiceCategory;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Schema;

class PagesController extends Controller
{
    use \App\Traits\Upload;
    use \App\Traits\CreateDeleteClientPage;

    /**
     * Display a listing of the resource.
     */
    public function index(string $res = null)
    {
        $pages = (Page::all()->toArray()) ? Page::all()->toArray() : 'No pages in DB';

        return view('admin_manikur.moder_pages.pages', ['res' => $res, 'pages' => $pages]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(Page $pages)
    {
        $columns = Schema::getConnection()->getDoctrineSchemaManager()->listTableColumns($pages->getTable());

        return view('admin_manikur.moder_pages.pages_create_form', ['fields' => $columns]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StorePageRequest $request)
    {
        // upload image
        $img = '';
        $img_res = '';
        $this->disk = 'public';
        $this->folder = 'images'.DIRECTORY_SEPARATOR.'pages';
        $this->filename = $request->alias;

        $img_valid = $request->validate([
            'picture' => 'mimes:jpg,png,webp|max:1024000',
            ]);
        if ($request->hasFile('picture') && $img_valid) {
            // $this->uploadFile($request->file('picture')) from trait Upload
            $img = $this->uploadFile($request->file('picture'));
            $img_res = 'The page image has been uploaded.<br>';
        }
        // end upload img

        // if single_page === no - create models, controllers etc
        if ($request->single_page === 'no') {
            if ($this->createNoSinglePage($request->alias)) {
                $img_res .= 'Controller, model, migration, view has been created.';
            } else {
                $img_res .= 'WARNING! Controller, model, migration, view has been NOT created.';
            }
        }

        // if service_page === yes - add to view buttons for categories and services creating
        if ($request->service_page === 'yes') {
            $create_cat_serv = 'layouts/create_cat_serv_buttons';
        } else {
            $create_cat_serv = '';
        }

        $create = Page::create([
            'alias' => $request->alias,
            'title' => $request->title,
            'description' => $request->description,
            'keywords' => ($request->keywords) ? $request->keywords : '',
            'robots' => $request->robots,
            'content' => ($request->content) ? $request->content : '',
            'single_page' => $request->single_page,
            'service_page' => ($request->service_page) ? $request->service_page : 'no',
            'img' => $img,
            'publish' => $request->publish,
        ]);
        $res = $create->attributesToArray();

        return view('admin_manikur.moder_pages.pages_store', ['res' => $res, 'img_res' => $img_res, 'create_cat_serv' => $create_cat_serv]);
    }

    /**
     * Display the specified resource.
     */
    public function show(Page $page)
    {
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Request $request, Page $pages)
    {
        $data = $pages->where('id', $request->id)->first()->toArray();

        return view('admin_manikur.moder_pages.page_edit_form', ['fields' => $data]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdatePageRequest $request, Page $pages)
    {
        $array = [
            'title' => $request->title,
            'description' => $request->description,
            'keywords' => ($request->keywords) ? $request->keywords : '',
            'robots' => $request->robots,
            'content' => ($request->content) ? $request->content : '',
            'single_page' => $request->single_page,
            'service_page' => $request->service_page,
            'publish' => $request->publish,
        ];
        // upload image
        $this->disk = 'public';
        $this->folder = 'images'.DIRECTORY_SEPARATOR.'pages';

        if ($request->hasfile('image_file') && $request->file('image_file')->isValid()) {
            $request->validate([
                'image_file.*' => 'mimes:jpg,png,webp|max:1024000',
            ]);

            $this->filename = mb_strtolower(sanitize(translit_to_lat($request->alias)));
            $img = $this->uploadFile($request->file('image_file'));
            if (!empty($img)) {
                $array['img'] = $img;
            }
        }
        if ($pages::where('id', $request->id)->update($array)) {
            return $this->index('Data of pages <b>"'.$request->alias.'"</b> have been updated!');
        } else {
            return $this->index('Data of pages <b>"'.$request->alias.'"</b> have been NOT updated!');
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Request $request, Page $pages)
    {
        $res = '';
        list($page_id, $alias, $img, $single_page, $service_page) = explode('plusplus', $request->id);
        if (!empty($single_page) && $single_page === 'no') {
            // delete created files, tables
            $res .= $this->delContrModMigrView($alias);
        }

        if (!empty($page_id) && $pages->destroy($page_id)) {
            // DELETE categories and services from table in db
            /* IF SET RELATIONSHIPS in model - not need
            if (!empty($service_page) && $service_page === 'yes') {
                if (ServiceCategory::where('page_id', $page_id)->delete()) {
                    $res .= 'Data of page from table "servise_ctegories" in DB <b>'.$alias.'</b> have been removed!<br>';
                }
                if (Service::where('page_id', $page_id)->delete()) {
                    $res .= 'Data of page from table "services" in DB <b>'.$alias.'</b> have been removed!<br>';
                }
            }
            */
            $res .= 'Data of page from table "pages" in DB <b>'.$alias.'</b> have been removed!<br>';
            /* $this->deleteFile($img, 'public') from trait Upload */
            if ($this->removeFile($img, 'public')) {
                $res .= 'Image of page <b>'.$alias.'</b> have been removed!';
            }

            return $this->index($res);
        } else {
            return $this->index('WARNING! The page <b>'.$alias.'</b> was deleted earlier or NOT removed!');
        }
    }
}
