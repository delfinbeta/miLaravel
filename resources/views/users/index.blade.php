@extends('layout2')

@section('title', $title)

@section('content')
	<div class="d-flex justify-content-between align-items-end">
    <h1 class="pb-1">{{ $title }}</h1>
    <p>
    	@if($showFilters)
      <a href="{{ route('users.trashed') }}" class="btn btn-danger">Ver Papelera <i class="fas fa-trash-alt"></i></a>
      <a href="{{ route('users.create') }}" class="btn btn-success">Nuevo usuario <i class="fas fa-plus"></i></a>
      @else
      <a href="{{ route('users.list') }}" class="btn btn-success"><i class="fas fa-arrow-left"></i> Volver al Listado de Usuarios</a>
      @endif
    </p>
  </div>
	<hr style="margin-top: 0;" />
	
	{{-- @include('users._filters') --}}
	@includeWhen($showFilters, 'users._filters')

	@if($users->isNotEmpty())
	<p>Viendo página {{ $users->currentPage() }} de {{ $users->lastPage() }}</p>
  <div class="table-responsive-lg">
		<table class="table table-striped table-dark">
		  <thead>
		    <tr>
		      <th scope="col"># <i class="fas fa-caret-down"></i><i class="fas fa-caret-up"></i></th>
          <th scope="col" class="sort-desc">Nombre <i class="fas fa-caret-down"></i><i class="fas fa-caret-up"></i></th>
          <th scope="col">Email <i class="fas fa-caret-down"></i><i class="fas fa-caret-up"></i></th>
          <th scope="col">Rol <i class="fas fa-caret-down"></i><i class="fas fa-caret-up"></i></th>
          <th scope="col">Fechas <i class="fas fa-caret-down"></i><i class="fas fa-caret-up"></i></th>
          <th scope="col" class="text-right th-actions">Acciones</th>
		    </tr>
		  </thead>
		  <tbody>
		  	@each('users._row', $users, 'user')
		  </tbody>
		</table>
	</div>
	{{ $users->links() }}
	@else
	<div class="alert alert-danger">
		No hay usuarios registrados
	</div>
	@endif
@endsection

@section('sidebar')
	<h2>Barra Lateral Sobreescrita</h2>
@endsection