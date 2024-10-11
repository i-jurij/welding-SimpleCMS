<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules\Password;

class UsersController extends Controller
{
    public function add()
    {
        return redirect()->route('admin.register.register');
    }

    public function list()
    {
        $users = [];
        $users = User::paginate(12);

        return view('admin_manikur.adm_pages.user_list', ['content' => $users]);
    }

    public function remove(Request $request)
    {
        $res = 'You have an admin grants?';
        if ($request->isMethod('post') && $request->user()['status'] === 'admin') {
            $user_id = $request->input('user_id');
            $i = 0;
            foreach ($user_id as $id) {
                if (DB::table('users')->delete($id)) {
                    ++$i;
                }
            }
            if ($i > 0 && $i === count($user_id)) {
                $res = 'Users have been removed from the database.';
            } elseif ($i > 0 && $i < count($user_id)) {
                $res = 'WARNING! Not all of checked Users have been removed from the database.';
            } else {
                $res = 'WARNING! Users have been NOT removed from the database.';
            }
        }

        return view('admin_manikur.adm_pages.user_result', ['res' => $res]);
    }

    public function show(Request $request)
    {
        $users = User::select('users.id', 'users.name', 'users.email', 'users.status')
            ->whereIn('users.id', $request->user_id)
            ->get();

        return view('admin_manikur.adm_pages.user_show', ['content' => $users]);
    }

    public function store(Request $request)
    {
        if ($request->isMethod('post') && $request->user()['status'] === 'admin') {
            $res = '';
            if (!empty($request->change_pass)) {
                $validator = $request->validate([
                    'change_pass' => [Password::defaults()],
                ]);

                $pass = User::where('id', $request->change_userid)
                    ->update([
                    'password' => Hash::make($validator['change_pass']),
                ]);
                if ($pass > 0) {
                    $res .= 'Users password have been stored in database.<br />';
                } else {
                    $res .= 'WARNING! Users password have been NOT stored in database.<br />';
                }
            }

            $change = User::where('id', $request->change_userid)
                        ->update(['name' => $request->change_name, 'email' => $request->change_email, 'status' => $request->change_status]);
            if ($change > 0) {
                $res .= 'Users data have been changed in database.';
            } else {
                $res .= 'WARNING! Users data have been NOT changed or NOT stored in database.';
            }
        } else {
            $res = 'You have an admin grants?';
        }

        return view('admin_manikur.adm_pages.user_result', ['res' => $res]);
    }
}
