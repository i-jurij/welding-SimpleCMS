<?php
$title = 'Content editing';
$page_meta_description = 'admins page, Content editing';
$page_meta_keywords = 'Content editing';
$robots = 'NOINDEX, NOFOLLOW';
?>

@extends('layouts/index_admin')
@section('content')

    <div class="content">
    @if (is_string($data))
        {{$data}}
    @endif
    @if (!empty($data['res']))
        @if (is_array($data['res']))
            @foreach ($data['res'] as $value)
                {{$value}}'<br>'
            @endforeach
        @endif
        @if (is_string($data['res']))
            {{$data['res']}}
        @endif
    @else
        @if (isset($data['serv']) && is_array($data['serv']))
            <form action="<?php echo url()->route('admin.service_page.post_content'); ?>" method="post" enctype="multipart/form-data" class="" id="service_content_form">
            @csrf
                <div class="form_radio_btn margin_bottom_1rem" style="width:85%;">
                    <div id="page_choice"><p class="pad">Выберите страницу, услугу и действие для содержимого:</p>
                    @foreach ($data['serv'] as $page => $cat_arr)
                        <label>
                            <input type="radio" name="page" class="buttons vertaligntop" id="{{sanitize(translit_to_lat($page))}}" value="{{$page}}">
                            <span>{{$page}}</span>
                        </label>
                    @endforeach
                    </div>
                    <div class="zapis_usluga">
                        @foreach ($data['serv'] as $page => $cat_arr)
                        <div class="uslugi display_none" id="div{{sanitize(translit_to_lat($page))}}" >
                            @foreach ($cat_arr as $cat_name => $serv_arr)
                                @if ($cat_name !== 'page_serv')
                                    @foreach ($serv_arr as $serv_name => $serv_id)
                                        <label class="text_left" for="{{$serv_id}}">
                                            <input
                                                type="radio"
                                                name="usluga"
                                                value="{{$serv_id}}"
                                                id="{{$serv_id}}"
                                            />
                                            <span>{{$page}},<br>{{$cat_name}}: {{$serv_name}}</span>
                                        </label>
                                    @endforeach
                                @elseif ($cat_name == 'page_serv')
                                    @foreach ($serv_arr as $serv_name => $serv_id)
                                        <label class="text_left" for="{{$serv_id}}">
                                            <input
                                                type="radio"
                                                name="usluga"
                                                value="{{$serv_id}}"
                                                id="{{$serv_id}}"
                                                required
                                            />
                                            <span>{{$page}},<br>{{$serv_name}}</span>
                                        </label>
                                    @endforeach
                                @endif
                            @endforeach
                            </div>
                        @endforeach
                    </div>
                </div>
                <div class="margintb1 display_none" id="add_or_remove" >
                    <button type="button" class="buttons" id="content_add" />Add</button>
                    <button type="submit" name="content_remove" value="content_remove" class="buttons" id="content_remove" form="service_content_form"/>Remove</button>
                </div>
                <div class="display_none pad back shad price" id="content">
                    <label ><p>Выберите файл в формате "ODT" весом до 3Мб</p>
                        <p>
                        <input type="hidden" name="MAX_FILE_SIZE" value="3145728" />
                        <input type="file" name="file_content_add" accept=".odt, application/vnd.oasis.opendocument.text" />
                        </p>
                    </label>
                </div>
                <div class="margintb1 display_none" id="form_buttons" >
                    <button type="submit" name="content_add" value="content_add" class="buttons" form="service_content_form" />Save</button>
                    <!-- <input type="reset" class="buttons" form="service_content_form" value="Reset" /> -->
                </div>
            </form>
        @elseif (isset($data['service_page']) && is_string($data['service_page']))
            {{$data['service_page']}}
        @endif
    @endif
</div>

<script type="text/javascript">

document.addEventListener('DOMContentLoaded', function () {
    $(function() {
        let page_buttons = $('input[name="page"');
        let uslugi = $('input[name="usluga"');
        // Добавить событие щелчка для всех TD
        page_buttons.click(function() {
            var button_id = $(this).prop('id');
            $('.uslugi').each(function (index, value){
                let page_id = $(this).prop('id');
                if (page_id == 'div'+button_id) {
                    $("#div"+button_id).toggle();
                    //unchecked preffred checked radio input
                    for(i=0; i<uslugi.length; i++ ) {
                        uslugi[i].checked = false;
                    }
                } else {
                    $(this).hide();
                }
            });
        });

        uslugi.click(function() {
            $('input[type="radio"]').not(':checked').closest("label").toggle();
            $(this).show();
            $('#add_or_remove').show();
        });

        $('#content_add').click(function() {
            $('#add_or_remove').hide();
            $('#content').show();
            $('#form_buttons').show();
        });

    });
}, false);

</script>


@stop
