@php
if (isset($page_data) && is_array($page_data) && !empty($page_data[0])) {
    $title = $page_data[0]["title"];
    $page_meta_description = $page_data[0]["description"];
    $page_meta_keywords = $page_data[0]["keywords"];
    $robots = $page_data[0]["robots"];
    $content['map'] = $page_data[0]["content"];
} else {
    $title = "Title";
    $page_meta_description = "description";
    $page_meta_keywords = "keywords";
    $robots = "INDEX, FOLLOW";
    $content['map'] = "map";
}
@endphp


@extends("layouts/index")

@section("content")

    @if (!empty($menu)) <p class="content">{{$menu}}</p> @endif
    <div class="content">
        <div class="map">
        <?php
        if (!empty($content) && !empty($content['map'])) {
            // ADD check to responsible map (if isset answer)
            echo html_entity_decode($content['map']);
        } elseif (is_readable(storage_path('app'.DIRECTORY_SEPARATOR.'public'.DIRECTORY_SEPARATOR.'images'.DIRECTORY_SEPARATOR.'map'.DIRECTORY_SEPARATOR.'map.jpg'))) {
            echo '<img src="'.asset('storage'.DIRECTORY_SEPARATOR.'images'.DIRECTORY_SEPARATOR.'map'.DIRECTORY_SEPARATOR.'map.jpg').'" alt="" class="mapp"/>';
        } else {
            echo 'Not map in DB or map image.';
        }
        ?>

        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function () {
            $(function(){
            // let ifr = $('.map > iframe');
            let ifr = $('.map').find( $('iframe'));
            if ( ifr.length ) {
                ifr.css({width: '', height: ''}).addClass('mapp');
            }
            $('html, body').animate({scrollTop: $(".map").offset().top}, 1000);
            });
        }, false);
    </script>
@stop
