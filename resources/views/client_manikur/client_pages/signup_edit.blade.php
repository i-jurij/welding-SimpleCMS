<?php
    $title = 'Editing an appointments';
    $page_meta_description = 'Editing an appointments';
    $page_meta_keywords = 'appointment, signup, edit, remove';
    $robots = 'INDEX, FOLLOW';
    $content['content'] = 'CONTENT OR NOT CONTENT';

    function inhtml($res_obj)
    {
        $inhtml = '<table class="table price_form_table" id="signup_all_list">
                                    <colgroup>
                                        <col width="20%">
                                        <col width="30%"
                                        <col width="25%">
                                        <col width="25%">
                                    </colgroup>
                                    <tbody>';
        foreach ($res_obj as $key => $date) {
            $inhtml .= '<tr><td colspan="4">'.$key.'</td></tr>';
            foreach ($date as $data) {
                $inhtml .= ' <tr>
                                    <td id="time_order_id'.$data['order_id'].'">'
                                        .$data['start_dt'].
                                    '</td>
                                    <td>'
                                        .$data['service'].
                                    '</td>
                                    <td>'
                                        .$data['master'].
                                    '</td>
                                    <td>
                                        <button type="button" value="change" class="buttons" id="'.$data['order_id'].'">Изменить</button>
                                        <button type="button" value="delete" class="buttons" id="'.$data['order_id'].'" >Удалить</button>
                                    </td>
                                </tr>';
            }
            $inhtml .= '<tr><td colspan="4"></td></tr>';
        }
        $inhtml .= '</tbody></table>';

        return $inhtml;
    }
    ?>


@extends("layouts/index")

@section("content")
<link rel="stylesheet" href="{{ url()->asset('storage'.DIRECTORY_SEPARATOR.'ppntmt'.DIRECTORY_SEPARATOR.'appointment'.DIRECTORY_SEPARATOR.'css'.DIRECTORY_SEPARATOR.'style.css') }}" />

    @if (!empty($res))
        <div class="content" id="res_div">
            @if (is_array($res))
                @php
                    print_r($res)
                @endphp
            @elseif (is_string($res))
                <p>{{$res}}</p>
            @endif
        </div>
    @endif

    @if (!empty($signup))
        <div class="content" id="signup_list_div">
            @if (is_array($signup))
                {!!inhtml($signup)!!}
                @include('components.back_button_js')
            @elseif (is_string($signup))
                <p>{{$signup}}</p>
            @endif
        </div>
    @endif

    @if (!empty($data))
        <div class="content" id="signup_edit_div"></div>
    @endif

