<?php

namespace App\Http\Controllers\Client;

use App\Http\Controllers\Controller;
use App\Models\Client;
use App\Models\Contacts;
use App\Models\Master;
use App\Models\Order;
use App\Models\Page;
use App\Models\Service;
use App\Models\ServiceCategory;
use Carbon\Carbon;
use Carbon\CarbonImmutable;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules;

class SignupController extends Controller
{
    use \App\Traits\GetCalendarSettings;
    use \App\Traits\GetRestDayTimes;
    use \App\Traits\GetAppointment;

    public function index($content, $page_data, $path_array)
    {
        $data = [];
        $thisdata = [];
        $data['service_page'] = Page::where('publish', 'yes')
            ->where('service_page', 'yes')
            ->select('id', 'title', 'img')
            ->get()
            ->toArray();
        foreach ($data['service_page'] as $value) {
            $thisdata[$value['title']] = $value['img'];
        }

        $data['page_cats'] = ServiceCategory::select('id', 'page_id', 'name')
            ->get()
            ->toArray();
        $data['page_cats_serv'] = Service::whereNotNull('category_id')
            ->select('id', 'page_id', 'category_id', 'name', 'price', 'duration')
            ->get()
            ->toArray();
        $data['page_serv'] = Service::whereNull('category_id')
        ->select('id', 'page_id', 'category_id', 'name', 'price', 'duration')
        ->get()
        ->toArray();

        foreach ($data['service_page'] as $page) {
            foreach ($data['page_cats'] as $cat) {
                if ($cat['page_id'] === $page['id']) {
                    foreach ($data['page_cats_serv'] as $cat_serv) {
                        if ($cat_serv['category_id'] === $cat['id']) {
                            $thisdata['serv'][$page['title']][$cat['name']][$cat_serv['name']] = $cat_serv['price'].'-'.$cat_serv['duration'].'-'.$cat_serv['id'];
                        }
                    }
                }
            }
            foreach ($data['page_serv'] as $serv) {
                if ($serv['page_id'] === $page['id']) {
                    $thisdata['serv'][$page['title']]['page_serv'][$serv['name']] = $serv['price'].'-'.$serv['duration'].'-'.$serv['id'];
                }
            }
        }

        return view('client_manikur.client_pages.signup', ['page_data' => $page_data, 'content' => $content, 'data' => $thisdata]);
    }

    public function appoint_masters(Request $request)
    {
        $res = [];
        if (!empty($request->serv_id)) {
            $masters = Service::find($request->serv_id)->masters;
            $res = [
                'masters' => $masters,
            ];
        }

        return json_encode($res);
    }

    protected function check_free_by_all_masters($start_dt)
    {
        // проверим, что будут свободные мастера
        // get count of order by approximately equal start time
        $num_of_order = Order::where('start_dt', '<=', $start_dt)
            ->where('end_dt', '>', $start_dt)
            ->count();
        // get count of masters
        $num_of_masters = Master::count();
        // если общее количество заказов на данное время меньше, чем количество мастеров
        if ($num_of_order < $num_of_masters) {
            return true;
        } else {
            return false;
        }
    }

    protected function check_free_by_master_id($master_id, $start_dt)
    {
        $order = Order::where('master_id', $master_id)
        ->where('start_dt', '<=', $start_dt)
        ->where('end_dt', '>', $start_dt)
        ->first();

        // check if start_dt not = or between srart_dt and end_dt for each item of $order collection
        if (!empty($order->id)) {
            $res = false;
        } else {
            // if this is the chosen time, the master is free
            $res = true;
        }

        return $res;
    }

    /**
     * @param int    $client   id
     * @param string $start_dt - service start time
     *
     * @return array or null
     */
    protected function check_client_other_signup(int $client_id, $start_dt)
    {
        $order_isset = Order::where('client_id', $client_id)
        ->where('start_dt', '<=', $start_dt)
        ->where('end_dt', '>', $start_dt)
        ->first();

        if (!empty($order_isset->id)) {
            $res = $order_isset->with('master')->with('service')->get()->toArray();

            $serv = Service::find($order_isset->service_id);
            $page = $serv->page->title;
            $cat = $serv->category->name;
            $serv_name = $serv->name;
            $serv_price = $serv->price;
            $res = [
                'order_id' => $order_isset->id,
                'time' => $order_isset->start_dt,
                'master' => '',
                'service' => $page.', '.$cat.', '.$serv_name.' - '.$serv_price,
            ];
        } else {
            $res = null;
        }

        return $res;
    }

