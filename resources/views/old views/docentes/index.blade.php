@extends('index')
@section('content')

<button class="btn btn-primary" type="button" data-bs-toggle="collapse" data-bs-target="#collapseExample" aria-expanded="false" aria-controls="collapseExample">
  Adicionar publicação
</button>

<div class="collapse" id="collapseExample">
  <div class="card card-body">
    <div class="form-floating">
      <select class="form-select" id="categoriaSelect" aria-label="Selecionar tipo de publicação" onchange="makeSubmenu(this.value)">
        <option selected>Escolha uma categoria</option>
        <option value="publicacao">Publicação</option>
      </select>
      <label for="floatingSelectDisabled">Tipo da publicação</label>
    </div>
    <div class="form-floating">
      <select class="form-select" id="tipoSelect" aria-label="Selecionar tipo de publicação">
        <option selected>Escolha um Tipo</option>
      </select>
      <label for="floatingSelectDisabled">Tipo da publicação</label>
    </div>
  </div>
</div>


<div class="container px-5 my-5" id="bookForm" style="display:none">
  <form>
    <div class="mb-3">
      <label class="form-label" for="titulo">Titulo</label>
      <input class="form-control" id="titulo" type="email" placeholder="Titulo" data-sb-validations="required,email" />
      <div class="invalid-feedback" data-sb-feedback="titulo:required">Titulo is required.</div>
      <div class="invalid-feedback" data-sb-feedback="titulo:email">Titulo Email is not valid.</div>
    </div>
    <div class="mb-3">
      <label class="form-label" for="volume">Volume</label>
      <input class="form-control" id="volume" type="text" placeholder="Volume" data-sb-validations="required" />
      <div class="invalid-feedback" data-sb-feedback="volume:required">Volume is required.</div>
    </div>
    <div class="mb-3">
      <label class="form-label" for="edicao">Edição</label>
      <input class="form-control" id="edicao" type="text" placeholder="Edição" data-sb-validations="required" />
      <div class="invalid-feedback" data-sb-feedback="edicao:required">Edição is required.</div>
    </div>
    <div class="mb-3">
      <label class="form-label" for="numeroDePaginas">Número de Páginas</label>
      <input class="form-control" id="numeroDePaginas" type="text" placeholder="Número de Páginas" data-sb-validations="required" />
      <div class="invalid-feedback" data-sb-feedback="numeroDePaginas:required">Número de Páginas is required.</div>
    </div>
    <div class="mb-3">
      <label class="form-label d-block"></label>
      <div class="form-check form-check-inline">
        <input class="form-check-input" id="refereed" type="checkbox" name="" data-sb-validations="" />
        <label class="form-check-label" for="refereed">Refereed?</label>
      </div>
    </div>
    <div class="mb-3">
      <label class="form-label" for="anoDaPublicacao">Ano Da Publicação</label>
      <select class="form-select" id="anoDaPublicacao" aria-label="Ano Da Publicação">
        <option value="1">1</option>
        <option value="2">2</option>
        <option value="3">3</option>
        <option value="4">4</option>
        <option value="5">5</option>
      </select>
    </div>
    <div class="mb-3">
      <label class="form-label" for="paisDaPublicacao">País da Publicação</label>
      <input class="form-control" id="paisDaPublicacao" type="text" placeholder="País da Publicação" data-sb-validations="required" />
      <div class="invalid-feedback" data-sb-feedback="paisDaPublicacao:required">País da Publicação is required.</div>
    </div>
    <div class="mb-3">
      <label class="form-label" for="cidadeDaPublicacao">Cidade Da Publicação</label>
      <input class="form-control" id="cidadeDaPublicacao" type="text" placeholder="Cidade Da Publicação" data-sb-validations="required" />
      <div class="invalid-feedback" data-sb-feedback="cidadeDaPublicacao:required">Cidade Da Publicação is required.</div>
    </div>
    <div class="mb-3">
      <label class="form-label" for="publisher">Publisher</label>
      <input class="form-control" id="publisher" type="text" placeholder="Publisher" data-sb-validations="required" />
      <div class="invalid-feedback" data-sb-feedback="publisher:required">Publisher is required.</div>
    </div>
    <div class="mb-3">
      <label class="form-label" for="papel">Papel</label>
      <select class="form-select" id="papel" aria-label="Papel">
        <option value="Select">Select</option>
        <option value="Assistant staff">Assistant staff</option>
        <option value="Author">Author</option>
        <option value="Coauthor">Coauthor</option>
        <option value="Editor">Editor</option>
        <option value="Translator">Translator</option>
      </select>
    </div>
    <div class="mb-3">
      <label class="form-label" for="url">Url</label>
      <input class="form-control" id="url" type="text" placeholder="Url" data-sb-validations="required" />
      <div class="invalid-feedback" data-sb-feedback="url:required">Url is required.</div>
    </div>
    <div class="d-none" id="submitSuccessMessage">
      <div class="text-center mb-3">
        <div class="fw-bolder">Form submission successful!</div>
        <p>To activate this form, sign up at</p>
        <a href="https://startbootstrap.com/solution/contact-forms">https://startbootstrap.com/solution/contact-forms</a>
      </div>
    </div>
    <div class="d-none" id="submitErrorMessage">
      <div class="text-center text-danger mb-3">Error sending message!</div>
    </div>
    <div class="d-grid">
      <button class="btn btn-primary btn-lg disabled" id="submitButton" type="submit">Submit</button>
    </div>
  </form>
