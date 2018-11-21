@extends('layout')

@section('title', $title)

@section('content')
	<h1>{{ $title }}</h1>
	<hr />
	<form method="POST" action="{{ url('usuarios/nuevo') }}">
		{{ csrf_field() }}

		<div class="form-group">
			<label for="name">Nombre:</label>
			<input type="text" name="name" id="name" class="form-control">
		</div>
		<div class="form-group">
			<label for="email">Email:</label>
			<input type="email" name="email" id="email" class="form-control">
		</div>
		<div class="form-group">
			<label for="password">Contraseña:</label>
			<input type="password" name="password" id="password" class="form-control">
		</div>

		<button type="submit" class="btn btn-info">Crear Usuario</button>
	</form>
@endsection

@section('sidebar')
	@parent
	<h2>Extensión de Barra Lateral</h2>
@endsection
