@extends('master')

@section('meta_tags')
    {!! SEOMeta::generate() !!}
    {!! OpenGraph::generate() !!}
    {!! Twitter::generate() !!}
    {!! JsonLd::generate() !!}
@endsection

@section('main_content')
    @if($collection != NULL)
        @if(isset($status) && $status == "0")
            <div class="alert alert-warning">UNPUBLISHED</div>
        @endif
        <div class="container-fluid" style="margin: 70px 0 30px">
            <h1 class="homehide">{{$collection->title}}</h1>
            <div class="row">
                <div class="col-sm-2"></div>
                <div class="col-sm-8">
                    <div class="" data-rellax-speed="1">
                        @if(!isset($collection->head2_img))
                            <a href="{{$collection->shop_link}}">
                                @endif
                                <img src="/storage/{{$collection->head_img}}" width="100%"
                                     alt="{{$collection->head_alt}}">
                                @if(!isset($collection->head2_img))
                            </a>
                        @endif
                    </div>
                </div>
                <div class="col-sm-2"></div>
            </div>
            @if ($collection->head2_img)
                <div class="row">
                    <div class="col-sm-2"></div>
                    <div class="col-sm-8">
                        <div class="" data-rellax-speed="1">
                            <a href="{{$collection->shop_link}}">
                                <img src="/storage/{{$collection->head2_img}}" width="100%"
                                     alt="{{$collection->head_alt}}">
                            </a>
                        </div>
                    </div>
                    <div class="col-sm-2"></div>
                </div>
            @endif
            @php
                // Text area
            @endphp
            <div class="row">
                <div class="col-sm-2"></div>
                <div class="col-sm-8 coll-desc">
                    {!! $collection->description !!}
                </div>
                <div class="col-sm-2"></div>
            </div>
            @if ($collection->foot2_img)
                <div class="row">
                    <div class="col-sm-2"></div>
                    <div class="col-sm-8 mobile-vertfix">
                        <div class="" data-rellax-speed="1">
                            <a href="{{$collection->catalog_link}}">
                                <img src="/storage/{{$collection->foot2_img}}" width="100%"
                                     alt="{{$collection->foot_alt}}">
                            </a>
                        </div>
                    </div>
                    <div class="col-sm-2"></div>
                </div>
            @endif
            <div class="row">
                <div class="col-sm-2"></div>
                <div class="col-sm-8 mobile-vertfix">
                    <div data-rellax-speed="1">
                        <a href="{{$collection->appt_link}}">
                            <img src="/storage/{{$collection->foot_img}}" width="100%" alt="{{$collection->foot_alt}}">
                        </a>
                    </div>
                </div>
                <div class="col-sm-2"></div>
            </div>
        </div>
    @else
        <h3 class="text-center">There is no collection you are finding!</h3>
    @endif

@endsection
