@php
$ver = Config::get('constants.value.VER');
$baseUrl = Config::get('constants.value.baseUrl');
$imgUrl = Config::get('constants.value.imgUrl');
//$session_id = \Session::getId();
$message = 'error';
//print_r($cartObj); die();
@endphp

@extends('master')

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
    'action' => 'cartcheckout',
    'callback_then' => 'callbackThen',
    'callback_catch' => 'callbackCatch'
]) !!}
@endpush
@endif
@endauth

@section('title')
 - Sample Cart: Checkout
@endsection
@section('main_content')

{{-- {{$cartObj}} --}}
<!-- <br>
<p>for loop</p> -->

<div id="cart-checkout" class="container-fluid">
  <div class="row r1 justify-content-center">

    @php
        $nextpage = '/ship';
        if($carttype=='sample')
          $nextpage = '/confirmation';

        $formUrl = '/cart/'.$carttype . $nextpage;
    @endphp

    @if(!$is_client)
    <div class="cart-address col-sm-6" style="margin-bottom:100px">
      <h2>Shipping Address</h2>
      <!--h1>{{-- $carttype --}} Cart Checkout</h1-->
      <!-- <form action="/sample-cart/thankyou" method="POST">

        {{--@csrf--}}
        <label for="title">Post Title</label>

        <input type="text" class="@error('title') is-invalid @enderror">
        <input type="text" id="username" name="username" required="required">
        <input type="submit">

        @error('title')
            <div class="alert alert-danger">{{ $message }}</div>
        @enderror

      </form>  -->

        {{ Form::open(array('url' => $formUrl)) }}

         @php
         echo Form::hidden('carttype', "$carttype");
         if($loginUser != NULL)
          echo Form::hidden('email', $loginUser->email );
         else {
          echo Form::text('email', '', ['required'=>'required', 'placeholder'=>'E-Mail'] );
         }
         echo Form::select('country', $countryArr, null, ['required'=>'required', 'placeholder'=>'Country', 'class'=>'country']);
         echo Form::text('firstname', '', ['required'=>'required', 'placeholder'=>'First Name', 'class'=>'half']);
         echo Form::text('lastname', '', ['required'=>'required', 'placeholder'=>'Last Name', 'class'=>'half lastname']);
         echo Form::text('address', '', ['required'=>'required', 'placeholder'=>'Address']);
         echo Form::text('address2', '', ['placeholder'=>'Apt, Suite, Etc']);
         echo Form::hidden('carttype', "$carttype");
         if($loginUser != NULL && $is_guest)
          {
            echo Form::text('company', $data['company_name']);
          }
         else {
          echo Form::text('company', '', ['required'=>'required', 'placeholder'=>'Company Name']);
         }
         echo Form::text('city', '', ['required'=>'required', 'placeholder'=>'Town/City']);
          //echo Form::text('state', '', ['required'=>'required', 'placeholder'=>'State', 'class'=>'half']);
          //$stateList = view('utils.statelist')->render();
          // $arr = @include('utils.statelist'); //@include('utils.statelist');

         echo Form::select('state', $statesArr, null, ['required'=>'required', 'placeholder'=>'State', 'class'=>'half state']);
         echo Form::text('zip', '', ['required'=>'required', 'placeholder'=>'Zip', 'class'=>'half']);
         echo Form::text('phone', '', ['placeholder'=>'Phone Number', 'class'=>'phone-number']);
         echo Form::label('phone', 'Used to contact you with delivery info (mobile preferred)');
         echo "<br>";
         echo Form::label('profession', 'Please select one of the following:');
         echo "<br>";
         echo Form::label('profession', 'I am a...');
         echo "<br>";
         echo Form::select('profession',
            array('contractor'=> 'Contractor/Installer',
            'purchasing_agent'=> 'Purchasing Agent',
            'designer'=>'Designer/Decorator',
            'consumer'=>'Consumer/End User',
            'other'=>'Other'), null, ['required' => 'required'], ['class' => 'form-control']);

         echo "<br>";
         echo Form::submit('SUBMIT', ['class'=>'btn btn-primary']);
         @endphp
         {{ Form::close() }}

    </div>
    @endif


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

    <div class="cart-items {{$loginUser === NULL ? 'col-sm-4' : 'col-sm-6'}}">
      <!--a href="/cart/{{ $carttype }}">Back to Cart</a-->
      <h2 class='d-flex justify-content-center'>Order Summary</h2>


