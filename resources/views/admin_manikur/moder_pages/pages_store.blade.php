
<?php
$title = 'Pages store';
$page_meta_description = 'admins page, Pages store';
$page_meta_keywords = 'Pages, store';
$robots = 'NOINDEX, NOFOLLOW';
?>

@extends('layouts/index_admin')
@section('content')

    <div class="content margintb1 ">
        <div class="price">
            @if (!empty($create_cat_serv))
                @include($create_cat_serv)
            @endif

            @if (!empty($img_res)) {!!$img_res!!} @endif
            <p class="margin_bottom_1rem ">Pages data has been stored!</p>
                <table class="table">
                    <thead>
                    <tr>
                        <th>Type</th>
                        <th>Data</th>
                    </tr>
                    </thead>
                    <tbody class="text_left">
                        @foreach ($res as $key => $value)
                        <tr>
                            <td>@php echo mb_ucfirst($key); @endphp</td>
                            <td>{{$value}}</td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
        </div>

    </div>

@stop
