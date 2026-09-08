@extends('publications.index')

@section('pubs-form-content')

@if ($errors->any())
<div class="alert alert-danger">
  <ul>
    @foreach ($errors->all() as $error)
    <li>{{ $error }}</li>
    @endforeach
  </ul>
</div>
@endif


@if(session('mensagem'))
<div class="alert alert-success" id="success-alert">
  <strong>Success! </strong> {{ session('mensagem') }}
</div>
@endif


<div class="container shadow p-3 mb-5 bg-body-tertiary rounded">
  <h1 class="text-center">Inserir conference paper</h1>
<form action="{{ route('conference_paper.store') }}" method="POST" enctype="multipart/form-data">
@csrf  
<div class="form-group row  " style="padding:8px;">
    <label for="paper_title" class="col-4 col-form-label">Título</label> 
    <div class="col-8">
      <input id="paper_title" name="paper_title" value="{{ old('paper_title') }}" placeholder="Título" type="text" required="required" class="form-control">
    </div>
  </div>
  <div class="form-group row " style="padding:8px;">
    <label for="conference_name" class="col-4 col-form-label">Nome da conferencia</label> 
    <div class="col-8">
      <input id="conference_name" value="{{ old('conference_name') }}" name="conference_name" placeholder="Nome da conferencia " type="text" class="form-control" required="required">
    </div>
  </div>
  <div class="form-group row" style="padding:8px;">
    <label for="presentation_date" class="col-4 col-form-label">Data da apresentação</label> 
    <div class="col-8">
      <input id="presentation_date" value="{{ old('presentation_date') }}" name="presentation_date" placeholder="Data da apresentação" type="date" class="form-control" required="required">
    </div>
  </div>
  <div class="form-group row" style="padding:8px;">
    <label for="conference_date" class="col-4 col-form-label">Data da conferencia</label> 
    <div class="col-8">
      <input id="conference_date" value="{{ old('conference_date') }}" name="conference_date" placeholder="Data da conferencia" type="date" class="form-control" required="required">
    </div>
  </div>
  <div class="form-group row" style="padding:8px;">
    <label for="conference_country" class="col-4 col-form-label">País da conferencia</label> 
    <div class="col-8">
      <input id="country_conference" value="{{ old('country_conference') }}" name="country_conference" placeholder="País da conferencia" type="text" class="form-control" required="required">
    </div>
  </div>
  <div class="form-group row" style="padding:8px;">
    <label for="city" class="col-4 col-form-label">Cidade</label> 
    <div class="col-8">
      <input id="city" name="city" value="{{ old('city') }}" placeholder="Cidade" type="text" class="form-control" required="required">
    </div>
  </div>
  <div class="form-group row" style="padding:8px;">
    <label for="text" class="col-4 col-form-label">Título do processo</label> 
    <div class="col-8">
      <input id="text" name="proceedings_title" placeholder="proceedings_title" value="{{ old('proceedings_title') }}" type="text" class="form-control" required="required">
    </div>
  </div>
  <div class="form-group row" style="padding:8px;">
    <label for="start_page" class="col-4 col-form-label">Página inicial</label> 
    <div class="col-8">
      <input id="start_page" name="start_page" value="{{ old('start_page') }}" placeholder="Página inicial" type="number" class="form-control" required="required">
    </div>
  </div>
  <div class="form-group row" style="padding:8px;">
    <label for="end_page" class="col-4 col-form-label">Página final</label> 
    <div class="col-8">
      <input id="end_page" name="end_page" value="{{ old('end_page') }}" placeholder="Página final" type="number" class="form-control">
    </div>
  </div>



  <div class="form-group row" style="padding:8px;">
    <label for="status" class="col-4 col-form-label">Status</label>
    <div class="col-8">

      @php

      $statusArray = [
      ["accepted","Accepted"],
      ["inpress","In press"],
      ["published","published"],
      ["submitted","Sumitted"],
      ["under_revision", "Under revision"]
      ]

      @endphp

      <select id="status" name="status" class="form-select" aria-describedby="statusHelpBlock" required="required">
        {-- Percorre o array e mostra as opções --}
        {-- Isto foi feito para selecionar a opção correta quando o valor é subemetido com erros --}
        @foreach ($statusArray as $status)

        @if( old("status") == $status[0])
        <option value="{{ $status[0] }}" selected>{{ $status[1] }}</option>
        @else
        <option value="{{ $status[0] }}">{{ $status[1] }}</option>
        @endif

        @endforeach
      </select>
      <span id="statusHelpBlock" class="form-text text-muted">Select the status of the publication</span>
    </div>
  </div> 
  <div class="form-group row" style="padding:8px;">
    <label for="pub_country" class="col-4 col-form-label">País de publicação</label> 
    <div class="col-8">
      <input id="pub_country" name="pub_country" value="{{ old('pub_country') }}" placeholder="País de publicação" type="text" class="form-control" required="required">
    </div>
  </div>
  <div class="form-group row" style="padding:8px;">
    <label for="pub_city" class="col-4 col-form-label">Cidade de publicação</label> 
    <div class="col-8">
      <input id="pub_city" name="pub_city" value="{{ old('pub_city') }}" placeholder="Cidade de publicação" type="text" class="form-control" required="required">
    </div>
  </div>
  <div class="form-group row" style="padding:8px;">
    <label for="publisher" class="col-4 col-form-label">Publicadora</label> 
    <div class="col-8">
      <input id="publisher" name="publisher" value="{{ old('publisher') }}" placeholder="Publicadora" type="text" class="form-control">
    </div>
  </div>
  <div class="form-group row" style="padding:8px;">
    <label for="select-role" class="col-4 col-form-label">Role</label>
    <div class="col-8">
      <select class="form-select" id="newField" name="role" aria-label="New Field">
        <option default>Role</option>
        <option value="Assistant staff">Assistant staff</option>
        <option value="Author">Author</option>
        <option value="Coauthor Editor">Coauthor Editor</option>
        <option value="Translator">Translator</option>
      </select>
      @error('Role')
      {{ $message }}
      @enderror
    </div>
  </div>
  <div class="form-group row">
    <label for="role" class="col-4 col-form-label">Função</label>
    <div class="col-8">
      <input id="url" name="url" value="{{ old('url') }}" placeholder="Link" type="text" class="form-control" required="required" aria-describedby="urlHelpBlock">
      <span id="urlHelpBlock" class="form-text text-muted">Insira o link do livro</span>
    </div>
  </div> 
  <div class="form-group row" style="padding:8px;">
    <div class="offset-4 col-8">
      <button name="submit" type="submit" class="btn btn-primary">Submit</button>
    </div>
  </div>
</form>
</div>
@endsection