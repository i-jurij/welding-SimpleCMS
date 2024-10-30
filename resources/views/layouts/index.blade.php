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
<body class="font-sans">
    <div style="width: 95%;" class="h-full max-w-screen-2xl mx-auto table">
        @include('layouts/header')

        <div class="w-full h-full ">
            <section class="items-start w-full relative mx-auto">

                <div class="back shad w-full mt-4 p-4 flex content-start">
                    <video width="60" autoplay muted loop class="me-4 ">
                        <source src="{{asset('storage/images/weld.mp4')}}" type="video/mp4">
                    </video>
                    <h1 class="w-full text-xl font-bold self-center">{{ $title }}</h1>
                </div>

                @if ($errors->any())
                        <div class="w-full back shad p-4 my-4 alert alert-danger error">
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
            </section>
        </div>

        @include('layouts/footer')
    </div>
    <script src="{{asset('storage'.DIRECTORY_SEPARATOR.'js'.DIRECTORY_SEPARATOR.'form-recall-mask.js')}}"></script>
</body>

</html>
