@php
// https://stackoverflow.com/questions/15389833/laravel-redirect-back-to-original-destination-after-login
Session::put('url.intended', URL::full());
$session_id = \Session::getId();

$ver = Config::get('constants.value.VER');
$baseUrl = Config::get('constants.value.baseUrl');
$imgUrl = Config::get('constants.value.imgUrl');

// master.blade.php
$type = isset($itemArr[0]['type_ii']) == 1 ? 'Type II' : 'Type I';
$countryLI = strtolower($itemArr[0]['country_of_origin1']) == 'usa' ? '<li><span class="specTitle">Origin:</span> <span class="specVal">USA</span></li>' : '';


$mailto = 'mailto:name@my-email.com?subject=InnovationsUSA&body=';
$mailto .= rawurlencode('The Innovations Design Studio collaborates with artisans around the world to develop unique wallcoverings, textiles, and faux leathers');
$mailto .='%0D%0A'; // break line

$pageTitle = '';
if(strpos($itemArr[0]['displayName'], 'Yesterdays') !== false) {
  $itemArr[0]['displayName'] = str_replace('Yesterdays News', 'Yesterday\'s News', 'Yesterday\'s News');
}
if($pageType=='item'){
  $dir1 = 'product';
  if( $mainImage != "" && $mainImage != 'NULL')
    $mainImgUrl = env('APP_URL') . '/storage/'.$mainImage. '?v=' . $ver;
  else
    $mainImgUrl = $imgUrl . '/storage/product/900x900/' . $mainImg . '.jpg?v=' . $ver;
  $urlName = str_replace(' ', '-', $itemArr[0]['displayName']);
  $thisUrl = $baseUrl . '/item/' . strtolower($urlName);

  $mailto .= rawurlencode($thisUrl);

  $pageTitle = '<a>' . $itemArr[0]['displayName'] . '</a>';
}
else{
  // $pageType=='sku'
  //$mainImgUrl = $imgUrl . '/storage/sku/900x900/' . $mainImg . '.jpg?v=' . $ver;
  $thisUrl = $baseUrl . '/item/' . strtolower($itemId) . '/' . strtolower($mainImg);

  $mailto .= rawurlencode($thisUrl);

    $pageTitle = '<a href="/item/'.$itemId.'">'.$itemArr[0]['displayName'] . ' ' . strtoupper($mainImg) .'</a>'; // . ' - ' . $sku;
}

if(strtolower($itemArr[0]['repeat'])=='non-match'){
  $itemArr[0]['repeat'] = 'Non-Match';
}else{
  $itemArr[0]['repeat'] = ucwords(strtolower($itemArr[0]['repeat']));
}

$tech_type = '';
if($itemArr[0]['tech_type_i']==1){
  $tech_type = 'Type I';
}
if($itemArr[0]['tech_type_ii']==1){
  $tech_type = 'Type II';
}

function makeTitle($title){
    $str = ucwords($title);
    $exclude = 'and,nor,but,or';
    $excluded = explode(",",$exclude);
    foreach($excluded as $noCap){$str = str_replace(ucwords($noCap),strtolower($noCap),$str);}
    return ucfirst($str);
}

function ucfirst_some($match) {
   $exclude = array('and','of','the');
   if ( in_array(strtolower($match[0]),$exclude) ) return $match[0];
   return ucfirst($match[0]);
}

$v1 = ucwords(strtolower($itemArr[0]['specData']));
$v1 = preg_replace_callback("/[a-zA-Z]+/",'ucfirst_some',$v1);
$v1 = str_replace("W/", "w/", $v1);
$backingText = "";
if(!$isFauxLeather || $itemArr[0]['backingData'] != "") {
  $backing = mb_convert_case(mb_strtolower($itemArr[0]['backingData']), MB_CASE_TITLE, "UTF-8");
  $backingText = ' <li>
    <span class="specTitle">Backing:</span>
    <span class="specVal">' . $backing . '</span>
  </li>';
}

$specData = '<ul class="skuSpec">
  <li class="font-weight-bold">SPECIFICATIONS:</li>
  <li>
    <span class="specTitle">Composition:</span>
    <span class="specVal">' . $v1 . '</span>
  </li>'.$backingText.'
  <li>
    <span class="specTitle">Total Weight:</span>
    <span class="specVal">' . $itemArr[0]['weight'] . ' oz/lineal yd
      <span class="metric">(' . $itemArr[0]['grams_sq_m'] . ' gsm)</span>
    </span>
  </li>
  <li>
    <span class="specTitle">Width:</span> <span class="specVal" >
    <span id="itemwidth">' . $itemArr[0]['width'] . '</span>
      <span class="metric">(' . $itemArr[0]['width_cm'] . ' cm)</span>
    </span>
  </li>
  <li>
    <span class="specTitle">Repeat:</span>
    <span class="specVal">' . $itemArr[0]['repeat']. '</span></li>
  <li>
    <span class="specTitle">Full Roll:</span>
    <span class="specVal">'.$itemArr[0]['bolt_size'].' yards</span>
  </li>';

  if($itemArr[0]['flame_astm_e84_class_a'] ==1 || $itemArr[0]['flame_euroclass_b']==1){
    $doubleH = '';

    if($itemArr[0]['flame_astm_e84_class_a'] ==1 &&
      $itemArr[0]['flame_euroclass_b']==1)
      $doubleH = 'double';

    $specData .= '<li class="'.$doubleH.'">
    <span class="specTitle">Fire Rating:</span>
    <span class="specVal">';

    if($itemArr[0]['flame_astm_e84_class_a'] ==1)
      $specData .= 'ASTM E84 – Class A';

    if($itemArr[0]['flame_euroclass_b'] ==1)
      $specData .=  '<br/>' . 'EN 13501 - Euroclass B';

    $specData .= '</span>
    </li>';
  }

  if($tech_type!=''){
    $specData .= '<li>
      <span class="specTitle type">Type:</span>
      <span class="specVal"> ' . $tech_type . '</span>
    </li>';
  }
