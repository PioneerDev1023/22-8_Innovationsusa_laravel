@php

$ver = Config::get('constants.value.VER');
$baseUrl = Config::get('constants.value.baseUrl');

$class1 = 'empty';
if(count($searchArr) > 0)
  $class1 = '';
@endphp
@extends('master')


@section('main_content')

<div id="find-a-rep-container" class="{{$class1}}">

  <div class="container-fluid custom-head">
    <img src="images/ui/Find-a-Rep.jpg" alt="find a sales representative">
    <div class="headContentContainer">
      <h1>FIND A REP</h1>
      <p>Search by Country and State below to find your local rep.</p>
    </div>
  </div>

{{-- <script>

  /*function actionOnSubmit(){

    //Get the select select list and store in a variable
    var e = document.getElementById("country");

    //Get the selected value of the select list
    var formaction = e.options[e.selectedIndex].value;

    //Update the form action

    document.findrep.action = 'find-a-rep/?' + formaction;

  }*/
</script> --}}

<div class="container">
  <div class="row">
    <div class="col-md-4 mx-auto">
      <div class="form-container">
          @php
          $formUrl = 'find-a-rep/';
          @endphp

          {{ Form::open(
            array(
                'name' => 'findrep',
                'url' => $formUrl,
                //'onSubmit' => 'actionOnSubmit()',
                'method' => 'get'
                )
            ) }}

           @php
           echo Form::label('country', 'Country', ['class' => 'label']);
           echo Form::select('country', $countryArr, null, ['required'=>'required', 'placeholder'=>'Country', 'id' => 'country', 'style' => 'width:100%']);

            $style1a = '';
            $style1b = 'width:100%;';
            if(count($searchArr) > 0){
              $country = request()->get('country');

              if(strtoupper($country) != 'USA'){
                $style1a = 'display:none';
                $style1b = 'width:100%; display:none';
              }
            }

           echo Form::label('state', 'State', ['class' => 'label', 'style' => $style1a]);
           echo Form::select('state', $statesArr, null, ['required'=>'required', 'placeholder'=>'Select state', 'id' => 'state', 'style' => $style1b]);

           echo Form::submit('SUBMIT', ['class' => 'btn btn-primary']);
           @endphp
           {{ Form::close() }}
      </div>
    </div>
  </div>
</div>

@if(count($searchArr) > 0)
<br/><br/>
<h4 class="font-weight-bold text-center">RESULTS</h4>
<hr />
  @php
  if(count($searchArr)<3){
    $colStr = 'mx-auto';
  }
  else{
    $colStr = '';
  }
  @endphp
  <div class="rep container">
    <div class="row">
      @foreach($searchArr as $val)
        <ul class='col-sm-4 {{$colStr}}'>
          @php
            $title_1 = $val->state_detail;

            if($title_1 == '')
              $title_1 = $val->state_full;
          @endphp
          <h4 class="state_detail">{{ Str::ucfirst($title_1) }}</h4>
          <li class="company">{{ Str::ucfirst($val->rep_corpname) }}</li>
          {{--
            <li>{{ Str::ucfirst(trim($val->address_1)) }}</li>

            @if($val->address_2!='')
              <li>{{ Str::ucfirst($val->address_2) }}</li>
            @endif

            @if( $val->city != '' )
              <li>
              {{ Str::ucfirst($val->city) }}
              ,  {{ Str::upper($val->state) }}
                {{ Str::ucfirst($val->zip) }}
              <p />
            @endif
          --}}
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
            <li><a href='{{$hrefFax}}'>Fax:  {{$val->fax}}</a></li>
          @endif

          @php
            $mailtoEmail = '';
            $email_1 = $val->showroom_contact_email;
            $email_2 = $val->sales_rep_outside1_email;
            $email_3 = $val->sales_rep_outside2_email;

            if($email_1 !=''){
              $mailtoEmail .= ';' . $email_1;
            }
            if($email_2 !=''){
              $mailtoEmail .= ';' . $email_2;
            }
            if($email_3 !=''){
              $mailtoEmail .= ';' . $email_3;
            }

            $mailtoEmail = substr($mailtoEmail, 1); // remove 1st char of str

            $email = strtolower($val->sales_rep_outside1);
          @endphp

          @if($val->showroom_io=='Yes')
            @php
              //$mailtoEmail = $val->showroom_contact_email;
              $email = strtolower($val->showroom_contact_email);
            @endphp
          @endif

          <li><a class="email" href="mailto:{{$mailtoEmail}}" target="_blank">Email Rep</a></li>
          @if($val->genericinfo_email!='')
            <li><a class="email" href="{{$val->genericinfo_email}}" target="_blank">Website</a></li>
          @endif
        </ul>
      @endforeach
    </div>
  </div>
@endif
</div>
@endsection
