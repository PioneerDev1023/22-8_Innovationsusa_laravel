@extends('layouts.app')

@section('content')
<div id="home" class="container">
  <div class="row justify-content-center">
    @if ($companyName)
    <h1>{{$companyName}}</h1>
    @else
    <h1>My Dashboard</h1>
    @endif
    <div class="col-md-8">
        <h2 class="box-header text-center">Recent Orders</h2>
        <div class="col-md-12">
        <div class="d-flex order-row-titles">
          {{-- @if (session('status')) --}}
            <!-- <div class="alert alert-success" role="alert">
                {{ session('status') }}
            </div> -->
          {{-- @endif --}}

            <!-- content here -->
            <ul class="list-inline mx-auto justify-content-center">
              <!--<p class='headerP'><span>Order History</span> View or Pay previous orders.</p>-->
              <li class='date list-inline-item'>Date</li>
              <li class='invoicenum list-inline-item'>Invoice</li>
              <li class='ordernum list-inline-item'>Order</li>
              <li class='amount list-inline-item'>Amount</li>
              <li class='action list-inline-item'>Action</li>
            </ul>
      </div>
      @foreach($orderHistoryObj as $order)
      <div class="d-flex order-row">

        @php
          $href1 = $href2 = "class='inactive'";
          $amountDue = (float)$order['v_balance_due'];
        @endphp

        @if ($amountDue > 0)
          @php
            $href2 = "href='/invoice/?" . $order['id'] . "&stat=notification' target='_blank' class='active orderBtn'";

              $now = time();
              // $tt1 = $now - 3600*24;
              $tt1 = $now;// for testing now
              // print_r(strtotime($order['firm_order_date'])) .' '. $tt1); die();

              if( strtotime($order['firm_order_date']) > $tt1 ) {
                $href = "";
                $aClass = 'inactive';
              }else{
                //$href = " href='/home/pay/". $order['full_transaction_number'] ."'";
                $href = ' href="'. $order['id'] . '"';
                $aClass = 'active';
              }

            $payStr = "<li class='paybtnLI list-inline-item'><a class='" . $aClass . " payBtn' " . $href . ">PAY</a></li>";
          @endphp
        @else
          @php
            $href1 = "href='/invoice/?id=" . $order['id'] . "&stat=invoice' target='_blank' class='active'";
            $payStr = "<li class='paybtnLI list-inline-item'><a class='payBtn inactive'>PAID</a></li>";
          @endphp
        @endif

        <ul class="list-inline mx-auto justify-content-center">
          <li class='date list-inline-item'>{{ $order['firm_order_date'] }}</li><?php
          ?><li class='invoicenum list-inline-item'><a {{ $href1 }}>{{ $order['invoice_number'] }}</a></li><?php
          ?><li class='ordernum list-inline-item'><a {{ $href2 }} class='orderBtn' >{{ $order['full_transaction_number'] }}</a></li><?php
          ?><li class='amount list-inline-item'>${{ number_format($order['total_amount'], 2) }}</li><?php
          ?>{!! $payStr !!}
        </ul>
      </div>
      @endforeach
        </div>
    </div>
  </div>
</div>
@endsection