//$specData .=  $countryLI;
$specData .= '</ul>';


if(strtolower($itemArr[0]['product_type'])=='faux leather'){
  $v1 = ucwords(strtolower($itemArr[0]['specData']));
  $v1 = str_replace("Pvc", "PVC", $v1);

  $specData = '<ul class="skuSpec">
    <li class="font-weight-bold">SPECIFICATIONS:</li>
    <li>
      <span class="specTitle">Composition:</span>
      <span class="specVal">' . $v1 . '</span>
    </li>
    '.$backingText.'
    <li>
      <span class="specTitle">Total Weight:</span>
      <span class="specVal">' . $itemArr[0]['weight'] . ' oz/lineal yd
        <span class="metric">(' . $itemArr[0]['grams_sq_m'] . ' gsm)</span>
      </span>
    </li>
    <li>
      <span class="specTitle">Width:</span> <span class="specVal" >
      <span id="itemwidth">' . $itemArr[0]['width'] . '</span>
        <span class="metric">(' . $itemArr[0]['width_cm'] . ' cm)</span>
      </span>
    </li>';

  if($itemArr[0]['repeat']!=''){
    $specData .= '<li>
      <span class="specTitle">Repeat:</span>
      <span class="specVal">' . $itemArr[0]['repeat']. '</span></li>
    <li>';
  }

  $specData .= '<li><span class="specTitle">Full Roll:</span>
      <span class="specVal">'.$itemArr[0]['bolt_size'].' yards</span>
    </li>';

    if( $itemArr[0]['flame_cal_117_pass']==1 ||
        $itemArr[0]['flame_nfpa_260_class_i']==1 ||
        $itemArr[0]['flame_ufac_class_i']==1 ){

      $count = 0;
      $fireTxt = '';
      $classH = '';

      if($itemArr[0]['flame_cal_117_pass']==1){
        $fireTxt .= '<br/><span class="specVal">Cal 117 – Pass</span>';
        $count++;
      }

      if($itemArr[0]['flame_nfpa_260_class_i']==1){
        $fireTxt .=  '<br/><span class="specVal">NFPA 260 – Class 1</span>';
        $count++;
      }

      if($itemArr[0]['flame_ufac_class_i']==1){
        $fireTxt .=  '<br/><span class="specVal">UFAC – Class I</span>';
        $count++;
      }

      $fireTxt = preg_replace('/^(?:<br\s*\/?>\s*)+/', '', $fireTxt);

      if($count==2)
        $classH = 'double';
      if($count==3)
        $classH = 'triple';

      $specData .= '<li class="'.$classH.'">
      <span class="specTitle">Fire Rating:</span>';

      $specData .= $fireTxt;

      $specData .= '</li>';
    }

    if( $itemArr[0]['tech_doublerubs_wyzenbeek']!='' ){
      $specData .= '<li>
        <span class="specTitle type">Testing:</span>
        <span class="specVal"> ' . number_format($itemArr[0]['tech_doublerubs_wyzenbeek']) . ' Wyzenbeek</span>
      </li>';
    }

  //$specData .=  $countryLI;
  $specData .= '</ul>';
}

if(strtolower($itemArr[0]['product_type'])=='sheers/drapery'){

  $v1 = ucwords(strtolower($itemArr[0]['specData']));
  $v1 = str_replace("Trevira Cs", "Trevira CS", $v1);
  $v1 = str_replace("Fr Polyester", "FR Polyester", $v1);

  $specData = '<ul class="skuSpec">
    <li class="font-weight-bold">SPECIFICATIONS:</li>
    <li>
      <span class="specTitle">Composition:</span>
      <span class="specVal">' . $v1 . '</span>
    </li>
    <li>
      <span class="specTitle">Total Weight:</span>
      <span class="specVal">' . $itemArr[0]['weight'] . ' oz/lineal yd
        <span class="metric">(' . $itemArr[0]['grams_sq_m'] . ' gsm)</span>
      </span>
    </li>
    <li>
      <span class="specTitle">Width:</span> <span class="specVal" >
      <span id="itemwidth">' . $itemArr[0]['width'] . '</span>
        <span class="metric">(' . $itemArr[0]['width_cm'] . ' cm)</span>
      </span>
    </li>';

  if($itemArr[0]['repeat']!=''){
    $specData .= '<li>
      <span class="specTitle">Repeat:</span>
      <span class="specVal">' . $itemArr[0]['repeat']. '</span></li>
    <li>';
  }

    /*$specData .= '<li><span class="specTitle">Full Roll:</span>
      <span class="specVal">'.$itemArr[0]['bolt_size'].' yards</span>
    </li>';*/

    if( isset($itemArr[0]['flame_nfpa_701_pass']) ){
      if( $itemArr[0]['flame_nfpa_701_pass']==1 ){

          $count = 0;
          $fireTxt = '';
          $classH = '';

          if($itemArr[0]['flame_nfpa_701_pass']==1){
            $fireTxt .= '<br/><span class="specVal"> NFPA 701 - Pass</span>';
            $count++;
          }

          $fireTxt = preg_replace('/^(?:<br\s*\/?>\s*)+/', '', $fireTxt);

          /*if($count==2)
            $classH = 'double';
          if($count==3)
            $classH = 'triple';*/

          $specData .= '<li class="'.$classH.'">
          <span class="specTitle">Fire Rating:</span>';

          $specData .= $fireTxt;

          $specData .= '</li>';
      }
    }

    /*if( $itemArr[0]['tech_doublerubs_wyzenbeek']!='' ){
      $specData .= '<li>
        <span class="specTitle type">Testing:</span>
        <span class="specVal"> ' . number_format($itemArr[0]['tech_doublerubs_wyzenbeek']) . ' Wyzenbeek</span>
      </li>';
    }*/

  //$specData .=  $countryLI;
  $specData .= '</ul>';
}

