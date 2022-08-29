@php
$dateTime = new DateTime('now', new DateTimeZone('America/New_York'));
$ver = Config::get('constants.value.VER');
$imgUrl = Config::get('constants.value.imgUrl');
@endphp
<html>
<head>
    <title>Innovations Order</title>
</head>
<body>
  <table width="600" style="padding-bottom:20px;">
     <tr>
      <td align="right"><img src="https://www.innovationsusa.com/ui/logoLG_397x45.png"/></td>
     </tr>
    </table>
    <table style="padding-bottom:10px; font-family:Verdana, Geneva, sans-serif; font-size:14px">
        <tr>
            <td width="310">Your order summary is below.<br/>Thank you again for your business.</td>
            <td width="70"></td>
            <td valign="top">Order Questions?<br/>Call Us: 800.227.8053<br/>Email: <a href="mailto:info@innovationsusa.com">info@innovationsusa.com</a> </td>
        </tr>
    </table>

     <table width="600" style="padding:30px; font-family:Verdana, Geneva, sans-serif; font-size:14px">
      <tr><th align="center">Your Order</th></tr>
        <tr><td align="center">Placed on {{  $dateTime->format('M d, Y h:i A') }}<!-- June 12, 2015 6:10 PM --> EDT</td></tr>
    </table>

    <table width="600" style="font-family:Verdana, Geneva, sans-serif; font-size:14px">
        <tr>
            <th width="420" align="left">Sample Items in your request</th>
            <th width="70" align="left">Qty</th>
            <th width="90" align="left">Price</th>
        </tr>
    </table>
     <table width="600" style="padding-bottom:20px; font-family:Verdana, Geneva, sans-serif; font-size:14px">



    @foreach($fullArr['samples'] as $item)
      <tr>
          <td width="70">
          <img src="{{$imgUrl}}/storage/sku/350x350/{{ $item['id'] }}.jpg" height="65" />
          </td>
          <td width="180">
          {{ $item['id'] }} <br/>
          {{ $item['color'] }}
          </td>
          <td width="170">{{ $item['name'] }}</td>
          <td width="70" align="left">{{ $item['quantity'] }}</td>
          <td width="90" align="left">$0.00</td>
      </tr>
    @endforeach




    </table>
    <table width="600" style="padding-bottom:20px; font-family:Verdana, Geneva, sans-serif; font-size:14px">
        <tr>
            <td width="400"></td>
            <td align="left">Sub Total</td>
            <td align="left">$0.00</td>
        </tr>
        <tr>
            <td></td>
            <td align="left" width="200">Shipping & Handling</td>
            <td align="left" width="100">$0.00</td>
        </tr>
        <tr>
            <td></td>
            <td align="left" style="font-weight:bold">Grand Total</td>
            <td align="left" style="font-weight:bold">$0.00</td>
        </tr>
    </table>
    <table style="font-family:Verdana, Geneva, sans-serif; font-size:12px; padding-bottom:40px;">
      <tr>
            <td width="240">
                Job: {{ $fullArr['form']['profession'] }} <br/>
                Ship to:<br/>
          <br/>
                {{ $fullArr['form']['company'] }} <br/>
                {{ $fullArr['form']['firstname'] }} {{ $fullArr['form']['lastname'] }}<br/>
                {{ $fullArr['form']['address'] }}<br/>
                @if($fullArr['form']['address2'] != '' )
                  {{ $fullArr['form']['address2'] }}<br/>
                @endif

                {{ $fullArr['form']['city'] }}, {{ $fullArr['form']['state'] }}, {{ $fullArr['form']['zip'] }}<br/>
                United States<br/>
                T: {{ $fullArr['form']['phone'] }}                
            </td>
        </tr>
    </table>
    <table width="600" style="font-family:Verdana, Geneva, sans-serif; font-size:12px;">
      <tr>
          <td align="center">Innovations in Wallcoverings, Inc. &copy; {{ date("Y")}}</td>
        </tr>
    </table>
</body>
</html>
