@extends('layout')

@section('title', $title)

@section('content')
	<h1>{{ $title }}</h1>
	<hr />
	<form method="POST" action="{{ url('usuarios/nuevo') }}">
		{{ csrf_field() }}
		<button type="submit" class="btn btn-info">Crear Usuario</button>
	</form>
@endsection

@section('sidebar')
	@parent
	<h2>Extensión de Barra Lateral</h2>
@endsection