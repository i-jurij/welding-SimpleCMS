<?php
$title = 'Abouts edit form';
$page_meta_description = 'admins page, Abouts edit form';
$page_meta_keywords = 'about, edit, form';
$robots = 'NOINDEX, NOFOLLOW';
$filesize = 1;
?>

            @extends("layouts/index_admin")


            @section("content")

                @if (!empty($menu)) <p class="content">{{$menu}}</p> @endif

                <div class="content">
                @if (is_array($abouts))
                    <form method="post" action="{{ url()->route('admin.about_editor.update') }}"  enctype="multipart/form-data" id="about_update" class="form_page_add">
                    @csrf
                        @foreach ($abouts as $about)
                            <div class="zapis_usluga" >
                                <p class="">Измените изображение, название и текст карточки страницы</p>
                                <div class="about_form back shad rad pad mar display_inline_block" id="inp0">
                                    <input type="hidden" name="id[]" value="{{$about['id']}}" />
                                    <label class="input-file">
                                        <input type="hidden" name="MAX_FILE_SIZE" value="{{$filesize*1024000}}" />
                                        <input type="file" id="f0" name="image_file[]" accept=".jpg,.jpeg,.png, image/jpeg, image/pjpeg, image/png" />
                                        <span >Изображение весом до {{$filesize}}Мб</span>
                                        <p id="fileSizef0" ></p>
                                    </label>
                                    <label ><p>Название (до 50 символов)</p>
                                        <p>
                                        <input type="text" name="title[]"  value="{{$about['title']}}" maxlength="50" />
                                        </p>
                                    </label>
                                    <label ><p>Текст (до 500 символов)</p>
                                        <p>
                                        <textarea name="content[]" maxlength="500" >{{$about['content']}}</textarea>
                                        </p>
                                    </label>
                                </div>
                            </div>
                        @endforeach

                        <div class="mar">
                            <input class="buttons" form="about_update" type="submit" value="Submit">
                            <input class="buttons" form="about_update" type="reset" value="Reset">
                        </div>

                    </form>
                @else {{$abouts}}
                @endif
                </div>
            @stop

<script type="module">
document.addEventListener('DOMContentLoaded', function () {
    $('form#about_update').on('change', function(){
    let f = $("[type='file']");
    if (f.length > 0) {
        f.each(function(){
            let file = this.files[0];
            let size = <?php echo $filesize; ?>*1024*1024; //1MB
            $(this).next().html(file.name);
            $('#fileSize'+this.id).html('');
            if (file.size > size) {
                $('#fileSize'+this.id).css("color","red").html('ERROR! Image size > <?php echo $filesize; ?>MB');
            } else {
                //$('#fileSize').html(file.name+' - '+file.size/1024+' KB');
            }
        });
    }
  });
}, false);
</script>
