<?php

namespace App;

use Illuminate\Notifications\Notifiable;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Foundation\Auth\User as Authenticatable;

class User extends Authenticatable
{
  use Notifiable;

  /**
   * The attributes that are mass assignable.
   *
   * @var array
   */
  protected $fillable = [
    'name', 'email', 'password'
  ];

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
    return $this->hasOne(UserProfile::class);
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
