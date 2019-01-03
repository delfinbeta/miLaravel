@extends('layout')

@section('title', $title)

@section('content')
	@card
		@slot('header', $title)

		@slot('content')
			@include('shared._errors')

			<form method="POST" action="{{ url('usuarios/nuevo') }}">
				{{-- @include('users._fields') --}}
				{{-- {{ new App\Http\ViewComponents\UserFields($user) }} --}}
				@render('UserFields', ['user' => $user])

				<div class="form-group mt-4">
					<button type="submit" class="btn btn-info">Crear Usuario</button>
				</div>
			</form>
		@endslot
	@endcard
@endsection

@section('sidebar')
	@parent
	<h2>Extensión de Barra Lateral</h2>
@endsection