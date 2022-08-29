@extends('master')

@section('meta_tags')
    {!! SEOMeta::generate() !!}
    {!! OpenGraph::generate() !!}
    {!! Twitter::generate() !!}
    {!! JsonLd::generate() !!}
@endsection

@section('main_content')
    @if(isset($status) && $status == "0")
        <div class="alert alert-warning">UNPUBLISHED</div>
    @endif
    <div class="container-fluid" style="height:100vh; padding-top: 50px;">
        <div class="container">
            <div class="row">
                <div class="col-md-6 mx-auto">
                    @php print_r($embed);
                    @endphp
                </div>
            </div>
        </div>
    </div>
@endsection
