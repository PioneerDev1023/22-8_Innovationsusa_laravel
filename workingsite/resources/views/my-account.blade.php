@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header text-center"><h4>{{ __('Update your account information') }} </h4>
                </div>

                <div class="card-body">
                    <form method="POST" action="{{ route('updateClient') }}" enctype="multipart/form-data">
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

                        <input type="hidden" name="wd_id" value="{{$client->wd_id}}">

                        <div class="form-group row">
                            <label for="company_name" class="col-md-4 col-form-label text-md-right">Company Name</label>

                            <div class="col-md-6">
                                <input id="company_name" type="text" class="form-control input" name="company_name" value="{{ old('company_name') !== null ? old('company_name') : $client->company_name}}" required autofocus>

                                @error('company_name')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>

                        <div class="form-group row">
                            <label for="city" class="col-md-4 col-form-label text-md-right">City</label>

                            <div class="col-md-6">
                                <input id="city" type="text" class="form-control input" name="city" value="{{ old('city') !== null ? old('city') : $client->city}}" required autofocus>

                                @error('city')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>

                        <div class="form-group row">
                            <label for="state" class="col-md-4 col-form-label text-md-right">State</label>

                            <div class="col-md-6">
                                <input id="state" type="text" class="form-control input" name="state" value="{{ old('state') !== null ? old('state') : $client->state}}" required autofocus>

                                @error('state')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>

                        <div class="form-group row">
                            <label for="country" class="col-md-4 col-form-label text-md-right">Country</label>

                            <div class="col-md-6">
                                <input id="country" type="text" class="form-control input" name="country" value="{{ old('country') !== null ? old('country') : $client->country}}" required autofocus>

                                @error('country')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>

                        <div class="form-group row">
                            <label for="zip_code" class="col-md-4 col-form-label text-md-right">Zip/Postal Code</label>

                            <div class="col-md-6">
                                <input id="zip_code" type="text" class="form-control input" name="zip_code" value="{{ old('zip_code') !== null ? old('zip_code') : $client->zip_code}}" required autofocus>
                                @error('zip_code')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>

                        <div class="form-group row">
                            <label for="website" class="col-md-4 col-form-label text-md-right">Website</label>

                            <div class="col-md-6">
                                <input id="website" type="text" class="form-control input" name="website" value="{{ old('website') !== null ? old('website') : $client->website}}" autofocus>
                                @error('website')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>

                        <!-- Attach any Resale Certificate(s) you may have and/or proof of business (business card, etc.): -->

                        <div class="form-group row">
                            <label for="certificate" class="col-md-4 col-form-label text-md-right">Resale Certificate(s)</label>

                            <div class="col-md-6">
                                <div class="d-flex align-items-center flex-column text-center col-md-4 py-4" id="upload_div">
                                <img alt="" src="/images/icons/upload-icon.svg" width="50px">
                                <p class="mt-2 file-size font-">choose a file</p>

                                <input id="certificate" type="file" class="form-control input" name="certificate" value="{{ old('certificate') !== null ? old('certificate') : $client->certificate}}" required autofocus>
                                <p class="mb-0 file-name">{{ old('certificate') !== null ? old('certificate') : $client->certificate}}</p>
                                </div>
                                @error('certificate')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>

                        <div class="form-group row">
                            <label for="comments" class="col-md-4 col-form-label text-md-right">Comments</label>

                            <div class="col-md-6">
                                <textarea id="comments" type="text" class="form-control input" name="comments" value="{{ old('comments') !== null ? old('comments') : $client->comments}}" autofocus></textarea>
                                @error('comments')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>



                        <div class="form-group row mb-0 float-right">
                            <div class="col-md-6 offset-md-4">
                                <button type="submit" class="btn btn-primary float-right">
                                    {{ __('Update') }}
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
<script>
    $("#upload_div").click(function() {
        $(this).find("input:file").click();
    });

    $('#certificate')
    .on('change', function(e) {
        var name = e.target.files[0].name;
        var size = e.target.files[0].size;
        $('p.file-name').text(name);
        $('p.file-size').text(Number(size/1024).toFixed(2)+"KB");

    });
</script>
@endsection
