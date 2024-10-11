<?php

namespace App\Http\Controllers\Moder;

use App\Http\Controllers\Controller;
use App\Models\Client;
use App\Models\Master;
use App\Models\Order;
use App\Models\Page;
use App\Models\Service;
use App\Models\ServiceCategory;
use Carbon\Carbon;
use Carbon\CarbonImmutable;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Http\Request;

class SignupController extends Controller
{
    use \App\Traits\GetCalendarSettings;
    use \App\Traits\GetRestDayTimes;
    use \App\Traits\GetAppointment;

    public function by_date(Request $request)
    {
        $signup = Order::lengthcalendar()->get();
        $data['by_date'] = $this->getServMas($signup);

        $date = (!empty($request->next)) ? $request->next : ((!empty($request->prev)) ? $request->prev : date('Y-m-d'));

        return view('admin_manikur.moder_pages.signup', ['data' => $data, 'dateprevnext' => htmlentities($date)]);
    }

    public function by_master()
    {
        $masters = Master::whereNull('data_uvoln')->get();

        $data['by_master'] = $masters->toArray();

        return view('admin_manikur.moder_pages.signup', ['data' => $data]);
    }

    public function post_by_master(Request $request)
    {
        if (!empty($request->master_id)) {
            $signup = Order::where('master_id', $request->master_id)->lengthcalendar()->get();
            if ($signup->isNotEmpty()) {
                $data['post_by_master'] = $this->getServMas($signup);

                foreach ($data['post_by_master']['work'] as $master => $value) {
                    foreach ($value as $key => $val) {
                        $date = Carbon::createFromFormat('Y-m-d H:i:s', $val['start_dt'])->format('d M Y');
                        $new_data['post_by_master'][$master][$date][] = $val;
                    }
                }
            } else {
                $new_data['post_by_master']['К мастеру нет записей.'] = '';
            }
        } else {
            $new_data['post_by_master']['Error! No master id get in controller.'] = '';
        }

        // return view('admin_manikur.moder_pages.signup', ['data' => $data]);
        return response()->json($new_data);
    }

    public function by_client(Request $request)
    {
        // form for search client by name or phone number
        $clients = Client::all();
        foreach ($clients as $key => $client) {
            $data['by_client'][$key]['id'] = $client->id;
            $data['by_client'][$key]['name'] = $client->name;
            $data['by_client'][$key]['phone'] = $client->phone;
        }

        return view('admin_manikur.moder_pages.signup_by_client_form', ['data' => $data]);
    }

    public function post_by_client(Request $request)
    {
        if (!empty($request->client_id)) {
            $validatedData = $request->validate([
                'client_id' => ['required', 'integer'],
            ]);
        }

        $signup = Order::where('client_id', $request->client_id)
                ->where('start_dt', '>', Carbon::now()->toDateTimeString())
                ->with('master')
                ->with('service')
                ->with('client')
                ->orderBy('start_dt')
                ->get();

        $data['post_by_client']['data'] = $this->sort_pag($signup);
        // return response()->json($data);
        return view('admin_manikur.moder_pages.signup', ['data' => $data]);
    }

    public function past()
    {
        $signup = Order::where('start_dt', '<', Carbon::now()->toDateTimeString())->with('master')->with('service')->with('client')->orderBy('start_dt')->paginate(10);
        $data['list'] = $this->sort_pag($signup);

        return view('admin_manikur.moder_pages.signup', ['data' => $data]);
    }

    public function future()
    {
        $signup = Order::where('start_dt', '>', Carbon::now()->toDateTimeString())->with('master')->with('service')->with('client')->orderBy('start_dt')->paginate(10);
        $data['list'] = $this->sort_pag($signup);

        return view('admin_manikur.moder_pages.signup', ['data' => $data]);
    }

    protected function sort_pag($array)
    {
        foreach ($array as $key => $value) {
            $array[$key]['page'] = Page::where('id', $value['service']['page_id'])->value('title');
            $array[$key]['category'] = ServiceCategory::where('id', $value['service']['category_id'])->value('name');
        }

        return $array;
    }

    public function remove(Request $request)
    {
        if (!empty($request->order_id)) {
            $del_order = Order::destroy($request->order_id);
            if ($del_order > 0) {
                $data['res'] = 'Delete';
            }
        } else {
            $data['res'] = 'Not delete';
        }

        return response()->json($data);
    }

    protected function getServMas(Collection $collection)
    {
        $res = [];
        foreach ($collection as $key => $value) {
            $service_data = $value->service;
            if (!empty($service_data)) {
                $page = Page::find($service_data['page_id'])->title;
                if (!empty($service_data['category_id'])) {
                    $category_data = ServiceCategory::find($service_data['category_id'])->name;
                }
                $category = (!empty($category_data)) ? $category_data.', ' : '';
                $service = $page.', '
                    .$category
                    .$service_data['name'].',<br>'
                    .$service_data['duration'].' мин.,<br>'
                    .$service_data['price'].' руб.';
            } else {
                $service = '';
            }
            $master_data = $value->master;

            $master = $master_data['master_name'].' '
                .$master_data['sec_name'].' '
                .$master_data['master_fam'].'<br>'
                .$master_data['master_phone_number'];

            $client_data = $value->client;
            $client_name = (!empty($client_data['name'])) ? $client_data['name'] : 'noname';
            $client = 'Клиент: '.$client_name.', <span style="white-space:nowrap;"> '.$client_data['phone'].'</span>';

            if (empty($master_data['data_uvoln'])) {
                $work = 'work';
            } else {
                $work = 'dismiss';
            }
            $res[$work][$master][] = [
                'order_id' => $collection[$key]['id'],
                'start_dt' => $collection[$key]['start_dt'],
                'service' => $service,
                'client' => $client,
            ];
        }
        if (!empty($res)) {
            foreach ($res as $masters) {
                foreach ($masters as $master => $signup) {
                    usort($signup, function ($a, $b) {
                        return strtotime($a['start_dt']) - strtotime($b['start_dt']);
                    });
                    $res[$master] = $signup;
                }
            }
        }

        return $res;
    }

    public function edit(Request $request)
    {
        $data['edit'] = [];
        if (!empty($request->order_id)) {
            $data = $this->edit_get_order_data($request);
            $master_id = $data['edit']['master_id'];
            $service_id = $data['edit']['service_id'];
        // $data['res'] = $this->edit_get_master_times($master_id, $service_id);
        } else {
            $data['edit']['Error! No order id get in controller.'] = '';
        }

        return response()->json($data);
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

    protected function get_master_times(Request $request)
    {
        $rest_day_time = $this->get_restdaytimes($request->master_id) ?? null;
        // get appointment by master
        $appointment = $this->get_appointment($request->master_id, 1) ?? null;
        $dur = Service::find($request->service_id)->duration;

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

    public function post_edit(Request $request, Order $order)
    {
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
