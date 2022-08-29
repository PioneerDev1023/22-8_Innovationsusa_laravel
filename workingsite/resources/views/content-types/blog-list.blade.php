@extends('master')

@section('meta_tags')
    {!! SEOMeta::generate() !!}
    {!! OpenGraph::generate() !!}
    {!! Twitter::generate() !!}
    {!! JsonLd::generate() !!}
@endsection

@section('main_content')

    <div id="catalog-list" class="projects">
        @if($pageType == "all")
            <h1 class='text-center'>WALLCOVERING BLOG POST</h1>
        @else
            <h1 class='text-center'>WALLCOVERING BLOG POST({{$category->title}})</h1>
        @endif
        <div id="padbottom-content" class="col-md-10 mx-auto container">
            <ul class="row">
                @if($pageType == "all")
                    <div class="col-sm-12 row"> 
                    @foreach ($list as $blog)                                                   
                        <li class="col-6 col-md-4">
                            <div class="inner">
                                <h3 class="skuWallTitle text-center">{{$blog->title}}</h3>
                                <a href='/blog/{{$blog->category->slug}}/{{$blog->slug}}' class="fabricName">
                                    <img src="/storage/{{$blog->header_img}}" alt="{{$blog->description}}"
                                            width="100%">
                                </a>
                                <p class="text-center">{{$blog->description}}</p>
                            </div> <!-- inner -->
                        </li>                        
                    @endforeach
                    </div>
                @else
                    @if(count($list) > 0)
                        @foreach($list as $blog)
                            <li class="col-6 col-md-4 ">
                                <div class="inner">
                                    <h3 class="skuWallTitle text-center">{{$blog->title}}</h3>
                                    <a href='/blog/{{$category->slug}}/{{$blog->slug}}' class="fabricName">
                                        <img src="/storage/{{$blog->header_img}}" alt="{{$blog->sub_title}}"
                                             width="100%">
                                    </a>
                                    <p class="text-center">{{$blog->sub_title}}</p>
                                </div> <!-- inner -->
                            </li>
                        @endforeach
                    @else
                        <h3 class="text-center col-sm-12">No data available</h3>
                    @endif
                @endif
            </ul>
        </div>
    </div>


@endsection
