<?php
$title = 'Contacts store';
$page_meta_description = 'admins page, Contacts store';
$page_meta_keywords = 'contacts, store';
$robots = 'NOINDEX, NOFOLLOW';
?>

@extends('layouts/index_admin')
@section('content')

    <div class="content margintb1 ">
        <div class="price">
            <p>Contact has been stored!</p>
                <table class="table">
                    <tr>
                        <th>N</th>
                        <th>Type</th>
                        <th>Data</th>
                    </tr>
                    <tr>
                        <td>1</td>
                        <td>{{$res['type']}}</td>
                        <td>{{$res['data']}}</td>
                    </tr>
                </table>
        </div>

    </div>

@stop
