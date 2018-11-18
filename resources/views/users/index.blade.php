@extends('layout')

@section('title', $title)

@section('content')
	<h1>{{ $title }}</h1>
	<hr />

	<table class="table table-striped table-dark">
	  <thead>
	    <tr>
	      <th scope="col">Nombre</th>
	      <th scope="col">Email</th>
	      <th scope="col">Opciones</th>
	    </tr>
	  </thead>
	  <tbody>
	  	@forelse($users as $user)
	    <tr>
	      <td>{{ $user->name }}</td>
	      <td>{{ $user->email }}</td>
	      <td>
	      	<a href="{{ route('users.show', ['id' => $user->id]) }}" class="btn btn-outline-info" role="button">Ver más +</a>
	      </td>
	    </tr>
	    @empty
	    <tr>
	    	<td colspan="3">No hay usuarios registrados</td>
	    </tr>
	    @endforelse
	  </tbody>
	</table>
@endsection

@section('sidebar')
	<h2>Barra Lateral Sobreescrita</h2>
@endsection