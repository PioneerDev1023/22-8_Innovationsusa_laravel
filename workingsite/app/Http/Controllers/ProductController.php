<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

use App\Product;
use App\PostView;
use App\ProductMaster;
use App\Http\Utils\Namefix;
use Image;

use SEOMeta;
use OpenGraph;
use JsonLd;
use Twitter;

use Jenssegers\Agent\Agent;

use Maatwebsite\Excel\Facades\Excel;
use App\Imports\ProductImport;

class ProductController extends Controller
{

    private $wcArr = array('Inspired Material','Natural Woven','Textile Wallcovering','Vinyl');
    private $filterArrColor = array('Beige','Black','Blue','Brown','Copper','Gold','Gray','Green','Multi-Color','Off-White','Orange','Pink','Purple','Red','Silver','White','Yellow');
    private $filterArrMaterial = array('Natural Woven','Textile','Vinyl','Specialty');
    private $filterArrPatterns = array('Abstract/Specialty','Animal Print','Botanical/Floral','Concrete/Plaster','Damask/Lattice/Medallion','Geometric/Linear','Large-Scale/Mural','Marbled','Stone');
    private $filterArrTexture = array('Cork/Faux Cork','Foiled/Metallic','Grasscloth/Faux Grasscloth','Linen/Faux Linen','Pleated','Relief','Silk/Faux Silk','Wood/Faux Wood');
    private $filterArrCollection = array('Summer 2022', 'Spring 2022', 'Fall 2021', 'Summer 2021', 'Spring 2021', 'Fall 2020','Summer 2020','Spring 2020','Fall 2019','Summer 2019','Spring 2019');
    private $filterArrEnvironment = array('leed 01350 cert', 'leed certified paper', 'leed 500 miles', 'leed rapidly renewable', 'leed recycled content by weight', 'leed ultralow voc vinyl','leed natural nonsynthetic');

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index($request, $postView, $product, $id = NULL){
        // https://softwareengineering.stackexchange.com/questions/176639/why-put-the-business-logic-in-the-model-what-happens-when-i-have-multiple-types

        //  domain logic should go into the model and application logic into the controller.

        $nameFix = new Namefix();

        //$productTitle = 'ALL PRODUCTS';
        $productTitle = $nameFix->productTitle($id);

        // http://localhost/innovations/_wip/product/
        //$columnArr = array('fabricName');
        $columnArr = array('product_master.item_name', 'product_list.main_img');

        // Method Chaining based on condition
        //$query = \App\Collection::query();
        //$product = new Product();
        $prodObj = $product->index($columnArr);
        //print_r($prodObj); die();

        $post = $product;
        $post->post_id = 'product';
        $post->titleslug = $id;
        $postView->createViewLog($post);

        //print_r($id); die();

        $filterObj = array();
        $type = 'product'; // important for Plp.js, line 427
        //$type = 'item';

        if ($id=='color'){
          // primary_color
          //$filterObj = $product->filterList('primary_color');
          $filterObj = $this->filterArrColor;
        }else if ($id=='material'){
          $filterObj = $this->filterArrMaterial;
        }else if ($id=='pattern'){
          $filterObj = $this->filterArrPatterns;
        }else if ($id=='texture'){
          $filterObj = $this->filterArrTexture;
        }else if ($id=='collection'){
          $filterObj = $this->filterArrCollection;
          //$type = 'product';
        }else if ($id=='all-wallcovering'){
          $filterObj = array(
            'color' => $this->filterArrColor,
            'material' =>$this->filterArrMaterial,
            'pattern' =>$this->filterArrPatterns,
            'texture' =>$this->filterArrTexture,
            'collection' =>$this->filterArrCollection,
          //  'environment' =>$this->filterArrEnvironment
          );
        }

        $productArr = array();
        foreach ($prodObj as $key => $value) {

        $main_img = "";
        $imgUrl = config('constants.value.imgUrl');
        $ver = config('constants.value.VER');

        if (isset($value->productList->main_img)) {
          $main_img = $value->productList->main_img;
        }

        $Agent = new Agent();
        if ($Agent->isMobile()) {
        // // you're a mobile device
          $jpgName1 = $imgUrl . '/storage/product/150x150/' .$nameFix->jpgName($value->item_name). '.jpg?v=' . $ver;
          $mainImage = $nameFix->thumbImageName($value->main_img, 'medium');

        }
        else {
          // you're a desktop device, or something similar
          $jpgName1 = $imgUrl . '/storage/product/350x350/' .$nameFix->jpgName($value->item_name). '.jpg?v=' . $ver;
          $mainImage = $value->main_img;
        }

            $productArr[]= array(
              'type' => $type,
              'jpgName1' => $jpgName1,
              'urlName1' => $nameFix->urlName($value->item_name),
              'displayName1' => $nameFix->displayName($value->item_name),
              'dbName' => $nameFix->dbName($value->item_name),
              'mainImage' => $mainImage,
              'mainImageThumb' => $nameFix->thumbImageName($value->main_img, 'medium')
            );
        }

        $agent = new Agent();
        $mobile = $agent->isMobile();

        $returnArr = [
            'pageId' => $id,
            'productTitle'=>$productTitle,
            'mainArr'=>$productArr,
            'filterObj'=>$filterObj,
            'mobile'=>$mobile,
        ];

        $description = "Search Innovations Wallcovering or Textile Products with ".$id;
        $seoKeywords = "$id"." Innovations in Wallcoverings";

        $lazyLoad = '';
        $obj = json_decode( $request->getContent() );

        if($obj)
          $lazyLoad = $obj->lazyload;


        if($lazyLoad=='true'){
          return $returnArr;
        }else{
          if ($id == "all-wallcovering") {
            SEOMeta::setTitle("All Products");
          } else if ($id == "faux-leather") {
            SEOMeta::setTitle("Shop Faux-Leather");
          } else {
            SEOMeta::setTitle("Shop Our Wallcoverings by ".ucfirst($id));
          }
          SEOMeta::setDescription($description);
          SEOMeta::addKeyword($seoKeywords);

          OpenGraph::setDescription($description);
          OpenGraph::setTitle($id);
          OpenGraph::setUrl(url()->current());
          OpenGraph::addProperty('type', 'product');
          OpenGraph::addProperty('locale', 'en-US');

          Twitter::setTitle($id);
          Twitter::setSite('@InnovationsUSA');

          JsonLd::setTitle($id);
          JsonLd::setDescription($description);
          JsonLd::setType('Product');
          return view('product', $returnArr);

        }

    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */

    public function show(Request $request, PostView $postView, Product $product, $id){


      $nameFix = new Namefix();

      $id = $nameFix->dbName($id);

      //echo $id; die();
      if(strtolower($id)=='sheers drapery'){
        return redirect()->to('/');
        die();
      }
      else if( $id=='all'){
        return $this->index($request, $postView, $product, $id);
        die();
      }else if( $id=='trending' ||
          $id=='color' ||
          $id=='material' ||
          $id=='pattern' ||
          $id=='texture' ||
          $id=='collection' ||
          $id=='search' ){
        return $this->index($request, $postView, $product, $id);

        die();

      }else if($id == 'all-wallcovering'){
        if( $request->color ||
            $request->material ||
            $request->pattern ||
            $request->texture ||
            $request->collection ||
            $request->environment ){
          return $this->allWallcoverings($request, $postView, $product);
        }else{
          return $this->index($request, $postView, $product, $id);
        }

        die();

      }

      $columnArr = array('item_name', 'main_img'); // , 'product_list.description'

      //print_r($id); die();
      $productTitle = $nameFix->productTitle($id);
      //print_r($productTitle); die();

      $productDescription = '';
      if($productTitle=='FAUX LEATHER'){
        $productDescription = 'Our vinyl and PVC-free faux leathers have a soft hand and are available in a variety of textures from traditional to trendy. Although designed for upholstery, many of our faux leathers can be used as wallcovering as well. Many of these durable materials are even bleach solution cleanable.';
      }

      $productObj_ = $product->getProductList($id, $columnArr, $this->wcArr);


      $post = $product;
      $post->post_id = 'product';
      $post->titleslug = $id;
      $postView->createViewLog($post);

      //get image url for desktop and mobile
      $imgUrl = config('constants.value.imgUrl');
      $ver = config('constants.value.VER');

      $Agent = new Agent();

      $productArr = array();

      foreach ($productObj_ as $key => $value) {

        $jpgName1 = "";
        if ($Agent->isMobile()) {
        // // you're a mobile device
          $jpgName1 = $imgUrl . '/storage/product/150x150/' .$nameFix->jpgName($value->item_name). '.jpg?v=' . $ver;

        }
        else {
          // you're a desktop device, or something similar
          $jpgName1 = $imgUrl . '/storage/product/350x350/' .$nameFix->jpgName($value->item_name);

        }

        $productArr[]= array(
          'type' => 'item',
          'jpgName1' => $jpgName1,
          'urlName1' => $nameFix->urlName($value->item_name),
          'displayName1' => $nameFix->displayName($value->item_name),
          'dbName' => $nameFix->dbName($value->item_name),
          'mainImage' => $nameFix->thumbImageName($value->main_img, 'medium'),
        );
      }

      $agent = new Agent();
      $mobile = $agent->isMobile();


      $returnArr =  [
        'pageId' => $id,
        'productTitle'=>$productTitle,
        'mainArr'=>$productArr,
        'productDescription'=>$productDescription,
        'mobile' => $mobile,
      ];



        // for sheer/drapery, faux leather
        $lazyLoad = '';
        $obj = json_decode( $request->getContent() );

        if($obj){
          $lazyLoad = $obj->lazyload;
        }

        $description = "Search Innovations Wallcovering or Fabric Products with".$id;
        $seoKeywords = "$id"." Innovations in Wallcoverings";

        if($lazyLoad=='true'){
          return $returnArr;
        }else{

          SEOMeta::setTitle($id);
          SEOMeta::setDescription($description);
          SEOMeta::addKeyword($seoKeywords);

          OpenGraph::setDescription($description);
          OpenGraph::setTitle($id);
          OpenGraph::setUrl(url()->current());
          OpenGraph::addProperty('type', 'product');
          OpenGraph::addProperty('locale', 'en-US');

          Twitter::setTitle($id);
          Twitter::setSite('@InnovationsUSA');

          JsonLd::setTitle($id);
          JsonLd::setDescription($description);
          JsonLd::setType('Product');

          return view('product', $returnArr);
        }
    }

    public function filter(Request $request, PostView $postView, Product $product, $id, $filters){

      $nameFix = new Namefix();

      $id = $nameFix->dbName($id);
      //echo $id . ' ' . $filters; die();

      $productTitle = 'SEARCH RESULTS';
        $type = 'product';
      if($id=='color'){
        $type = 'item';
        $productTitle = 'COLLECTION';
      }

      //print_r($filterArr); die();


      // http://localhost/innovations/RC15_laravel_5.1/public/product/vinyl

      //$prodObj = App\Collection::find($id);
      $columnArr = array('item_name');

      //print_r($id); die();

      //$product = new Product();
      $prodObj = $product->getProductFilter($id, $filters, $type, $this->wcArr);

      //dd($filters);

      //print_r($prodObj); die();

      $post = $product;
      $post->post_id = $filters;
      $post->titleslug = $id;
      $postView->createViewLog($post);


      $productArr = array();


      foreach ($prodObj as $key => $value) {

        // check if the device is mobile or not and render different images 900 -- 350 -- 150

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

        // end for image resize

        $displayName = $nameFix->displayName($value->item_name) . ' - ' . $value->item_number;
        //dd($jpgName);

        if($type=='product'){
          $productArr[]= array(
              'type' => 'product',
              'jpgName1' => $jpgName1,
              'mainImage' => $nameFix->thumbImageName($value->main_img, 'medium'),
              'urlName1' => $nameFix->urlName($value->item_name),
              'displayName1' => $nameFix->displayName($value->item_name),
              'itemName' => $nameFix->urlName($value->item_name),
              'dbName' => $nameFix->dbName($value->item_name),
          );
        }
        else{
          $productArr[]= array(
            'type' => 'item',
            'jpgName' => $jpgName, // need caps
            'mainImage' => $nameFix->thumbImageName($value->main_img, 'medium'),
            'skuImage' => $value->galleryimg,
            'urlName' => $nameFix->urlName($value->item_number),
            'displayName' => $displayName,
            'itemName' => $nameFix->urlName($value->item_name),
            'dbName' => $nameFix->dbName($value->item_number),
          );
        }
      }

     // dd($productArr); die();

      $filterObj = array();
      if ($id=='color'){
        //$filterObj = $product->filterList('primary_color');
        $filterObj = $this->filterArrColor;
      }else if ($id=='material'){
        $filterObj = $this->filterArrMaterial;
      }else if ($id=='pattern'){
        $filterObj = $this->filterArrPatterns;
      }else if ($id=='texture'){
        $filterObj = $this->filterArrTexture;
      }else if ($id=='collection'){
        $filterObj = $this->filterArrCollection;

        $productArr = $this->group($productArr);
      }

      $agent = new Agent();
      $mobile = $agent->isMobile();

      $returnArr = [
        'pageId' => $id,
        'filters'=>$filters,
        'productTitle'=>$productTitle,
        'mainArr'=>$productArr,
        'filterObj'=>$filterObj,
        'mobile'=> $mobile
      ];

      $description = "Search Wallcovering or Textile Products with ".$filters;
      $seoKeywords = $filters." Innovations in Wallcoverings";

      $lazyLoad = '';
      $obj = json_decode( $request->getContent() );
      if($obj)
        $lazyLoad = $obj->lazyload;


      if($lazyLoad=='true'){
        return $returnArr;
      }else{

        $title = "Shop Our Wallcoverings by ".ucfirst($id);

        if($id == "collection") {
          if($filters == "spring-2022")
            $title = "Focal Point | Spring-2022 Wallcovering Collection";
        }

        SEOMeta::setTitle($title);
        SEOMeta::setDescription($description);
        SEOMeta::addKeyword($seoKeywords);

        OpenGraph::setDescription($description);
        OpenGraph::setTitle($id);
        OpenGraph::setUrl(url()->current());
        OpenGraph::addProperty('type', 'product');
        OpenGraph::addProperty('locale', 'en-US');

        Twitter::setTitle($id);
        Twitter::setSite('@InnovationsUSA');

        JsonLd::setTitle($id);
        JsonLd::setDescription($description);
        JsonLd::setType('Product');

        return view('product', $returnArr);
      }
    }

    private function allWallcoverings( $request,  $postView,  $product){

      $filterArr = array();
      $filters = '';
      if($request->color){
        $filterArr['primary_color'] = $request->color;

        $filtersArr = explode(" ", $request->color);
        foreach($filtersArr as $val){
          $filters .= '+' . $val;
        }
      }
      if($request->material){
        $filterArr['product_type'] = $request->material;

        $filtersArr = explode(" ", $request->material);
        foreach($filtersArr as $val){
          if(strtolower($val)=='natural-woven') $val = 'natural woven';
          $filters .= '+' . $val;
        }
      }
      if($request->pattern){
        $filterArr['product_design'] = $request->pattern;

        $filtersArr = explode(" ", $request->pattern);
        foreach($filtersArr as $val){
          $val = str_replace("-", "/", $val);

          if(strtolower($val)=='animal/print') $val = 'animal print';
          if(strtolower($val)=='large/scale/mural') $val = 'large-scale/mural';

          $filters .= '+' . $val;
        }
        //print_r($filters); die();
      }
      if($request->texture){
        $filterArr['content'] = $request->texture;

        $filtersArr = explode(" ", $request->texture);
        foreach($filtersArr as $val){
          $filters .= '+' . $val;
        }
      }
      if($request->collection){

        //print_r($request->collection); die();
        $filterStr = '';
        $filterArr_ = explode(" ", $request->collection);
        foreach ($filterArr_ as $key => $value) {
          $pieces = explode("-", $value);
          $value = $pieces[1] . '-' . $pieces[0];
          $filterStr .= $value . ' ';
        }

        //print_r($filterStr); die();
        //$filterArr['collection'] = $request->collection;
        $filterArr['collection'] = rtrim($filterStr); // remove last empty space

        $filtersArr = explode(" ", $request->collection);
        foreach($filtersArr as $val){
          $filters .= '+' . $val;
        }
        //print_r($filters); die();
      }

      if($request->environment){

        $filterArr['environment'] = $request->environment;

        $filtersArr = explode(" ", $request->environment);
        foreach($filtersArr as $val){
          $val = str_replace("-", " ", $val);

          $filters .= '+' . $val;
        }
        //print_r($filters); die();
      }

      if($filters!=''){
         $filters = substr($filters, 1); // remove first char from str
      }

      $id = 'all-wallcovering';
      $nameFix = new Namefix();
      $productTitle = 'ALL PRODUCTS';

      // http://localhost/innovations/_wip/product/

      $columnArr = array('item_name');

      // print_r($filters); die();
       //print_r($filterArr); die();
      //print_r(array_keys($filterArr)); die();

      // Method Chaining based on condition
      //$query = \App\Collection::query();
      //$product = new Product();
      $prodObj = $product->getAllFilter($filterArr);
      // print_r($prodObj); die();

      $post = $product;
      $post->post_id = 'product';
      $post->titleslug = $id;
      $postView->createViewLog($post);

      //print_r($id); die();

      $filterObj = array(
        'color' => $this->filterArrColor,
        'material' => $this->filterArrMaterial,
        'pattern' => $this->filterArrPatterns,
        'texture' => $this->filterArrTexture,
        'collection' => $this->filterArrCollection,
        'environment' => $this->filterArrEnvironment
      );


      //print_r($filterObj); die();


      $mainArr = array();
      $productArr = array();
      $itemArr = array();
      foreach ($prodObj as $value) {

        $type = '';

        foreach ($filterArr as $key => $filter_2) {

          $filter_1 = explode(" ", $filter_2);
          //print_r($filter);
          foreach ($filter_1 as $val) {
            // print_r($value); die(); print_r($key); die(); print_r($val); die();
            $collectionVal = str_replace(" ", "-", $value->collection);

            // check if in array so that items with collection and color match will display all colors and not group into collection
            if( strtolower($collectionVal) == strtolower($val)
                &&  in_array( strtolower($value->primary_color) ,$filterArr) ){
              $type = 'item';
              //$type = 'product';
            }
          }
        }

        if (is_array($filterArr) && count($filterArr) == 1 && array_key_exists("collection", $filterArr)) {
          $type = 'product';
        }

        //die();

        $displayName = $nameFix->displayName($value->item_name) . ' - ' . $value->item_number;

        // get main_img
        $row = ProductMaster::where('item_name', $value->item_name)->first();

        if (isset($row->productList->main_img)) {
          $main_img = $row->productList->main_img;
        } else {
          $main_img = '';
        }

        $jpgName = "";
        $imgUrl = config('constants.value.imgUrl');
        $ver = config('constants.value.VER');

        $Agent = new Agent();
        if ($Agent->isMobile()) {
        // // you're a mobile device
          $jpgName = $imgUrl . '/storage/sku/150x150/' . strtoupper($value->item_number) .'.jpg' . '?v=' . $ver;
          $jpgName1 = $imgUrl . '/storage/product/150x150/' .$nameFix->jpgName($value->item_name). '.jpg?v=' . $ver;

        }
        else {
          // you're a desktop device, or something similar
          $jpgName = $imgUrl . '/storage/sku/350x350/' . strtoupper($value->item_number) .'.jpg' . '?v=' . $ver;
          $jpgName1 = $imgUrl . '/storage/product/350x350/' .$nameFix->jpgName($value->item_name). '.jpg?v=' . $ver;

        }

        if($type=='product'){
          $productArr[]= array(
              'type' => 'product',
              'jpgName1' => $jpgName1,
              'mainImage' => $nameFix->thumbImageName($main_img, 'medium'),
              'urlName1' => $nameFix->urlName($value->item_name),
              'displayName1' => $nameFix->displayName($value->item_name),
              'dbName' => $nameFix->dbName($value->item_name),
          );
        }
        else{
          $itemArr[]= array(
            'type' => 'item',
            'jpgName' => $jpgName,
            'urlName' => $nameFix->urlName($value->item_number),
            'mainImage' => $nameFix->thumbImageName($main_img, 'medium'),
            'displayName' => $displayName,
            'itemName' => $nameFix->urlName($value->item_name),
            'dbName' => $nameFix->dbName($value->item_number),
          );
        }


      }

      // [x]remove dups, not necessary because of model; DB::raw('count(*) as total')
      //$productArr = array_map("unserialize", array_unique(array_map("serialize", $productArr)));

      // [x] $itemArr = array_map("unserialize", array_unique(array_map("serialize", $itemArr)));

      // print_r($productArr); die();
      // print_r($itemArr); die();
      //$productArr = array_unique($productArr);

      // delete the duplicate items
      $serialized = array_map('serialize', $productArr);
      $unique = array_unique($serialized);
      $productArr = array_intersect_key($productArr, $unique);
      $mainArr = array_merge( $productArr, $itemArr );

     // print_r($mainArr); die();

      $agent = new Agent();
      $mobile = $agent->isMobile();

      $lazyLoad = '';
      $obj = json_decode( $request->getContent() );
      if($obj)
        $lazyLoad = $obj->lazyload;

      $returnArr = [
          'pageId' => $id,
          'filters'=> $filters,
          'productTitle'=>$productTitle,
          'mainArr'=>$mainArr,
          'filterObj'=>$filterObj,
          'mobile'=>$mobile,
      ];


      if($lazyLoad=='true'){
        return $returnArr;
      }else{

        $description = "Search all Innovations wallcovering products to find exactly what your interior design project needs.";
        $seoKeywords = "All Wallcovering Innovations in Wallcoverings";

        SEOMeta::setTitle("All Products");
        SEOMeta::setDescription($description);
        SEOMeta::addKeyword($seoKeywords);

        OpenGraph::setDescription($description);
        OpenGraph::setTitle($id);
        OpenGraph::setUrl(url()->current());
        OpenGraph::addProperty('type', 'product');
        OpenGraph::addProperty('locale', 'en-US');

        Twitter::setTitle($id);
        Twitter::setSite('@InnovationsUSA');

        JsonLd::setTitle($id);
        JsonLd::setDescription($description);
        JsonLd::setType('Product');

        return view('product', $returnArr);
      }

    }

    public function showCSVForm() {

      if(!\Auth::check()) {
        return redirect()->to('/');
      } else {
        $user = \Auth::user();
        if($user->role->name != "admin") {
          return redirect()->to('/');
        }
      }

      return view('update-csv-form');

    }

    public function updateCSVProduct(Request $request) {

      $request->validate([
        "updated_csv_file" => "required",
        "template_csv_file" => "required",
      ]);

      $updatedArray = Excel::toArray(new ProductImport, $request->file('updated_csv_file'));
      $templateArray = Excel::toArray(new ProductImport, $request->file('template_csv_file'));

      if(!is_array($updatedArray) || !is_array($templateArray)) {
        return redirect()->back();
      }

      $id_pdfArray = array();
      foreach($templateArray[0] as $template) {
        $id_pdfArray[strtolower($template['item_number'])] = $template['id_pdf'];
      }



      $resultArray = array();

      $resultArray[] = array(
        "id_pdf","item_number","item_name","style_additional_description","item_additional_description","mill_description","color_name","width","repeat","content","wholesale_price","retail_price","furniture_manufacturer_price","other_price","tests","label_message","finish","country_of_origin1","vendor","date_introduced","selling_unit","product_category","usage","collection","internal_comment","default_warehouse","weight","shipping_weight","freight_code","package_size","large_piece_size","lead_time","discountable","warehouse_location","inventoried","custom_item","discontinue_code","product_design","product_type","width_cm","grams_sq_m","primary_color","secondary_color","bolt_size","imo_compliant","phthalate_free","type_ii","min_order_quantity","min_selling_quantity","min_order_increment","cut_fee","env_ca_01350_cert","env_fsc_certified_paper","env_innvironments_compliant","env_leed_within_500_miles","env_phthalate_free_vinyl","env_rapidly_renewable","env_recycled_backing","env_recycled_content_by_weight","env_ultralow_voc_vinyl","env_natural_nonsynthetic","finish_cork_faux","finish_foiled_metallic","finish_grasscloth_faux","finish_linen_faux","finish_pleated","finish_relief","finish_silk_faux","finish_wood_faux","flame_astm_e84_class_a","flame_cal_117_pass","flame_euroclass_b","flame_imo_compliant","flame_nfpa_260_class_i","flame_nfpa_701_pass","flame_ufac_class_i","tech_antimicrobial","tech_doublerubs_wyzenbeek","tech_ink_resistant_finish","tech_seaming","tech_type_i","tech_type_ii"
      );

      $index  = 1;

      foreach($updatedArray[0] as $product) {

        $id = isset($id_pdfArray[strtolower($product['item_number'])]) ? $id_pdfArray[strtolower($product['item_number'])] : $index++;

        $resultArray[] = $this->reorderArrayKey($product, $id);
      }

      $export = new \App\Exports\ProductExport($resultArray);

      return Excel::download($export, 'ProductMaster.csv');

      return redirect()->back()->with(array("success" => "Successfully Exported!!!"));

    }

    protected function reorderArrayKey($data, $id_pdf) {

      $orderArray = array(
        "id_pdf","item_number","item_name","style_additional_description","item_additional_description","mill_description","color_name","width","repeat","content","wholesale_price","retail_price","furniture_manufacturer_price","other_price","tests","label_message","finish","country_of_origin1","vendor","date_introduced","selling_unit","product_category","usage","collection","internal_comment","default_warehouse","weight","shipping_weight","freight_code","package_size","large_piece_size","lead_time","discountable","warehouse_location","inventoried","custom_item","discontinue_code","product_design","product_type","width_cm","grams_sq_m","primary_color","secondary_color","bolt_size","imo_compliant","phthalate_free","type_ii","min_order_quantity","min_selling_quantity","min_order_increment","cut_fee","env_ca_01350_cert","env_fsc_certified_paper","env_innvironments_compliant","env_leed_within_500_miles","env_phthalate_free_vinyl","env_rapidly_renewable","env_recycled_backing","env_recycled_content_by_weight","env_ultralow_voc_vinyl","env_natural_nonsynthetic","finish_cork_faux","finish_foiled_metallic","finish_grasscloth_faux","finish_linen_faux","finish_pleated","finish_relief","finish_silk_faux","finish_wood_faux","flame_astm_e84_class_a","flame_cal_117_pass","flame_euroclass_b","flame_imo_compliant","flame_nfpa_260_class_i","flame_nfpa_701_pass","flame_ufac_class_i","tech_antimicrobial","tech_doublerubs_wyzenbeek","tech_ink_resistant_finish","tech_seaming","tech_type_i","tech_type_ii"
      );

      $result = array();
      foreach($orderArray as $col) {
        if($col == "id_pdf") {
          $result['id_pdf'] = $id_pdf;
          continue;
        }
        $result[$col] = $data[$col];
      }
      return $result;
    }

    private function group($arr){

      $nameFix = new Namefix();

      $groupArr = array();
      foreach ($arr as $key => $value) {
        $groupArr[]= array(
          'type' => $value['type'],
          'jpgName1' => $value['jpgName1'],
          'mainImage' => $value['mainImage'],
          'urlName1' => $nameFix->urlName($value['itemName']),
          'displayName1' => $nameFix->displayName($value['itemName']),
          'dbName' => $nameFix->dbName($value['itemName']),
        );
      }

      $groupArr = array_unique($groupArr, SORT_REGULAR); // unique

      //print_r($groupArr); die();
      return $groupArr;
    }
}
