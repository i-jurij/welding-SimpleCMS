@php
if (isset($page_data) && is_array($page_data) && !empty($page_data[0])) {
    // title get from $this_show_method_data['name']
    $title = (!empty($this_show_method_data['name'])) ? $this_show_method_data['name'] : $page_data[0]["title"];
    // page_meta_description get from $data['cat']['description']
    $page_meta_description = (!empty($this_show_method_data['description'])) ? $this_show_method_data['description'] : $page_data[0]["description"];
    $page_meta_keywords = $page_data[0]["keywords"];
    $robots = $page_data[0]["robots"];
    $content['content'] = (!empty($data['cat']) && !empty($data['cat']['name'])) ? $data['cat']['name'] : $page_data[0]["content"];
} else {
    $title = "Title";
    $page_meta_description = "description";
    $page_meta_keywords = "keywords";
    $robots = "INDEX, FOLLOW";
    $content['content'] = "CONTENT FOR DEL IN FUTURE";
}
    $dismiss_signup = ' <p class=\"pad\">\
                            <small>\
                                Если нужно записаться на то же время к другому мастеру или на другую услугу:\
                                <br>отмените запись и запишитесь заново.\
                            </small>\
                        </p>';
@endphp


@extends("layouts/index")

@section("content")

<link rel="stylesheet" href="{{ url()->asset('storage'.DIRECTORY_SEPARATOR.'ppntmt'.DIRECTORY_SEPARATOR.'appointment'.DIRECTORY_SEPARATOR.'css'.DIRECTORY_SEPARATOR.'style.css') }}" />

    @if (!empty($menu)) <p class="content">{{$menu}}</p> @endif

    @if (!empty(session('res')))
        @if (is_array(session('res')))
            <div class="content">
                <h3 class="pad">{{session('res')['client_name']}}</h3>
                <p><b>Вы записались на:</b></p>
                <div class="table_body" style="border-collapse: collapse;">
                    <div class="table_row">
                        <div class="table_cell" style="text-align:right;">{{session('res')['service']}}</div>
                        <div class="table_cell">{{session('res')['price']}} руб.</div>
                    </div>
                    @if (!empty(session('res')['master']))
                    <div class="table_row">
                        <div class="table_cell" style="text-align:right;">Мастер: </div>
                        <div class="table_cell">{{session('res')['master']}}</div>
                    </div>
                    @endif
                    <div class="table_row">
                        <div class="table_cell" style="text-align:right;">Дата,<br /> время:</div>
                        <div class="table_cell">{{session('res')['time']}}</div>
                    </div>
                    <div class="table_row">
                        <div class="table_cell" style="text-align:right;">Ваш номер:</div>
                        <div class="table_cell">{{session('res')['client_phone']}} </div>
                    </div>
                    @if (!empty(session('res')['client_password']))
                    <div class="table_row">
                        <div class="table_cell" style="text-align:right;">Ваш пароль (запомните его, если хотите управлять своими записями):</div>
                        <div class="table_cell">{{session('res')['client_password']}} </div>
                    </div>
                    @endif
                </div>
                <h3>Спасибо за ваш выбор!</h3>
            </div>
        @elseif (is_string(session('res')))
            <div class="content"><p class="error pad">{{session('res')}}</p></div>
        @elseif (session('res') === false)
            <p class="error">
                Warning!<br>
                Data of order have been NOT stored!<br>
                You may have already signed up?
            </p>
            {!!$dismiss_signup!!}
        @endif
    @else
        @if (!empty(session('dismiss')))
            <script type="text/javascript">
                function modal_alert(message_string) {
                    var newDiv = document.createElement('div');
                    newDiv.classList.add('modal')
                    newDiv.id = "alert"
                    newDiv.innerHTML = '<div><p>' + message_string + '</p><button id="alert_ok">OK</button></div>';
                    // Добавляем только что созданный элемент в дерево DOM
                    //document.body.insertBefore(newDiv, my_div);
                    document.querySelector('#zapis_usluga_form').parentNode.insertBefore(newDiv, document.querySelector('#zapis_usluga_form'));
                    // setup body no scroll
                    document.body.style.overflow = 'hidden';

                    let but = document.getElementById('alert_ok')
                    but.focus()
                    but.addEventListener('click', function (ev) {
                    newDiv.remove()
                    // setup body scroll
                    document.body.style.overflow = 'visible';
                    })
                }

                document.addEventListener('DOMContentLoaded', function () {
                    modal_alert("{{session('dismiss')}}");
                });
            </script>
        @endif
        <div class="content">
        @if (!empty($data['serv']))
            <form method="post" action="" id="zapis_usluga_form" class="form_zapis_usluga">
                @csrf
                <div class="choice" id="give_a_phone">
                    <div class="" id="form_phone">
                    <h3 class="back shad rad pad">Введите свое имя и номер телефона для связи</h3>
                        <div class="form-group pad margin_bottom_1rem">
                            <div class="">
                                <div class="error" id="phone_error"><small></small></div>
                                <label class="zapis_usluga">
                                    <p class="pad">Ваше имя (одно слово, только буквы):</p>
                                    <input type="text" title="Ваше имя (одно слово, только буквы)" placeholder="Не обязательно заполнять" pattern="^([а-яА-ЯёЁa-zA-Z]+)?$" name="zapis_name" id="zapis_name" maxlength="255" value="{{ old('name') }}" />
                                </label>
                                <br>
                                <input type="text" placeholder="Ваша фамилия" name="last_name" id="last_name" maxlength="50" />
                                <p class="error" id="tel_mes"></p>
                                <label class="zapis_usluga">
                                    <p class="pad">Номер телефона для связи с вами:</p>
                                    <input type="tel" name="zapis_phone_number"  id="number" class="number"
                                    title="Формат: +7 999 999 99 99" placeholder="+7 ___ ___ __ __"
                                    minlength="6" maxlength="17"
                                    pattern="^(\+?(7|8|38))[ ]{0,1}s?[\(]{0,1}?\d{3}[\)]{0,1}s?[\- ]{0,1}s?\d{1}[\- ]{0,1}?\d{1}s?[\- ]{0,1}?\d{1}s?[\- ]{0,1}?\d{1}s?[\- ]{0,1}?\d{1}s?[\- ]{0,1}?\d{1}s?[\- ]{0,1}?\d{1}s?[\- ]{0,1}?"
                                    value="{{ old('phone_number') }}"
                                    required />
                                </label>
                                <br>
                                <label class="zapis_usluga display_none" id="zapis_password_label">
                                    <p class="pad">Введите пароль:</small></p>
                                    <input type="text" title="Пароль (для управления записями)" placeholder="Пароль" name="client_password" id="client_password" minlength="8" maxlength="255" />
                                </label>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="choice display_none" id="services_choice">
                    <h3 class="back shad rad pad margin_rlb1">Выберите услугу</h3>
                    <div class="zapis_usluga page_buttons">
                        @foreach ($data['serv'] as $page => $cat_arr)
                            <button type="button" class="buttons zapis_usluga_buttons masters_edit vertaligntop" id="{{translit_to_lat(sanitize($page))}}">
                                <img class="" src="{{asset('storage'.DIRECTORY_SEPARATOR.$data[$page])}}" alt="{{$page}} image" />
                                {{$page}}
                            </button>
                        @endforeach
                    </div>
                    <div class="zapis_usluga zapis_usluga_spisok">
                        @foreach ($data['serv'] as $page => $cat_arr)
                            <div class="uslugi display_none" id="div{{translit_to_lat(sanitize($page))}}" >
                                @foreach ($cat_arr as $cat_name => $serv_arr)
                                    <div class="">
                                    @if ($cat_name !== 'page_serv')
                                        @foreach ($serv_arr as $serv_name => $serv_duration)
                                            @php
                                                $id = translit_to_lat(sanitize($serv_name)).'plus'.$serv_duration;
                                                list($price, $duration, $serv_id) = explode('-', $serv_duration);
                                            @endphp
                                            <label class="custom-checkbox back text_left" for="{{$id}}">
                                                <input
                                                    type="radio"
                                                    name="usluga"
                                                    value="{{$serv_id}}"
                                                    id="{{$id}}"
                                                />
                                                <span>{{$page}},<br>{{$cat_name}}: {{$serv_name}},<br>{{$price}} руб.</span>
                                            </label>
                                        @endforeach
                                    @elseif ($cat_name == 'page_serv')
                                        @foreach ($serv_arr as $serv_name => $serv_duration)
                                            @php
                                                $id = translit_to_lat(sanitize($serv_name)).'plus'.(int) $serv_duration;
                                                list($price, $duration, $serv_id) = explode('-', $serv_duration);
                                                $cat_name = 'page_serv';
                                            @endphp
                                            <label class="custom-checkbox back text_left" for="{{$id}}">
                                                <input
                                                    type="radio"
                                                    name="usluga"
                                                    value="{{$serv_id}}"
                                                    id="{{$id}}"
                                                />
                                                <span>{{$page}},<br>{{$serv_name}},<br>{{$price}} руб.</span>
                                            </label>
                                        @endforeach
                                    @endif

                                    </div>
                                @endforeach
                            </div>
                        @endforeach
                    </div>
                </div>

                <div class="choice display_none" id="master_choice"></div>

                <h3 class="back shad rad pad margin_rlb1 display_none" id="timeh3">Выберите время</h3>
                <div class="choice display_none margin_bottom_1rem" id="time_choice"></div>
                <div class="display_none" id="occupied"></div>
                <div class="choice display_none" id="zapis_end"></div>
            </form>

            <div class="zapis_usluga margin_bottom_1rem" id="buttons_div">
                <button class="buttons" id="dismiss_order" disabled>Ваши записи</button>
                <button type="button" class="buttons" id="button_back" value="" disabled >Назад</button>
                <button type="button" class="buttons" id="button_next" value="" disabled >Записаться</button>
            </div>

        @else
            <div class="content"><p>Список услуг пуст.</p></div>
        @endif
        </div>
    @endif

