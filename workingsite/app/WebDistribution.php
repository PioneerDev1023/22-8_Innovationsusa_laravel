<?php
namespace App;

use App\Http\Utils\Namefix;

use App\Client;

class WebDistribution {

  private $PDFurl;
  private $PDFapiKey;
  private $PDFendPoint;

  // Get database access
  public function __construct($v1, $v2){
    $this->endPoint = $v1;
    $this->PDFapiKey = $v2;
  }

  public function curlPDFapi($pdfURL){
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $pdfURL);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);

    curl_setopt($ch, CURLOPT_HTTPHEADER, array(
        //"Content-Type: application/json",
        "X-Api-Key: " . $this->PDFapiKey,
        'X-App-Id: 5'
    ));

    $data = curl_exec($ch);

    curl_close($ch);

    return $data;
  }

  private function cUrlData($url, $v1, $v2){
    $jsonData = $v1;

    $ch = curl_init();

    curl_setopt($ch, CURLOPT_URL, $url);

    if($v2=='step1'||$v2=='step2'){
      // create transaction
      curl_setopt($ch, CURLOPT_POST, 1);
      curl_setopt($ch, CURLOPT_POSTFIELDS, $jsonData);
    }

    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);

    if (strpos($this->endPoint, 'https://') === false) {
        // !!! for non SSL , error on desktop
        // Failed to connect to localhost port 1080: Connection refused

        curl_setopt($ch, CURLOPT_PROXY, '');
        curl_setopt($ch, CURLOPT_PORT, $_SERVER['SERVER_PORT']);
    }


    curl_setopt($ch, CURLOPT_HTTPHEADER, array(
        "Content-Type: application/json",
        'X-Api-Key: ' . $this->PDFapiKey,
        //'X-App-Id: 5'
    ));

    $output = curl_exec($ch);

    curl_close($ch);

    return $output;
  }

  private function dataArray($userData, $customerData){

    $name = $userData['firstname'] . ' ' . $userData['lastname'];
    $company = $userData['company'];
    //$email = $userData['email'];
    $address_1 = $userData['address'];
    $address_2 = $userData['address2'];
    $city = $userData['city'];
    $state = $userData['state'];
    $zip = $userData['zip'];
    $country = $userData['country'];
    $phone = $userData['phone'];
    //$token = $userData['token'];

    $customerId = $customerData['customerId'];
    $repId = $customerData['repId'];
    $termsId = $customerData['termsId'];



    return $arr = array(
      "transaction_type_id"  => "1",
      "backordered"          => "true", // back orders
      "source_key"           => "web",
      "line"                 => "2",
      "warehouse_id"         => "120", // << NEW JERSEY WAREHOUSE, pdf_sku.php
      "customer_id"          => $customerId,
      "rep1_id"              => $repId,
      "order_terms_id"       => $termsId,
      "currency_id"          => "151",
      "carrier_id"           => "2",
      "shipping_service_id"  => "2",
      "sale_type_id"         => "1",
      "ship_to_name"         => $company,
      "ship_to_street"       => $address_1,
      "ship_to_street2"      => $address_2,
      "ship_to_city"         => $city,
      "ship_to_country_id"   => "228",
      "ship_to_postal_code"  => $zip,
      "ship_to_attention"    => $name,
      "ship_to_phone"        => $phone,
      "state_tax_percent"    => 8.25,
      "federal_tax_percent"  => 8.25,
   );
    // "state_tax_amount" => 8.25,
    // "federal_tax_amount" => 8.25,
    // "state_taxable_basis" => 8.25,

  }

  public function createShipping($cartObj, $cutFee, $arrAddy, $customerData){

    $cartObjArr = array();
    foreach($cartObj as $key=>$val){

      // STEP 1
      $dataArr = $this->dataArray($arrAddy, $customerData);
      $url = $this->endPoint . "transaction";
      $jsonData = json_encode($dataArr);
      $data1 = $this->cUrlData($url, $jsonData, 'step1');
      //The TRUE returns an array instead of an object.
      $data = json_decode($data1, true);

      $transactionId = $data['id'];

      //$transactionId = 123; // <TEST>

      //print_r($val); die();

      // STEP 2
      $skuId = $val['id'];
      $qty = $val['quantity'];
      // get float from mixed string, this has "$" sign
      $price = (float) filter_var( $val['price'] , FILTER_SANITIZE_NUMBER_FLOAT, FILTER_FLAG_ALLOW_FRACTION );

      $path = 'item?company=2&item_number=';
      $url = $this->endPoint . $path . $skuId;

      $data2 = $this->cUrlData($url, '', 'itm_number');
      $itemNumObj = json_decode($data2, true);//The TRUE returns an array instead

      $itemNum = $itemNumObj[0]['id'];
      $unitNum = $itemNumObj[0]['style']['selling_unit_id'];

      $dataArray2 = array(
        "transaction"  => $transactionId,
        "item"  => $itemNum,
        "price"  => $price,
        "quantity"  => $qty,
        "unit" => $unitNum,
      );

      $jsonData = json_encode($dataArray2);

      $url = $this->endPoint . "transaction-item";
      $data21 = $this->cUrlData($url, $jsonData, 'step2');
      $data = json_decode($data21, true);//The TRUE returns an array instead

      $cartObjArr[$skuId]= $data;
    }

    // STEP 3
    $itmFeeDataArr = array();

    $shippingArr = array();

    foreach($cartObjArr as $key=>$val){

      $freight = 0;
      $weight = 0;
      $stateTax = 0;
      $packingCharge = 0;

      $transactionId = $val['transaction_id'];

      // freight options
      $pdfURL = $this->endPoint . "transaction/" . $transactionId . "/freight-options";
      $data3 = $this->cUrlData($pdfURL, '', 'step3');
      $data = json_decode($data3, true);//The TRUE returns an array instead
      $freightOptions = $data['rates'];

      // state-tax
      $pdfURL = $this->endPoint . "transaction/" . $transactionId . "/state-tax";
      $data3 = $this->cUrlData($pdfURL, '', 'step3');
      $data = json_decode($data3, true);
      $stateTax = $data['rate'];


      // CLOSE OUT
      $pdfURL = $this->endPoint . "transaction/" . $transactionId . "?force=false";
      $data3 = $this->cUrlData($pdfURL, '', 'step3');
      $data = json_decode($data3, true);

      $shippingArr[] = array('id'=>$key, 'rate'=>$freightOptions, 'tax'=> $stateTax);
    }

    //  for every 40 lbs of weight, we register an additional packaging charge of $12. So up to and including 40 lbs in weight is $12. Then 41-80 is 2 x $12 for $24 additional packaging charge. And so on as the weight increase.

    //return $itmFeeDataArr;

    return $shippingArr;
  }

  public function pdfStock($id){
    $pdfURL = $this->endPoint . 'item/' . $id . "/?with[]=customFields.field&with[]=style.customFields&with[]=style.prices&with[]=style.sellingUnit&with[]=style.packageSize&with[]=style.productTypeCode&with[]=style.productDesignCode";
    $data = $this->curlPDFapi($pdfURL);
    return json_decode($data, true);
  }

  public function pdfDyeLots($id) {
    $pdfURL = $this->endPoint . 'item/'.$id. '/inventory';
    $data = $this->curlPDFapi($pdfURL);
    return json_decode($data, true);
  }

  public function pdfSimple($sku) {
    $pdfURL = 'https://distribution.pdfsystems.com/api/simple/item/lookup?sku='.$sku;
    $PDFapiKey = $this->PDFapiKey;
    $data = $this->curlPDFapi($pdfURL);
    return json_decode($data, true);
  }

  public function pdfFullSku($sku) {
    $path = 'item?company=2&item_number=';
    $url = $this->endPoint . $path . $sku;
    $data = $this->cUrlData($url, '', 'itm_number');
    $ret = json_decode($data);
    return $ret;
  }

  public function pdfRep($id) {

    $rep_url = $this->endPoint . 'rep/'.$id . '&with[]=employees.emailAddress';
    $sales_data = $this->curlPDFapi($rep_url);
    $data = json_decode($sales_data, true);

    return $data;

  }

  public function getPdfItemUpdate($itemName) {
    // handle some edge cases with $itemName...
    $nameFix = new Namefix();
    $itemName = $nameFix->urlName($itemName);

    // if(strpos($itemName, '-') !== -1) {
    //   $itemName = str_replace('-', ' ', $itemName);
    // }

    // remove - or -- at any other than the end of item_name

    $suffix = "";


    if (strpos(strrev($itemName), '--') === 0) {

      $suffix = "--";

    }
    else if (strpos(strrev($itemName), '-') === 0) {

      $suffix = "-";

    }
    else {
      $suffix = "";
    }


    // $itemName = rtrim($itemName, "-");

    // $itemName = str_replace('-', ' ', $itemName).$suffix;

    $pdfURL = $this->endPoint . "item?company=2&with[]=style.productCategoryCode&with[]=style.usageCode&with[]=style.collection&with[]=style.vendor&with[]=style&search=" . $itemName;

    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $pdfURL);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
    curl_setopt($ch, CURLOPT_HTTPHEADER, array(
      'X-Api-Key: 296b41434754722c783d392720',
      'X-App-Id: 5'
    ));
    $data_prep = curl_exec($ch);
    curl_close($ch);
    $ret = json_decode($data_prep, true);

    // we need to clean up the data a bit
    foreach ($ret as $key => $value) {

      if(strtolower($value['style']['name']) !== strtolower($itemName)) {
        $checkstyle = str_split(strtolower($value['style']['name']));
        $checkitem = str_split(strtolower($itemName));
        $diff = array_diff($checkstyle, $checkitem);
        if(count($diff) >=1 && $value['style']['name'] != 'Roppongi II') {
          unset($ret[$key]);
        }
      }
    }
    $updateArray = array();
    foreach ($ret as $key => $value){
      $itemId =                      isset($value['id'])                                       ? $value['id']                                      : '';
      $itemName =                    isset($value['style']['name'])                            ? $value['style']['name']                           : '';
      // fix data entry error
      if($itemName == 'alchemy +') {
        $itemName = 'alchemy+';
      }

      $style_additional_description =isset($value['style']['additional_description'])          ? $value['style']['additional_description']         : '';
      $item_additional_description = isset($value['additional_description'])                   ? $value['additional_description']                  : '';
      $internal_comment =            isset($value['style']['comment'])                         ? utf8_encode($value['style']['comment'])           : '';
      $product_category =            isset($value['style']['product_category_code']['name'])   ? $value['style']['product_category_code']['name']  : '';
      $usage =                       isset($value['style']['usage_code']['name'])               ? $value['style']['usage_code']['name']            : '';
      $product_design_code_id =      isset($value['style']['product_design_code_id'])          ? $value['style']['product_design_code_id']         : '';
      $product_type_code_id =        isset($value['style']['product_type_code_id'])            ? $value['style']['product_type_code_id']           : '';

      $collection =                  isset($value['style']['collection']['name']) ? $value['style']['collection']['name'] : '';
      $discontinue =                 isset($value['discontinue_code']['name']) ? $value['discontinue_code']['name'] : '';
      $custom_item =                 isset($value['custom_item']) ? $value['custom_item'] : '';

      $arr1 = array(
        'id_pdf' =>                        $itemId,
        'item_number' =>                   $value['item_number'] ? $value['item_number'] : '',
        'item_name' =>                     utf8_encode($itemName ),
        'style_additional_description' =>  $style_additional_description,
        'item_additional_description' =>   $item_additional_description,
        'mill_description' =>              $value['mill_description'] ? $value['mill_description'] : '',
        'color_name' =>                    $value['color_name'] ? $value['color_name'] : '',
        'width' =>                         $value['style']['width'] ? $value['style']['width'] : '',
        'repeat' =>                        $value['style']['repeat'] ? $value['style']['repeat'] : '',
        'content' =>                       $value['style']['content'] ? $value['style']['content'] : '',
        'tests' =>                         $value['style']['tests'] ? mb_convert_encoding($value['style']['tests'], "UTF-8", "Windows-1252") : '',
        'label_message' =>                 $value['label_message'] ? $value['label_message'] : '',
        'finish' =>                        $value['style']['finish'] ? $value['style']['finish'] : '',
        'country_of_origin1' =>            $value['style']['origin_country'] ? $value['style']['origin_country'] : '',
        'vendor' =>                        $value['style']['vendor']['name'] ? $value['style']['vendor']['name'] : '',
        'date_introduced' =>               $value['style']['date_introduced'] ? $value['style']['date_introduced'] : '',
        'product_category' =>              $product_category,
        'usage' =>                         $usage,
        'collection' =>                    $collection,
        'internal_comment' =>              $internal_comment,
        'weight' =>                        $value['style']['weight'] ? $value['style']['weight'] : '',
        'shipping_weight' =>               $value['style']['shipping_weight'] ? $value['style']['shipping_weight'] : '',
        'freight_code' =>                  $value['style']['freight_code_id'] ? $value['style']['freight_code_id'] : '',
        'large_piece_size' =>              $value['style']['large_piece_size'] ? $value['style']['large_piece_size'] : '',
        'lead_time' =>                     $value['style']['lead_time'] ? $value['style']['lead_time'] : '',
        'discountable' =>                  $value['style']['discountable'] ? $value['style']['discountable'] : '',
        'warehouse_location' =>            $value['warehouse_location'] ? $value['warehouse_location'] : '',
        'inventoried' =>                   $value['style']['inventoried'] ? $value['style']['inventoried'] : '',
        'custom_item' =>                   $custom_item,
        'discontinue_code' =>              $value['discontinue_code_id'] ? $value['discontinue_code_id'] : '',
        'width_cm' =>                      $value['style']['width_cm'] ? $value['style']['width_cm'] : '',
        'grams_sq_m' =>                    $value['style']['grams_per_square_meter'] ?   $value['style']['grams_per_square_meter'] : '',
      );

      if( $itemName =='CUSTOM BINDER' ||
          $itemName =='FOLDER CARD' ||
          $itemName =='PRESENTATION BOARD' ||
          $itemName =='MEMOSET' ||
          $itemName =='PROMO ITEM'){}
      else if( strpos(strtoupper($discontinue), 'DISCONTINUED') !== false ||
                $custom_item == true){}
      else if($collection=='' || $product_category =='' || strtoupper($product_category)=='UNKNOWN' ){}
      else{

                $pdfURL_2 = $this->endPoint . "item/".$itemId."/?with[]=customFields.field&with[]=style.customFields&with[]=style.prices&with[]=style.sellingUnit&with[]=style.packageSize&with[]=style.productTypeCode&with[]=style.productDesignCode";
                $skudata = $this->curlPDFapi($pdfURL_2);

                $data = json_decode($skudata, true);

                $arr2 = array(
                  'product_design' =>                 isset($data['style']['product_design_code']) ? $data['style']['product_design_code']['name'] : '',
                  'product_type' =>                   isset($data['style']['product_type_code']) ? $data['style']['product_type_code']['name'] : '',
                  'selling_unit' =>                   isset($data['style']['selling_unit']) ? $data['style']['selling_unit']['name'] : '',
                  'package_size' =>                   isset($data['style']['package_size']) ? $data['style']['package_size']['name'] : '',
                  'primary_color' =>                  isset($data['primary_color_code']) ? $data['primary_color_code']['name'] : '',
                  'secondary_color' =>                isset($data['secondary_color_code']) ? $data['secondary_color_code']['name'] : '',
                  'wholesale_price' =>                isset($data['style']['prices'][0]['wholesale_price']) ? $data['style']['prices'][0]['wholesale_price'] : '',
                  'retail_price' =>                   isset($data['style']['prices'][0]['retail_price']) ? $data['style']['prices'][0]['retail_price'] : '',
                  'furniture_manufacturer_price' =>   isset($data['style']['prices'][0]['new_furniture_price']) ? $data['style']['prices'][0]['new_furniture_price'] : '',
                  'other_price' =>                    isset($data['style']['prices'][0]['new_other_price']) ? $data['style']['prices'][0]['new_other_price'] : '',
                  'bolt_size' =>                      $data['style']['standard_bolt_size'] ? $data['style']['standard_bolt_size'] : '',
                  'imo_compliant' =>                  isset($data['style']['custom_fields']['IMO Compliant']) ? $data['style']['custom_fields']['IMO Compliant']['current'] : '',
                  'phthalate_free' =>                 isset($data['style']['custom_fields']['Phthalate Free']) ? $data['style']['custom_fields']['Phthalate Free']['current'] : '',
                  'min_order_quantity' =>             isset($data['style']['minimum_order_quantity']) ? $data['style']['minimum_order_quantity'] : '',
                  'min_selling_quantity' =>           isset($data['style']['minimum_selling_quantity']) ? $data['style']['minimum_selling_quantity'] : '',
                  'min_order_increment' =>            isset($data['style']['minimum_order_increment']) ? $data['style']['minimum_order_increment'] : '',
                  'cut_fee' =>                        isset($data['style']['custom_fields']['Cut Fee']) ? $data['style']['custom_fields']['Cut Fee']['current'] : '',
                  'env_ca_01350_cert' =>              isset($data['style']['custom_fields']['Environmental: CA 01350 Certified']) ? $data['style']['custom_fields']['Environmental: CA 01350 Certified']['current'] : '',
                  'env_fsc_certified_paper' =>        isset($data['style']['custom_fields']['Environmental: FSC Certified Paper']) ? $data['style']['custom_fields']['Environmental: FSC Certified Paper']['current'] : '',
                  'env_innvironments_compliant' =>    isset($data['style']['custom_fields']['Environmental: Innvironments Compliant']) ? $data['style']['custom_fields']['Environmental: Innvironments Compliant']['current'] : '',
                  'env_leed_within_500_miles' =>      isset($data['style']['custom_fields']['Environmental: LEED Regional Within 500 Miles Of']) ? $data['style']['custom_fields']['Environmental: LEED Regional Within 500 Miles Of']['current'] : '',
                  'env_phthalate_free_vinyl' =>       isset($data['style']['custom_fields']['Environmental: Phthalate Free Vinyl']) ? $data['style']['custom_fields']['Environmental: Phthalate Free Vinyl']['current'] : '',
                  'env_rapidly_renewable' =>          isset($data['style']['custom_fields']['Environmental: Rapidly Renewable (MR 6)']) ? $data['style']['custom_fields']['Environmental: Rapidly Renewable (MR 6)']['current'] : '',
                  'env_recycled_backing' =>           isset($data['style']['custom_fields']['Environmental: Recycled Backing']) ? $data['style']['custom_fields']['Environmental: Recycled Backing']['current'] : '',
                  'env_recycled_content_by_weight' => isset($data['style']['custom_fields']['Environmental: Recycled Content by Weight (MR 4.1/4.2)']) ? $data['style']['custom_fields']['Environmental: Recycled Content by Weight (MR 4.1/4.2)']['current'] : '',
                  'env_ultralow_voc_vinyl' =>         isset($data['style']['custom_fields']['Environmental: Ultra-Low VOC Vinyl (EQ 4.1)']) ? $data['style']['custom_fields']['Environmental: Ultra-Low VOC Vinyl (EQ 4.1)']['current'] : '',
                  'env_natural_nonsynthetic' =>       isset($data['style']['custom_fields']['Environmental: Uses Natural/Non-Synthetic Fibers']) ? $data['style']['custom_fields']['Environmental: Uses Natural/Non-Synthetic Fibers']['current'] : '',
                  'finish_cork_faux' =>               isset($data['style']['custom_fields']['Finish: Cork/Faux Cork']) ? $data['style']['custom_fields']['Finish: Cork/Faux Cork']['current'] : '',
                  'finish_foiled_metallic' =>         isset($data['style']['custom_fields']['Finish: Foiled/Metallic']) ? $data['style']['custom_fields']['Finish: Foiled/Metallic']['current'] : '',
                  'finish_grasscloth_faux' =>         isset($data['style']['custom_fields']['Finish: Grasscloth/Faux Grasscloth']) ? $data['style']['custom_fields']['Finish: Grasscloth/Faux Grasscloth']['current'] : '',
                  'finish_linen_faux' =>              isset($data['style']['custom_fields']['Finish: Linen/Faux Linen']) ? $data['style']['custom_fields']['Finish: Linen/Faux Linen']['current'] : '',
                  'finish_pleated' =>                 isset($data['style']['custom_fields']['Finish: Pleated']) ? $data['style']['custom_fields']['Finish: Pleated']['current'] : '',
                  'finish_relief' =>                  isset($data['style']['custom_fields']['Finish: Relief']) ? $data['style']['custom_fields']['Finish: Relief']['current'] : '',
                  'finish_silk_faux' =>               isset($data['style']['custom_fields']['Finish: Silk/Faux Silk']) ? $data['style']['custom_fields']['Finish: Silk/Faux Silk']['current'] : '',
                  'finish_wood_faux' =>               isset($data['style']['custom_fields']['Finish: Wood/Faux Wood']) ? $data['style']['custom_fields']['Finish: Wood/Faux Wood']['current'] : '',
                  'flame_astm_e84_class_a' =>         isset($data['style']['custom_fields']['Flame: ASTM E84 - Class A']) ? $data['style']['custom_fields']['Flame: ASTM E84 - Class A']['current'] : '',
                  'flame_cal_117_pass' =>             isset($data['style']['custom_fields']['Flame: Cal 117 - Pass (upholstery only)']) ? $data['style']['custom_fields']['Flame: Cal 117 - Pass (upholstery only)']['current'] : '',
                  'flame_euroclass_b' =>              isset($data['style']['custom_fields']['Flame: Euroclass B']) ? $data['style']['custom_fields']['Flame: Euroclass B']['current'] : '',
                  'flame_imo_compliant' =>            isset($data['style']['custom_fields']['Flame: IMO Compliant']) ? $data['style']['custom_fields']['Flame: IMO Compliant']['current'] : '',
                  'flame_nfpa_260_class_i' =>         isset($data['style']['custom_fields']['Flame: NFPA 260 - Class I (Upholstery Only)']) ? $data['style']['custom_fields']['Flame: NFPA 260 - Class I (Upholstery Only)']['current'] : '',
                  'flame_nfpa_701_pass' =>            isset($data['style']['custom_fields']['Flame: NFPA 701 - Pass (drapery only)']) ? $data['style']['custom_fields']['Flame: NFPA 701 - Pass (drapery only)']['current'] : '',
                  'flame_ufac_class_i' =>             isset($data['style']['custom_fields']['Flame: UFAC - Class I (Upholstery Only)']) ? $data['style']['custom_fields']['Flame: UFAC - Class I (Upholstery Only)']['current'] : '',
                  'tech_antimicrobial' =>             isset($data['style']['custom_fields']['Technical: Anti-Microbial']) ? $data['style']['custom_fields']['Technical: Anti-Microbial']['current'] : '',
                  'tech_doublerubs_wyzenbeek' =>      isset($data['style']['custom_fields']['Technical: Doublerubs/Wyzenbeek']) ? $data['style']['custom_fields']['Technical: Doublerubs/Wyzenbeek']['current'] : '',
                  'tech_ink_resistant_finish' =>      isset($data['style']['custom_fields']['Technical: Ink Resistant Finish (Upholstery Only)']) ? $data['style']['custom_fields']['Technical: Ink Resistant Finish (Upholstery Only)']['current'] : '',
                  'tech_seaming' =>                   isset($data['style']['custom_fields']['Technical: Seaming']) ? $data['style']['custom_fields']['Technical: Seaming']['current'] : '',
                  'tech_type_i' =>                    isset($data['style']['custom_fields']['Technical: Type I']) ? $data['style']['custom_fields']['Technical: Type I']['current'] : '',
                  'tech_type_ii' =>                   isset($data['style']['custom_fields']['Technical: Type II']) ? $data['style']['custom_fields']['Technical: Type II']['current'] : '',
                );

                $pdfURL_3 = $this->endPoint . "item/" . $itemId. "/inventory";

                $data_3 = $this->curlPDFapi($pdfURL_3);

                $taildata = json_decode($data_3, true);

                if(count($data) > 0){}
                else{
                  $data[0]['warehouse_name'] = '';
                }
                if(isset($taildata[0])) {
                  $arr3 = array(
                    'default_warehouse' => $taildata[0]['warehouse_name']
                  );
                } else {
                  $arr3 = array(
                    'default_warehouse' => ''
                  );
                }

                $arr = array_merge($arr1, $arr2, $arr3);
                $updateArray[$key] = $arr;
              }
              if(empty($updateArray && !empty($arr1))) {
                $updateArray[$key] = $arr1;
                $updateArray[$key]['disco'] = true;
              }
            }
            return $updateArray;
          }

    public function getPdfClients()
    {
      $i = 1;
      $total = 500;
      $ret = array();

      for($i=1; $i <= $total; $i++) {
        $pdfURL = $this->endPoint . "customer?company=2&count=$total&page=$i";

        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $pdfURL);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_HTTPHEADER, array(
          'X-Api-Key: 296b41434754722c783d392720',
          'X-App-Id: 5'
        ));

        $data_prep = curl_exec($ch);
        curl_close($ch);
        $data = json_decode($data_prep, true);
        $endpage = count($data);
        $chunk = array();
        foreach($data as $key => $value) {
          $chunk[$i][$key]['wd_id'] = $value['customer_number'];
          if(!empty($value['addresses'][0])) {
            $chunk[$i][$key]['company'] =  $value['addresses'][0]['company_name'];
            $chunk[$i][$key]['street_address'] = $value['addresses'][0]['street'] . ' '.  $value['addresses'][0]['street2'];
            $chunk[$i][$key]['city'] = $value['addresses'][0]['city'];
            $chunk[$i][$key]['state'] = isset($value['addresses'][0]['state']['code']) ? $value['addresses'][0]['state']['code'] : '';
            $chunk[$i][$key]['zip_code'] = $value['addresses'][0]['postal_code'];
            $chunk[$i][$key]['country'] = isset($value['addresses'][0]['state']['country']['code3']) ? $value['addresses'][0]['state']['country']['code3'] : '';
          }
          $ret[] = $chunk[$i][$key];
        }
        if($endpage < $total) { break; }
      }
      return $ret;
    }

    public function pdfCustomerID($wd_id) {
      $pdfURL = $this->endPoint . "customer?company=2&search=%23".$wd_id;
      $ch = curl_init();
      curl_setopt($ch, CURLOPT_URL, $pdfURL);
      curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
      curl_setopt($ch, CURLOPT_HTTPHEADER, array(
        'X-Api-Key: 296b41434754722c783d392720',
        'X-App-Id: 5'
      ));
      $data_prep = curl_exec($ch);
      curl_close($ch);
      $data = json_decode($data_prep, true);

      $count = count($data);
      $element = $count -1;

      $ret[0]=$data[$element]['id'];

      //$companyName = $data[$element]['name'];

      $row = Client::where('wd_id', $wd_id)->get();

      if (count($row) === 0) {
        $ret[0] = NULL;
      }
      else {

        $companyName = $row[0]->company_name;

        if($data[$element]['name'] != $companyName) {
          foreach($data as $key => $datum) {
            if ($datum['name'] == $companyName)  {
              $ret[0] = $datum['id'];
            }
          }
        }
      }

      $ret[1] = $data;

      return $ret;

    }

    public function pdfCustomerCountry($wd_id) {
      $pdfURL = $this->endPoint . "customer?company=2&customer_number=$wd_id";
      $ch = curl_init();
      curl_setopt($ch, CURLOPT_URL, $pdfURL);
      curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
      curl_setopt($ch, CURLOPT_HTTPHEADER, array(
        'X-Api-Key: 296b41434754722c783d392720',
        'X-App-Id: 5'
      ));
      $data_prep = curl_exec($ch);
      curl_close($ch);
      $data = json_decode($data_prep, true);

      $count = count($data);
      $element = $count -1;

      $country=$data[$element]['country_id'];
      $zip_code=$data[$element]['primary_address']['postal_code'];

      return array(
        'country' => $country,
        'zip_code' => $zip_code
      );

    }
    public function pdfInvoice($status, $order_id){

        $endPoint = 'https://distribution.pdfsystems.com';
        $pdfURL = $endPoint . "/export/transaction/$status/" . $order_id;

        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $pdfURL);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_HTTPHEADER, array(
            'X-Api-Key: '.$this->PDFapiKey,
            'X-App-Id: 5'
        ));


          //; filename="invoice'.$id.'.pdf"
        $result = curl_exec($ch);

        curl_close($ch);

        return $result;
    }

    public function createSampleTransaction($user_name = "", $customer_id = "", $rep_id = "", $rep_email = "", $attn = "", $company_name ="", $street = "", $city = "", $state_id = "", $zip_code = "", $country_id = "228", $customer_email = ""){

      $endPoint = 'https://distribution.pdfsystems.com';
      $pdfURL = $endPoint . "/api/sample-transaction";

      $ch = curl_init();
      curl_setopt($ch, CURLOPT_URL, $pdfURL);
      curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
      curl_setopt($ch, CURLOPT_POST, true);
      curl_setopt($ch, CURLOPT_HTTPHEADER, array(
          "Content-Type: application/json",
          'X-Api-Key: '.$this->PDFapiKey,
          'X-App-Id: 5'
      ));

      $data = array(
        "line"=> "2",
        "default_sample_type_code_id"=> "4394",
        "source_key"=> "web",
        "ordered_by"=> $user_name,
        "warehouse_id"=> "120",
        "customer_id"=> $customer_id, // wd_id for user[important]
        "rep_id"=> $rep_id,
        "carrier_id"=> "3",
        "shipping_service_id"=> "2",
        "rep_email"=> $rep_email,
        "customer_attention"=> "Sample Order",
        "ship_to_attention"=> $attn,
        "ship_to_name"=> $company_name,
        "ship_to_street"=> $street,
        "ship_to_street2"=> "",
        "ship_to_city"=> $city,
        "ship_to_state_id"=> $state_id, // zipcode for state
        "ship_to_country_id"=> '228', // zipcode for country
        "ship_to_postal_code"=> $zip_code,
        "sidemark"=> "wallcovering",
        "shipper_number"=> "F12345",
        "customer_email"=> $customer_email,
        "sample_usage"=> "0"
      );

      $payload_data = json_encode($data);


      curl_setopt($ch, CURLOPT_POSTFIELDS, $payload_data);

        //; filename="invoice'.$id.'.pdf"
      $result = curl_exec($ch);

      curl_close($ch);

      return $result;
  }

  // parameter: $id: newly created transaction id, $cartItems: sample items picked into the cart(array('id', 'qty'))

  public function addSampleItem($id, $cartItems){

      $endPoint = 'https://distribution.pdfsystems.com';
      $pdfURL = $endPoint . "/api/sample-transaction-item";

      $ch = curl_init();
      curl_setopt($ch, CURLOPT_URL, $pdfURL);
      curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
      curl_setopt($ch, CURLOPT_POST, true);
      curl_setopt($ch, CURLOPT_HTTPHEADER, array(
          "Content-Type: application/json",
          'X-Api-Key: '.$this->PDFapiKey,
          'X-App-Id: 5'
      ));

      $data = array();
      $data['id'] = $id;
      $data['data'] = array();

      foreach($cartItems as $item) {
        $data['data'][] = array('id'=>$item['id_pdf'], "sampleTypeChosen"=> "4394","quantityOrdered"=> $item['quantity'],"comments"=> "");
      }

      $payload_data = json_encode($data);

      curl_setopt($ch, CURLOPT_POSTFIELDS, $payload_data);

      // filename="invoice'.$id.'.pdf";

      $result = curl_exec($ch);

      curl_close($ch);

      return $result;
  }

  public function addTrancItem($id, $payload){

    $endPoint = 'https://distribution.pdfsystems.com';
    $pdfURL = $endPoint . "/api/transaction-item";

    $payload_data = json_encode($payload);

    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $pdfURL);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
    curl_setopt($ch, CURLOPT_POSTFIELDS, $payload_data);
    curl_setopt($ch, CURLOPT_POST, true);
    curl_setopt($ch, CURLOPT_HTTPHEADER, array(
        "Content-Type: application/json",
        'X-Api-Key: '.$this->PDFapiKey,
        'X-App-Id: 5'
    ));

    $result = curl_exec($ch);

    curl_close($ch);

    return $result;
}

  public function createTransaction($user_name = "", $customer_id = "", $rep_id = "", $rep_email = "", $attn = "", $company_name ="", $street = "", $city = "", $state_id = "", $zip_code = "", $country_id = "228", $customer_email = ""){

    $endPoint = 'https://distribution.pdfsystems.com';
    $pdfURL = $endPoint . "/api/transaction";

    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $pdfURL);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
    curl_setopt($ch, CURLOPT_POST, true);
    curl_setopt($ch, CURLOPT_HTTPHEADER, array(
        "Content-Type: application/json",
        'X-Api-Key: '.$this->PDFapiKey,
        'X-App-Id: 5'
    ));

    $data = array(
      "line"=> "2",
      "source_key"=> "web",
      "ordered_by"=> $user_name,
      "transaction_type_id"=> "1", // 1 = Order, 3 = Reserve
      "warehouse_id"=> "120",
      "customer_id"=> $customer_id, // wd_id for user[important]
      "currency_id"=> "151",
      "rep1_id"=> $rep_id,
      "sale_type_id"=> "1",
      "order_terms_id"=> "1179",
      "carrier_id"=> "3",
      "rep_email"=> $rep_email,
      "customer_attention"=> "Bill To Attention",
      "ship_to_attention"=> $attn,
      "ship_to_name"=> $company_name,
      "ship_to_street"=> $street,
      "ship_to_street2"=> "",
      "ship_to_city"=> $city,
      "ship_to_state_id"=> $state_id, // zipcode for state
      "ship_to_country_id"=> '228', // zipcode for country
      "ship_to_postal_code"=> $zip_code,
      "sidemark"=> "wallcovering",
      "shipper_number"=> "F12345",
      "customer_email"=> $customer_email,
    );

    $payload_data = json_encode($data);

    curl_setopt($ch, CURLOPT_POSTFIELDS, $payload_data);

      //; filename="invoice'.$id.'.pdf"
    $result = curl_exec($ch);

    curl_close($ch);

    return $result;
  }

  public function finalizeTrac($pdfURL, $payload = NULL){
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $pdfURL);
    curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'PUT');
    if(!is_null($payload)) {
      $payload_data = json_encode($payload);
      curl_setopt($ch, CURLOPT_POSTFIELDS, $payload_data);
    }

    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);

    curl_setopt($ch, CURLOPT_HTTPHEADER, array(
        "Content-Type: application/json",
        "X-Api-Key: " . $this->PDFapiKey,
        'X-App-Id: 5'
    ));

    $data = curl_exec($ch);

    curl_close($ch);

    return $data;
  }

  public function getLeatherStatus($itemName) {
    // handle some edge cases with $itemName...
    $nameFix = new Namefix();
    $itemName = $nameFix->urlName($itemName);

    $itemName = rtrim($itemName, "-");

    $itemName = str_replace('-', ' ', $itemName);

    // need correct endpoint
    $pdfURL = $this->endPoint . "item?company=2&with[]=style.customFields&search=" . $itemName;


    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $pdfURL);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
    curl_setopt($ch, CURLOPT_HTTPHEADER, array(
      'X-Api-Key: 296b41434754722c783d392720',
      'X-App-Id: 5'
    ));
    $data_prep = curl_exec($ch);
    curl_close($ch);

    $ret = json_decode($data_prep, true);

    return $ret;

  }

}
