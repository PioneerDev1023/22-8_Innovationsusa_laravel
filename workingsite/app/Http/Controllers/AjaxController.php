<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use App\Ajax;

use App\Http\Utils\Namefix;
use Jenssegers\Agent\Agent;

class AjaxController extends Controller
{
    private function find_words($haystack, $needle, $context = 3) {
      // https://stackoverflow.com/questions/22762797/php-show-searching-word-with-5-surrounding-words
      $haystack = strtolower($haystack);
      $needle = strtolower($needle);

      $haystack = ' '.$haystack.' ';
      if ($i=strpos($haystack, $needle)) {
          $start=$i;
          $end=$i;
          $spaces=0;

          while ($spaces < ((int) $context/2) && $start > 0) {
              $start--;
              if (substr($haystack, $start, 1) == ' ') {
                  $spaces++;
              }
          }

          while ($spaces < ($context +1) && $end < strlen($haystack)) {
              $end++;
              if (substr($haystack,$end,1) == ' ') {
                  $spaces++;
              }
          }

          while ($spaces < ($context +1) && $start > 0) {
              $start--;
              if (substr($haystack, $start, 1) == ' ') {
                  $spaces++;
              }
          }

          return(trim(substr($haystack, $start, ($end - $start))));
      } else {
          return false;
      }
    }

    private function highlight_keywords($string, $keyword) {
      return preg_replace("/(\p{L}*?)(".preg_quote($keyword).")(\p{L}*)/ui", "$1<b>$2</b>$3", $string);
    }

    public function navList(Request $request){

      $obj = json_decode( $request->getContent() );
      $searchString = $obj->searchStr;
      //$s1 = trim($s1);

      /*if (strpos(strtolower($searchString), 'sheers ') !== false) {
        $searchString = 'Sheers/Drapery';
      }*/



      $ajax = new Ajax();
      $searchObj = $ajax->quickResult( $searchString );

      // $s111 = print_r($searchObj, true);
      // \Log::info($s111);

      $arr = array();
      foreach ($searchObj as $key => $value) {

        // $s111 = $value->item_name . ' /// '. $obj->searchStr;
        // \Log::info($s111);

        $s1 = $value->item_name;
        $s2 = '';
        $s3 = '';
        $s4 = '/item/' . $s1;

        // item_number now takes precedence over the queries above
        if(stripos($value->item_number, $obj->searchStr)!==false){
          $s2 = strtoupper($value->item_number);
          $s4 = '/item/' . $value->item_name . '/' . $value->item_number;
        }

        if (strpos(strtolower($searchString), 'cork') !== false) {
          $s2 = 'Finish: Cork/Faux Cork  ';
        }
        if (strpos(strtolower($searchString), 'metallic') !== false) {
          $s2 = 'Finish: Foiled/Metallic';
        }
        if (strpos(strtolower($searchString), 'grasscloth') !== false) {
          $s2 = 'Finish: Grasscloth/Faux Grasscloth';
        }
        if (strpos(strtolower($searchString), 'linen') !== false) {
          $s2 = 'Finish: Linen/Faux Linen';
        }
        if (strpos(strtolower($searchString), 'pleat') !== false ||
            strpos(strtolower($searchString), 'relief') !== false) {
          $s2 = 'Finish: Pleated Finish: Relief';
        }
        if (strpos(strtolower($searchString), 'silk') !== false) {
          $s2 = 'Finish: Silk/Faux Silk';
        }

        if (strpos(strtolower($searchString), 'wood') !== false ) {
          $s2 = 'Finish: Wood/Faux Wood';
        }

        if(stripos($value->item_name, $obj->searchStr)!==false){
          $match = $this->highlight_keywords( $this->find_words($value->item_name, $obj->searchStr), $obj->searchStr );
          $s1 = $match;
          $s2 = '';
          $s4 = '/item/' . $value->item_name;
        }

        if(stripos($value->color_name, $obj->searchStr)!==false){
          $match = $this->highlight_keywords( $this->find_words($value->color_name, $obj->searchStr), $obj->searchStr );
          $s2 = 'color name: ' . $match;
          $s4 = '/item/' . $value->item_name . '/' . $value->item_number;
        }
        if(stripos($value->primary_color, $obj->searchStr)!==false){
          $match = $this->highlight_keywords( $this->find_words($value->primary_color, $obj->searchStr), $obj->searchStr );
          $s2 = 'color: ' . $match;
          $s4 = '/item/' . $value->item_name . '/' . $value->item_number;
        }
        if( stripos($value->collection, $obj->searchStr)!==false) {
          $s2 = $this->highlight_keywords( $this->find_words($value->collection, $obj->searchStr), $obj->searchStr );
        }
        if( stripos($value->product_design, $obj->searchStr)!==false) {
          $s2 = $this->highlight_keywords( $this->find_words($value->product_design, $obj->searchStr), $obj->searchStr );
        }
        if(stripos($value->product_type, $obj->searchStr)!==false){
          $match = $this->highlight_keywords( $this->find_words($value->product_type, $obj->searchStr), $obj->searchStr );
          $s2 = 'type: ' . $match;
        }
        if(stripos($value->product_category, $searchString )!==false){
          $match = $this->highlight_keywords( $this->find_words($value->product_category, $searchString), $searchString );
          $s2 = 'Category: ' . $match;
        }

        if(stripos($value->item_number, $searchString )!==false){
          $match = $this->highlight_keywords( $this->find_words($value->item_number, $searchString), $searchString );
          $s2 = 'SKU: ' . $match;
        }

        if (strpos(strtolower($searchString), 'leed') !== false ||
            strpos(strtolower($searchString), 'environment') !== false) {
          $s2 = 'Leed Compliant';
        }

        if (strpos(strtolower($searchString), 'euroclass') !== false) {
          $s2 = 'Euroclass B Flame: IMO Compliant';
        }
        if (strpos(strtolower($searchString), 'type ii') !== false ||
          strpos(strtolower($searchString), 'typeii') !== false ||
          strpos(strtolower($searchString), 'type 2') !== false ||
          strpos(strtolower($searchString), 'type2') !== false ) {
          $s2 = 'Technical: Type II';
        }

        $arr[] = array(
          's1' => ucwords(strtolower($s1)),// title case,
          's2' => strtolower($s2), // case sensitive causes issue
          's4' => strtolower($s4),
        );
      }

      return json_encode($arr);
    }

