<?php

namespace App\Traits;

use Illuminate\Support\Facades\Route;

trait PagesRoutesHierarchyArray
{
    public function list_of_routes_by_middleware_userstatus(): array
    {
        $res = [];
        $routes = Route::getRoutes()->getRoutesByMethod()['GET'];
        $admin_routes = [];
        $moder_routes = [];
        $user_routes = [];
        foreach ($routes as $route) {
            $middleware = $route->middleware();
            for ($i = 0; $i < count($middleware); ++$i) {
                if ($middleware[$i] == 'isadmin') {
                    array_push($admin_routes, $route->getName());
                }
                if ($middleware[$i] == 'ismoder') {
                    if ($route->getName() !== 'admin.price.post_edit') {
                        array_push($moder_routes, $route->getName());
                    }
                }
                if ($middleware[$i] == 'isuser') {
                    array_push($user_routes, $route->getName());
                }
            }
        }
        $res = ['admin' => $admin_routes, 'moder' => $moder_routes, 'user' => $user_routes];

        return $res;
    }

    public function routs_hierarchie(): array
    {
        $res = [];
        foreach ($this->list_of_routes_by_middleware_userstatus() as $key => $value) {
            $tree = [];
            foreach ($value as $path) {
                $tmp = &$tree;
                $pathParts = explode('.', rtrim($path, '.'));
                foreach ($pathParts as $pathPart) {
                    if (!array_key_exists($pathPart, $tmp)) {
                        $tmp[$pathPart] = [];
                    }
                    $tmp = &$tmp[$pathPart];
                }
                $tmp = $path;
            }

            $res[$key] = $tree;
        }

        return $res;
    }
}
