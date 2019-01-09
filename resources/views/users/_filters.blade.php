<form method="get" action="{{ url('usuarios') }}">
  {{-- <div class="row row-filters">
    <div class="col-md-12">
      @foreach(['' => 'Todos', 'with_team' => 'Con Equipo', 'without_team' => 'Sin Equipo'] as $value => $text)
      <div class="form-check form-check-inline">
        <input class="form-check-input" type="radio" name="team" id="team_{{ $value ?: 'all' }}" value="{{ $value }}" {{ $value == request('team') ? 'checked' : '' }} />
        <label class="form-check-label" for="team_{{ $value ?: 'all' }}">{{ $text }}</label>
      </div>
      @endforeach
    </div>
  </div> --}}
  <div class="row row-filters">
    <div class="col-md-12">
      @foreach($states as $value => $text)
      <div class="form-check form-check-inline">
        <input class="form-check-input" type="radio" name="state" id="state_{{ $value }}" value="{{ $value }}" {{ $value == request('state') ? 'checked' : '' }} />
        <label class="form-check-label" for="state_{{ $value }}">{{ $text }}</label>
      </div>
      @endforeach
    </div>
  </div>
  <div class="row row-filters">
    <div class="col-md-6">
      <div class="form-inline form-search">
        {{-- <div class="input-group">
          <input type="search" name="search" class="form-control form-control-sm" placeholder="Buscar..." value="{{ request('search') }}" />
          <div class="input-group-append">
            <button type="submit" class="btn btn-secondary btn-sm"><i class="fas fa-search"></i></button>
          </div>
        </div> --}}
        <input type="text" name="search" class="form-control form-control-sm" placeholder="Buscar..." value="{{ request('search') }}" />
        
        {{-- <div class="btn-group">
          <button type="button" class="btn btn-sm btn-secondary dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">Rol</button>
          <div class="dropdown-menu">
            <a class="dropdown-item" href="#">Todos</a>
            <a class="dropdown-item" href="#">Usuario</a>
            <a class="dropdown-item" href="#">Admin</a>
          </div>
        </div> --}}
        <div class="btn-group">
          <select name="role" id="role" class="form-control select-field">
            @foreach($roles as $value => $text)
            <option value="{{ $value }}" {{ request('role') == $value ? 'selected' : '' }}>{{ $text }}</option>
            @endforeach
          </select>
        </div>
        
        <div class="btn-group drop-skills">
          <button type="button" class="btn btn-sm btn-secondary dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">Habilidades</button>
          <div class="drop-menu skills-list">
            @foreach($skills as $skill)
            <div class="form-group form-check">
              <input type="checkbox" class="form-check-input" name="skills[]" id="skill_{{ $skill->id }}" value="{{ $skill->id }}" {{ in_array($skill->id, $checkedSkills) ? 'checked' : '' }} />
              <label class="form-check-label" for="skill_{{ $skill->id }}">{{ $skill->name }}</label>
            </div>
            @endforeach
          </div>
        </div>
      </div>
    </div>
    <div class="col-md-6 text-right">
      <div class="form-inline form-dates">
        <label for="date_start" class="form-label-sm">Fecha</label>&nbsp;
        <div class="input-group">
          <input type="text" class="form-control form-control-sm" name="date_start" id="date_start" placeholder="Desde">
          <div class="input-group-append">
            <button type="button" class="btn btn-secondary btn-sm"><i class="far fa-calendar-alt"></i></button>
          </div>
        </div>
        <div class="input-group">
          <input type="text" class="form-control form-control-sm" name="date_start" id="date_start" placeholder="Hasta">
          <div class="input-group-append">
            <button type="button" class="btn btn-secondary btn-sm"><i class="far fa-calendar-alt"></i></button>
          </div>
        </div> 
        &nbsp;
        <button type="submit" class="btn btn-sm btn-primary">Filtrar</button>
      </div>            
    </div>
  </div>
</form>
<hr />