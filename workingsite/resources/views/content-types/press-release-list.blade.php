@extends('master')

@section('meta_tags')
{!! SEOMeta::generate() !!}
{!! OpenGraph::generate() !!}
{!! Twitter::generate() !!}
{!! JsonLd::generate() !!}
@endsection

@section('main_content')

<div id="catalog-list" class="projects">
    <h1 class='text-center'>WALLCOVERING PRESS RELEASES</h1>
    <div id="padbottom-content" class="col-md-10 mx-auto container">
        <ul class="row">
            @foreach ($list as $release)
            <li class="col-6 col-md-4 ">
                <div class="inner">
                    <h3 class="skuWallTitle text-center">{{$release->title}}</h3>
                    <a href='/press-release/{{$release->slug}}' class="fabricName">
                        <img src="/storage/{{$release->header_img}}" alt="{{$release->sub_title}}" width="100%">
                    </a>
                    <p class="text-center">{{$release->sub_title}}</p>
                </div> <!-- inner -->
            </li>
            @endforeach

        </ul>
    </div>
</div>


@endsection
