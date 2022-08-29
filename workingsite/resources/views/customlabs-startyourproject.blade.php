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

<div id="account-registration" class="container wufoo">
  <div class="row">
    <div class="col-sm-9 mx-auto">
        
        <div id="wufoo-zjgeamv1bwml95"> Fill out my <a href="https://innovations.wufoo.com/forms/zjgeamv1bwml95">online form</a>. </div> <script type="text/javascript"> var zjgeamv1bwml95; (function(d, t) { var s = d.createElement(t), options = { 'userName':'innovations', 'formHash':'zjgeamv1bwml95', 'autoResize':true, 'height':'1073', 'async':true, 'host':'wufoo.com', 'header':'show', 'ssl':true }; s.src = ('https:' == d.location.protocol ?'https://':'http://') + 'secure.wufoo.com/scripts/embed/form.js'; s.onload = s.onreadystatechange = function() { var rs = this.readyState; if (rs) if (rs != 'complete') if (rs != 'loaded') return; try { zjgeamv1bwml95 = new WufooForm(); zjgeamv1bwml95.initialize(options); zjgeamv1bwml95.display(); } catch (e) { } }; var scr = d.getElementsByTagName(t)[0], par = scr.parentNode; par.insertBefore(s, scr); })(document, 'script'); </script>

    </div>
  </div>
</div>
@stop