<div class="cart-subhead">
  <span class="count-str">{{$countStr}}</span>

  @if($count>1)
  <a class="dropdown-toggle {{$collapsed}}" data-toggle="collapse" href="#collapse-filter-{{$carttype}}" role="button" aria-expanded="false" aria-controls="collapseFilter">Expand</a>
 @endif

</div>

    @if($count > 0)
      @foreach($cartObj as $key => $item)
        @if($key==0)
          <ul class="container" >
            <li class="row li-{{$key}}">
              <a class="col-md-1">
                <img class="img-fluid" src="{{$imgUrl}}/storage/sku/350x350/{{ $item['id'] }}.jpg" alt="">
              </a>
              <div class="wrapper col-md-11">
                <div class="info">
                    <p data-itemname="{{ $item['name'] }}">{{ $item['name'] }}</p>
                    <p>
                      <span data-itemnum="{{ $item['id'] }}">{{ $item['id'] }}</span>
                      <span data-itemcolor="{{ $item['color'] }}">{{ ucwords(strtolower( $item['color'] )) }}</span><br>
                      <span data-itemquant="{{ $item['quantity'] }}">Quantity: {{ $item['quantity'] }}</span>
                    </p>
                </div>
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
          @if($key>0)
            <li class="row li-{{$key}}">
              <a class="col-md-1">
                <img class="img-fluid" src="{{$imgUrl}}/storage/sku/350x350/{{ $item['id'] }}.jpg" alt="">
              </a>
              <div class="wrapper col-md-11">
                <div class="info">
                    <p data-itemname="{{ $item['name'] }}">{{ $item['name'] }}</p>
                    <p>
                      <span data-itemnum="{{ $item['id'] }}">{{ $item['id'] }}</span>
                      <span data-itemcolor="{{ $item['color'] }}">{{ ucwords(strtolower( $item['color'] )) }}</span><br>
                      <span data-itemquant="{{ $item['quantity'] }}">Quantity: {{ $item['quantity'] }}</span>
                    </p>
                </div>
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
                  <span data-itemcolor="{{ $item['color'] }}">{{ ucwords(strtolower( $item['color'] )) }}</span><br>
                  <span data-itemquant="{{ $item['quantity'] }}">Quantity: {{ $item['quantity'] }}</span>
                </p>
            </div>
          </div>

        </li>
        @endforeach
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

      @if($carttype == "sample" && $is_client)
      <div class="address">
        <p>
          <span class="left">Shipping Address:</span>
          <span class="right">{{$data['company_name']}} <br> {{$data['street']}}<br> {{$data['city']}}, {{$data['state']}}  {{$data['zip_code']}}</span>
        </p>
      </div>
      @endif

      @if($is_client)
      {{ Form::open(array('url' => $formUrl)) }}

      @php
      $name = explode(" ", $loginUser->name);
      if(count($name) == 1) {
        $name[1] = "";
      }
         echo Form::hidden('carttype', $carttype);
         echo Form::hidden('email', $loginUser->email);
         echo Form::hidden('country', $loginUser->client->country);
         echo Form::hidden('firstname', trim($name[0]));
         echo Form::hidden('lastname', trim($name[1]));
         echo Form::hidden('address', $data['street']);
         echo Form::hidden('address2', '');
         echo Form::hidden('company', $data['company_name']);
         echo Form::hidden('city', $data['city']);
         echo Form::hidden('state', $data['state']);
         echo Form::hidden('zip', $data['zip_code']);
         echo Form::hidden('phone', '');
         echo Form::hidden('profession', 'consumer');
         if($carttype == "sample" && $is_client) {
           echo Form::submit('ORDER', ['class'=>'btn btn-primary m-auto btn-submit', 'style'=>'display:block']);
         } else {
         echo Form::submit('PROCEED', ['class'=>'btn btn-primary m-auto btn-submit', 'style'=>'display:block']);
         }
         @endphp

      {{ Form::close() }}
      @endif
      <div class="justify-content-center spinner-div" style="display: none;">
        <div class="spinner-border" role="status">
          <span class="sr-only">Loading...</span>
        </div>
      </div>
    </div>
  </div>
</div>
<script type="text/javascript">
  $('.order-form').on('submit', function() {
    $('.btn-submit').attr('disabled', true);
    $('.btn-submit').css('display', 'none');
    $('.spinner-div').css('display', 'flex');
  });
</script>
@stop
