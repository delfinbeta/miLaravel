@extends('layout')

@section('title', $title)

@section('content')
	<h1>{{ $title }}</h1>
	<hr />
@endsection

@section('sidebar')
	@parent
	<h2>Extensión de Barra Lateral</h2>
@endsection