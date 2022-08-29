@extends('master')

@section('meta_tags')
{!! SEOMeta::generate() !!}
{!! OpenGraph::generate() !!}
{!! Twitter::generate() !!}
{!! JsonLd::generate() !!}
@endsection

@section('main_content')
    <div id="video-list" class="projects">
        <h1 class='text-center'>VIDEOS</h1>
      <div id="padbottom-content" class="col-md-10 mx-auto container">
        <ul class="row">
          @foreach ($videos as $video)

          @php
            $link = $video->slug.'/modal';
          @endphp

          <li class="col-6 col-md-4 ">
            @if ( $video->status == 0 )
              <div class='alert alert-warning'>UNPUBLISHED</div>
              @endif
            <div class="inner">
              <a href='#vidModal' data-attr="{{$link}}" id="vidLink" class="fabricName" data-toggle="modal" data-target="#vidModal">
                <img src="/storage/{{$video->thumb_url}}" alt="{{$video->title}}" width="100%">
                <p class="skuWallTitle">{{$video->title}}</p>
              </a>
            </div> <!-- inner -->
          </li>
          @endforeach

        </ul>
      </div>
    </div>

  <!-- Modal -->
    <div class="modal vidMod fade" tabindex="-1" id="vidModal" role="dialog">
      <div class="modal-dialog flipbook" role="document" aria-labelledby="vidModalCenterTitle" aria-hidden="">
          <!-- Modal content-->
          <div class="modal-content">
              <div class="modal-body" id="vidContent">

              </div>
          </div>
       </div>
    </div>

  <script>
        // display modal

        $(document).on('click', '#vidLink', function(event) {
            event.preventDefault();
            let href = window.location.href + '/' + $(this).attr('data-attr');

            $.ajax({
                url: href,
                beforeSend: function() {
                    $('#loader').show();
                },
                // return the result
                success: function(result) {
                    $('#vidModal').modal("show");
                    $('#vidContent').html(result).show();
                },
                complete: function() {
                    $('#loader').hide();
                },
                error: function(jqXHR, testStatus, error) {
                    console.log(error);
                    alert("Page " + href + " cannot open. Error:" + error);
                    $('#loader').hide();
                },
                timeout: 8000
            })
        });

        $('.vidMod').on('hide.bs.modal', function(e) {
          $('#vidContent iframe').attr('src', '').attr("src", $("#vidContent iframe").attr("data-src"));
        })

    </script>

@endsection
