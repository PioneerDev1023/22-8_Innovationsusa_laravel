@php
//print_r( Cookie::get() ); die();
@endphp
<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
{!! Config::get('constants.value.google1') !!}
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
@hasSection('meta_tags')
@yield('meta_tags')
@else
{!! SEOMeta::generate() !!}
{!! OpenGraph::generate() !!}
{!! Twitter::generate() !!}
{!! JsonLd::generate() !!}
@endif


<link rel="apple-touch-icon" sizes="76x76" href="/images/icons/apple-touch-icon.png">
<link rel="icon" type="image/png" sizes="32x32" href="/images/icons/favicon-32x32.png">
<link rel="icon" type="image/png" sizes="16x16" href="/images/icons/favicon-16x16.png">
<link rel="manifest" href="/images/icons/site.webmanifest">
<link rel="mask-icon" href="/images/icons/safari-pinned-tab.svg" color="#5bbad5">
<meta name="msapplication-TileColor" content="#da532c">
<meta name="theme-color" content="#ffffff">
<meta name="robots" content="max-image-preview:large">


<meta name="author" content="Innovations USA"/>
<meta name="copyright" content="{{ now()->year }} Innovations USA"/>
 <meta name="facebook-domain-verification" content="mep3j3g0n6lflop29olu2miwkc02sl" />
 <meta name="csrf-token" content="{{ csrf_token() }}">
<link rel="canonical" href="{{  Request::fullUrl() }}" />
<link href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css" rel="stylesheet"/>
<script
  src="https://code.jquery.com/jquery-3.5.1.min.js"
  integrity="sha256-9/aliU8dGd2tb6OSsuzixeV4y/faTqgFtohetphbbj0="
  crossorigin="anonymous"></script>
<script src='https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js' type='text/javascript'></script>
@if(Request::is('/'))
<script id="mcjs">!function(c,h,i,m,p){m=c.createElement(h),p=c.getElementsByTagName(h)[0],m.async=1,m.src=i,p.parentNode.insertBefore(m,p)}(document,"script","https://chimpstatic.com/mcjs-connected/js/users/efc599d1386bb3db323a26192/191416bc3c06fd80585b97d9c.js");</script>
@endif
@stack('styles')
<link rel="stylesheet" type="text/css" href="{{ asset('css/app.css') }}?v={{Config::get('constants.value.VER')}}"/>
@stack('scripts_head')
</head>
<body @if (Request::is('presentation-request')) class="wholebody" @endif>
{!! Config::get('constants.value.google2') !!}
@if(Request::is('/'))
  <div class="text-center" style="background-color:#7A7D81">
    <a href="https://www.innovationsusa.com/product/all-wallcovering" class="text-center text-white text-uppercase" style="font-size:0.8rem;">Visit our product pages to request a free sample! </a>
  </div>
@endif

@yield('main_content')

</body>

<script type="text/javascript">
  var $zoho=$zoho || {};
  $zoho.salesiq = $zoho.salesiq ||

  {widgetcode:"3458724986b44bdae45b9784be5bcbe078d1e36ebbad81385d6924c0b40da02e1a2010ab7b6727677d37b27582c0e9c4", values:{},ready:function(){}};

  var d=document; //.getElementById("chatcontainer");
      s=d.createElement("script");
      s.type="text/javascript";
      s.id="zsiqscript";
      s.defer=true;

  s.src="https://salesiq.zoho.com/widget";
  t=d.getElementsByTagName("script")[0];
  t.parentNode.insertBefore(s,t);
  d.write("<div id='zsiqwidget'></div>");
</script>
@stack('scripts')
<script type="text/javascript" src="{{ asset('js/app.js') }}?v={{Config::get('constants.value.VER')}}"></script>
</html>
