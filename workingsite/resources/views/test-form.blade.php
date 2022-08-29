<?php
$message = 'error';
?>
@extends('master')

@section('title')
 - Test Form
@endsection
@section('main_content')
<div class="container">
    <div class="row justify-content-center my-5">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header text-center"><h4>{{ __('Presentation Request') }} </h4>
                Give us a few quick details about yourself and we'll be in back in touch soon to set up an appointment.
                </div>

                <div class="card-body">
                    <form method="POST" id="presentation_request" action="/test-form" enctype="multipart/form-data">
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
                            <label for="name" class="col-md-4 col-form-label text-md-right">Full Name   </label>

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
                            <label for="company_name" class="col-md-4 col-form-label text-md-right">Company Name </label>

                            <div class="col-md-6">
                                <input id="company_name" type="text" class="form-control input" name="company_name" value="{{ old('company_name')}}" required autofocus>

                                @error('company_name')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>

                        <div class="form-group row">
                            <label for="city" class="col-md-4 col-form-label text-md-right">City </label>

                            <div class="col-md-6">
                                <input id="city" type="text" class="form-control input" name="city" value="{{ old('city')}}" required autofocus>

                                @error('city')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>

                        <div class="form-group row">
                            <label for="state" class="col-md-4 col-form-label text-md-right">State </label>

                            <div class="col-md-6">
                                <input id="state" type="text" class="form-control input" name="state" value="{{ old('state')}}" required autofocus>

                                @error('state')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>

                        <fieldset class="row mb-3">
                            <label class="col-md-4 col-form-label text-md-right">Preferred Method of Contact</label>
                            <div class="col-md-6">
                            <div class="form-check">
                                <input class="form-check-input" type="radio" name="methodOfContact" id="radio1" value="email" checked>
                                <label class="form-check-label" for="radio1">                                
                                    Email
                                </label>
                            </div>
                            <div class="form-check">
                                <input class="form-check-input" type="radio" name="methodOfContact" id="radio2" value="phone">
                                <label class="form-check-label" for="radio2">
                                    Phone Call
                                </label>
                            </div>                            
                            </div>
                        </fieldset>

                        <div class="form-group row email-group">
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
                        <div class="form-group row d-none phone-group">
                            <label for="phone" class="col-md-4 col-form-label text-md-right">{{ __('Phone number') }}</label>

                            <div class="col-md-6">
                                <input id="phone" type="text" class="form-control @error('phone') is-invalid @enderror" name="phone" value="{{ old('phone') }}" autocomplete="phone">

                                @error('phone')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>

                        <div class="form-group row mb-0">
                            <div class="col-md-6 offset-md-4">
                                <button type="submit" class="btn btn-primary">
                                    {{ __('Submit') }}
                                </button>
                                <br><br>     
                            </div>

                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
<script>
    $(document).ready(function() {
        $("#presentation_request input[name=methodOfContact]").on("change", function() {
            const value = $(this).val();
            if (value == "email") {
                $('.email-group').removeClass("d-none");
                $('.phone-group').addClass("d-none");
                $('#email').prop('required',true);
                $('#phone').removeAttr('required');
            } else {
                $('.email-group').addClass("d-none");
                $('.phone-group').removeClass("d-none");
                $('#phone').prop('required',true);
                $('#email').removeAttr('required');
            }            
        })
    });
</script>
@stop