<?php

namespace App\Http\Controllers\Moder;

use App\Http\Controllers\Controller;
use App\Models\Master;
use Carbon\CarbonImmutable;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class SheduleMasterController extends Controller
{
    use \App\Traits\GetCalendarSettings;
    use \App\Traits\GetRestDayTimes;
    use \App\Traits\GetAppointment;

    /**
     * Display a listing of the resource.
     */
    public function index(Master $masters)
    {
        $m['masters'] = $masters->whereNull('data_uvoln')->get()->toArray();

        return view('admin_manikur.moder_pages.shedule_masters', ['data' => $m]);
    }

    public function edit(Request $request)
    {
        $validated = $request->validate([
            'master_id' => 'required|numeric',
        ]);
        $master_id = (!empty($request->master_id)) ? $request->master_id : null;
        // get restdaytimes by master
        $rest_day_time = $this->get_restdaytimes($master_id) ?? null;
        // get appointment by master
        // $appointment = $this->get_appointment($master_id, 1) ?? null;

        $res = $this->getCalSet();
        $data = [
            'lehgth_cal' => $res['lehgth_cal'],
            'endtime' => $res['endtime'],
            'period' => $res['period'],
            'worktime' => $res['worktime'],
            'lunch' => $res['lunch'],
            'org_weekend' => $res['orgweekends'],
            'holiday' => $res['holidays'],
            'rest_day_time' => $rest_day_time,
            'exist_app_date_time_arr' => null,
            'serv_duration' => '',
        ];

        // return view('admin_manikur.moder_pages.shedule_masters_edit', ['data' => $data]);
        return response()->json($data);
    }

    public function store(Request $request)
    {
        $data = [];

        $validated = $request->validate([
            'master' => 'required|numeric',
        ]);

        if (!empty($request->date)) {
            $validated = $request->validate([
                'date.*' => 'numeric',
            ]);
            foreach ($request->date as $value) {
                $date = CarbonImmutable::createFromTimestamp($value / 1000)->toDateString();
                $insert_date[] = ['master_id' => $request->master, 'date' => $date];
            }
            $sql_insert_date = DB::table('restdaytimes')->insert($insert_date);
            $data['insert_date'] = 'SUCCESS! Masters rest date have been stored.';
        }

        if (!empty($request->daytime)) {
            $validated = $request->validate([
                'daytime.*' => 'numeric',
            ]);
            foreach ($request->daytime as $val) {
                $datetime = CarbonImmutable::createFromTimestamp($val / 1000);
                $insert_daytime[] = ['master_id' => $request->master, 'date' => $datetime->toDateString(), 'time' => $datetime->format('H:i')];
            }
            $sql_insert_daytime = DB::table('restdaytimes')->insert($insert_daytime);
            $data['insert_time'] = 'SUCCESS! Masters rest datetime have been stored.';
        }

        if (!empty($request->deldate)) {
            $validated = $request->validate([
                'deldate.*' => 'numeric',
            ]);
            foreach ($request->deldate as $va) {
                $deldate = CarbonImmutable::createFromTimestamp($va / 1000)->toDateString();
                $delete_date[] = $deldate;
            }
            $sql_delete_date = DB::table('restdaytimes')->where('master_id', $request->master)->whereIn('date', $delete_date)->delete();
            $data['dalete_date'] = 'SUCCESS! Masters rest date have been deleted.';
        }

        if (!empty($request->deltime)) {
            $validated = $request->validate([
                'deltime.*' => 'numeric',
            ]);
            foreach ($request->deltime as $v) {
                $deldatetime = CarbonImmutable::createFromTimestamp($v / 1000);
                // $delete_daytime[] = ['master_id' => $request->master, 'date' => $deldatetime->toDateString(), 'time' => $deldatetime->format('H:i')];
                $sql_delete_daytime = DB::table('restdaytimes')
                    ->where('master_id', $request->master)
                    ->where('date', $deldatetime->toDateString())
                    ->where('time', $deldatetime->format('H:i'))
                    ->delete();
            }
            // $sql_delete_daytime = DB::table('restdaytimes')->where($delete_daytime)->delete();
            $data['dalete_time'] = 'SUCCESS! Masters datetimes have been deleted.';
        }

        // return view('admin_manikur.moder_pages.shedule_masters', ['data' => $data]);
        return redirect()->route('admin.masters.shedule')->with('data', $data);
    }
}