$skuSpecIcons = '<li><div class="icons">';
if( $itemArr[0]['env_phthalate_free_vinyl'] == 1 ){
  $skuSpecIcons .= '<div class="icon png"><img src="../../images/icons/Phthalate-Free.png" width="70"/><span>Phthalate Free</span></div>';
  //$skuSpecIcons .= '<div class="icon">' . file_get_contents('images/icons/Phthalate-Free.svg') . '<span>Phthalate Free</span></div>';
}
if( strpos( strtolower($itemArr[0]['tech_seaming']), 'seamless' ) !== false){
  $skuSpecIcons .= '<div class="icon">' . file_get_contents('images/icons/Seamless.svg') . '<span>Seamless</span></div>';
}
if( strpos( strtolower($itemArr[0]['tech_seaming']), 'minimal' ) !== false){
  $skuSpecIcons .= '<div class="icon">' . file_get_contents('images/icons/Minimal-Seams.svg') . '<span>Minimal Seams</span></div>';
}
if( strpos( strtolower($itemArr[0]['tech_seaming']), 'noticeable' ) !== false){
  $skuSpecIcons .= '<div class="icon">' . file_get_contents('images/icons/Noticeable-Seams.svg') . '<span>Noticeable Seams</span></div>';
}
if( strpos( strtolower($itemArr[0]['product_type']), 'natural' ) !== false){
  $skuSpecIcons .= '<div class="icon">' . file_get_contents('images/icons/Natural.svg') . '<span>Natural Woven</span></div>';
}
if( strpos( strtolower($itemArr[0]['product_type']), 'textile' ) !== false){
  $skuSpecIcons .= '<div class="icon">' . file_get_contents('images/icons/Textile.svg') . '<span>Textile Wallcovering</span></div>';
}
if( strpos( strtolower($itemArr[0]['product_type']), 'vinyl' ) !== false){
  $skuSpecIcons .= '<div class="icon png"><img src="../../images/icons/Vinyl.png" width="70"/><span>Vinyl</span></div>';
  //$skuSpecIcons .= '<div class="icon">' . file_get_contents('images/icons/Vinyl.svg') . '<span>Vinyl</span></div>';
}
if( strpos( strtolower($itemArr[0]['product_type']), 'inspired' ) !== false){
  $skuSpecIcons .= '<div class="icon">' . file_get_contents('images/icons/Inspired-Material.svg') . '<span>Inspired Material</span></div>';
}

if( strpos( strtolower($itemArr[0]['usage']), 'low' ) !== false){
  $skuSpecIcons .= '<div class="icon">' . file_get_contents('images/icons/Low-Traffic.svg') . '<span>Low Traffic</span></div>';
}
if( strpos( strtolower($itemArr[0]['usage']), 'medium' ) !== false){
  $skuSpecIcons .= '<div class="icon">' . file_get_contents('images/icons/Medium-Traffic.svg') . '<span>Medium Traffic</span></div>';
}
if( strpos( strtolower($itemArr[0]['usage']), 'high' ) !== false){
  $skuSpecIcons .= '<div class="icon">' . file_get_contents('images/icons/High-Traffic.svg') . '<span>High Traffic</span></div>';
}

if( $itemArr[0]['env_fsc_certified_paper'] == 1 ||
    $itemArr[0]['env_rapidly_renewable'] == 1 ||
    $itemArr[0]['env_recycled_backing'] == 1 ||
    $itemArr[0]['env_recycled_content_by_weight'] == 1 ||
    $itemArr[0]['env_natural_nonsynthetic'] == 1){
  $skuSpecIcons .= '<div class="icon">' . file_get_contents('images/icons/Eco-Friendly.svg') . '<span>Eco Friendly</span></div>';
}
if( $itemArr[0]['tech_type_ii'] == 1 ){
  $skuSpecIcons .= '<div class="icon">' . file_get_contents('images/icons/Type-II.svg') . '<span>Type II</span></div>';
}
// TODO: make this more dynamic.  add field for "award"
if(Request::segment(2) == 'strata' ){
  $skuSpecIcons .= '<div class="icon">' . file_get_contents('images/icons/innovations_bdny-winner.svg') . '<span>Winner</span></div>';
}
if(Request::segment(2) == 'harlequin' ){
  $skuSpecIcons .= '<div class="icon">' . file_get_contents('images/icons/Innovations_Best-of-Year_2021.svg') . '<span>Finalist</span></div>';
}

$skuSpecIcons .= '</div></li>';

// sheers, faux leather
if($skuSpecIcons == '<li><div class="icons"></div></li>'){
  if($itemArr[0]['product_type'] == "Faux Leather") {
    $skuSpecIcons = '<li><div class="icons"><div class="icon">' . file_get_contents('images/icons/upholstery.svg') . '<span>Upholstery</span></div>';
    if(isset($isWallcovering) && $isWallcovering) {
      $skuSpecIcons .= '<div class="icon">' . file_get_contents('images/icons/faux-leather-wallcovering.svg') . '<span>Wallcovering</span></div></div></li>';
    } else {
      $skuSpecIcons .= '</div></li>';
    }
  } else {
    $skuSpecIcons = '';
  }
}

$session_id = \Session::getId();
\Cart::session($session_id);
$cartContent = Cart::session($session_id)->getContent();

@endphp

