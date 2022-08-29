@extends('master')

@section('meta_tags')
    {!! SEOMeta::generate() !!}
    {!! OpenGraph::generate() !!}
    {!! Twitter::generate() !!}
    {!! JsonLd::generate() !!}
@endsection

@section('main_content')
  <h1 class="homehide"> {{$title}} wallcovering catalog</h1>
    @if(isset($status) && $status == "0")
        <div class="alert alert-warning">UNPUBLISHED</div>
    @endif
    @php print_r($embed);
    @endphp
@endsection
