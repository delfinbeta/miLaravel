<?php

namespace App\Http\Controllers;

use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

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
        //     $users = [];
        // } else {
        //     $users = ['Dayan', 'Carlos', 'Zoraida', 'Gonzalo'];
        // }

        // $users = DB::table('users')->get();
        $users = User::all();

        // return view('users.index')
        //     ->with('title', $title)
        //     ->with('users', $users);

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
        return view('users.new', compact('title'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        return 'Procesando información';
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
        //     return response()->view('errors.404', [], 404);
        // }

        return view('users.show', compact('title', 'user'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $title = 'Editar Usuario';
        return view('users.edit', compact('title', 'id'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
