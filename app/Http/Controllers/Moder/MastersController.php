<?php

namespace App\Http\Controllers\Moder;

use App\Http\Controllers\Controller;
use App\Models\Master;
use App\Models\Page;
use App\Models\Service;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class MastersController extends Controller
{
    use \App\Traits\Upload;

    public function index(Master $masters)
    {
        $services = [];
        $m = $this->all_masters($masters);

        // services for each masters
        foreach ($m as $mm) {
            if (!empty($mm) && is_array($mm)) {
                foreach ($mm as $master) {
                    $services[$master['id']] = $this->services_for_master($masters, $master['id']);
                }
            }
        }

        return view('admin_manikur.moder_pages.masters', ['masters' => $m['masters'], 'masters_dism' => $m['dismissed_masters'], 'services' => $services]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $data = $this->all_services();

        return view('admin_manikur.moder_pages.masters_create_form', ['services' => $data]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request, Master $masters)
    {
        $res = [];
        // upload image
        $this->disk = 'public';
        $this->folder = 'images'.DIRECTORY_SEPARATOR.'masters';
        $this->filename = mb_strtolower(sanitize(translit_to_lat($request->master_phone_number)));

        $request->validate([
            'image_file' => 'mimes:jpg,png,webp|max:1024000',
        ]);
        if ($request->hasfile('image_file') && $request->file('image_file')->isValid()) {
            if ($img = $this->uploadFile($request->file('image_file'))) {
                $res['img'] = 'The photo of master "'.$request->master_fam.'" has been uploaded.';
            }
        }

        $insert = [
            'master_photo' => (!empty($img)) ? $img : null,
            'master_name' => $request->master_name,
            'sec_name' => (!empty($request->sec_name)) ? $request->sec_name : null,
            'master_fam' => $request->master_fam,
            'master_phone_number' => $request->master_phone_number,
            'spec' => '',
            'data_priema' => (!empty($request->hired)) ? $request->hired : null,
            'data_uvoln' => (!empty($request->dismissed)) ? $request->dismissed : null,
        ];

        if (!empty($insert) && is_array($insert)) {
            $master = $masters->where([
                'master_name' => $request->master_name,
                'master_fam' => $request->master_fam,
                'master_phone_number' => $request->master_phone_number,
            ])->first();
            if (empty($master->id)) {
                $master_create = $masters->create($insert);
                $res['db'] = 'The data of master "'.$request->master_fam.'" has been stored in db.';
                // create user of app for master
                $ne = my_sanitize_number($request->master_phone_number);

                $user = User::where(['name' => $ne, 'email' => $ne.'@com.com'])->first();
                if (empty($user->id)) {
                    $user_create = User::create([
                        'name' => $ne,
                        'email' => $ne.'@com.com',
                        'status' => 'user',
                        'password' => Hash::make('password'),
                    ]);
                    $master_create->user_id = $user_create->id;
                    $res['user'] = 'User "'.$ne.'" with email "'.$ne.'@com.com" created.';
                } else {
                    $master_create->user_id = $user->id;
                    $res['user'] = 'User "'.$ne.'" with email "'.$ne.'@com.com" already exists.';
                }

                $master_create->save();
                if (!empty($request->serv)) {
                    $serv = Service::find($request->serv);
                    $master_create->services()->attach($serv);
                }
            } else {
                $res['master_isset'] = 'The data of master "'.$request->master_fam.'" already exists in db.';
            }
        }

        // return view('admin_manikur.moder_pages.masters', ['res' => $res]);
        return redirect()->route('admin.masters.list')->with('mes', $res);
    }

    /**
     * Display the specified resource.
     */
    public function show(Master $masters)
    {
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Request $request, Master $masters)
    {
        // $res = $masters->where('id', $request->id)->get()->toArray();
        // $res = $masters->where('id', $request->id)->first()->toArray();
        $master = $masters->find($request->id);
        $data['master'] = $master->toArray();
        $data['services'] = $this->all_services();

        return view('admin_manikur.moder_pages.masters_edit', ['res' => $data]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Master $masters)
    {
        if (!empty($request->id)) {
            $res['db'] = 'The data of master has not been changed.';
            if (!empty($request->master_name)) {
                $insert['master_name'] = $request->master_name;
            }
            if (!empty($request->sec_name)) {
                $insert['sec_name'] = $request->sec_name;
            }
            if (!empty($request->master_fam)) {
                $insert['master_fam'] = $request->master_fam;
            }
            if (!empty($request->master_phone_number)) {
                $insert['master_phone_number'] = $request->master_phone_number;
            }
            if (!empty($request->spec)) {
                $insert['spec'] = $request->spec;
            }
            if (!empty($request->hired)) {
                $insert['data_priema'] = $request->hired;
            }
            if (!empty($request->dismissed)) {
                $insert['data_uvoln'] = $request->dismissed;
            }

            // upload image
            $this->disk = 'public';
            $this->folder = 'images'.DIRECTORY_SEPARATOR.'masters';

            if ($request->hasfile('image_file') && $request->file('image_file')->isValid()) {
                $request->validate([
                    'image_file.*' => 'mimes:jpg,png,webp|max:1024000',
                ]);

                $this->filename = mb_strtolower(sanitize(translit_to_lat($request->master_phone_number)));
                $img = $this->uploadFile($request->file('image_file'));
                if (!empty($img)) {
                    $res['img'] = 'The photo of master has been uploaded.';
                    $insert['master_photo'] = $img;
                }
            }

            if (!empty($insert) && is_array($insert)) {
                if ($masters->where('id', $request->id)->update($insert) > 0) {
                    $res['db'] = 'The data of master has been updated in db.';
                }
            }

            if (!empty($request->serv)) {
                $master = $masters::find($request->id);
                $master->services()->detach($request->serv);
                $master->services()->attach($request->serv);
                $res['db'] = 'The data of master has been updated in db.';
            }
        } else {
            $res = 'The master was not selected';
        }

        // return view('admin_manikur.moder_pages.masters', ['res' => $res]);
        return redirect()->route('admin.masters.list')->with('mes', $res);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Request $request, Master $masters)
    {
        $res = '';
        list($id, $image) = explode('plusplus', $request->id);
        if (!empty($id)) {
            $master = $masters->find($id);
            if (!empty($master->user_id)) {
                $user = User::where('id', $master->user_id)->delete();
                $res .= 'User data have been removed! ';
            }

            DB::table('restdaytimes')->where('master_id', $id)->delete();

            $master->services()->detach();
            $masters->destroy($id);
            $res .= 'Masters data have been removed! ';
        } else {
            $res .= 'WARNING! Masters data have been NOT removed! ';
        }
        if (!empty($image) && $image !== 'images'.DIRECTORY_SEPARATOR.'ddd.jpg') {
            if (delete_file(storage_path('app'.DIRECTORY_SEPARATOR.'public'.DIRECTORY_SEPARATOR.$image)) !== 'true') {
                $res .= delete_file(storage_path('app'.DIRECTORY_SEPARATOR.'public'.DIRECTORY_SEPARATOR.$image));
            } else {
                $res .= 'Image of Master have been deleted.';
            }
        }

        // return view('admin_manikur.moder_pages.masters', ['res' => $res]);
        return redirect()->route('admin.masters.list')->with('mes', $res);
    }

    public function all_services()
    {
        $data = [];
        // get data from table services (id, page_id, categories_id, name)
        // and from service-categories and pages (with service_page === 'yes')
        // than in view we can display list of pages -> categories-> services and choose what you need
        $services = [];
        $services = Page::select('id', 'title')->where('service_page', 'yes')
        ->with('categories')
        ->with('services')
        ->get()
        ->toArray();

        foreach ($services as $page) {
            foreach ($page['categories'] as $cat) {
                foreach ($page['services'] as $cat_serv) {
                    if ($cat_serv['category_id'] === $cat['id']) {
                        $data[$page['id'].'#'.$page['title']][$cat['id'].'#'.$cat['name']][$cat_serv['id']] = $cat_serv['name'];
                    }
                }
            }
            foreach ($page['services'] as $serv) {
                if (empty($serv['category_id'])) {
                    $data[$page['id'].'#'.$page['title']][$page['id'].'#page_serv'][$serv['id']] = $serv['name'];
                }
            }
        }

        return $data;
    }

    public function all_masters(Master $masters)
    {
        if ($masters->exists()) {
            $m = $masters->whereNull('data_uvoln')->get()->toArray();
            $m_dism = $masters->whereNotNull('data_uvoln')->get()->toArray();
        }
        if (!isset($m)) {
            $m = 'Table masters is empty';
        }
        if (!isset($m_dism)) {
            $m_dism = '';
        }

        return ['masters' => $m, 'dismissed_masters' => $m_dism];
    }

    public function services_for_master(Master $master, $master_id)
    {
        $services = $master->find($master_id)->services;
        $page_serv = $services->each(function ($serv) {
            if (!empty($serv->category_id)) {
                $serv->category->page;
            } else {
                $serv->page;
            }
        })->toArray();

        $data = [];
        $page_id = 0;
        foreach ($page_serv as $serv) {
            if (!empty($serv['category']['page']['id'])) {
                $page_id = $serv['category']['page']['id'];
                $title = $serv['category']['page']['title'];
            }

            if (!empty($serv['page']['id'])) {
                $page_id = $serv['page']['id'];
                $title = $serv['page']['title'];
            }

            $page_title = (!empty($title)) ? $title : 'no page title';

            $category_id = (!empty($serv['category']['id'])) ? $serv['category']['id'] : $page_id;

            $category_name = (!empty($serv['category']['name'])) ? $serv['category']['name'] : 'page_serv';

            $data[$page_id.'#'.$page_title][$category_id.'#'.$category_name][$serv['id']] = $serv['name'];
        }

        return $data;
    }
}
