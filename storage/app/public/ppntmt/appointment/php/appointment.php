<?php
    $dataobj = ['lehgth_cal' => 14,
                'endtime' => "17:00",
                'tz' => "Europe/Simferopol",
                'org_weekend' => array('Сб' => '14:00', 'Sat' => '14:00', 'Вс' => '', "Sun" => ''),
                'rest_day_time' => [
                    '2022-03-25' => [],
                    '2022-03-23' => ['16:00', '17:00', '18:00'],
                ],
                'holiday' =>  array('1979-09-18', '2005-05-31',),
                'period' => 60,
                'worktime' => ['09:00', '19:00'],
                'lunch' => array("12:00", 40),
                'exist_app_date_time_arr' => [
                    '2022-03-21' => array('11:00' => '', '13:00' => '', '14:30' => null),
                    '2022-03-22' => array('13:00' => '30', '13:30' => '30', '15:00' => 40),
                    '2022-03-23' => ['09:00' => '140'],
                    '2022-03-24' => ['09:00' => '40', '09:40' => '30', '10:10' => '60'],
                ],
                'view_date_format' => 'd.m',
                'view_time_format' => 'H:i'

    ];
    header('Content-Type: application/json');
    echo json_encode( $dataobj );
    exit;
?>
