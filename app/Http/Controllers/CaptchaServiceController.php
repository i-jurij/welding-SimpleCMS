<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class CaptchaServiceController extends Controller
{
    public function index()
    {
        return view('index');
    }

    public function capthcaFormValidate(Request $request)
    {
        $this->validate($request, [
            'number' => 'required',
            'captcha' => 'required|captcha',
        ], [
                'captcha.captcha' => 'Invalid captcha code.',
        ]);
    }

    public function reloadCaptcha()
    {
        return response()->json(['captcha' => captcha_img()]);
    }
}
