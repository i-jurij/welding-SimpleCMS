<?php

namespace App\Http\Controllers\UserAdminControllers;

use App\Http\Controllers\Controller;
use App\Models\Master;
use App\Models\Order;
use App\Models\Page;
use App\Models\ServiceCategory;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Http\Request;

class SignupController extends Controller
{
    public function list(Request $request)
    {
        $id = (string) auth()->user()->id;
        if (!empty($id)) {
            $master = Master::where('user_id', $id)->whereNull('data_uvoln')->first();
            if (!empty($master->id)) {
                $signup = Order::where('master_id', $master->id)->lengthcalendar()->get();
                $data['by_date'] = $this->getServMas($signup);
                $date = (!empty($request->next)) ? $request->next : ((!empty($request->prev)) ? $request->prev : date('Y-m-d'));
            } else {
                $data = 'No master for this user exists.';
                $date = '';
            }
        } else {
            $data = 'User is no autority.';
            $date = '';
        }

        return view('admin_manikur.user_pages.signup', ['data' => $data, 'dateprevnext' => htmlentities($date)]);
    }

    protected function getServMas(Collection $collection)
    {
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
                    .$service_data['name'].', '
                    .$service_data['duration'].' мин., '
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

            $res[$master][] = [
                'order_id' => $collection[$key]['id'],
                'start_dt' => $collection[$key]['start_dt'],
                'service' => $service,
                'client' => $client,
            ];
        }

        return $res;
    }
}
