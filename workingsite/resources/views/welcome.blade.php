@php
    $ver = Config::get('constants.value.VER');
    $baseUrl = Config::get('constants.value.baseUrl');
@endphp

@extends('master')


@section('main_content')

    <div id="welcomecontainer" class="mb-5">
        <h1 class="homehide">Beautiful Wallcovering and Wallpaper -- Environmental and Luxurious</h1>

        @include('layouts.front-carousel', ['slides' => $slides, "isMobile" => $isMobile])
    </div>

    <!-- Interstitial leave commented-out for future projects
    <div class="modal fade" tabindex="-1" id="interstitial" role="dialog">
        <div class="modal-dialog interstitial-content" role="document" aria-labelledby="interstitialModalCenterTitle"
             aria-hidden="true">
            <div class="modal-content">
                <div class="modal-body" id="interstitialContent">

                </div>
            </div>
        </div>
    </div> -->

@if (Cookie::get('Interstitial') === null || Cookie::get('userInterstitial') === null)
  <!--  <script>

          $(document).ready( function() {
              setTimeout(function(){
                  let href = window.location.href + 'newsletter-interstitial-content';
                  // display modal conent
                  $.ajax({
                    url: href,
                    beforeSend: function() {
                        $('#loader').show();
                      },
                      // return the result
                      success: function(result) {
                        $('#interstitial').modal("show");
                        $('#interstitialContent').html(result).show();
                      },
                      complete: function() {
                        $('#loader').hide();
                      },
                      error: function(jqXHR, testStatus, error) {
                    console.log(error);
                    alert("Page " + href + " cannot open. Error:" + error);
                    $('#loader').hide();
                  },
                  timeout: 1000 //8000
                })
             }, 500); //2500
          });

    </script> -->
@endif
@endsection

{{-- @push('styles')
    <link rel="stylesheet" type="text/css" href="//cdn.jsdelivr.net/npm/slick-carousel@1.8.1/slick/slick.css"/>
@endpush --}}

{{-- @push('scripts_head')
@endpush

@push('scripts')
@endpush --}}
