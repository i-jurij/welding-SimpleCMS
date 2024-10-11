@php
$title = "Price edit";
$page_meta_description = "Price edit page";
$page_meta_keywords = "Price edit";
$robots = "NOINDEX, NOFOLLOW";
@endphp
@extends("layouts/index_admin")

@section("content")

<div class="content">

@if (!empty($data['res']))
    @if (is_array($data['res']))
        @php
            print_r($data['res'])
        @endphp
    @elseif (is_string($data['res']))
        <p>{{$data['res']}}</p>
    @endif
@elseif (!empty($data['serv']))
    <div class="form_radio_btn margin_bottom_1rem" style="width:85%;">
        <p class="pad margin_bottom_1rem">В строке нужной услуги кликните по ячейке в колонке с ценой, введите данные, нажмите кнопку Сохранить.</p>
        <div class="price">
            <form action="{{url()->route('admin.price.update')}}" method="post" name="price_form" id="price_form" >
            @csrf
                <table class="table price_form_table">
                    <caption class=""><b>{{$data['title']}}</b></caption>
                    <colgroup>
                        <col width="10%">
                        <col width="65%">
                        <col width="25%">
                    </colgroup>
                    <thead>
                        <tr>
                            <th>№</th>
                            <th>Услуга</th>
                            <th>Цена</th>
                        </tr>
                    </thead>
                    <tbody>

    @php
        $i = 1;
    @endphp
    @foreach ($data['serv'] as $cat_name => $serv_arr)
        @if ($cat_name != 'page_serv')
            <tr><td colspan="3">{{$cat_name}}</td></tr>
            @foreach ($serv_arr as $serv_name => $cat_serv_price)
                @php
                    $ar = explode('#', $cat_serv_price);
                    $price = $ar[1];
                    $id = $ar[0];
                @endphp
                <tr>
                    <td>{{$i}}</td>
                        <td style="text-align:left">{{$serv_name}}</td>
                        <td class="td" id="serv_id[{{$id}}]">{{$price}}</td>
                </tr>
                @php
                    ++$i;
                @endphp
            @endforeach
        @else
            <td colspan="3">Услуги вне категорий</td>
            @foreach ($serv_arr as $servv_name => $serv_price)
                @php
                    $sar = explode('#', $serv_price);
                    $sprice = $sar[1];
                    $sid = $sar[0];
                @endphp
                <tr>
                    <td>{{$i}}</td>
                        <td style="text-align:left">{{$servv_name}}</td>
                        <td class="td" id="serv_id[{{$sid}}]">{{$sprice}}</td>
                </tr>
                @php
                    ++$i;
                @endphp
            @endforeach
        @endif
    @endforeach

                    </tbody>
                </table>
                <div class="margintb1" id="form_buttons" >
                    <button type="submit" name="submit" class="buttons" form="price_form" />Сохранить</button>
                    <input type="reset" class="buttons" form="price_form" value="Сбросить" />
                </div>
            </form>
        </div>
    </div>
@else
    @if (!empty($data['service_page']))
        <form action="{{url()->route('admin.price.post_edit')}}" method="post" id="form_price_edit" >
        @csrf
                <div class="form_radio_btn margin_bottom_1rem" style="width:85%;">
                    <p class="pad margin_bottom_1rem">Выберите страницу для редактирования расценок:</p>
                    @foreach ($data['service_page'] as $value)
                        <label>
                            <input type="radio" name="id" value="{{$value['id']}}" required />
                            <span>{{$value['title']}}</span>
                        </label>
                    @endforeach
                </div>
                <div class="margintb1" id="form_price_edit_buttons" >
                    <button type="submit" name="submit" class="buttons" form="form_price_edit" />Далее</button>
                    <input type="reset" class="buttons" form="form_price_edit" value="Сбросить" />
                </div>
            </form>
    @else
        'No data'
    @endif
@endif
</div>

<script>
document.addEventListener('DOMContentLoaded', function () {
    $ (function () {// эквивалентна вкладке тела на странице плюс событие onload
                // Найти все узлы TD
        var tds=$(".price_form_table .td");
                // Добавить событие щелчка для всех TD
        tds.click(function(){
                        // Получить объект текущего клика
            var td=$(this);
                        // Удалите текущий текстовый контент TD
           var oldText=td.text();
           var idstr=td.attr('id');
                      // Создать текстовое поле, установите значение текстового поля сохранено значение
           var input=$('<input type="number" name="'+idstr+'" min="0" step="10" style="width:100%;" value="'+oldText+'" />');
                      // Установите содержимое текущего объекта TD для ввода
           td.html(input);
                      // Установите флажок Click события текстового поля
           input.click(function(){
               return false;
           });
                      // Установите стиль текстового поля
           input.css("border-width","0");
           //input.css("font-size","1rem");
           input.css("text-align","center");
                      // Установите ширину текстового поля, равная ширине TD
           input.width(td.width());
                      // Запустите полное событие выбора, когда текстовое поле получает фокус
           input.trigger("focus").trigger("select");
                      // вернуться к тексту, когда текстовое поле потеряло фокус
           input.blur(function(){
               var input_blur=$(this);
           });
        });

        // удаление полей ввода при нажатии кнопки сброса
        $("#price_form").on('reset', function(){
            let td = $('.td');
            td.each( function() {
                let inp = $(this).find('input');
                if ( inp.val() !== '' ) {
                    let price = inp.val();
                    inp.remove();
                    $(this).html(price);
                    //console.log(price+'\n');
                }
            });
        });

   });
}, false);
</script>

@stop
