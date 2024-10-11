<?php
$title = 'Admins page';
$page_meta_description = 'admins page';
$page_meta_keywords = 'admins page';
$robots = 'NOINDEX, NOFOLLOW';
?>

@extends('layouts/index_admin')

@Push('css')
*{
   /* color: black; */
}
@endpush

@section('content')
@include('components/admin-pages-panel')

@stop
