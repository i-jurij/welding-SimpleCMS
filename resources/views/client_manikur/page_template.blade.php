@php
if (isset($page_data) && is_array($page_data) && !empty($page_data[0])) {
    $title = $page_data[0]['title'];
    $page_meta_description = $page_data[0]['description'];
    $page_meta_keywords = $page_data[0]['keywords'];
    $robots = $page_data[0]['robots'];
    $content['page_content'] = $page_data[0]['content'];
} else {
    $title = 'Title';
    $page_meta_description = 'description';
    $page_meta_keywords = 'keywords';
    $robots = 'INDEX, FOLLOW';
    $content = 'CONTENT FOR DEL IN FUTURE';
}
@endphp

@extends('layouts/index')

@section('content')
    @if (!empty($menu)) <p class="content">{{$menu}}</p> @endif

    <div class="back shad rad pad margin_rlb1">
    @if (!empty($content['page_content']))
        {!! $content['page_content'] !!}
    @else
        No content
    @endif
    </div>
@stop
