@extends('master')

@section('title')
 - Sample Cart
@endsection

@section('main_content')
@auth
@if(!$is_client)
@push('head')
<script type="text/javascript">
    function callbackThen(response){
        // read HTTP status
        console.log(response.status);

        // read Promise object
        response.json().then(function(data){
            console.log(data);
        });
    }
    function callbackCatch(error){
        console.error('Error:', error)
    }
</script>
{!! htmlScriptTagJsApi([
    'action' => 'cartpage',
    'callback_then' => 'callbackThen',
    'callback_catch' => 'callbackCatch'
]) !!}
@endpush
@endif
@endauth

{{-- {{ $cartObj }} --}}
<!-- <br>
<p>for loop</p> -->
@php
$ver = Config::get('constants.value.VER');
$baseUrl = Config::get('constants.value.baseUrl');
$imgUrl = Config::get('constants.value.imgUrl');

  $class1 = '';
if(count($cartObj) <= 2){
  $class1 = 'empty';
}
@endphp
<div id="cart" class="container-fluid {{$class1}}" data-carttype="{{ $carttype }}" style="padding-bottom: 100px; overflow-y:scroll">
  <div class="row">
    <div class="col-sm-4 mx-auto cart-top-nav">
      @auth
        <h1 class="">CART</h1>
          @php

          if($is_client) {

           if($carttype=='sample'){
            echo '<p><a class="active">SAMPLES<span class="underline"></span></a> <a href="/cart/shopping">SHOPPING</a></p>';
            $price = 'Free';
           }
           else{
            echo '<p><a href="/cart/sample">SAMPLES</a> <a class="active">SHOPPING<span class="underline"></span></a></p>';
            $price = '';
           }
         } else {
           echo '<p><a class="active">SAMPLES<span class="underline"></span></a></p>';
         }
          @endphp
      @else
        <h1 class="">{{ ucwords($carttype) }} Cart</h1>
          @php
          $price = '';
          @endphp
      @endauth
    </div>
  </div>
  @if($carttype=='sample')
  <div class="row">
    @if(count($cartObj) > 0)
    <div class="cart-items col-sm-8">
      <ul id="cartUL">
        @foreach($cartObj as $item)
        <li class="row">
          <a class="col-5 col-md-3" href="/item/{{ strtolower($item['urlname']) }}/{{ strtolower($item['id']) }}/"><img class="img-fluid" src="{{$imgUrl}}/storage/sku/350x350/{{ $item['id'] }}.jpg" alt=""></a>

          <div class="col-7 col-md-9 cart-container">

            <div class="wrapper">
              <div class="info">
                  <p class="titleCase" data-itemname="{{ $item['name'] }}">{{ ucwords(strtolower( $item['name'] )) }}</p>
                  <p>
                    <span class="">{{ ucwords($carttype) }}</span> : <span>{{ $item['id'] }}</span>
                    <span class="titleCase" data-itemcolor="{{ $item['color'] }}">{{ ucwords(strtolower( $item['color'] )) }}</span>
                  </p>
              </div>
              <div class="input">

                  <span class="lock" >
                    <span class="txt">Qty:</span>
                    <span class="cart-qty" data-qty="{{ $item['quantity'] }}" data-itemnum="{{ $item['id'] }}"/>
                  </span>
                  </span>

                  @php
                    if($carttype=='sample'){
                      $price = 'Free';
                      $price_1 = 'Free';
                    }
                    else{
                      setlocale(LC_MONETARY, 'en_US');
                      $price = '--';
                      $price_1 = '$' . number_format($item['price'], 2);
                    }
                  @endphp
                  <span class="price" data-price="{{$item['price']}}">{{ $price_1 }}</span>

                 <span class="cart-x"><!--a href="">Remove</a--></span>

              </div>
            </div>
          </div>
        </li>
        @endforeach
      </ul>
    </div>
    <div id="{{$carttype}}-container" class="checkout-container col-sm-4">
      <div class="checkout-stat">
        <p class="sub-total">
          <span class="left">Item Subtotal:</span>
          <span class="right">{{$price}}</span>
        </p>

        @if($carttype=='shopping')
          <p class="cutfee">
            <span class="left">Cut Fees:</span>
            <span class="right"></span>
          </p>
          <p class="message">
            Shipping and taxes will be calculated <br>
            when you select your delivery address.
          </p>
        @else
          <p class="shipping">
            <span class="left">Shipping:</span>
            <span class="right">{{$price}}</span>
          </p>
          <p class="tax">
            <span class="left">Estimated Tax:</span>
            <span class="right">{{$price}}</span>
          </p>
        @endif

      </div>
      <div class="total">
        <p>
          <span class="left">Total:</span>
          <span class="right">{{$price}}</span>
        </p>
      </div>
        @if($carttype=='sample' && $is_client)
        <form action="/cart/{{$carttype}}/checkout" method="get">
          <p>Your order will ship to:</p>
          </p>{{$data['company_name']}}<br/> {{$data['street']}}<br/> {{$data['city']}}, {{$data['state']}} {{$data['zip_code']}}</p>
          <a href="javascript:void(0)" onclick="$('.form-div').slideDown()" class="orangelink">Change</a>
          <div class="row mb-3 form-div" style="display:none">
            <label for="inputName" class="col-sm-2 col-form-label">Name</label>
            <div class="col-sm-10">
              <input type="text" name="company_name" id="inputName" value = "{{$data['company_name']}}">
            </div>
          </div>
          <div class="row mb-3 form-div" style="display:none">
            <label for="inputStreet" class="col-sm-2 col-form-label">Street</label>
            <div class="col-sm-10">
              <input type="text" name="street" id="inputStreet" value = "{{$data['street']}}">
            </div>
          </div>
          <div class="row mb-3 form-div" style="display:none">
            <label for="inputCity" class="col-sm-2 col-form-label">City</label>
            <div class="col-sm-10">
              <input type="text" name="city" id="inputCity" value = "{{$data['city']}}">
            </div>
          </div>
          <div class="row mb-3 form-div" style="display:none">
            <label for="inputState" class="col-sm-2 col-form-label">State</label>
            <div class="col-sm-10">
              <input type="text" name="state" id="inputState" value = "{{$data['state']}}">
            </div>
          </div>
          <div class="row mb-3 form-div" style="display:none">
            <label for="inputZipCode" class="col-sm-2 col-form-label">Zip Code</label>
            <div class="col-sm-10">
              <input type="text" name="zip_code" id="inputZipCode" value = "{{$data['zip_code']}}">
            </div>
          </div>
          <div class="checkout-btn">
            <button type="submit" class="btn btn-primary">PROCEED TO CHECKOUT</button>
          </div>
        </form>
        @else
          <div class="checkout-btn">
            <a href="/cart/{{$carttype}}/checkout" class="btn btn-primary">PROCEED TO CHECKOUT</a>
          </div>
        @endif
        @if (session('orderLimitError'))
        <div class="alert alert-danger mt-3">
            {{session('orderLimitError')}}
        </div>
        @endif
        </div>
    @else
      <div class="col-sm-8 mx-auto">
        <h2 class="text-center">Your cart is empty</h2>
      </div>
    @endif
  </div>
  @else
  <div class="row justify-content-center">
    <h2 class="text-center">Coming Soon...</h2>
  </div>
  @endif

</div>

<div class="cart-note">
  <p>Please note that our samples ship ground.  If you need samples immediately please contact your local rep.</p>
</div>
<!-- <script>
/*function myFunction() {
  document.getElementById("myNumber").value = "16";
}*/
</script> -->
@stop
