<?php

//print_r($collectionArr); die();

// https://laravel.com/docs/5.6/views

// master.blade.php
// pass vairable to master:
//@extends('master', ['title' => $title])

$ver = Config::get('constants.value.VER');
//print_r($pageId); die();

if ($pageId=='color' || $pageId=='material' || $pageId=='pattern' || $pageId=='texture'){
  $class1 = '';
  $class2 = '';
}else{
  $class1 = 'mx-auto';
  $class2 = 'invisible';
}
?>
@extends('master') 




@section('main_content')
<div class="product container">
  
  <div class='row'>
    @if ($pageId=='color' || $pageId=='material' || $pageId=='pattern' || $pageId=='texture')
    <div class="col-md-1 {{ $class2 }}">
      <ul>
        
      @foreach($filterObj as $filter)
        <li><input type="checkbox" class="custom-control-input" id="checkbox-{{ $loop->index }}">
        <label class="custom-control-label" for="checkbox-{{ $loop->index }}">{{ $filter }}</label></li>
      @endforeach
      </ul>
    </div>
    @endif

    <div class="col-md-11 {{ $class1 }}">
      <ul class='row'>
  		@foreach($productArr as $item)
          <li class='col-6 col-md-4 {{ $class1 }}'>
    			<div class="inner">
    				<a class='fabricName' href="{{ url('/') }}/item/{{ $item['urlName'] }}">
    						<img src="//innovationsusa.com/+aimg2017_dev/fabrics_184x184/Innovations_{{ $item['jpgName'] }}.jpg?v={{ $ver }}" alt="{{ $item['urlName'] }}">
    				<p class="skuWallTitle">{{ $item['displayName'] }}</p>
          </a>
    			</div>
    		</li>
  		@endforeach
    	</ul>
    </div>

  </div><!-- row -->

</div>
@stop