    public function searchList(Request $request, $id){

      $nameFix = new Namefix();
      $ajax = new Ajax();

      // $id = $nameFix->dbName($id);
      $searchObj = $ajax->pageResult( $id );

      $productTitle = $nameFix->productTitle($id);

      $productArr = array();
      $Agent = new Agent();
      $imgUrl = config('constants.value.imgUrl');
      $ver = config('constants.value.VER');
      
      foreach ($searchObj as $key => $value) {
        $displayName = $nameFix->displayName($value->item_name) . ' - ' . $value->item_number;

        $type='item';
        // if( strpos(strtolower($id), 'euroclass') !== false ||
        //            strtolower($id)=='leed' ||
        //            strtolower($id)=='environment' ||
        //            strtolower($id)=='cork' ||
        //            strtolower($id)=='metallic' ||
        //            strtolower($id)=='grasscloth' ||
        //            strtolower($id)=='linen' ||
        //            strtolower($id)=='pleated' ||
        //            strtolower($id)=='linen' ||
        //            strtolower($id)=='silk' ||
        //            strtolower($id)=='wood' ||
        //            strtolower($id)=='wallcovering' ||
        //            strtolower($id)=='sheers/drapery'||
        //            strtolower($id)=='type ii' ||
        //            strtolower($id)=='typeii' ||
        //            strtolower($id)=='type2' ||
        //            strtolower($id)=='type 2'){
        //   $type = 'product';
        // }
        
        $jpgName = "";
        $jpgName1 = "";
        $imgUrl = config('constants.value.imgUrl');
        $ver = config('constants.value.VER');

        $Agent = new Agent();
        if ($Agent->isMobile()) {
        // // you're a mobile device
          $jpgName = $imgUrl . '/storage/sku/150x150/' . strtoupper($value->item_number) .'.jpg' . '?v=' . $ver;
          $jpgName1 = $imgUrl . '/storage/product/150x150/' .$nameFix->jpgName($value->item_name) . '.jpg?v=' . $ver;

        }
        else {
          // you're a desktop device, or something similar
          $jpgName = $imgUrl . '/storage/sku/350x350/' . strtoupper($value->item_number) .'.jpg' . '?v=' . $ver;
          $jpgName1 = $imgUrl . '/storage/product/350x350/' .$nameFix->jpgName($value->item_name) . '.jpg?v=' . $ver;

        }

        if($type=='product'){
          $productArr[]= array(
            'type' => 'product',
            'jpgName1' => $jpgName1,
            'urlName1' => $nameFix->urlName($value->item_name),
            'displayName1' => $nameFix->displayName($value->item_name),
            'itemName' => $nameFix->urlName($value->item_name),
            'mainImage' => $nameFix->thumbImageName($value->main_img, 'medium'),
            'dbName' => $nameFix->dbName($value->item_name),
          );
        }
        else{
          $productArr[]= array(
            'type' => 'item',
            'jpgName' => $jpgName, // need caps
            'urlName' => $nameFix->urlName($value->item_number),
            'displayName' => $displayName,
            'itemName' => $nameFix->urlName($value->item_name),
            'dbName' => $nameFix->dbName($value->item_number),
          );
        }
      }

      $lazyLoad = '';
      $obj = json_decode( $request->getContent() );
      if($obj)
        $lazyLoad = $obj->lazyload;

      if($lazyLoad=='true'){
        return $productArr;
      }else{
        return view('search-page', [
          'pageId' => $id,
          'productTitle'=>$productTitle,
          'productArr'=>$productArr,
        ]);
      }

    }

