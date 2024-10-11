<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Crypt;

class LogsController extends Controller
{
    use \App\Traits\ClearFile;

    public function index()
    {
        $logs = files_in_dir(storage_path('logs'), $ext = 'log');
        if (!empty($logs)) {
            foreach ($logs as $key => $file) {
                $path = storage_path('logs'.DIRECTORY_SEPARATOR.$file);
                $size = filesize($path);
                $res[$key]['size'] = human_filesize($size, 2);
                // $res[$key]['file'] = $file;
                $res[$key]['file'] = Crypt::encryptString($file);
            }
        }

        return view('admin_manikur.adm_pages.logs', ['list' => $res]);
    }

    public function show(Request $request)
    {
        if (!empty($request->log_name)) {
            $log = Crypt::decryptString($request->log_name);
            // $content = file_get_contents(storage_path('logs'.DIRECTORY_SEPARATOR.$log));
            $size = filesize(storage_path('logs'.DIRECTORY_SEPARATOR.$log));

            if (!empty($request->show)) {
                if ($size > 10 * 1024 * 1024) {
                    $lines = get_n_lines_from_txt_file(storage_path('logs'.DIRECTORY_SEPARATOR.$log), 5000);
                } else {
                    $lines = file(storage_path('logs'.DIRECTORY_SEPARATOR.$log));
                }

                $content['log_name'] = $log;
                $content['log_data'] = $lines;

                return view('admin_manikur.adm_pages.logs', ['show' => $content]);
            }

            if (!empty($request->clear)) {
                if ($this->clearFile(storage_path('logs'.DIRECTORY_SEPARATOR.$log), 5000)) {
                    $res = 'Success! Log "'.$log.'" cleared.';

                    return back()->with('res', $res);
                } else {
                    $res = 'Error! Log "'.$log.'" NOT cleared.';

                    return back()->with('res', $res);
                }
            }
        } else {
            return back();
        }
    }
}
