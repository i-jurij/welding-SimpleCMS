<?php
$title = 'Completed callbacks';
$page_meta_description = 'admins page, Completed callbacks';
$page_meta_keywords = 'Completed callbacks';
$robots = 'NOINDEX, NOFOLLOW';
?>

@extends('layouts/index_admin')
@section('content')

    @if (Session::has('res'))
        <p class="content">{{Session::get('res')}}</p>
        @php
            Session::put('res', '');
        @endphp
    @endif

    @if (!empty($callbacks))
    <div class="content">
        @if ( (Auth::user()->status === 'Admin' || Auth::user()->status === 'admin') || (Auth::user()->status === 'Moder' || Auth::user()->status === 'moder') )
            <form method="post" action="{{ url()->route('admin.callbacks.remove') }}" class="zapis_usluga">
            @csrf
                <button type="submit" class="buttons" name="submit" value="clear">Очистить журнал</button>
            </form>
        @endif

        <div class="div_center pad" style="width:100%;max-width:1240px;">

                <table class="table">
                    <colgroup>
                    <col width="5%">
                    <col width="15%">
                    <col width="15%">
                    <col width="15%">
                    <col width="50%">
                    </colgroup>
                    <thead>
                    <tr>
                        <th>№</th>
                        <th>Дата, время</th>
                        <th>Номер</th>
                        <th>Имя</th>
                        <th>Сообщение</th>
                    </tr>
                    </thead>
                    <tbody>
                <?php
                $i = 1;
foreach ($callbacks as $value) {
    $date = new DateTimeImmutable($value->created_at);
    $data = $date->format('d.m.Y');
    $time = $date->format('H:i');
    ?>
                                    <tr>
                                    <td><?php echo $i; ?></td>
                                    <td style="text-align:left"><?php echo $data.' '.$time; ?></td>
                                    <td style="text-align:left; white-space: nowrap;"><?php echo $value->client['phone']; ?></td>
                                    <td style="text-align:left"><?php echo $value->name; ?></td>
                                    <td style="text-align:left"><?php echo $value->send; ?></td>
                                    </tr>
                                    <?php
    ++$i;
}
?>
                </tbody>
                </table>
        </div>
    </div>
    @else
        <p class="content">MESSAGE:<br> Empty callbacks.</p>
    @endif
@stop
