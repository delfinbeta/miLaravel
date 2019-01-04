@extends('layout')

@section('title', $title)

@section('content')
	<h1>{{ $title }}</h1>
	<hr />

	@if($professions->isNotEmpty())
	<table class="table table-striped table-dark">
	  <thead>
	    <tr>
	      <th scope="col">#</th>
	      <th scope="col">Título</th>
	      <th scope="col">Perfiles</th>
	      <th scope="col" colspan="2">Acciones</th>
	    </tr>
	  </thead>
	  <tbody>
	  	@foreach($professions as $profession)
	    <tr>
	      <td>{{ $profession->id }}</td>
	      <td>{{ $profession->title }}</td>
	      <td>{{ $profession->profiles_count }}</td>
	      <td>
	      	<a href="#" class="btn btn-outline-warning" role="button">Editar</a>
	      </td>
	      <td>
	      	<form method="POST" action="{{ route('professions.delete', $profession) }}">
	      		@method('DELETE')
	      		@csrf
	      		<button type="submit" class="btn btn-outline-danger" {{ ($profession->profiles_count == 0) ? '' : 'disabled' }}>Eliminar</button>
	      	</form>
	      </td>
	    </tr>
	    @endforeach
	  </tbody>
	</table>
	@else
	<div class="alert alert-danger">
		No hay Profesiones registradas
	</div>
	@endif
@endsection

@section('sidebar')
	@parent
@endsection