@extends('publications.index')

@section('pubs-form-content')

@if(session('mensagem'))

<div class="alert alert-success" id="success-alert">
  <strong>Success! </strong> {{ session('mensagem') }}
</div>

@endif

<div class="container rounded-3 border shadow bg-light ">
  <h1 class="text-center">Form de Inserção</h1>
  <div class="row">
    <div class="col">
      <form action="{{ route('books.store') }}" id="form" method="POST" enctype="multipart/form-data">
        @csrf
        <div class="form-group row" style="padding:8px;">
          <label for="book_title" class="col-4 col-form-label">Titulo do livro</label>
          <div class="col-8">
            <input id="book_title" name="title" placeholder="Titulo do livro" type="text" value="{{ old('title') }}" class="form-control">
            @error('title')
            {{ $message }}
            @enderror
          </div>
        </div>
        <div class="form-group row" style="padding:8px;">
          <label for="volume" class="col-4 col-form-label">Volume</label>
          <div class="col-8">
            <input id="volume" name="volume" placeholder="Volume" value="{{old('volume')}}" type="text" class="form-control" required="required">
            @error('volume')
            {{ $message }}
            @enderror
          </div>
        </div>
        <div class="form-group row" style="padding:8px;">
          <label for="edition" class="col-4 col-form-label">Edição</label>
          <div class="col-8">
            <input id="edition" name="edition" placeholder="Edição" type="text" value="{{old('edition')}}" class="form-control" required="required">
            @error('edition')
            {{ $message }}
            @enderror
          </div>
        </div>
        <div class="form-group row" style="padding:8px;">
          <label for="pages_number" class="col-4 col-form-label">Numero de páginas</label>
          <div class="col-8">
            <input id="pages_number" value="{{old('pages_number')}}" name="pages_number" placeholder="Numero de páginas" type="number" min="1" required="required" class="form-control">
            @error('pages_number')
            {{ $message }}
            @enderror
          </div>
        </div>
        <div class="form-group row" style="padding:8px;">
          <label for="status" class="col-4 col-form-label">Status</label>


          @php

          $statusArray = [
          ["accepted","Accepted"],
          ["inpress","In press"],
          ["published","published"],
          ["submitted","Sumitted"],
          ["under_revision", "Under revision"]
          ]

          @endphp

          <div class="col-8">
            <select id="status" name="status" class="form-select" aria-describedby="statusHelpBlock" selected="{{old('pages_number')}}" required="required">

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

            @error('status')
            {{ $message }}
            @enderror


            <span id="statusHelpBlock" class="form-text text-muted">Select the status of the publication</span>
          </div>
        </div>
        <div class="form-group row" style="padding:8px;">
          <label class="col-4">Referenciado</label>
          <div class="col-8">
            <div class="custom-control custom-checkbox custom-control-inline">
              <input name="refreed" id="refreed_0" type="checkbox" value="{{ old('refreed') }}" class="custom-control-input" aria-describedby="refreedHelpBlock">
              <label for="refreed_0" class="custom-control-label">Referenciado?</label>
            </div>
            @error('refreed')
            {{ $message }}
            @enderror
            <span id="refreedHelpBlock" class="form-text text-muted">Selecione se o livro está referenciado</span>
          </div>
        </div>
        <div class="form-group row" style="padding:8px;">
          <label for="country" class="col-4 col-form-label">País</label>
          <div class="col-8">
            <input id="country" name="country" value="{{ old('country') }}" placeholder="país" type="text" class="form-control" required="required">
            @error('country')
            {{ $message }}
            @enderror
          </div>
        </div>
        <div class="form-group row" style="padding:8px;">
          <label for="city" class="col-4 col-form-label">Cidade</label>
          <div class="col-8">
            <input id="city" name="city" value="{{ old('city') }}" placeholder="cidade" type="text" class="form-control" required="required">
            @error('city')
            {{ $message }}
            @enderror
          </div>
        </div>
        <div class="form-group row" style="padding:8px;">
          <label for="publisher" class="col-4 col-form-label">Publicadora</label>
          <div class="col-8">
            <input id="publisher" name="publisher" value="{{ old('publisher') }}" placeholder="publicadora" type="text" class="form-control" required="required">
            @error('publisher')
            {{ $message }}
            @enderror
          </div>
        </div>
        <div class="form-group row" style="padding:8px;">
          <label for="select-role" class="col-4 col-form-label">Role</label>
          <div class="col-8">

            @php

            $roleArray = [
            ["","Role"],
            ["Assistant staff","Assistant staff"],
            ["Author","Author"],
            ["Coauthor Editor","Coauthor Editor"],
            ["Translator", "Translator"]
            ]

            @endphp

            <select class="form-select" id="newField" name="role" aria-label="New Field">

              @foreach($roleArray as $role)

              @if( old("role") == $role[0] )
              <option value="{{ $role[0] }}" selected>{{ $role[1] }}</option>
              @else
              <option value="{{ $role[0] }}">{{ $role[1] }}</option>
              @endif

              @endforeach


            </select>
            @error('role')
            {{ $message }}
            @enderror
          </div>
        </div>
        <div class="form-group row" style="padding:8px;">
          <label for="url" class="col-4 col-form-label">Link</label>
          <div class="col-8">
            <input id="url" name="url" value="{{ old('url') }}" placeholder="Link" type="text" class="form-control" required="required" aria-describedby="urlHelpBlock">
            <span id="urlHelpBlock" class="form-text text-muted">Insira o link do livro</span>
          </div>
          @error('Link')
          {{ $message }}
          @enderror
        </div>
        <div class="form-group row" style="padding:8px;">
          <div class="offset-4 col-8 ">
            <button name="submit" type="submit" class="btn btn-primary">Submit</button>
          </div>
        </div>
      </form>
    </div>
  </div>
</div>
@endsection