    protected function check_client_signup(int $client_id, int $service_id, $start_dt, $master_id = null)
    {
        if (!empty($master_id)) {
            $order_isset = Order::where('client_id', $client_id)
            ->where('master_id', $master_id)
            ->where('service_id', $service_id)
            ->where('start_dt', '=', $start_dt)
            ->with('master')
            ->first();
        } else {
            $order_isset = Order::where('client_id', $client_id)
            ->where('service_id', $service_id)
            ->where('start_dt', '=', $start_dt)
            ->with('master')
            ->first();
        }

        if (!empty($order_isset->id)) {
            $master_name = (!empty($order_isset->master['master_name'])) ? $order_isset->master['master_name'] : '';
            $master_fam = (!empty($order_isset->master['master_fam'])) ? $order_isset->master['master_fam'] : '';
            $serv = Service::find($service_id);
            $page = $serv->page->title;
            $cat = $serv->category->name;
            $serv_name = $serv->name;
            $serv_price = $serv->price;
            $res = [
                'order_id' => $order_isset->id,
                'time' => $order_isset->start_dt,
                'master' => $master_name.' '.$master_fam,
                'service' => $page.', '.$cat.', '.$serv_name.' - '.$serv_price,
            ];
        } else {
            $res = null;
        }

        return $res;
    }

    /**
     * @param string $client_phone_number
     *
     * @return int
     */
    protected function get_client_id($client_phone_number, Client $client)
    {
        $cli = $client->where('phone', $client_phone_number)->first();
        if (!empty($cli->id)) {
            return $cli->id;
        } else {
            return 0;
        }
    }

    public function appoint_check(Request $request, Client $client, Master $master)
    {
        $res = null;

        $client_phone_number = $request->zapis_phone_number;
        $client_id = $this->get_client_id($client_phone_number, $client);
        $service_id = my_sanitize_number($request->usluga);
        $time = (int) $request->time / 1000;
        // $start_dt = \DateTime::createFromFormat('d-m-Y H:i:s', '16-06-2023 14:30');
        $start_dt = Carbon::createFromTimestamp($time)->toDateTimeString();
        if (!empty($request->master)) {
            $master_id = my_sanitize_number($request->master);
        }

        // check if client not already sign up
        if (!empty($master_id)) {
            $res = $this->check_client_signup($client_id, $service_id, $start_dt, $master_id);
        } else {
            $res = $this->check_client_signup($client_id, $service_id, $start_dt);
        }
        if (!empty($res)) {
            return json_encode(['res' => false, 'client_signup' => $res]);
        }

        // check if client not sign up on the same time
        $res = $this->check_client_other_signup($client_id, $start_dt);
        if (!empty($res)) {
            return json_encode(['res' => false, 'client_signup' => $res]);
        }

        // check if master is free in this time
        if (!empty($master_id)) {
            if ($this->check_free_by_master_id($master_id, $start_dt)) {
                $master_data = $master->select('master_photo', 'master_name', 'sec_name', 'master_fam')
                    ->where('id', $master_id)
                    ->first()->toArray();
                $res = ['res' => true, 'master_data' => $master_data];
            } else {
                $res = ['res' => false, 'master_busy' => true];
            }
        } else {
            if ($this->check_free_by_all_masters($start_dt)) {
                $res = ['res' => true];
            } else {
                $res = ['res' => false, 'all_master_busy' => true];
            }
        }

        return json_encode($res);
    }

    public function validate_name_phone(Request $request)
    {
        $rules = [
            'zapis_phone_number' => ['required', 'regex:/^(\+?(7|8|38))[ ]{0,1}s?[\(]{0,1}?\d{3}[\)]{0,1}s?[\- ]{0,1}s?\d{1}[\- ]{0,1}?\d{1}s?[\- ]{0,1}?\d{1}s?[\- ]{0,1}?\d{1}s?[\- ]{0,1}?\d{1}s?[\- ]{0,1}?\d{1}s?[\- ]{0,1}?\d{1}s?[\- ]{0,1}?/'],
        ];
        $messages = [
            'zapis_phone_number.regex' => 'The phone number does not match the specified format. Телефонный номер не соответсвует формату +9 999 999 99 99',
        ];
        if (isset($request->zapis_name)) {
            $rules['zapis_name'] = ['regex:/^[а-яА-ЯёЁa-zA-Z]+$/', 'max:255'];
            $messages['zapis_name.max'] = 'The name is too long (255 characters allowed).';
            $messages['zapis_name.regex'] = 'The allowed characters of name is only letters.';
        }

        $this->validate($request, $rules, $messages);
    }

