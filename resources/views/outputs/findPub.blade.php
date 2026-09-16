@extends('index')
@section('statistics')
@section('content')

<section class="py-6">
    <div class="container max-w-4xl mx-auto px-4">
        <div class="text-center mb-6">
            <h1 class="text-2xl sm:text-3xl font-bold text-slate-900 tracking-tight">{{ __('Pesquisa de Produção Científica') }}</h1>
            <p class="text-sm text-slate-500 mt-1">{{ __('Consulte publicações e metadados científicos indexados em bases internacionais') }}</p>
        </div>

        <form id="form" class="mb-6">
            <div class="relative flex items-center shadow-sm rounded-2xl bg-white border border-slate-200/80 p-1.5 transition-all focus-within:border-emerald-500 focus-within:ring-2 focus-within:ring-emerald-500/20">
                <div class="flex-1 form-outline relative">
                    <input type="search" id="text-to-search" name="text-to-search" class="w-full bg-transparent border-0 px-4 py-2.5 text-sm text-slate-800 placeholder-slate-400 focus:outline-none focus:ring-0" placeholder="{{ __('Pesquise por DOI ou título científico (ex: 10.1016/...)') }}" />
                </div>
                <button type="submit" class="px-5 py-2.5 bg-emerald-700 hover:bg-emerald-800 text-white text-sm font-semibold rounded-xl transition-colors cursor-pointer inline-flex items-center gap-2">
                    <i class="fas fa-search text-xs"></i>
                    <span>{{ __('Pesquisar') }}</span>
                </button>
            </div>
        </form>

        <div class="row justify-content-center">
            <div class="card text-center rounded-2xl border border-slate-200/80 bg-white/95 shadow-sm p-4 my-3 max-w-sm mx-auto" id="repositoriesCard" style="display: none;">
                <div class="card-body p-2">
                    <div class="flex items-center justify-center gap-3">
                        <span class="text-sm font-bold text-slate-800 tracking-tight">Scopus Database</span>
                        <div class="spinner-border spinner-border-sm text-emerald-700" role="status" id="scopus-spinner" aria-hidden="true"></div>
                        <i class="far fa-check-circle text-xl text-emerald-500" id="scopus-check-icon" style="display: none;"></i>
                    </div>
                </div>
            </div>
        </div>

        <div class="row justify-content-center pt-2">
            <div class="col-12">
                <div class="card mb-4 rounded-2xl border border-slate-200/80 bg-white shadow-md overflow-hidden" id="search-results" style="display: none;">
                    <div class="card-body prose prose-slate prose-emerald max-w-none p-6 sm:p-8" id="element1">
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<script>
    const apiResultsToShow = document.getElementById("search-results");
    const repositoriesCard = document.getElementById("repositoriesCard");

    $("#form").submit(function(event) {
        event.preventDefault();

        if (repositoriesCard) repositoriesCard.style.display = "block";
        const spinner = document.getElementById("scopus-spinner");
        const checkIcon = document.getElementById("scopus-check-icon");
        if (spinner) spinner.style.display = "inline-block";
        if (checkIcon) checkIcon.style.display = "none";

        const textVal = document.getElementById("text-to-search").value;
        if (!textVal) return;

        $.ajax({
            type: 'GET',
            data: {
                doi: textVal
            },
            url: '/test-search',
            success: function(data, status) {
                if (spinner) spinner.style.display = "none";
                if (checkIcon) checkIcon.style.display = "block";
                if (apiResultsToShow) apiResultsToShow.style.display = "block";

                const item = (data && data["data"] && data["data"][0]) ? data["data"][0] : null;
                    const cardContent = document.getElementById("element1");

                    if (!item) {
                        cardContent.innerHTML = `<p class="text-slate-500">{{ __('Nenhum resultado encontrado para esta consulta.') }}</p>`;
                    } else {
                        cardContent.innerHTML = `
                            <div class="not-prose flex items-center justify-between pb-3 mb-4 border-b border-slate-100">
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-semibold bg-emerald-100 text-emerald-800 uppercase tracking-wide">
                                    <i class="fas fa-database me-1.5 text-[10px]"></i> ${item["api"] || 'Scopus'}
                                </span>
                                <span class="text-xs text-slate-400 font-mono">${item["eid"] || ''}</span>
                            </div>
                            <div class="not-prose mb-5">
                                <h2 class="text-xl sm:text-2xl font-bold text-slate-900 tracking-tight leading-snug m-0">
                                    ${item["dcTitle"] || 'Sem Título'}
                                </h2>
                                <p class="text-sm text-slate-600 font-medium mt-2 flex flex-wrap items-center gap-2">
                                    <span class="text-slate-800 font-semibold"><i class="fas fa-user-pen me-1 text-emerald-700"></i> ${item["dcCreator"] || 'Autor Desconhecido'}</span>
                                    <span class="text-slate-400">&bull;</span>
                                    <span class="italic text-slate-600"><i class="fas fa-book-open me-1 text-slate-400"></i> ${item["prismPublicationName"] || 'Publicação'}</span>
                                </p>
                            </div>
                            <div class="not-prose grid grid-cols-1 sm:grid-cols-2 gap-3 p-4 rounded-xl bg-slate-50 border border-slate-100 text-xs">
                                <div>
                                    <span class="font-bold text-slate-400 uppercase tracking-wider block mb-0.5">DOI</span>
                                    <span class="font-mono text-slate-800 select-all">${item["doi"] || '-'}</span>
                                </div>
                                <div>
                                    <span class="font-bold text-slate-400 uppercase tracking-wider block mb-0.5">Scopus ID</span>
                                    <span class="font-mono text-slate-800 select-all">${item["dcIdentifier"] || '-'}</span>
                                </div>
                            </div>
                            <div class="not-prose mt-5 pt-4 border-t border-slate-100 flex items-center justify-end">
                                <a href="${item["url"] || '#'}" target="_blank" rel="nofollow noopener noreferrer"
                                   class="inline-flex items-center gap-2 px-4 py-2 text-xs font-semibold text-white bg-emerald-700 hover:bg-emerald-800 rounded-full shadow-sm hover:shadow transition-all">
                                    <span>{{ __('Aceder ao Registo Científico') }}</span>
                                    <i class="fas fa-arrow-up-right-from-square text-[10px]"></i>
                                </a>
                            </div>
                        `;
                    }

                    apiResultsCardAnimation.startAnimation();
                }
            });
        }, 3000);
    });
</script>
@endsection