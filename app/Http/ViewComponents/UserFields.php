<?php

namespace App\Http\ViewComponents;

use App\{User, Profession, Skill};
use Illuminate\Contracts\Support\Htmlable;

class UserFields implements Htmlable
{
	/**
	 * @var User
	 */
	private $user;

	public function __construct(User $user) {
		$this->user = $user;
	}

	// public function __toString() {
	// 	return $this->toHtml();
	// }

	/**
	 * Get content as a string of HTML.
	 *
	 * @return string
	 */
	public function toHtml() {
		// TODO: Implement toHtml() method.
		// $professions = Profession::orderBy('title', 'ASC')->get();
  //   $skills = Skill::orderBy('name', 'ASC')->get();
  //   $roles = trans('users.roles');

    // return view('users._fields', compact('professions', 'skills', 'roles'))
    // 	->with('user', $this->user)
    // 	->render();

    return view('users._fields', [
    	'professions' => Profession::orderBy('title', 'ASC')->get(),
    	'skills' => Skill::orderBy('name', 'ASC')->get(),
    	'roles' => trans('users.roles'),
    	'user' => $this->user,
    ]);
	}
}