<?php
//$session_id = \Session::getId();
$message = 'error';
?>
@extends('master')

@section('title')
 - Sample Cart: Checkout
@endsection
@section('main_content')

{{-- {{$cartObj}} --}}
<!-- <br>
<p>for loop</p> -->

<div id="cart-ship" class="container-fluid justify-content-center">
  <div class="row r1 justify-content-center">
    <div class="cart-info col-md-4">
      <div class="address">

        <h2>Shipping Address</h2>
        @php
          $address = $formArr['form']['address'];
          if($formArr['form']['address2']!=''){
            $address = $formArr['form']['address'] . ',' . $formArr['form']['address2'];
          }


          $data = '+1' . $formArr['form']['phone']; // '+11234567890'; 8285775197
          if(  preg_match( '/^\+\d(\d{3})(\d{3})(\d{4})$/', $data,  $matches ) )
          {
              $phone = $matches[1] . '-' .$matches[2] . '-' . $matches[3];
              //print_r($result); die();
              //return $result;
          }else{
            $phone = $formArr['form']['phone'];
          }

        @endphp
        @foreach($formArr as $key => $val)
          {{$val['fullname']}} <br/>
          {{$address}}<br>{{$val['city']}}, {{$val['state']}} {{$val['zip']}} <br/>
          {{$phone}}
        @endforeach

      </div>

      <div class="ship-option">
        <h2>Shipping Option</h2>
        @php
        $formUrl = '/cart/shopping/review';
        @endphp
        {{ Form::open(array('url' => $formUrl)) }}
        @php
          echo Form::hidden('TrancId', $TrancId);
          echo Form::hidden('carttype', $carttype);
          echo Form::hidden('firstname', $val['firstname']);
          echo Form::hidden('lastname', $val['lastname']);
          echo Form::hidden('fullname', $val['fullname']);
          echo Form::hidden('address', $address);
          echo Form::hidden('phone', $phone);
                                          // value
          if($freightOpts == NULL)
          {
            echo Form::radio('ship_option', '2', false, ['id'=>'ship1','required'=>'required', 'class'
            =>"d-inline"]);
            echo Form::label('ship1', 'Standard Shipping (5-8 Business Days) Free', ['class'=>"d-inline"]);
            echo "<br>";                    // value
            echo Form::radio('ship_option', '3', false, ['id'=>'ship2','required'=>'required', 'class'
            =>"d-inline"]);
            echo Form::label('ship2', '3 Day Shipping', ['class'=>"d-inline"]);
            echo "<br>";                    // value
            echo Form::radio('ship_option', '5', false, ['id'=>'ship3','required'=>'required', 'class'
            =>"d-inline"]);
            echo Form::label('ship3', 'Next Day Shipping', ['class'=>"d-inline"]);
            echo "<br>";
          }
          else {
            foreach($freightOpts as $index=>$freight) {
              echo Form::radio('ship_option', $freight->service_id, false, ['id'=>"ship".$index,'required'=>'required', 'class'
              =>"d-inline"]);
              echo Form::label('ship'.$index, $freight->service, ['class'=>"d-inline"]);
              echo "<br>";
            }
          }
          echo Form::submit('SAVE AND CONTINUE', ['class'=>'btn btn-primary']);
        @endphp
        {{ Form::close() }}

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

    @if($count > 0)
      @foreach($cartObj as $key => $item)
        @if($key==0)
          <ul class="container" >
            <li class="row li-{{$key}}">
              <a class="col-md-1">
                <img class="img-fluid" src="https://www.innovationsusa.com/storage/sku/350x350/{{ $item['id'] }}.jpg" alt="">
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
                <img class="img-fluid" src="https://www.innovationsusa.com/storage/sku/350x350/{{ $item['id'] }}.jpg" alt="">
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
          @endif
        @endforeach
      </ul>

    @else
      <ul class="container">
        @foreach($cartObj as $item)
        <li class="row">
          <a class="col-md-1">
            <img class="img-fluid" src="//innovationsusa.com/+aimg2017_dev/colordetail_184x184/{{ $item['id'] }}.jpg" alt="">
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

    </div>
  </div>
</div>
@stop
