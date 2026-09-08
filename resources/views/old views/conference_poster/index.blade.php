
<form>
  <div class="form-group row">
    <label for="paper_tile" class="col-4 col-form-label">Titulo do artigo</label> 
    <div class="col-8">
      <input id="paper_tile" name="paper_tile" placeholder="Titulo do artigo" type="text" class="form-control" required="required">
    </div>
  </div>
  <div class="form-group row">
    <label for="conference_name" class="col-4 col-form-label">Nome da conferencia</label> 
    <div class="col-8">
      <input id="conference_name" name="conference_name" placeholder="Nome da conferencia " type="text" class="form-control" required="required">
    </div>
  </div>
  <div class="form-group row">
    <label for="presentation_date" class="col-4 col-form-label">Data de apresentação</label> 
    <div class="col-8">
      <input id="presentation_date" name="presentation_date" placeholder="Data de apresentação" type="date" class="form-control" required="required">
    </div>
  </div>
  <div class="form-group row">
    <label for="conference_country" class="col-4 col-form-label">Pais de conferencia</label> 
    <div class="col-8">
      <input id="conference_country" name="conference_country" placeholder="Pais de conferencia " type="text" class="form-control" required="required">
    </div>
  </div>
  <div class="form-group row">
    <label for="city" class="col-4 col-form-label">Cidade</label> 
    <div class="col-8">
      <input id="city" name="city" placeholder="Cidade" type="text" class="form-control" required="required">
    </div>
  </div>
  <div class="form-group row">
    <label for="proceedings_title" class="col-4 col-form-label">Titulo do processo</label> 
    <div class="col-8">
      <input id="proceedings_title" name="proceedings_title" placeholder="Titulo do processo" type="text" required="required" class="form-control">
    </div>
  </div>
  <div class="form-group row">
    <label for="start_page" class="col-4 col-form-label">Página de inicio</label> 
    <div class="col-8">
      <input id="start_page" name="start_page" placeholder="Página de inicio" type="number" class="form-control" required="required">
    </div>
  </div>
  <div class="form-group row">
    <label for="end_page" class="col-4 col-form-label">Página de fim</label> 
    <div class="col-8">
      <input id="end_page" name="end_page" placeholder="Página de fim" type="number" class="form-control" required="required">
    </div>
  </div>
  <div class="form-group row">
    <label class="col-4">Estado</label> 
    <div class="col-8">
      <div class="custom-control custom-checkbox custom-control-inline">
        <input name="status" id="status_0" type="checkbox" class="custom-control-input" value="approved" required="required" aria-describedby="statusHelpBlock" checked="checked"> 
        <label for="status_0" class="custom-control-label">Aprovado</label>
      </div> 
      <span id="statusHelpBlock" class="form-text text-muted">Selecionado o estado deste artigo</span>
    </div>
  </div>
  <div class="form-group row">
    <label for="pub_country" class="col-4 col-form-label">Pais de publicação</label> 
    <div class="col-8">
      <input id="pub_country" name="pub_country" placeholder="Pais de publicação" type="text" class="form-control" required="required">
    </div>
  </div>
  <div class="form-group row">
    <label for="pub_city" class="col-4 col-form-label">Cidade de publicação</label> 
    <div class="col-8">
      <input id="pub_city" name="pub_city" placeholder="Cidade de publicação" type="text" class="form-control" required="required">
    </div>
  </div>
  <div class="form-group row">
    <label for="publisher" class="col-4 col-form-label">Publicador</label> 
    <div class="col-8">
      <input id="publisher" name="publisher" placeholder="Publicador" type="text" class="form-control" required="required">
    </div>
  </div>
  <div class="form-group row">
    <label for="role" class="col-4 col-form-label">Função</label> 
    <div class="col-8">
      <input id="role" name="role" placeholder="Função" type="text" class="form-control" required="required">
    </div>
  </div> 
  <div class="form-group row">
    <div class="offset-4 col-8">
      <button name="submit" type="submit" class="btn btn-primary">Submit</button>
    </div>
  </div>
</form>