@extends('master')
@if($pageType == "sku")
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
    'action' => 'customerservice',
    'callback_then' => 'callbackThen',
    'callback_catch' => 'callbackCatch'
]) !!}
@endpush
@endif
@section('meta_tags')
{!! SEOMeta::generate() !!}
{!! OpenGraph::generate() !!}
{!! Twitter::generate() !!}
{!! JsonLd::generate() !!}
@endsection
@section('main_content')
<script>
  const sess = "{{ $session_id }}";
</script>

{{-- <div id="itmHeroImg" class="container-fluid">
  <div class="row">
    <img src="//innovationsusa.com/storage/product/Innovations_{{ $itemArr[0]['displayName'] }}.jpg" alt="{{ $itemArr[0]['displayName'] }} {{ $itemArr[0]['color'] }} {{ $itemArr[0]['product_type'] }} wallcovering">
  </div>
</div> --}}
<div id="itemcontainer" class="container-fluid">
	<div class="row">

	  <div class="itmSubmenuContainer col-12 col-lg-6">
			<div id="detailSpecDiv" class="" style="">

        <div class="imgContainer"> <!-- {{$imgUrl}}/storage/sku/900x900 -->
          <img src="{{$mainImgUrl}}" alt="{{ $itemArr[0]['displayName'] }} {{ $itemArr[0]['color'] }} {{ $itemArr[0]['product_type'] }} wallcovering">
        </div>

        <div class="slider-nav-pdp desktop hide">
          <div class="carousel-wrap">
            <div class="owl-carousel owl-theme">
            @foreach($itemGallery as $item)
            @php
              if($item->img_link) {
                $size='small';
                $filename = preg_replace('/(\.[^.]*)$/', "-$size$1", $item->img_link);
                $thumb_url = '/storage/'.$filename;
              } else {
                $thumb_url = $imgUrl.'/storage/gallery/220x220/' . $item->galleryimg . '?v='. $ver;
              }
            @endphp
              <div class="item" data-toggle="modal" data-target="#modal-pdp-gallery-container"><img data-pin-no-hover="true" src="{{$thumb_url}}" alt="{{ $itemArr[0]['displayName'] }}"></div>

            @endforeach
            </div>
          </div>
          <div class="share">
            Share via <a href="{{$mailto}}" class="email">email</a> / <a href="#" class="pinterest" data-url="{{$thisUrl}}"><img class = "pinterest-icon" alt="pinterest icon" src="{{asset('images/pinterest.svg')}}"></a> <div id='download-image-2' data-downimage="{{$mainImgUrl}}"></div>
          </div>
        </div>

			</div><!-- detailSpecDiv -->
		</div><!-- itmSubmenuContainer -->

    @php
      //$coverClass='';
      //if(count($itemArr)>10) $coverClass = 'cover';
      $show  = '';
      $expanded = 'false';

    @endphp

		<div class="skuThumbDiv col-12 col-lg-6 hide">
			<div class="itm thumbnailContainer {{--$coverClass--}}">
        <h1 class="skuTitle">{!! $pageTitle !!}</h1>
        <div class="desktop">
  				<ul class="skuUL row">
  					@foreach($itemArr as $key => $item)
              @php
                $active = '';
                if($mainImg==strtoupper($item['skuNum'])){
                  $active = 'active';

                  if($key > 9){
                    $show = 'show';
                    $expanded = 'true';
                  }
                }

                // check cart items
                 $cartIO = '';
                foreach($cartContent as $cart) {
                  if($item['skuNum'] == $cart->attributes->itemnum){
                    $cartIO = 'cart1';
                  }
                }
                $thumb_src = $item['thumb_img'];

              @endphp

              @if($key<=9)
    					<li data-sku="" class="col-sm-2 {{$active}} {{$cartIO}}">
    						<div class="inner">
    							<a class="skuA fl" href="/item/{{ $itemArr[0]['urlName'] }}/{{ strtolower($item['skuNum']) }}" data-featherlight="#fl0">
    						    <img src="{{$thumb_src}}" data-pin-no-hover="true" class="img-fluid" alt="thumbnail">
                  @php $item['color'] = strtoupper($item['color'])
                  @endphp
                  @if(strpos($item['color'], ' ') === false)
    							       <p class="skuBottomTxt"><span class="itemNum">{{ strtoupper($item['skuNum']) }}</span> <span class="skuName">{{ strtoupper($item['color']) }}</span> <span class="trigger"></span></p>
                  @else
                         <div class="itemNum">{{ strtoupper($item['skuNum']) }}</div><div class="skuName" style="margin-bottom:20px;">{{ strtoupper($item['color']) }}</div> <span class="trigger"></span>

                  @endif
                  <span class="red-dot"></span>
    							</a>
    						</div>
    					</li>
              @endif
  					@endforeach
  					<!-- <div class="spinner hide"><i class="fa fa-spinner fa-spin" aria-hidden="true"></i></div> -->
  				</ul>

          @if(count($itemArr)>10)
            <ul class="skuUL row container collapsed collapse {{$show}}" id="collapse-filter-pdp">
              @foreach($itemArr as $key => $item)

              @if($key > 9)
                @php
                  $active = '';
                  if($mainImg==strtoupper( $item['skuNum']) ){
                    $active = 'active';
                  }

                  $cartIO = '';
                  foreach($cartContent as $cart) {
                    if($item['skuNum'] == $cart->attributes->itemnum){
                      $cartIO = 'cart1';
                    }
                  }
                @endphp
              <li data-sku="" class="col-sm-2 {{$active}} {{$cartIO}}">
                <div class="inner">
                  <a class="skuA fl" href="/item/{{ $itemArr[0]['urlName'] }}/{{ strtolower($item['skuNum']) }}" data-featherlight="#fl0">
                    <img src="{{$item['thumb_img']}}.jpg" class="img-fluid" alt="SKU {{ strtoupper($item['skuNum']) }} thumbnail">
                  <p class="skuBottomTxt"><span class="itemNum">{{ strtoupper($item['skuNum']) }}</span> <span class="skuName">{{ strtoupper($item['color']) }}</span> <span class="trigger"></span></p>
                  <span class="red-dot"></span>
                  </a>
                </div>
              </li>
            @endif
            @endforeach
          </ul>
          <a class="dropdown collapsed" data-toggle="collapse" href="#collapse-filter-pdp" role="button" aria-expanded="{{$expanded}}" aria-controls="collapseFilter"></a>
          @endif
        </div>

        <div class="mobile">
          <div class="carousel-wrap">
            <div class="owl-carousel owl-theme">
            @foreach($itemArr as $item)
              @php
                $active = '';
                if($mainImg==strtoupper( $item['skuNum']) ){
                  $active = 'active1';
                }

                $cartIO = '';
                foreach($cartContent as $cart) {
                  if($item['skuNum'] == $cart->attributes->itemnum){
                    $cartIO = 'cart1';
                  }
                }
              @endphp
              <div class="mobileItem item {{$active}} {{$cartIO}}">
                  <a class="skuA fl" href="/item/{{ $itemArr[0]['urlName'] }}/{{ strtolower($item['skuNum']) }}" >
                    <img src="{{$item['thumb_img']}}.jpg" class="img-fluid" alt="Wallcovering SKU {{ strtolower($item['skuNum']) }}">
                  <p class="skuBottomTxt">
                  <span class="itemNum">{{ strtoupper($item['skuNum']) }}</span> 
                  @if(strlen($item['color']) >= 8)
                    <br/><br/>
                  @endif
                  <span class="skuName">
                    {{ $item['color'] }}
                  </span> <span class="trigger"></span></p>
                  <span class="red-dot"></span>
                  </a>
                </div>
            @endforeach
            </div>
          </div>
        </div>
			</div><!-- itm thumbnailContainer -->

      <div class="skuStatDiv ">
        {{-- <div class="more-colors">
          <button id="showMoreColorBtn" class="btn btn-primary" ></button>
        </div> --}}

         @if($pageType=='sku')

          @php
            $price = 0;
          @endphp

          @if($is_client)
            @php
              $price = $wholesaleprice;
            @endphp
          @endif

          <div id="pdp-spec" data-itemnum="{{ $sku }}" data-idPdf="{{$id_pdf}}" data-unit={{$unit}} data-itemname="{{ $itemArr[0]['displayName'] }}" data-itemwidth="{{ $itemArr[0]['width'] }}" data-itemcolor="{{ $skucolor }}" data-price="{{ $price }}" data-cut_fee="{{$cut_fee}}" data-isLoggedIn="{{\Auth::check() ? 'true':'false'}}" style="display: none;"></div>

          @php
            $cartIO = '';
            foreach($cartContent as $cart) {
              if($sku == $cart->attributes->itemnum){
                $cartIO = 'cart1';
              }
            }
          @endphp
          <div class="alert alert-danger" id="order_limit_alert" style="display:none;">Guest accounts are limited to 3 samples per order.</div>
          <div id="pdp-sample-btn" class="{{$cartIO}}"></div>
          @else
          <div id="pdp-spec" data-itemname="{{ $itemArr[0]['displayName'] }}" data-itemwidth="{{ $itemArr[0]['width'] }}" style="display: none;"></div>
        @endif

        @if($itemArr[0]['product_type']=='Faux Leather' || $itemArr[0]['product_type']=='Sheers/Drapery')
        @else
        @if($pageType=='sku')
          <div id="pdp-yardage-btn" class="{{$pageType}}" data-itemnum="{{ $sku }}" data-itemname="{{ $itemArr[0]['displayName'] }}" data-width="{{ $itemArr[0]['width'] }}"></div>
        @else
          <div id="pdp-yardage-btn" class="{{$pageType}}"  data-itemname="{{ $itemArr[0]['displayName'] }}" data-width="{{ $itemArr[0]['width'] }}"></div>
        @endif
        @endif

        @if($is_client)
          @if($pageType=='sku')
            <div class="statDiv">
              <p>Price  <span>${{ $price}} Per {{ucfirst($selling_unit)}}</span></p>
              <p>Stock<font color="#FA4616">*</font>  <span>{{$inventory}} {{ucfirst($selling_unit)}}s</span></p>
              <p>Largest Dye Lot  <span>{{ $largestDyeLot}} {{ucfirst($selling_unit)}}s</span></p>
              <p>Minimum Order  <span>{{$minOrder}} {{ucfirst($selling_unit)}}s</span></p>
              <p><label class="cutfee_title">Cut Fee </label> <span class="cutfee_text">{{$cut_fee}}<font color="#FA4616" style="vertical-align: super; font-size: small;">?</font><span class="tooltiptext1" style="margin-top:30px; left:50%; margin-left:-60px;">{{$cut_fee_tooltip}}</span></span><span class="tooltiptext1" style="margin-top:30px; left:50%; margin-left:-60px;">{{$cut_fee_tooltip}}</span></p>
              <!-- <p>Quantity  <span>2000 Yards</span></p> -->
              <p><font color="#f93d0b" size = "1.5rem">*STOCK VALUES ARE UPDATED DAILY AND MAY VARY FROM NUMBER SHOWN</font></p>
              @if($inventory <> 0 && $is_client)
                <!-- <div class="alert alert-success" style="display: none;" id="cart_success">Item has been added to your <a class="text-danger" href="/cart/shopping">cart</a>
                </div>
                <div class="alert alert-danger" style="display: none;" id="qty_alert">Your order is less than the minimum of {{$minOrder}} {{ucfirst($selling_unit)}}s
                </div>
                <input id="min_order" type="hidden" value="{{$minOrder}}">
              <p>Quantity: <input id = "qty" class="col-md-12" type="number"></p>
              <div id="pdp-purchase-btn"></div> -->
              @endif

              <!-- <p>Cut Fee <span>${{ $cut_fee }} </span></p>
              <p>Width  <span>{{ $width }} </span></p>
              <p>Bolt Size <span>{{ $bolt_size }} </span></p>
              @if(isset($inventory) && $inventory != 0)
                <p>Total Inventory <span>{{$inventory}} {{$selling_unit}}s</span></p>
                  @else
                <p>Inventory:  <span>Currently out of stock</span></p>
             @endif -->

           </div>

          @endif

        @else
          @auth
          @else
          <div id="pdp-login-btn">
            <!-- temp -->
            <a href="/login" class="btn col-md-12" style="position: relative; background-color:#E7E5DC;">LOGIN</a>

            <!-- temp -->
          </div>
          <p class="login-copy">Login for additional product information.</p>
          @endauth
        @endif


      </div>

	  </div><!-- skuThumDiv col-sm-8 -->




  <div class="desktop col-12 copy-container hide">
    <div class="col-6">
      <p class="specTitle1">{{  html_entity_decode($itemArr[0]['description']) }}</p>
      {!! $specData !!}
      <ul class="skuOption">
        <li>
          @php
            $specSheet = $itemArr[0]['displayName'];
            $specSheet = preg_replace("/[\s]/", "-", $specSheet);
            $specSheet = str_replace("'", '', $specSheet);
            $hangInstr = strtolower($specSheet);
          @endphp
          <a href="{{$imgUrl}}/storage/+docs/specSheet/{{$specSheet}}_Spec-Sheet.pdf?v={{$ver}}" target="_blank">Spec Sheet</a>

         @if($itemArr[0]['product_type']=='Faux Leather' || $itemArr[0]['product_type']=='Sheers/Drapery')
         <a href="{{$imgUrl}}/storage/+docs/careMaint/Instructions_{{$itemArr[0]['careMaint']}}.pdf?v={{$ver}}" target="_blank">Care and Maintenance</a>
         @else
          <a href="{{$imgUrl}}/storage/+docs/hanging_instructions/{{ $hangInstr }}.pdf?v={{$ver}}" target="_blank">Hanging Instructions</a>
        @endif
        </li>
        {!! $skuSpecIcons !!}
      </ul>
    </div>
  </div>

    <div class="slider-nav-pdp mobile col-12 col-lg-6">
      <div class="share">
        Share via <a href="{{$mailto}}">email</a> / <a href="#" class="pinterest" data-url="{{$thisUrl}}">pinterest</a> <div id='download-image' data-downimage="{{$mainImgUrl}}"><a href="#">Download Image</a></div>
      </div>
      @php
      // print "<pre>"; print_r($itemGallery); die();
        @endphp
      <div class="carousel-wrap">
        <div class="owl-carousel owl-theme">
        @foreach($itemGallery as $item)
        @php
            if($item->img_link) {
              $size='small';
              $filename = preg_replace('/(\.[^.]*)$/', "-$size$1", $item->img_link);
              $thumb_url = '/storage/'.$filename;
            } else {
            $thumb_url = $imgUrl.'/storage/gallery/220x220/' . $item->galleryimg . '?v='. $ver;
            }
        @endphp
          <div class="item" data-toggle="modal" data-target="#modal-pdp-gallery-container"><img src="{{$thumb_url}}" alt="thumbnail"></div>
        @endforeach
        </div>
      </div>
    </div>

    <div class="copy-container hide mobile">
      <div class="col-12">
        <p class="specTitle1">{{  html_entity_decode($itemArr[0]['description']) }}</p>
        {!! $specData !!}
        <ul class="skuOption">

          <li>
          @php
            $specSheet = $itemArr[0]['displayName'];
            $specSheet = preg_replace("/[\s]/", "-", $specSheet);

            $hangInstr = strtolower($specSheet);
          @endphp
          <a href="{{$imgUrl}}/storage/+docs/specSheet/{{$specSheet}}_Spec-Sheet.pdf?v={{$ver}}" target="_blank">Spec Sheet</a>

           @if($itemArr[0]['product_type']=='Faux Leather' || $itemArr[0]['product_type']=='Sheers/Drapery')
           <a href="{{$imgUrl}}/storage/+docs/careMaint/Instructions_{{$itemArr[0]['careMaint']}}.pdf?v={{$ver}}" target="_blank">Care and Maintenance</a>
           @else
            <a href="{{$imgUrl}}/storage/+docs/hanging_instructions/{{ $hangInstr }}.pdf?v={{$ver}}" target="_blank">Hanging Instructions</a>
          @endif

          </li>
          {!! $skuSpecIcons !!}
        </ul>
      </div>
    </div>
