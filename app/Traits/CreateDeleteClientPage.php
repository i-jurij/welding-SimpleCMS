<?php

namespace App\Traits;

use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

trait CreateDeleteClientPage
{
    use CreateFile;
    use FileFind;
    use DeleteFile;

    protected function createFile($path, $content)
    {
        if ($this->checkAndCreateFile($path, $content)) {
            return true;
        } else {
            return false;
        }
    }

    public function delContrModMigrView($page_alias): string
    {
        $mes = '';
        if (function_exists('my_mb_ucfirst')) {
            $classname = my_mb_ucfirst($page_alias);
        }
        $migrations_dir = app_path().DIRECTORY_SEPARATOR.'..'.DIRECTORY_SEPARATOR.'database'.DIRECTORY_SEPARATOR.'migrations';
        // $controller = app_path().DIRECTORY_SEPARATOR.'Http'.DIRECTORY_SEPARATOR.'Controllers'.DIRECTORY_SEPARATOR.$classname.'Controller.php';
        $controller = app_path().DIRECTORY_SEPARATOR.'Http'.DIRECTORY_SEPARATOR.'Controllers'.DIRECTORY_SEPARATOR.'Client'.DIRECTORY_SEPARATOR.$classname.'Controller.php';
        $model = app_path().DIRECTORY_SEPARATOR.'Models'.DIRECTORY_SEPARATOR.$classname.'.php';
        $migration = [$this->migrationFind($migrations_dir, $page_alias)];
        $view = realpath(app_path().DIRECTORY_SEPARATOR.'..'.DIRECTORY_SEPARATOR.'resources'.DIRECTORY_SEPARATOR.'views'.DIRECTORY_SEPARATOR.'client_manikur'.DIRECTORY_SEPARATOR.'client_pages'.DIRECTORY_SEPARATOR.$page_alias.'.blade.php');

        $path_array = [
            'controller' => $controller,
            'model' => $model,
            'migration' => $migration,
            'view' => $view,
        ];

        // delete row with table name from table migrations
        foreach ($migration[0] as $value) {
            DB::table('migrations')->where('migration', pathinfo($value, PATHINFO_FILENAME))->delete();
        }
        $mes .= 'Entries from table  <b>mirations</b> in DB have been removed!<br>';

        // delete table of page from db
        Schema::dropIfExists($page_alias);
        Schema::dropIfExists($page_alias.'s');
        $mes .= 'Table  <b>'.$page_alias.'</b> in DB have been removed!<br>';

        foreach ($path_array as $key => $path) {
            if ($key === 'migration' && is_array($path[0])) {
                foreach ($path[0] as $v) {
                    if ($this->deleteFile($v)) {
                        $mes .= 'Migration "'.pathinfo($value, PATHINFO_FILENAME).'" for page <b>"'.$page_alias.'"</b> has been deleted.<br>';
                    } else {
                        $mes .= 'Migration "'.pathinfo($value, PATHINFO_FILENAME).'" for page <b>"'.$page_alias.'"</b> has been NOT deleted.<br>'.(string) $this->delFile($path).'<br>';
                    }
                }
            } elseif ($this->deleteFile($path)) {
                $mes .= my_mb_ucfirst($key).' for page <b>"'.$page_alias.'"</b> has been deleted.<br>';
            } else {
                $mes .= my_mb_ucfirst($key).' for page <b>"'.$page_alias.'"</b> has been NOT deleted.<br>'.(string) $this->delFile($path).'<br>';
            }
        }

        return $mes;
    }

    protected function createNoSingleController($classname)
    {
        $path = app_path().DIRECTORY_SEPARATOR.'Http'.DIRECTORY_SEPARATOR.'Controllers'.DIRECTORY_SEPARATOR.'Client'.DIRECTORY_SEPARATOR.$classname.'Controller.php';
        $content = '<?php'
            .PHP_EOL.'namespace App\Http\Controllers\Client;'
            .PHP_EOL.'use App\Http\Controllers\Controller;'
            .PHP_EOL.'use App\Models\Contacts;'
            .PHP_EOL.'use App\Models\Pages;'
            .PHP_EOL.'class '.$classname.'Controller extends Controller'
            .PHP_EOL.'{'
                .PHP_EOL.'public function index($content, $page_data = \'\', $path_array = \'\')'
                .PHP_EOL.'{'
                    .PHP_EOL.'$res = ["Empty page."];'
                    .PHP_EOL.'return view("client_manikur.client_pages.'.mb_strtolower($classname).'", ["page_data" => $page_data, "content" => $content, "res" => $res]);'
                    .PHP_EOL.'}'
                .PHP_EOL.'}
        ';

        return $this->createFile($path, $content);
    }

    protected function createNoSingleView($page_alias)
    {
        $path = app_path().DIRECTORY_SEPARATOR.'..'.DIRECTORY_SEPARATOR.'resources'.DIRECTORY_SEPARATOR.'views'.DIRECTORY_SEPARATOR.'client_manikur'.DIRECTORY_SEPARATOR.'client_pages'.DIRECTORY_SEPARATOR.$page_alias.'.blade.php';
        $content = '@php'
            .PHP_EOL.'if (isset($page_data) && is_array($page_data) && !empty($page_data[0])) {'
                .PHP_EOL.'$title = $page_data[0]["title"];'
                .PHP_EOL.' $page_meta_description = $page_data[0]["description"];'
                .PHP_EOL.' $page_meta_keywords = $page_data[0]["keywords"];'
                .PHP_EOL.'$robots = $page_data[0]["robots"];'
                .PHP_EOL.'$content["page_content"] = $page_data[0]["content"];'
            .PHP_EOL.'} else {'
                .PHP_EOL.'$title = "Title";'
                .PHP_EOL.'$page_meta_description = "description";'
                .PHP_EOL.'$page_meta_keywords = "keywords";'
                .PHP_EOL.'$robots = "INDEX, FOLLOW";'
                .PHP_EOL.'$content = "CONTENT FOR DEL IN FUTURE";'
                .PHP_EOL.'}'
            .PHP_EOL.'@endphp'
            .PHP_EOL.'@extends("layouts/index")'
            .PHP_EOL.'@section("content")'
            .PHP_EOL.'@if (!empty($menu)) <p class="content">{{$menu}}</p> @endif'
                .PHP_EOL.'<div class="content">'
                .PHP_EOL.'@if (!empty($res) && is_array($res))'
                    .PHP_EOL.'@foreach ($res as $re)'
                        .PHP_EOL.'{{$re}}'
                    .PHP_EOL.'@endforeach'
                .PHP_EOL.'@endif'
                .PHP_EOL.'@if (!empty($content["page_content"]))'
                    .PHP_EOL.'{!! $content["page_content"] !!}'
                .PHP_EOL.'@endif'
                .PHP_EOL.'</div>'
            .PHP_EOL.'@stop
        ';

        return $this->createFile($path, $content);
    }

    public function createNoSinglePage($page_alias): bool
    {
        if (function_exists('my_mb_ucfirst')) {
            $classname = my_mb_ucfirst($page_alias);
        }
        // $artisan = Artisan::call('make:model', ['name' => $classname, '--controller' => true, '--migration' => true]);
        $artisan = Artisan::call('make:model', ['name' => $classname, '--migration' => true]);

        if ($artisan === 0 && $this->createNoSingleController($classname) && $this->createNoSingleView($page_alias)) {
            // put controller, model, migration code into files
            return true;
        } else {
            // delete created files
            $this->delContrModMigrView($page_alias);

            return false;
        }
    }
}
