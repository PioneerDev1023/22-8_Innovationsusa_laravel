<?php
//$session_id = \Session::getId();
$message = 'error';
?>
@extends('master')

@section('title')
 - Let Us Shop
@endsection
@section('meta_tags')
{!! SEOMeta::generate() !!}
{!! OpenGraph::generate() !!}
{!! Twitter::generate() !!}
{!! JsonLd::generate() !!}
@endsection
@section('main_content')

{{-- {{$cartObj}} --}}
<!-- <br>
<p>for loop</p> -->

<div id="account-registration" class="container wufoo">
  <div class="row">
    <div class="col-sm-9 mx-auto">

        <div id="wufoo-q1wiudn51t9cjtv"> Fill out my <a href="https://innovations.wufoo.com/forms/q1wiudn51t9cjtv">online form</a>. </div> <script type="text/javascript"> var q1wiudn51t9cjtv; (function(d, t) { var s = d.createElement(t), options = { 'userName':'innovations', 'formHash':'q1wiudn51t9cjtv', 'autoResize':true, 'height':'1246', 'async':true, 'host':'wufoo.com', 'header':'show', 'ssl':true }; s.src = ('https:' == d.location.protocol ?'https://':'http://') + 'secure.wufoo.com/scripts/embed/form.js'; s.onload = s.onreadystatechange = function() { var rs = this.readyState; if (rs) if (rs != 'complete') if (rs != 'loaded') return; try { q1wiudn51t9cjtv = new WufooForm(); q1wiudn51t9cjtv.initialize(options); q1wiudn51t9cjtv.display(); } catch (e) { } }; var scr = d.getElementsByTagName(t)[0], par = scr.parentNode; par.insertBefore(s, scr); })(document, 'script'); </script>

    </div>
  </div>
</div>
@stop
