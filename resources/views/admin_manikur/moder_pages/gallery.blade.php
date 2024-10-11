@php
$title = "Gallery edit";
$page_meta_description = "Gallery edit page";
$page_meta_keywords = "Gallery edit";
$robots = "NOINDEX, NOFOLLOW";
@endphp


@extends("layouts/index_admin")

@section("content")
    <link rel="stylesheet" type="text/css" href="{{ url()->asset('storage'.DIRECTORY_SEPARATOR.'css'.DIRECTORY_SEPARATOR.'fancybox.css') }}" >
        @if (!empty($menu)) <p class="content">{{$menu}}</p> @endif

        <?php
    if (!empty($res)) {
        ?>
        <div class="content"><p>
        <?php
            echo $res;
        ?>
        </p></div>
        <?php
    } else {
        ?>
    <form action="<?php echo route('admin.gallery.go'); ?>" method="post" name="gallery_edit" id="gallery_edit" class="back shad rad margin_rlb1" enctype="multipart/form-data">
    @csrf
    <div class="pad margin_bottom_1rem display_none" id="div_add_photo">
        <h3 class=" ">Добавить</h3>
        <div class="">
        <label ><p>Выберите фото весом до 3Мб</p>
            <p>
            <input type="hidden" name="MAX_FILE_SIZE" value="3145728" />
            <input type="file" multiple="multiple" name="gallery_add[]" accept=".jpg,.jpeg,.png,.webp, image/jpeg, image/pjpeg, image/png, image/webp" />
            </p>
        </label>
        </div>
    </div>

    <div class="pad margin_bottom_1rem display_none" id="div_del_photo">
        <h3 class=" ">Удалить</h3>
        <p>Выберите фото для удаления</p>
        <div class="gallery flex ">
        <?php
            $width = 150;
        $height = 100;
        $dir = storage_path('app'.DIRECTORY_SEPARATOR.'public'.DIRECTORY_SEPARATOR.'images'.DIRECTORY_SEPARATOR.'gallery');
        // $files = [];
        foreach (files_in_dir($dir, 'jpg, jpeg, png, webp') as $file) {
            if (is_file($dir.DIRECTORY_SEPARATOR.$file)) {
                // $files[] = basename($file);
                // $id = pathinfo($file, PATHINFO_FILENAME);
                echo '<div class="photo_del margin05 shad rad" id="'.$dir.DIRECTORY_SEPARATOR.$file.'">
                        <img src="'.asset('storage'.DIRECTORY_SEPARATOR.'images'.DIRECTORY_SEPARATOR.'gallery'.DIRECTORY_SEPARATOR.$file).'" alt="photo" class=" rad" width="'.$width.'" height="'.$height.'" />
                    </div>';
            }
        }
        // print_r($files);
        ?>
        </div>
    </div>

    <div class="pad margin_bottom_1rem display_none" id="div_change_link">
        <h3 class=" ">Изменить ссылку на каталог с фото</h3>
        <div class="">
        <label ><p>Вставьте адрес ссылки</p>
            <p>
            <input style="width:100%;" type="text" name="photo_link" placeholder="https://vk.com/your-album" maxlength="300" pattern="[Hh][Tt][Tt][Pp][Ss]?:\/\/(?:(?:[a-zA-Z\u00a1-\uffff0-9]+-?)*[a-zA-Z\u00a1-\uffff0-9]+)(?:\.(?:[a-zA-Z\u00a1-\uffff0-9]+-?)*[a-zA-Z\u00a1-\uffff0-9]+)*(?:\.(?:[a-zA-Z\u00a1-\uffff]{2,}))(?::\d{2,5})?(?:\/[^\s]*)?" />
            </p>
        </label>
        </div>
    </div>

    <div class="display_none" id="form-buttons">
        <a href="<?php echo url()->route('admin.gallery.edit'); ?>" ><button class="buttons" type="button">Назад</button></a>
        <button class="buttons" form="gallery_edit" type="reset" id="reset">Очистить</button>
        <button class="buttons" type="submit" form="gallery_edit">Готово</button>
    </div>
    </form>

    <div id="mas_form" class="mas_form  margin_bottom_1rem">
      <button class="buttons gal" id="add_photo">Добавить фото</button>
      <button class="buttons gal" id="del_photo">Удалить фото</button>
      <button class="buttons gal" id="change_link">Изменить ссылку на каталог</button>
    </div>
    <?php
    }
        ?>

<script type="module">
    function jq( myid ) {
        return "#" + myid.replace( /(:|\.|\[|\]|\/|,|=|@)/g, "\\$1" );
    }
    document.addEventListener('DOMContentLoaded', function () {
    $(function() {
        $('.gal').on('click',function(){
            let id =  this.id;
            $('#gallery_edit').addClass('pad');
            $('#gallery_edit').show();
            $('#div_'+id).show();
            $('#form-buttons').show();
            $('#mas_form').hide();
        });

        $('.photo_del').on('click',function(){
            let file = this.id;
            let inp = $('#form-buttons').children(jq('inp'+file));
            if ( inp.val() ){
                $(this).removeClass('selected_shad');
                inp.remove();
            } else {
                $(this).addClass('selected_shad');
                $('#form-buttons').append('<input type="hidden" name="gallery_del[]" value="'+file+'" id="inp'+file+'" />');
            }
        });

            $('#reset').on('click',function(){
                $('input[type=hidden]').remove();
                $('.photo_del').removeClass('selected_shad');
        });
    });
    }, false);
</script>

@stop
