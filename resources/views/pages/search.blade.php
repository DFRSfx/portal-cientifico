<x-app-layout>

    <div class="container pt-5">
        <div class="row">
            <div class="col-xl-3">
                <div class="card mb-3">
                    <div class="card-body">
                        <div class="card-title">
                            <h4 class="text-base font-bold text-emerald-800">{{ __('Pesquisa') }}</h4>
                        </div>
                        <form>
                            <div class="form-group">
                                <label for="query" class="my-3">Digite sua consulta:</label>
                                <input type="text" class="form-control" id="query" name="query"
                                    placeholder="Ex: Fusão nuclear">
                            </div>
                            <div class="form-group">
                                <label for="filter" class="my-3">Filtrar por:</label>
                                <select class="form-control" id="filter" name="filter">
                                    <option value="">Todos os campos</option>
                                    <option value="title">Título</option>
                                    <option value="author">Autor</option>
                                    <option value="year">Ano</option>
                                    <option value="journal">Revista</option>
                                </select>
                            </div>
                            <button class="btn btn-primary mt-3 w-full" type="submit">{{ __('Pesquisar') }}</button>
                          
                        </form>
                    </div>
                </div>
            </div>
            <div class="col-xl-9">
                <div class="card mb-3">
                    <div class="card-body">
                        <div class="card-title">
                            <h4 class="text-base font-bold text-emerald-800">{{ __('Resultados da pesquisa') }}</h4>
                        </div>
                       <div id="results">
                            <!-- Aqui vão os resultados da pesquisa, gerados dinamicamente -->
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
