<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

use App\Item;
use App\WebDistribution;
use App\PostView;
use App\Http\Utils\Namefix;

use SEOMeta;
use OpenGraph;
use JsonLd;
use Twitter;

use Jenssegers\Agent\Agent;

class ItemController extends Controller{

  public function limit($value, $limit = 140, $end = '...')
  {
    if (mb_strwidth($value, 'UTF-8') <= $limit) {
        return $value;
    }

    return rtrim(mb_strimwidth($value, 0, $limit, '', 'UTF-8')).$end;
  }

  public function getItem(PostView $postView, Item $item, $id){
    // test comment
	  $nameFix = new Namefix();

	  $id = $nameFix->dbName($id);

    $imgUrl = config('constants.value.imgUrl');
    $ver = config('constants.value.VER');

    $Agent = new Agent();


    $item = new Item();
    $itemObj = $item->getItem($id);
    $similarData = $item->getSimilarProducts($id, "item");
    $similarItems = array();
    foreach($similarData as $similarItem) {

      $mainImage = "";
      if(!$similarItem->productList) {
        $mainImage = null;
      } else {
        $mainImage = $similarItem->productList->main_img;
      }

      $itemId = $nameFix->urlName($similarItem->item_name);

      if ($Agent->isMobile()) {
        // // you're a mobile device
        $mainImg = $imgUrl . '/storage/product/150x150/' . strtolower($nameFix->jpgName($itemId)) . '.jpg?v=' . $ver;

        }
        else {
          // you're a desktop device, or something similar
          $mainImg = $imgUrl . '/storage/product/350x350/' . strtolower($nameFix->jpgName($itemId)) . '.jpg?v=' . $ver;

      }

      $similarItems[] = array(
        'mainImg' => $mainImg,
        'mainImage' => $mainImage,
        'urlName' => $itemId,
        "displayName" => strtoupper($nameFix->displayName($similarItem->item_name))
      );
    }

    $post = $item;
    $post->post_id = 'item';
    $post->titleslug = $id;
    $postView->createViewLog($post);

    if(count($itemObj['sku']) <= 0){
     return abort(404);
     die();
    }

		$itemArr = array();

		foreach ($itemObj['sku'] as $key => $value) {

      if ($Agent->isMobile()) {
      // // you're a mobile device
        $thumb_img = $imgUrl . '/storage/sku/150x150/' . $value->item_number .'.jpg' . '?v=' . $ver;

      }
      else {
        // you're a desktop device, or something similar
        $thumb_img = $imgUrl . '/storage/sku/350x350/' . $value->item_number .'.jpg' . '?v=' . $ver;

      }

			$itemArr[]= array(
        'urlName' => $nameFix->urlName($value->item_name),
        'skuNum' => $value->item_number,
        'thumb_img' => $thumb_img,
        'displayName' => $nameFix->displayName($value->item_name),
        'content' => $value->content,
        'careMaint' => $value->careMaint,
        'color' => $value->color_name,
        'product_type' => $value->product_type,
        'width' => $value->width,
        'width_cm' => $value->width_cm,
        'weight' => $value->weight,
        'bolt_size' => $value->bolt_size,
        'usage' => $value->usage,
        'flame_astm_e84_class_a' => $value->flame_astm_e84_class_a,
        'flame_euroclass_b' => $value->flame_euroclass_b,
        'flame_cal_117_pass' => $value->flame_cal_117_pass,
        'flame_nfpa_260_class_i' => $value->flame_nfpa_260_class_i,
        'flame_ufac_class_i' => $value->flame_ufac_class_i,
        'flame_nfpa_701_pass' => $value->flame_nfpa_701_pass,
        'env_ca_01350_cert' => $value->env_ca_01350_cert,
        'env_fsc_certified_paper' => $value->env_fsc_certified_paper,
        'env_innvironments_compliant' => $value->env_innvironments_compliant,
        'env_leed_within_500_miles' => $value->env_leed_within_500_miles,
        'env_phthalate_free_vinyl' => $value->env_phthalate_free_vinyl,
        'env_rapidly_renewable' => $value->env_rapidly_renewable,
        'env_recycled_backing' => $value->env_recycled_backing,
        'env_recycled_content_by_weight' => $value->env_recycled_content_by_weight,
        'env_ultralow_voc_vinyl' => $value->env_ultralow_voc_vinyl,
        'env_natural_nonsynthetic' => $value->env_natural_nonsynthetic,
        'tech_doublerubs_wyzenbeek' => $value->tech_doublerubs_wyzenbeek,
        'grams_sq_m' => $value->grams_sq_m,
        'repeat' => $value->repeat,
        'tests' => $value->tests,
        'country_of_origin1' => $value->country_of_origin1,
        'tech_type_i' => $value->tech_type_i,
        'tech_type_ii' => $value->tech_type_ii,
        'description' => $value->description,
        'specData' => $value->specData,
        'backingData' => $value->backingData,
        'env_phthalate_free_vinyl' => $value->env_phthalate_free_vinyl,
        'tech_seaming' => $value->tech_seaming,
        'seenin' => $value->seenin,
			);

    }

    //check if the faux-leather product is marked or not

    $isWallcovering = false;
    $isFauxLeather =true;
    if(isset($itemObj) && count($itemObj['sku']) > 0) {
      $value = $itemObj['sku'][0];
      if($value->product_type == 'Faux Leather') {

        $isFauxLeather = true;

        // go to WD and check whether or not it's usable as wallpaper
        $PDFendPoint = config('constants.value.PDFendPoint');
        $PDFapiKey = config('constants.value.PDFapiKey');
        $wd = new WebDistribution($PDFendPoint, $PDFapiKey);
        $leatherSku = $wd->getLeatherStatus($value->item_name);
        for($i=0; $i < count($leatherSku); $i++) {
          $flag = strpos($leatherSku[$i]['style']['custom_fields']['Application (faux leather): Wallcovering']['current'], "Direct Glue");

          if ($flag !== false) {
            $isWallcovering = true;
            break;
          }
        }
		  }
    }

    $seoKeywords = array();

    $seoKeywords = array(
      $itemArr[0]['content'] . ' Wallcovering',
      $itemArr[0]['tests'],
      $itemArr[0]['backingData'],
      'Made in ' . $itemArr[0]['country_of_origin1'],
      'Innovations in Wallcoverings'
    );

    // title material
    $contentArr = explode(' ', $itemArr[0]['content']);

    $description = $this->limit($itemArr[0]['description']);
    // $description = explode('\n', $desc);
    // $description = $description[0] . '...';

    $title = $itemArr[0]['displayName'];

    $material = ($itemArr[0]['product_type'] != "Inspired Material") ? $itemArr[0]['product_type'] : 'Specialty';

    if($title == "Bartow") {
      $title .= " | Modern Natural-Woven";
    } else if($title == "Barbizon ") {
      $title .= " | Luxury Natural-Woven";
    } else if($title == "Nuevo Laredo") {
      $title .= " | Modern Faux-Leather";
    } else if($title == "Abstract") {
      $title .= " | Luxury Vinyl";
    } else if($title == "Pasadena") {
      $title .= " | Nylon Microfiber Faux-Leather";
    } else if($title == "Reed") {
      $title .= " | Eco-Friendly Chevron";
    } else if($title == "Madrid" || $title == "Barcelona" || $title == "El-Paso") {
      $title .= " | Luxury Faux-Leather";
    } else if($title == "Belgrade") {
      $title .= " | Modern Textile";
    } else if($title == "Bustle" || $title == "Walden") {
      $title .= " | Luxury Textile";
    } else if($title == "Reno") {
      $title .= " | Polyurethane Faux-Leather";
    } else if ($title == "Plush") {
      $title .= " | Modern Faux-Leather";
    } else if ($title == "Alchemy" || $title == "Arrugas") {
      $title .= " | Luxury Vinyl";
    } else if ($title == "Yakisugi") {
      $title .= " | Modern Cork";
    } else if ($title == "Burnish") {
      $title = "Walden | Metallic Vinyl";
    } else if ($title == "Boucle") {
      $title .= " | Seamless Vinyl";
    } else if ($title == "Ashlar") {
      $title .= " | Phthalate Free Vinyl";
    } else if ($title == "Alentejo") {
      $title .= " | Type II Vinyl";
    }
    else {
      $title.= ' '. $material;
    }

    SEOMeta::setTitle(rtrim($itemArr[0]['displayName']));
    SEOMeta::setDescription($description);
    SEOMeta::addKeyword($seoKeywords);

    OpenGraph::setDescription($description);
    OpenGraph::setTitle($itemArr[0]['displayName']);
    OpenGraph::setUrl(url()->current());
    OpenGraph::addProperty('type', 'product');
    OpenGraph::addProperty('locale', 'en-US');

    Twitter::setTitle($itemArr[0]['displayName']);
    Twitter::setSite('@InnovationsUSA');

    JsonLd::setTitle($itemArr[0]['displayName']);
    JsonLd::setDescription($itemArr[0]['description']);
    JsonLd::setType('Product');

    // check if client

    $is_client = false;

    if(\Auth::check()) {
      $user = \Auth::user();
      if(\Auth::check()) {
        if($user->role->name == "Client" || $user->role->name == "client" || $user->role->name == "admin" || $user->role->name == "Admin")
          $is_client = true;
      }
    }


		return view('item', [
      'pageType' => 'item',
      'is_client' => $is_client,
      'mainImg' => strtolower($nameFix->jpgName($id)),
			'itemArr'=>$itemArr,
      'similarItems' => $similarItems,
      'itemGallery'=>$itemObj['gallery'],
      'itemSeenIns' =>$itemObj['dynSeenIns'],
      'mainImage' => $itemObj['sku'][0]->main_img,
      "isWallcovering" => $isWallcovering,
      "isFauxLeather" => $isFauxLeather
		]);

	}
  public function getSku(PostView $postView, Item $item, $id, $sku){

    // check if client

    $is_client = false;

    if(\Auth::check()) {
      $user = \Auth::user();
      if(\Auth::check() && $user->role->name == "Client" || $user->role->name == 'Staff' || $user->role->name == 'admin') {
        $is_client = true;
      }
    }

    $imgUrl = config('constants.value.imgUrl');
    $ver = config('constants.value.VER');

    $Agent = new Agent();

    if($is_client) {
      $PDFendPoint = config('constants.value.PDFendPoint');
      $PDFapiKey = config('constants.value.PDFapiKey');
      $wd = new WebDistribution($PDFendPoint, $PDFapiKey);

      $fullSku = $wd->pdfFullSku($sku);
    } else {
      $fullSku = array();
    }

    $nameFix = new Namefix();

    $id = $nameFix->dbName($id);

    $itemObj = $item->getItem($id);

    $itemSeenIns =  $itemObj['dynSeenIns'];


    $myColorName = '';
    $myColor = '';
    $myMaterial = '';
    $myPattern = '';
    $myTexture = '';
    $myWhatsnew = '';

    $skuColor = '';
    $itemArr = array();
    $mySkuNum = NULL;
    $isWallcovering = false;
    $thumb_img = "";
    $isFauxLeather = false;
    foreach ($itemObj['sku'] as $key => $value) {

      if ($Agent->isMobile()) {
        // // you're a mobile device
          $thumb_img = $imgUrl . '/storage/sku/150x150/' . $value->item_number .'.jpg' . '?v=' . $ver;

        }
        else {
          // you're a desktop device, or something similar
          $thumb_img = $imgUrl . '/storage/sku/350x350/' . $value->item_number .'.jpg' . '?v=' . $ver;

        }

      if(strtolower($value->item_number)== $sku){
        $id_pdf = $value->id_pdf;
        $unit = $value->selling_unit;
        $skuColor = $value->color_name;
        $wholesalePrice = $value->wholesale_price;
        $selling_unit = $value->selling_unit;
        $width = $value->width;
        $bolt_size = $value->bolt_size;
        $feeA = "Fee A";
        $feeB = "Fee B";
        $feeC = "Fee C";
        $sbr = "SBR";

        $posA = strpos($value->cut_fee, $feeA);
        $posB = strpos($value->cut_fee, $feeB);
        $posC = strpos($value->cut_fee, $feeC);
        $posD = strpos($value->cut_fee, $sbr);

        if($posA !== false) {
            $cut_fee = $feeA;
            $cut_fee_tooltip = "These items are subject to a cut fee of $4/yard on orders under 30 yards";
        } elseif
            ($posB !== false) {
            $cut_fee = $feeB;
            $cut_fee_tooltip = "These items have a minimum order of one roll, but may be ordered in increments of 4 yards after purchasing one roll, for a one-time cut fee of $35";
        } else if
            ($posC !== false) {
            $cut_fee = $feeC;
            $cut_fee_tooltip = "These items have a setup charge of $35 on orders under 30 yards.";
        } else {
          $cut_fee = 'None';
          $cut_fee_tooltip = "These items are available by the yard without a cut fee after ordering the minimum order";
        }

        //check if the faux-leather product is marked or not

        if($value->product_type == 'Faux Leather') {
          $isFauxLeather = true;
          // go to WD and check whether or not it's usable as wallpaper
          $PDFendPoint = config('constants.value.PDFendPoint');
          $PDFapiKey = config('constants.value.PDFapiKey');
          $wd = new WebDistribution($PDFendPoint, $PDFapiKey);
          $leatherSku = $wd->getLeatherStatus($value->item_name);
          $i = 0;
            for($i=0; $i < count($leatherSku); $i++) {
              if($value->item_number == $leatherSku[$i]['item_number']) {
                $isWallcovering = strpos($leatherSku[$i]['style']['custom_fields']['Application (faux leather): Wallcovering']['current'], "Direct Glue");
                if ($isWallcovering !== false) {
                  $isWallcovering = true;
                }
                break;
              }
            }
        }

      }

      $itemArr[]= array(
        'urlName' => $nameFix->urlName($value->item_name),
        'skuNum' => $value->item_number,
        'thumb_img' => $thumb_img,
        'displayName' => $nameFix->displayName($value->item_name),
        'content' => $value->content,
        'careMaint' => $value->careMaint,
        'color' => $value->color_name,
        'product_type' => $value->product_type,
        'width' => $value->width,
        'width_cm' => $value->width_cm,
        'weight' => $value->weight,
        'bolt_size' => $value->bolt_size,
        'usage' => $value->usage,
        'flame_astm_e84_class_a' => $value->flame_astm_e84_class_a,
        'flame_euroclass_b' => $value->flame_euroclass_b,
        'flame_cal_117_pass' => $value->flame_cal_117_pass,
        'flame_nfpa_260_class_i' => $value->flame_nfpa_260_class_i,
        'flame_ufac_class_i' => $value->flame_ufac_class_i,
        'flame_nfpa_701_pass' => $value->flame_nfpa_701_pass,
        'env_ca_01350_cert' => $value->env_ca_01350_cert,
        'env_fsc_certified_paper' => $value->env_fsc_certified_paper,
        'env_innvironments_compliant' => $value->env_innvironments_compliant,
        'env_leed_within_500_miles' => $value->env_leed_within_500_miles,
        'env_phthalate_free_vinyl' => $value->env_phthalate_free_vinyl,
        'env_rapidly_renewable' => $value->env_rapidly_renewable,
        'env_recycled_backing' => $value->env_recycled_backing,
        'env_recycled_content_by_weight' => $value->env_recycled_content_by_weight,
        'env_ultralow_voc_vinyl' => $value->env_ultralow_voc_vinyl,
        'env_natural_nonsynthetic' => $value->env_natural_nonsynthetic,
        'tech_doublerubs_wyzenbeek' => $value->tech_doublerubs_wyzenbeek,
        'grams_sq_m' => $value->grams_sq_m,
        'repeat' => $value->repeat,
        'tests' => $value->tests,
        'country_of_origin1' => $value->country_of_origin1,
        'tech_type_i' => $value->tech_type_i,
        'tech_type_ii' => $value->tech_type_ii,
        'description' => $value->description,
        'specData' => $value->specData,
        'backingData' => $value->backingData,
        'env_phthalate_free_vinyl' => $value->env_phthalate_free_vinyl,
        'tech_seaming' => $value->tech_seaming,
        'seenin' => $value->seenin,
      );

      if($value->item_number==strtoupper($sku)){
        $myColorName = $value->color_name;
        $myColor = $value->primary_color;
        $myMaterial = $value->content;
        $myPattern = $value->product_design;
        $myTexture = $value->content;
        $myWhatsnew = $value->collection;
        $mySkuNum = $value->id_pdf;
      }
    }

    // dye lot
    if($mySkuNum == null || !$is_client)
      $skuDyeLot = array();
    else {
      $skuDyeLot = $wd->pdfDyeLots($mySkuNum);
    }


    //dd($skuDyeLot);

    $largestDyeLot = 0;
    $dyeLotArray = array();
    if(isset($skuDyeLot) && count($skuDyeLot) > 0 ) {
      $DyeLots = $this->array_value_recursive('lot', $skuDyeLot);
      if(is_array($DyeLots))
        $DyeLots = array_unique($DyeLots);
      else {
        $DyeLots = array(0 => $DyeLots);
      }
      foreach($DyeLots as $index => $DyeLot) {
        $dyeLotArray[$index] = 0;
        foreach($skuDyeLot as $skuDye) {
          if($skuDye['lot'] == $DyeLot) {
            $dyeLotArray[$index] += $skuDye['on_hand'];
          }
        }
      }

      if(count($dyeLotArray) > 0) $largestDyeLot = max($dyeLotArray);
    }

    if($is_client) {
      $minOrder = $fullSku[0]->style->minimum_selling_quantity;
    } else { $minOrder = '';}

    if($myColorName == ''){
     return abort(404);
     die();
    }
    if(isset($itemArr)) {
      $inventory = 0;
      if($is_client) {
        $wdItem = $wd->pdfSimple($sku);

        $inventory = $wdItem['inventory']['available'];
      }
      // $selling_unit = $itemArr['selling_unit'];

      $seoKeywords = array();

        $seoKeywords = array(
          $itemArr[0]['backingData'] . ' Wallcovering',
          $itemArr[0]['tests'],
          $itemArr[0]['backingData'],
          'Made in ' . $itemArr[0]['country_of_origin1'],
          'Innovations in Wallcoverings'
        );

        SEOMeta::addKeyword($seoKeywords);


      if($itemArr[0]['product_type'] != "Inspired Material") {
        $material = $itemArr[0]['product_type'];
      } else {
        $material = 'Specialty';
      }

      $title = $itemArr[0]['displayName'].' '.

      $description = $this->limit($itemArr[0]['description']);
      SEOMeta::setTitle($itemArr[0]['displayName']);
      SEOMeta::setDescription($description);

      OpenGraph::setDescription($description);
      OpenGraph::setTitle($itemArr[0]['displayName']);
      OpenGraph::setUrl('https://www.innovations.com/item/'. $itemArr[0]['urlName']);
      OpenGraph::addProperty('type', 'product');
      OpenGraph::addProperty('locale', 'en-US');

      Twitter::setTitle($itemArr[0]['displayName']);
      Twitter::setSite('@InnovationsUSA');

      JsonLd::setTitle($itemArr[0]['displayName']);
      JsonLd::setDescription($itemArr[0]['description']);
      JsonLd::setType('Product');
    }

    $post = $item;
    $post->post_id = $sku;
    $post->titleslug = 'item';
    $post->color = $myColor;
    $post->material = $myMaterial;
    $post->pattern = $myPattern;
    $post->texture = $myTexture;
    $postView->createViewLog($post);

    $wholesalePrice = 0;

    if($is_client) {
      // if new product, the whole sale price from wd api

      $skuData = $wd->pdfSimple($sku);

      //getting the price
      $product = $wd->pdfStock($id_pdf);
      //dd($product);
      $wholesalePrice = $product['style']['prices'][0]['wholesale_price'];
    } else {
      $sholesalePrice = 0;
    }

    if ($Agent->isMobile()) {
      // // you're a mobile device
        $mainImgUrl = $imgUrl . '/storage/sku/350x350/' . strtoupper($sku) .'.jpg' . '?v=' . $ver;

      }
      else {
        // you're a desktop device, or something similar
        $mainImgUrl = $imgUrl . '/storage/sku/900x900/' . strtoupper($sku) .'.jpg' . '?v=' . $ver;

      }

      $similarData = $item->getSimilarProducts($sku, "sku");
      $similarItems = array();
      foreach($similarData as $similarItem) {

        if ($Agent->isMobile()) {
          // // you're a mobile device
            $mainImg = $imgUrl . '/storage/sku/150x150/' . strtoupper($similarItem->item_number) .'.jpg' . '?v=' . $ver;

          }
          else {
            // you're a desktop device, or something similar
            $mainImg = $imgUrl . '/storage/sku/350x350/' . strtoupper($similarItem->item_number) .'.jpg' . '?v=' . $ver;

          }

        $itemId = $nameFix->urlName($similarItem->item_name);
        $productId = $nameFix->urlName($similarItem->item_number);
        $similarItems[] = array(
          'mainImage' => $mainImg,
          'urlName' => '/item/'.$itemId.'/'.$productId,
          "displayName" => $nameFix->displayName($similarItem->item_name)." ".strtoupper($nameFix->displayName($similarItem->item_number))
        );
      }

    return view('item', [
      'pageType' => 'sku',
      'similarItems' => $similarItems,
      'is_client' => $is_client,
      'mainImg' => strtoupper($sku),
      'mainImgUrl'=> $mainImgUrl,
      'itemId' => strtolower($nameFix->urlName($id)),
      'sku' => strtoupper($sku),
      'skucolor' => strtoupper($skuColor),
      'wholesaleprice' => $wholesalePrice,
      'id_pdf' => $id_pdf,
      'unit' => $unit,
      'itemArr'=>$itemArr,
      'itemGallery'=>$itemObj['gallery'],
      'inventory' => $inventory,
      'selling_unit' => strtolower($selling_unit),
      'width' => $width,
      'cut_fee' => $cut_fee,
      'cut_fee_tooltip' => $cut_fee_tooltip,
      'minOrder' => $minOrder,
      'bolt_size' => $bolt_size,
      'itemSeenIns' => $itemSeenIns,
      'largestDyeLot' => $largestDyeLot,
      "isWallcovering" => $isWallcovering,
      "isFauxLeather" => $isFauxLeather
    ]);
  }

