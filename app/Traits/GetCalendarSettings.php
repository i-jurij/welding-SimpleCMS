<?php

namespace App\Traits;

use Illuminate\Support\Facades\DB;

trait GetCalendarSettings
{
    public function getCalSet()
    {
        // get data from orgworktimeset orgweekends holidays
        $sets = DB::table('orgworktimesets')->find(1);
        foreach ($sets as $key => $value) {
            $data[$key] = $value;
        }
        $data['worktime'] = [$data['work_start'], $data['work_end']];
        $data['lunch'] = [$data['lunch_time'], $data['lunch_duration']];
        unset($data['updated_at'], $data['created_at'], $data['id'], $data['work_start'], $data['work_end'], $data['lunch_time'], $data['lunch_duration']);

        $we = DB::table('orgweekends')->get();
        foreach ($we as $val) {
            $data['orgweekends'][$val->name_of_day_of_week] = $val->time;
        }

        $hol = DB::table('holidays')->get();
        foreach ($hol as $va) {
            $data['holidays'][] = $va->date;
        }

        return $data;
    }
}
