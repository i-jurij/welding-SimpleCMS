@php
$title = "Sign up";
$page_meta_description = "Appointment of client";
$page_meta_keywords = "Appointment, signup";
$robots = "NOINDEX, NOFOLLOW";
@endphp
@extends("layouts/index_admin")

@section("content")

<div class="content">
    @if (!empty($data))
        @if (is_array($data))
            @if (!empty($data['by_date']))
                @if (!empty($dateprevnext))
                    @php
                        $date = $dateprevnext;
                        $prev = date('Y-m-d', strtotime($date.'- 1 days')) ?? '';
                        $next = date('Y-m-d', strtotime($date.'+ 1 days')) ?? '';
                    @endphp
                    <p class="margin_rlb1">
                    <a href="{{url()->route('admin.master_signup.list')}}?prev={{$prev}}" class="back shad rad pad_tb05_rl1 display_inline_block">< </a>
                    <span class="back shad rad pad_tb05_rl1 display_inline_block" style="width:17rem;">{{date('l d M Y', strtotime($date))}}</span>
                    <a href="{{url()->route('admin.master_signup.list')}}?next={{$next}}" class="back shad rad pad_tb05_rl1 display_inline_block"> ></a>
                    </p>
                @endif
                <?php
                $res = '';
                foreach ($data['by_date'] as $master => $signup) {
                    $art = '';
                    foreach ($signup as $value) {
                        if (\Carbon\Carbon::parse($value['start_dt'])->toDateString() === $date) {
                            $art .= '<article class="main_section_article">
                                          <p>'.\Carbon\Carbon::parse($value['start_dt'])->format('H:i').'</p>
                                          <p>'.$value['service'].' </p>
                                          <p>'.$value['client'].'</p>
                                      </article>';
                        }
                    }
                    if (!empty($art)) {
                        $res .= '<div class="back shad rad pad margin_rlb1">';
                        // $res .= '<p><b>'.$master.'</b></p>';
                        $res .= $art;
                        $res .= '</div>';
                    } else {
                        // $res .= '<p class="pad">К мастеру нет записей на '.date('d.m.Y', strtotime($date)).'.</p>';
                    }
                }
                echo $res;
                ?>
            @endif
        @elseif (is_string($data))
            <p>{{$data}}</p>
        @endif
    @else
        No data from controller
    @endif
</div>

@stop
