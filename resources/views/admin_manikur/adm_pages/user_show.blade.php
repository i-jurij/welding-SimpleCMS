<?php
$title = 'User change';
$page_meta_description = 'admins page, deleting of users';
$page_meta_keywords = 'admins, user, delete';
$robots = 'NOINDEX, NOFOLLOW';
?>

@extends('layouts/index_admin')
@section('content')
    <div class="content margintb1 ">
        <div>
        @if (Auth::user()['status']==='admin')

            <p class="margintb1">
                Change the data only in the fields that you want to change.
            </p>
            <form method="post" action="{{ route('admin.user.store') }}" id="users_store" class="pad form_del_ch">
            @csrf
                <div class="form-element margintb1 text_center">
                    @foreach ($content as $user)
                        <div class="shad rad display_inline_block ">
                            <div class="table_body">
                                <input type="hidden" value="{{$user->id}}" name="change_userid" />
                                <label class="table_row ">
                                    <span class="table_cell text_left">Username</span>
                                        <span class="table_cell">
                                            <input type="text" class="user_name" id ="user_{{$user->id}}" value="{{$user->name}}" name="change_name" minlength="3" maxlength="25" pattern="^[a-zA-Zа-яА-ЯёЁ0-9-_]{3,25}$" />
                                        </span>
                                </label>
                                <label class="table_row ">
                                    <span class="table_cell text_left">Email</span>
                                        <span class="table_cell">
                                            <input type="email" class="user_name" id ="email_{{$user->id}}" value="{{$user->email}}" name="change_email" minlength="10" maxlength="150" />
                                        </span>
                                </label>
                                <label class="table_row">
                                    <span class="table_cell text_left">Status</span>
                                        <span class="table_cell">
                                            <input type="text" class="user_status" id ="userstatus_{{$user->id}}" value="{{$user->status}}" name="change_status" minlength="3" maxlength="25" pattern="^(admin|moder|user)$" />
                                        </span>
                                </label>
                                <label class="table_row">
                                    <span class="table_cell text_left">Password</span>
                                        <span class="table_cell">
                                            <input type="password" class="user_password" id ="userpass_{{$user->id}}" name="change_pass" minlength="4" maxlength="150" />
                                        </span>
                                </label>
                            </div>
                        </div>
                    @endforeach
                </div>
                <div class="form-element mar">
                    <button type="submit" form="users_store" class="buttons" id="user_ch">Submit</button>
                    <button type="reset" form="users_store" class="buttons">Reset</button>
                </div>
            </form>
            @else
            You are not authorized.
            @endif
        </div>
    </div>

@stop
