<?php
//$session_id = \Session::getId();
$message = 'error';
?>
@extends('master') 

@section('title')
 - Account Registration
@endsection
@section('main_content')

{{-- {{$cartObj}} --}}
<!-- <br>
<p>for loop</p> -->

<div id="account-registration" class="container">
  <div class="row">
    <div class="col-sm-9 mx-auto">
        
        <div id="wufoo-r1t77chw133q0zq"> Fill out my <a href="https://innovations.wufoo.com/forms/r1t77chw133q0zq">online form</a>. </div> 
        <script type="text/javascript"> var r1t77chw133q0zq; (function(d, t) { var s = d.createElement(t), options = { 'userName':'innovations', 'formHash':'r1t77chw133q0zq', 'autoResize':true, 'height':'1160', 'async':true, 'host':'wufoo.com', 'header':'show', 'ssl':true }; s.src = ('https:' == d.location.protocol ?'https://':'http://') + 'secure.wufoo.com/scripts/embed/form.js'; s.onload = s.onreadystatechange = function() { var rs = this.readyState; if (rs) if (rs != 'complete') if (rs != 'loaded') return; try { r1t77chw133q0zq = new WufooForm(); r1t77chw133q0zq.initialize(options); r1t77chw133q0zq.display(); } catch (e) { } }; var scr = d.getElementsByTagName(t)[0], par = scr.parentNode; par.insertBefore(s, scr); })(document, 'script'); </script>

    </div>
  </div>
</div>
@stop