<?php

namespace App;

use Illuminate\Database\Eloquent\Builder;
// use Illuminate\Support\Str;
// use Illuminate\Support\Facades\Validator;

class UserQuery extends Builder
{
	use FiltersQueries;

	public function findByEmail($email) {
    return static::where(compact('email'))->first();
  }

  protected function filterRules() {
  	return [
  		'search' => 'filled',
  		'state' => 'in:active,inactive',
  		'role' => 'in:admin,user'
  	];
  }

  public function filterBySearch($search)
  {
    // if(empty($search)) { return $this; }

    return $this->whereRaw('CONCAT(first_name, " ", last_name) like ?', "%{$search}%")
          			->orWhere('email', 'like', "%{$search}%")
          			->orWhereHas('team', function($query) use ($search) {
          				$query->where('name', 'like', "%{$search}%");
          			});
  }

  public function filterByState($state)
  {
    // if($state == 'active') {
    //   return $this->where('active', true);
    // }

    // if($state == 'inactive') {
    //   return $this->where('active', false);
    // }

    // return $this;
    return $this->where('active', $state == 'active');
  }

  // public function filterByRole($role)
  // {
  //   if(in_array($role, ['user', 'admin'])) {
  //     $this->where('role', $role);
  //   }

  //   return $this;
  // }
}