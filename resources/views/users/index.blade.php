@extends('layout')

@section('title', $title)

@section('content')
	<h1>{{ $title }}</h1>
	<hr />

	@if($users->isNotEmpty())
	<table class="table table-striped table-dark">
	  <thead>
	    <tr>
	      <th scope="col">Nombre</th>
	      <th scope="col">Email</th>
	      <th scope="col" colspan="3">Opciones</th>
	    </tr>
	  </thead>
	  <tbody>
	  	@foreach($users as $user)
	    <tr>
	      <td>{{ $user->name }}</td>
	      <td>{{ $user->email }}</td>
	      <td>
	      	<a href="{{ route('users.show', $user) }}" class="btn btn-outline-info" role="button">Ver más +</a>
	      </td>
	      <td>
	      	<a href="{{ route('users.edit', $user) }}" class="btn btn-outline-warning" role="button">Editar</a>
	      </td>
	      <td>
	      	<form method="POST" action="{{ route('users.delete', $user) }}">
	      		{{ method_field('DELETE') }}
	      		{{ csrf_field() }}
	      		<button type="submit" class="btn btn-outline-danger">Eliminar</button>
	      	</form>
	      </td>
	    </tr>
	    @endforeach
	  </tbody>
	</table>
	@else
	<div class="alert alert-danger">
		No hay usuarios registrados
	</div>
	@endif
@endsection

@section('sidebar')
	<h2>Barra Lateral Sobreescrita</h2>
@endsection