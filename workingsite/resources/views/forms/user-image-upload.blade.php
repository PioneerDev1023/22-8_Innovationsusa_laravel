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
    'action' => 'guestupload',
    'callback_then' => 'callbackThen',
    'callback_catch' => 'callbackCatch'
]) !!}
@endpush

@section('main_content')
<div class="container">
    <h1 class="homehide">Submit Wallcovering Installation Images to Innovations</h1>
    <div class="row ugp-container m-5 col-md-8 m-auto">
        <h3>Image Submission Form</h3>
        <p>Thank you for submitting images of your project! We’re excited to feature them on our websites and on  social media. We'll be sure to tag you and credit the photographer as requested below any time the images are used. Please be sure to tag us @innovationsusa whenever you post our products so that we can find your post.</p>
        <div id="user-image-form" class="row col-md-12">
            @if(session('success'))
                    <div class="alert alert-success col-12">
                        {!! session('success') !!}
                    </div>
                @endif
                @if($errors->any())
                <div class="alert alert-warning col-12">
                    @foreach ($errors->all() as $error)
                        {{$error}}
                    @endforeach
                </div>
            @endif
        <form id="image-upload-form" class="text-left ugp-form pl-0 col-md-12 mb-5" method="POST" action="/share-install-images" encType="multipart/form-data">
        @csrf()
        <div class="mb-3">
            <label for="companyName" class="form-label">Company/Firm Name<span class="orange-color">*</span></label>
            <input type="text" name="companyName" class="form-control @error('companyName') is-invalid @enderror" id="companyName" value="{{ old('companyName')}}"/>
            @error('companyName')
                <span class="invalid-feedback" role="alert">
                    <strong>{{ $message }}</strong>
                </span>
            @enderror
        </div>
        <div class="mb-3">
            <label for="designerName" class="form-label">Designer Name and Contact</label>
            <input type="text" name="designerName" class="form-control" id="designerName" value="{{ old('designerName')}}"/>
        </div>
        <div class="mb-3">
            <label class="form-label">Designer Instagram</label>
            <input type="text" class="form-control" name="designInstaName" id="designInstaName" value="{{ old('designInstaName')}}"/>
        </div>
        <div class="mb-3">
            <label for="skus" class="form-label">SKU(s) Featured<span class="orange-color">*</span></label>
            <input type="text" name="skus" class="form-control @error('skus') is-invalid @enderror" id="skus" value="{{ old('skus')}}"/>
            @error('skus')
                <span class="invalid-feedback" role="alert">
                    <strong>{{ $message }}</strong>
                </span>
            @enderror
        </div>
        <div class="mb-3">
            <label for="projectDesc" class="form-label">Tell Us About Your Project</label>
            <textarea class="form-control" id="projectDesc" rows="3" aria-describedby="projectDescHelp" value="{{ old('projectDesc')}}"></textarea>
        </div>
        <div class="mb-3">
            <label class="form-label">Photographer Name and Contact</label>
            <input type="text" class="form-control" name="photoName" id="photoName" value="{{ old('photoName')}}"/>
        </div>
        <div class="mb-3">
            <label class="form-label">Photographer Instagram</label>
            <input type="text" class="form-control" name="photoInstaName" id="photoInstaName" value="{{ old('photoInstaName')}}"/>
        </div>
        <div class="mb-3">
            <label class="form-label">I grant Innovations in Wallcovering the right to use these images on:<span class="orange-color">*</span></label>
            <div class="form-check">
                <input class="form-check-input @error('checkbox') is-invalid @enderror" type="checkbox" value="web" id="webCheck" name="checkbox[]" {{old('checkbox') && in_array("web", old('checkbox')) ? "checked" : ""}}/>
                <label class="form-check-label" for="webCheck">
                    Web
                </label>
            </div>
            <div class="form-check">
                <input class="form-check-input @error('checkbox') is-invalid @enderror" type="checkbox" value="social" id="socialCheck" name="checkbox[]" {{old('checkbox') && in_array("social", old('checkbox')) ? "checked" : ""}}/>
                <label class="form-check-label" for="socialCheck">
                    Social Media
                </label>
            </div>
            @error('checkbox')
                <span class="text-danger">
                    <strong>{{ $message }}</strong>
                </span>
            @enderror
        </div>
        <div class="input-group mb-3">
            <label for="user-image-input col-12" class="form-label">Please upload any relevant files: Installs, before shots, in progress shots etc.<span class="orange-color">*</span></label>
            <div id="imageHelp" class="form-text col-md-12">
                <p>The maximum file size per attached file is 50 MB.</p>
                <p>Please do not reduce the image size before submitting to us.</p>
                <p>If you are having any issues please email marketing@innovationsusa.com</p>
            </div>
            <input type="file" class="d-none" accept="image/png, image/gif, image/jpeg" id="files" name="files[]" multiple value="{{ old('files')}}" onchange="changefile()">

            <div id="user-image-input"></div>
            @error('files')
                <span class="text-danger">
                    <strong>{{ $message }}</strong>
                </span>
            @enderror
        </div>
        <div class="mb-3">
            <label for="termAgree" class="form-label">Do the rights to any of these images need to be purchased?<span class="orange-color">*</span></label>

            <select class="form-control @error('termAgree') is-invalid @enderror" aria-label="termHelp" name="termAgree" id="termAgree" value="{{ old('termAgree')}}">
                <option value="">Select...</option>
                <option value="1" {{old('termAgree') == '1' ? 'selected' : ''}}>Yes</option>
                <option value="0" {{old('termAgree') == '0' ? 'selected' : ''}}>No</option>
            </select>
            @error('termAgree')
                <span class="invalid-feedback" role="alert">
                    <strong>{{ $message }}</strong>
                </span>
            @enderror
        </div>
        <!-- <button type="button" id="submit_btn" class="btn btn-secondary float-right">Next</button> -->
        <div class="mb-3">
            <label class="form-label">Name<span class="orange-color">*</span></label>
            <input type="text" class="form-control @error('userName') is-invalid @enderror" name="userName" id="userName" value="{{ old('userName')}}"/>
            @error('userName')
                <span class="invalid-feedback" role="alert">
                    <strong>{{ $message }}</strong>
                </span>
            @enderror
        </div>
        <div class="mb-3">
            <label class="form-label">Email<span class="orange-color">*</span></label>
            <input type="email" class="form-control @error('userEmail') is-invalid @enderror" name="userEmail" id="userEmail" value="{{ old('userEmail')}}"/>
            @error('userEmail')
                <span class="invalid-feedback" role="alert">
                    <strong>{{ $message }}</strong>
                </span>
            @enderror
        </div>
        <button type="submit" id="submit_btn" class="btn btn-secondary float-right"
        >Submit</button>
        <span class="orange-color">*</span>Required
      </form>
        </div>
    </div>
</div>
@endsection
