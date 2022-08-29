<?php
namespace App;

use Illuminate\Database\Eloquent\Model;

use \DB;
use App\PostView;
use TCG\Voyager\Traits\Resizable;
use App\ProductList;

class Item extends Model
{
    use Resizable;
    private $hiddenArr = array(
      array('custom_item', 'NOT LIKE', 1),
      array('discontinue_code', 'NOT LIKE', 1),
      array('style_additional_description', 'NOT LIKE', '%Discontinued%'),
      //array('collection', 'NOT LIKE', '%2021 Summer%'),
    );

    public function getItem($id/*, $columnArr*/){

      $query = DB::table('product_master')
            ->rightJoin('product_list', 'product_master.item_name', '=', 'product_list.fabricName')
            ->orderByRaw('LENGTH(product_master.item_number), product_master.item_number ASC') // natural sort

            //->select($columnArr)
            ->where('product_master.item_name', '=', $id)
            ->where($this->hiddenArr);

      //dd($query->toSql());

      $query = $query->get(); // ->orderBy('item_number', 'ASC')      

      $galleryObj = $this->getGallery($id);

      $dynSeenIns = $this->getSeenIns($id);

      //return $prodObj;
      return array('sku' => $query, 'gallery' => $galleryObj, 'dynSeenIns' => $dynSeenIns);
    }

    public function getItemFilter($id/*, $columnArr*/){


      $prodObj = DB::table('product_master')
            ->where('product_master.item_number', '=', $id)
            ->where($this->hiddenArr)
            ->get();

      return $prodObj;
    }

    public function getSeenins($fabricName) {
      $dynSeenIns = array();
      $product = ProductList::where('fabricName', $fabricName)->first();
      if(!empty($product))
        $dynSeenIns = $product->seenin()->get();
      return $dynSeenIns;
    }

    private function getGallery($id){
      $colArr = array(
        'product_detail.orderid',
        'product_detail.fabricName',
        'product_master.color_name',
        'product_detail.galleryimg',
        'product_detail.firm_link',
        'product_detail.img_link',
        'product_detail.firm_name',
        'product_detail.txt4',
      );

      $query = DB::table('product_detail');

      // left join outputs if this relation exist, otherwise blank
      $query = $query->leftJoin('product_master', 'product_master.item_number', '=', 'product_detail.txt4');

      $query = $query->select($colArr);
            //->select($columnArr)
      $query = $query->where('product_detail.fabricName', '=', $id)
                     ->where('product_detail.discontinued', '=', 0);
      //$query = $query->where($this->hiddenArr);
      $query = $query->orderBy('product_detail.orderid', 'asc');

      //dd($query->toSql());

      $query = $query->get();

      return $query;
    }

    public function getSimilarProducts($id, $type) {
      $productMaster = new ProductMaster();
      $productList = new ProductList();
      if($type == "sku") {        
        if(!$productMaster->similarProductsQuery($id, "sku")) {
          return [];
        }
        return $productMaster->similarProductsQuery($id, "sku")->inRandomOrder()->limit(4)->get();
      } else {        
        if(!$productMaster->similarProductsQuery($id)) {
          return [];
        }        
        return $productMaster->similarProductsQuery($id)->groupBy('item_name')->inRandomOrder()->limit(4)->get();
      }
    }
}
