@php
if (isset($page_data) && is_array($page_data) && !empty($page_data[0])) {
    // title get from $this_show_method_data['name']
    $title = $page_data[0]["title"];
    // page_meta_description get from $data['cat']['description']
    $page_meta_description = $page_data[0]["description"];
    $page_meta_keywords = $page_data[0]["keywords"];
    $robots = $page_data[0]["robots"];
    $content['content'] = $page_data[0]["content"];
} else {
    $title = "Title";
    $page_meta_description = "description";
    $page_meta_keywords = "keywords";
    $robots = "INDEX, FOLLOW";
    $content['content'] = "CONTENT FOR DEL IN FUTURE";
}

@endphp


@extends("layouts/index")

@section("content")

    @if (!empty($menu)) <p class="content">{{$menu}}</p> @endif

    @if(is_string($this_show_method_data))
        <p class="back shad p-4 m-4">{{$this_show_method_data}}</p>
    @endif

    @if (!empty($this_show_method_data))
        @include('components.back_button_js')

        @php
            $title = (!empty($this_show_method_data['cat']['name'])) ? $this_show_method_data['cat']['name'] : (!empty($this_show_method_data['serv']) ? $this_show_method_data['serv']['name'] : '');
            $page_meta_description = (!empty($this_show_method_data['cat']['description'])) ? $this_show_method_data['cat']['description'] : ((!empty($this_show_method_data['serv']['description'])) ? $this_show_method_data['serv']['description'] : '');
            $page_meta_keywords = str_replace(' ', ', ', $page_meta_description);
            $robots = "INDEX, FOLLOW";
        @endphp

        @if (!empty($this_show_method_data['cat']))
            @php
                $cat = $this_show_method_data['cat'];
                $img_cat = DIRECTORY_SEPARATOR.'images'.DIRECTORY_SEPARATOR.$cat['image'];
            @endphp
            <article class="main_section_article back mt-4">
                <div class="main_section_article_imgdiv">
                    <img src="{{asset('storage'.$img_cat)}}" alt="Фото {{$cat['name']}}" class="main_section_article_imgdiv_img" />
                </div>
                <div class="main_section_article_content mt-4">
                    <h3 class="font-bold text-lg">{{$cat['name']}}</h3>
                    <span>{{$cat['description']}}</span>
                </div>
            </article>

            @if (!empty($this_show_method_data['serv']))
                @foreach ($this_show_method_data['serv'] as $ke => $serv)
                    @php
                        $img_serv = DIRECTORY_SEPARATOR.'images'.DIRECTORY_SEPARATOR.$serv['image'];
                    @endphp
                    @if (empty($data['serv'][$ke]['category_id']) || $data['serv'][$ke]['category_id'] === '')
                        <article class="main_section_article back mt-4">
                            <div class="main_section_article_imgdiv">
                                <img src="{{asset('storage'.$img_serv)}}" alt="Фото {{$serv['name']}}" class="main_section_article_imgdiv_img" />
                            </div>
                            <div class="main_section_article_content  mt-4">
                                <h3 class="font-bold">{{$serv['name']}}</h3>
                                <span>{{$serv['description']}}</span><br />
                                <span>От {{$serv['price']}} руб.</span>
                            </div>
                        </article>
                    @endif
                @endforeach
            @endif

        @endif

        @if (empty($this_show_method_data['cat']) && !empty($this_show_method_data['serv']))
            @php
                $serv = $this_show_method_data['serv'];
                $img_serv = DIRECTORY_SEPARATOR.'images'.DIRECTORY_SEPARATOR.$serv['image'];
            @endphp
            <article class="back shad p-6 my-4 mx-auto inline-block">
                    <img
                        src="{{asset('storage'.$img_serv)}}"
                        alt="Фото {{$serv['name']}}"
                        class="w-3/5 max-w-xs ms-auto me-auto md:ms-0 md:me-6 md:float-left"
                    />
                <div class="max-w-4xl">
                    <h3 class="hidden font-bold text-lg">{{$serv['name']}}</h3>
                    <p class="text-justify mt-4 md:mt-0 mb-4 mx-auto">{{$serv['description']}}</p>
                     <p>От <span class="font-bold">{{$serv['price']}}</span> руб.</p>
                </div>
            </article>
            <br>
            @if (!empty($this_show_method_data['content']))
                <article class="back shad p-4 my-4 mx-4">
                    <div class=" ">
                        {!!$this_show_method_data['content']!!}
                    </div>
                </article>
            @endif
        @endif

    @else
        <article class="main_section_article back mt-4">
            <div class="main_section_article_imgdiv p-4">
                <h2 class="font-bold text-xl">Расценки</h2>
            </div>
            <div class="main_section_article_content">
                <h2 class="font-bold">{{$title}}</h2>
                @if (!empty($data['min_price']))
                    @foreach ($data['min_price'] as $k => $v)
                        <span>{{$k}} - от {{$v}} руб.</span><br />
                    @endforeach
                @endif
                <br />
                <a href="{{url('/price#'.$page_data[0]['alias'])}}" style="text-decoration: underline;">Прайс</a>
            </div>
        </article>

        @if (!empty($data['cat']))
            @foreach ($data['cat'] as $key => $cat)
            @php
                $img_cat = DIRECTORY_SEPARATOR.'images'.DIRECTORY_SEPARATOR.$cat['image'];
            @endphp
                <article class="main_section_article back mt-4">
                <a href="<?php echo url('/'.$page_data[0]['alias'].'/category/'.$cat['id']); ?>">
                    <div class="main_section_article_imgdiv">
                        <img src="{{asset('storage'.$img_cat)}}" alt="Фото {{$cat['name']}}" class="main_section_article_imgdiv_img" />
                    </div>
                    <div class="main_section_article_content mt-4">
                        <h3 class="font-bold text-lg">{{$cat['name']}}</h3>
                            @if (!empty($data['serv']))
                                @foreach ($data['serv'] as $k => $serv)
                                    @if ($serv['category_id'] == $cat['id'])
                                        <span>{{$serv['name']}} от {{$serv['price']}} руб.</span><br />
                                    @endif
                                @endforeach
                            @endif
                     </div>
                </a>
                </article>
            @endforeach
        @endif

        @if (!empty($data['serv']))
            @foreach ($data['serv'] as $ke => $serv)
                @php
                    $img_serv = DIRECTORY_SEPARATOR.'images'.DIRECTORY_SEPARATOR.$serv['image'];
                @endphp
                @if (empty($data['serv'][$ke]['category_id']) || $data['serv'][$ke]['category_id'] === '')
                    <article class="main_section_article back mt-4">
                    <a href="<?php echo url('/'.$page_data[0]['alias'].'/service/'.$data['serv'][$ke]['id']); ?>">
                        <div class="main_section_article_imgdiv">
                            <img src="{{asset('storage'.$img_serv)}}" alt="Фото {{$serv['name']}}" class="main_section_article_imgdiv_img" />
                        </div>
                        <div class="main_section_article_content mt-4">
                            <h3 class="font-bold">{{$data['serv'][$ke]['name']}}</h3>
                            <!-- <span>{{$data['serv'][$ke]['description']}}</span><br /> -->
                            <span>От {{$data['serv'][$ke]['price']}} руб.</span>
                        </div>
                    </a>
                    </article>
                @endif
            @endforeach

        @endif

    @endif
@stop
