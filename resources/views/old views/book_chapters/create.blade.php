 <form action="{{ route('book_chapters.store') }}" method="POST" enctype="multipart/form-data">
 @csrf 
 <div class="form-group row">
    <label for="book_title" class="col-4 col-form-label">Titulo do livro</label> 
    <div class="col-8">
      <input id="book_title" name="book_title" placeholder="titulo do livro" type="text" class="form-control" required="required">
    </div>
  </div>
  <div class="form-group row">
    <label for="chapter_title" class="col-4 col-form-label">Titulo do capitulo</label> 
    <div class="col-8">
      <input id="chapter_title" name="chapter_title" placeholder="titulo do capitulo" type="text" class="form-control" required="required">
    </div>
  </div>
  <div class="form-group row">
    <label for="edition" class="col-4 col-form-label">Edição</label> 
    <div class="col-8">
      <input id="edition" name="edition" placeholder="Edição" type="text" class="form-control" required="required">
    </div>
  </div>
  <div class="form-group row">
    <label for="start_page" class="col-4 col-form-label">Página de Inicio</label> 
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
    <label for="volume" class="col-4 col-form-label">Volume</label> 
    <div class="col-8">
      <input id="volume" name="volume" placeholder="Volume" type="text" class="form-control" required="required">
    </div>
  </div>
  <div class="form-group row">
    <label class="col-4">Referenciado</label> 
    <div class="col-8">
      <div class="custom-control custom-checkbox custom-control-inline">
        <input name="refreed" id="refreed_0" type="checkbox" class="custom-control-input" value="refreed" aria-describedby="refreedHelpBlock" checked="checked" required="required"> 
        <label for="refreed_0" class="custom-control-label">Referenciado?</label>
      </div> 
      <span id="refreedHelpBlock" class="form-text text-muted">Selecione se o livro está referenciado</span>
    </div>
  </div>
  <div class="form-group row">
    <label for="country" class="col-4 col-form-label">País</label> 
    <div class="col-8">
      <input id="country" name="country" placeholder="país" type="text" class="form-control" required="required">
    </div>
  </div>
  <div class="form-group row">
    <label for="city" class="col-4 col-form-label">Cidade</label> 
    <div class="col-8">
      <input id="city" name="city" placeholder="cidade" type="text" class="form-control" required="required">
    </div>
  </div>
  <div class="form-group row">
    <label for="publisher" class="col-4 col-form-label">Publicadora</label> 
    <div class="col-8">
      <input id="publisher" name="publisher" placeholder="publicadora" type="text" class="form-control" required="required">
    </div>
  </div>
  <div class="form-group row">
    <label for="role" class="col-4 col-form-label">Função</label> 
    <div class="col-8">
      <input id="role" name="role" placeholder="função" type="text" class="form-control">
    </div>
  </div>
  <div class="form-group row">
    <label for="url" class="col-4 col-form-label">Link</label> 
    <div class="col-8">
      <input id="url" name="url" placeholder="Link" type="text" class="form-control" required="required" aria-describedby="urlHelpBlock"> 
      <span id="urlHelpBlock" class="form-text text-muted">Insira o link do livro</span>
    </div>
  </div> 
  <div class="form-group row">
    <div class="offset-4 col-8">
      <button name="submit" type="submit" class="btn btn-primary">Submit</button>
    </div>
  </div>
</form>
