<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Utils\FormHelper;
use SEOMeta;
use OpenGraph;
use JsonLd;
use Twitter;

class RegistrationController extends Controller
{
    function registerUser(){

      $formHelp = new FormHelper();
      $countryArr = $formHelp->countryArr();
      $statesArr = $formHelp->statesArr();

      $seoKeywords = array('wallcoverings', 'interior design', 'luxury', 'account register', 'flipbook');

      SEOMeta::setTitle('New Client Account Registration');
      SEOMeta::setDescription("account register innovationsusa wallcovering");
      SEOMeta::addKeyword($seoKeywords);

      OpenGraph::setDescription("account register innovationsusa wallcovering");
      OpenGraph::setTitle('Account Register');
      OpenGraph::setUrl(url()->current());
      OpenGraph::addProperty('type', 'product');
      OpenGraph::addProperty('locale', 'en-US');

      Twitter::setTitle('Account Register');
      Twitter::setSite('@InnovationsUSA');

      JsonLd::setTitle('Account Register');
      JsonLd::setDescription("account register innovationsusa wallcovering");
      JsonLd::setType('Flipbook');

      return view('account-registration', [
        'countryArr' => $countryArr,
        'statesArr' => $statesArr
      ]);
    }
}
