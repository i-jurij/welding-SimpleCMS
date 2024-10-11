@php
$title = "Appointment settings";
$page_meta_description = "Appointment settings";
$page_meta_keywords = "Appointment settings";
$robots = "NOINDEX, NOFOLLOW";
@endphp
@extends("layouts/index_admin")

@section("content")
<link rel="stylesheet" href="//code.jquery.com/ui/1.13.2/themes/dark-hive/jquery-ui.css">
<style>
    input[type="number"],
    #datepicker {
        width: 6rem;
    }
    .ui-datepicker-calendar,
    .ui-datepicker-month,
    .ui-datepicker-next,.ui-datepicker-prev,
    .ui-datepicker-current {
    display:none;
    }

    .ui-datepicker-year {
        background-color: var(--bgcolor-content);
        color: inherit;
    }
</style>
    @if (!empty($data['res']))
        <div class="content">
            @if (is_array($data['res']))
                @foreach ($data['res'] as $res)
                    {{$res}}<br>
                @endforeach
            @elseif (is_string($data['res']))
                <p>{{$data['res']}}</p>
            @endif
        </div>
    @else
        <div class="content">
            <p style="margin:0;" id="p_pro">Показать/скрыть справку</p>
            <p class="margin_rlb1 text_left display_none" id="pro">
                <br>
                Заполняйте ВСЕ поля! За исключением праздничных дней (но нужно выбрать один из вариантов: запонить вручную или загрузить с сервера).
                <br><br>
                `endtime = "17:00"`
                <br>
                Время, после которого календарь начинается с завтрашней даты (те запись на сегодня уже недоступна)
                <br>
                The time after which the dates start from tomorrow (those records are no longer available for today)
                <br><br>
                `lehgth_cal = 14`
                <br>
                Количество отображаемых дней для записи
                <br>
                The number of days displayed for an appointment
                <br><br>
                `tz = "Europe/Simferopol"`
                <br>
                Часовой пояс
                <br>
                Timezone
                <br><br>
                `period = 60`
                <br>
                Период (интервал) между временами для записи в минутах (09:00, 10:00, 11:00, ...)
                <br>
                Time interval for an appointment
                <br><br>
                `lunch = "12:00", 60`
                <br>
                Время начала HH:mm и длительность обеденного перерыва в минутах
                <br>
                The start time HH:mm and the duration of the lunch break in minutes
                <br><br>
                `worktime = '09:00', '19:00'`
                <br>
                Рабочее время $worktime[0] - начало, $worktime[1] - конец
                <br>
                Working time $worktime[0] - start, $worktime[1] - end
                <br><br>
                `org_weekend = Сб-14:00, Sat-14:00, Вс, Sun`
                <br>
                Постоянно планируемые в организации выходные, название дня
                ('Пн', 'Вт', 'Ср', 'Чт', 'Пт', 'Сб', 'Вс' ) ('Sun', 'Mon', 'Tue', 'Wed', 'Thu', 'Fri', 'Sat'),
                и через пробел - время, если целый день выходной ничего не пишем,
                если, к примеру, в субботу заканчивается рабочий день в 14.00 - то пишем
                время начала отдыха в 24часовом формате HH:mm
                <br>
                Weekends that are constantly planned in the organization, the key is the name of the day,
                the value is empty if the whole day is off,
                or the start time of the rest in the 24-hour format HH:mm
                <br><br>

                `holiday = '2023-02-23', '2023-03-08', '2023-03-17'`
                <br>
                праздничные дни хоть на 10 лет вперед. Можно ввести вручную через пробел или загрузить с http://xmlcalendar.ru.
                Если данные будут и там и там - в БД запишутся все (кроме дублей).
            </p>
        </div>
        <div class="content">
        <form action="{{url()->route('admin.signup.settings.store')}}" method="post" id="signup_settings_form" class="div_center" style="max-width: 80rem;">
        @csrf
            <table class="table" >
                <colgroup>
                    <col width="45%">
                    <col width="45%">
                </colgroup>
                <tbody>
                    <tr>
                        <td style="text-align:left">Время, после которого календарь начинается с завтрашней даты (17:00)</td>
                        <td class="td text_left">
                            <input type="time" name="endtime" value="17:00" title="End time" pattern="^\d{2}:\d{2}$">
                        </td>
                    </tr>
                    <tr>
                        <td style="text-align:left">Количество отображаемых дней для записи (14)</td>
                        <td class="td text_left" >
                            <input type="number" step="1" name="length" value="14" title="Calendar length">
                        </td>
                    </tr>

                    <tr>
                        <td style="text-align:left">Период (интервал времени для записи, 60)</td>
                        <td class="td text_left" >
                        <input type="number" step="10" name="period" value="60" title="End time">
                        </td>
                    </tr>
                    <tr>
                        <td style="text-align:left">Обеденный перерыв (время начала, длительность в минутах, 12:00 60)</td>
                        <td class="td text_left" >
                            <input type="time" name="lunch_time" value="12:00" title="Lunch_time">
                            <input type="number" step="10" name="lunch_duration" value="60" title="Lunch duration">
                        </td>
                    </tr>
                    <tr>
                        <td style="text-align:left">Рабочее время (время начала, время конца, 09:00 19:00)</td>
                        <td class="td text_left" >
                            <input type="time" name="work_start" value="09:00" title="Work start time">
                            <input type="time" name="work_end" value="19:00" title="Work end time">
                        </td>
                    </tr>
                    <tr>
                        <td style="text-align:left">
                            Выходные дни (название дня и время начала отдыха. Если целый день выходной - поле времени оставьте пустым,
                            например Cб 14:00, Вс)
                        </td>
                        <td class="td text_left" >
                            @php
                                $days = ['Пн', 'Вт', 'Ср', 'Чт', 'Пт', 'Сб', 'Вс'];
                            @endphp
                            @foreach ($days as $key => $day)
                                <label >
                                    <input type="checkbox" name="weekend[{{$key}}]" value="{{$day}}" />
                                    {{$day}}
                                </label>
                                <input type="time" name="weekend_start[{{$key}}]" value="" title="Weekend start time">
                                <br>
                            @endforeach
                        </td>
                    </tr>
                    <tr>
                        <td style="text-align:left"></td>
                        <td class="td text_left" ></td>
                    </tr>
                    <tr>
                        <td style="text-align:left">Праздничные дни (список дат через пробел в формате ГГГГ-мм-дд)</td>
                        <td class="td text_left" >
                            <input
                                style="width:100%;"
                                type="text"
                                name="holidays"
                                value=""
                                placeholder="2023-01-01 2023-02-24 2023-05-09"
                                pattern="([0-9]{4}-[0-9]{1,2}-[0-9]{1,2}){1}([ ][0-9]{4}-[0-9]{1,2}-[0-9]{1,2})+"
                            />
                        </td>
                    </tr>
                    <tr>
                        <td style="text-align:left">Или загрузить праздничные дни с удаленного сервера (укажите год)</td>
                        <td class="td text_left" >
                            <input type="text" id="datepicker" name="dpholidays" value="{{date('Y')}}"/>
                        </td>
                    </tr>
                </tbody>
            </table>

            <div class="form-element mar">
                <button type="submit" form="signup_settings_form" class="buttons" id="signup_settings_submit">Save</button>
                <button type="reset" form="signup_settings_form" class="buttons" id="signup_settings_reset">Reset</button>
            </div>
        </form>
        </div>
    @endif
    <script type="module" src="https://code.jquery.com/ui/1.13.2/jquery-ui.min.js" integrity="sha256-lSjKY0/srUM9BE3dPm+c4fBo1dky2v27Gdjm2uoZaL0=" crossorigin="anonymous"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function () {
        $(document).ready(function() {
            $('#datepicker').datepicker({
                changeYear: true,
                showButtonPanel: true,
                dateFormat: 'yy',
                onClose: function(dateText, inst) {
                    var year = $("#ui-datepicker-div .ui-datepicker-year :selected").val();
                    $(this).datepicker('setDate', new Date(year, 1));
                }
            });
        $(".date-picker-year").focus(function () {
                $(".ui-datepicker-month").hide();
            });
        });
        }, false);
    </script>
@stop
