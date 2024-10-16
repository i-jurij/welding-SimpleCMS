<?php
$title = 'Users list';
$page_meta_description = 'admins page, deleting of users';
$page_meta_keywords = 'admins, user, delete';
$robots = 'NOINDEX, NOFOLLOW';
?>

@extends('layouts/index_admin')
@section('content')

<div class="content my-1 ">
    <div>
        @if (Auth::user()['status']==='admin')

        <p>
            WARNING!!!<br />
            You need to leave at least one user with the admin status.
        </p>

        @php
        $labelclass = '';
        $divclass = "";
        if (str_contains(\Request::getRequestUri(), "/admin/user/remove")) {
        $res_route = route('admin.user.remove');
        $labelclass = "checkbox-btn";
        $type = "checkbox";
        } elseif (str_contains(\Request::getRequestUri(), "/admin/user/change")) {
        $res_route = route('admin.user.show');
        $divclass = "form_radio_btn";
        $type = "radio";
        }
        @endphp

        <form method="post" action="{{ $res_route }}" id="users_shoose" class="p-1 form_del_ch">
            @csrf
            <div class="form-element my-1 {{$divclass}}">
                @foreach ($content as $user)
                <label class="{{$labelclass}}">
                    <input type="{{$type}}" id="user_{{$user->id}}" value="{{$user->id}}" name="user_id[]" />
                    <span>
                        <table class="text_left">
                            <tr>
                                <td>Name: </td>
                                <td>{{$user->name}}</td>
                            </tr>
                            <tr>
                                <td>Email: </td>
                                <td>{{$user->email}}</td>
                            </tr>
                            <tr>
                                <td>Status: </td>
                                <td>{{$user->status}}</td>
                            </tr>
                        </table>
                    </span>
                </label>
                @endforeach
            </div>
            <div class="form-element" id="paginator">{!! $content->render() !!}</div>
            <div class="form-element m-1 p-1">
                <button type="submit" form="users_shoose" class="buttons" id="del_ch">Submit</button>
                <button type="reset" form="users_shoose" class="buttons">Reset</button>
            </div>
        </form>
        @else
        You are not authorized.
        @endif
    </div>
</div>

@stop