  public function getItemAjax($id){
    $nameFix = new Namefix();
    $item = new Item();
    $itemObj = $item->getItem($nameFix->dbName($id));
    return $itemObj;
  }

  public function getItemFilterAjax(PostView $postView, Item $item, $id){
    $item = new Item();
    $itemObj = $item->getItemFilter($id);

    $post = $item;
    $post->post_id = $id;
    $post->titleslug = 'item';

    $postView->createViewLog($post);


    return $itemObj;
  }

  public function itemCalculator(Request $request, $item = "")

  {

    SEOMeta::setTitle('Calculate Yardage Needed');
    SEOMeta::setDescription('Calculator for wallcovering yardage');
    SEOMeta::addKeyword(array("yardage", "calculator", "wallcovering", "wallpaper"));

    OpenGraph::setDescription('Calculator for wallcovering yardage');
    OpenGraph::setTitle('Calculate Yardage Needed');
    OpenGraph::setUrl(url()->current());
    OpenGraph::addProperty('type', 'calculator');
    OpenGraph::addProperty('locale', 'en-US');

    Twitter::setTitle('Calculator for wallcovering yardage');
    Twitter::setSite('@InnovationsUSA');

    JsonLd::setTitle('Calculate Yardage Needed');
    JsonLd::setDescription('Calculator for wallcovering yardage');
    JsonLd::setType('calculator');

    $data = array();
    $width = 0;
    if($request->query('width'))
      $width = $request->query('width');
    $data['width'] = $width;
    $data['item'] = $item;
    if($width == 0 && $item == '')
      $data['type'] = 'yardage';
    else
      $data['type'] = 'item';
    return view('yardage-calculator', $data);
  }

