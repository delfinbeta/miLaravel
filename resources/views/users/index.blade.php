@extends('layout')

@section('title', $title)

@section('content')
	<h1>{{ $title }}</h1>
	<hr />

	<ul>
		@forelse($users as $user)
		<li>{{ $user->name }}</li>
		@empty
		<li>No hay usuarios registrados</li>
		@endforelse
	</ul>
@endsection

@section('sidebar')
	<h2>Barra Lateral Sobreescrita</h2>
@endsection