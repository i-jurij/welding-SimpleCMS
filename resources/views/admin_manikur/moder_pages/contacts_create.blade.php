<?php
$title = 'Contacts creating';
$page_meta_description = 'admins page, Contacts creating';
$page_meta_keywords = 'contacts, creating';
$robots = 'NOINDEX, NOFOLLOW';
?>

@extends('layouts/index_admin')
@section('content')
<div class="content">
    <p>
        Create contacts data:<br />
        type: adres, tlf, email, vk, telegram, watsapp, viber or other:<br />
        data: value of contact.<br />
    </p>
</div>

<div>
<form action="{{ route('admin.contacts.store') }}" method="post" class="content">
    @csrf
    <div class="form-recall-main ">

        <div class="mar">
            <div class="mar">
                <input type="text" placeholder="Type of contact eg tlf" name="type" maxlength="100" />
                <input type="text" placeholder="Value eg +7 978 000 11 22" name="data" maxlength="100" />
            <div id="error"><small></small></div>
        </div>

        <div class="mar">
            <button class="buttons form-recall-submit" type="submit">Submit</button>
            <button class="buttons form-recall-reset" type="reset" onclick="Reset()">Reset</button>
        </div>

    </div>
</form>
</div>
@stop
