<form action="{{ route('conference_poster.store') }}" method="POST" enctype="multipart/form-data">
@csrf  
<div class="form-group row" style="padding:8px;">
    <label for="paper_tile" class="col-4 col-form-label">Titulo do artigo</label> 
    <div class="col-8">
      <input id="paper_tile" name="paper_tile" placeholder="Titulo do artigo" type="text" required="required" class="form-control">
    </div>
  </div>
  <div class="form-group row" style="padding:8px;">
    <label for="conference_name" class="col-4 col-form-label">Nome da conferencia</label> 
    <div class="col-8">
      <input id="conference_name" name="conference_name" placeholder="Nome da conferencia " type="text" required="required" class="form-control">
    </div>
  </div>
  <div class="form-group row" style="padding:8px;">
    <label for="presentation_date" class="col-4 col-form-label">Data de apresentação</label> 
    <div class="col-8">
      <input id="presentation_date" name="presentation_date" placeholder="Data de apresentação" type="date" required="required" class="form-control">
    </div>
  </div>
  <div class="form-group row" style="padding:8px;">
    <label for="status" class="col-4 col-form-label">Status</label> 
    <div class="col-8">
      <select id="status" name="status" class="form-select" aria-describedby="statusHelpBlock" required="required">
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
    <label for="role" class="col-4 col-form-label">Função</label> 
    <div class="col-8">
      <input id="role" name="role" placeholder="Função" type="text" required="required" class="form-control">
    </div>
  </div> 
  <div class="form-group row" style="padding:8px;">
    <div class="offset-4 col-8">
      <button name="submit" type="submit" class="btn btn-primary">Submit</button>
    </div>
  </div>
</form>