@php

$ver = Config::get('constants.value.VER');
$baseUrl = Config::get('constants.value.baseUrl');

@endphp
@extends('master')


@section('main_content')

<div id="customlabs">
  <div class="container-fluid custom-head">
    <img src="images/ui/Wallpaper-Customizer.jpg" alt="Customize your wallpaper">
    <div class="headContentContainer">
      <h1 class="bg">CREATE YOUR OWN CUSTOM WALLCOVERING</h1>
      <p class="bg">Customize a wallcovering from one of our collections, use our design service to create something new, or upload your own design.</p>
      <a href="/custom-labs-start-your-project" class="btn btn-primary bg">START YOUR PROJECT</a>
    </div>
  </div>
  <div class="container">
    <div class="row">
      <div class="col-md-10 mx-auto custom-body">
        <!-- <div style="min-height:400px; background-color: #F1F1F1"></div> -->
        <p>Custom printed wallcovering is a creative, popular and affordable way to transform any space. From glamourous metallics, to elevated vinyls and sophisticated naturals, Innovations offers a variety of substrates to create the perfect custom wallcovering for your needs. Our design experts will collaborate with you to identify the optimal approach to scale, color, texture and pattern to bring your vision to life.  </p>

        <p class='how-it-works'>
          <img src="images/ui/custom-more-hands.jpg" alt="Custom wallcovering designs" align="left"/>
          <h3>HOW DOES IT WORK?</h3>
          <p>Submit your artwork, idea or project inspiration to our design team or select one of our existing products to start with. Our design team will work with you on scale, color and substrate options to make your vision a reality.</p>
        </p>

         <p>Ready to get started?  Fill out a short <a href="/custom-labs-start-your-project">project request form</a> and our team will be in touch within one business day. </p>
         <br>
         {{-- <hr> --}}
      </div>
      @php
      /*$projObj = array(
        'calico', 'rubik', 'aerial', 'denim', 'gobi'
      ); */
      @endphp
      {{-- <div class="projects">
        <h2>PAST PROJECTS</h2>
        <div id="" class="col-md-10 mx-auto container">
          <ul class="row">

            @foreach($projObj as $key => $item)
            <li class="col-6 col-md-3 ">
              <div class="inner">
                <a class="fabricName" href="#" data-toggle="modal" data-target="#modal-customlabs-container">
                  <img src="https://www.innovationsusa.com/storage/product/900x900/{{$item}}.jpg" alt="{{$item}}" width="100%">
                  <p class="skuWallTitle">{{ Str::of($item)->ucfirst() }}</p>
                </a>
              </div><!-- inner -->
            </li>
            @endforeach

          </ul>
        </div>
      </div> --}}
    </div>
  </div>
</div>


{{-- <div class="modal fade" id="modal-customlabs-container" tabindex="-1" role="dialog" aria-labelledby="modal-customlabs" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-body">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          {!! file_get_contents('images/icons/x.svg') !!}
        </button>
        <div class="carousel-wrap">
          <div class="owl-carousel owl-theme">
             @foreach($projObj as $item)
                <div class="item"><img src="https://www.innovationsusa.com/storage/product/900x900/{{$item}}.jpg" alt="{{$item}}" width="100%"></div>
              @endforeach
          </div>
        </div>

      </div><!-- modal-body -->
    </div>
  </div>
</div>  --}}
@stop
