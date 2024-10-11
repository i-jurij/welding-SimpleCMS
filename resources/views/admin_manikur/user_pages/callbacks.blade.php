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

        <div class="margin_bottom_1rem">
            Выберите номера по которым уже перезвонили, поставив галочку. <br />
            Нажмите кнопку "Удалить", чтобы убрать их из списка,<br />
            или "Сбросить", чтобы снять выбранное.
        </div>

        <form action="" method="post" class="">
            @csrf
            <div class="margintb05">
            <input type="submit" class="buttons" name="submit" value="Удалить"/>
            <input type="reset" class="buttons" value="Cбросить"/>
            </div>
            <div class="flex adm_recall_article_container">
                @foreach ($callbacks as $cb)
                    <article class="adm_recall_article ">
                        <div class="">{{$fmt->format($cb->created_at)}}</div>
                            <div class="margin_botom_1rem">
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
                                <p class="margin_top_1rem">
                                    <label class="shad pad"><input type="checkbox" name="id[]" value="{{$cb->id}}" /> Перезвонили</label>
                                </p>
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