<div class="d-flex flex-column seenin-container">
    @if($pageType == 'item' && $similarItems)
    <div class="seenin hide">
    <h3>SIMILAR PRODUCTS</h3>
    <ul class="row">
    @foreach($similarItems as $similarItem)
    @php
    if( $similarItem['mainImage'] != "" && $similarItem['mainImage'] != 'NULL')
      $mainImgUrl = env('APP_URL') . '/storage/'.$similarItem['mainImage']. '?v=' . $ver;
    else
      $mainImgUrl = $similarItem['mainImg'];
    @endphp
    <li class="col-8 col-sm-6 col-md-3">
          <div class="inner">
            <a class="fabricName" href="{{$similarItem['urlName']}}">
            <img data-pin-no-hover="true" src="{{$mainImgUrl}}" alt="{{$similarItem['displayName']}}" width="100%">
            <p class="seenTitle">{{$similarItem['displayName']}}</p>
          </a>
        </div>
      </li>
    @endforeach
    </ul>

    </div>
    @endif

    @if($pageType == 'sku' && $similarItems)
    <div class="seenin">
    <h3>SIMILAR PRODUCTS</h3>
    <ul class="row">
    @foreach($similarItems as $similarItem)
    <li class="col-8 col-sm-6 col-md-3">
          <div class="inner">
            <a class="fabricName" href="{{$similarItem['urlName']}}">
            <img data-pin-no-hover="true" src="{{$similarItem['mainImage']}}" alt="{{$similarItem['displayName']}}" width="100%">
            <p class="seenTitle">{{$similarItem['displayName']}}</p>
          </a>
        </div>
      </li>
    @endforeach
    </ul>
    </div>
    @endif

    @if( $itemArr[0]['seenin'] !=0 || !$itemSeenIns->isEmpty())
      <div class="seenin hide">
      <h3>ALSO SEEN IN</h3>

      @php
      $seenArr = array();
      if($itemSeenIns->isEmpty()) {
        // we need these as integers
        $seenArr = array_map('intval', explode(",", $itemArr[0]['seenin']));
      } else {
        $seenArr = $itemSeenIns;
      }

      @endphp

      <ul class="row">
      @foreach($seenArr as $key => $value)
        @php

        if($value===1){
          $title = 'SP20 Collection Catalog';
          $url = '/catalogs/flora-fauna-spring-2020';
          $img_src = $baseUrl.'/storage/product/seenin/Innovationsusa_also-seen-in-' . $value . '.jpg';
        }
        else if($value===2){
          $title = 'Foundation Lookbook';
          $url = '/catalogs/the-foundation-lookbook';
          $img_src = $baseUrl.'/storage/product/seenin/Innovationsusa_also-seen-in-' . $value . '.jpg';
        }
        else if($value===3){
          $title = 'SU20 Collection Catalog';
          $url = '/catalogs/streetscape-summer-2020';
          $img_src = $baseUrl .'/storage/product/seenin/Innovationsusa_also-seen-in-' . $value . '.jpg';
        }
        else if($value===4){
          $title = 'FA20 Collection Catalog';
          $url = '/catalogs/harmony-fall-2020';
          $img_src = $baseUrl .'/storage/product/seenin/Innovationsusa_also-seen-in-' . $value . '.jpg';
        }
        else if($value===5){
          $title = 'SP21 Collection Catalog';
          $url = '/catalogs/modern-threads-spring-2021';
          $img_src = $baseUrl .'/storage/product/seenin/Innovationsusa_also-seen-in-' . $value . '.jpg';
        }
        else if($value===6){
          $title = 'SU21 Collection Catalog';
          $url = '/catalogs/elemental-summer-2021';
          $img_src = $baseUrl .'/storage/product/seenin/Innovationsusa_also-seen-in-' . $value . '.jpg';
        }
        else if($value===7){
          $title = 'FA21 Collection Catalog';
          $url = '/catalogs/elan-fall-2021';
          $img_src = $baseUrl .'/storage/product/seenin/Innovationsusa_also-seen-in-' . $value . '.jpg';
        }
        else if($value===8){
          $title = 'FA21 Video';
          $url = 'https://vimeo.com/618967695';
          $img_src = $baseUrl .'/storage/product/seenin/Innovationsusa_also-seen-in-' . $value . '.jpg';
        } else {
          $title = $value->title;
          $url = $value->url;
          $img_src = '/storage/'.$value->image;
        }
        @endphp
        <li class="col-8 col-sm-6 col-md-3">
          <div class="inner">
            <a class="fabricName" href="{{$url}}">
              <img data-pin-no-hover="true" src="{{$img_src}}" alt="{{$title}}" width="100%">
              <p class="seenTitle">{{ $title }}</p>
            </a>
          </div><!-- inner -->
        </li>
      @endforeach
      </ul>
    </div>
    @endif
  </div>

	</div><!-- row -->