<script src="{{ url()->asset('storage'.DIRECTORY_SEPARATOR.'ppntmt'.DIRECTORY_SEPARATOR.'appointment'.DIRECTORY_SEPARATOR.'js'.DIRECTORY_SEPARATOR.'appointment.js')}}"></script>
<script type="text/javascript">
document.addEventListener('DOMContentLoaded', function () {
    function pad(n) {
            return n < 10 ? '0' + n : n;
    }
    function capitalizeFirstLetter(string) {
        return string.charAt(0).toUpperCase() + string.slice(1);
    }
    var days_of_week = ['Вс', 'Пн', 'Вт', 'Ср', 'Чт', 'Пт', 'Сб'];
    var months = ['Янв', 'Фев', 'Мар', 'Апр', 'Май', 'Июн', 'Июл', 'Авг', 'Сен', 'Окт', 'Ноя', 'Дек'];

    let res_div = document.querySelector('#res_div');
    if (res_div) {
        const yOffset = -100;
        const y = res_div.getBoundingClientRect().top + window.pageYOffset + yOffset;

        window.scrollTo({top: y, behavior: 'smooth'});
    }

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
            //throw new TypeError("Data from server is not JSON!");
            return await response;
        } else {
            return await response.json();
        }
    }

    let table = document.querySelector('#signup_all_list');
    if(table) {
        table.addEventListener('click', function(element){
            let form_data = '<form action="" method="post" id="signup_edit_form" name="theForm">@csrf\
                            <input type="hidden" name="order_id" value="" id="order_id_input" />\
                            <input type="hidden" name="client_id" value="" id="order_client_id" />\
                        </form>';
            let div_for_paste = document.querySelector('#signup_list_div') ;
            if (div_for_paste) {
                div_for_paste.innerHTML += form_data;
            }

            if (element.target.nodeName == 'BUTTON') {
                let signup_action = element.target.value;
                let id = element.target.id;
                let input = document.querySelector('#order_id_input');
                if (input) {
                    input.value = id;
                }
                let input_client_id = document.querySelector('#order_client_id');
                input_client_id.value = '<?php if (!empty($client_id)) {
                    echo $client_id;
                } else {
                    echo '';
                } ?>';

                if (signup_action == 'delete' && id) {
                    if (document.theForm) {
                        document.theForm.action = '{{url()->route("client.signup.remove")}}';
                        document.theForm.submit();
                    }
                }
                if (signup_action == 'change' && id) {
                    if (document.theForm) {
                        document.theForm.action = '{{url()->route("client.signup.edit")}}';
                        document.theForm.submit();
                    }
                }
            }
        });
    }

    let edit_div = document.querySelector('#signup_edit_div');
    if (edit_div) {
        master_data = <?php if (!empty($data)) {
            echo json_encode($data);
        } else {
            echo 'undefined';
        }?>;
        if (master_data) {
            arr = master_data.edit;
            order_id = arr.id;
            service_id = arr.service_id;
            serv_dur = (new Date(arr.end_dt).getTime() - new Date(arr.start_dt).getTime()) / 1000 / 60;

            edit_div.innerHTML = '<div class="form-recall-main">\
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
                                            </tbody>\
                                        </table>\
                                    </div>';
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
            table_ch.parentNode.insertBefore(newDiv, table_ch);
            // setup body no scroll
            document.body.style.overflow = 'hidden';
            appointment('short', '/signup/appoint_time', arr.service_id, master_id, '{{ csrf_token() }}');
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
                    data_from_db('{{url()->route("client.signup.store")}}', "order_id=" + order_id+"&serv_dur="+serv_dur+"&start_dt="+start_dt)
                    .then(promise => promise)
                    .then(data => {
                        //location.reload();
                        document.querySelector('.modal').remove();
                        document.body.style.overflow = 'scroll';
                        new_dt = new Date(data).toLocaleString('ru-RU', { weekday: 'long', day: 'numeric', month: 'long', year: "numeric", hour: 'numeric', minute: 'numeric' });
                        txt = (!!new_dt) ? new_dt : data;
                        if (txt === 'Invalid Date') {
                            document.querySelector('#order_time').innerHTML = '<span style="color:red;">Ошибка!</span>';
                        } else {
                            document.querySelector('#order_time').innerHTML = '<span style="color:green;">Сохранено: <br>' + txt + '</span>';
                            document.querySelector('#order_time').value = new Date(data).getTime();
                        }
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
            table_ch.parentNode.insertBefore(newDiv, table_ch);
            // setup body no scroll
            document.body.style.overflow = 'hidden';
            let cancel_but = document.getElementById('cancel_but');
            cancel_but.addEventListener('click', function () {
                document.querySelector('.modal').remove();
                    document.body.style.overflow = 'scroll';
            });

            data_from_db("{{url()->route('client.signup.get_masters')}}", "order_id=" + order_id+"&service_id="+service_id+"&start_dt="+start_dt)
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
                                        data_from_db('{{url()->route("client.signup.store")}}', "order_id=" + order_id+"&master_id="+master_id)
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

        let table_ch = document.querySelector('#signup_change');
        if (table_ch) {
            table_ch.addEventListener('click', function(element){
                if (element.target.id == 'time_ch') {
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

    }
}, false);
</script>

@stop
