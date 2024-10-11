<?php

namespace App\Traits;

use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

trait GetAppointment
{
    /**
     * @param int $number_of_month - the time period for which you want to get data (number of month)
     */
    protected function get_appointment(int $master_id, int $number_of_month)
    {
        // clear orders older then two year
        // $two_year_ago = Carbon::today()->subYears(2)->toDateString();
        // $clear = DB::table('orders')->where('created_at', '<', $two_year_ago)->delete();

        $data = [];
        $start = Carbon::today()->subMonths($number_of_month)->toDateString();
        $sql = DB::table('orders')
            ->select('status', 'start_dt', 'end_dt')
            ->where('master_id', $master_id)
            ->where('start_dt', '>', $start)
            ->get();
        foreach ($sql as $order) {
            $start_dt = Carbon::createFromFormat('Y-m-d H:i:s', $order->start_dt);
            $end_dt = Carbon::createFromFormat('Y-m-d H:i:s', $order->end_dt);

            $start_dt_date = $start_dt->toDateString();
            $start_st_time = $start_dt->format('H:i');
            $duration = $end_dt->diffInMinutes($start_dt);

            $data[$start_dt_date][$start_st_time] = $duration;
        }

        return $data;
    }
}
