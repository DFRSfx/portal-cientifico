@extends('layouts.admin')
@section('content-author')
    <p>Editar</p>
    <form class="row" action="{{ route('authors.update', $authors->id) }}" method="POST" enctype="multipart/form-data">
        @csrf
        @method('PATCH')
        <div class="col-sm-4">
            <div class="form-outline mb-4">
                <input type="text" name="title" id="title" value="{{$authors->name}}" class="form-control" />
                <label class="form-label" for="name">Nome</label>
            </div>
            <div class="form-outline mb-4">
                <input type="text" name="title" id="title" value="{{$authors->ciencia_vitae}}" class="form-control" />
                <label class="form-label" for="name">Ciencia vitae</label>
            </div>
            <div class="form-outline mb-4">
                <input type="text" name="title" id="title" value="{{$authors->orcid}}" class="form-control" />
                <label class="form-label" for="name">Orcid</label>
            </div>
            <div class="form-outline mb-4">
                <input type="text" name="title" id="title" value="{{$authors->id_google_scholer}}" class="form-control" />
                <label class="form-label" for="name">Id google</label>
            </div>
            <div class="form-outline mb-4">
                <input type="text" name="title" id="title" value="{{$authors->id_researcher}}" class="form-control" />
                <label class="form-label" for="name">Id researcher</label>
            </div>
            <div class="form-outline mb-4">
                <input type="text" name="title" id="title" value="{{$authors->id_scopus_author}}" class="form-control" />
                <label class="form-label" for="name">Id scopus_autorh</label>
            </div>
            <button type="submit" class="btn btn-primary">{{ __('Editar') }}</button>
        </div>
        </form>
@endsection