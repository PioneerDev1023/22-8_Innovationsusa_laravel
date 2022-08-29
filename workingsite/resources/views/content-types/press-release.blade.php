@extends('master')

@section('meta_tags')
    {!! SEOMeta::generate() !!}
    {!! OpenGraph::generate() !!}
    {!! Twitter::generate() !!}
    {!! JsonLd::generate() !!}
@endsection

@section('main_content')
    @if($release != NULL)
    @if(isset($status) && $status != "PUBLISHED")
        <div class="alert alert-warning">UNPUBLISHED</div>
    @endif
        <div class="container mt-5">
            <div class="col-sm-8 col-md-8 m-auto">
                <h1>{{$release->title}}</h1>
                <p class="coll-desc1">{{$release->sub_title}}</p>
                <div class="row justify-content-center">
                    <div class="col-sm-12">
                        <div class="" data-rellax-speed="1">
                            @if(isset($release->header_img))
                                <a href="/">
                                    <img src="/storage/{{$release->header_img}}" width="100%" alt="{{$release->title}}">
                                </a>
                            @endif
                        </div>
                    </div>
                </div>
                @if(isset($release->header_caption) && $release->header_caption != "")
                    <p class="text-right col-sm-12 m-auto">{{$release->header_caption}}</p>
                @endif
                <div class="row my-3">
                    <div class="col-sm-12 coll-desc1 font-weight-bold">
                        {{$release->location}}
                    </div>
                </div>
                <div class="row">
                    <div class="col-sm-12 coll-desc1">
                        {!! $beforeText !!}
                    </div>
                </div>
                <div class="row">
                    <div class="col-sm-12 coll-desc1">
                        <div class="float-sm-left mr-sm-4 my-sm-3">
                            <a href="/">
                              @if(isset($release->footer_img) && $release->footer_img != "")
                                <img class="float-md-left mr-3" src="/storage/{{$release->footer_img}}">
                                @endif
                            </a>
                            @if(isset($release->footer_caption) && $release->footer_caption != "")
                                <p class="text-right col-sm-12 m-auto"
                                   style="font-size: 1rem;">{{$release->footer_caption}}</p>
                            @endif
                        </div>
                        {!! $afterText !!}
                    </div>
                </div>
            </div>
        </div>
    @else
        <h3 class="text-center">There are no press releases.</h3>
    @endif

@endsection
