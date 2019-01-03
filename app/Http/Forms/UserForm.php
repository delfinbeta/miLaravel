<?php

namespace App\Http\Forms;

use App\{User, Profession, Skill};
use Illuminate\Contracts\Support\Responsable;

class UserForm implements Responsable
{
	private $view;
	private $title;
	private $user;

	public function __construct($view, $title, User $user)
	{
		$this->view = $view;
		$this->title = $title;
		$this->user = $user;
	}

	/**
	 * Create an HTTP response that represents the object.
	 *
	 * @param  \Illuminate\Http\Request $request
	 * @return \Illuminate\Http\Response
	 */
	public function toResponse($request)
	{
		// TODO: Implement toResponse() method.
		return view($this->view, [
			'title' => $this->title,
			'user' => $this->user,
			'professions' => Profession::orderBy('title', 'ASC')->get(),
      'skills' => Skill::orderBy('name', 'ASC')->get(),
      'roles' => trans('users.roles'),
		]);
	}
}