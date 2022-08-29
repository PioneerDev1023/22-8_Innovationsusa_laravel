@php

$session_id = \Session::getId();
\Cart::session($session_id);
$cartTotalQuantity = Cart::session($session_id)->getTotalQuantity();
$cartIOclass='';

if($cartTotalQuantity>0)
  $cartIOclass = 'red-dot';

@endphp
<nav class="navbar navbar-expand-lg navbar-light @if (Request::is('presentation-request')) whitenav @endif">



  <a id="mobile-hamburger" class="navbar-toggler collapsed border-0" type="button" data-toggle="collapse" data-target="#navbarToggler1" ria-controls="navbarToggler1">
      <span> </span>
      <span> </span>
      <span> </span>
  </a>

  <a class="navbar-brand" href="/" style="background:url({{Config::get('constants.baseUrl')}}/storage/{{setting('site.logo')}}) no-repeat 0 0; background-size:contain;">Innovations USA</a>

  <div class="nav-dropdowns container-fluid fade hide">
    <div class="row justify-content-center">
      <div class="col-md-3 menu-left">
            <ul class="menu-1 fade">
              <li class="title">PRODUCTS</li>
              <li><a class="dropdown-item wallcovering dropdown-toggle" href="#" >Wallcovering</a>
                </li>
              <li><a class="dropdown-item faux_leather" href="/product/faux-leather">Faux Leather</a></li>
              <li><a class="dropdown-item custom_lab" href="/custom-labs">Custom Lab</a></li>
              <li><a class="dropdown-item custom_lab" href="/yardage-calculator">Yardage Calculator</a></li>
              {{-- <li><a class="dropdown-item sheers_drapery" href="/product/sheers-drapery">Sheers & Drapery</a></li> --}}
            </ul>
            <ul class="menu-3 fade">
              <li class="title">NEWS</li>
              <li><a class="dropdown-item" href="/catalogs">Product Catalogs</a></li>
              <li><a class="dropdown-item" href="/videos">Videos</a></li>
              <li><a class="dropdown-item" href="/mentions">Press Mentions</a></li>
              <li><a class="dropdown-item" href="/social-media/instagram">Instagram</a></li>
              <li><a class="dropdown-item" href="/social-media/pinterest">Pinterest</a></li>
            </ul>
            <ul class="menu-2 fade">
                <li class="title">CONTACT US</li>
                <li><a class="dropdown-item" href="/find-a-rep">Find A Rep</a></li>
                <li><a class="dropdown-item" href="/customer-service">Customer Service</a></li>
                <li><a class="dropdown-item" href="/faq">FAQs</a></li>
                <li><a class="dropdown-item" href="/showrooms">Showrooms</a></li>
                <li><a class="dropdown-item" href="/let-us-shop-for-you">Let Us Shop For You</a></li>
                <li><a class="dropdown-item" href="/presentation-request">Request a Presentation</a></li>
                <li><a class="dropdown-item" href="/share-install-images">Image Submissions</a></li>
            </ul>
      </div>
      <div class="col-md-9 menu-right">
            <!-- <div class="bg"></div> -->
            <ul class="wallcovering menu-11 fade">
              <li><a  href="/product/color"><img src="/images/ui/Menu-Products-Wallcoverings-By-Color.jpg" alt="wallcovering colors"><span class="outer"><span>By Color</span></span></a></li>
              <li class="middle"><a  href="/product/material"><img src="/images/ui/Menu-Products-Wallcoverings-By-Material.jpg" alt="wallcovering materials"><span class="outer"><span>By Material</span></span></a></li>
              <li><a  href="/product/pattern"><img src="/images/ui/Menu-Products-Wallcoverings-By-Pattern.jpg" alt="wallcovering patterns"><span class="outer"><span>By Pattern</span></span></a></li>
              <li><a  href="/product/texture"><img src="/images/ui/Menu-Products-Wallcoverings-By-Textures-Finishes.jpg" alt="wallcovering finishes"><span class="outer"><span>By Texture & Finish</span></span></a></li>
              <li class="middle"><a  href="/product/collection/summer-2022"><img src="/images/ui/Menu-Products-Wallcoverings-By-WhatsNew.jpg" alt="new wallcoverings"><span class="outer"><span>What's New</span></span></a></li>
              <li><a  href="/product/all-wallcovering"><img src="/images/ui/Menu-Products-Wallcoverings-By-View-All.jpg" alt="all wallcoverings"><span class="outer"><span>View All</span></span></a></li>
            </ul>
            <ul class="faux_leather menu-11 fade d-flex"><img src="/images/ui/Menu-Products-Faux-Leather.jpg" style="margin-bottom:0.18rem;" alt="faux leather" width="100%"></ul>
            <ul class="sheers_drapery menu-11 fade"><img src="/images/ui/Menu-Products-Sheers-and-Drapery.jpg" alt="sheers and drapery" width="100%"></ul>
            <ul class="contact menu-22 fade d-flex"><img src="/images/ui/Menu-Contact-Us.jpg" alt="contact us" width="100%" ></ul>
            <ul class="news menu-33 fade d-flex"><img src="/images/ui/Menu-News.jpg" alt="wallcovering news" width="100%"></ul>

      </div>
    </div>
  </div>

  <div class="bg"></div>
  <!--    --    --    MOBILE NAV    --    --    -->
  <div class="right-nav mobile">
    <ul class="navbar-nav">
      <li class="nav-item cart">
        <a href="/cart/sample" class="nav-link shoppingbag">
          {!! file_get_contents('images/icons/shoppingbag1.svg') !!}
           <span id='mobile-cart-count' class="{{$cartIOclass}}"></span>
        </a>
      </li>
      <li class="nav-item search">
        <div id="navsearch-btn-1-mobile" class="search-label">
          <!-- from React -->
        </div>
      </li>
    </ul>
  </div>

  <div id="search-box-mobile" class="collapsed">
    <div id="sliding-panel-outer-mobile">
      <div id="sliding-panel-inner-mobile">
        <div id="search-input-container-mobile">
          <!-- from React <input type="text" id="search-input" placeholder="Search..." /> -->
        </div>
        <div class="slide-out-btn-container">
          <a class="slide-out-search-btn" href="">
            {!! file_get_contents('images/icons/Search.svg') !!}
          </a>
          <a id="x-ico" class="x-ico" href="">
            {!! file_get_contents('images/icons/x.svg') !!}
          </a>
        </div>
      <!-- <button id="search-submit">Search</button> -->
      </div><!--#sliding-panel-inner-->
    </div><!--#sliding-panel-outer-->

    <div class='underline'></div>
    <div id="search-results-mobile"></div>
  </div><!--#search-box-->


  <div class="collapse navbar-collapse" id="navbarToggler1">
    <ul class="desktop navbar-nav mr-auto mt-2 mt-lg-0">
      <li class="nav-item dropdown">
          <a class="nav-link products" href="#">PRODUCTS<span class="underline"></span></a>
      </li>
      <li class="nav-item dropdown">
        <a class="nav-link newslink" href="#">NEWS<span class="underline"></span></a>
      </li>
      <li class="nav-item dropdown">
          <a class="nav-link contactus" href="#">CONTACT US<span class="underline"></span></a>
      </li>
    </ul>
    <ul class="mobile navbar-nav">
      <li class="nav-item dropdown">
        <a class="nav-link dropdown-toggle collapsed" data-toggle="collapse" href="#navitemDropdown11" role="button" aria-expanded="false" aria-controls="navitemDropdown11">Products</a>
        <ul id="navitemDropdown11" class="drop-down-ul collapse">
          <li class="first">
            <a class="nav-link dropdown-toggle collapsed" data-toggle="collapse" href="#navitemDropdown22" role="button" aria-expanded="false" aria-controls="navitemDropdown22">Wallcovering</a>
            <ul id="navitemDropdown22" class="drop-down-ul collapse">
              <li><a class="dropdown-item" href="/product/color">By Color</a></li>
              <li><a class="dropdown-item" href="/product/material">By Material</a></li>
              <li><a class="dropdown-item" href="/product/pattern">By Pattern</a></li>
              <li><a class="dropdown-item" href="/product/texture">By Texture & Finishes</a></li>
              <li><a class="dropdown-item" href="/product/collection/summer-2022">What's New</a></li>
              <li><a class="dropdown-item" href="/product/all-wallcovering">View All</a></li>
            </ul>
          </li>
          <li><a class="dropdown-item" href="/product/faux-leather">Faux Leather</a></li>
          <li><a class="dropdown-item" href="/custom-labs">Custom Lab</a></li>
          <li><a class="dropdown-item custom_lab" href="/yardage-calculator">Yardage Calculator</a></li>
          {{-- <li><a class="dropdown-item" href="/product/sheers-drapery">Sheers & Drapery</a></li> --}}
        </ul>
      </li>
      <li class="nav-item">
        <a class="nav-link dropdown-toggle collapsed" data-toggle="collapse" href="#navitemDropdown44" role="button" aria-expanded="false" aria-controls="navitemDropdown44">
          News</a>
          <ul id="navitemDropdown44" class="drop-down-ul collapse">
            <li><a class="dropdown-item" href="/catalogs">Product Catalogs</a></li>
            <li><a class="dropdown-item" href="/videos">Videos</a></li>
            <li><a class="dropdown-item" href="/mentions">Press Mentions</a></li>
            <li><a class="dropdown-item" href="/social-media/instagram">Instagram</a></li>
            <li><a class="dropdown-item" href="/social-media/pinterest">Pinterest</a></li>
          </ul>
      </li>
      <li class="nav-item dropdown">
        <a class="nav-link dropdown-toggle collapsed" data-toggle="collapse" href="#navitemDropdown33" role="button" aria-expanded="false" aria-controls="navitemDropdown33">
          Contact Us
        </a>
        <ul id="navitemDropdown33" class="drop-down-ul collapse">
          <li><a class="dropdown-item" href="/find-a-rep">Find A Rep</a></li>
          <li><a class="dropdown-item" href="/customer-service">Customer Service</a></li>
          <li><a class="dropdown-item" href="/faq">FAQs</a></li>
          <li><a class="dropdown-item" href="/showrooms">Showrooms</a></li>
          <li><a class="dropdown-item" href="/let-us-shop-for-you">Let Us Shop For You</a></li>
          <li><a class="dropdown-item" href="/presentation-request">Request a Presentation</a></li>
          <li><a class="dropdown-item" href="/share-install-images">Image Submissions</a></li>
        </ul>
      </li>
      <li class="nav-item">
        <a class="nav-link login" href="/login">Login To Shop</a>
      </li>
      <li class="nav-item">
        <a  class="nav-link no-style" href="mailto:info@innovationsusa.com">INFO@INNOVATIONSUSA.COM</a>
        <a class="nav-link no-style">800.227.8053</a>
      </li>
      <!-- <li class="nav-item">
        <a class="nav-link disabled" href="#">Disabled</a>
      </li> -->
    </ul>
  </div>

  <div class="right-nav desktop">
    <ul class=" navbar-nav">

      <li id="searchbar" class="collapsed nav-item">
        <div id="search-box">
            <div id="sliding-panel-outer">
              <div id="sliding-panel-inner">
                <a class="slide-out-search-btn" href="">
                  {!! file_get_contents('images/icons/Search.svg') !!}
                </a>
                <div id="search-input-container">
                  <!-- from React <input type="text" id="search-input" placeholder="Search..." /> -->
                </div>
              <!-- <button id="search-submit">Search</button> -->
              </div><!--#sliding-panel-inner-->
            </div><!--#sliding-panel-outer-->
            <div id="navsearch-btn-1" class="search-label">
              <!-- from React -->
            </div>
          <div class='underline'></div>
        </div><!--#search-box-->
        <div id="search-results"></div>
      </li><!--#searchbar-->

      <li class="nav-item cart">
        <a href="/cart/sample" class="nav-link shoppingbag">
          {!! file_get_contents('images/icons/shoppingbag1.svg') !!}
           <span id='cart-count' class="{{$cartIOclass}}"></span>
        </a>
      </li>
      @auth
      @if(Auth::user()->role->name == "Client")
      <li class="nav-item shop dropdown">
          <a class="nav-link userdata_btn" data-toggle="collapse" href="#LoginUserDropdown" role="button" aria-expanded="false" aria-controls="LoginUserDropdown">Hi, {{Auth::user()->name}}</a>
          <ul id="LoginUserDropdown" class="drop-down-ul collapse" style="position:absolute; padding-left:0px;transform: translate(-30%);">
            <li class="dropdown-item"><a class="nav-link" href="{{ url('/home') }}">My Dashboard</a></li>
            <li class="dropdown-item"><a class="nav-link" href="{{ url('logout') }}">Logout</a></li>
          </ul>
      </li>
      @elseif( Auth::user()->role->name == "employee" || Auth::user()->role->name == "admin")
      <li class="nav-item shop dropdown">
          <a class="nav-link userdata_btn" data-toggle="collapse" href="#LoginUserDropdown" role="button" aria-expanded="false" aria-controls="LoginUserDropdown">Hi, {{Auth::user()->name}}</a>
          <ul id="LoginUserDropdown" class="drop-down-ul collapse" style="position:absolute; padding-left:0px;transform: translate(-50%);">
            <li class="dropdown-item"><a class="nav-link" href="{{ url('/dashboard') }}">My Dashboard</a></li>
            <li class="dropdown-item"><a class="nav-link" href="{{ url('logout') }}">Logout</a></li>
          </ul>
      </li>
      @else
      <li class="nav-item shop dropdown">
          <a class="nav-link userdata_btn" data-toggle="collapse" href="#LoginUserDropdown" role="button" aria-expanded="false" aria-controls="LoginUserDropdown">Hi, {{Auth::user()->name}}</a>
          <ul id="LoginUserDropdown" class="drop-down-ul collapse" style="position:absolute; padding-left:0px;transform: translate(-50%);">
            <!-- <li class="dropdown-item"><a class="nav-link" href="#">My Dashboard</a></li> -->
            <li class="dropdown-item"><a class="nav-link" href="{{ url('logout') }}">Logout</a></li>
          </ul>
      </li>
      @endif

      @else
        <li class="nav-item login shop">
          <a class="nav-link" href="/login">LOGIN TO SHOP</a>
          <!-- <a class="nav-link" href="/login">LOGIN TO SHOP<span class="tooltiptext" style="top:40px; left:14px;">Coming Soon</span></a> -->

        </li>
        {{-- {{ route('voyager.login') }} --}}
      @endauth
    </ul>
  </div>

</nav>
