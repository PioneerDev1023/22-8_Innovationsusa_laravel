@php

// https://laravel.com/docs/5.6/views

// master.blade.php
// pass vairable to master:
//@extends('master', ['title' => $title])

$ver = Config::get('constants.value.VER');
$baseUrl = Config::get('constants.value.baseUrl');
$imgUrl = Config::get('constants.value.imgUrl');

// print_r($pageId); die();
// print_r($filterObj); die();
// print_r($filters); die();

$filtersArr = '';
$collapsed = '';
$collapse = '';
$class1 = 'mx-auto';
$class2 = 'invisible';

if ($pageId=='color' || $pageId=='material' || $pageId=='pattern' ||
    $pageId=='texture' || $pageId=='collection' || $pageId == 'all-wallcovering'){

  $class1 = '';
  $class2 = '';

  $collapsed = '';
  $collapse = 'show';

  if($pageId == 'all-wallcovering')
    $collapse = '';

  if( isset($filters) ){

    $filtersArr = explode("+", $filters);

    foreach ($filtersArr as $key => $value) {
      // check if filter contains numbers: collection fall-2020
      // because filter items such as off-white needs dash
      if( 1 === preg_match('~[0-9]~', $value) ||
          strtolower($value)=='natural-woven' ||
          strtolower($value)=='abstract-specialty'||
          strtolower($value)=='animal-print'||
          strtolower($value)=='botanical-floral'||
          strtolower($value)=='concrete-plaster'||
          strtolower($value)=='damask-lattice-medallion'||
          strtolower($value)=='geometric-linear' ){
            $newItem = str_replace("-", " ", $filtersArr[$key]);
            unset($filtersArr[$key]);
            array_push($filtersArr, $newItem);
      }



      if( strtolower($value)=='cork-faux-cork' ||
          strtolower($value)=='foiled-metallic' ||
          strtolower($value)=='grasscloth-faux-grasscloth' ||
          strtolower($value)=='linen-faux-linen' ||
          strtolower($value)=='silk-faux-silk' ||
          strtolower($value)=='wood-veneer-faux-wood' ){
            $newItem = str_replace("-", "/", $filtersArr[$key]);
            $newItem = str_replace("faux/", "faux ", $newItem); // remove slash after faux
            unset($filtersArr[$key]);
            array_push($filtersArr, $newItem);
      }

      if($pageId=='pattern'){
        if( $value=='large-scale-mural' ){
          $newItem = 'large-scale/mural';
          unset($filtersArr[$key]);
          array_push($filtersArr, $newItem);
        }else if($value=='animal-print'){
          $newItem = 'animal print';
          unset($filtersArr[$key]);
          array_push($filtersArr, $newItem);
        }else{
          $newItem = str_replace("-", "/", $value);
          unset($filtersArr[$key]);
          array_push($filtersArr, $newItem);
        }
      }

      if($value == "wood-faux-wood") {
        unset($filtersArr[$key]);
        array_push($filtersArr, 'wood/faux wood');
      }
    }

    if( $mobile==1 ){
      $collapsed = 'collapsed';
      $collapse = 'collapse';
    }

  }

}

$thumbClass='col-md-9';

// print_r($pageId); die();
// print_r($filtersArr); die();
@endphp


@extends('master')

@section('meta_tags')
{!! SEOMeta::generate() !!}
{!! OpenGraph::generate() !!}
{!! Twitter::generate() !!}
{!! JsonLd::generate() !!}
@endsection

@section('main_content')
  <nav aria-label="breadcrumb">
    <ol class="breadcrumb">
      <li class="breadcrumb-item"><a>PRODUCTS</a></li>

      @if($pageId=='faux leather' || $pageId=='sheers/drapery')
      @else
        <li class="breadcrumb-item wallcovering"><a href="#">WALLCOVERING</a></li>
      @endif

      @php
      $f1 = strtoupper($productTitle);
      $f1 = str_replace("-", " ", $f1);
        //if($f1=='COLOR') $f1 = 'COLORS';
      @endphp
      <li class="breadcrumb-item active" aria-current="page">{{$f1}}</li>
    </ol>
  </nav>
