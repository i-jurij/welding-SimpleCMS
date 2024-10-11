<?php
$title = 'Users action result';
$page_meta_description = 'admins page, users, result of action';
$page_meta_keywords = 'admins, user, delete, change';
$robots = 'NOINDEX, NOFOLLOW';
?>

@extends('layouts/index_admin')
@section('content')
    <div class="content margintb1 ">
            <p>{!! $res !!}</p>
    </div>

@stop
