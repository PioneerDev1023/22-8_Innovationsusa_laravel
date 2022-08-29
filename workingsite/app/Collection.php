<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use TCG\Voyager\Traits\Resizable;
use Illuminate\Support\Str;

class Collection extends Model
{
  use Resizable;
  protected $table = "collections";
  public $timestamps = true;
  protected $primaryKey= 'id';
  public $incrementing = true;

  public static function boot()
  {
    parent::boot();

    static::saving(function ($model) {
        $model->slug = Str::slug($model->title);
    });
  }

}
