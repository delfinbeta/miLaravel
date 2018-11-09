@extends('layout')

@section('title', $title)

@section('content')
	<h1>{{ $title }}</h1>
	<hr />

	@if(!empty($users))
	<ul>
		@foreach($users as $user)
		<li>{{ $user }}</li>
		@endforeach
	</ul>
	@else
	<p>No hay usuarios registrados</p>
	@endif
@endsection

@section('sidebar')
	<h2>Barra Lateral Sobreescrita</h2>
@endsection