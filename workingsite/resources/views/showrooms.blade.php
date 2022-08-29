<?php

$ver = Config::get('constants.value.VER');
$baseUrl = Config::get('constants.value.baseUrl');

//print_r($showroomObj[2]); die();
?>
@extends('master') 
  

@section('main_content')

<div id="showrooms-container" class="container-fluid">
  <div class="nav">
    <h1 class="text-center">SHOWROOMS</h1>
    <ul>
      <li><a href="#corporate">CORPORATE SHOWROOMS</a></li>
      <li><a href="#partner">PARTNER SHOWROOMS</a></li>
      <li><a href="#international">INTERNATIONAL SHOWROOMS</a></li>
    </ul>
  </div>

  
  @foreach($showroomObj as $key => $showroom)
    @php
      if($key==0) $id = 'corporate';
      else if($key==1) $id = 'partner';
      else if($key==2) $id = 'international';
    @endphp

    <h2 id="{{$id}}">{{strtoupper($id)}} SHOWROOMS</h2>

    <div class="row">
    @foreach($showroom as $val)
   
      @if(  ($id=='corporate' && $val->rep_corpname=='Innovations') ||
               ( ($id=='partner' || $id=='international') && strtolower($val->showroom_io)=='yes')  )

        @if( ($id=='corporate' && $val->rep_corpname=='Innovations' && trim($val->address_1)=='150 Varick Street') || 
            ( ($id=='partner' || $id=='international')  &&
              ( trim($val->address_1)=='351 Peachtree Hills Avenue' ||
                trim($val->address_1)=='8687 Melrose Avenue' ||
                trim($val->address_1)=='1025 North Stemmons Freeway' ||
                trim($val->address_1)=='222 West Merchandise Mart Plaza') 
                                                                ) )
        
                                                                
        @elseif( ($id=='partner' || $id=='international') && $val->rep_corpname=='Innovations' && trim($val->address_1)=='979 Third Avenue')
        @else

         
          <ul class='col-sm-4'>
            <h4> {{ Str::upper($val->city) }} </h4> 
            <li> {{ Str::ucfirst($val->rep_corpname) }} </li> 
            <li> {{ Str::ucfirst(trim($val->address_1)) }} </li> 

            @if($val->address_2!='')
              <li> {{ Str::ucfirst($val->address_2) }} </li> 
            @endif

            @if( $val->city != '' )
              <li> 
              {{ Str::ucfirst($val->city) }}, {{ Str::upper($val->state) }}
                {{ Str::ucfirst($val->zip) }}
              
            @endif
            
            @if( $val->phone!='' )
              @php
                $hrefPhone = 'tel:1'. str_replace("-", '', $val->phone);
              @endphp

              <li><a href='{{$hrefPhone}}'>Phone: {{$val->phone}}</a></li>
            @endif

            @if($val->fax!='')
              @php
              $hrefFax = 'tel:1' . str_replace("-", '', $val->fax);
              @endphp
              <li><a href='{{$hrefFax}}'>Fax:  {{$val->fax}} </a></li>
            @endif

            @php
              $mailtoEmail = $val->sales_rep_outside1_email;
              $email = strtolower($val->sales_rep_outside1);
            @endphp

            @if($val->showroom_io=='Yes')
              @php
                $mailtoEmail = $val->showroom_contact_email;
                $email = strtolower($val->showroom_contact_email);
              @endphp
            @endif
          
            <li><a class="email" href="mailto:{{$mailtoEmail}}" target="_blank">Email Rep</a></li> 
            <li><a class="email" href="{{$val->genericinfo_email}}" target="_blank">Website</a></li> 
          </ul> 
        @endif

      @endif

    @endforeach
    </div>

  @endforeach
</div>

@endsection
