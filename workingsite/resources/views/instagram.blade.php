@extends('master')

@section('meta_tags')
{!! SEOMeta::generate() !!}
{!! OpenGraph::generate() !!}
{!! Twitter::generate() !!}
{!! JsonLd::generate() !!}
@endsection

@section('main_content')
    <div id="catalog-list" class="projects">
        <h1 class='text-center'>LATEST SOCIAL MEDIA</h1>
        <h4 class='text-center'>Follow us on Instagram @innovationsusa</h4>
        <div id="padbottom-content" class="col-md-10 mx-auto container">
            <div class="eLIB--grid__singleOption">
                <link rel="stylesheet" href="https://linkin.bio/css/gallery.min.css">
                <iframe src="https://linkin.bio/innovationsusa" width="100%" height="1165px" frameborder="0"></iframe>
            </div>
        </div>
    </div>
@endsection
