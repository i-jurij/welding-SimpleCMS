<?php

namespace App\Traits;

use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

trait GetRestDayTimes
{
    protected function get_restdaytimes($id)
    {
        // clear restdaytimes older then two year
        $two_year_ago = Carbon::today()->subYears(2)->toDateString();
        $clear = DB::table('restdaytimes')->where('master_id', $id)->where('date', '<', $two_year_ago)->delete();

        $data = [];
        if (!empty($id)) {
            $sql = DB::table('restdaytimes')->where('master_id', $id)->get();
            foreach ($sql as $value) {
                if (!empty($value->time)) {
                    $data[$value->date][] = $value->time;
                } else {
                    $data[$value->date] = [];
                }
            }
        }

        return $data;
    }
}
