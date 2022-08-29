@extends('layouts.app')

@section('content')
<div class="container mt-5">

@if($client == false)
<h3 class="text-center mx-auto col-md-10">Data not available</h3>
@else
  <div class="row align-items-center flex-column">

    @if ($companyName && $userName)
    <div class = "row col-md-12">
      <div class="col-md-1"></div>
      <h3 class="text-center mx-auto col-md-10">     HI {{strtoupper($userName)}}, {{strtoupper($companyName)}}</h3>
      <a class="col-md-1" href="/logout">LOGOUT</a>
    </div>
    @else
    <h3 class="text-center mx-auto">My Dashboard</h3>
    @endif
    <h5 class="box-header text-center mx-auto mt-5 mb-1">Your Sales Representative: {{$repName}}</h5>
    <h5 class="text-center mx-auto mb-1">Phone: {{$repPhoneNumber}}</h5>
    <h5 class="text-center mx-auto mb-5">Send rep an <a href="mailto:{{$repEmail}}" target='top' class='orangelink'>e-mail</a>
      . </h5>
    <div class="col-md-10 d-flex flex-md-row flex-sm-column homeTile-container">
      <div class="flex-grow-1 d-block">
        <a href="/product/all-wallcovering"><img src="/images/ui/Innovations_Harlequin.jpg" style="width:100%"><h5 class="text-center mx-auto">SHOP</h5></a>

      </div>
      <div class="flex-grow-1 d-block">
        <a href="/docs/SU22-US-Pricelist.pdf" target="_blank"><img src="/images/ui/Innovations_Intaglio.jpg" style="width:100%"><h5 class="text-center mx-auto">PRICE LIST</h5></a>

      </div>
      <div class="flex-grow-1 d-block myaccount-box">
        <a href="/home/my-account"><img src="/images/ui/Innovations-Pulse.jpg" style="width:100%"><h5 class="text-center mx-auto">MY ACCOUNT</h5></a>

        <!-- <span class="tooltiptext" style="top:60%; left:50%; margin-left:-60px;">Coming Soon</span> -->
      </div>
    </div>
    <div class="container-fluid">

    </div>

  </div>
</div>
<div id="home" class="container-fluid">
      <div class="row col-md-10 mx-auto justify-content-center order-header">
        <div class="col-md-12 text-center">
          <a href="#"><h3 class="box-header font-weight-bold d-block">ORDER HISTORY</h3></a>
        </div>
        @if($is_client)
          <p><a class="active" id="purchase_order">Purchases</a> <a id="sample_order">Samples</a></p>
        @endif
        @if(isset($orderHistoryObj) && count($orderHistoryObj) > 0)
        <div class="col-md-12 purchase-container">


          <table class="table order-table">
            <thead class="">
              <tr>
                <th scope="col">DATE</th>
                <th scope="col">INVOICE</th>
                <th scope="col">ORDER</th>
                <th scope="col">AMOUNT</th>
                <th scope="col">STATUS</th>
                <th scope="col">TRACKING</th>
              </tr>
            </thead>
            <tbody>
             @foreach($orderHistoryObj as $order)
              <tr>
                <td>{{ $order['firm_order_date'] }}</td>
                <td>

                  <form action="{{$order['href1']}}" method="post" target="_blank">@csrf<button type="submit" class="btn-link btn focus-nooutline p-0">{{ $order["invoice_number"] }}</button></form>

                </td>
                <!-- <td><a {{ $order['href1']}}>{{ $order['invoice_number'] }}</a></td> -->
                <td><a {{ $order["href2"] }} class='orderBtn' >{{ $order['full_transaction_number'] }}</a></td>
                <td>${{ number_format($order['total_amount'], 2) }}</td>
                <td>
                  @if($order['href']!= "")
                  <form action="{{$order['href']}}" method="post" target="_blank">@csrf<button type="submit" class="btn-link btn focus-nooutline orange-color p-0">{{$order['payStr']}}</button></form>
                  @else
                  <a class='payBtn inactive'>PAID</a>
                  @endif
                </td>
                <td>{!! $order['linkStr'] !!}</td>
              </tr>
              @endforeach
            </tbody>
          </table>
          <p class='d-flex justify-content-center'>Please contact Customer Service for details on older orders.</p>
        @else
        <div class="col-md-12">
          <div class="d-flex order-row-titles justify-content-center">
            No recent orders.
          </div>
        </div>
        @endif
      </div>
      <div class="col-md-12 sample-container">
        @if(isset($sampleOrders) && count($sampleOrders) > 0)
          <table class="table order-table">
            <thead class="">
              <tr>
                <th scope="col"> ORDER ID</th>
                <th scope="col"> SHIP-TO ATTENTION</th>
                <th scope="col"> STATUS</th>
              </tr>
            </thead>
            <tbody>
            @foreach($sampleOrders as $index => $sampleOrder)
              <tr>
                <td>{{$sampleOrder->id}}</td>
                <td>{{$sampleOrder->ship_to_attention}}</td>
                <td>{{$sampleOrder->status}}</td>
              </tr>
            @endforeach
            </tbody>
          </table>
        @endif
      </div>
  @endif
    </div>
    <div class="container">

<div class="row align-items-center flex-column">
  <h4 class="text-center mx-auto font-weight-bold mt-5">NEED HELP?</h4>
  <h6 class="box-header text-center mx-auto mt-1">Call us at 800.2276.8053 or email us <a class="btn-link" href="mailto:info@innovationsusa.com">info@innovationsusa.com</a></h6>
  <script type="text/javascript">
    $(document).ready(function() {
        $('#purchase_order').on('click', function() {

          $('#sample_order').removeClass('active');
          $('#purchase_order').addClass('active');
          $('.sample-container').hide();
          $('.purchase-container').slideDown();
        });
        $('#sample_order').on('click', function() {

          $('#purchase_order').removeClass('active');
          $('#sample_order').addClass('active');
          $('.purchase-container').hide();
          $('.sample-container').slideDown();
        });
      }
    )
  </script>
    @endsection
