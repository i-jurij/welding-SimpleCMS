<?php
$title = 'HOME';
$page_meta_description = 'GET FROM DB';
$page_meta_keywords = 'GET FROM DB';
$robots = 'INDEX, FOLLOW';
$data['content'] = 'CONTENT FOR DEL IN FUTURE';
?>

@extends('layouts/index')

@Push('css')
*{
   /* color: black; */
}
@endpush

@section('content')
<div class="">
@if (!empty($content['pages_menu']))
    <?php
    $sort_pages = [];
foreach ($content['pages_menu'] as $pages) {
    if (is_array($pages) && !empty($pages)) {
        if ($pages['alias'] === 'callback') {
            $sort_pages[0] = $pages;
        } elseif ($pages['alias'] === 'signup') {
            $sort_pages[1] = $pages;
        } elseif ($pages['alias'] === 'manikur') {
            $sort_pages[2] = $pages;
        } elseif ($pages['alias'] === 'second') {
            $sort_pages[3] = $pages;
        } elseif ($pages['alias'] === 'gallery') {
            $sort_pages[4] = $pages;
        } elseif ($pages['alias'] === 'about') {
            $sort_pages[5] = $pages;
        } elseif ($pages['alias'] === 'map') {
            $sort_pages[6] = $pages;
        } elseif ($pages['alias'] === 'price') {
            $sort_pages[7] = $pages;
        } elseif ($pages['alias'] === 'persinfo') {
            $sort_pages[8] = $pages;
        }
    }
}
foreach ($content['pages_menu'] as $pages) {
    if (!in_array_recursive($pages['alias'], $sort_pages, true)) {
        array_push($sort_pages, $pages);
    }
}
ksort($sort_pages);
unset($content['pages_menu'], $pages);
?>
    @foreach ($sort_pages as $pages)
        <article class="main_section_article ">
            <a class="main_section_article_content_a" href="{{url('/'.$pages['alias'])}}" >
                <div class="main_section_article_imgdiv">
                <img src="{{asset('storage/'.$pages['img'])}}" alt="{{$pages['title']}}" class="main_section_article_imgdiv_img" />
                </div>
                <div class="main_section_article_content margin_top_1rem">
                    <h2>{{mb_ucfirst($pages['title'])}}</h2>
                    <span>
                        {{mb_ucfirst($pages['description'])}}
                    </span>
                </div>
            </a>
		</article>
    @endforeach
@else
    No routes (pages)
@endif
</div>
@stop

@Push('js')
<script></script>
@endpush
