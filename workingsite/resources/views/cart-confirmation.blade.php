@php
$ver = Config::get('constants.value.VER');
$baseUrl = Config::get('constants.value.baseUrl');
@endphp

@extends('master') 

@section('main_content')

<div id="cart-confirmation">
  <div class="container-fluid custom-head">
    <div class="imgContainer">
      <img src="../../images/ui/Order-Confirmation.jpg" alt="">
    </div>
    <div class="headContentContainer">
      <h1>ORDER CONFIRMATION</h1>
      <p>Thank you for placing an order with Innovations. You should receive a confirmation email with your order details shortly. If you have any questions, please contact your local sales representative or Innovations corporate at 800.227.8053</p>
    </div>
  </div>
  <div class="container">
    <div class="row">
       <div class="col-md-10 mx-auto custom-body">
        <h3>NEED HELP?</h3>
        <p>
          Call us at <a href="tel:8002278053">800.227.8053</a> or email us at <a href="mailto:info@innovationsusa.com">info@innovationsusa.com</a>
        </p>

      </div>

    </div>
  </div>
</div>
@stop