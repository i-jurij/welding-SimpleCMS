<?php
$title = 'Сварочные работы, изделия и конструкции из металла';
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
        } elseif ($pages['alias'] === 'gates') {
            $sort_pages[2] = $pages;
        } elseif ($pages['alias'] === 'canopy') {
            $sort_pages[3] = $pages;
              } elseif ($pages['alias'] === 'stairs') {
            $sort_pages[4] = $pages;
              } elseif ($pages['alias'] === 'fence') {
            $sort_pages[5] = $pages;
              } elseif ($pages['alias'] === 'other') {
            $sort_pages[6] = $pages;
              } elseif ($pages['alias'] === 'service6') {
            $sort_pages[7] = $pages;
        } elseif ($pages['alias'] === 'gallery') {
            $sort_pages[8] = $pages;
        } elseif ($pages['alias'] === 'about') {
            $sort_pages[9] = $pages;
        } elseif ($pages['alias'] === 'map') {
            $sort_pages[10] = $pages;
        } elseif ($pages['alias'] === 'price') {
            $sort_pages[11] = $pages;
        } elseif ($pages['alias'] === 'persinfo') {
            $sort_pages[12] = $pages;
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
        <article class="main_section_article back">
            <a href="{{url('/'.$pages['alias'])}}" >
                <div class="main_section_article_imgdiv mb-4">
                    <img src="{{asset('storage/'.$pages['img'])}}" alt="{{$pages['title']}}" class="main_section_article_imgdiv_img" />
                </div>
                <div class="main_section_article_content">
                    <h2 class="font-bold">{{mb_ucfirst($pages['title'])}}</h2>
                    <p class="">
                        {{mb_ucfirst($pages['description'])}}
                    </p>
                </div>
            </a>
		</article>
    @endforeach
@else
        <article class="main_section_article back">
            <a class="" href="" >
                <div class="main_section_article_imgdiv">
                <img src="{{asset('storage/images/ddd.jpg')}}" alt="No picture" class="main_section_article_imgdiv_img" />
                </div>
                <div class="main_section_article_content mt-1">
                    <h2>Empty</h2>
                    <span>
                        No routes (pages)
                    </span>
                </div>
            </a>
		</article>
@endif
</div>
@stop

@Push('js')
<script></script>
@endpush
