<?php

namespace App\Http\Controllers\Client;

use App\Http\Controllers\Controller;
use App\Jobs\JobCallbackMail;
use App\Models\Callback;
use App\Models\Client;
use App\Models\Contacts;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Storage;

class CallbackController extends Controller
{
    public function index($content, $page_data, $method_and_params = '')
    {
        $res = null;
        $files = Storage::disk('public')->files('images'.DIRECTORY_SEPARATOR.'captcha_imgs');
        $randomArray = [];

        // 9 - number of images in captcha in callback.blade.php
        while (count($randomArray) < 9) {
            $randomKey = mt_rand(0, count($files) - 1);
            $randomArray[$randomKey] = $files[$randomKey];
        }
        $randomArray = array_values($randomArray);

        return view('client_manikur.client_pages.callback', ['page_data' => $page_data, 'content' => $content, 'res' => $res, 'captcha_imgs' => $randomArray]);
    }

    public function store(Request $request, Client $client)
    {
        $content['contacts'] = Contacts::select('type', 'data')->get()->toArray();
        /*
        $page_data = (Pages::where('alias', 'callback')->get()) ? Pages::where('alias', 'callback')->get()->toArray() : ['No pages data in DB'];
        */
        // field last_name is visually hidden and must be empty else - spambot
        $res = 'Your request has been processed';

        // определяем IP-адрес пользователя, or proxy :(
        // в переменную $badIP мы можем со временем вписать IP-адреса некоторых спамеров
        // (например тех, которые заполнят Вашу форму вручную)
        // IP будет указано в log файле. IP-адреса указываем в кавычках, через запятую,
        // пример: ['185.189.114.123', '185.212.171.99',]
        $badIP = [];
        $ipAddr = $_SERVER['REMOTE_ADDR'];
        $today = date('Y-m-d_H:i:s');
        $log_text = "Заявка на звонок из формы \"Перезвоните мне\"\n
                     Имя: {$request->name}\n
                     Телефон: {$request->phone_number}\n
                     Сообщение: {$request->send}\n
                     \$badIP located in app/Controllers/Client/CallbackController.php in method store().";

        // а также если в поле с сообщением нет ни одного соответствия адресам сайтов
        // можем добавить любые другие сочетания букв, по аналогии, через пайп, например (\.ua) и прочее
        $site_link = preg_match("/(www)|(http)|(https)|(@)|(\.ru)|(\.com)|(\.ua)|(\.рф)/i", $request->send);

        // если в поле с сообщением были признаки сайтов - записываем логи
        if ((bool) $site_link) {
            $storageDestinationPath = storage_path('logs'.DIRECTORY_SEPARATOR.'callback_spam.log');
            if (!File::exists($storageDestinationPath)) {
                File::put($storageDestinationPath, "\n{$today}\nIP:{$ipAddr}\n{$log_text}\n");
            } else {
                File::append($storageDestinationPath, "\n{$today}\nIP:{$ipAddr}\n{$log_text}\n");
            }
        }

        if (!in_array($ipAddr, $badIP) && empty($request->last_name)) {
            $rules = [
                'phone_number' => ['required', 'regex:/^(\+?(7|8|38))[ ]{0,1}s?[\(]{0,1}?\d{3}[\)]{0,1}s?[\- ]{0,1}s?\d{1}[\- ]{0,1}?\d{1}s?[\- ]{0,1}?\d{1}s?[\- ]{0,1}?\d{1}s?[\- ]{0,1}?\d{1}s?[\- ]{0,1}?\d{1}s?[\- ]{0,1}?\d{1}s?[\- ]{0,1}?/'],
            ];
            $messages = [
                'phone_number.regex' => 'The phone number does not match the specified format. Телефонный номер не соответсвует формату +9 999 999 99 99',
            ];
            if (isset($request->name)) {
                $rules['name'] = 'max:255';
            }
            if (isset($request->send)) {
                $rules['send'] = ['max:500', 'not_regex:/(www)|(http)|(https)|(@)|(\.ru)|(\.com)|(\.ua)|(\.рф)/i'];
                $messages = [
                    'send.max' => 'Send is too long',
                    'send.not_regex' => [
                        'В сообщении присутствуют ссылки на интернет сайты.',
                        'Пожалуйста, отправьте сообщение без ссылок или свяжитесь с нами по телефону.',
                    ],
                ];
            }
            if (isset($request->dada)) {
                $this->validate($request, $rules, $messages);
            } else {
                $rules['captcha'] = 'required|captcha';
                $messages['captcha'] = 'Invalid captcha code.';
                $this->validate($request, $rules, $messages);
            }

            // INSERT TO CLIENT AND CALLBACK TABLES
            $client_insert = [
                'name' => ($request->name) ? $request->name : null,
                'phone' => $request->phone_number,
            ];

            $cd = $client->updateOrCreate([
                'phone' => $request->phone_number,
            ], $client_insert);

            $client_id = $client->where('phone', $request->phone_number)->first()->id;
            $callback_insert = [
                'client_id' => $client_id,
                'send' => ($request->send) ? $request->send : null,
                'response' => false,
            ];

            // выборка из бд за последние 2 часа
            /*
            $time = date('Y-m-d H:i:s', strtotime('-2 hour'));
            $sql2 = "SELECT client_id FROM `callbacks` WHERE created_at > NOW() - INTERVAL 2 HOUR";
            $vib_date_time = $pdo->query($sql2);
            */
            $now = Carbon::now();
            $sub = Carbon::now()->subHours(2);
            $callback_2_hours = Callback::whereBetween('created_at', [$sub, $now])
             ->where('client_id', $client_id)
             ->first();

            if (empty($callback_2_hours)) {
                $callback = new Callback();
                $callback->create($callback_insert);

                // $this->send_mail($request);
                $data = [
                    'name' => ($request->name) ? $request->name : 'no name',
                    'phone' => $request->phone_number,
                    'send' => ($request->send) ? $request->send : 'no send',
                ];
                JobCallbackMail::dispatch($data);
            }

            $res = ['Ваша заявка принята. Ожидайте звонка...'];

            return view('client_manikur.client_pages.callback-store', ['res' => $res, 'content' => $content]);
        } else {
            return response($res, 200)->header('Content-Type', 'text/plain');
        }
    }
}
