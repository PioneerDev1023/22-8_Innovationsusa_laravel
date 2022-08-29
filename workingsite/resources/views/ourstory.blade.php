@php

$ver = Config::get('constants.value.VER');
$baseUrl = Config::get('constants.value.baseUrl');
$imgUrl = Config::get('constants.value.imgUrl');
@endphp

@extends('master')


@section('main_content')

<div id="our-story">
  <h3>OUR STORY</h3>
  <div class="container-fluid custom-head">
    <img src="{{$imgUrl}}/storage/aboutus/Our-Story_header.jpg" alt="About us - NYC wallcovering designer" width="100%">
    <div class="headContentContainer"></div>
  </div>
  <div class="container">
    <div class="row">
       <div class="col-md-10 custom-body ">
          <p class="synopsis text-left">Founded by Rudy Mayer in 1975, Innovations is committed to forward-thinking design and creating wallcoverings that transform interiors. As pioneers in the industry, we introduced many firsts by experimenting with new materials and design techniques. From our inspired products to our highly regarded service, we are dedicated to elevating your shopping experience every step of the way. With everything from natural wovens to luxe textiles and elevated vinyls, Innovations’ versatile assortment explores materiality and technique without compromising durability. <span>Experience wallcovering with Innovations.</span></p>
        </div>
    </div>

    <div class="row">
      <div class="col-md-5">
          <img src="{{$baseUrl}}/storage/aboutus/Our-Story_image1.jpg" alt="history of Innovations in Wallcovering" width="100%">
      </div>
      <div class="text-bloc col-md-7">
        <h3 class="title-2">WHY CHOOSE INNOVATIONS?</h3>
        <h3>We Ship Quickly:</h3>
        <P>Submit a sample request via <a href="mailto:samples@innovationsusa.com">samples@innovationsusa.com</a> and your memos will ship within one day. We also keep over 1,000 skus in stock and ready to ship within one day.</P>

        <h3>We Shop For You:</h3>
        <p>Tell us a few details about your latest project  and our team of wallcovering experts will shop our catalog and send you a curated selection of memos right away.</p>

        <h3>We Are Committed To Quality:</h3>
        <p>Backed by our 45 years of experience, our products are constructed from the highest quality materials that have been hand selected by our design team to deliver on our promise of inspired design that lasts.</p>
      </div>
    </div>
    <div class="row">
      <div class="text-bloc col-md-7">
        <h3>We Treat You Like Family:</h3>
        <P>Owned and operated by the Mayer family since its foundation; Innovations is proud of the personal level of customer service they provide. Not surprisingly, our customer service team is rated one of the highest in the industry. Whether via email or over the phone, our team works tirelessly to help make sure that each of your projects is a success.</P>

        {{--
        <h3>We Give Back to the Community:</h3>
        <p>Our love for design is matched only by our passion for social equality and love of animals.  A diverse workplace, Innovations actively supports educational charities for at-risk youth through financial donations. A strong proponent of pet adoption, Innovations makes a significant donation to animal organizations each year in addition to volunteering at local pet-related events.</p>
         --}}

        <p style="padding-top:30px;">
          We look forward to partnering with you on your next project. <br/><br/>
          Sincerely, <br/>
          The Innovations Team
        </p>
      </div>
      <div class="col-md-5">
          <img src="{{$imgUrl}}/storage/aboutus/Our-Story_image2.jpg" alt="Our story" width="100%">
      </div>
    </div>
  </div>

  <div class="projects">
  </div>
</div>
@stop
