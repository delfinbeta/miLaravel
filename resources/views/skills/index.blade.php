@extends('layout')

@section('title', $title)

@section('content')
	<h1>{{ $title }}</h1>
	<hr />

	@if($skills->isNotEmpty())
	<table class="table table-striped table-dark">
	  <thead>
	    <tr>
	      <th scope="col">#</th>
	      <th scope="col">Título</th>
	      <th scope="col" colspan="2">Acciones</th>
	    </tr>
	  </thead>
	  <tbody>
	  	@foreach($skills as $skill)
	    <tr>
	      <td>{{ $skill->id }}</td>
	      <td>{{ $skill->name }}</td>
	      <td>
	      	<a href="#" class="btn btn-outline-warning" role="button">Editar</a>
	      </td>
	      <td>
	      	{{-- <form method="POST" action="{{ route('skills.delete', $skill) }}">
	      		{{ method_field('DELETE') }}
	      		{{ csrf_field() }}
	      		<button type="submit" class="btn btn-outline-danger">Eliminar</button>
	      	</form> --}}
	      	---
	      </td>
	    </tr>
	    @endforeach
	  </tbody>
	</table>
	@else
	<div class="alert alert-danger">
		No hay Profesiones registradas
	</div>
	@endif
@endsection

@section('sidebar')
	@parent
@endsection