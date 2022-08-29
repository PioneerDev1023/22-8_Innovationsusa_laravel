@php

//print_r($collectionArr); die();

// https://laravel.com/docs/5.6/views

// master.blade.php
// pass vairable to master:
//@extends('master', ['title' => $title])

$ver = Config::get('constants.value.VER');
$baseUrl = Config::get('constants.value.baseUrl');
$imgUrl = Config::get('constants.value.imgUrl');
//print_r($pageId); die();

 /*if ($pageId=='color' || $pageId=='material' || $pageId=='pattern' || $pageId=='texture'){
  $class1 = '';
  $class2 = '';

  if(isset($filters)){
    $filtersArr = explode("+", $filters);
  }else{
    $filtersArr = '';
  }

}else{ */
  $class1 = 'mx-auto';
  $class2 = 'invisible';
  $filtersArr = '';
//}

// print_r($filtersArr); die();
@endphp

@extends('master')

@section('main_content')
<div id="productcontainer" class="product container-fluid">
  <div class='row'>
    @if ($pageId=='color' || $pageId=='material' || $pageId=='pattern' || $pageId=='texture')
    <div id="product-filter" class="col-md-1 {{ $class2 }}">
      <div id="product-filter-btn" data-filter="{{$pageId}}"></div>
      <ul>
        @foreach($filterObj as $filter)
          @php
          $checkIO = '';
          if($filtersArr != ''){
            if( in_array(strtolower($filter), $filtersArr) ){
              $checkIO = 'checked';
            }
          }
          @endphp
          <li><input type="checkbox" class="custom-control-input" id="checkbox-{{ $loop->index }}" {{$checkIO}}>
          <label class="custom-control-label" for="checkbox-{{ $loop->index }}">{{ $filter }}</label></li>

        @endforeach
      </ul>
    </div>

    @endif

    <div id="product-thumbs" class="col-md-11 {{ $class1 }}">
      @if(isset($productArr) && count($productArr) > 0)
      <ul class='row'>

      @foreach($productArr as $item)
      <li class='col-6 col-md-4 {{ $class1 }}'>
        <div class="inner">

          {{-- @php
            /*if($filtersArr == ''){
              $imgdir = 'fabrics_184x184';
              $img = 'Innovations_' . $item['jpgName'] . '.jpg';
              $displayname =  $item['displayName'];
              $url = $item['urlName'];
              $plpIO = 'main';
            }
            else{*/
              //$imgdir = 'colordetail_184x184';
              //$img = $item['jpgName'] . '.jpg';
              $displayname =  $item['displayName'];
              $url = $item['itemName'] . '/' . $item['urlName'];
              $thumbType = 'item';

              $mainImgUrl = $imgUrl . '/storage/sku/900x900/' . $item['jpgName'] . '.jpg?v=' . $ver;
            //}
          @endphp --}}


          @php
            //print_r($key); die();
              if($item['type']=='product'){
                $imgdir = 'fabrics_184x184';
                //$img = 'Innovations_' . $item['jpgName1'] . '.jpg';
                $displayname =  $item['displayName1'];
                $url = $item['urlName1'];
                $thumbType = 'product';
                if(array_key_exists('mainImage', $item) && $item['mainImage'] != "" && $item['mainImage'] != 'NULL')
                  $mainImgUrl = env('APP_URL') . '/storage/'.$item['mainImage'];
                else
                  $mainImgUrl = $item['jpgName1'];
              }
              else{
                $imgdir = 'colordetail_184x184';
                //$img = $item['jpgName'] . '.jpg';
                $displayname =  $item['displayName'];
                $url = env('APP_URL') . '/item/' . $item['itemName'] . '/' . $item['urlName'];
                $thumbType = 'item';
                $mainImgUrl = $item['jpgName'];
              }
            @endphp


          <a class='fabricName' href="{{ $url }}">

              <img src="{{ $mainImgUrl }}">
              @php
              $classS = '';
              if(strlen($displayname) > 18){
                $classS = 'shrink';
              }
              @endphp
              <p class="skuWallTitle {{$classS}}">{{ $displayname }}</p>
            </a>
            <span class="plp-preview-button" data-title="{{ $item['dbName'] }}" data-producttype="{{$thumbType}}"></span>


        </div><!-- inner -->
      </li>
      @endforeach

      </ul>
      @else
      <h3 class="text-bold text-center m-auto">No results Found.</h3>
      @endif
      <div id="dynamic-load"></div>
    </div><!-- productcontainer -->
  </div><!-- row -->
</div><!-- #productcontainer product container -->

<div class="modal fade" id="modal-product-container" tabindex="-1" role="dialog" aria-labelledby="modal-product" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-body">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          {!! file_get_contents('images/icons/x.svg') !!}
        </button>
        <img id="plp-modal-img1" src="" alt="wallcovering product" width="100%">
        <div class="container-fluid">

          <ul id="plp-item-ul" class="row hide">
            <li class="col-md-6">
              <h3 class="plp-modal-title">title</h3>
              <p id="plp-modal-num-color">title 2</p>
              @auth
                <p id="plp-modal-price">price</p>
              @endauth
            </li>
            <li class="col-md-6">
              <div id='download-image'><a href="#">Download image</a></div>
            </li>
            <li id="plp-sample-btn" class="col-md-6"></li>
            <li class="col-md-6"><button class="learnmore btn btn-primary">LEARN MORE</button></li>
          </ul>

          <ul id="plp-product-ul" class="row hide">
            <li class="col-md-6">
              <h3 class="plp-modal-title">title</h3>
              <!--<p class="plp-modal-num-color">title 2</p>
              p id="plp-modal-price">price</p-->
            </li>
            <li class="col-md-6">
              <div id='download-image-2'><a href="#">Download image</a></div>
            </li>
            <li class="col-md-6"></li>
            <li class="col-md-6"><button class="learnmore btn btn-primary">LEARN MORE</button></li>
          </ul>

        </div>
      </div>
    </div>
  </div>
</div>
@stop
