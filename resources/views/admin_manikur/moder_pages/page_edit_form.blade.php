<?php
$title = 'Page edit form';
$page_meta_description = 'admins page, Page editing form';
$page_meta_keywords = 'Pages, edit, form';
$robots = 'NOINDEX, NOFOLLOW';
$filesize = 1;
?>

@extends('layouts/index_admin')
@section('content')
    <div class="content">
    @if (is_array($fields) && !empty($fields))
    @if (!empty($fields['service_page']) && $fields['service_page'] === 'yes')
        @include('layouts/create_cat_serv_buttons')
    @endif
        <div class="price">
            <form method="post" action="{{ url()->route('admin.pages.update') }}" enctype="multipart/form-data" id="page_update"  class="form_page_add">
            @csrf
            <label class="input-file">
                <input type="hidden" name="MAX_FILE_SIZE" value="{{$filesize*1024000}}" />
                <input type="file" id="f0" name="image_file" accept=".jpg,.jpeg,.png, image/jpeg, image/pjpeg, image/png" />
                <span >Изображение весом до {{$filesize}}Мб</span>
                <p id="fileSizef0" ></p>
            </label>
            <table class="table">
                <thead>
                <tr>
                    <th>Type</th>
                    <th>Data</th>
                </tr>
                </thead>
                <tbody class="text_left">
                <?php
                foreach ($fields as $key => $value) {
                    $pattern = '';
                    $length = '100';
                    if ($key === 'alias') {
                        $pattern = 'pattern="^[a-zA-Zа-яА-ЯёЁ0-9-_]{1,100}$"';
                    }
                    if ($key === 'content' || $key === 'description') {
                        $input_start = '<textarea  style="width:100%;"';
                        $input_end = '>'.$value.'</textarea>';
                        if ($key === 'description') {
                            $length = '255';
                        }
                        if ($key === 'content') {
                            $length = '65535';
                        }
                    } elseif ($key === 'id' || $key === 'single_page' || $key === 'service_page') {
                        $input_start = '<input type="hidden" style="width:100%;"';
                        $input_end = ' />'.$value;
                    } else {
                        $input_start = '<input type="text" style="width:100%;"';
                        $input_end = ' />';
                    }

                    if ($key === 'keywords') {
                        $length = '500';
                    }

                    if ($key === 'publish') {
                        $length = '10';
                    }

                    if ($key !== 'created_at' && $key !== 'updated_at' && $key !== 'img') {
                        echo '  <tr>
                                    <td>'.$key.'</td>
                                    <td>'.$input_start.' name="'.$key.'" maxlength="'.$length.'" value="'.$value.'" '.$pattern.' '.$input_end.'</td>
                                </tr>';
                    }
                }
?>
                </tbody>
            </table>
            <div class="">
                <button type="submit" form="page_update" class="buttons" id="edit_page_sub">Save</button>
                <button type="reset" form="page_update" class="buttons">Reset</button>
            </div>
            </form>
        </div>
    </div>
    @endif

@stop
<script type="module">
document.addEventListener('DOMContentLoaded', function () {
    $('form#page_update').on('change', function(){
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

  $('#page_update').on('reset', function(e) {
        setTimeout(function() {
            $("[type='file']").each(function(){
                let file = 'Изображение весом до {{$filesize}}Мб';
                $(this).next().html(file);
                $('#fileSize'+this.id).html('');
            });
        },200);
    });
}, false);
</script>
