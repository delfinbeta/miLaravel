<?php

namespace App;

use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Foundation\Auth\User as Authenticatable;
// use Illuminate\Support\Facades\DB;
// use Illuminate\Database\Eloquent\Builder;

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

  /**
   * Create a new Eloquent query builder for the model.
   *
   * @param  \Illuminate\Database\Query\Builder   $query
   * @return \Illuminate\Database\Eloquent\Builder|static
   */
  public function newEloquentBuilder($query)
  {
    // return new Builder($query);
    return new UserQuery($query);
  }

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

  public function getNameAttribute() {
    return "{$this->first_name} {$this->last_name}";
  }

  public function setStateAttribute($value)
  {
    $this->attributes['active'] = $value == 'active';
  }

  public function getStateAttribute()
  {
    if($this->active !== null) {
      return $this->active ? 'active' : 'inactive';
    }
  }

  public function scopeFilterBy($query, QueryFilter $filters, array $data) {
    // return (new UserFilter())->applyTo($query, $filters);
    return $filters->applyTo($query, $data);
  }
}
