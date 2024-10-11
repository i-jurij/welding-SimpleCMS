<?php
$title = 'Contacts edit form';
$page_meta_description = 'admins page, Contacts editing';
$page_meta_keywords = 'contacts, edit, form';
$robots = 'NOINDEX, NOFOLLOW';
?>

@extends('layouts/index_admin')
@section('content')
    <div class="content ">

        <div class="price">
        <form method="post" action="{{ url()->route('admin.contacts.update') }}" id="contacts_edit_form" class="margin_top_1rem">
            @csrf
            <table class="table">
                <tr>
                    <th>ID</th>
                    <th>Type</th>
                    <th>Data</th>
                </tr>
                @foreach ($contact_data as $data)
                <tr>
                    <td>{{$data['id']}}</td>
                    <td><input type="text" name="type" value="{{$data['type']}}" /></td>
                    <td><input type="text" name="data" value="{{$data['data']}}" required /></td>
                </tr>
                @endforeach
            </table>
            <input type="hidden" name="id" value="{{$data['id']}}" required />
            <div class="form-element mar">
                <button type="submit" form="contacts_edit_form" class="buttons" id="contacts_submit">Submit</button>
                <button type="reset" form="contacts_edit_form" class="buttons" id="contacts_reset">Reset</button>
            </div>
        </form>
        </div>

    </div>
@stop
