<?php

namespace App;

use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Foundation\Auth\User as Authenticatable;
// use Illuminate\Support\Facades\DB;

class User extends Authenticatable
{
  use SoftDeletes;
  use Notifiable;

  /**
   * The attributes that are mass assignable.
   *
   * @var array
   */
  protected $guarded = [];

  // protected $perPage = 5;

  /**
   * The attributes that should be hidden for arrays.
   *
   * @var array
   */
  protected $hidden = [
    'password', 'remember_token',
  ];

  protected $casts = [
    //
  ];

  public function profile() {
    return $this->hasOne(UserProfile::class)->withDefault();
  }

  public function team() {
    return $this->belongsTo(Team::class)->withDefault();
  }

  public function skills() {
    return $this->belongsToMany(Skill::class, 'user_skill');
  }

  public function isAdmin() {
    return $this->role === 'admin';
  }

  public static function findByEmail($email) {
    return static::where(compact('email'))->first();
  }

  public function scopeSearch($query, $search)
  {
    if(empty($search)) { return; }

    $query->where(function($query) use ($search) {
      // $query->where(DB::raw('CONCAT(first_name, " ", last_name)'), 'like', "%{$search}%")
      $query->whereRaw('CONCAT(first_name, " ", last_name) like ?', "%{$search}%")
            ->orWhere('email', 'like', "%{$search}%")
            ->orWhereHas('team', function($query) use ($search) {
              $query->where('name', 'like', "%{$search}%");
            });
    });
  }

  public function getNameAttribute() {
    return "{$this->first_name} {$this->last_name}";
  }
}
