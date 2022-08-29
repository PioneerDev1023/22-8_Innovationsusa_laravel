@extends('master')

@section('meta_tags')
    {!! SEOMeta::generate() !!}
    {!! OpenGraph::generate() !!}
    {!! Twitter::generate() !!}
    {!! JsonLd::generate() !!}
@endsection

@section('main_content')

    <div id="catalog-list" class="projects">
        <h1 class='text-center'>WALLCOVERING COLLECTIONS</h1>
        <div id="padbottom-content" class="col-md-10 mx-auto container">
            <ul class="row">
                @if(isset($list) && $list != NULL)
                    @foreach ($list as $collection)
                        <li class="col-6 col-md-4 ">
                          @if ( $collection->status == 0 )
                            <div class='alert alert-warning'>UNPUBLISHED</div>
                            @endif
                            <div class="inner">
                                <a href='/collections/{{$collection->slug}}' class="fabricName">
                                    <img src="/storage/{{$collection->thumb_img}}" alt="{{$collection->title}}"
                                         width="100%">
                                    <p class="skuWallTitle">{{$collection->title}}</p>
                                </a>
                            </div> <!-- inner -->
                        </li>
                    @endforeach
                @else
                    <p class="text-center">No data avalable.</p>
                @endif

            </ul>
        </div>
    </div>


@endsection
