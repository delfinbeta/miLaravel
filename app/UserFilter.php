<?php

namespace App;

use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;

class UserFilter extends QueryFilter
{
	public function rules(): array
	{
		return [
			'search' => 'filled',
			'state' => 'in:active,inactive',
			'role' => 'in:admin,user',
      'skills' => 'array|exists:skills,id',
      'from' => 'date_format:d/m/Y',
      'to' => 'date_format:d/m/Y'
		];
	}

  public function search($query, $search)
  {
    return $query->where(function($query) use ($search) {
      $query->whereRaw('CONCAT(first_name, " ", last_name) like ?', "%{$search}%")
            ->orWhere('email', 'like', "%{$search}%")
            ->orWhereHas('team', function($query) use ($search) {
              $query->where('name', 'like', "%{$search}%");
            });
    });
  }

  public function state($query, $state)
  {
    return $query->where('active', $state == 'active');
  }

  public function skills($query, $skills)
  {
    // $query->whereHas('skills', function($query) use ($skills) {
    //   $query->whereIn('skills.id', $skills)
    //         ->havingRaw('COUNT(skills.id) = ?', [count($skills)]);
    // });
    $subquery = DB::table('user_skill')
      ->selectRaw('COUNT(user_skill.id)')
      ->whereRaw('user_skill.user_id = users.id')
      ->whereIn('skill_id', $skills);

    // $query->addBinding($subquery->getBindings());
    // $query->where(DB::raw("({$subquery->toSql()})"), count($skills));
    $query->whereQuery($subquery, count($skills));
  }

  public function from($query, $date)
  {
    $date = Carbon::createFromFormat('d/m/Y', $date);

    $query->whereDate('created_at', '>=', $date);
  }

  public function to($query, $date)
  {
    $date = Carbon::createFromFormat('d/m/Y', $date);

    $query->whereDate('created_at', '<=', $date);
  }
}