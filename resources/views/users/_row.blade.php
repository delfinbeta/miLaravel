<tr>
  <td rowspan="2">{{ $user->id }}</td>
  <th scope="row">
  	{{ $user->name }}
  	<span class="note">{{ $user->team->name }}</span>
	</th>
  <td>{{ $user->email }}</td>
  <td>{{ $user->role }}</td>
  <td>
  	<span class="note">Registro: {{ $user->created_at->format('d/m/Y') }}</span>
  	<span class="note">Último login: {{ $user->created_at->format('d/m/Y') }}</span>
  </td>
  <td class="text-right">
  	@if($user->trashed())
  	<form method="POST" action="{{ route('users.delete', $user) }}">
  		{{ method_field('DELETE') }}
  		{{ csrf_field() }}
  		<button type="submit" class="btn btn-danger"><i class="fas fa-times"></i></button>
  	</form>
  	@else
  	<form method="POST" action="{{ route('users.trash', $user) }}">
  		{{ method_field('PATCH') }}
  		{{ csrf_field() }}
  		<a href="{{ route('users.show', $user) }}" class="btn btn-outline-info" role="button"><i class="fas fa-search-plus"></i></a>
  		<a href="{{ route('users.edit', $user) }}" class="btn btn-outline-warning" role="button"><i class="fas fa-pen"></i></a>
  		<button type="submit" class="btn btn-outline-danger"><i class="fas fa-trash-alt"></i></button>
  	</form>
  	@endif
  </td>
</tr>
<tr class="skills">
	<td colspan="1"><span class="note">{{ optional($user->profile->profession)->title }}</span></td>
	<td colspan="4"><span class="note">{{ $user->skills->implode('name', ', ') ?: 'Sin Habilidades :(' }}</span></td>
</tr>