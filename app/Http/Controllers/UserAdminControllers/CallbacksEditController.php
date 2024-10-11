<?php

namespace App\Http\Controllers\UserAdminControllers;

use App\Http\Controllers\Controller;
use App\Models\Callback;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Session;

class CallbacksEditController extends Controller
{
    public function need()
    {
        /*
        $callbacks = DB::table('callbacks')
            ->where([['response', false], ['order_id', null]])
            ->join('clients', 'clients.id', '=', 'callbacks.client_id')
            ->select('callbacks.id', 'clients.name', 'clients.phone', 'callbacks.send', 'callbacks.created_at')
            ->get();

        foreach ($callbacks as $key => $object) {
            $call[$key] = (array) $object;
            $call[$key]['created_at'] = date('d.m.Y H:i', strtotime($object->created_at));
        }
        */

        $call = Callback::where('response', false)
            ->with(['client'])
            ->get();

        return view('admin_manikur.user_pages.callbacks', ['callbacks' => $call, 'stat' => 'Need to']);
    }

    public function completed()
    {
        $callbacks = Callback::where('response', true)
        ->with(['client'])
        ->get();

        return view('admin_manikur.user_pages.callback_completed', ['callbacks' => $callbacks]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Callback $callback)
    {
        if (!empty($request->id)) {
            $callback->whereIn('id', $request->id)->update(['response' => true]);
        }

        return redirect()->route('admin.callbacks.need');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Request $request, Callback $callback)
    {
        $res = '';
        $today = Carbon::today();

        if ($request->has('submit')) {
            try {
                // $callback::where('response', true)->delete();
                $callback::where('response', true)->where('created_at', '<', $today->toDateTimeString())->delete();
                Session::flash('res', 'Callbacks data have been removed!');
                // $res .= 'Callbacks data have been removed!';
            } catch (\Throwable $th) {
                // $res .= 'WARNING! Callbacks data have been NOT removed.\n'.dd($th).'\n';
                $storageDestinationPath = storage_path('logs'.DIRECTORY_SEPARATOR.'callback_error.log');
                if (!File::exists($storageDestinationPath)) {
                    File::put($storageDestinationPath, "\n{$today}\ndd($th)\n");
                } else {
                    File::append($storageDestinationPath, "\n{$today}\ndd($th)\n");
                }
            }
        }

        return redirect()->route('admin.callbacks.completed');
    }
}