  public function skuCalculator(Request $request, $item = "", $sku = "")

  {

    SEOMeta::setTitle('Calculate Yardage Needed');
    SEOMeta::setDescription('Calculator for wallcovering yardage');
    SEOMeta::addKeyword(array("yardage", "calculator", "wallcovering", "wallpaper"));

    OpenGraph::setDescription('Calculator for wallcovering yardage');
    OpenGraph::setTitle('Calculate Yardage Needed');
    OpenGraph::setUrl(url()->current());
    OpenGraph::addProperty('type', 'calculator');
    OpenGraph::addProperty('locale', 'en-US');

    Twitter::setTitle('Calculator for wallcovering yardage');
    Twitter::setSite('@InnovationsUSA');

    JsonLd::setTitle('Calculate Yardage Needed');
    JsonLd::setDescription('Calculator for wallcovering yardage');
    JsonLd::setType('calculator');

    $data = array();
    $width = 0;
    if($request->query('width'))
      $width = $request->query('width');

    $data['width'] = $width;
    $data['item'] = $item;
    $data['sku'] = $sku;
    $data['type'] = 'sku';

    return view('yardage-calculator', $data);
  }

  public function array_value_recursive($key, array $arr){
    $val = array();
    array_walk_recursive($arr, function($v, $k) use($key, &$val){
        if($k == $key) array_push($val, $v);
    });
    return count($val) > 1 ? $val : array_pop($val);
  }
}
