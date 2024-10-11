<?php

namespace App\Http\Controllers\Moder;

use App\Http\Controllers\Controller;
use App\Models\About;
use Illuminate\Http\Request;

class AboutController extends Controller
{
    use \App\Traits\Upload;

    public function index(About $abouts)
    {
        $abouts = About::all()->toArray();
        $status = request()->segment(count(request()->segments()));

        return view('admin_manikur.moder_pages.about', ['abouts' => $abouts, 'status' => $status]);
    }

    public function create()
    {
        $status = 'create';

        return view('admin_manikur.moder_pages.about', ['status' => $status]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request, About $abouts)
    {
        $res = [];
        // upload image
        $this->disk = 'public';
        $this->folder = 'images'.DIRECTORY_SEPARATOR.'pages'.DIRECTORY_SEPARATOR.'about';

        $request->validate([
            'image_file.*' => 'mimes:jpg,png,webp|max:1024000',
        ]);
        if ($request->hasfile('image_file')) {
            foreach ($request->file('image_file') as $k => $image) {
                if ($request->file('image_file')[$k]->isValid()) {
                    $this->filename = mb_strtolower(sanitize(translit_to_lat($request->title[$k])));
                    $img = $this->uploadFile($image);
                    if ($img !== false) {
                        $res[$k]['img'] = 'The about image for "'.$request->title[$k].'" has been uploaded.';
                    }

                    $array = [
                        'title' => $request->title[$k],
                        'content' => $request->content[$k],
                        'image' => $img,
                    ];

                    $insert[$k] = $array;
                }
            }
        }

        if (!empty($insert) && is_array($insert)) {
            foreach ($insert as $key => $value) {
                // $res[$key]['db'] = $abouts->create($value)->attributesToArray();
                $abouts->create($value)->attributesToArray();
                $res[$key]['db'] = 'The abouts data for "'.$value['title'].'" has been stored in db.';
            }
        }

        return view('admin_manikur.moder_pages.about', ['res' => $res]);
    }

    /**
     * Display the specified resource.
     */
    public function show(About $abouts)
    {
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Request $request, About $abouts)
    {
        if (!empty($request->id) && is_array($request->id)) {
            $data = $abouts->whereIn('id', $request->id)->select('id', 'title', 'content')->get()->toArray();
        } else {
            $data = 'Nothing was selected';
        }

        return view('admin_manikur.moder_pages.about_edit_form', ['abouts' => $data]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, About $abouts)
    {
        $res = [];

        foreach ($request->id as $ka => $value) {
            $ids[$ka] = $value;
            $insert[$ka]['title'] = $request->title[$ka];
            $insert[$ka]['content'] = $request->content[$ka];
        }
        // upload image
        $this->disk = 'public';
        $this->folder = 'images'.DIRECTORY_SEPARATOR.'pages'.DIRECTORY_SEPARATOR.'about';

        $request->validate([
            'title' => 'max:50',
            'content' => 'max:500',
        ]);
        if ($request->hasfile('image_file')) {
            $request->validate([
                'image_file.*' => 'mimes:jpg,png,webp|max:1024000',
            ]);
            foreach ($request->file('image_file') as $k => $image) {
                if ($image->isValid()) {
                    $this->filename = mb_strtolower(sanitize(translit_to_lat($request->title[$k])));
                    $img = $this->uploadFile($image);
                    if ((bool) $img) {
                        $res[$k]['img'] = 'The about image for "'.$request->title[$k].'" has been uploaded.';
                        $insert[$k]['image'] = $img;
                    }
                }
            }
        }

        if (!empty($insert) && is_array($insert) && !empty($ids) && is_array($ids)) {
            foreach ($ids as $key => $id) {
                $abouts->where('id', $id)->update($insert[$key]);
                $res[$key]['db'] = 'The abouts data for "'.$insert[$key]['title'].'" has been stored in db.';
            }
        }

        return view('admin_manikur.moder_pages.about', ['res' => $res]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Request $request, About $abouts)
    {
        $res = '';
        if ($abouts->destroy($request->id)) {
            $res .= 'About data have been removed!<br>';
        } else {
            $res .= 'WARNING! About data have been NOT removed!<br>';
        }
        if (!empty($request->image) && is_array($request->image)) {
            foreach ($request->image as $image) {
                if (delete_file($image) !== 'true') {
                    $res .= delete_file($image);
                } else {
                    $res .= 'Image(s) for about(s) have been deleted.<br>';
                }
            }
        }

        return view('admin_manikur.moder_pages.about', ['res' => $res]);
    }
}
