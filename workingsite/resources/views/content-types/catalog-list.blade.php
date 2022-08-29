@extends('master')

@section('meta_tags')
{!! SEOMeta::generate() !!}
{!! OpenGraph::generate() !!}
{!! Twitter::generate() !!}
{!! JsonLd::generate() !!}
@endsection

@section('main_content')
    <div id="catalog-list" class="projects">
        <h1 class='text-center'>COLLECTION CATALOGS AND LOOKBOOKS</h1>
      <div id="padbottom-content" class="col-md-10 mx-auto container">
        <ul class="row">
          @foreach ($catalogs as $catalog)

          @php
            $link = $catalog->slug.'/modal';
          @endphp
          <li class="col-6 col-md-4 ">
            @if ( $catalog->status == 0 )
              <div class='alert alert-warning'>UNPUBLISHED</div>
              @endif
            <div class="inner">
              <a href='#issuuModal' data-attr="{{$link}}" id="popModal" class="fabricName" data-toggle="modal" data-target="#issuuModal">
                <img src="/storage/{{$catalog->thumb_url}}" alt="{{$catalog->title}}" width="100%">
                <p class="skuWallTitle">{{$catalog->title}}</p>
              </a>
            </div> <!-- inner -->
          </li>
          @endforeach

        </ul>
      </div>
    </div>
  <!-- Modal -->
    <div class="modal fade" tabindex="-1" id="issuuModal" role="dialog">
      <div class="modal-dialog flipbook" role="document" aria-labelledby="issuuModalCenterTitle" aria-hidden="true">
          <!-- Modal content-->
          <div class="modal-content">
              <div class="modal-body" id="issuuContent">

              </div>
          </div>
      </div>
    </div>
</div>

  <script>
        // display modal

        $(document).on('click', '#popModal', function(event) {
            event.preventDefault();
            let href = window.location.href + '/' + $(this).attr('data-attr');
            console.log(href);
            $.ajax({
                url: href,
                beforeSend: function() {
                    $('#loader').show();
                },
                // return the result
                success: function(result) {
                    $('#issuuModal').modal("show");
                    $('#issuuContent').html(result).show();
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

    </script>

    @endsection
