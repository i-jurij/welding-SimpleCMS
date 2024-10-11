@php
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
@if (!empty($menu)) <p class="content">{{$menu}}</p> @endif
<div class="">
    <div>
    @if (!empty($abouts) && is_array($abouts))
        @foreach ($abouts as $about)
            @php
                $img = imageFor($about['image']);
            @endphp
            <article class="main_section_article ">
                <div class="main_section_article_imgdiv margin_bottom_1rem">
                    <img src="{{asset('storage'.DIRECTORY_SEPARATOR.$img)}}" alt="{{$about['title']}}" class="main_section_article_imgdiv_img" />
                </div>
                <div class="main_section_article_content">
                    <h3>{{$about['title']}}</h3>
                    <span>{{$about['content']}}</span>
                </div>
            </article>
        @endforeach
    @endif
    </div>
    <div>
    @if (!empty($masters) && is_array($masters))
        @foreach ($masters as $master)
            @php $img = 'images'.DIRECTORY_SEPARATOR.'ddd.jpg' @endphp
            @php $img = 'images'.DIRECTORY_SEPARATOR.'ddd.jpg' @endphp
            @if (!empty($master['master_photo']) && file_exists(storage_path('app'.DIRECTORY_SEPARATOR.'public'.DIRECTORY_SEPARATOR.$master['master_photo'])))
                @php $img = $master['master_photo'] @endphp
            @elseif (empty($master['master_photo']) && file_exists(storage_path('app'.DIRECTORY_SEPARATOR.'public'.DIRECTORY_SEPARATOR.'images'.DIRECTORY_SEPARATOR.'masters'.DIRECTORY_SEPARATOR.mb_strtolower(sanitize(translit_to_lat($master['master_phone_number']))).'.jpg')))
                @php $img = 'images'.DIRECTORY_SEPARATOR.'masters'.DIRECTORY_SEPARATOR.mb_strtolower(sanitize(translit_to_lat($master['master_phone_number']))).'.jpg' @endphp
            @endif
            <article class="main_section_article ">
                <div class="main_section_article_imgdiv margin_bottom_1rem" style="background-color: var(--bgcolor-content);">
                    <img src="{{asset('storage'.DIRECTORY_SEPARATOR.$img)}}" alt="{{$master['master_fam']}}" class="main_section_article_imgdiv_img" />
                </div>

                <div class="main_section_article_content">
                    <span>
                        Мастер:
                    </span>
                    <h3>{{$master['master_name']}} {{$master['master_fam']}}</h3>
                </div>
            </article>
        @endforeach
    @endif
    </div>
</div>
@stop
