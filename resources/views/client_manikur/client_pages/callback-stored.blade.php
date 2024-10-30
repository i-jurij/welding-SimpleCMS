@php
    $title = "Ожидайте звонка";
    $page_meta_description = "В скором времени ожидайте обратный вызов.";
    $page_meta_keywords = "Обратный, вызов, звонок, перезвоним";
    $robots = "INDEX, FOLLOW";

@endphp

@extends("layouts/index")
@section("content")
@if (!empty($menu)) <p class="content">{{$menu}}</p> @endif
    @if (!empty($res) && is_array($res))
        <p class="content">
            @foreach ($res as $re)
                {{$re}}<br>
            @endforeach
        </p>
    @elseif (!empty($res) && is_string($res)) <p class="content">{{$res}}</p>
    @else
        <p class="content">No data</p>
    @endif
@stop
