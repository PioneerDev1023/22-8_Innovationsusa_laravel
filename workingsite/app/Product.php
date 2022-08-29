<?php
namespace App;

use Illuminate\Database\Eloquent\Model;
use \DB;

use TeamTNT\TNTSearch\TNTSearch;

// php artisan make:model app\Product
class Product extends Model {
  //protected $table = 'product_master'; // override plural

  private $discontinuedArr = array(
      array('item_name', 'NOT LIKE', '%(%'),
      array('item_name', 'NOT LIKE', '%)%'),
      array('item_name', 'NOT LIKE', '%-1%'),
      array('item_name', 'NOT LIKE', '%-'),
      array('vendor', 'NOT LIKE', '%Unknown%'),
      array('color_name', 'NOT LIKE', 'do not use%'),
      array('color_name', 'NOT LIKE', 'custom color%'),
      array('mill_description', 'NOT LIKE', 'custom%'),
      array('color_name', 'NOT LIKE', 'custom%'),
      array('item_number', 'NOT LIKE', 'sko%'),
      array('item_name', 'NOT LIKE', 'PROMO%'),
      array('color_name', 'NOT LIKE', '' ),
      array('content', 'NOT LIKE', '' ),
      array('collection', 'NOT LIKE', '' ),
      array('product_type', 'NOT LIKE', '' ),
      array('product_category', 'NOT LIKE', '' ),
      array('internal_comment', 'NOT LIKE', '%Discontinued%'),
      array('custom_item', 'NOT LIKE', 1),
      array('discontinue_code', 'NOT LIKE', 1),
      array('style_additional_description', 'NOT LIKE', '%Discontinued%'),
      //array('product_master.collection', 'NOT LIKE', '%2021 Summer%'),
      //array('', '', ''),
  );
  
  public function index($columnArr){
    $query = DB::table('product_master')
             ->leftJoin('product_list', 'product_master.item_name', '=', 'product_list.fabricName')
             ->select($columnArr, DB::raw('count(*) as total'))
             //->where('product_master.product_type', 'NOT LIKE', '%Sheers/Drapery%')
             ->where('product_master.product_type', 'NOT LIKE', '%Faux Leather%')
             ->where('product_master.product_type', 'NOT LIKE', '')
             ->where($this->discontinuedArr)
             ->groupBy('product_master.item_name');
    
    //dd($query->toSql()); 

    $query = $query->simplePaginate(30); // ->get()

    return $query; // ->get()
  }

  public function getProductList($id, $columnArr, $wcArr = array()){

    $query = DB::table('product_master')
                ->leftJoin('product_list', 'product_master.item_name', '=', 'product_list.fabricName');

    if( $id=='wallcoverings' || $id=='all wallcoverings' ){
      $query = $query->select($columnArr)
                ->where('product_master.product_type', '=', $wcArr[0])
                ->orWhere('product_master.product_type', '=', $wcArr[1])
                ->orWhere('product_master.product_type', '=', $wcArr[2])
                ->orWhere('product_master.product_type', '=', $wcArr[3]);
    }
    else{
      $query = $query->select($columnArr)->where('product_master.product_type', '=', $id);
    }

    $query = $query->where($this->discontinuedArr)
              ->groupBy('product_master.item_name') // issue with description
              ->orderBy('product_master.item_name', 'asc')
              ->simplePaginate(30); // ->get()

    return  $query;
  }
  
