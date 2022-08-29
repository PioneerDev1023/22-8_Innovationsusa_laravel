<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Forms extends Model
{

    protected $table = 'forms';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'title', 'mailto', 'hook', 'layout', 'email_template', 'message_success'
    ];
}
