<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use TCG\Voyager\Traits\Resizable;
use Illuminate\Support\Str;

class PressRelease extends Model
{
  use Resizable;
  protected $table = "press_releases";
  public $timestamps = true;
  protected $primaryKey= 'id';
  public $incrementing = true;

  public static function boot()
  {
    parent::boot();

    static::saving(function ($model) {
      if(!isset($model->slug)) {
        $model->slug = Str::slug($model->title);
      }
    });
  }

}
