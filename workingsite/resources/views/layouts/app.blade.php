<!DOCTYPE html>
<html lang="en">
<head>
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
<!-- CSRF Token -->
<meta name="csrf-token" content="{{ csrf_token() }}">
<title>Innovationsusa - @yield('title')</title>
<link href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css" rel="stylesheet"/>
<!-- <link rel="stylesheet" href="{{ asset('font-awesome/css/font-awesome.min.css') }}"> -->
<!-- <link rel="stylesheet" href="{{ asset('css/style_innovations.css') }}"/> -->
<link rel="stylesheet" href="{{ asset('css/app.css') }}"/>
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
<script src='https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js' type='text/javascript'></script>
<script id="mcjs">!function(c,h,i,m,p){m=c.createElement(h),p=c.getElementsByTagName(h)[0],m.async=1,m.src=i,p.parentNode.insertBefore(m,p)}(document,"script","https://chimpstatic.com/mcjs-connected/js/users/efc599d1386bb3db323a26192/191416bc3c06fd80585b97d9c.js");</script>
@stack('head')
</head>
<body @if (Request::is('login') || Request::is('register')) class="scrollbottom" @endif>
@include('nav.top')

        <main class="py-4">
            @yield('content')
        </main>

</body>
@include('nav.footer')

<script
  src="https://code.jquery.com/jquery-3.5.1.min.js"
  integrity="sha256-9/aliU8dGd2tb6OSsuzixeV4y/faTqgFtohetphbbj0="
  crossorigin="anonymous"></script>
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/js/bootstrap.min.js"></script>
<script type="text/javascript" src="{{url('js/app.js')}}"></script>
</html>
