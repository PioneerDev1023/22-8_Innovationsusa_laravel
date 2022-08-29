@extends('voyager::master')

@section('page_header')

@endsection

@include('voyager::alerts')
@include('voyager::dimmers')

@section('content')

      <h1 class="page-title">Export Guest Users to CSV File</h1>
      <div class="container-fluid">
        <p>Export all guests registered between the following dates:</p>
      
        {{ Form::open(
          array(
              'name' => 'export-guests',
              'url' => 'dashboard/export-guests/',
              //'onSubmit' => 'actionOnSubmit()',
              'method' => 'post',
              'class' => "form"
              )
          ) }}

         @php
         echo '<div class="form-group  col-md-12">';
         echo Form::label('from', 'Date From', ['class' => 'control-label']);         
         echo Form::date('from', null, ['required'=>'false', 'id' => 'from', 'class'=>'form-control']);
         echo '</div><div class="form-group  col-md-12 ">';
         echo Form::label('to', 'Date To', ['class' => 'control-label']);
         echo Form::date('to', null, ['required'=>'false', 'id' => 'to', 'class'=>'form-control']);
         echo '</div>';
         echo Form::submit('EXPORT CSV FILE', ['class' => 'btn btn-primary']);
         @endphp
         {{ Form::close() }}
         </div>
@endsection
