<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;

// menu (list of routes to admin pages)
class AdminHomeController extends Controller
{
    use \App\Traits\PagesRoutesHierarchyArray;

    public function getview()
    {
        $collection_of_routes = $this->routs_hierarchie();

        return view('admin_manikur.home_adm', ['routes' => $collection_of_routes]);
    }
}
