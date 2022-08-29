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
                <div class="card-header text-center"><h4>{{ __('Representative Request') }} </h4>
                </div>

                <div class="card-body">
                    <form method="POST" id="presentation_request" action="/test-form2" enctype="multipart/form-data">
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
                            <label for="rep" class="col-md-4 col-form-label text-md-right">Rep Name </label>

                            <div class="col-md-6">
                                <input id="rep" type="text" class="form-control input" name="rep" value="{{ old('rep')}}" required autofocus>

                                @error('rep')
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