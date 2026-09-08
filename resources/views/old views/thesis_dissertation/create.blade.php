<form action="{{ route('thesis_dissertation.store') }}" method="POST" enctype="multipart/form-data">
  @csrf
  <div class="form-group row" style="padding:8px;">
    <label for="title" class="col-4 col-form-label">Título</label>
    <div class="col-8">
      <input id="title" name="title" placeholder="Título" type="text" class="form-control" required="required">
    </div>
  </div>
  <div class="form-group row" style="padding:8px;">
    <label for="volumes_number" class="col-4 col-form-label">Numero de volumes</label>
    <div class="col-8">
      <input id="volumes_number" name="volumes_number" placeholder="Numero de volumes" type="text" required="required" class="form-control">
    </div>
  </div>
  <div class="form-group row" style="padding:8px;">
    <label for="institutions_list" class="col-4 col-form-label">Lista das instituições</label>
    <div class="col-8">
      <input id="institutions_list" name="institutions_list" placeholder="Lista das instituições" type="text" class="form-control">
    </div>
  </div>
  <div class="form-group row" style="padding:8px;">
    <label for="institutions_list" class="col-4 col-form-label">Instituições</label>
    <div class="col-8">
      <select id="institutions_list" name="institutions_list" class="custom-select" aria-describedby="institutions_listHelpBlock" required="required">
        <option value="isla">ISLA</option>
        <option value="uporto">UPorto</option>
        <option value="ulisboa">ULisboa</option>
        <option value="iscap">ISCAP</option>
        <option value="lusofona">Lusófona</option>
      </select>
      <span id="institutions_listHelpBlock" class="form-text text-muted">Selecione a instituição na qual a tese foi apresentada</span>
    </div>
  </div>
  <div class="form-group row" style="padding:8px;">
    <label for="degree_type" class="col-4 col-form-label">Grau académico</label>
    <div class="col-8">
      <select id="degree_type" name="degree_type" class="custom-select" aria-describedby="degree_typeHelpBlock" required="required">
        <option value="master">Mestrado</option>
        <option value="doctor">Doutoramento</option>
        <option value="post_doc">Pós Doutoramento</option>
      </select>
      <span id="degree_typeHelpBlock" class="form-text text-muted">Escolha o grau académico na qual esta tese se enquadra</span>
    </div>
  </div>
  <div class="form-group row" style="padding:8px;">
    <label for="classification" class="col-4 col-form-label">Classificação final</label>
    <div class="col-8">
      <input id="classification" min="0" max="20" name="classification" placeholder="Classificação final" type="number" class="form-control" aria-describedby="classificationHelpBlock" required="required">
      <span id="classificationHelpBlock" class="form-text text-muted">Escolha a classificação final, num grau de 0-20</span>
    </div>
  </div>
  <div class="form-group row" style="padding:8px;">
    <label for="date" class="col-4 col-form-label">Data da defesa</label>
    <div class="col-8">
      <input id="date" name="date" placeholder="Data da defesa" type="date" class="form-control" required="required">
    </div>
  </div>
  <div class="form-group row" style="padding:8px;">
    <label for="url" class="col-4 col-form-label">Link da tese</label>
    <div class="col-8">
      <input id="url" name="url" placeholder="Link da tese" type="text" class="form-control" required="required" aria-describedby="urlHelpBlock">
      <span id="urlHelpBlock" class="form-text text-muted">Indique o alojamento desta tese</span>
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
    <div class="offset-4 col-8">
      <button name="submit" type="submit" class="btn btn-primary">Submit</button>
    </div>
  </div>
</form>