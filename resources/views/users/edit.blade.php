@extends('layout')

@section('title', $title)

@section('content')
	<h1>{{ $title }}</h1>
	<hr />
	<p>Editar Usuario {{ $id }}</p>
@endsection