</div><!-- skuDiv container-fluid --->

{{-- <div class="modal fade" id="modal-pdp-login-container" tabindex="-1" role="dialog" aria-labelledby="modal-pdp-login" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-body">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          {!! file_get_contents('images/icons/x.svg') !!}
        </button>
          <form method="POST" action="{{ route('login') }}">
              @csrf

              <div class="form-group row">
                  <label for="email" class="col-md-4 col-form-label text-md-right">{{ __('E-Mail Address') }}</label>

                  <div class="col-md-6">
                      <input id="email" type="email" class="form-control @error('email') is-invalid @enderror" name="email" value="{{ old('email') }}" required autocomplete="email" autofocus>

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
                      <input id="password" type="password" class="form-control @error('password') is-invalid @enderror" name="password" required autocomplete="current-password">

                      @error('password')
                          <span class="invalid-feedback" role="alert">
                              <strong>{{ $message }}</strong>
                          </span>
                      @enderror
                  </div>
              </div>

              <div class="form-group row">
                  <div class="col-md-6 offset-md-4">
                      <div class="form-check">
                          <input class="form-check-input" type="checkbox" name="remember" id="remember" {{ old('remember') ? 'checked' : '' }}>

                          <label class="form-check-label" for="remember">
                              {{ __('Remember Me') }}
                          </label>
                      </div>
                  </div>
              </div>

              <div class="form-group row mb-0">
                  <div class="col-md-8 offset-md-4">
                      <button type="submit" class="btn btn-primary">
                          {{ __('Login') }}
                      </button>

                      @if (Route::has('password.request'))
                          <br>
                          <a class="btn btn-link" href="{{ route('password.request') }}">
                              {{ __('Forgot Your Password?') }}
                          </a>
                      @endif


                      @if (Route::has('register'))
                         /  <a href="{{ route('register') }}">Register</a>
                      @endif

                  </div>
              </div>
          </form>
      </div>
    </div>
  </div>
</div> --}}

