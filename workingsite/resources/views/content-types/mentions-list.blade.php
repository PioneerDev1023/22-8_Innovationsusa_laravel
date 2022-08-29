@extends('master')

@section('meta_tags')
{!! SEOMeta::generate() !!}
{!! OpenGraph::generate() !!}
{!! Twitter::generate() !!}
{!! JsonLd::generate() !!}
@endsection

@section('main_content')
        <div id="catalog-list" class="projects">
        <h1 class='text-center'>MENTIONS</h1>
        <div id="padbottom-content" class="col-md-10 mx-auto container">
        <ul class="row">
          @foreach($seenins as $seenin)
          <li class="col-6 col-md-4 ">
            @if ( $seenin->include_mentions == 0 )
              <div class='alert alert-warning'>UNPUBLISHED</div>
              @endif
            <div class="inner">
              <a href='{{$seenin->url}}' target="_blank" class="fabricName">
                <img src="/storage/{{$seenin->image}}" alt="{{ $seenin->title }}" width="100%">
                <p class="skuWallTitle">{{ $seenin->title }}</p>
              </a>
            </div> <!-- inner -->
          </li>
          @endforeach

          </ul>
      </div>
    </div>
@endsection
