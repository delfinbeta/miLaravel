@extends('layout')

@section('title', "{$title}")

@section('content')
	<h1>{{ $title }}</h1>
	<hr />
	<div class="card text-white bg-info mb-3" style="max-width: 18rem;">
  <div class="card-header">{{ $user->name }}</div>
	  <div class="card-body">
	    <h5 class="card-title">
	    	@if($user->isAdmin())
	    	Admin
	    	@else
	    	Usuario Común
	    	@endif
	    </h5>
	    <p class="card-text">Correo electrónico: {{ $user->email }}</p>
	    <p class="card-text">Creado: {{ $user->created_at }}</p>
	  </div>
	  <div class="card-footer">
	  	<a href="{{ url('/usuarios/') }}" class="btn btn-outline-light"><< Regresar al Listado</a>
	  </div>
	</div>
@endsection