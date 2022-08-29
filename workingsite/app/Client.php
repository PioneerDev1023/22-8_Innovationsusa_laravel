<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Client extends Model
{
  protected $table = "clients";
  public $timestamps = false;
  protected $primaryKey= 'id';
  public $incrementing = true;

  protected $fillable = [
      'wd_id', 'company_name', 'street_address', 'city', 'state', 'country', 'zip_code',
      'trade_cert', 'website', 'comments'
  ];

  public function users()
  {
      return $this->belongsToMany(User::class);
  }
}
