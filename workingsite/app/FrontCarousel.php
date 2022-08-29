<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use TCG\Voyager\Traits\Resizable;
use App\Http\Utils\Namefix;

class FrontCarousel extends Model
{
  use Resizable;
  protected $table = "front_carousels";
  public $timestamps = true;
  protected $primaryKey= 'id';
  public $incrementing = true;

  public function mobileImage($value) {
    $nameFix = new Namefix();
    return $nameFix->thumbImageName($value, 'medium');
  }
}
