<?php

namespace App;

use Illuminate\Support\Str;
use Illuminate\Support\Facades\Validator;

trait FiltersQueries
{
	public function filterBy(array $filters)
  {
  	// $rules = [
  	// 	'search' => 'filled',
  	// 	'state' => 'in:active,inactive',
  	// 	'role' => 'in:admin,user'
  	// ];
  	$rules = $this->filterRules();

  	// $validator = Validator::make($filters, $rules);
  	$validator = Validator::make(array_intersect_key($filters, $rules), $rules);

  	foreach($validator->valid() as $name => $value) {
  		// $this->{'filterBy'.Str::studly($name)}($value);  // search => Search, first_name => FirstName
  		// $method = 'filterBy'.Str::studly($name);

  		// if(method_exists($this, $method)) {
  		// 	$this->$method($value);
  		// }
  		$this->applyFilter($name, $value);
  	}

  	// foreach($filters as $name => $value) {
  	// 	$this->{'filterBy'.Str::studly($name)}($value);  // search => Search, first_name => FirstName
  	// }

  	// $this->byState($filters['state'])
   //    	 ->byRole($filters['role'])
   //    	 ->search($filters['search']);

    return $this;
  }

  protected function applyFilter($name, $value) 
  {
  	$method = 'filterBy'.Str::studly($name);

		if(method_exists($this, $method)) {
			$this->$method($value);
		} else {
			$this->where($name, $value);
		}
  }
}