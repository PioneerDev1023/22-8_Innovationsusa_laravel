@php
//$session_id = \Session::getId();
$message = 'error';

$baseUrl = Config::get('constants.value.baseUrl');
$imgUrl = Config::get('constants.value.imgUrl');

@endphp
@extends('master')

@section('title')
 - New Collection
@endsection

@section('meta_tags')
{!! SEOMeta::generate() !!}
{!! OpenGraph::generate() !!}
{!! Twitter::generate() !!}
{!! JsonLd::generate() !!}
@endsection
@section('main_content')

{{-- {{$cartObj}} --}}
<!-- <br>
<p>for loop</p> -->

<div class="container-fluid" style="margin: 70px 0 30px">
  <h1 class="homehide">New Wallcovering Design Collection from Innovations USA</h1>
    <div class="row">
      <div class="col-sm-2"></div>
      <div class="col-sm-8">
        <div class="" data-rellax-speed="1">
          <a href="/product/collection/fall-2021">
            <img src="{{$imgUrl}}/storage/new-collection/Innovationsusa-fall-2021-1.jpg?v=2" width="100%">
          </a>
        </div>
      </div>
      <div class="col-sm-2"></div>
    </div>
    <div class="row">
      <div class="col-sm-2"></div>
      <div class="col-sm-8 mobile-vertfix">
        <div class="" data-rellax-speed="1">
          <a href="/product/collection/fall-2021">
            <img src="{{$imgUrl}}/storage/new-collection/Innovationsusa-fall-2021-2.jpg?v=2" width="100%">
          </a>
        </div>
      </div>
      <div class="col-sm-2"></div>
    </div>
    <div class="row">
      <div class="col-sm-2"></div>
      <div class="col-sm-8 mobile-vertfix">
        <div  data-rellax-speed="1">
            <img src="{{$imgUrl}}/storage/new-collection/Innovationsusa-fall-2021-3.jpg?v=2" width="100%">
        </div>
      </div>
      <div class="col-sm-2"></div>
    </div>
    <div class="row">
      <div class="col-sm-2"></div>
      <div class="col-sm-8 mobile-vertfix">
        <div class="" data-rellax-speed="1">
          <a href="/catalogs/elan-fall-2021" target="_blank">
            <img src="{{$imgUrl}}/storage/new-collection/Innovationsusa-fall-2021-4.jpg?v=2" width="100%">
          </a>
        </div>
      </div>
      <div class="col-sm-2"></div>
    </div>
    <div class="row">
      <div class="col-sm-2"></div>
      <div class="col-sm-8 mobile-vertfix">
        <div class="" data-rellax-speed="1">
            <img src="{{$imgUrl}}/storage/new-collection/Innovationsusa-fall-2021-5.jpg?v=2" width="100%">
        </div>
      </div>
      <div class="col-sm-2"></div>
    </div>
    <div class="row">
      <div class="col-sm-2"></div>
      <div class="col-sm-8 mobile-vertfix">
        <div class="" data-rellax-speed="1">
            <img src="{{$imgUrl}}/storage/new-collection/Innovationsusa-fall-2021-6.jpg?v=2" width="100%">
        </div>
      </div>
      <div class="col-sm-2"></div>
    </div>
    <div class="row">
      <div class="col-sm-2"></div>
      <div class="col-sm-8 mobile-vertfix">
        <div class="" data-rellax-speed="1">
          <a href="/presentation-request">
            <img src="{{$imgUrl}}/storage/new-collection/Innovationsusa-fall-2021-7.jpg?v=2" width="100%">
          </a>
        </div>
      </div>
      <div class="col-sm-2"></div>
    </div>

</div>
@stop
