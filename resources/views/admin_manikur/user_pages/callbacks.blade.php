<?php
$st = (isset($stat)) ? $stat : '';

$title = $st.' callback';
$page_meta_description = 'admins page, Callbacks';
$page_meta_keywords = 'Callbacks';
$robots = 'NOINDEX, NOFOLLOW';

$fmt = new IntlDateFormatter(
    'ru-RU',
    IntlDateFormatter::FULL,
    IntlDateFormatter::FULL,
    'Europe/Moscow',
    IntlDateFormatter::GREGORIAN,
    'HH:mm, dd MMMM Y, EEEE'
);
// 'HH:mm, dd MMM yy, EEEE'

?>

@extends('layouts/index_admin')
@section('content')
    @if (!empty($res))
        <p class="content">{{$res}}</p>
    @elseif (!empty($callbacks))

    <div class="content">

        <div class="mb-4">
            Выберите номера по которым уже перезвонили, поставив галочку. <br />
            Нажмите кнопку "Подтвердить", чтобы убрать их из списка,<br />
            или "Сбросить", чтобы снять выбранное.
        </div>

        <form action="" method="post" class="">
            @csrf
            <div class="my-0.5">
            <input type="submit" class="buttons" name="submit" value="Подтвердить"/>
            <input type="reset" class="buttons" value="Cбросить"/>
            </div>
            <div class="flex flex-wrap items-center justify-center">
                @foreach ($callbacks as $cb)
                    <article class="shad p-4 m-4">
                        <div class="">{{$fmt->format($cb->created_at)}}</div>
                            <div class="">
                                <table class="text_left">
                                    <tr>
                                        <td>Имя:</td>
                                        <td>&nbsp;{{$cb->name}}</td>
                                    </tr>
                                    <tr>
                                        <td>Номер:</td>
                                        <td>&nbsp;{{$cb->client['phone']}}</td>
                                    </tr>
                                    <tr>
                                        <td>Сообщение:</td>
                                        <td>&nbsp;{{$cb->send}}</td>
                                    </tr>
                                </table>
                                <div class="mt-4">
                                    <label class="shad p-2"><input type="checkbox" name="id[]" value="{{$cb->id}}" /> Перезвонили</label>
                                </div>
                            </div>
                    </article>
                @endforeach
            </div>
        </form>
    </div>
    @else
        <p class="content">MESSAGE:<br> Empty callbacks.</p>
    @endif
@stop
