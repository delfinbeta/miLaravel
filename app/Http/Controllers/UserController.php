<?php

namespace App\Http\Controllers;

use App\{User, Profession, UserProfile, Skill};
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;
use App\Http\Requests\CreateUserRequest;
use App\Http\Forms\UserForm;

class UserController extends Controller
{
  /**
   * Display a listing of the resource.
   *
   * @return \Illuminate\Http\Response
   */
  public function index()
  {
    $title = 'Listado de Usuarios';

    // if(request()->has('empty')) {
    //   $users = [];
    // } else {
    //   $users = ['Dayan', 'Carlos', 'Zoraida', 'Gonzalo'];
    // }

    // $users = DB::table('users')->get();
    $users = User::all();

    // return view('users.index')
    //   ->with('title', $title)
    //   ->with('users', $users);

    return view('users.index', compact('title', 'users'));
  }

  /**
   * Show the form for creating a new resource.
   *
   * @return \Illuminate\Http\Response
   */
  public function create()
  {
    $title = "Crear Nuevo Usuario";

    // $professions = Profession::orderBy('title', 'ASC')->get();
    // $skills = Skill::orderBy('name', 'ASC')->get();
    // $roles = trans('users.roles');

    // $user = new User;

    // return view('users.new', compact('title', 'professions', 'skills', 'roles', 'user'));

    // return view('users.new', compact('title', 'user'))
    //   ->with($this->formsData());

    return new UserForm('users.new', $title, new User);
  }

  /**
   * Store a newly created resource in storage.
   *
   * @param  \Illuminate\Http\Request  $request
   * @return \Illuminate\Http\Response
   */
  public function store(CreateUserRequest $request)
  {
    // $data = $request->validate([
    //   'name' => 'required',
    //   'email' => 'required|email|unique:users,email',
    //   'password' => 'required|min:6',
    //   'bio' => 'required',
    //   'twitter' => 'url'
    // ], [
    //   'name.required' => 'El campo nombre es obligatorio',
    //   'email.required' => 'El campo email es obligatorio',
    //   'email.email' => 'Email inválido',
    //   'email.unique' => 'Email ya registrado',
    //   'password.required' => 'El campo contraseña es obligatorio',
    //   'password.min' => 'La contraseña debe contener mínimo 6 caracteres'
    // ]);

    // User::createUser($data);
    $request->createUser();

    return redirect()->route('users.list');
  }

  /**
   * Display the specified resource.
   *
   * @param  int  $id
   * @return \Illuminate\Http\Response
   */
  public function show(User $user)
  {
    $title = 'Detalle de Usuario';

    // $user = User::findOrFail($id);

    // $user = User::find($id);

    // if($user == null) {
    //   return response()->view('errors.404', [], 404);
    // }

    return view('users.show', compact('title', 'user'));
  }

  /**
   * Show the form for editing the specified resource.
   *
   * @param  int  $id
   * @return \Illuminate\Http\Response
   */
  public function edit(User $user)
  {
    $title = 'Editar Usuario';

    // return view('users.edit', compact('title', 'user'))
    //   ->with($this->formsData());

    return new UserForm('users.edit', $title, $user);
  }

  // protected function formsData()
  // {
  //   return [
  //     'professions' => Profession::orderBy('title', 'ASC')->get(),
  //     'skills' => Skill::orderBy('name', 'ASC')->get(),
  //     'roles' => trans('users.roles'),
  //   ];
  // }

  /**
   * Update the specified resource in storage.
   *
   * @param  \Illuminate\Http\Request  $request
   * @param  int  $id
   * @return \Illuminate\Http\Response
   */
  public function update(Request $request, User $user)
  {
    // $data = request()->validate([
    //   'name' => 'required',
    //   'email' => 'required|email|unique:users,email,'.$user->id,
    //   'password' => ''
    // ]);

    $data = request()->validate([
      'name' => 'required',
      'email' => ['required', 'email', Rule::unique('users')->ignore($user->id)],
      'password' => '',
      'role' => '',
      'profession_id' => '',
      'bio' => '',
      'twitter' => '',
      'skills' => ''
    ]);

    if($data['password'] != null) {
      $data['password'] = bcrypt($data['password']);
    } else {
      unset($data['password']);
    }

    // $user->update($data);
    $user->fill($data);

    $user->role = $data['role'];
    $user->save();
    $user->profile->update($data);

    $user->skills()->sync($data['skills'] ?? []);

    return redirect()->route('users.show', ['user' => $user]);
  }

  /**
   * Remove the specified resource from storage.
   *
   * @param  int  $id
   * @return \Illuminate\Http\Response
   */
  public function destroy(User $user)
  {
    $user->delete();

    return redirect()->route('users.list');
  }
}
