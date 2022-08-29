<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Laravel\Scout\Searchable;

class ProductList extends Model
{

  use Searchable;
  
  protected $table = "product_list";
  public $timestamps = false;
  protected $primaryKey= 'id';
  public $incrementing = true;  

  public function seenin()
  {
    return $this->belongsToMany(SeenIn::class, 'seenin_pivot', 'product_list_id', 'seen_in_id');
  }
  
  public function productMaster() {
    return $this->hasMany(ProductMaster::class, 'item_name', 'fabricName');
  }
  
}
