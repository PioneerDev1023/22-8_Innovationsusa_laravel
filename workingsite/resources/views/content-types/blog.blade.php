@extends('master')

@section('meta_tags')
    {!! SEOMeta::generate() !!}
    {!! OpenGraph::generate() !!}
    {!! Twitter::generate() !!}
    {!! JsonLd::generate() !!}
@endsection

@section('main_content')
    @if($blogContent != NULL)
    @if(isset($status) && !$status)
        <div class="alert alert-warning">UNPUBLISHED</div>
    @endif
        <div class="container mt-5">
            <div class="col-sm-8 col-md-8 m-auto">
                <h1>{{$blogContent->title}}</h1>
                <p class="coll-desc1">{{$blogContent->sub_title}}</p>
                <div class="row justify-content-center">
                    <div class="col-sm-12">
                        <div class="" data-rellax-speed="1">
                            @if(isset($blogContent->header_img))
                                <a href="/">
                                    <img src="/storage/{{$blogContent->header_img}}" width="100%"
                                         alt="{{$blogContent->title}}">
                                </a>
                            @endif
                        </div>
                    </div>
                </div>
                @if(isset($blogContent->header_caption) && $blogContent->header_caption != "")
                    <p class="text-right col-sm-12 m-auto">{{$blogContent->header_caption}}</p>
                @endif
                <div class="row my-3">
                    <div class="col-sm-12 coll-desc1 font-weight-bold">
                        {{$blogContent->location}} ({{$date}})
                    </div>
                </div>
                <div class="row">

                    <div class="col-sm-12 coll-desc1">

                        <div class="float-sm-left mr-sm-4 mb-sm-3 col-6">
                            <a href="/">
                                <img class="float-md-left mr-3 col-12" src="/storage/{{$blogContent->body_img1}}" style="width:100%">
                            </a>
                            @if(isset($blogContent->body_caption1) && $blogContent->body_caption1 != "")
                                <p class="text-right col-sm-12 m-auto"
                                   style="font-size: 1rem;">{{$blogContent->body_caption1}}</p>
                            @endif
                        </div>
                        {{$beforeText}}

                    </div>
                </div>
                <div class="row">

                    <div class="col-sm-12 coll-desc1">


                        <div class="float-sm-right mr-sm-4 mb-sm-3 col-6">
                            <a href="/">
                                <img class="float-md-left mr-3 col-12" src="/storage/{{$blogContent->body_img2}}" style="width:100%">
                            </a>
                            @if(isset($blogContent->body_caption2) && $blogContent->body_caption2 != "")
                                <p class="text-right col-sm-12 m-auto"
                                   style="font-size: 1rem;">{{$blogContent->body_caption2}}</p>
                            @endif
                        </div>

                        {{$middleText}}
                    </div>
                </div>

                <div class="row">

                    <div class="col-sm-12 coll-desc1">

                        <div class="float-sm-left mr-sm-4 mb-sm-3 col-6">
                            <a href="/">
                                <img class="float-md-left mr-3 col-12" src="/storage/{{$blogContent->footer_img}}" style="width:100%">
                            </a>
                            @if(isset($blogContent->footer_caption) && $blogContent->footer_caption != "")
                                <p class="text-right col-sm-12 m-auto"
                                   style="font-size: 1rem;">{{$blogContent->footer_caption}}</p>
                            @endif
                        </div>
                        {{$beforeText}}

                    </div>
                </div>
                <div class="col-sm-10 col-md-10 m-auto">
                    <h1 class="text-center">###</h1>
                    <a href="#"><h4>View product catalog.</h4><h4>For additional details visit us.</h4></a>
                    <p>Contact:</p>
                    <p>Jennifer Dombkowsk</p>
                    <p>T. 212.807.6300</p>
                    <p>E. jdombkowski@innovationsusa.com</p>
                    <p>About Innovations:</p>
                    <p>For over 45 years, Innovations has been committed to forward-thinking design and creating
                        wallcoverings that transform
                        interiors. With everything from natural wovens to luxe textiles and elevated vinyls,
                        Innovations’ versatile assortment explores
                        materiality and technique without compromising durability. Experience wallcovering with
                        Innovations.</p>
                </div>
            </div>
        </div>
    @else
        <h3 class="text-center">There is no press releases you are finding!</h3>
    @endif

@endsection
