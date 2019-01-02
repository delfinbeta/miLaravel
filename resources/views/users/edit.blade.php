@extends('layout')

@section('title', $title)

@section('content')
	<h1>{{ $title }}</h1>
	<hr />

	@include('shared._errors')
	
	<form method="POST" action="{{ url("usuarios/{$user->id}") }}">
		{{ method_field('PUT') }}
		@include('users._fields')

		<div class="form-group mt-4">
			<button type="submit" class="btn btn-info">Actualizar Usuario</button>
		</div>
	</form>
@endsection

@section('sidebar')
	@parent
	<h2>Extensión de Barra Lateral</h2>
@endsection