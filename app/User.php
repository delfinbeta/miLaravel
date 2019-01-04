<?php

namespace App;

use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Foundation\Auth\User as Authenticatable;

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

  public function profession() {
    return $this->belongsTo(Profession::class);
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
}
