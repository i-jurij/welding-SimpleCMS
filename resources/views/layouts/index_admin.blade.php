<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
  <meta charset="utf-8">
  <!--
  <meta name="referrer" content="origin-when-cross-origin">
-->
  <meta http-equiv="content-type" content="text/html; charset=utf-8">

  <title>{{ $title }}</title>
  <meta name="description" content="{{$page_meta_description}}">
  <META NAME="keywords" CONTENT="{{$page_meta_keywords}}">
  <meta name="viewport" content="width=device-width, height=device-height, initial-scale=1.0">
  <META NAME="Robots" CONTENT="{{ $robots }}">
  <meta name="author" content="I-Jurij">
  @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body>
@include('layouts.navigation')
    <div class="wrapper">

        <div class="main ">
            <section class="main_section">
                <div class="flex flex_top">
                    <!--
                    <div class="content title">
                        <p class="nav">
                        @include('components/admin-pages-menu')
                        </p>
                    </div>
                    -->
                    <p class="content " id="page_title">{{$title}}</p>
                    @if(session()->has('errors') || $errors->any())
                        <div class="zapis_usluga back shad pad margin_rlb1 alert alert-danger error fade in">
                            <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
                            <h4>Following errors occurred:</h4>
                            <ul>
                                @foreach ($errors->all() as $error)
                                    @if (is_array($error))
                                        @foreach ($error as $mes)
                                            <li>{{ $mes }}</li>
                                        @endforeach
                                    @else
                                        <li>{{ $error }}</li>
                                    @endif
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    @yield('content')
                    @if (url()->current() !== url()->route('admin.home'))

                        @php $pieces = explode('/', Request::path()); @endphp
                        @if (count($pieces) > 2)
                            <p style="height:5rem;"></p>
                            @include('components/button_go_to_admin_home')
                            @include('components/history_back_button_js')
                        @else
                            <p style="height:5rem;"></p>
                            @include('components/button_go_to_admin_home')
                        @endif

                    @endif
                </div>
            </section>
        </div>
    </div>

    @stack('js')

    <script type="module" src="{{asset('storage'.DIRECTORY_SEPARATOR.'js'.DIRECTORY_SEPARATOR.'form-recall-mask.js')}}"></script>
</body>

</html>