<div id="productcontainer" class="product container-fluid">

  <div class='row'>
    @if ($pageId=='color' || $pageId=='material' || $pageId=='pattern' || $pageId=='texture' || $pageId=='collection' || $pageId == 'all-wallcovering')
      @php
        //$thumbClass='col-md-10';
      @endphp

        @if($pageId == 'all-wallcovering')
          <div id="product-filter" class="col-md-3 {{ $class2 }}">

            @foreach($filterObj as $key => $filters)
              @php
                  //print_r($filters); die();
                  //print_r(array_keys($filterObj)[0]); die();
                $filterTitle = array_keys($filterObj)[$loop->index];
              @endphp
              <div id="{{ $filterTitle }}" class="filter-container">
                @php
                $f1 = strtoupper($filterTitle);
                //print_r($f1); die();
                if($f1=='TEXTURE') $f1 = 'TEXTURE & FINISH';
                //else if($f1=='COLOR') $f1 = 'COLORS';
                @endphp

                <a class="dropdown-toggle {{$collapsed}}" data-toggle="collapse" href="#collapse-filter-{{$filterTitle}}" role="button" aria-expanded="false" aria-controls="collapseFilter">
                  {{ $f1 }}
                </a>

                <div class="collapse {{$collapse}}" id="collapse-filter-{{$filterTitle}}">
                  <ul>
                  @foreach($filters as $key => $filter)

                    @php
                    //print_r($filters); die();
                    //echo $loop->iteration;
                    $checkIO = '';
                    if($filtersArr != ''){

                      //if( in_array(strtolower($filter), $filtersArr = str_replace("-", " ", $filtersArr)) ){
                      if( in_array(strtolower($filter), $filtersArr ) ){
                        $checkIO = 'checked';
                      }
                    }
                    @endphp

                    <li><input type="checkbox" class="custom-control-input" id="checkbox-{{ $filterTitle }}-{{ $loop->index }}" {{$checkIO}}>
                    <label class="custom-control-label" for="checkbox-{{ $filterTitle }}-{{ $loop->index }}">{{ $filter }}</label></li>
                  @endforeach

                  </ul>
                </div>
              </div>
            @endforeach

            <div id="product-filter-btn" data-filter="{{$pageId}}"></div>
          </div>
        @else
          <div id="product-filter" class="col-md-3 {{ $class2 }}">
            @php
            $f1 = strtoupper($pageId);
              if($f1=='TEXTURE')
                $f1 = '<span>TEXTURE & FINISH</span>';
            //elseif($f1=='COLOR') $f1 = 'COLORS';
            @endphp
            <a class="dropdown-toggle {{$collapsed}}" data-toggle="collapse" href="#collapseFilter" role="button" aria-expanded="false" aria-controls="collapseFilter">
              <h1><span class="t1">SEARCH BY</span> {!! $f1 !!}</h1>
            </a>

            <div class="collapse {{$collapse}}" id="collapseFilter">
              <ul>
              @foreach($filterObj as $filter)

                @php
                //print_r($filterObj); die();
                // print_r($filter); //die();
                // print_r($filtersArr); die();
                $checkIO = '';
                if($filtersArr != ''){
                  //if( in_array(strtolower($filter), $filtersArr = str_replace("-", " ", $filtersArr)) ){
                  if( in_array(strtolower($filter), $filtersArr ) ){
                    $checkIO = 'checked';
                  }
                }
                @endphp

                <li><input type="checkbox" class="custom-control-input" id="checkbox-{{ $loop->index }}" {{$checkIO}}>
                <label class="custom-control-label" for="checkbox-{{ $loop->index }}">{{ $filter }}</label></li>

              @endforeach
              </ul>
            </div>

            <div id="product-filter-btn" data-filter="{{$pageId}}"></div>
          </div>
        @endif

    @elseif( $pageId=='faux leather' || $pageId=='sheers/drapery')
      @php
        $thumbClass='col-md-9';
      @endphp
      <div id="product-description" class="col-md-3 ">
        @php
        $t1 = strtoupper($productTitle);
        @endphp
        <h1>{{ $t1 }}</h1>
        <p>{!! $productDescription !!}</p>
      </div>
    @endif

    <div id="product-thumbs" class="{{ $thumbClass }} {{ $class1 }}">
    @if(count($mainArr) > 0)
      <ul class='row'>        
    		@foreach($mainArr as  $item)
        <li class='col-6 col-md-4 {{ $class1 }}'>
    			<div class="inner">

            @php
              //print_r($item['type']); die();
              if($item['type']=='product' || ($filtersArr == '' || $pageId=='collection') ){
                $imgdir = 'fabrics_184x184';
                //$img = 'Innovations_' . $item['jpgName1'] . '.jpg';
                $displayname =  $item['displayName1'];
                $url = $item['urlName1'];
                $urlName = $item['urlName1'];
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
                $url = $item['itemName'] . '/' . $item['urlName'];
                $urlName = $item['dbName'];
                $thumbType = 'item';
                $mainImgUrl = $item['jpgName'];
                // $mainImgUrl = $imgUrl . '/storage/sku/900x900/' . $item['skuImage'] . '?v=' . $ver;
              }
            @endphp

    				<a class='fabricName' href="{{ url('/') }}/item/{{ $url }}">

    					<!--img src="//innovationsusa.com/+aimg2017_dev/{{-- $imgdir --}}/{{--$img--}}?v={{-- $ver --}}" alt="{{-- $url --}}"-->
              <img src="{{ $mainImgUrl }}" alt= "{{ $displayname }} Wallcovering">
              @php
              $classS = '';
              if(strlen($displayname) > 18){
                $classS = 'shrink';
              }
              @endphp
    				  <p class="skuWallTitle {{$classS}}">{{ $displayname }}</p>
            </a>
            <span class="plp-preview-button" data-title="{{ $urlName }}" data-producttype="{{$thumbType}}"></span>

    			</div><!-- inner -->
    		</li>
    		@endforeach        
    	</ul>
      <div id="dynamic-load"></div>
      @else
      <h3 class="text-center">No results found.</h3>

    @endif      
    </div><!-- productcontainer -->
  </div><!-- row -->
