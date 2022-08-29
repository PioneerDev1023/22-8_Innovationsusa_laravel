<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

use App\Showroom;
use App\Http\Utils\FormHelper;

use SEOMeta;
use OpenGraph;
use JsonLd;
use Twitter;

class ShowroomController extends Controller
{
    public function getShowroom(Showroom $showroom){

      //if($id=='corporate')
        $arr1 = [['corporate'],['GA', 'IL', 'TX', 'CA', 'NY']];  

      //else if($id=='partner')
        $arr2 = [['partner'], ['FULL']];

      //else if($id=='international')
        $arr3 = [['international'], ['FULL']];
      // else{
      //   return view('showrooms', ['error']);
      //   die();
      // }

      $arrObj_1 = $showroom->getShowroomList($arr1[0], $arr1);
      $arrObj_2 = $showroom->getShowroomList($arr2[0], $arr2);
      $arrObj_3 = $showroom->getShowroomList($arr3[0], $arr3);
      $showroomObj = array_merge(array($arrObj_1), array($arrObj_2), array($arrObj_3));
      // print_r( $showroomObj[0] ); die();

      $seoKeywords = array('wallcoverings', 'interior design', 'luxury', 'find a rep', 'flipbook');

      SEOMeta::setTitle('Showrooms Locations');
      SEOMeta::setDescription("showroom innovationsusa wallcovering");
      SEOMeta::addKeyword($seoKeywords);

      OpenGraph::setDescription("showroom innovationsusa wallcovering");
      OpenGraph::setTitle('Showroom');
      OpenGraph::setUrl(url()->current());
      OpenGraph::addProperty('type', 'product');
      OpenGraph::addProperty('locale', 'en-US');

      Twitter::setTitle('Showroom');
      Twitter::setSite('@InnovationsUSA');

      JsonLd::setTitle('Showroom');
      JsonLd::setDescription("showroom innovationsusa wallcovering");
      JsonLd::setType('Flipbook');     

      return view('showrooms', [ 
        'showroomObj'=>$showroomObj, 
        //'mobile'=>$mobile, 
      ]);
    }
    public function findARep(Showroom $showroom, Request $request){

      $input = $request->all();
      //print_r($input); die();

      if(count($input)>0){
        $country = isset($input['country']) ? $input['country'] : '';
        $state = isset($input['state']) ? $input['state'] : '';
        $searchArr = $showroom->getRepSearch($country, $state);

        if(strtoupper($country)=='USA' && strtoupper($state)=='NY'){
          // arrays are obj, these func error!
          //$searchArr = array_shift($searchArr);
          //$searchArr = array_merge($searchArr[1], $searchArr[2]);
          //print_r($searchArr); die();
          $nyArr = array();
          foreach ($searchArr as $key => $value) {
            if( $value->state_detail == 'New York City' ||
                $value->state_detail == 'Long Island and Westchester'){
              $nyArr[] = $value;
            }
          }
          //print_r($nyArr); die();
          $searchArr = $nyArr;
        }
        
      }else{
        $searchArr = array();
      }
      //print_r($searchArr); die();

      $formHelp = new FormHelper();
      $countryArr = $formHelp->countryArr();
      $statesArr = $formHelp->statesArr();
      
      $seoKeywords = array('wallcoverings', 'interior design', 'luxury', 'find a rep', 'flipbook');

      SEOMeta::setTitle('Find-a-Sales-Representative');
      SEOMeta::setDescription("find a rep innovationsusa wallcovering");
      SEOMeta::addKeyword($seoKeywords);

      OpenGraph::setDescription("find a rep innovationsusa wallcovering");
      OpenGraph::setTitle('Find a rep');
      OpenGraph::setUrl(url()->current());
      OpenGraph::addProperty('type', 'product');
      OpenGraph::addProperty('locale', 'en-US');

      Twitter::setTitle('Find a rep');
      Twitter::setSite('@InnovationsUSA');

      JsonLd::setTitle('Find a rep');
      JsonLd::setDescription("find a rep innovationsusa wallcovering");
      JsonLd::setType('Flipbook');      

      return view('find-a-rep', [
        'countryArr' => $countryArr,
        'statesArr' => $statesArr,
        'searchArr' => $searchArr,
      ]);
    }

}
