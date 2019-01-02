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

		<?php if($errors->has('name')) {
						$error1 = 'is-invalid';
						$msj_error1 = $errors->first('name');
					} else {
						$error1 = '';
						$msj_error1 = '';
					}

					if($errors->has('email')) {
						$error2 = 'is-invalid';
						$msj_error2 = $errors->first('email');
					} else {
						$error2 = '';
						$msj_error2 = '';
					}

					if($errors->has('password')) {
						$error3 = 'is-invalid';
						$msj_error3 = $errors->first('password');
					} else {
						$error3 = '';
						$msj_error3 = '';
					} ?>

		<div class="form-group">
			<label for="name">Nombre:</label>
			<input type="text" name="name" id="name" class="form-control {{ $error1 }}" value="{{ old('name') }}" />
			<div class="invalid-feedback">{{ $msj_error1 }}</div>
		</div>
		<div class="form-group">
			<label for="email">Email:</label>
			<input type="email" name="email" id="email" class="form-control {{ $error2 }}" value="{{ old('email') }}" />
			<div class="invalid-feedback">{{ $msj_error2 }}</div>
		</div>
		<div class="form-group">
			<label for="password">Contraseña:</label>
			<input type="password" name="password" id="password" class="form-control {{ $error3 }}" />
			<div class="invalid-feedback">{{ $msj_error3 }}</div>
		</div>
		<div class="form-group">
			<label for="profession_id">Profesión</label>
			<select name="profession_id" id="profession_id" class="form-control">
				<option value="">Selecciona una Profesión</option>
				@foreach($professions as $profession)
				<option value="{{ $profession->id }}" {{ old('profession_id') == $profession->id ? 'selected' : '' }}>{{ $profession->title }}</option>
				@endforeach
			</select>
		</div>
		<div class="form-group">
			<label for="bio">Bio:</label>
			<textarea name="bio" id="bio" class="form-control">{{ old('bio') }}</textarea>
		</div>
		<div class="form-group">
			<label for="twitter">Twitter:</label>
			<input type="text" name="twitter" id="twitter" class="form-control" placeholder="https://twitter.com/usuario" value="{{ old('twitter') }}" />
		</div>
		<h5>Habilidades</h5>
		@foreach($skills as $skill)
		<div class="form-check form-check-inline">
		  <input class="form-check-input" 
		  	type="checkbox" 
		  	name="skills[{{ $skill->id }}]" 
		  	id="skill_{{ $skill->id }}" 
		  	value="{{ $skill->id }}" 
		  	{{ old("skills.{$skill->id}") ? 'checked' : '' }} />
		  <label class="form-check-label" for="skill_{{ $skill->id }}">{{ $skill->name }}</label>
		</div>
		@endforeach
		<h5 class="mt-3">Rol</h5>
		@foreach($roles as $role => $name)
		<div class="form-check form-check-inline">
		  <input class="form-check-input" 
		  	type="radio" 
		  	name="role" 
		  	id="role_{{ $role }}" 
		  	value="{{ $role }}" 
		  	{{ old('role') == $role ? 'checked' : '' }} />
		  <label class="form-check-label" for="role_{{ $role }}">{{ $name }}</label>
		</div>
		@endforeach
		<div class="form-group mt-4">
			<button type="submit" class="btn btn-info">Crear Usuario</button>
		</div>
	</form>
@endsection

@section('sidebar')
	@parent
	<h2>Extensión de Barra Lateral</h2>
@endsection