    public function newsletterSignup(Request $request){
      $obj = json_decode( $request->getContent() );
      $ajax = new Ajax();
      $searchObj = $ajax->insertSignup( html_entity_decode($obj->emailAddress) );

      return json_encode($searchObj);
    }

    public function downloadImage(Request $request){

      $headers = [
        'Content-Type' => 'image/jpg',
      ];

      $obj = json_decode( $request->getContent() );

      //$file = 'https://dev1.innovationsusa.com/storage/product/900x900/aerial.jpg';

      $contents = file_get_contents($obj->imageUrl);

      Storage::disk('public')->put('tmp-dld.jpg', $contents);

      $path = base_path() . '/public/storage/tmp-dld.jpg';

      /* $filename = 'temp-image.jpg';
      $tempImage = tempnam(sys_get_temp_dir(), $filename);
      copy($obj->imageUrl, $tempImage); */

      return response()->download($path);

    }

    public function gdpr(){

      $lifetime = time() + 60 * 60 * 24 * 365; // one year
      $cookie = \Cookie::make('gdpr', 'gdpr_1', $lifetime, '/');

      // forever cookie
      //$cookie = Cookie::forever('name', 'value');

      /*
        By doing this Cookie::make('first', 'first', 1); , you are creating a cookie it doesn't mean it is already set. you have to send cookie with response.
      */
      return response()->json('gdpr_1')->withCookie($cookie);
    }

    // front page modal popup
    public function newsletterInterstitialSeen() {

    //   //$lifetime =1;
    //   $lifetime = 60 * 24 * 7; // 7 days
    //   $cookie = \Cookie::make('userInterstitial', 'seen', $lifetime, '/');

    //   return response()->json('seen')->withCookie($cookie);

    return;
    }

    // public function newsletterInterstitialSubmit() {

    //   //$lifetime = 30;
    //   $lifetime = 60 * 24 * 30; // 30 days
    //   $cookie = \Cookie::make('userInterstitial', 'submit', $lifetime, '/');

    //   return response()->json('seen')->withCookie($cookie);
    // }

}
