@php

use Jenssegers\Agent\Agent;
$gdprIO = '';
if (request()->cookie('gdpr') == 'gdpr_1'){
  $gdprIO = 'accepted';
}

$agent = new Agent();

@endphp
<footer class="@if (Request::is('presentation-request') || Request::is('login') || Request::is('password/reset') || Request::is('register') || Request::is('catalogs') || Request::is('videos') || Request::is('collections') || Request::segment(1) == 'cart' || Request::segment(1) == 'social-media' || Request::segment(1) == 'product' || Request::segment(1) == 'search') @if (!$agent->isMobile()) fixed-bottom presreq @endif @endif @if(Request::segment(1) == 'product' && $agent->isMobile()) fixed-bottom infinite-bottom @endif page-footer font-small indigo hide">
  <div class="container-fluid text-left">
    <div class="row" >

      <div class="desktop co-info col-md-5">
        <ul class="list-unstyled">
          <li>
            <a href="/our-story">OUR STORY</a>
          </li>
          <li>
            <a href="mailto:info@innovationsusa.com">INFO@INNOVATIONSUSA.COM</a>
          </li>
          <li>
            <a href="#!">800.227.8053</a>
          </li>
        </ul>
      </div>

      <!-- Grid column -->

      <div class="signup col-md-7">
        <div id="foot-newsletter-container"></div>
        <div id="chatcontainer">
          <span></span>
        </div>
      </div>


      <div class="mobile co-info col-md-7">
        <ul class="list-unstyled">
          <li>
            <a href="/our-story">OUR STORY</a>
          </li>
          <li>
            <a href="mailto:info@innovationsusa.com">INFO@INNOVATIONSUSA.COM</a>
          </li>
          <li>
            <a href="#!">800.227.8053</a>
          </li>
        </ul>
      </div>

    </div>
  </div>
  <div class="footer-copyright text-left">

    <div class="desktop">
      <ul class="list-inline">
        <li class="list-inline-item">© {{ now()->year }} INNOVATIONS USA</li>
        <li class="list-inline-item">
          <a href="/privacy-policy">PRIVACY POLICY</a>
        </li>
        <li class="list-inline-item">
          <a href="/terms-conditions">TERMS & CONDITIONS</a>
        </li>
      </ul>

      <ul class="social">
        <li class="link"><a href="https://www.linkedin.com/company/2143853" target="_blank" title="Innovationsusa Linked In">{!! file_get_contents('images/icons/linkedin.svg') !!}</a></li>
        <li class="fb"><a href="https://www.facebook.com/innovationsusa" target="_blank" title="Innovationsusa Facebook">{!! file_get_contents('images/icons/facebook.svg') !!}</a></li>
        <li class="pin"><a href="https://pinterest.com/innovationsusa/boards/" target="_blank" title="Innovationsusa Pinterest">{!! file_get_contents('images/icons/pinterest.svg') !!}</a></li>
        <li class="inst"><a href="https://www.instagram.com/innovationsusa" target="_blank" title="Innovationsusa Instagram">{!! file_get_contents('images/icons/Instagram.svg') !!}</a></li>
      </ul>
    </div>

    <div class="mobile">
      <ul class="social">
        <li class="inst"><a href="https://www.instagram.com/innovationsusa" target="_blank" title="Innovationsusa Instagram">{!! file_get_contents('images/icons/Instagram.svg') !!}</a></li>
        <li class="pin"><a href="https://pinterest.com/innovationsusa/boards/" target="_blank" title="Innovationsusa Pinterest">{!! file_get_contents('images/icons/pinterest.svg') !!}</a></li>
        <li class="fb"><a href="https://www.facebook.com/innovationsusa" target="_blank" title="Innovationsusa Facebook">{!! file_get_contents('images/icons/facebook.svg') !!}</a></li>
        <li class="link"><a href="https://www.linkedin.com/company/2143853" target="_blank" title="Innovationsusa Linked In">{!! file_get_contents('images/icons/linkedin.svg') !!}</a></li>
      </ul>
      <ul class="list-inline">
        <li class="list-inline-item">© {{ now()->year }} INNOVATIONS USA</li>
        <!--<li class="list-inline-item">
          <a href="/privacy-policy">PRIVACY POLICY</a>
        </li>
        <li class="list-inline-item">
          <a href="/terms-conditions">TERMS & CONDITIONS</a>
        </li>-->
      </ul>
    </div>

  </div>

  <div id="gdpr" class="{{$gdprIO}}">
    <p>Innovationsusa.com uses cookies to recognize your browsing preferences and to analyze traffic. View our <a class='terms' href="/terms-conditions">privacy page</a> to learn more.</p>
    <div id="accept-btn"></div>
  </div>
</footer>
