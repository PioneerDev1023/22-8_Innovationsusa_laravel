<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Rep extends Model
{
  protected $table = "rep";
  public $timestamps = false;
  protected $primaryKey= 'rep_id';
  public $incrementing = true;
}
