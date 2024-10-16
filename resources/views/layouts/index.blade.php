<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}"  class="bgcolor">

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
<body class="">
    <div class="wrapper">
        @include('layouts/header')

        <div class="main ">
            <section class="main_section">
                <div class="flex flex_top">
                     @if (url()->current() !== url()->route('client.home'))
                        <div class="content title">
                            <h1>{{ $title }}</h1>
                        </div>
                    @endif
                    @if ($errors->any())
                        <div class="zapis_usluga back shad p-1 my-1 alert alert-danger error">
                            <ul>
                                @foreach ($errors->all() as $error)
                                    @if (is_array($error))
                                        @foreach ($error as $mes)
                                            <li>{{ $mes }}</li>
                                        @endforeach
                                    @else
                                        @if ($error === "The client password field confirmation does not match.")
                                            <li>{{ "Неверный пароль :(" }}</li>
                                        @else
                                            <li>{{ $error }}</li>
                                        @endif
                                    @endif
                                @endforeach
                            </ul>
                        </div>
                    @endif
                    @yield('content')

                    @if (url()->current() !== url()->route('client.home'))
                        @php $pieces = explode('/', Request::path()); @endphp
                        @if (count($pieces) > 3)
                            @include('components/back_button')
                        @else
                            @include('components/button_client_home')
                        @endif
                    @endif
                </div>
            </section>
        </div>

        @include('layouts/footer')
    </div>
    <script src="{{asset('storage'.DIRECTORY_SEPARATOR.'js'.DIRECTORY_SEPARATOR.'form-recall-mask.js')}}"></script>
</body>

</html>
