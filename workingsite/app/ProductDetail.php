<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use TCG\Voyager\Traits\Resizable;
use Illuminate\Support\Str;

class ProductDetail extends Model
{

  use Resizable;
  protected $table = "product_detail";
  public $timestamps = false;
  protected $primaryKey= 'id';
  public $incrementing = true;


}