  public function getProductFilter($id, $filters, $type, $wcArr = array()){

    if ($id=='material'){
          
      $filters = str_replace("-", " ", $filters);
      // specialty -> inspired material
      $filters = str_replace("specialty", "Inspired Material", $filters);
    }else if ($id=='pattern'){      
      $arr = explode("+", $filters);
      //print_r($arr); die();
      $str = '';
      foreach ($arr as $key => $value) {
        if(strtolower($value)=='large-scale-mural'){
          $val_1 = 'large-scale/mural';
        }
        else if(strtolower($value)=='animal-print'){
          $val_1 = 'animal print';
        }
        else{
          $val_1 = str_replace("-", "/", $value);
        }

        $str .= $val_1 . '+';
      }

      $filters = rtrim($str, "+");
    }

    $tnt = new TNTSearch;

    $filters = str_replace("+", ") or (", $filters);
    $filters = str_replace("/", " ", $filters);
    $filters = str_replace("-", " ", $filters);
    $filters = "(".$filters.")";

    $tnt->loadConfig(config("scout.tntsearch"));
    $tnt->selectIndex('individual-filter.index');
    //dd($filters);

    $res = $tnt->searchBoolean($filters, 1000);

    $items = ProductMaster::whereIn('id_pdf', $res['ids'])->where('product_type', 'NOT LIKE', '%Faux Leather%')->where($this->discontinuedArr);
    if($id !== "color") {
      $items = $items->groupBy('item_name');
    }
    $result = $items->orderBy('item_name')->simplePaginate(30);
    // dd($items);   
    return $result;

  }

  public function getAllFilter($filterArr){

    //dd($filterArr);
    $tnt = new TNTSearch;
    $filter = array();
    foreach($filterArr as $key => $filterStr) {      
      
      $filterStr = str_replace(" ", ") or (", $filterStr);

      $filterStr = $this->getSearchString($key, $filterStr);    
      $filterStr = str_replace("/", " ", $filterStr);
      $filterStr = str_replace("-", " ", $filterStr);     
      
      if(strpos($filterStr, ") or (") !== false) {
        $filterStr = "(".$filterStr.")";
      }

      $filter[] = "(".$filterStr.")";
      

    }

    $filterString = implode(' ', $filter);

    //dd($filterString);
    
    $tnt->loadConfig(config("scout.tntsearch"));
    $tnt->selectIndex("individual-filter.index");
    $res = $tnt->searchBoolean($filterString, 1000);

    $items = ProductMaster::whereIn('id_pdf', $res['ids'])->where('product_type', 'NOT LIKE', '%Faux Leather%')->where($this->discontinuedArr)->orderBy('item_name')->orderBy('item_number')->simplePaginate(30);

    if(count($filterArr) == 1 && array_key_exists('collection', $filterArr)) {
      $items = ProductMaster::whereIn('id_pdf', $res['ids'])->where('product_type', 'NOT LIKE', '%Faux Leather%')->where($this->discontinuedArr)->orderBy('item_name')->orderBy('item_number')->groupBy('item_name')->simplePaginate(30);
    }

    //dd($items);
    return $items;
  }

  protected function getSearchString($id, $filter) {

    if ($id=='product_type'){
          
      $filter = str_replace("-", " ", $filter);
      // specialty -> inspired material
      $filter = str_replace("specialty", "Inspired Material", $filter);
    }else if ($id=='product_design'){      
      $arr = explode("+", $filter);
      //print_r($arr); die();
      $str = '';
      foreach ($arr as $key => $value) {
        if(strtolower($value)=='large-scale-mural'){
          $val_1 = 'mural';
        }
        else if(strtolower($value)=='animal-print'){
          $val_1 = 'animal print';
        }
        else{
          $val_1 = str_replace("-", "/", $value);
        }

        $str .= $val_1 . '+';
      }

      $filter = rtrim($str, "+");
    }

    return $filter;
  }
}

/*
  $posts = Posts::join("post_views", "post_views.id_post", "=", "posts.id")
            ->where("created_at", ">=", date("Y-m-d H:i:s", strtotime('-24 hours', time())))
            ->groupBy("posts.id")
            ->orderBy(DB::raw('COUNT(posts.id)'), 'desc')//here its very minute mistake of a paranthesis in Jean Marcos' answer, which results ASC ordering instead of DESC so be careful with this line
            ->get([DB::raw('COUNT(posts.id) as total_views'), 'posts.*']);
*/