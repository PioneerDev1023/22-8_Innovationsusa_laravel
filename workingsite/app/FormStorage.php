<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use App\Forms;

class FormStorage extends Model
{

    protected $table = 'form_storage';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'form_id', 'data',
    ];

    public function setFormDataAttribute($value)
    {
        $this->attributes['data'] = serialize($value);
    }

    public function storeFormData($data) {
      // reusable function that should be invoked any time a form whose
      // data we want to save is submitted.
    }

    public function getFormDataAttribute($value)
    {
        return unserialize($value);
    }
    
}
