<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

use TCG\Voyager\Traits\Resizable;
use Illuminate\Support\Str;

class Video extends Model
{
    // make images resizable in template
    use Resizable;
    protected $table = "videos";
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
    public function getRouteKeyName()
    {
      return 'slug';
    }
}
