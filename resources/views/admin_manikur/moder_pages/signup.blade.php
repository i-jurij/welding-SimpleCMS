@php
$title = "Sign up";
$page_meta_description = "Appointment of client";
$page_meta_keywords = "Appointment, signup";
$robots = "NOINDEX, NOFOLLOW";
$uv = '';
@endphp
@extends("layouts/index_admin")

@section("content")
<link rel="stylesheet" href="{{ url()->asset('storage'.DIRECTORY_SEPARATOR.'ppntmt'.DIRECTORY_SEPARATOR.'appointment'.DIRECTORY_SEPARATOR.'css'.DIRECTORY_SEPARATOR.'style.css') }}" />

<div class="content">
    @if (!empty($data))
        @if (is_array($data))
            @if (!empty($data['by_date']))
                <?php
                if (!empty($dateprevnext)) {
                    $date = $dateprevnext;
                }
                $prev = date('Y-m-d', strtotime($date.'- 1 days'));
                $next = date('Y-m-d', strtotime($date.'+ 1 days'));
                // $fulldate = date('l d M Y', strtotime($date));
                $dt = \Carbon\Carbon::parse($date)->locale('ru_RU');
                $weekday = $dt->getTranslatedShortDayName();
                $dat = $dt->isoFormat('LL');
                $fulldate = $dat.', '.$weekday;
                ?>
                <p class="margin_rlb1">
                <a href="<?php echo url()->route('admin.signup.by_date'); ?>?prev=<?php echo $prev; ?>" class="back shad rad pad_tb05_rl1 display_inline_block">< </a>
                <span class="back shad rad pad_tb05_rl1 display_inline_block" style="width:15rem;"><?php echo $fulldate; ?></span>
                <a href="<?php echo url()->route('admin.signup.by_date'); ?>?next=<?php echo $next; ?>" class="back shad rad pad_tb05_rl1 display_inline_block"> ></a>
                </p>

                <?php
                $res = '';
                foreach ($data['by_date']['work'] as $master => $signup) {
                    $art = '';
                    foreach ($signup as $value) {
                        if (\Carbon\Carbon::parse($value['start_dt'])->toDateString() === $date) {
                            $carbon_dt = \Carbon\Carbon::parse($value['start_dt']);
                            $time = $carbon_dt->format('H:i');
                            $datet = $carbon_dt->format('d.m.Y');
                            $art .= '<article class="main_section_article" id="'.$value['order_id'].'">
                                          <p>'.$time.'<br>'.$datet.'</p>
                                          <p>'.$value['service'].' </p>
                                          <p>'.$value['client'].'</p>
                                      </article>';
                        }
                    }
                    if (!empty($art)) {
                        $res .= '<div class="back shad rad pad margin_rlb1">';
                        $res .= '<p><b>'.$master.'</b></p>';
                        $res .= $art;
                        $res .= '</div>';
                    }
                }
                echo $res;
                ?>
            @elseif (!empty($data['by_master']))
                <div id="app">
                    <p class="pad">Выберите мастера:</p>
                @foreach ($data['by_master'] as $master)
                    @php
                        $master_name = $master['master_name'].' '
                            .$master['sec_name'].' '
                            .$master['master_fam'].'<br>'
                            .$master['master_phone_number'];
                    @endphp
                    <button
                        type="button"
                        class="buttons masters"
                        id="{{$master['id']}}">{!!$master_name!!}</button>
                @endforeach
                </div>
            @elseif (!empty($data['list']) || !empty($data['post_by_client']))
                <div id="list_wrapper">
                    <div id="js_table"></div>
                    @if (!empty($data['list']))
                        {{ $data['list']->links() }}
                    @endif
                </div>
            @endif
        @elseif (is_string($data))
            <p>{{$data}}</p>
        @endif
    @else
        No data from controller
    @endif
</div>

