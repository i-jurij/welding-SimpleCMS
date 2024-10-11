<?php
$title = 'Master data edit form';
$page_meta_description = 'admins page, Master data edit form';
$page_meta_keywords = 'Master, data, edit, form';
$robots = 'NOINDEX, NOFOLLOW';
$filesize = 1;
?>

@extends('layouts/index_admin')

@section('content')

<div class="content">
    @if (!empty($res) && is_array($res) && !empty($res['master']))
    <form action="{{url()->route('admin.masters.update')}}" method="post"  enctype="multipart/form-data" id="master_edit_form">
    @csrf
        <div class="form-recall-main">
            <div class="pers">Edit masters data. Изменить данные мастера:</div>
            <div class="">
                <div id="error"><small></small></div>
                <div class="master_create">
                    <label class="input-file pad">
                        <input type="hidden" name="MAX_FILE_SIZE" value="{{$filesize*1024000}}" />
                        <input type="file" id="f0" name="image_file" accept=".jpg,.jpeg,.png, image/jpeg, image/pjpeg, image/png" />
                        <span >Photo of master. Фото мастера. ( < {{$filesize}}Мб )</span>
                        <p id="fileSizef0" ></p>
                    </label>

                    <input type="text" value="{{$res['master']['master_name']}}" placeholder="Name Имя" name="master_name" id="master_name" maxlength="30" required></input>
                    <br>
                    <input type="text" value="{{$res['master']['sec_name']}}" placeholder="Second name Отчество" name="sec_name" id="sec_name" maxlength="30"></input>
                    <br>
                    <input type="text" value="{{$res['master']['master_fam']}}" placeholder="Last name Фамилия" name="master_fam" id="master_fam" maxlength="30" required></input>
                    <br>

                    <input type="tel" value="{{$res['master']['master_phone_number']}}" name="master_phone_number"  id="master_number" class="number" title="+7 999 999 99 99" minlength="6" maxlength="17"
                            placeholder="+7 ___ ___ __ __" pattern="(\+?7|8)?\s?[\(]{0,1}?\d{3}[\)]{0,1}\s?[-]{0,1}?\d{1}\s?[-]{0,1}?\d{1}\s?[-]{0,1}?\d{1}\s?[-]{0,1}?\d{1}\s?[-]{0,1}?\d{1}\s?[-]{0,1}?\d{1}\s?[-]{0,1}?\d{1}\s?[-]{0,1}?" required>
                    </input>


                    <div class="shoose_services pad mar">
                        <p class="">Specialization Специализация:</p>
                        <ul>
                            @foreach ($res['services'] as $key => $service)
                                @php
                                    list($page_id, $page_title) = explode('#', $key);
                                @endphp
                                <li class="display_inline_block text_left margin_top_1rem">
                                <label class=" buttons" for="p{{$page_id}}">
                                    <input type="checkbox" id="p{{$page_id}}" class="pagess"> {{$page_title}}: all все
                                </label>

                                <input type="button" class=" buttons clarify" id="add{{$page_id}}" value="Clarify Уточнить" />
                                    <ul class="display_none" id="padd{{$page_id}}">
                                        @foreach ($service as $ke => $cats)
                                            @php
                                                list($cat_id, $cat_name) = explode('#', $ke);
                                                if ($cat_name === 'page_serv') $cat_name = 'Other services';
                                            @endphp

                                            <li class="display_inline_block margin_top_1rem pad" style="position:relative; vertical-align:top;">
                                                <label class="buttons">
                                                    <input type="checkbox" class="pp{{$page_id}}" id="c{{$cat_id}}" > {{$cat_name}}
                                                </label>
                                                <ul class="margin_top_1rem">
                                                    @foreach ($cats as $k => $serv)
                                                    <li class="margin_rl1 pad"><label class="buttons"><input type="checkbox" class="pp{{$page_id}} cc{{$cat_id}}" name="serv[]" value="{{$k}}"> {{$serv}}</label></li>
                                                    @endforeach
                                                </ul>
                                            </li>
                                        @endforeach
                                    </ul>
                                </li>
                            @endforeach
                        </ul>
                    </div>


                    Hired date. Дата принятия на работу
                    <br>
                    <label>
                        <input type="date" name="hired" value="{{$res['master']['data_priema']}}" id="hired" min="2023-01-01" max="2050-12-31"></input>
                    </label>
                    <br>

                    Dismissed date. Дата увольнения
                    <br>
                    <label>
                        <input type="date"  value="{{$res['master']['data_uvoln']}}" name="dismissed" id="dismissed"  min="2023-01-01" max="2050-12-31"></input>
                    </label>
                </div>
            </div>

            <input type="hidden" name="id" value="{{$res['master']['id']}}" required />
            <div class="margin_top_1rem pad">
                <button class="buttons" type="submit" id="upload" form="master_edit_form">Изменить</button>
                <button class="buttons" type="reset" onclick="reset()" form="master_edit_form">Очистить</button>
            </div>
            <br class="clear" />
        </div>
    </form>
    @endif
</div>

<script type="module">
document.addEventListener('DOMContentLoaded', function () {
    $('form#master_edit_form').on('change', function(){
    let f = $("[type='file']");
    if (f.length > 0) {
        f.each(function(){
            let file = this.files[0];
            let size = <?php echo $filesize; ?>*1024*1024; //1MB
            if (file) {
                $(this).next().html(file.name);
            }

            $('#fileSize'+this.id).html('');
            if (file && file.size > size) {
                $('#fileSize'+this.id).css("color","red").html('ERROR! Image size > <?php echo $filesize; ?>MB');
            } else {
                //$('#fileSize').html(file.name+' - '+file.size/1024+' KB');
            }
        });
    }
  });

  $('#master_edit_form').on('reset', function(e) {
        setTimeout(function() {
            $("[type='file']").each(function(){
                let file = 'Photo of master. Фото мастера. ( < {{$filesize}}Мб )';
                $(this).next().html(file);
                $('#fileSize'+this.id).html('');
            });
        },200);
    });

    let check = document.querySelector('.shoose_services');

    check.onclick = function(el) {
        let el_id = el.target.id;
        let check_state = el.target.checked;

        let page_checkboxed = document.querySelectorAll('.p'+el_id);
        for(var i=0; i<page_checkboxed.length; i++) {
            if (check_state == true) {
                page_checkboxed[i].checked = true;
            } else {
                page_checkboxed[i].checked = false;
            }
        }

        let cat_checboxed = document.querySelectorAll('.c'+el_id);
        for(var i=0; i<cat_checboxed.length; i++) {
            if (check_state == true) {
                cat_checboxed[i].checked = true;
            } else {
                cat_checboxed[i].checked = false;
            }
        }
    }

    let clarify = $('.clarify');
    clarify.on('click', function(el) {
        let el_id = el.target.id;
        $('#p'+el_id).toggle();
    });

}, false);
</script>
@stop
