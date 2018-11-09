@extends('layout')

@section('title', "Usuario {$id}")

@section('content')
	<h1>{{ $title }}</h1>
	<hr />
	<p>Mostrando detalle del usuario: {{ $id }}</p>
@endsection