<div class="slider-nav-welcome ">
  <div class="carousel-wrap">
    <div class="owl-carousel owl-theme">

      @foreach ($slides as $slide)      
        @if($slide->active == 1)
        @php
          $imageUrl = $slide->slide_image; 
          if($isMobile) {            
            $imageUrl = $slide->mobileImage($slide->slide_image);
            if($slide->mobile_image != null && $slide->mobile_image) {
              $imageUrl = $slide->mobile_image;
            }
          }
        @endphp
         <div class="item" data-toggle="modal" data-target="#modal-pdp-gallery-container">
            <img src="/storage/{{$imageUrl}}" alt="{{$slide->caption_text_top}} {{$slide->caption_text_bottom}}">
            <div class="copy" style="top:35%; left:5%;">
              <h3 class="contrast"> {{$slide->caption_text_top}}</h3>
              @if($slide->caption_text_bottom)
                <h3 class="contrast">{{$slide->caption_text_bottom}}</h3>
                @endif
              <p class=""></p>
              <a href="{{$slide->button_link}}" class="btn btn-primary solid">{{$slide->button_text}}</a>
            </div>
          </div>
        @endif
      @endforeach
        </div>
      </div>
    </div>
