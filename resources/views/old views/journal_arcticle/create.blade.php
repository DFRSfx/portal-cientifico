<form action="{{ route('journal_arcticle.store') }}" method="POST" enctype="multipart/form-data">
@csrf
  <div class="form-group row" style="padding:8px;">
    <label for="arcticle_title" class="col-4 col-form-label">Titulo do artigo</label> 
    <div class="col-8">
      <input id="arcticle_title" name="arcticle_title" placeholder="Titulo do artigo" type="text" class="form-control" required="required">
    </div>
  </div>
  <div class="form-group row" style="padding:8px;">
    <label for="journal" class="col-4 col-form-label">Journal</label> 
    <div class="col-8">
      <input id="journal" name="journal" placeholder="Journal" type="text" class="form-control" required="required">
    </div>
  </div>
  <div class="form-group row" style="padding:8px;">
    <label for="volume" class="col-4 col-form-label">Volume</label> 
    <div class="col-8">
      <input id="volume" name="volume" placeholder="Volume" type="text" class="form-control">
    </div>
  </div>
  <div class="form-group row" style="padding:8px;">
    <label for="issue" class="col-4 col-form-label">Edição</label> 
    <div class="col-8">
      <input id="issue" name="issue" placeholder="Edição" type="text" class="form-control" required="required">
    </div>
  </div>
  <div class="form-group row" style="padding:8px;">
    <label for="start_page" class="col-4 col-form-label">Página de inicio</label> 
    <div class="col-8">
      <input id="start_page" name="start_page" placeholder="Página de inicio" type="number" class="form-control" required="required">
    </div>
  </div>
  <div class="form-group row" style="padding:8px;">
    <label for="end_page" class="col-4 col-form-label">Página de fim</label> 
    <div class="col-8">
      <input id="end_page" name="end_page" placeholder="Página de fim" type="number" class="form-control" required="required">
    </div>
  </div>
  <div class="form-group row" style="padding:8px;">
    <label for="city" class="col-4 col-form-label">Cidade</label> 
    <div class="col-8">
      <input id="city" name="city" placeholder="Cidade" type="text" class="form-control" required="required">
    </div>
  </div>
  <div class="form-group row" style="padding:8px;">
    <label for="publisher" class="col-4 col-form-label">Publicador</label> 
    <div class="col-8">
      <input id="publisher" name="publisher" placeholder="Publicador" type="text" class="form-control" required="required">
    </div>
  </div>
  <div class="form-group row" style="padding:8px;">
    <label for="status" class="col-4 col-form-label">Status</label> 
    <div class="col-8">
      <select id="status" class="form-select" name="status"  aria-describedby="statusHelpBlock" required="required">
        <option value="accepted">Accepted</option>
        <option value="inpress">In press</option>
        <option value="published">published</option>
        <option value="submitted">Sumitted</option>
        <option value="under_revision">Under revision</option>
      </select> 
      <span id="statusHelpBlock" class="form-text text-muted">Select the status of the publication</span>
    </div>
  </div> 
  <div class="form-group row" style="padding:8px;">
    <label class="col-4">Referenciado?</label> 
    <div class="col-8">
      <div class="custom-control custom-checkbox custom-control-inline">
        <input name="refreed" id="refreed_0" type="checkbox" class="custom-control-input" value="true" required="required" aria-describedby="refreedHelpBlock"> 
        <label for="refreed_0" class="custom-control-label">Sim</label>
      </div> 
      <span id="refreedHelpBlock" class="form-text text-muted">Indique se está referenciado noutra publicação</span>
    </div>
  </div>
  <div class="form-group row" style="padding:8px;">
    <label class="col-4">É de acesso ao publico?</label> 
    <div class="col-8">
      <div class="custom-control custom-checkbox custom-control-inline">
        <input name="open_access" id="open_access_0" type="checkbox" class="custom-control-input" value="true" aria-describedby="open_accessHelpBlock" required="required"> 
        <label for="open_access_0" class="custom-control-label">Sim</label>
      </div> 
      <span id="open_accessHelpBlock" class="form-text text-muted">Indique se é de acesso ao público</span>
    </div>
  </div>
  <div class="form-group row" style="padding:8px;">
    <label class="col-4">Estado</label> 
    <div class="col-8">
      <div class="custom-control custom-checkbox custom-control-inline">
        <input name="status" id="status_0" type="checkbox" class="custom-control-input" value="true" required="required" aria-describedby="statusHelpBlock"> 
        <label for="status_0" class="custom-control-label">Publicado</label>
      </div> 
      <span id="statusHelpBlock" class="form-text text-muted">Indique se está publicado</span>
    </div>
  </div>
  <div class="form-group row" style="padding:8px;">
    <label for="date" class="col-4 col-form-label">Data de publicação</label> 
    <div class="col-8">
      <input id="date" name="date" placeholder="Data de publicação" type="date" class="form-control" required="required">
    </div>
  </div>
  <div class="form-group row" style="padding:8px;">
    <label for="country" class="col-4 col-form-label">País</label> 
    <div class="col-8">
      <input id="country" name="country" placeholder="País" type="text" class="form-control" required="required">
    </div>
  </div>
  <div class="form-group row" style="padding:8px;">
    <label for="role" class="col-4 col-form-label">Função</label> 
    <div class="col-8">
      <input id="role" name="role" placeholder="Função" type="text" class="form-control" required="required">
    </div>
  </div>
  <div class="form-group row" style="padding:8px;">
    <label for="url" class="col-4 col-form-label">Link da publicação</label> 
    <div class="col-8">
      <input id="url" name="url" placeholder="Link da publicação" type="text" class="form-control" required="required">
    </div>
  </div> 
  <div class="form-group row" style="padding:8px;">
    <div class="offset-4 col-8">
      <button name="submit" type="submit" class="btn btn-primary">Submit</button>
    </div>
  </div>
</form>