<?php
$message = 'error';
?>
@extends('master')

@section('title')
 - Presentation Request Form
@endsection
@section('main_content')

{{-- {{$cartObj}} --}}
<!-- <br>
<p>for loop</p> -->

<div id="account-registration" class="container wufoo fa21">
  <div class="row">
    <div class="col-sm-9 mx-auto">
      <div id="wufoo-z1vmu4yb0t9f6fo"> Fill out my <a href="https://innovations.wufoo.com/forms/z1vmu4yb0t9f6fo">online form</a>. </div> <script type="text/javascript"> var z1vmu4yb0t9f6fo; (function(d, t) { var s = d.createElement(t), options = { 'userName':'innovations', 'formHash':'z1vmu4yb0t9f6fo', 'autoResize':true, 'height':'690', 'async':true, 'host':'wufoo.com', 'header':'show', 'ssl':true }; s.src = ('https:' == d.location.protocol ?'https://':'http://') + 'secure.wufoo.com/scripts/embed/form.js'; s.onload = s.onreadystatechange = function() { var rs = this.readyState; if (rs) if (rs != 'complete') if (rs != 'loaded') return; try { z1vmu4yb0t9f6fo = new WufooForm(); z1vmu4yb0t9f6fo.initialize(options); z1vmu4yb0t9f6fo.display(); } catch (e) { } }; var scr = d.getElementsByTagName(t)[0], par = scr.parentNode; par.insertBefore(s, scr); })(document, 'script'); </script>
    </div>
  </div>
  </div>
</div>
@stop