</div>

<div class="container px-5 my-5" id="valor2" style="display:none">

  <form>
    <div class="ui-accordion-header ui-helper-reset ui-state-default ui-corner-all" role="tab" aria-expanded="false" aria-selected="false" tabindex="0"><span class="ui-icon ui-icon-triangle-1-e"></span>More information<span class="ui-outputlabel-rfi"> *</span></div>

    <div class="container" style="background-color:red;">
      Adicionar publicação 
    </div>

    <div class="collapse" id="target-fieldset">
      <div class="mb-3">
        <label class="form-label" for="titulo">Titulo</label>
        <input class="form-control" id="titulo" type="email" placeholder="Titulo" data-sb-validations="required,email" />
        <div class="invalid-feedback" data-sb-feedback="titulo:required">Titulo is required.</div>
        <div class="invalid-feedback" data-sb-feedback="titulo:email">Titulo Email is not valid.</div>
      </div>
      <div class="mb-3">
        <label class="form-label" for="volume">Volume</label>
        <input class="form-control" id="volume" type="text" placeholder="Volume" data-sb-validations="required" />
        <div class="invalid-feedback" data-sb-feedback="volume:required">Volume is required.</div>
      </div>
      </fieldset>
    </div>

    <div class="container" style="background-color:red;">
      Adicionar publicação 2
    </div>

    <div class="collapse" id="target-fieldset2">
      <div class="mb-3">
        <label class="form-label" for="titulo">Titulo</label>
        <input class="form-control" id="titulo" type="email" placeholder="Titulo" data-sb-validations="required,email" />
        <div class="invalid-feedback" data-sb-feedback="titulo:required">Titulo is required.</div>
        <div class="invalid-feedback" data-sb-feedback="titulo:email">Titulo Email is not valid.</div>
      </div>
      <div class="mb-3">
        <label class="form-label" for="volume">Volume</label>
        <input class="form-control" id="volume" type="text" placeholder="Volume" data-sb-validations="required" />
        <div class="invalid-feedback" data-sb-feedback="volume:required">Volume is required.</div>
      </div>
    </div>

  </form>
</div>



@endsection
















<!--
<a href="{{ route('publications.create') }}" class="btn btn-primary btn-sm active" role="button" aria-pressed="true">Adicionar</a>
<div class="mt-3"></div>
<table id="publication_table" class="table table-striped " style="width:100%">
  <thead>
    <tr>
      <th>Titulo</th>
    </tr>
  </thead>
  <tbody>
    @foreach ($publications as $publication)
    <tr>
      <td>{{ $publication->title }}</td>
      <td>
        <a style="float:left;margin-right:5px;" href="{{ route('publications.edit', $publication->id) }}" class="btn btn-primary btn-sm" role="button" aria-pressed="true">Editar</a>
        <a style="float:left;;margin-right:5px;" href="{{ route('publications.show', $publication->id) }}" class="btn btn-success btn-sm" role="button" aria-pressed="true">Ver</a>
        <form style="float:left;;margin-right:5px;" action="{{ route('publications.destroy', $publication->id) }}" method="POST">
          @csrf
          @method('DELETE')
          <button class="btn btn-danger btn-sm" type="submit">Eliminar</button>
        </form>
      </td>
    </tr>
    @endforeach
  </tbody>
</table>
<script>
  $(document).ready(function() {
    $('#publication_table').DataTable();
  });
</script> 