    protected function check_isset_client(Request $request, $newHash)
    {
        $client = Client::where(['phone' => $request->zapis_phone_number], ['name' => $request->zapis_name], ['password' => $newHash])->first();
        if (!empty($client->id)) {
            return [3, $client];
        }
        $client = Client::where(['phone' => $request->zapis_phone_number], ['password' => $newHash])->first();
        if (!empty($client->id)) {
            return [2, $client];
        }
        $client = Client::where(['phone' => $request->zapis_phone_number], ['name' => $request->zapis_name])->first();
        if (!empty($client->id)) {
            return [1, $client];
        }
        $client = Client::where(['phone' => $request->zapis_phone_number])->first();
        if (!empty($client->id)) {
            return [0, $client];
        }
    }

    public function appoint_end(Request $request, Order $order)
    {
        if (!empty($request->dismiss)) {
            $order_dismiss = $order->find($request->dismiss);
            $time = date('H:i d.m.Y', strtotime($order_dismiss->start_dt));
            $order_dismiss->delete();

            return back()->with('dismiss', 'Запись на '.$time.' отменена.');
        } else {
            // if isset $request->last_name - spam bot
            if (empty($request->last_name)) {
                // save client data to table  'clients'
                $this->validate_name_phone($request);

                if (!empty($request->client_password_first)) {
                    $request->validate(['client_password_first' => [Rules\Password::defaults()]]);
                    $newHash = Hash::make($request->client_password_first);
                }
                $client_name = (!empty($request->zapis_name)) ? $request->zapis_name : null;
                $password = (!empty($newHash)) ? $newHash : null;
                // $client = Client::firstOrCreate(['phone' => $request->zapis_phone_number], ['name' => $request->zapis_name], ['password' => $password]);
                $client_check = Client::where(['phone' => $request->zapis_phone_number])->first();
                if (!empty($client_check->id)) {
                    if (!empty($client_check->password)) {
                        if (Hash::check($request->client_password_first, $client_check->password)) {
                            $client = $client_check;
                            if (empty($client->name && !empty($client_name))) {
                                $client->name = $client_name;
                                $client->save();
                            }
                        } else {
                            $res = 'Пароли не совпадают!';

                            return back()->with('res', $res);
                        }
                    } else {
                        if (!empty($request->client_password_first)) {
                            $client = $client_check;
                            $client->password = $newHash;
                            $client->save();
                        }
                        if (empty($client_check->name && !empty($client_name))) {
                            $client = $client_check;
                            $client->name = $client_name;
                            $client->save();
                        }
                    }
                } else {
                    $client = Client::create(['phone' => $request->zapis_phone_number], ['name' => $request->zapis_name], ['password' => $password]);
                }

                // get duration of service and calculate the value of end_dt
                $service = Service::find($request->usluga);
                $dur = $service->duration;
                $start_dt = CarbonImmutable::createFromTimestamp($request->time / 1000);
                // if $dur > 9 - minutes, else hours
                if ($dur > 9) {
                    $end_dt = CarbonImmutable::createFromTimestamp($request->time / 1000)->addMinutes($dur);
                } else {
                    $end_dt = CarbonImmutable::createFromTimestamp($request->time / 1000)->addHours($dur);
                }

                // check if client not sign up on the same time
                $check = $this->check_client_signup($client->id, $request->usluga, $start_dt->toDateTimeString());
                // if client dont have signup (check is empty)
                if (empty($check)) {
                    // присваиваем переменным значения для записи в бд
                    $insert = [
                        'client_id' => $client->id,
                        'service_id' => $request->usluga,
                        'status' => '1',
                        'start_dt' => $start_dt->toDateTimeString(),
                        'end_dt' => $end_dt->toDateTimeString(),
                    ];
                    if (!empty($request->master)) {
                        $insert['master_id'] = $request->master;
                    } else {
                        $insert['master_id'] = null;
                    }

                    $sql_insert = $order->create($insert);

                    if (!empty($sql_insert->id)) {
                        $client_name = ($request->zapis_name) ? $request->zapis_name : '';
                        $page = $service->with('page')->find($request->usluga);
                        $page = $page['page']['title'] ?? '';
                        $cat = $service->with('category')->find($request->usluga);
                        $cat = (!empty($cat['category']['name'])) ? $cat['category']['name'] : '';
                        $serv = $service->name ?? '';
                        $price = $service->price ?? '';

                        if (!empty($request->master)) {
                            $m = Master::find($request->master);
                            $master = $m['master_name'].' '.$m['master_fam'];
                        }
                        $cyr = ['Вс', 'Пн', 'Вт', 'Ср', 'Чт', 'Пт', 'Сб'];
                        $month = ['января', 'февраля', 'марта', 'апреля', 'мая', 'июня', 'июля', 'августа', 'сентября', 'октября', 'ноября', 'декабря'];

                        $res = [
                            'master' => $master ?? '',
                            'client_name' => $client_name,
                            'service' => $page.', '.mb_strtolower($cat, 'UTF-8').' '.mb_strtolower($serv, 'UTF-8'),
                            'price' => $price,
                            'time' => $cyr[$start_dt->dayOfWeek].', '.$start_dt->day.' '.$month[$start_dt->month - 1].' '.$start_dt->format('Y, H:i'),
                            'client_phone' => $request->zapis_phone_number,
                            'client_password' => $request->client_password_first,
                        ];
                    } else {
                        $res = false;
                    }
                } else {
                    $res = false;
                }
            } else {
                $res = false;
            }

            return back()->with('res', $res);
        }
    }