<script src="{{ url()->asset('storage'.DIRECTORY_SEPARATOR.'ppntmt'.DIRECTORY_SEPARATOR.'appointment'.DIRECTORY_SEPARATOR.'js'.DIRECTORY_SEPARATOR.'appointment.js')}}"></script>

<script>
    window.onload = function() {

        function js_date(datas){
            let day = days_of_week[new Date(datas).getDay()];
            let year = new Date(datas).getFullYear();
            let month = months[new Date(datas).getMonth()];
            let date = new Date(datas).getDate();
            let date_dt = day+', '+pad(date)+' '+month+' '+year;
            return date_dt;
        }
        function pad(n) {
            return n < 10 ? '0' + n : n;
        }
        function capitalizeFirstLetter(string) {
            return string.charAt(0).toUpperCase() + string.slice(1);
        }
        var days_of_week = ['Вс', 'Пн', 'Вт', 'Ср', 'Чт', 'Пт', 'Сб'];
        var months = ['Янв', 'Фев', 'Мар', 'Апр', 'Май', 'Июн', 'Июл', 'Авг', 'Сен', 'Окт', 'Ноя', 'Дек'];

        window.Laravel = { csrfToken: '{{ csrf_token() }}' };

        async function data_from_db(url, enter_data = '') { // or data = {}
            const myHeaders = {
                //'Content-Type': 'application/json'
                'Content-Type': 'application/x-www-form-urlencoded',
                "X-CSRF-TOKEN": Laravel.csrfToken
            };

            const myInit = {
                method: 'POST',
                mode: 'same-origin', // no-cors, *cors, same-origin
                cache: 'no-cache', // *default, no-cache, reload, force-cache, only-if-cached
                credentials: 'same-origin', // include, *same-origin, omit
                headers: myHeaders,
                redirect: 'follow', // manual, *follow, error
                referrerPolicy: 'no-referrer', // no-referrer, *client
                body: enter_data
                // JSON.stringify(data) // body data type must match "Content-Type" header
            };
            const myRequest = new Request(url, myInit);
            const response = await fetch(myRequest);
            const contentType = response.headers.get('content-type');
            //const mytoken = response.headers.get('content-type');
            if (!contentType || !contentType.includes('application/json')) {
                throw new TypeError("Data from server is not JSON!");
            }

            return await response.json();
        }

        function res_obj(page_object){
            page_object['data'].forEach(element => {
                let options = { weekday: 'short', year: 'numeric', month: 'short', day: 'numeric' };
                //let date = capitalizeFirstLetter(new Date(element.start_dt).toLocaleDateString("ru-RU", options));
                const dt = new Date(element.start_dt);
                const year = dt.getFullYear();
                const month = dt.getMonth();
                const day = dt.getDate();
                let date = pad(day)+'.'+pad(month+1)+'.'+year;

                let order_id = element.id;
                let start_dt = pad(new Date(element.start_dt).getHours())
                    +':'+pad(new Date(element.start_dt).getMinutes())
                    +'<br>'+capitalizeFirstLetter(new Date(element.start_dt).toLocaleDateString("ru-RU", options));
                let uvolen = (element.master.data_uvoln == null || element.master.data_uvoln == "") ? '' : '<p style="color:red;">УВОЛЕН!</p>';
                let master = element.master.master_name+' '+element.master.sec_name+' '+element.master.master_fam+',<br>'+element.master.master_phone_number+uvolen;
                let category = (!!element.category) ? element.category+', ' : ' ';
                let service = element.page+': '+category+element.service.name+', '+element.service.duration+' мин., '+element.service.price+' руб.';
                let client_name = (!!element.client.name) ? element.client.name+', ' : '';
                let client = 'Клиент:<br>'+client_name+element.client.phone;

                function arr_push() {
                    res_obj[date][master].push({start_dt: start_dt, order_id: order_id, service: service, client: client});
                }
                function obj_add() {
                    if (!!res_obj[date][master]) {
                        arr_push();
                    } else {
                        res_obj[date][master] = [];
                        arr_push();
                    }
                }

                if (!!res_obj[date]) {
                    obj_add();
                } else {
                    res_obj[date] = {};
                    obj_add();
                }
            });
            return res_obj;
        }

        function inhtml(res_obj){
            inhtml = '<table class="table price_form_table" id="signup_all_list">\
                                    <colgroup>\
                                        <col width="20%">\
                                        <col width="30%">\
                                        <col width="25%">\
                                        <col width="25%">\
                                    </colgroup>\
                                    <tbody>';
            for (const date in res_obj) {
                if (Object.hasOwnProperty.call(res_obj, date)) {
                    const element = res_obj[date];
                    date_p = '<tr><td colspan="4">'+date+'</td></tr>';
                    //js_table.parentNode.insertBefore(newDiv, js_table);
                    //js_table.insertAdjacentHTML( 'afterbegin', date_p );
                    inhtml += date_p;
                    for (const master in element) {
                        if (Object.hasOwnProperty.call(element, master)) {
                            const array = element[master];
                            inhtml += '<tr><td colspan="4">'+master+'</td></tr>';

                            for (let index = 0; index < array.length; index++) {
                                const data = array[index];
                                inhtml += ' <tr>\
                                                            <td id="time_order_id'+data.order_id+'">'
                                                                +data.start_dt+
                                                            '</td>\
                                                            <td>'
                                                                +data.service+
                                                            '</td>\
                                                            <td>'
                                                                +data.client+
                                                            '</td>\
                                                            <td>\
                                                                <button type="button" value="change" class="buttons" id="'+data.order_id+'">Изменить</button>\
                                                                <button type="button" value="delete" class="buttons" id="'+data.order_id+'" >Удалить</button>\
                                                            </td>\
                                                        </tr>';

                            }
                        }
                    }
                    inhtml += '<tr><td colspan="4"></td></tr>';
                }
            }
            inhtml += '</tbody></table>';
            return inhtml ;
        }

        function time_change(order_id, serv_dur, master_id) {
            var newDiv = document.createElement('div');
            newDiv.classList.add('modal')
            newDiv.id = "alert"
            newDiv.innerHTML = '<div><div id="time_choice"></div>\
                                    <p>После выбора даты и времени нажмите "Ок"</p>\
                                    <button id="alert_ok">OK</button>\
                                    <button id="cancel_but">Cancel</button>\
                                    </div>';
            // Добавляем только что созданный элемент в дерево DOM
            //document.body.insertBefore(newDiv, my_div);
            document.querySelector('#list_wrapper').parentNode.insertBefore(newDiv, document.querySelector('#list_wrapper'));
            // setup body no scroll
            document.body.style.overflow = 'hidden';
            appointment('short', '/admin/signup/get_master_times', arr.service_id, master_id, Laravel.csrfToken);
            let but = document.getElementById('alert_ok');
            let cancel_but = document.getElementById('cancel_but');
            cancel_but.addEventListener('click', function () {
                document.querySelector('.modal').remove();
                document.body.style.overflow = 'scroll';
            });
            but.addEventListener('click', function (ev) {
                let time_checked = document.querySelector('#time_choice input[name="time"]:checked');
                if ( time_checked ) {
                    let start_dt = time_checked.value;
                    data_from_db("{{url()->route('admin.signup.edit.post')}}", "order_id=" + order_id+"&serv_dur="+serv_dur+"&start_dt="+start_dt)
                    .then(promise => promise)
                    .then(data => {
                        //location.reload();
                        document.querySelector('.modal').remove();
                        document.body.style.overflow = 'scroll';
                        new_dt = new Date(data).toLocaleString('ru-RU', { weekday: 'long', day: 'numeric', month: 'long', year: "numeric", hour: 'numeric', minute: 'numeric' });
                        txt = (!!new_dt) ? new_dt : data;
                        document.querySelector('#order_time').innerHTML = '<span style="color:green;">Сохранено: <br>' + txt + '</span>';
                        document.querySelector('#order_time').value = new Date(data).getTime();
                    })
                    .catch(function (err) {
                        console.log("Fetch Error :-S", err);
                    });
                }
            })
        }

        function master_change(order_id, service_id, start_dt) {
            var newDiv = document.createElement('div');
            newDiv.classList.add('modal')
            newDiv.id = "alert"
            newDiv.innerHTML = '<div><div id="master_choice"></div>\
                                    <p>После выбора mastera нажмите "Ок"</p>\
                                    <button id="alert_ok">OK</button>\
                                    <button id="cancel_but">Cancel</button>\
                                    </div>';
            // Добавляем только что созданный элемент в дерево DOM
            //document.body.insertBefore(newDiv, my_div);
            document.querySelector('#list_wrapper').parentNode.insertBefore(newDiv, document.querySelector('#list_wrapper'));
            // setup body no scroll
            document.body.style.overflow = 'hidden';
            let cancel_but = document.getElementById('cancel_but');
            cancel_but.addEventListener('click', function () {
                document.querySelector('.modal').remove();
                    document.body.style.overflow = 'scroll';
            });

            data_from_db("{{url()->route('admin.signup.get_masters')}}", "order_id=" + order_id+"&service_id="+service_id+"&start_dt="+start_dt)
            .then(promise => promise)
            .then(masters_data => {
                    //console.log(masters_data)
                    if (typeof masters_data === 'string' || masters_data instanceof String) {
                        document.querySelector('#master_choice').innerHTML = masters_data;
                    } else  {
                        document.querySelector('#master_choice').innerHTML = '<div class="radio-group flex">';
                        for (const master of masters_data) {
                            document.querySelector('#master_choice').innerHTML += '<article class="main_section_article radio" data-value="'+master.id+'" style="min-width:13rem;">\
                                    <div class="main_section_article_imgdiv" style="background-color: var(--bgcolor-content);">\
                                        <img src="{{asset("storage")}}/'+master.master_photo+'" alt="Фото '+master.master_fam+'" class="main_section_article_imgdiv_img" />\
                                    </div>\
                                    <div class="main_section_article_content margin_top_1rem">\
                                        <h3 id="'+master.id+'">'+master.master_name+' '+master.master_fam+'<br>'+master.master_phone_number+'</h3>\
                                    </div>\
                                </article>';
                        }
                        document.querySelector('#master_choice').innerHTML += '</div>';

                        document.querySelectorAll('.radio').forEach(function(master_article) {
                            master_article.addEventListener('click', function(){
                                document.querySelectorAll('.radio').forEach(function (elm) {
                                    elm.classList.remove("selected");
                                });
                                this.classList.add('selected');
                                let master_id = $(this).attr('data-value');

                                let but = document.getElementById('alert_ok');
                                if (!!but) {
                                    but.addEventListener('click', function (ev) {
                                        data_from_db("{{url()->route('admin.signup.edit.post')}}", "order_id=" + order_id+"&master_id="+master_id)
                                            .then(promise => promise)
                                            .then(data => {
                                                //location.reload();
                                                if (!!document.querySelector('.modal')) {document.querySelector('.modal').remove();}
                                                document.body.style.overflow = 'scroll';
                                                document.querySelector('#order_master').innerHTML = '<span style="color:green;">Сохранено: <br>'
                                                    + data.master_name +
                                                    ' ' + data.sec_name +
                                                    ' ' + data.master_fam +
                                                    ', ' + data.master_phone_number +
                                                    '</span>';
                                                document.querySelector('#order_master').value = data.id;
                                        })
                                        .catch(function (err) {
                                            console.log("Fetch Error :-S", err);
                                        });
                                    })
                                }
                            })
                        })

                    }

            })
        }

        function fetch_del_change(element) {
            if (element.target.nodeName == 'BUTTON') {
                        let action = element.target.value;
                        let id = element.target.id;

                        if (action == 'delete' && id !== 'undefined') {
                            data_from_db("{{url()->route('admin.signup.remove')}}", "order_id=" + id)
                            .then(promise => promise)
                            .then(master_data => {
                                location.reload();
                            })
                            .catch(function (err) {
                                console.log("Fetch Error :-S", err);
                            });
                        }

                        if (action == 'change' && id !== 'undefined') {
                            history.pushState(document.body.innerHTML, "", '{{url()->current()}}#change');

                            let div = document.querySelector('#js_edit');
                            //3 buttons for choice time or master or service
                            data_from_db("{{url()->route('admin.signup.edit')}}", "order_id=" + id)
                            .then(promise => promise)
                            .then(master_data => {
                                //console.log(master_data)
                                arr = master_data.edit;
                                order_id = arr.id;
                                service_id = arr.service_id;
                                serv_dur = (new Date(arr.end_dt).getTime() - new Date(arr.start_dt).getTime()) / 1000 / 60;
                                //console.log(serv_dur)

                                //document.querySelector('#back_button_admin').style.display = '';

                                document.querySelector('#list_wrapper').innerHTML =
                                    '<div class="form-recall-main">\
                                        <table class="table price_form_table" id="signup_change">\
                                            <tbody class="text_left">\
                                                <tr>\
                                                    <td>\
                                                        Время: \
                                                    </td>\
                                                    <td id="order_time">'
                                                        +pad(new Date(arr.start_dt).getHours())
                                                        +':'+pad(new Date(arr.start_dt).getMinutes())
                                                        +'<br>'+capitalizeFirstLetter(new Date(arr.start_dt).toLocaleDateString("ru-RU", { weekday: 'short', year: 'numeric', month: 'short', day: 'numeric' }))+
                                                    '</td>\
                                                    <td>\
                                                        <br><button type="button" class="buttons" id="time_ch">Перенести</button>\
                                                    </td>\
                                                </tr>\
                                                <tr>\
                                                    <td>\
                                                        Мастер: \
                                                    </td>\
                                                    <td id="order_master">'
                                                        +arr.master.master_name+' '+arr.master.sec_name+' '+arr.master.master_fam+', '+arr.master.master_phone_number+
                                                    '</td>\
                                                    <td>\
                                                        <br><button type="button" class="buttons" id="master_ch">Выбрать</button>\
                                                    </td>\
                                                </tr>\
                                                <tr>\
                                                    <td>\
                                                        Услуга: \
                                                    </td>\
                                                    <td>'
                                                        +arr.service+
                                                    '</td>\
                                                    <td>\
                                                    </td>\
                                                </tr>\
                                                <tr>\
                                                    <td>\
                                                        Клиент: \
                                                    </td>\
                                                    <td>'
                                                        +arr.client.name+', '+arr.client.phone+
                                                    '</td>\
                                                    <td>\
                                                    </td>\
                                                </tr>\
                                                <tr>\
                                                    <td>\
                                                        Статус: \
                                                    </td>\
                                                    <td>'
                                                        +arr.status+
                                                    '</td>\
                                                    <td>\
                                                        <br><button type="button" class="buttons" id="status_ch">Change status</button>\
                                                    </td>\
                                                </tr>\
                                            </tbody>\
                                        </table>\
                                    </div>';

                                    let table_ch = document.querySelector('#signup_change');
                                    if (!!table_ch) {
                                        table_ch.addEventListener('click', function(element){

                                            if (element.target.id == 'time_ch') {
                                                //history.pushState(document.body.innerHTML, "", '{{url()->current()}}#time_change');

                                                if (!!document.querySelector('#order_master').value) {
                                                    master_id = document.querySelector('#order_master').value;
                                                    //console.log(master_id+'new')
                                                } else {
                                                    master_id = arr.master_id;
                                                    //console.log(master_id)
                                                }
                                                time_change(order_id, serv_dur, master_id);
                                            }
                                            if (element.target.id == 'master_ch') {
                                                //history.pushState(document.body.innerHTML, "", '{{url()->current()}}#master_change');

                                                if (!!document.querySelector('#order_time').value) {
                                                    d = new Date(document.querySelector('#order_time').value);
                                                    start_dt = (d.getFullYear()
                                                        +"-"+("0"+(d.getMonth()+1)).slice(-2)
                                                        +"-"+("0"+d.getDate()).slice(-2)
                                                        +" "+("0"+d.getHours()).slice(-2)
                                                        + ":" + ("0" + d.getMinutes()).slice(-2));
                                                } else {
                                                    start_dt = arr.start_dt;
                                                }
                                                master_change(order_id, service_id, start_dt);
                                            }

                                        });
                                    }
                            })
                            .catch(function (err) {
                                console.log("Fetch Error :-S", err);
                            });
                        }
                    }
        }

        // for by_master
        let elem = document.querySelectorAll('.masters');
        for (var i = 0; i < elem.length; i++) {
            elem[i].onclick = function (e) {
                let id = e.target.id;

                //history.pushState(document.body.innerHTML, "", '{{url()->current()}}');
                document.querySelector('#app').innerHTML = '';
                //document.querySelector('#div_'+id).toggle();
                data_from_db("{{url()->route('admin.signup.post_by_master')}}", "master_id=" + id)
                    .then(promise => promise)
                    .then(master_data => {
                        let string = '';
                        for (const key in master_data.post_by_master) {
                            if (Object.hasOwnProperty.call(master_data.post_by_master, key)) {
                                master = key;
                                string += '<div class="back shad rad pad margin_rlb1" >'+'<span><b>'+master+'</b></span></div>';

                                for (const datas in master_data.post_by_master[key]) {
                                    if (Object.hasOwnProperty.call(master_data.post_by_master[key], datas)) {

                                        string += '<div class="back shad rad pad margin_rlb1">\
                                                    <p class="pad">'+js_date(datas)+'</p>';
                                        let serv_and_client = master_data.post_by_master[key][datas];

                                        for (var i = 0; i < serv_and_client.length; i++){
                                            let start_dt = serv_and_client[i].start_dt;
                                            let hour = new Date(start_dt).getHours();
                                            let minutes = new Date(start_dt).getMinutes();
                                            let fulldate = pad(hour)+':'+pad(minutes);

                                            let service = serv_and_client[i].service;
                                            let client = serv_and_client[i].client;

                                            string += '<div class="back shad rad pad mar display_inline_block"'
                                                        +'<span class="display_inline_block zapis_usluga"><b>'+capitalizeFirstLetter(fulldate)+'</b></span><br>'
                                                        +service+'<br>'
                                                        +client+'<br>\
                                                        </div>';
                                        }
                                        string += '</div>';
                                    }
                                }
                            }
                        }
                        document.querySelector('#app').innerHTML = string;
                    })
                    .catch(function (err) {
                        console.log("Fetch Error :-S", err);
                    });
            };
        }

        let js_table = document.querySelector('#js_table');
        if (!!js_table) {
            let dell = <?php if (!empty($data)) {
                echo json_encode($data);
            }?>;

            if ('post_by_client' in dell) {
                document.querySelector('#js_back_button').remove();
                page_object = dell.post_by_client;
                //console.log(page_object)
            } else if ('list' in dell) {
                page_object = dell.list;
            }

            //history.pushState('', "", '{{url()->current()}}#list');
            if ( !'{{url()->current()}}'.includes(window.location.hash)) {
                //history.pushState('', "", "{{url()->route('admin.home')}}");
                //history.pushState('', "", '{{url()->current()}}');
            }
            var res_obj = res_obj(page_object);
            js_table.innerHTML = inhtml(res_obj);

            let table = document.querySelector('#signup_all_list');
            if(!!table) {
                table.addEventListener('click', function(element){
                    fetch_del_change(element);
                });
            }
        }


    }
</script>
@stop
