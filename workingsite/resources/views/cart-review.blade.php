@php
$ver = Config::get('constants.value.VER');
$baseUrl = Config::get('constants.value.baseUrl');
$imgUrl = Config::get('constants.value.imgUrl');
//$session_id = \Session::getId();
$message = 'error';

@endphp
@extends('master') 

@section('title')
 - Sample Cart: Checkout
@endsection
@section('main_content')

{{-- {{$cartObj}} --}}
<!-- <br>
<p>for loop</p> -->

<div id="cart-review" class="container-fluid justify-content-center">
  <div class="row r1 justify-content-center">
    <div class="cart-info col-md-4">
      <div class="address">
      
        <h2>Shipping Address</h2>
          {{$formArr['form']['fullname']}} <br/>
          {{$formArr['form']['address']}} <br/>
          {{$formArr['form']['phone']}}
      </div>

      <div class="ship-option">
        <h2>Shipping Option</h2>
        {{$formArr['form']['ship_option']}}
      </div>
      <div class="payment">
        <h2>Payment Info</h2>
        <p>By clicking below you will be brought to a ...</p>
        
        @if(!is_null($TrancId) && $TrancId !== '')
          <form action="{{url('pay/'.$TrancId)}}" method="post" target="_blank">@csrf<button type="submit" class="btn btn-primary">PAY</button></form>
        @else
          <a href="/cart/shopping/confirmation" class="btn btn-primary">PAY</a>
        @endif        
        
      </div>
    </div>

    @php
      $count = count($cartObj);
      $countStr = $count . ' Item';
      $collapsed = '';
      if($count>1){
        $countStr = $countStr . 's';
        $collapsed = 'collapsed';
      }


      $price1 = 'Free';
      $price2 = 'Free';
      $price3 = 'Free';
      $price4 = 'Free';
      if($carttype=='shopping'){
        $price1 = 0; // sub
        $price2 = 0; // ship
        $price3 = 0; // tax
        $price4 = 0; // total
      }
    @endphp

    <div class="cart-items col-sm-4">
      <!--a href="/cart/{{ $carttype }}">Back to Cart</a-->
      <h2>Order Summary</h2>
       
<div class="cart-subhead">
  <span class="count-str">{{$countStr}}</span>

  @if($count>1)
  <a class="dropdown-toggle {{$collapsed}}" data-toggle="collapse" href="#collapse-filter-{{$carttype}}" role="button" aria-expanded="false" aria-controls="collapseFilter">Expand</a>
 @endif

</div>

    @if($count>1)
      @foreach($cartObj as $key => $item)
        @if($key==0)
          <ul class="container" >
            <li class="row li-{{$key}}">
              <a class="col-md-1">
                <img class="img-fluid" src="{{$imgUrl}}/storage/sku/350x350/{{ $item['id'] }}.jpg" alt=""> 
              </a>
              <div class="wrapper col-md-11">
                <!--div class="info">
                    <p data-itemname="{{ $item['name'] }}">{{ $item['name'] }}</p>
                    <p> 
                      <span data-itemnum="{{ $item['id'] }}">{{ $item['id'] }}</span> 
                      <span data-itemcolor="{{ $item['color'] }}">{{ ucwords(strtolower( $item['color'] )) }}</span>
                    </p>
                </div-->
              </div>
              
            </li>
          </ul>
        @endif

        @php
        if($carttype=='shopping')
          $price1 += (float)$item['price'] * (float)$item['quantity'];
        @endphp
      @endforeach

        {{-- calculate sub, ship, tax, total --}}
        @php
          if($carttype=='shopping'){
            $price4 = $price1 + $price2 + $price3;
            $price4 = '$' . number_format($price4, 2);

            $price1 = '$' . number_format($price1, 2);
          }
        @endphp

      <ul class="container collapse {{$collapsed}}" id="collapse-filter-{{$carttype}}">
        @foreach($cartObj as $key => $item)
          @if($key > 0)
            <li class="row li-{{$key}}">
              <a class="col-md-1">
                <img class="img-fluid" src="{{$imgUrl}}/storage/sku/350x350/{{ $item['id'] }}.jpg" alt=""> 
              </a>
              <div class="wrapper col-md-11">
                <!--div class="info">
                    <p data-itemname="{{ $item['name'] }}">{{ $item['name'] }}</p>
                    <p> 
                      <span data-itemnum="{{ $item['id'] }}">{{ $item['id'] }}</span> 
                      <span data-itemcolor="{{ $item['color'] }}">{{ ucwords(strtolower( $item['color'] )) }}</span>
                    </p>
                </div-->
              </div>
            </li>
          @endif
        @endforeach
      </ul>

    @else
      <ul class="container">
        @foreach($cartObj as $item)
        <li class="row">
          <a class="col-md-1">
            <img class="img-fluid" src="{{$imgUrl}}/storage/sku/350x350/{{ $item['id'] }}.jpg" alt=""> 
          </a>

          <div class="wrapper col-md-11">
            <div class="info">
                <p data-itemname="{{ $item['name'] }}">{{ $item['name'] }}</p>
                <p> 
                  <span data-itemnum="{{ $item['id'] }}">{{ $item['id'] }}</span> 
                  <span data-itemcolor="{{ $item['color'] }}">{{ ucwords(strtolower( $item['color'] )) }}</span>
                </p>
            </div>
          </div>
          
        </li>
        @php
        if($carttype=='shopping')
          $price1 += (float)$item['price'] * (float)$item['quantity'];
        @endphp
        @endforeach
        {{-- calculate sub, ship, tax, total --}}
        @php
          if($carttype=='shopping'){
            $price4 = $price1 + $price2 + $price3;
            $price4 = '$' . number_format($price4, 2);

            $price1 = '$' . number_format($price1, 2);
          }
        @endphp
      </ul>
    @endif


      <div class="checkout-stat">
        <p class="sub-total">
          <span class="left">Item Subtotal:</span>
          <span class="right">{{$price1}}</span>
        </p>
        <p class="shipping">
          <span class="left">Shipping:</span>
          <span class="right">{{$price2}}</span>
        </p>
        <p class="tax">
          <span class="left">Estimated Tax:</span>
          <span class="right">{{$price3}}</span>
        </p>
      </div>
      <div class="total">
        <p>
          <span class="left">Total:</span>
          <span class="right">{{$price4}}</span>
        </p>
      </div>

    </div>
  </div>
</div>
@stop