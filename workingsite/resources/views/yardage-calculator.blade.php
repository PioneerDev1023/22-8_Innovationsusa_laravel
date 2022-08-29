@extends('master')

@section('meta_tags')
{!! SEOMeta::generate() !!}
{!! OpenGraph::generate() !!}
{!! Twitter::generate() !!}
{!! JsonLd::generate() !!}
@endsection

@stack('styles')

@section('main_content')
<div class="container-fluid" style="height:100vh; padding-top: 50px;background-color:#e7e5dc;">
  <div class="container calc-container">
    <div class="row">
      <div class="col-md-8 mx-auto">
        <div class="row">
            <h1 class="mx-auto">YARDAGE CALCULATOR</h1>
        </div>
        <div class="row">
            @if($type == "item")
            <h3 class="skuTitle mx-auto"><a href="{{url('/item/'.$item)}}">{{$item}}</a></h3>
            @elseif($type == "sku")
            <h3 class="skuTitle mx-auto"><a href="{{url('/item/'.$item.'/'.strtolower($sku))}}">{{$item}}&nbsp;{{$sku}}</a></h3>
            @endif
        </div>
        <div class="row text-center">
            <p class="mx-auto">Trying to get an idea of how much material you will need for your job? Enter in the dimensions of the wall you are trying to cover, or enter in the total square footage needed, and let the calculator do the rest.</p>
            <p class="mx-auto">Tip: Measure your wall from its widest and tallest points and disregard door and window openings.</P>
        </div>
        <p class="diagram"><img src="/images/ui/w-h.png" alt="Wallcovering yardage calculator" ></p>
        <hr />
      </div>
    </div>
  </div>
</div>
@if($type == "item")
<div id="materialcalc-container" data-type="item" data-item="{{$item}}" data-width="{{$width}}"></div>
@elseif($type == "sku")
<div id="materialcalc-container" data-type="sku" data-item="{{$item}}" data-sku="{{$sku}}" data-width="{{$width}}"></div>
@elseif($type == "yardage")
<div id="materialcalc-container"></div>
@endif
@endsection
