<?php

namespace App;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use App\Role;

class User extends \TCG\Voyager\Models\User
{
    use Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'wd_id', 'name', 'email', 'password', 'role_id', 'newsletter'
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    public function client()
    {

      return $this->hasOne(Client::class, 'wd_id', 'wd_id');

    }
    public function role()
    {

      return $this->hasOne(Role::class, 'id', 'role_id');
    }

    public function assignRole(Role $role)
    {
      return $this->save($role);
    }
    public function scopeAuthor($query) {
      $role_author = Role::where('name', 'Author')->first();
      $role_staff = Role::where('name', 'Staff')->first();
      return $query->where('role_id', $role_author->id)->orWhere('role_id', $role_staff->id);
    }
}