</div><!-- #productcontainer product container -->

<div class="modal fade" id="modal-product-container" tabindex="-1" role="dialog" aria-labelledby="modal-product" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-body">
        <button type="button"
         class="close" data-dismiss="modal" aria-label="Close">
          {!! file_get_contents('images/icons/x.svg') !!}
        </button>
        <img id="plp-modal-img1" src="" alt="product image" width="100%">
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
              <div id='download-image' data-downimage=""><a href="#">Download image</a></div>
            </li>
            <li id="plp-sample-btn" class="col-md-6"></li>
            <li class="learn-more col-md-6"><button class="learnmore btn btn-primary">LEARN MORE</button></li>
          </ul>

          <ul id="plp-product-ul" class="row hide">
            <li class="col-8">
              <h3 class="plp-modal-title">title</h3>
              <!--<p class="plp-modal-num-color">title 2</p>
              p id="plp-modal-price">price</p-->
            </li>
            <li class="col-4">
              <div id='download-image-2' data-downimage=""><a href="#">Download image</a></div>
            </li>
            <li class="col-6"><!--button class="btn btn-primary">ORDER A SAMPLE</button--></li>
            <li class="learn-more col-6"><button class="learnmore btn btn-primary">LEARN MORE</button></li>
          </ul>

        </div>
      </div>
      <!-- <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
        <button type="button" class="btn btn-primary">Save changes</button>
      </div> -->
    </div>
  </div>
</div>
@stop
