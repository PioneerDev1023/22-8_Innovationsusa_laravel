@extends('voyager::master')

@section('page_header')

@endsection

@include('voyager::alerts')
@include('voyager::dimmers')

@section('content')

      <h1 class="page-title">Add or update products from Web Distribution</h1>
      <div class="container-fluid">
        <p>Enter a new or existing product to update/add from Web Distribution.</p>
      </div>
        {{ Form::open(
          array(
              'name' => 'update-from-wd',
              'url' => 'dashboard/update-from-wd/',
              //'onSubmit' => 'actionOnSubmit()',
              'method' => 'post'
              )
          ) }}

         @php
         echo Form::label('product', 'Product to Update/Add', ['class' => 'label']);
         echo Form::text('product', null, ['required'=>'false', 'placeholder'=>'Product name', 'id' => 'product']);
         echo Form::submit('UPDATE/ADD', ['class' => 'btn btn-primary']);
         @endphp
         {{ Form::close() }}
         <br>
         <div class="container-fluid">
           <p>Enter a client id to update/add from Web Distribution.</p>
         </div>
         {{ Form::open(
           array(
               'name' => 'update-client-from-wd',
               'url' => 'dashboard/update-from-wd/',
               //'onSubmit' => 'actionOnSubmit()',
               'method' => 'post'
               )
           ) }}

          @php
          echo Form::label('client', 'Client to Update/Add', ['class' => 'label']);
          echo Form::text('client', null, ['required'=>'false', 'placeholder'=>'Client ID', 'id' => 'client']);
          echo Form::submit('UPDATE/ADD', ['class' => 'btn btn-primary']);
          @endphp
          {{ Form::close() }}
@endsection