<div class="modal fade" id="modal-pdp-gallery-container" tabindex="-1" role="dialog" aria-labelledby="modal-pdp-gallery" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-body">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          {!! file_get_contents('images/icons/x.svg') !!}
        </button>
        <div class="carousel-wrap">
        <div class="owl-carousel owl-theme">
           @foreach($itemGallery as $item)
            @php
              $firmName ='';
              $d1 = $item->fabricName;
              if($item->txt4 != '' || $item->txt4 != null){
                $d1 .=  ' ' . $item->txt4 . ' ' . ucwords(strtolower($item->color_name));
              }
              if($item->firm_name != '' || $item->firm_name != null){
                $firmName = ' - ' . $item->firm_name;
              }
              if($item->img_link) {
                $img_src= $baseUrl. '/storage/' . $item->img_link;
              }
              if(empty($item->img_link)) {
                $img_src = $baseUrl. '/storage/gallery/900x900/' . $item->galleryimg .'?v=' . $ver;
              }
              $img_url = rawurlencode($img_src);
            @endphp
              <div class="item">
              <a data-pin-do="buttonBookmark" href="https://www.pinterest.com/pin/create/button/?url={{$img_url}}" data-pin-custom="true" data-pin-tall="true" data-pin-round="true" href="https://www.pinterest.com/pin/create/button/"><img src="{{$img_src}}" alt={{$item->fabricName}}></a>
                <!-- <img src="{{$img_src}}" alt={{$item->fabricName}}> -->
                <h5 class="description">{{$d1}}</h5>
                @if($item->firm_link !='' || $item->firm_link != NULL)
                  <a href="{{$item->firm_link}}" class="orangelink" />
                  @endif
                <h5 class="description">{{$firmName}}</h5>
                @if($item->firm_link !='' || $item->firm_link != NULL)
                  </a>
                  @endif
                <h5 class="download-image-gallery" data-downimage="{{$img_src}}"></h5>
              </div>
            @endforeach
        </div>
        </div>
      </div>
    </div>
  </div>
