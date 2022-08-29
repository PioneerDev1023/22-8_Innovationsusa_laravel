@extends('master')

@section('meta_tags')
    {!! SEOMeta::generate() !!}
    {!! OpenGraph::generate() !!}
    {!! Twitter::generate() !!}
    {!! JsonLd::generate() !!}
@endsection

@section('main_content')

<div class="col-md-12">
    <form action="/update-csv-product" method="post" enctype="multipart/form-data">
        @csrf
        <div class="form-group">
            <label for="csvfile">Updated CSV File</label>
            <input name="updated_csv_file" type="file" class="form-control-file" id="csvfile">
        </div>
        <div class="form-group">
            <label for="csvfile">Original CSV File</label>
            <input name="template_csv_file" type="file" class="form-control-file" id="csvfile">
        </div>
        <div class="form-group row">
        <div class="form-group row">
            <div class="col-sm-10">
            <button type="submit" class="btn btn-primary">Upload CSV</button>
            </div>
        </div>
    </form>
</div>

@endsection
