@php
$photo_link = 'https://disk.yandex.ru/d/PldT5ChpcRCVRg';
if (isset($page_data) && is_array($page_data) && !empty($page_data[0])) {
    $title = $page_data[0]["title"];
    $page_meta_description = $page_data[0]["description"];
    $page_meta_keywords = $page_data[0]["keywords"];
    $robots = $page_data[0]["robots"];
    $content["page_content"] = $page_data[0]["content"];
} else {
    $title = "Title";
    $page_meta_description = "description";
    $page_meta_keywords = "keywords";
    $robots = "INDEX, FOLLOW";
    $content = "CONTENT FOR DEL IN FUTURE";
}
@endphp


@extends("layouts/index")

@section("content")
<link rel="stylesheet" type="text/css" href="{{ url()->asset('storage'.DIRECTORY_SEPARATOR.'js'.DIRECTORY_SEPARATOR.'gallery'.DIRECTORY_SEPARATOR.'fancybox.css') }}" >
<link rel="stylesheet" type="text/css" href="{{ url()->asset('storage'.DIRECTORY_SEPARATOR.'js'.DIRECTORY_SEPARATOR.'gallery'.DIRECTORY_SEPARATOR.'panzoom.css') }}" >
    @if (!empty($menu)) <p class="content">{{$menu}}</p> @endif
        <div class="">

        <div class="gallery">
        @php
            /**
            * @param string $directory = 'gallery_img';    // Папка с изображениями
            * @param string $pattern   = '#z*.(jpg|png|jpeg|webm*)#'; // паттерн отбора изображений
            * @param string $width     = 100; //ширина в пикселях
            * @param string $height    = 100; //высота в пикселях
            *
            * @return string or null
            */
            function simpleGallery_fancybox($directory, $pattern, $width = '', $height = '')
            {
                $iterator = new FilesystemIterator($directory);
                $filter = new RegexIterator($iterator, $pattern);
                $gallery = '';
                foreach ($filter as $name) {
                    $nameww = explode('public', str_replace(' ', '%20', $name));
                    $nameww = ltrim(array_pop($nameww), '/');
                    $namefn = pathinfo($name, PATHINFO_FILENAME);
                    $w = (!empty($width)) ? 'width="'.$width.'"' : '';
                    $h = (!empty($height)) ? 'height="'.$height.'"' : '';
                    $gallery .= '<a data-fancybox="gallery" class="gallery_a" href="'.Storage::url($nameww).'" title="'.$namefn.'">
                                    <img class="rounded" src="'.asset('storage'.DIRECTORY_SEPARATOR.$nameww).'" alt="'.$namefn.'" '.$w.' '.$h.'  />
                                </a>';
                }

                return (!empty($gallery)) ? $gallery : null;
            }
            // var for gallery.php
            // Папка с изображениями
            $directory = storage_path('app'.DIRECTORY_SEPARATOR.'public'.DIRECTORY_SEPARATOR.'images'.DIRECTORY_SEPARATOR.'gallery');
            $pattern = '#z*.(jpg|png|jpeg|webm*)#';
            // $width = 460;
            // $height = 320;
            echo simpleGallery_fancybox($directory, $pattern, $width = '', $height = '');

            if (!empty($photo_link)) {
                $photo_link_name = explode('.', parse_url($photo_link, PHP_URL_HOST));
            }
        @endphp
    </div>
    </div>
    <p class="zapis_usluga back shad pad margin_rlb1">
        Больше снимков можно посмотреть в
        <a href="@if (isset($photo_link)) {{$photo_link}} @endif" > @if (isset($photo_link_name)) {{ strtoupper($photo_link_name[0])}} @endif </a>
     </p>
    <script  src="{{ url()->asset('storage'.DIRECTORY_SEPARATOR.'js'.DIRECTORY_SEPARATOR.'gallery'.DIRECTORY_SEPARATOR.'fancybox.umd.js') }}"></script>
@stop
