@extends('layout')

@section('title', $title)

@section('content')
	@component('shared._card')
		@slot('header', $title)

		@slot('content')
			@include('shared._errors')

			<form method="POST" action="{{ url("usuarios/{$user->id}") }}">
				{{ method_field('PUT') }}
				@render('UserFields', compact('user'))
				{{-- @include('users._fields') --}}

				<div class="form-group mt-4">
					<button type="submit" class="btn btn-info">Actualizar Usuario</button>
				</div>
			</form>
		@endslot
	@endcomponent
@endsection

@section('sidebar')
	@parent
	<h2>Extensión de Barra Lateral</h2>
@endsection