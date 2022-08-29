<?php

$ver = Config::get('constants.value.VER');
$baseUrl = Config::get('constants.value.baseUrl');
?>

<script>
  function onSubmit(token) {
    document.getElementById("contact-form").submit();
  }
</script>

@extends('master')
@push('head')
<script type="text/javascript">
    function callbackThen(response){
        // read HTTP status
        console.log(response.status);

        // read Promise object
        response.json().then(function(data){
            console.log(data);
        });
    }
    function callbackCatch(error){
        console.error('Error:', error)
    }
</script>
{!! htmlScriptTagJsApi([
    'action' => 'customerservice',
    'callback_then' => 'callbackThen',
    'callback_catch' => 'callbackCatch'
]) !!}
@endpush

@section('main_content')

<div id="customer-service">
  <div class="container-fluid custom-head">
    <img src="images/ui/Customer-Service.jpg" alt="customer service">
    <div class="headContentContainer">
      <h1>THANK YOU FOR YOUR INTEREST IN INNOVATIONS</h1>
      <h4>Have a question? Give us a call at <a href="tel:8664980515">866.498.0515</a></h4>
        <h5>or<h5>
      <a href="mailto:info@innovationsusa.com" class="btn btn-primary">EMAIL US</a>
    </div>
  </div>
  <div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card cust-serv">
                <div class="card-header text-center" style="background:white;"><h4>{{ __('Contact Us') }} </h4>
                </div>

                <div class="card-body">
                    <form method="POST" id="contact-form" action="{{ route('customer-service') }}" enctype="multipart/form-data">
                        @csrf
                        @if(session('success'))
                            <div class="alert alert-success">
                                {!! session('success') !!}
                            </div>
                        @endif
                        @if($errors->any())
                        <div class="alert alert-warning">
                          @foreach ($errors->all() as $error)
                            {{$error}}
                          @endforeach
                        </div>
                          @endif

                        <div class="form-group row">
                            <label for="wd_id" class="col-md-4 col-form-label text-md-left">Innovations Customer ID   <span class="register_quiz" ><font color="#FA4616" >?</font><span class="tooltiptext2" ><img src="{{asset('/images/INNOVATIONS-invoice_tooltip.jpg')}}" style="width:550px;"></span></span></label>

                            <div class="col-md-6">
                                <input id="wd_id" type="text" class="form-control input" name="wd_id" value="{{ old('wd_id')}}" placeholder="{{ __('(Optional)') }}"autofocus>

                                @error('wd_id')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>

                        <div class="form-group row">
                            <label for="company_name" class="col-md-4 col-form-label text-md-left">Company Name</label>

                            <div class="col-md-6">
                                <input id="company_name" type="text" class="form-control input" name="company_name" value="{{ old('company_name')}}" autofocus required>
                                @error('company_name')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>
                        <div class="form-group row">
                            <label for="name" class="col-md-4 col-form-label text-md-left">Name</label>

                            <div class="col-md-6">
                                <input id="name" type="text" class="form-control input" name="name" value="{{ old('name')}}" required autofocus>
                                @error('name')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>
                        <div class="form-group row">
                            <label for="email" class="col-md-4 col-form-label text-md-left">{{ __('E-Mail Address') }}</label>

                            <div class="col-md-6">
                                <input id="email" type="email" class="form-control @error('email') is-invalid @enderror" name="email" value="{{ old('email') }}" required autocomplete="email">

                                @error('email')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>

                        <div class="form-group row">
                            <label for="topic" class="col-md-4 col-form-label text-md-left">{{ __('Topic') }}</label>

                            <div class="col-md-6">
                                <select id="topic" type="select" class="form-control @error('topic') is-invalid @enderror" name="topic" required>
                                  <option value="order">Customer Service and Order Inquiries</option>
                                  <option value="sales">Sales Inquiries</option>
                                  <option value="marketing">Marketing/Press Inquiries</option>
                                  <option value="general">General Inquiries</option>
                                </select>
                                @error('topic')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>

                        <div class="form-group row">
                            <label for="message" class="col-md-4 col-form-label text-md-left">{{ __('Message') }}</label>

                            <div class="col-md-6">
                                <textarea id="message" rows="4" cols="50" class="form-control @error('message') is-invalid @enderror" name="message" value="{{ old('message') }}" required></textarea>

                                @error('message')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>

                        <div class="form-group row mb-0">

                            <div class="col-md-6 offset-md-4">
                                <button type="submit" class="btn btn-primary" style="float:right;">
                                    {{ __('Send') }}
                                </button>
                            </div>

                        </div>

                    </form>
                </div>
            </div>
        </div>
       </div>
    </div>
  </div>

@stop
