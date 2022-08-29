<?php

namespace App\Http\Controllers\Voyager;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use TCG\Voyager\Facades\Voyager;
use App\ProductMaster;
use App\ProductList;
use App\ProductDetail;
use App\Client;
use Illuminate\Support\Facades\Auth;
use App\Http\Utils\Namefix;
use App\WebDistribution;
use App\Item;
use Illuminate\Database\Eloquent\Model;
use \DB;

class VoyagerProductMasterController extends VoyagerBaseController
{
  protected function guard() {
    return Auth::guard(app('VoyagerGuard'));
  }
    public function indexWd(Request $request) {
      $this->authorize('update_product_master');
      // if query param set, form has been submitted -> display message
      if(isset($request->query)) {
        $query = $request->query();
        if(isset($query['product'])) {
          Session::flash('message', $query['product'].' updated successfully!');
          Session::flash('alert-class', 'success');
        }
      }

      return view('vendor.voyager.product-master.wd-update');
    }

    public function getproducts()
    {
      $data = ProductMaster::select('item_name')->distinct('item_name')->orderBy('item_name')->get();

      return response()->json($data);
    }

    public function updateWd(Request $request) {
      $this->authorize('update_product_master');
      $itemName = $request->input('product');

      $wd_id = $request->input('client');

      $PDFendPoint = config('constants.value.PDFendPoint');
      $PDFapiKey = config('constants.value.PDFapiKey');

      $wd = new WebDistribution($PDFendPoint, $PDFapiKey);
      if(isset($itemName)) {
        $updateArr = $wd->getPdfItemUpdate($itemName);
      }
      if(isset($wd_id)) {
        $clientArr = $wd->pdfCustomerID($wd_id);
      }

      //dd($updateArr);

      if(empty($updateArr) && isset($itemName)) {
        Session::flash('message', $itemName.' does not exist in Web Distribution.  Deleting from database if exists.');
        Session::flash('alert-class', 'info');

        $delList = ProductList::where('fabricName', $itemName)
          ->update(['discontinued' => 1]);
        $previousUrl = strtok(url()->previous(), '?');

        return redirect()->to($previousUrl);
      }

      if(empty($clientArr) && isset($wd_id)) {
        Session::flash('message', $wd_id.' does not exist in Web Distribution.  Deleting from database if exists.');
        Session::flash('alert-class', 'info');
        Client::where('wd_id', $wd_id)
          ->delete($wd_id);

        $previousUrl = strtok(url()->previous(), '?');

        return redirect()->to($previousUrl);
      }

      if(isset($clientArr)) {
        Client::updateOrInsert(
          ['wd_id' => $wd_id],
          ['updated_at' => date("Y-m-d H:i:s"), 'company_name' => $clientArr[1][0]['primary_address']['company_name'],
          'street_address' => $clientArr[1][0]['primary_address']['street'],
          'city' => $clientArr[1][0]['primary_address']['city'],
          'state' => $clientArr[1][0]['primary_address']['state']['code'],
          'country' => $clientArr[1][0]['primary_address']['country']['code3'],
          'zip_code' => $clientArr[1][0]['primary_address']['postal_code']
          ]
        );

      }
      if(isset($updateArr)) {
        $rawSkus = ProductMaster::select('item_number')->where('item_name', $itemName)->get()->toArray();
        $localSkus = array();
        foreach($rawSkus as $sku) {
          $localSkus[] = $sku['item_number'];
        }

        foreach($updateArr as $key => $array) {
          if(isset($array['disco'])) {
            ProductMaster::where('item_number', $array['item_number'])
              ->delete($array['item_number']);

            Session::flash('message', 'SKU: '.$array['item_number'].' has been discontinued in Web Distribution and deleted from the database.');
            Session::flash('alert-class', 'info');
            unset($updateArr[$key]);
          }
          $remSkus[] = $array['item_number'];
        }

      $delSkus = array_diff($localSkus, $remSkus);

      foreach($delSkus as $delSku) {
        ProductMaster::where('item_number', $delSku)
          ->delete($delSku);
      }

      $updateVals = array();


      foreach($updateArr as $key => $value) {

        if (strpos(strrev($value['item_name']), '-') === 0) {
          $value['item_name'] = rtrim($value['item_name'], '-');
          $value['item_name'] = rtrim($value['item_name'], ' ');
        }
        //dd($value);

        if(empty($value['discontinue_code']) && $value['custom_item'] == false) {
          $updateVals[$key]['item_number'] = $value['item_number'];
          $updateVals[$key]['style_additional_description'] = $value['style_additional_description'];
          $updateVals[$key]['item_additional_description'] = $value['item_additional_description'];
          $updateVals[$key]['mill_description'] = $value['mill_description'];
          $updateVals[$key]['color_name'] = $value['color_name'];
          $updateVals[$key]['width'] = $value['width'];
          $updateVals[$key]['repeat'] = $value['repeat'];
          $updateVals[$key]['content'] = $value['content'];
          $updateVals[$key]['tests'] = $value['tests'];
          $updateVals[$key]['label_message'] = $value['label_message'];
          $updateVals[$key]['finish'] = $value['finish'];
          $updateVals[$key]['country_of_origin1'] = $value['country_of_origin1'];
          $updateVals[$key]['vendor'] = $value['vendor'];
          $updateVals[$key]['date_introduced'] = $value['date_introduced'];
          $updateVals[$key]['product_category'] = $value['product_category'];
          $updateVals[$key]['usage'] = $value['usage'];
          $updateVals[$key]['collection'] = $value['collection'];
          $updateVals[$key]['internal_comment'] = $value['internal_comment'];
          $updateVals[$key]['weight'] = $value['weight'];
          $updateVals[$key]['shipping_weight'] = $value['shipping_weight'];
          $updateVals[$key]['freight_code'] = $value['freight_code'];
          $updateVals[$key]['large_piece_size'] = $value['large_piece_size'];
          $updateVals[$key]['lead_time'] = $value['lead_time'];
          $updateVals[$key]['discountable'] = $value['discountable'];
          $updateVals[$key]['warehouse_location'] = $value['warehouse_location'];
          $updateVals[$key]['inventoried'] = $value['inventoried'];
          $updateVals[$key]['custom_item'] = $value['custom_item'];
          $updateVals[$key]['discontinue_code'] = $value['discontinue_code'];
          $updateVals[$key]['width_cm'] = $value['width_cm'];
          $updateVals[$key]['grams_sq_m'] = $value['grams_sq_m'];
          $updateVals[$key]['product_design'] = $value['product_design'];
          $updateVals[$key]['product_type'] = $value['product_type'];
          $updateVals[$key]['selling_unit'] = $value['selling_unit'];
          $updateVals[$key]['package_size'] = $value['package_size'];
          $updateVals[$key]['primary_color'] = $value['primary_color'];
          $updateVals[$key]['secondary_color'] = $value['secondary_color'];
          $updateVals[$key]['wholesale_price'] = $value['wholesale_price'];
          $updateVals[$key]['retail_price'] = $value['retail_price'];
          $updateVals[$key]['furniture_manufacturer_price'] = $value['furniture_manufacturer_price'];
          $updateVals[$key]['other_price'] = $value['other_price'];
          $updateVals[$key]['bolt_size'] = $value['bolt_size'];
          $updateVals[$key]['imo_compliant'] = $value['imo_compliant'];
          $updateVals[$key]['phthalate_free'] = $value['phthalate_free'];
          $updateVals[$key]['min_order_quantity'] = $value['min_order_quantity'];
          $updateVals[$key]['min_selling_quantity'] = $value['min_selling_quantity'];
          $updateVals[$key]['min_order_increment'] = $value['min_order_increment'];
          $updateVals[$key][ 'cut_fee']= $value['cut_fee'];
          $updateVals[$key]['env_ca_01350_cert'] = $value['env_ca_01350_cert'];
          $updateVals[$key]['env_fsc_certified_paper'] = $value['env_fsc_certified_paper'];
          $updateVals[$key]['env_innvironments_compliant'] = $value['env_innvironments_compliant'];
          $updateVals[$key]['env_leed_within_500_miles'] = $value['env_leed_within_500_miles'];
          $updateVals[$key]['env_phthalate_free_vinyl'] = $value['env_phthalate_free_vinyl'];
          $updateVals[$key]['env_rapidly_renewable'] = $value['env_rapidly_renewable'];
          $updateVals[$key]['env_recycled_backing'] = $value['env_recycled_backing'];
          $updateVals[$key]['env_recycled_content_by_weight'] = $value['env_recycled_content_by_weight'];
          $updateVals[$key]['env_ultralow_voc_vinyl'] = $value['env_ultralow_voc_vinyl'];
          $updateVals[$key]['env_natural_nonsynthetic'] = $value['env_natural_nonsynthetic'];
          $updateVals[$key]['finish_cork_faux']= $value['finish_cork_faux'];
          $updateVals[$key]['finish_foiled_metallic']= $value['finish_foiled_metallic'];
          $updateVals[$key]['finish_grasscloth_faux'] = $value['finish_grasscloth_faux'];
          $updateVals[$key]['finish_linen_faux'] = $value['finish_linen_faux'];
          $updateVals[$key]['finish_pleated'] = $value['finish_pleated'];
          $updateVals[$key]['finish_relief'] = $value['finish_relief'];
          $updateVals[$key]['finish_silk_faux'] = $value['finish_silk_faux'];
          $updateVals[$key]['finish_wood_faux'] = $value['finish_wood_faux'];
          $updateVals[$key]['flame_astm_e84_class_a'] = $value['flame_astm_e84_class_a'];
          $updateVals[$key]['flame_cal_117_pass'] = $value['flame_cal_117_pass'];
          $updateVals[$key]['flame_euroclass_b'] = $value['flame_euroclass_b'];
          $updateVals[$key]['flame_imo_compliant']= $value['flame_imo_compliant'];
          $updateVals[$key]['flame_nfpa_260_class_i'] = $value['flame_nfpa_260_class_i'];
          $updateVals[$key]['flame_nfpa_701_pass'] = $value['flame_nfpa_701_pass'];
          $updateVals[$key]['flame_ufac_class_i'] = $value['flame_ufac_class_i'];
          $updateVals[$key]['tech_antimicrobial'] = $value['tech_antimicrobial'];
          $updateVals[$key]['tech_doublerubs_wyzenbeek'] = $value['tech_doublerubs_wyzenbeek'];
          $updateVals[$key]['tech_ink_resistant_finish'] = $value['tech_ink_resistant_finish'];
          $updateVals[$key]['tech_seaming'] = $value['tech_seaming'];
          $updateVals[$key]['tech_type_i'] = $value['tech_type_i'];
          $updateVals[$key]['tech_type_ii'] = $value['tech_type_ii'];
          $updateVals[$key]['default_warehouse'] = $value['default_warehouse'];
        }
        if(!empty($updateVals[$key])) {
          foreach($updateVals[$key] as $updatekey => $val) {
            DB::table('product_master')
              ->updateOrInsert(
                ['item_name' => $value['item_name'], 'id_pdf' => $value['id_pdf']],
                [$updatekey => $val]
              );
            }
          } // end query loop
        } // end loop through data from WD
      } // end loop for product update
      // Clear previous url from query string
      $previousUrl = strtok(url()->previous(), '?');

      return redirect()->to(
        $previousUrl . '?' . http_build_query(['product' => $itemName])
      );
    } // end method
} // end class
