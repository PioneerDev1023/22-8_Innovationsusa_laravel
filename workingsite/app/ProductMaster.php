<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Laravel\Scout\Searchable;

class ProductMaster extends Model
{

  use Searchable;

  protected $table = "product_master";
  public $timestamps = false;
  protected $primaryKey= 'id_pdf';
  public $incrementing = false;

  // protected $fillable = ['item_name', 'item_additional_description', 'style_additional_description'];
  protected $guarded = [];

  public function productList()
  {
    return $this->belongsTo(ProductList::class, 'fabricName', 'item_name');
  }

  public function getScoutKey()
  {
      return $this->item_name;
  }

  /**
   * Get the key name used to index the model.
   *
   * @return mixed
   */
  public function getScoutKeyName()
  {
      return 'item_name';
  }

  public function toSearchableArray()
  {
    // @Top@ ***Attention!!!***
    // first use the first array to create index file and rename individual-filter.index
    // and then comment the first array and use second array and create index file and rename all-wallcovering.index


    // first array
    //  $array =  [
    //    'id_pdf' => $this->id_pdf,
    //    'color' => $this->primary_color,
    //    'material' => $this->product_type,
    //    'pattern' => $this->product_design == "Concrete/Plaster" ? "Stone ".$this->product_design : $this->product_design,
    //    'texture' => $this->getColumns(),
    //    'collection' => $this->collection,
    //    'environment' => $this->getColumns('env'),
    //    'content' => $this->content,
    //  ];

    // second array
    $array =  [
        'id_pdf' => $this->id_pdf,
        'item_number' => $this->item_number,
        'item' => $this->item_name,
        'color' => $this->primary_color,
        'item-color' => $this->color_name,
        'material' => $this->product_type,
        'pattern' => $this->product_design == "Concrete/Plaster" ? "Stone ".$this->product_design : $this->product_design,
        'texture' => $this->getColumns(),
        'collection' => $this->collection,
        'category' => $this->product_category,
        'content' => $this->content,
      //  'environment' => $this->getColumns('env'),
        'euroclass' => $this->flame_euroclass_b == "1" ? "euroclass" : "",
        "tech_type_ii" => $this->tech_type_ii == "1" ? "type ii typeii type 2 type2" : "",
    ];

    return $array;
  }

  public function getColumns($type = 'texture') {

    $textureColumns = array(
      'finish_cork_faux'=> "finish cork faux cork",
      'finish_foiled_metallic'=> "finish foiled metallic foiled",
      'finish_grasscloth_faux'=> "finish grasscloth faux grasscloth",
      'finish_linen_faux'=> "finish linen faux linen",
      'finish_pleated'=> "finish pleated",
      'finish_relief'=> "finish relief",
      'finish_silk_faux'=> "finish silk faux silk",
      'finish_wood_faux'=> "finish wood faux wood woodgrain"
    );

    // @Top@ ***Attention!!!***---modification! Please modify the "environment" to proper value
    $envColumns = array(
      'env_ca_01350_cert' => "environment leed 01350 cert",
      'env_fsc_certified_paper' => "environment leed certified fsc within 500 miles",
      'env_leed_within_500_miles' => "environment leed",
      'env_rapidly_renewable' => "environment leed rapidly renewable",
      'env_recycled_content_by_weight' => "environment leed recycled content by weight",
      'env_ultralow_voc_vinyl' => "environment leed ultralow voc vinyl",
      'env_natural_nonsynthetic' => "environment leed natural nonsynthetic"
    );

    $texture = array();

    $Columns = $textureColumns;
    if($type == 'env') {
      $Columns = $envColumns;
    }

    foreach($Columns as $key => $column) {
      if($this->$key == "1") {
        $texture[] = $column;
      }
    }

    return implode(" ", $texture);

  }

  public function similarProductsQuery($id, $type = "item") {
    $row = null;
    if($type == "sku") {
      $row = $this->where('item_number', "=", $id)->first();
    } else {
      $row = $this->where('item_name', "=", $id)->first();
    }

    $columnArr = array(
      'finish_cork_faux',
      'finish_foiled_metallic',
      'finish_grasscloth_faux',
      'finish_linen_faux',
      'finish_pleated',
      'finish_relief',
      'finish_silk_faux',
      'finish_wood_faux'
    );

    $productType =  "";
    $textures = array();
    $color = "";
    $color1 = "";
    $selfProductCol = "item_name";

    if ($row) {

      $color = $row->primary_color;
      $color1 = $row->color_name;
      $productType = $row->product_type;

      foreach($columnArr as $column) {
        $data = $row->$column;
        if($data == '1') {
          $textures[] = $column;
        }
      }

      if($type == "sku") {
        $selfProductCol = "item_number";
      }

    } else {
      return false;
    }

    $productsDefault = $this->where('product_type', 'NOT LIKE', '%Faux Leather%')->where($this->discontinuedArr)->where($selfProductCol, "<>", $id);
    $products = $productsDefault;

    if($type == "sku") {
      $products = $products->where(function ($query) use ($color1, $color){
        $query->where('color_name', "LIKE", "%".$color1."%")
              ->orWhere('primary_color', "LIKE", "%".$color."%");
      });
    }

    // foreach($textures as $index => $texture) {

    //   $products = $products->where($texture, "=", '1');r
    // }
    if($textures) {
      $texture = $textures[0];

      $products = $products->where($texture, "=", '1');
    } else {
      $products = $products->where('product_type', "=", $productType);
    }

    return $products;
  }
}
