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
      'first_name' => 'required',
      'last_name' => 'required',
      'email' => [
        'required', 'email', 
        Rule::unique('users')->ignore($this->user)
      ],
      'password' => '',
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
      ],
      'state' => [
        Rule::in(['active', 'inactive'])
      ]
    ];
  }

  public function updateUser(User $user) {
    $user->fill([
      'first_name' => $this->first_name,
      'last_name' => $this->last_name,
      'email' => $this->email,
      'state' => $this->state
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
