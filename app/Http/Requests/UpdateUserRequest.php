<?php

namespace App\Http\Requests;

use App\{User, Role};
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateUserRequest extends FormRequest
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
      'email' => [
        'required', 'email', 
        Rule::unique('users')->ignore($this->user)
      ],
      'password' => 'min:6',
      'role' => [
        'required',
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

  public function updateUser(User $user) {
    $user->fill([
      'name' => $this->name,
      'email' => $this->email
    ]);

    if($this->password != null) {
      $user->password = bcrypt($this->password);
    }

    $user->role = $this->role;
    $user->save();

    $user->profile->update([
      'bio' => $this->bio,
      'twitter' => $this->twitter,
      'profession_id' => $this->profession_id
    ]);

    $user->skills()->sync($this->skills ?: []);
  }
}