    public function appoint_time(Request $request)
    {
        $master_id = null;
        if (!empty($request->master_id)) {
            $validated = $request->validate([
                'master_id' => 'numeric',
            ]);
            $master_id = $request->master_id;
        }
        $rest_day_time = $this->get_restdaytimes($master_id) ?? null;
        // get appointment by master
        $appointment = $this->get_appointment($master_id, 1) ?? null;

        if (!empty($request->service_id)) {
            $dur = Service::find($request->service_id)->duration;
        }

        // query for get woktime, lunchtime, holiday, weekdays and other
        $data = $this->getCalSet();

        $res = [
            'lehgth_cal' => $data['lehgth_cal'],
            'endtime' => $data['endtime'],
            'period' => $data['period'],
            'worktime' => $data['worktime'],
            'lunch' => $data['lunch'],
            'org_weekend' => $data['orgweekends'],
            'holiday' => $data['holidays'],
            'rest_day_time' => $rest_day_time,
            'exist_app_date_time_arr' => $appointment,
            'serv_duration' => $dur,
        ];

        return response()->json($res);
    }

    public function signup_list(Request $request, Client $client, $res = null)
    {
        $content['contacts'] = Contacts::select('type', 'data')->get()->toArray();
        if (!empty($request->zapis_phone_number)) {
            $this->validate_name_phone($request);
        }

        $client_id = ($request->client_id) ? $request->client_id : $this->get_client_id($request->zapis_phone_number, $client);

        // password validate
        $request->validate(['client_password' => ['required', Rules\Password::defaults()]]);

        $client = Client::find($client_id);
        if (empty($client->id)) {
            return back()->withErrors(['nodata' => 'Данных о записях нет.']);
        }

        if (empty($client->password)) {
            return back()->withErrors(['password' => 'У вас нет сохраненного пароля для доступа к записям.']);
        }

        if (Hash::check($request->client_password, $client->password)) {
            if (Hash::needsRehash($client->password)) {
                $newHash = Hash::make($request->client_password);
                $client->password = $newHash;
                $client->save();
            }

            // get orders data by client
            $signup = Order::where('client_id', $client_id)
            ->where('start_dt', '>', Carbon::now()->toDateTimeString())
            ->whereHas('master', function ($query) {
                return $query->whereNull('data_uvoln');
            })
            ->with('master')
            ->with('service')
            ->orderBy('start_dt')
            ->get();

            foreach ($signup as $key => $value) {
                $signup[$key]['page'] = Page::where('id', $value['service']['page_id'])->value('title');
                $signup[$key]['category'] = ServiceCategory::where('id', $value['service']['category_id'])->value('name');
            }
            function get_res_obj($signup)
            {
                $res_obj = [];
                $date = '';
                foreach ($signup as $elem) {
                    $date = Carbon::parse($elem['start_dt'])->locale('ru_RU')->isoFormat('LL');
                    $start_dt = Carbon::parse($elem['start_dt'])->format('H:i');
                    $order_id = $elem['id'];
                    $master = $elem['master']['master_name'].' '.$elem['master']['sec_name'].' '.$elem['master']['master_fam'].',<br>'.$elem['master']['master_phone_number'];
                    $category = (!empty($elem['category'])) ? $elem['category'].', ' : ' ';
                    $service = $elem['page'].': '.$category.$elem['service']['name'].', '.$elem['service']['duration'].' мин., '.$elem['service']['price'].' руб.';

                    if (!empty($res_obj[$date])) {
                        array_push($res_obj[$date], ['start_dt' => $start_dt, 'order_id' => $order_id, 'service' => $service, 'master' => $master]);
                    } else {
                        $res_obj[$date] = [];
                        array_push($res_obj[$date], ['start_dt' => $start_dt, 'order_id' => $order_id, 'service' => $service, 'master' => $master]);
                    }
                }

                return $res_obj;
            }

            return view('client_manikur.client_pages.signup_edit', ['content' => $content, 'signup' => get_res_obj($signup->toArray()), 'client_id' => $client_id, 'res' => $res]);
        } else {
            return back()->withErrors(['password' => 'Неверный пароль :(']);
        }
    }

