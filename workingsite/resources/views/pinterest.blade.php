@extends('master')

@section('meta_tags')
{!! SEOMeta::generate() !!}
{!! OpenGraph::generate() !!}
{!! Twitter::generate() !!}
{!! JsonLd::generate() !!}
@endsection

@section('main_content')
  <div class='container'>
        <div id="catalog-list" align="center">
        <h1 class='text-center'>LATEST FROM PINTEREST</h1>
        <h4 class='text-center'>Follow us on Pinterest @innovationsusa</h4>
          <div id="padbottom-content" class="col-md-10 mx-auto container">
            <a data-pin-do="embedUser" data-pin-board-width="1200" data-pin-scale-height="1200" data-pin-scale-width="900" href="https://www.pinterest.com/innovationsusa"></a>
          </div>
        </div>
  </div>
    <script async defer src="//assets.pinterest.com/js/pinit.js"></script>
@endsection
