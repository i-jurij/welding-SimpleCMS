<?php
$title = 'Masters data edit';
$page_meta_description = 'admins page, Masters data editing';
$page_meta_keywords = 'Masters data editing';
$robots = 'NOINDEX, NOFOLLOW';
?>

@extends('layouts/index_admin')
@section('content')

@if (!empty(session('mes')))
    @if (is_array(session('mes')))
        <p class="content">MESSAGE:<br>
            @foreach (session('mes') as $re)
                {{$re}}<br>
            @endforeach
        </p>
    @elseif (is_string(session('mes')))
        <p class="content">MESSAGE:<br> {{session('mes')}}</p>
    @endif
@endif

    @if (!empty($masters))
    <div class="content">
        @if (is_array($masters))
            <table class="table masters_edit">
                <caption>Masters</caption>
                <thead>
                    <tr>
                        <th>N</th>
                        <th>Photo</th>
                        <th>Name</th>
                        <th>Phone</th>
                        <th>Spec</th>
                        <th>Hired Принят</th>
                        <th>Dismissed Уволен</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody class="text_left">
                    @foreach ($masters as $key => $master)
                        @php $img = 'images'.DIRECTORY_SEPARATOR.'ddd.jpg' @endphp
                        @if (!empty($master['master_photo']) && file_exists(storage_path('app'.DIRECTORY_SEPARATOR.'public'.DIRECTORY_SEPARATOR.$master['master_photo'])))
                            @php $img = $master['master_photo'] @endphp
                        @elseif (empty($master['master_photo']) && file_exists(storage_path('app'.DIRECTORY_SEPARATOR.'public'.DIRECTORY_SEPARATOR.'images'.DIRECTORY_SEPARATOR.'masters'.DIRECTORY_SEPARATOR.mb_strtolower(sanitize(translit_to_lat($master['master_phone_number']))).'.jpg')))
                            @php $img = 'images'.DIRECTORY_SEPARATOR.'masters'.DIRECTORY_SEPARATOR.mb_strtolower(sanitize(translit_to_lat($master['master_phone_number']))).'.jpg' @endphp
                        @endif

                    <tr>
                        <td>{{$key + 1}}</td>
                        <td><img src="{{asset('storage'.DIRECTORY_SEPARATOR.$img)}}" alt="Photo  {{$master['master_fam']}}" /></td>
                        <td>{{$master['master_name']}}<br> {{$master['sec_name']}}<br> {{$master['master_fam']}}</td>
                        <td>{!!str_replace(' ', '&nbsp;', $master['master_phone_number'])!!}</td>
                        <td>
                            @if (!empty($services) && !empty($services[$master['id']]))
                            <ul>
                                @foreach ($services[$master['id']] as $key => $service)
                                    @php
                                        list($page_id, $page_title) = explode('#', $key);
                                    @endphp
                                    <li class="">
                                    {{$page_title}}:
                                    <br>
                                    <button class="buttons" onclick="showall(this.id);" id="spec{{$master['id']}}{{$page_id}}">Show all</button>
                                    <br>
                                        <ul class="display_none" id="sspec{{$master['id']}}{{$page_id}}">
                                            @foreach ($service as $ke => $cats)
                                                @php
                                                    list($cat_id, $cat_name) = explode('#', $ke);
                                                @endphp
                                                @if ($cat_name !== 'page_serv')
                                                <li class="margin_top_1rem">{{$cat_name}}: </li>
                                                <ul>
                                                    @foreach ($cats as $k => $serv)
                                                    <li class="margin_rl1">{{$serv}}</li>
                                                    @endforeach
                                                </ul>
                                                @endif
                                            @endforeach
                                            @foreach ($service as $ke => $cats)
                                                @php
                                                    list($cat_id, $cat_name) = explode('#', $ke);
                                                    if ($cat_name === 'page_serv') $cat_name = 'Other';
                                                @endphp
                                                @if ($cat_name === 'Other')
                                                <li class="margin_top_1rem">{{$cat_name}}</li>
                                                <ul>
                                                    @foreach ($cats as $k => $serv)
                                                    <li class="margin_rl1">{{$serv}}</li>
                                                    @endforeach
                                                </ul>
                                                @endif
                                            @endforeach
                                        </ul>
                                    </li>
                                @endforeach
                            </ul>
                            @endif
                        </td>
                        <td>{{$master['data_priema']}}</td>
                        <td>{{$master['data_uvoln']}}</td>
                        <td>
                            <form method="post" action="{{ url()->route('admin.masters.edit.form') }}" class="display_inline_block">
                            @csrf
                                <button type="submit" class="buttons" value="{{$master['id']}}" name="id">Edit</button>
                            </form>
                            <form method="post" action="{{ url()->route('admin.masters.remove') }}" class="display_inline_block">
                            @csrf
                                <button type="submit" class="buttons" value="{{$master['id']}}plusplus{{$img}}" name="id">Remove</button>
                            </form>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        @elseif (is_string($masters))
            <p class="content">MESSAGE:<br> {{$masters}}</p>
        @endif
    </div>
    @endif

    @if (!empty($masters_dism) && is_array($masters_dism))
    <div class="content">
        <table class="table masters_edit">
            <caption>Dismissed masters. Уволенные мастера.</caption>
                <thead>
                    <tr>
                        <th>N</th>
                        <th>Photo</th>
                        <th>Name</th>
                        <th>Phone</th>
                        <th>Spec</th>
                        <th>Hired Принят</th>
                        <th>Dismissed Уволен</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody class="text_left">
                    @foreach ($masters_dism as $key => $master_dism)
                        @php $img = 'images'.DIRECTORY_SEPARATOR.'ddd.jpg' @endphp
                        @if (!empty($master_dism['master_photo']) && file_exists(storage_path('app'.DIRECTORY_SEPARATOR.'public'.DIRECTORY_SEPARATOR.$master_dism['master_photo'])))
                            @php $img = $master_dism['master_photo'] @endphp
                        @elseif (empty($master_dism['master_photo']) && file_exists(storage_path('app'.DIRECTORY_SEPARATOR.'public'.DIRECTORY_SEPARATOR.'images'.DIRECTORY_SEPARATOR.'masters'.DIRECTORY_SEPARATOR.mb_strtolower(sanitize(translit_to_lat($master_dism['master_phone_number']))).'.jpg')))
                            @php $img = 'images'.DIRECTORY_SEPARATOR.'masters'.DIRECTORY_SEPARATOR.mb_strtolower(sanitize(translit_to_lat($master_dism['master_phone_number']))).'.jpg' @endphp
                        @endif
                    <tr>
                        <td>{{$key + 1}}</td>
                        <td><img src="{{asset('storage'.DIRECTORY_SEPARATOR.$img)}}" alt="Photo  {{$master_dism['master_fam']}}" /></td>
                        <td>{{$master_dism['master_name']}}<br> {{$master_dism['sec_name']}}<br> {{$master_dism['master_fam']}}</td>
                        <td>{{$master_dism['master_phone_number']}}</td>
                        <td>
                        @if (!empty($services) && !empty($services[$master_dism['id']]))
                            <ul>
                                @foreach ($services[$master_dism['id']] as $key => $service)
                                    @php
                                        list($page_id, $page_title) = explode('#', $key);
                                    @endphp
                                    <li class="">
                                    {{$page_title}}:
                                    <br>
                                    <button class="buttons" onclick="showall(this.id);" id="spec{{$master_dism['id']}}{{$page_id}}">Show all</button>
                                    <br>
                                        <ul class="display_none" id="sspec{{$master_dism['id']}}{{$page_id}}">
                                            @foreach ($service as $ke => $cats)
                                                @php
                                                    list($cat_id, $cat_name) = explode('#', $ke);
                                                @endphp
                                                @if ($cat_name !== 'page_serv')
                                                <li class="margin_top_1rem">{{$cat_name}}: </li>
                                                <ul>
                                                    @foreach ($cats as $k => $serv)
                                                    <li class="margin_rl1">{{$serv}}</li>
                                                    @endforeach
                                                </ul>
                                                @endif
                                            @endforeach
                                            @foreach ($service as $ke => $cats)
                                                @php
                                                    list($cat_id, $cat_name) = explode('#', $ke);
                                                    if ($cat_name === 'page_serv') $cat_name = 'Other';
                                                @endphp
                                                @if ($cat_name === 'Other')
                                                <li class="margin_top_1rem">{{$cat_name}}</li>
                                                <ul>
                                                    @foreach ($cats as $k => $serv)
                                                    <li class="margin_rl1">{{$serv}}</li>
                                                    @endforeach
                                                </ul>
                                                @endif
                                            @endforeach
                                        </ul>
                                    </li>
                                @endforeach
                            </ul>
                            @endif
                        </td>
                        <td>{{$master_dism['data_priema']}}</td>
                        <td>{{$master_dism['data_uvoln']}}</td>
                        <td>
                            <form method="post" action="{{ url()->route('admin.masters.edit.form') }}" class="display_inline_block">
                            @csrf
                                <button type="submit" class="buttons" value="{{$master_dism['id']}}plusplus{{$img}}" name="id">Edit</button>
                            </form>
                            <form method="post" action="{{ url()->route('admin.masters.remove') }}" class="display_inline_block">
                            @csrf
                                <button type="submit" class="buttons" value="{{$master_dism['id']}}plusplus{{$img}}" name="id">Remove</button>
                            </form>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
    </div>
    @endif

    <script>
        /*
        function showall(id) {
            const EL = document.querySelector('#s'+id);
            if (EL) {
                if (EL.style.display == "") {
                    EL.style.display =  "block";
                    document.querySelector('#'+id).innerHTML = 'Hide';
                } else {
                    EL.style.display =  "";
                    document.querySelector('#'+id).innerHTML = 'Show All';
                }
            }
        }
        */
        function showall(id) {
            const EL = $('#s'+id);
            if (EL) {
                EL.slideToggle('slow');
            }
            if ($('#'+id).html() == 'Show all') {
                $('#'+id).html('Hide');
            } else {
                $('#'+id).html('Show all');
            }
        }
    </script>
@stop