    public function signup_remove(Request $request, Client $client)
    {
        // $content['contacts'] = Contacts::select('type', 'data')->get()->toArray();
        if (!empty($request->order_id)) {
            $del_order = Order::destroy($request->order_id);
            if ($del_order > 0) {
                $res = 'Запись удалена!';
            } else {
                $res = 'Ошибка! Запись НЕ удалена или была удалена ранее!';
            }
        } else {
            $res = 'Ошибка! В запросе нет информации о заказе!';
        }
        // return view('client_manikur.client_pages.signup_res', ['content' => $content, 'res' => $res]);
        return $this->signup_list($request, $client, $res);
    }

    public function signup_edit(Request $request)
    {
        $content['contacts'] = Contacts::select('type', 'data')->get()->toArray();
        $data['edit'] = [];
        if (!empty($request->order_id)) {
            $data = $this->edit_get_order_data($request);
        } else {
            $data['edit']['Error! No order id get in controller.'] = '';
        }

        return view('client_manikur.client_pages.signup_edit', ['content' => $content, 'data' => $data]);
    }

    protected function edit_get_order_data(Request $request)
    {
        $signup = Order::with('service')->with('master')->with('client')->find($request->order_id)->toArray();
        if (!empty($signup['id'])) {
            $page = Page::find($signup['service']['page_id'])->title;
            if (!empty($signup['service']['category_id'])) {
                $category_data = ServiceCategory::find($signup['service']['category_id'])->name;
            }
            $category = (!empty($category_data)) ? $category_data.', ' : '';
            $signup['service'] = $page.', '
                .$category
                .$signup['service']['name'].',<br>'
                .$signup['service']['duration'].' мин.,<br>'
                .$signup['service']['price'].' руб.';

            $data['edit'] = $signup;
        } else {
            $data['edit']['.'] = '';
        }

        return $data;
    }

    public function signup_store(Request $request, Order $order)
    {
        $content['contacts'] = Contacts::select('type', 'data')->get()->toArray();
        $data = '';
        if (!empty($request->start_dt && $request->serv_dur)) {
            $start_dt = CarbonImmutable::createFromTimestamp($request->start_dt / 1000);
            // if $dur > 9 - minutes, else hours
            if ($request->serv_dur > 9) {
                $dur = (int) $request->serv_dur;
                $end_dt = CarbonImmutable::createFromTimestamp($request->start_dt / 1000)->addMinutes($dur);
            } else {
                $end_dt = CarbonImmutable::createFromTimestamp($request->start_dt / 1000)->addHours($request->serv_dur);
            }

            if ($order->where('id', $request->order_id)->update(['start_dt' => $start_dt, 'End_dt' => $end_dt])) {
                $data = $start_dt;
            } else {
                $data = 'ERROR! Time not updated.';
            }
        }

        if (!empty($request->master_id)) {
            // update master_id in order
            if ($order->where('id', $request->order_id)->update(['master_id' => $request->master_id])) {
                $ord = $order->find($request->order_id);
                $data = $ord->master;
            } else {
                $data = 'ERROR ! Master not updated.';
            }
        }

        return response()->json($data);
    }

    public function get_masters(Request $request)
    {
        if (!empty($request->service_id) && !empty($request->start_dt)) {
            $start_dt = Carbon::parse($request->start_dt)->toDateTime();
            $service = Service::find($request->service_id);
            foreach ($service->masters as $master) {
                if (empty($master->data_uvoln)) {
                    $order = Order::where('master_id', $master->id)
                    ->where('start_dt', '<=', $start_dt)
                    ->where('end_dt', '>', $start_dt)
                    ->first();

                    // check if start_dt not = or between srart_dt and end_dt for each item of $order collection
                    if (empty($order->id)) {
                        $data[] = $master;
                    }
                }
            }
        } else {
            $data = 'There is no necessary data in the request';
        }

        return response()->json($data);
    }
}