<script src="{{ url()->asset('storage'.DIRECTORY_SEPARATOR.'ppntmt'.DIRECTORY_SEPARATOR.'appointment'.DIRECTORY_SEPARATOR.'js'.DIRECTORY_SEPARATOR.'appointment.js')}}"></script>
<script type="text/javascript">
document.addEventListener('DOMContentLoaded', function () {
    var div_id = "time_choice"
    var div_short = document.querySelector('#' + div_id)
    var div_sched = document.querySelector('#' + div_id)
    var div_month = document.querySelector('#' + div_id)

    function modal_alert(message_string) {
          var newDiv = document.createElement('div');
          newDiv.classList.add('modal')
          newDiv.id = "alert"
          newDiv.innerHTML = '<div><p>' + message_string + '</p><button id="alert_ok">OK</button></div>';
          // Добавляем только что созданный элемент в дерево DOM
          if (!!div_month) {
            my_div = div_month
          }
          if (!!div_short) {
            my_div = div_short
          }
          //document.body.insertBefore(newDiv, my_div);
          document.querySelector('#time_choice').parentNode.insertBefore(newDiv, my_div);
          // setup body no scroll
          document.body.style.overflow = 'hidden';

          let but = document.getElementById('alert_ok')
          but.focus()
          but.addEventListener('click', function (ev) {
            newDiv.remove()
          // setup body scroll
          document.body.style.overflow = 'visible';
          })
        }


    function scrolltobuttonnext(radioinputselector) {
        $(radioinputselector).on('change', function(){
            if ( $(radioinputselector+':checked').length > 0 ) {
                $('html, body').animate({
                scrollTop: $("#buttons_div").offset().top
                }, 500);
                $('#button_next').focus();
            }
        });
    }

    function capitalizeFirstLetter(string) {
        return string.charAt(0).toUpperCase() + string.slice(1);
    }

    //get name of day of week
    function getDayName(dateStr, locale, long) {
        if (!dateStr) dateStr = new Date()
        if (!long) long = "long"
        if (!locale) locale = "ru-RU"
        var date = new Date(dateStr);
        return date.toLocaleDateString(locale, { weekday: long });
    }

    function pad(n) {
        return n<10 ? '0'+n : n;
    }

    function my_date_string(timestamp) {
        let cyrnameofmonth = ['января', 'февраля', 'марта', 'апреля', 'мая', 'июня', 'июля', 'августа', 'сентября', 'октября', 'ноября', 'декабря'];
        const jsDateTime = new Date(timestamp);
        const day = capitalizeFirstLetter(getDayName(jsDateTime));
        const day_of_month = jsDateTime.getDate();
        const month = cyrnameofmonth[jsDateTime.getMonth()];
        const year = jsDateTime.getFullYear();
        const hours = jsDateTime.getHours();
        const minutes = jsDateTime.getMinutes();

        return day+', '+day_of_month+' '+month+' '+year+', '+pad(hours)+':'+pad(minutes);
    }

    function escapeHtml(unsafe) {
        return unsafe
            .replace(/&/g, "&amp;")
            .replace(/</g, "&lt;")
            .replace(/>/g, "&gt;")
            .replace(/"/g, "&quot;")
            .replace(/'/g, "&#039;");
    }

    function validate_data() {
        //$(this).val('zapis_sql').html('Записаться!');
        let phone = $('#give_a_phone input[type="tel"]').val().replace(/ /g, '\u00a0');

        //validation inputs
        let res = {};
        let name_regex = new RegExp('\^\(\[а\-яА\-ЯёЁa\-zA\-Z\]\+\)\?\$');
        let client_name = escapeHtml($('form#zapis_usluga_form input[name="zapis_name"]').val());

        let phone_regex = new RegExp("\^\(\\\+\?\(7\|8\|38\)\)\[ \]\{0,1\}s\?\[\\\(\]\{0,1\}\?\\d\{3\}\[\\\)\]\{0,1\}s\?\[\\\- \]\{0,1\}s\?\\d\{1\}\[\\\- \]\{0,1\}\?\\d\{1\}s\?\[\\\- \]\{0,1\}\?\\d\{1\}s\?\[\\\- \]\{0,1\}\?\\d\{1\}s\?\[\\\- \]\{0,1\}\?\\d\{1\}s\?\[\\\- \]\{0,1\}\?\\d\{1\}s\?\[\\\- \]\{0,1\}\?\\d\{1\}s\?\[\\\- \]\{0,1\}\?");
        let client_phone = escapeHtml($('form#zapis_usluga_form input[name="zapis_phone_number"]').val());

        if (name_regex.test(client_name)) {
            res.client_name = true;
        } else {
            res.client_name = false;
            res.error = '<p class="error pad" >Имя должно состоять только из букв.</p>';
        }
        if (phone_regex.test(client_phone)) {
            res.client_phone = true;
        } else {
            res.client_phone = false;
            res.error += '<p class="error pad" >Неверно введен номер телефона.</p>';
        }

        return res;
    }

$(function() {
    let dismiss_order = $('#dismiss_order');
    if (dismiss_order) {
        dismiss_order.click(function() {

            $('#button_next').hide();
            $('#button_back').hide();
            dismiss_order.html('Список записей');
            res = validate_data();

            let client_password = $('#client_password');
            if ( client_password.length ) {
                password = client_password.val();
            }

            if (res.client_name && res.client_phone && !password ) {
                let zapis_password_label = $('#zapis_password_label');
                zapis_password_label.show();
            }

            if (res.client_name && res.client_phone && password ) {
                let input_dismiss = '<input type="hidden" name="dismiss_order" value="true" />';
                $('form#zapis_usluga_form')
                    .attr('action', '{{url()->route("client.signup.list")}}')
                    .append(input_dismiss)
                    .submit();
            }

            if (res.error) {
                $('#phone_error').html(res.error).show();
            }
        });
    }

    //for page choice
    // Найти все узлы TD
    var page_buttons=$("#services_choice > .page_buttons > .zapis_usluga_buttons");
    // Добавить событие щелчка для всех TD
    page_buttons.click(function() {
        var button_id = $(this).prop('id');
        $('.uslugi').each(function (index, value){
            let page_id = $(this).prop('id');
            if (page_id == 'div'+button_id) {
                $("#div"+button_id).toggle();
                //$('#services_choice label').show();
            } else {
                $(this).hide();
            }
        });
    });

    $('#give_a_phone input[name="zapis_phone_number"]').on('input', function(){
        $('#button_next').val('services_choice').prop('disabled', false);
        $('#dismiss_order').prop('disabled', false);
    });
    ////////////////////////
    $('#button_next').click(function(){
    if ( $('#give_a_phone input[name="zapis_phone_number"]').val() && $(this).val() == 'services_choice' )
    {
        $('#dismiss_order').hide();
        //$(this).val('zapis_sql').html('Записаться!');

        res = validate_data();
        if (res.client_name && res.client_phone) {
            $('#phone_error').html();
            $('#phone_error').hide();
            $('#give_a_phone').hide();
            $('#services_choice').show();
            $('#button_back').val('give_a_phone').prop('disabled', false);
            $(this).val('master_next').html('Далее');
            scrolltobuttonnext('#services_choice input[type="radio"]');
        } else {
            $('#phone_error').html(res.error).show();
            $('#button_next').prop('disabled', true);
            $('#give_a_phone input[name="zapis_name"]').on('input', function(){
                $('#button_next').val('services_choice').prop('disabled', false);
            });
            $('#dismiss_order').show();
        }
    }
    //if ( $('#services_choice input:checkbox:checked').length > 0 && $(this).val() == 'master_next')
    else if ( $('#services_choice input[type="radio"][name="usluga"]:checked').length > 0 && $(this).val() == 'master_next')
    {
        let service = $('#services_choice input[type="radio"][name="usluga"]:checked').val();
        $('#services_choice').hide();
        $('#master_choice').show();
        $(this).val('time_choice');
        $('#button_back').val('services_choice')

        $.ajax({
            url: '<?php echo url('/'); ?>/signup/masters',
    		method: 'post',
    		dataType: 'json',
    		data: {
                "_token": "{{ csrf_token() }}",
                'serv_id': service
            },
    		success: function(result){
                if (result.masters.length > 0) {
                    let mst = '<h3 class="back shad rad pad margin_rlb1">Выберите специалиста</h3>\
                                <div class="radio-group flex">';

                        result.masters.forEach(element => {
                            if (element.data_uvoln == '' || element.data_uvoln == null) {
                                mst += '<article class="main_section_article radio" data-value="'+element.id+'">\
                                    <div class="main_section_article_imgdiv" style="background-color: var(--bgcolor-content);">\
                                        <img src="{{asset("storage")}}/'+element.master_photo+'" alt="Фото '+element.master_fam+'" class="main_section_article_imgdiv_img" />\
                                    </div>\
                                    <div class="main_section_article_content margin_top_1rem">\
                                        <h3 id="'+element.id+'">'+element.master_name+' '+element.master_fam+'</h3>\
                                    </div>\
                                </article>';
                            }
                    });
                    mst += '<input type="hidden" id="master" name="master" />\
                            </div>';
                    $('#master_choice').html(mst);

                    //for master_choice
                    $('.radio-group .radio').click(function(){
                        $(this).parent().find('.radio').removeClass('selected');
                        $(this).addClass('selected');
                        var val = $(this).attr('data-value');
                        $(this).parent().find('#master').val(val);

                        $('html, body').animate({
                            scrollTop: $("#buttons_div").offset().top
                        }, 500);
                        $('#button_next').focus();
                    });
                } else {
                    $('#master_choice').html('<p class="pad">No masters for this service available.</p>');
                    //click event on button next
                    setTimeout(function(){
                        $("#button_next").click();
                    }, 10);
                }

    		},
            error: function(data) {
                $('#master_choice').html('<p class="pad">Извините, где-то возникла ошибка :(</p>');
            },
            cache: false
    	});
    }
    else if ( ( $('#master_choice #master').val() || $('#master_choice').html() == '<p class="pad">No masters for this service available.</p>') && $(this).val() == 'time_choice' )
    {
        $('#master_choice').hide();
        $('#timeh3').show();
        $('#time_choice').show();
        $(this).val('zapis_end');

        if ($('#master_choice').html() == '<p class="pad">No masters for this service available.</p>') {
                $('#button_back').val('services_choice');
        } else {
            $('#button_back').val('master_choice');
        }

        //get appoinment by master
        let master = $('#master_choice #master').val();
        let service = $('#services_choice input[type="radio"][name="usluga"]:checked').val();
        //post sql query to db (request to post route url)

        // CALL TO APPOINTMENT SCRIPT
        appointment('short', '/signup/time', service, master, "{{csrf_token()}}");
        $('html, body').animate({
            scrollTop: $(".title").offset().top
        }, 500);
        //scrolltobuttonnext('#time_choice input[name="time"]');
    }
    else if ( $('#time_choice input[name="time"]:checked').length && $(this).val() == 'zapis_end' )
    {
        $(this).val('zapis_sql').html('Записаться!');

        let client_name = $('input[name="zapis_name"]').val();
        let client_phone = $('input[name="zapis_phone_number"]').val();
        let service = Number($('input[name="usluga"]:checked').val());
        let master = Number($('#master_choice #master').val());
        let time = Number($('#time_choice input[type="radio"][name="time"]:checked').val());
        $.ajax({
            url: '<?php echo url('/'); ?>/signup/check',
    		method: 'post',
    		dataType: 'json',

    		data: {
                "_token": "{{ csrf_token() }}",
                'zapis_phone_number': client_phone,
                'master': master,
                'usluga': service,
                'time': time
            },
    		success: function(responce){
                if (responce.hasOwnProperty('res') && responce.res == true) {
                    //client can sign up
                    if (responce.hasOwnProperty('master_data')) {
                        master_photo = (responce.master_data.hasOwnProperty('master_photo')) ? responce.master_data.master_photo : '';
                        master_name = (responce.master_data.hasOwnProperty('master_name')) ? responce.master_data.master_name : '';
                        sec_name = (responce.master_data.hasOwnProperty('sec_name')) ? responce.master_data.sec_name : '';
                        master_fam = (responce.master_data.hasOwnProperty('master_fam')) ? responce.master_data.master_fam : '';
                        master_info = master_name+' '+sec_name+' '+master_fam;
                        mp = ' <img \
                                    src="{{asset("storage")}}/'+master_photo+'" \
                                    alt="Photo '+master+'" \
                                    style="max-height:10rem;"\
                                />';
                        mmm = ' <div class="table_row">\
                                    <div class="table_cell text_right" style="vertical-align:top;">Мастер: </div>\
                                    <div class="table_cell text_left">'
                                        +mp+master_info+
                                    '</div>\
                                </div>';
                    } else {
                        mmm = '';
                    }

                    //$('#occupied').hide();
                    $('#zapis_end').show().addClass('margin_rlb1').html( '<h3 class="pad">'+client_name+' </h3>\
                                    <p id="zap_na">Вы записываетесь на:</p>\
                                    <div class="table_body text_left div_center" >\
                                        <div class="table_row">\
                                            <div class="table_cell text_right">Услуга:</div>\
                                            <div class="table_cell text_left">'
                                                +$("input:radio[name=\"usluga\"]:checked").next('span').html().replace('<br>', ' ').split('&emsp;').join(' ')+
                                            '</div>\
                                        </div>'
                                        +mmm+
                                        '<div class="table_row">\
                                            <div class="table_cell text_right">Дата, время:</div>\
                                            <div class="table_cell text_left">'+my_date_string(time)+'</div>\
                                        </div>\
                                        <div class="table_row">\
                                            <div class="table_cell text_right">Ваш номер:</div>\
                                            <div class="table_cell text_left">'+client_phone+' </div>\
                                        </div>\
                                        <div class="table_row" id="client_password_div">\
                                            <div class="table_cell text_right">Придумайте пароль <br>для доступа к записям <br>длиной больше 8 символов.</div>\
                                            <div class="table_cell text_left">\
                                                <input type="text" title="Пароль (для управления записями)" placeholder="Не обязательно" name="client_password_first" id="client_password_first" minlength="8" maxlength="255" />\
                                            </div>\
                                        </div>\
                                    </div>');
                } else {
                    //client can not sign up
                    if (responce.hasOwnProperty('client_signup')) {
                        order_id = (responce.client_signup.hasOwnProperty('order_id')) ? responce.client_signup.order_id : '';
                        time_data = (responce.client_signup.hasOwnProperty('time')) ? responce.client_signup.time : '';
                        master_data = (responce.client_signup.hasOwnProperty('master')) ? responce.client_signup.master : '';
                        service_data = (responce.client_signup.hasOwnProperty('service')) ? responce.client_signup.service : '';
                    }

                    $('#button_next').val('zapis_sql').html('Отменить запись');
                    //$("form#zapis_usluga_form")[0].reset();
                    $('#button_back').focus();
                    // add input with dismiss input
                    $('#time_choice').after('<input type="hidden" name="dismiss" value="'+order_id+'" />');


                    let client_signup = '<p style="margin: 0 auto;">Вы уже записаны на:</p>\
                            <div class="table_body" style="border-collapse: collapse;">\
                                <div class="table_row">\
                                    <div class="table_cell" style="text-align:right;">Дата, время:</div>\
                                    <div class="table_cell">'+my_date_string(time_data)+'</div>\
                                </div>\
                                <div class="table_row">\
                                    <div class="table_cell" style="text-align:right;">Услуга:</div>\
                                    <div class="table_cell">'+service_data+' руб.</div>\
                                </div>\
                                <div class="table_row">\
                                    <div class="table_cell" style="text-align:right;">Мастер:</div>\
                                    <div class="table_cell">'+master_data+'</div>\
                                </div>\
                            </div>';
                    let dismiss_signup = "{!!$dismiss_signup!!}";

                    if (responce.hasOwnProperty('client_signup')) {
                        $('#zapis_end').show().addClass('margin_rlb1').html(client_signup+dismiss_signup);
                    }

                    if (responce.hasOwnProperty('master_busy')) {
                        $('#zapis_end').show().addClass('margin_rlb1').html( "<p class=\"pad\">"+my_date_string(time)+"\
                                    <br /> недавно были заняты другим клиентом.<br />\
                                    Выберите, пожалуйста другое время или другого мастера.\
                                </p>");
                    }

                    if (responce.hasOwnProperty('all_master_busy')) {
                        $('#zapis_end').show().addClass('margin_rlb1').html( "<p class=\"pad\">"+my_date_string(time)+"\
                                    <br /> все мастера заняты.<br />Выберите, пожалуйста другое время.\
                                </p>");
                    }
                }
    		},
            error: function(data) {
                $('#zapis_end').show().addClass('margin_rlb1').html( '<p class="pad">Извините, где-то возникла ошибка :(</p>)');
            },
            cache: false
    	});
        //alert($('.master_datetime input[name="time"]:checked').val());
        $('#timeh3').hide();
        $('#time_choice').hide();
        $('#button_back').val('time_choice');
    } else if ( $(this).val() == 'zapis_sql' )
    {
        // set button next type = submit and action for form = url()->route("client.signup.end")
        $(this).val('zapis_sql').attr("type", "submit").attr("form", "zapis_usluga_form");
        $('form#zapis_usluga_form').attr('action', '{{url()->route("client.signup.end")}}');
        /*
        $.ajax({
            url: '<?php // echo url('/');?>/signup/end',
            method: 'post',
            dataType: 'html',
            data: $('form#zapis_usluga_form').serialize(),
            success: function(data){
                $('#zapis_end').html(data);
                $('#button_back, #button_next').hide();
                //console.dir(data);
            },
            error: function(data) {
                let res = '<p class="error pad">Ошибка передачи данных. Повторите ввод данных, пожалуйста. :(</p>)';
                $('#zapis_end').html(res);
            },
            cache: false
        });
        */
    }
    else
    {
      //alert('Сделайте выбор или введите данные, пожалуйста.');
      modal_alert('Сделайте выбор или введите данные, пожалуйста.');
    }
  });

  $('#button_back').click(function() {
    let choice_div_id = $(this).val();
    $('.choice').each(function(){
      if ( $(this).prop('id') == choice_div_id )
      {
        $('#'+choice_div_id).show();
        if ($(this).prop('id') == 'give_a_phone') {
            $('#button_back').prop('disabled', true);
            $('#button_next').val('services_choice').html('Записаться');
            $('#dismiss_order').show();
        } else if ( $(this).prop('id') == 'services_choice') {
            $('#button_next').val('master_next');
            $('#button_back').val('give_a_phone');
            if ($('#master_choice').html() == '<p class="pad">No masters for this service available.</p>') {
                $('#timeh3').hide();
            }
        } else if ($(this).prop('id') == 'master_choice') {
          $('#button_next').val('time_choice');
          $('#button_back').val('services_choice');
          $('#timeh3').hide();
        } else if ($(this).prop('id') == 'time_choice') {
            let dissmiss_input = $('input[name="dismiss"]');
            if (!!dissmiss_input) {
                dissmiss_input.remove();
            }
            $('#button_next').val('zapis_end');
            if ($('#master_choice').html() == '<p class="pad">No masters for this service available.</p>') {
                $('#button_back').val('services_choice');
            } else {
                $('#button_back').val('master_choice');
            }
            $('#timeh3').show();

            $('#button_next').val('zapis_end').html('Далее').attr('disabled', false).show();
        }else if ($(this).prop('id') == 'zapis_end') {
            $('#button_back').val('time_choice');
          //$('#button_next').val('zapis_sql');
        }
      } else {
        $(this).hide();
      }
    });
  });
});
}, false);
</script>

@stop
