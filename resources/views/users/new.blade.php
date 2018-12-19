@extends('layout')

@section('title', $title)

@section('content')
	<h1>{{ $title }}</h1>
	<hr />

	@if($errors->any())
	<div class="alert alert-danger">
		<ul>
			@foreach($errors->all() as $error)
			<li>{{ $error }}</li>
			@endforeach
		</ul>
	</div>
	@endif

	<form method="POST" action="{{ url('usuarios/nuevo') }}">
		{{ csrf_field() }}

		<div class="form-group">
			<?php if($errors->has('name')) {
							$error1 = 'is-invalid';
							$msj_error1 = $errors->first('name');
						} else {
							$error1 = '';
							$msj_error1 = '';
						} ?>

			<label for="name">Nombre:</label>
			<input type="text" name="name" id="name" class="form-control {{ $error1 }}" value="{{ old('name') }}" />
			<div class="invalid-feedback">{{ $msj_error1 }}</div>
		</div>
		<div class="form-group">
			<label for="email">Email:</label>
			<input type="email" name="email" id="email" class="form-control" value="{{ old('email') }}" />
		</div>
		<div class="form-group">
			<label for="password">Contraseña:</label>
			<input type="password" name="password" id="password" class="form-control" />
		</div>

		<button type="submit" class="btn btn-info">Crear Usuario</button>
	</form>
@endsection

@section('sidebar')
	@parent
	<h2>Extensión de Barra Lateral</h2>
@endsection