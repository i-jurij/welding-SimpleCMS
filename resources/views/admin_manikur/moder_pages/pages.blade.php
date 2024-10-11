<?php
$title = 'Pages editing';
$page_meta_description = 'admins page, Pages editing';
$page_meta_keywords = 'Pages editing';
$robots = 'NOINDEX, NOFOLLOW';

$sp = false;
if ((bool) mb_strstr(url()->current(), 'service_page')) {
    $sp = true;
    $title = 'Service page editing';
    $page_meta_description = 'admins page, service page editing';
}
?>

@extends('layouts/index_admin')
@section('content')
    @if (!empty($res)) <p class="content">MESSAGE:<br> {!! $res !!}</p> @endif

    @if (!empty($pages))
        @if (is_array($pages))
        <div class="content margintb1 ">
        <div class="">
                <table class="table">
                    <thead>
                    <tr>
                        <th>N</th>
                        <th>Alias</th>
                        <th>Title</th>
                        <th>Actions</th>
                    </tr>
                    </thead>
                    <tbody class="text_left">
                        @foreach ($pages as $key => $page)
                            @if ($sp)
                                @if ($page['service_page'] === 'yes')
                                    <tr>
                                        <td>{{$key + 1}}</td>
                                        <td>{{$page['alias']}}</td>
                                        <td>{{$page['title']}}</td>
                                        <td>
                                        <form method="post" action="{{ url()->route('admin.pages.edit.form') }}" class="display_inline_block">
                                        @csrf
                                            <button type="submit" class="buttons" value="{{$page['id']}}" name="id">Edit</button>
                                        </form>
                                        <form method="post" action="{{ url()->route('admin.pages.remove') }}" class="display_inline_block">
                                        @csrf
                                            <button type="submit" class="buttons" value="{{$page['id']}}plusplus{{$page['alias']}}plusplus{{$page['img']}}plusplus{{$page['single_page']}}plusplus{{$page['service_page']}}" name="id">Remove</button>
                                        </form>
                                        </td>
                                    </tr>
                                @endif
                            @else
                                <tr>
                                    <td>{{$key + 1}}</td>
                                    <td>{{$page['alias']}}</td>
                                    <td>{{$page['title']}}</td>
                                    <td>
                                    <form method="post" action="{{ url()->route('admin.pages.edit.form') }}" class="display_inline_block">
                                    @csrf
                                        <button type="submit" class="buttons" value="{{$page['id']}}" name="id">Edit</button>
                                    </form>
                                    <form method="post" action="{{ url()->route('admin.pages.remove') }}" class="display_inline_block">
                                    @csrf
                                        <button type="submit" class="buttons" value="{{$page['id']}}plusplus{{$page['alias']}}plusplus{{$page['img']}}plusplus{{$page['single_page']}}plusplus{{$page['service_page']}}" name="id">Remove</button>
                                    </form>
                                    </td>
                                </tr>
                            @endif
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
        @else
            <p class="content">MESSAGE:<br> {{$pages}}</p>
        @endif
    @else
        <p class="content">MESSAGE:<br> No data</p>
    @endif
@stop
