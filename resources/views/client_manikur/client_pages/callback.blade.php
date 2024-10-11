@php
if (isset($page_data) && is_array($page_data) && !empty($page_data[0])) {
    $title = $page_data[0]["title"];
    $page_meta_description = $page_data[0]["description"];
    $page_meta_keywords = $page_data[0]["keywords"];
    $robots = $page_data[0]["robots"];
    $content['content'] = $page_data[0]["content"];
} else {
    $title = "Title";
    $page_meta_description = "description";
    $page_meta_keywords = "keywords";
    $robots = "INDEX, FOLLOW";
    $content['content'] = "CONTENT FOR DEL IN FUTURE";
}
    // action for form (if isset imgs in storage for captcha or not)
    // $action = (!empty($captcha_imgs)) ? url()->route('client.callback.send_mail') : 'javascript:void(0)';
    // for ajax
    //$action = 'javascript:void(0)';
    //for controller
    $action = url()->route("client.callback.store");
@endphp
@extends("layouts/index")
@section("content")
@if (!empty($menu)) <p class="content">{{$menu}}</p> @endif
    @if (!empty($res) && is_array($res))
        <p class="content">
            @foreach ($res as $re)
                {{$re}}<br>
            @endforeach
        </p>
    @elseif (!empty($res) && is_string($res)) <p class="content">{{$res}}</p>
    @else
        <p class="back shad pad margin_rlb1 zapis_usluga">
            Не обещаем перезвонить вам сразу же. У нас нет колл-центра.<br />
            Перезвоним как только сможем.
        </p>
        <div class="back shad rad pad margin_bottom_1rem form_recall_div">
            <form action="{{$action}}" method="post" class="form-recall-main" id="recall_one">
                @csrf
                <div class="">
                    <div class="form-group padt1">
                        <div class="">
                            <div id="error"><small></small></div>
                            <label class="zapis_usluga">Ваше имя:
                                <br>
                                <input type="text" placeholder="Не обязательно заполнять" name="name" id="name" maxlength="255" value="{{ old('name') }}" />
                            </label>
                            <br>
                            <input type="text" placeholder="Ваша фамилия" name="last_name" id="last_name" maxlength="50" />
                            <p class="error" id="tel_mes"></p>
                            <label class="zapis_usluga">Номер мобильной связи:
                                <br>
                                <input type="tel" name="phone_number"  id="number" class="number"
                                title="Формат: +7 999 999 99 99" placeholder="+7 ___ ___ __ __"
                                minlength="6" maxlength="17"
                                pattern="^(\+?(7|8|38))[ ]{0,1}s?[\(]{0,1}?\d{3}[\)]{0,1}s?[\- ]{0,1}s?\d{1}[\- ]{0,1}?\d{1}s?[\- ]{0,1}?\d{1}s?[\- ]{0,1}?\d{1}s?[\- ]{0,1}?\d{1}s?[\- ]{0,1}?\d{1}s?[\- ]{0,1}?\d{1}s?[\- ]{0,1}?"
                                value="{{ old('phone_number') }}"
                                required />
                            </label>
                            <br>
                            <label class="zapis_usluga">Вопрос:
                                <br>
                                <textarea placeholder="Не обязательно заполнять" name="send"  id="send" maxlength="500" value="{{ old('send') }}"></textarea>
                            </label>
                        </div>
                    </div>

                    <div class="margin_bottom_1rem capcha" id="captcha_div"></div>

                    <div class="form-group" id="sr_but">
                        <button class="buttons form-recall-submit" >Отправить</button>
                        <button class="buttons form-recall-reset" type="reset">Очистить</button>
                    </div>
                    <div class="clear"></div>
                </div>

                <div class="margin_top_1rem">
                    <p class="pers">
                    Отправляя данную форму, вы даете согласие на
                    <br>
                    <a href="{{url('/persinfo')}}">
                        обработку персональных данных
                    </a>
                    </p>
                </div>
            </form>


        <script type="module">
        function guidGenerator() {
            var S4 = function() {
            return (((1+Math.random())*0x10000)|0).toString(16).substring(1);
            };
            return (S4()+S4()+"-"+S4()+"-"+S4()+"-"+S4()+"-"+S4()+S4()+S4());
        }

        function ajax_mail() {
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="_token"]').attr('content')
                }
            });
            var dataar = $("form#recall_one").serialize();
            $.ajax({
                type: 'POST',
                url: '{{url()->route("client.callback.send_mail")}}',
                method: 'post',
                dataType: 'json',
                data: dataar,
                success: function(data){
                    $(".form_recall_div").html(data);
                }
            });
        }

        document.addEventListener('DOMContentLoaded', function () {

            var myGlobals = { isWin: false, isOsX:false, isNix:false };
            var appVer = navigator.appVersion;
            if      (appVer.indexOf("Win")!=-1)   myGlobals.isWin = true;
            else if (appVer.indexOf("Mac")!=-1)   myGlobals.isOsX = true;
            else if (appVer.indexOf("X11")!=-1)   myGlobals.isNix = true;
            else if (appVer.indexOf("Linux")!=-1) myGlobals.isNix = true;

            function getPathSeparator(){
                if(myGlobals.isWin){
                    return '\\';
                }
                else if(myGlobals.isOsx  || myGlobals.isNix){
                    return '/';
                }
                // default to *nix system.
                return '/';
            }

            function getDir(filePath){
                if(filePath !== '' && filePath != null){
                // this will fail on Windows, and work on Others
                return filePath.substring(0, filePath.lastIndexOf(getPathSeparator()) + 1);
                }
            }


            $(function() {
                var strings = [];
                let cimgs = <?php echo json_encode($captcha_imgs); ?>;
                // to turn off image captcha
                // var imgs = (cimgs.length < 0) ? cimgs : [];
                // to turn on image captcha
                var imgs = (cimgs.length > 0) ? cimgs : [];

                if (imgs.length > 0) {
                    var uniqids = [];
                    //for (var i = 0; i < 6; i++)
                    for (var i = 0; i < imgs.length; i++)
                    {
                        //random id generated
                        uniqids[i] = guidGenerator();
                    }
                    //choice random id from ids array
                    var truee = uniqids[Math.floor(Math.random()*uniqids.length)];

                    for (var i = 0; i < uniqids.length; i++)
                    {
                        let imgpath = '{{asset("storage")}}';
                        imgs[uniqids[i]] = '<img src="'+imgpath+getPathSeparator()+imgs[i]+'" style="width:5rem;" />';
                        strings[i] = '<input id="captcha_'+uniqids[i]+'" class="captcha" name="dada" value="'+i+'" type="radio" />\
                        <label class="captcha_img  display_inline_block" for="captcha_'+uniqids[i]+'">\
                            <img src="'+imgpath+getPathSeparator()+imgs[i]+'" id="img_'+uniqids[i]+'"/>\
                        </label>';
                    }

                    if (strings.length) {
                        $('#captcha_div').before('<div class="margin_top_1rem mes"><p>Выберите, пожалуйста, среди других этот рисунок:</p>\
                                        <p class="div_center padt1">'+imgs[truee]+'</p></div>');

                        $('#captcha_div').addClass('pad');
                        $('#captcha_div').append('<div class="imgs div_center" style="width:21rem;"></div>');
                        for (var i = 0; i < strings.length; i++)
                        {
                            $('#captcha_div .imgs').append(strings[i]);
                        }

                        $("#img_"+truee).addClass('access');

                        $('#captcha_div').append('<p><small>После выбора рисунка нажмите Отправить.</small></p>');
                    }

                    $('button.form-recall-submit').click(function(){
                        event.preventDefault();
                        let check = $("#captcha_"+truee).is(':checked');
                        if ($('#number').val()) {
                            if ( check == true ) {
                                //ajax_mail();
                                $('form#recall_one').submit();
                            } else {
                                //alert('Выберите, пожалуйста, соответствующий рисунок :)');
                                $('.mes').addClass('error');
                                $('html, body').animate({
                                    scrollTop: $(".mes").offset().top - 90
                                }, 1000);
                            }
                        } else {
                            //alert('Вы забыли ввести номер телефона :)');
                            $('#tel_mes').html('Вы забыли ввести номер телефона :)');
                            $('html, body').animate({
                                //scrollTop: $(".mes").offset().top - 90
                                scrollTop: $(this).height() / 2
                            }, 1000);
                        }
                    });
                } else {
                    // laravel captcha
                    $('#sr_but').before('<div class="form-group{{ $errors->has("captcha") ? " has-error" : "" }} margin_bottom_1rem">\
                            <p id=\"mes\"></p>\
                            <div>\
                                <div class=\"capcha2\">\
                                    <span class=\"display_inline_block div_center\" style=\"vertical-align: middle;\">{!! captcha_img() !!}</span>\
                                    <button type=\"button\" class=\"buttons\" id=\"reload\">&#x21bb;</button>\
                                </div>\
                                <input id=\"captcha\" type=\"text\" placeholder=\"Введите текст\" name=\"captcha\" />\
                            </div>\
                        </div>\
                    ');


                    $('#reload').click(function () {
                        $.ajax({
                            type: 'GET',
                            url: '{{url()->route("captcha.reload")}}',
                            success: function (data) {
                                $(".capcha2 span").html(data.captcha);
                            }
                        });
                    });

                    $('button.form-recall-submit').click(function(){
                        event.preventDefault();
                        if ($('#number').val() && $('#captcha').val()) {
                            //ajax_mail();
                            $('form#recall_one').submit();
                        } else {
                            if (!$('#number').val()) {
                                //alert('Вы забыли ввести номер телефона :)');
                                $('#tel_mes').html('Вы забыли ввести номер телефона :)');
                                $('html, body').animate({
                                    //scrollTop: $(".mes").offset().top - 90
                                    scrollTop: $(this).height() / 2
                                }, 1000);
                            }
                            if (!$('#captcha').val()) {
                                //alert('Заполните поле для ввода текста с картинки :)');
                                $('#mes').html('Заполните поле для ввода текста с картинки :)');
                                $('html, body').animate({
                                        scrollTop: $("#mes").offset().top
                                }, 2000);
                            }

                        }
                    });
                }
            });

        }, false);
        </script>
        </div>
    @endif
@stop
