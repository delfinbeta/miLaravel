<?php

namespace App\Http\Requests;

use App\Role;
use App\User;
use Illuminate\Support\Facades\DB;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class CreateUserRequest extends FormRequest
{
  /**
   * Determine if the user is authorized to make this request.
   *
   * @return bool
   */
  public function authorize()
  {
    return true;
  }

  /**
   * Get the validation rules that apply to the request.
   *
   * @return array
   */
  public function rules()
  {
    return [
      'first_name' => 'required',
      'last_name' => 'required',
      'email' => ['required', 'email', 'unique:users,email'],
      'password' => 'required|min:6',
      'role' => [
        'nullable',
        Rule::in(Role::getList())
      ],
      'profession_id' => [
        'nullable', 'present', 
        Rule::exists('professions', 'id')->whereNull('deleted_at')
      ],
      'bio' => 'required',
      'twitter' => ['nullable', 'url'],
      'skills' => [
        'array',
        Rule::exists('skills', 'id')
      ],
      'state' => [
        Rule::in(['active', 'inactive'])
      ]
      // 'active' => ''
    ];
  }

  public function messages() {
    return [
      'first_name.required' => 'El campo nombre es obligatorio',
      'last_name.required' => 'El campo apellido es obligatorio',
      'email.required' => 'El campo email es obligatorio',
      'email.email' => 'Email inválido',
      'email.unique' => 'Email ya registrado',
      'password.required' => 'El campo contraseña es obligatorio',
      'password.min' => 'La contraseña debe contener mínimo 6 caracteres'
    ];
  }

  public function createUser() {
    DB::transaction(function() {
      // $user = User::create([
      //   'name' => $data['name'],
      //   'email' => $data['email'],
      //   'password' => bcrypt($data['password']),
      //   'role' => $data['role']
      // ]);
      $user = User::create([
        'first_name' => $this->first_name,
        'last_name' => $this->last_name,
        'email' => $this->email,
        'password' => bcrypt($this->password),
        'role' => $this->role ?? 'user',
        // 'active' => $this->active
        // 'active' => $this->state == 'active'
        'state' => $this->state
      ]);

      $user->profile()->create([
        'bio' => $this->bio,
        'twitter' => $this->twitter,
        'profession_id' => $this->profession_id
      ]);

      $user->skills()->attach($this->skills);
    });
  }
}
