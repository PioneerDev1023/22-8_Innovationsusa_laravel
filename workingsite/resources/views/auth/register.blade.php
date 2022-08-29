<?php

$ver = Config::get('constants.value.VER');
$baseUrl = Config::get('constants.value.baseUrl');
?>

<script>
  function onSubmit(token) {
    document.getElementById("contact-form").submit();
  }
</script>

@extends('layouts.app')

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
    'action' => 'register',
    'callback_then' => 'callbackThen',
    'callback_catch' => 'callbackCatch'
]) !!}
@endpush

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header text-center"><h4>{{ __('Existing Innovations Trade Clients') }} </h4>
                                                     <h4>{{ __('Sign Up for Online Account Access') }} </h4>
                </div>

                <div class="card-body">
                    <form method="POST" action="{{ route('register') }}" enctype="multipart/form-data">
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
                            <label for="wd_id" class="col-md-4 col-form-label text-md-right">Innovations Customer ID   <span class="register_quiz" ><font color="#FA4616" >?</font><span class="tooltiptext2" ><img src="{{asset('/images/INNOVATIONS-invoice_tooltip.jpg')}}" style="width:550px;" alt="tooltip"></span></span></label>

                            <div class="col-md-6">
                                <input id="wd_id" type="text" class="form-control input" name="wd_id" value="{{ old('wd_id')}}" required autofocus>

                                @error('wd_id')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>

                        <div class="form-group row">
                            <label for="zip_code" class="col-md-4 col-form-label text-md-right">Zip/Postal Code</label>

                            <div class="col-md-6">
                                <input id="zip_code" type="text" class="form-control input" name="zip_code" value="{{ old('zip_code')}}" required autofocus>
                                @error('zip_code')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>

                        <div class="form-group row">
                            <label for="name" class="col-md-4 col-form-label text-md-right">{{ __('Full Name') }}</label>

                            <div class="col-md-6">
                                <input id="name" type="text" class="form-control @error('name') is-invalid @enderror" name="name" value="{{ old('name') }}" required autocomplete="name" autofocus>

                                @error('name')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>

                        <div class="form-group row">
                            <label for="email" class="col-md-4 col-form-label text-md-right">{{ __('E-Mail Address') }}</label>

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
                            <label for="password" class="col-md-4 col-form-label text-md-right">{{ __('Password') }}</label>

                            <div class="col-md-6">
                                <input id="password" type="password" class="form-control @error('password') is-invalid @enderror" name="password" required autocomplete="new-password">

                                @error('password')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>

                        <div class="form-group row">
                            <label for="password-confirm" class="col-md-4 col-form-label text-md-right">{{ __('Confirm Password') }}</label>

                            <div class="col-md-6">
                                <input id="password-confirm" type="password" class="form-control" name="password_confirmation" required autocomplete="new-password">
                            </div>
                        </div>

                        <div class="form-group row mb-0">
                            <div class="col-md-6 offset-md-4">
                                <button type="submit" class="btn btn-primary">
                                    {{ __('Register') }}
                                </button>
                                <br><br>
                                <p>Open a New Trade Account with Innovations  <a href='/account-registration' class='orangelink'>here.</a></p>
                            </div>

                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