</div>

<div class="modal fade" id="guestModal" tabindex="-1" role="dialog" aria-labelledby="guestModalLabel" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
        <h5 class="modal-title" id="exampleModalLabel"><a class='orangelink' href='/login'>Login to your online account</a> or create a guest account below to order samples.</h5>
        <h6 class="modal-title">Guest accounts are limited to 3 samples per order.</h6>
      <div class="modal-body">
        <div class="alert alert-danger" role="alert" id="guest_alert" style="display:none;">
                  Either e-mail is invalid or password is too weak.  Please try again.
        </div>
        <div class="text-center" style="display:none;" id="guest_spinner">
          <div class="spinner-border" role="status">
            <span class="sr-only">Loading...</span>
          </div>
        </div>
        <form class="my-5">
          <div class="form-group row">
            <label for="name" class="col-sm-2 col-form-label">Name</label>
            <div class="col-sm-10">
              <input type="text" class="form-control" id="name" name="name" placeholder="Name" required>
            </div>
          </div>
          <div class="form-group row">
            <label for="password" class="col-sm-2 col-form-label">Email</label>
            <div class="col-sm-10">
              <input type="email" class="form-control" id="email" name="email" placeholder="email" required>
            </div>
          </div>
          <div class="form-group row">
            <label for="company" class="col-sm-2 col-form-label">Company</label>
            <div class="col-sm-10">
              <input type="text" class="form-control" id="company" name="company" placeholder="Company" required>
            </div>
          </div>
          <div class="form-group row">
            <label for="password" class="col-sm-2 col-form-label">Password</label>
            <div class="col-sm-10">
              <input type="password" class="form-control" id="password" name="password" placeholder="Password" required>
            </div>
          </div>
          <div class="form-group col-sm-10 float-right pl-4">
              <input type="checkbox" class="form-check-input" id="newsletter" name="newsletter" checked required>
              <label for="newsletter" class="form-check-label ml-2">Sign up for our Newsletter</label>
          </div>
          <div class="form-group col-sm-10 float-right">
            By registering for a Guest Account, you agree to our <a href="/privacy-policy">Privacy Policy</a> and <a href="/terms-conditions">Terms & Conditions.</a>
          </div>
        </form>
      </div>
      <div class="modal-footer">
        <!-- <button type="button" id="register_btn" class="btn btn-primary">Register</button> -->
        <div id="pdp-guest-btn"></div>
      </div>
    </div>
  </div>
</div>

<!-- <div class="modal fade" id="modal-pdp-yardage-container" tabindex="-1" role="dialog" aria-labelledby="modal-pdp-yardage" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-body">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          {!! file_get_contents('images/icons/x.svg') !!}
        </button>
        <h5 class="modal-title">MATERIAL CALCULATOR</h5>
        <p>Trying to get an idea of how much material you will need for your job? Enter in the dimensions of the wall you are trying to cover, or enter in the total square footage needed, and let the calculator do the rest.</p>
        <p>Tip: Measure your wall from its widest and tallest points and disregard door and window openings.</p>
        <p class="diagram"><img src="/images/ui/w-h.png" alt="material calculator diagram"></p>

        <hr />

        <div id="pdp-field-container"></div>

        <p></p>
      </div>
    </div>
  </div>
</div> -->

@endsection

<!-- Push a style dynamically from a view -->
<script async defer data-pin-hover="true" data-pin-tall="true" data-pin-round="true" src="//assets.pinterest.com/js/pinit.js"></script>

{{-- @push('styles')
  <link rel="stylesheet" type="text/css" href="//cdn.jsdelivr.net/npm/slick-carousel@1.8.1/slick/slick.css"/>
@endpush --}}
