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
      'name' => 'required',
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
      ]
    ];
  }

  public function messages() {
    return [
      'name.required' => 'El campo nombre es obligatorio',
      'email.required' => 'El campo email es obligatorio',
      'email.email' => 'Email inválido',
      'email.unique' => 'Email ya registrado',
      'password.required' => 'El campo contraseña es obligatorio',
      'password.min' => 'La contraseña debe contener mínimo 6 caracteres'
    ];
  }

  public function createUser() {
    DB::transaction(function() {
      $data = $this->validated();

      // $user = User::create([
      //   'name' => $data['name'],
      //   'email' => $data['email'],
      //   'password' => bcrypt($data['password']),
      //   'role' => $data['role']
      // ]);
      $user = new User([
        'name' => $data['name'],
        'email' => $data['email'],
        'password' => bcrypt($data['password'])
      ]);

      $user->role = $data['role'] ?? 'user';

      $user->save();

      $user->profile()->create([
        'bio' => $data['bio'],
        'twitter' => $data['twitter'] ?? null,
        'profession_id' => $data['profession_id'] ?? null
      ]);

      $user->skills()->attach($data['skills'] ?? []);
    });
  }
}
