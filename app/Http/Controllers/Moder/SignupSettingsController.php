<?php

namespace App\Http\Controllers\Moder;

use App\Http\Controllers\Controller;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class SignupSettingsController extends Controller
{
    public function settings()
    {
        return view('admin_manikur.moder_pages.signup_settings', ['data' => 'signup settings']);
    }

    public function store(Request $request)
    {
        $data['res'] = [];
        $insert = [];
        $rules = [
            'endtime' => ["regex:/^\d{2}:\d{2}$/"],
            'length' => ["regex:/^(\d+)?$/", 'max:366'],
            'period' => ["regex:/^(\d+)?$/", 'max:360'],
            'lunch_time' => ["regex:/^\d{2}:\d{2}$/"],
            'lunch_duration' => ["regex:/^(\d+)?$/", 'max:240'],
            'work_start' => ["regex:/^\d{2}:\d{2}$/"],
            'work_end' => ["regex:/^\d{2}:\d{2}$/"],
            'weekend.*' => ['regex:/^[a-zA-Zа-яА-ЯёЁ]{2,}$/', 'max:4'],
        ];
        $messages = [
            'endtime.regex' => ['Type of inputs field "End time" must be in format hh:mm eg 17:00.'],
            'length.regex' => ['Type of inputs field "Calendar length" must be digits eg 14.'],
            'period.regex' => ['Type of inputs field "Period" must be digits eg 60.'],
            'lunch_time.regex' => ['Type of inputs field "Lunch time" must be in format hh:mm eg 17:00.'],
            'lunch_duration.regex' => ['Type of inputs field "Lunch duration" must be digits eg 60.'],
            'work_start.regex' => ['Type of inputs field "End time" must be in format hh:mm eg 17:00.'],
            'work_end.regex' => ['Type of inputs field "End time" must be in format hh:mm eg 17:00.'],
            'weekend.*.regex' => ['Type of inputs field "Weekend days name" must be short name of day of week eg Sat.'],
        ];
        $request->validate($rules, $messages);

        if (!empty($request->endtime)) {
            $insert['endtime'] = $request->endtime;
        }

        if (!empty($request->length)) {
            $insert['lehgth_cal'] = $request->length;
        }

        if (!empty($request->period)) {
            $insert['period'] = $request->period;
        }

        if (!empty($request->lunch_time)) {
            $insert['lunch_time'] = $request->lunch_time;
        }

        if (!empty($request->lunch_duration)) {
            $insert['lunch_duration'] = $request->lunch_duration;
        }

        if (!empty($request->work_start)) {
            $insert['work_start'] = $request->work_start;
        }

        if (!empty($request->work_end)) {
            $insert['work_end'] = $request->work_end;
        }

        if (!empty($insert)) {
            DB::table('orgworktimesets')->truncate();
            $new_orgworktimesets = DB::table('orgworktimesets')->insert($insert);
            $data['res'][] = 'Settings for calendar have been stored!';
        }

        if (!empty($request->weekend)) {
            if (is_array($request->weekend)) {
                foreach ($request->weekend as $key => $value) {
                    $weekend_insert[$key]['name_of_day_of_week'] = $value;
                    if (!empty($request->weekend_start)) {
                        if (!empty($request->weekend_start[$key]) && preg_match('/^\d{2}:\d{2}$/', $request->weekend_start[$key])) {
                            $weekend_insert[$key]['time'] = $request->weekend_start[$key];
                        } else {
                            $weekend_insert[$key]['time'] = '';
                        }
                    }
                }
            }
            DB::table('orgweekends')->truncate();
            // sql insert
            $new_orgweekends = DB::table('orgweekends')->insert($weekend_insert);
            $data['res'][] = 'Weekends have been stored!';
        }

        if (!empty($request->dpholidays)) {
            // validate
            $rules = [
                'dpholidays' => ["regex:/^(\d{4})?$/"],
            ];
            $messages = [
                'dpholidays.regex' => ['Type of inputs field "Holidays from remote server" must be year in format YYYY eg 2023.'],
            ];
            $request->validate($rules, $messages);

            $calendar = simplexml_load_file('http://xmlcalendar.ru/data/ru/'.$request->dpholidays.'/calendar.xml');
            if ($calendar !== false) {
                $calendar = $calendar->days->day;

                // все праздники за текущий год
                foreach ($calendar as $day) {
                    $d = (array) $day->attributes()->d;
                    $d = $d[0];
                    $d = date('Y').'-'.substr($d, 0, 2).'-'.substr($d, 3, 2);
                    // не считая короткие дни
                    if ($day->attributes()->t == 1) {
                        $arHolidays[] = $d;
                    }
                }
            } else {
                $data['res'][] = 'ERROR! Holidays from remote server have been NOT download!';
            }
        }

        if (!empty($request->holidays)) {
            $rules = [
                'holidays' => ['regex:/([0-9]{4}-[0-9]{1,2}-[0-9]{1,2}){1}([ ][0-9]{4}-[0-9]{1,2}-[0-9]{1,2})+/'],
            ];
            $messages = [
                'holidays.regex' => ['Type of inputs field must be year in format YYYY-mm-dd separated by a space eg 2023-01-01 2023-01-02.'],
            ];
            $request->validate($rules, $messages);

            $holidays = explode(' ', $request->holidays);
        }

        if (!empty($arHolidays) && !empty($holidays)) {
            $pre_holidays = array_unique(array_merge($arHolidays, $holidays));
            foreach ($pre_holidays as $value) {
                $res_holidays[]['date'] = $value;
            }
        } elseif (empty($arHolidays) && !empty($holidays)) {
            foreach ($holidays as $value) {
                $res_holidays[]['date'] = $value;
            }
        } elseif (!empty($arHolidays) && empty($holidays)) {
            foreach ($arHolidays as $value) {
                $res_holidays[]['date'] = $value;
            }
        }

        if (!empty($res_holidays)) {
            // sql del old (2 year holidays)
            $old_year = $request->dpholidays - 2;
            $old = DB::table('holidays')
                    ->where('date', '<', Carbon::create($old_year, 12, 30, 01, 01, 01)->toDateString())
                    ->delete();
            // sql insert
            $new = DB::table('holidays')->insertOrIgnore($res_holidays);
            $data['res'][] = 'Holidays have been stored!';
        } else {
            $data['res'][] = 'Holidays array is empty!';
        }

        return view('admin_manikur.moder_pages.signup_settings', ['data' => $data]);
    }
}
