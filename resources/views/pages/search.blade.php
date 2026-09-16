<x-app-layout>
    <x-slot:title>
        {{ __('Pesquisa') }}
    </x-slot>

    <section class="py-8 bg-slate-50/50 min-h-[calc(100vh-160px)]">
        <div class="max-w-6xl mx-auto px-4 sm:px-6">

            {{-- Header --}}
            <div class="mb-6">
                <h1 class="text-xl sm:text-2xl font-bold text-slate-900 tracking-tight flex items-center gap-2.5">
                    <span class="w-2.5 h-6 bg-emerald-700 rounded-full inline-block"></span>
                    {{ __('Pesquisa Avançada') }}
                </h1>
                <p class="text-xs sm:text-sm text-slate-500 mt-1">
                    {{ __('Consulte produções científicas, autores e publicações através de filtros dedicados.') }}
                </p>
            </div>

            <div class="grid grid-cols-1 lg:grid-cols-12 gap-6 items-start">
                
                {{-- Search Filters (4 cols) --}}
                <div class="lg:col-span-4">
                    <div class="bg-white border border-slate-200/80 rounded-2xl shadow-xs overflow-hidden">
                        <div class="p-4 sm:p-5 border-b border-slate-100 bg-slate-50/40">
                            <h2 class="text-xs sm:text-sm font-bold uppercase tracking-wider text-slate-800 m-0 flex items-center gap-2">
                                <i class="fas fa-filter text-emerald-700"></i>
                                {{ __('Parâmetros') }}
                            </h2>
                        </div>
                        <div class="p-5">
                            <form id="search-form" class="space-y-4">
                                <div>
                                    <label for="query" class="block text-xs font-bold uppercase tracking-wider text-slate-700 mb-1.5">
                                        {{ __('Termo de Pesquisa') }}
                                    </label>
                                    <input type="text" id="query" name="query"
                                        class="w-full px-3.5 py-2.5 text-sm bg-white border border-slate-200 rounded-xl shadow-2xs focus:outline-none focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500 transition-all placeholder:text-slate-400"
                                        placeholder="{{ __('Ex: Fusão nuclear, Inteligência Artificial...') }}">
                                </div>

                                <div>
                                    <label for="filter" class="block text-xs font-bold uppercase tracking-wider text-slate-700 mb-1.5">
                                        {{ __('Filtrar Por') }}
                                    </label>
                                    <select id="filter" name="filter"
                                        class="w-full px-3.5 py-2.5 text-sm bg-white border border-slate-200 rounded-xl shadow-2xs focus:outline-none focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500 transition-all font-medium text-slate-800">
                                        <option value="">{{ __('Todos os campos') }}</option>
                                        <option value="title">{{ __('Título') }}</option>
                                        <option value="author">{{ __('Autor') }}</option>
                                        <option value="year">{{ __('Ano') }}</option>
                                        <option value="journal">{{ __('Revista / Conferência') }}</option>
                                    </select>
                                </div>

                                <button type="submit"
                                    class="w-full inline-flex items-center justify-center gap-2 px-5 py-2.5 text-xs sm:text-sm font-semibold text-white bg-emerald-700 hover:bg-emerald-800 rounded-xl shadow-xs transition-all cursor-pointer">
                                    <i class="fas fa-search text-xs"></i>
                                    <span>{{ __('Pesquisar') }}</span>
                                </button>
                            </form>
                        </div>
                    </div>
                </div>

                {{-- Results (8 cols) --}}
                <div class="lg:col-span-8">
                    <div class="bg-white border border-slate-200/80 rounded-2xl shadow-xs overflow-hidden">
                        <div class="p-4 sm:p-5 border-b border-slate-100 bg-slate-50/40">
                            <h2 class="text-xs sm:text-sm font-bold uppercase tracking-wider text-slate-800 m-0 flex items-center gap-2">
                                <i class="fas fa-list-check text-emerald-700"></i>
                                {{ __('Resultados da Pesquisa') }}
                            </h2>
                        </div>
                        <div class="p-5 sm:p-6" id="results">
                            <div class="text-center py-8 text-slate-400">
                                <i class="fas fa-magnifying-glass text-3xl mb-3 text-slate-300"></i>
                                <p class="text-xs sm:text-sm text-slate-500 max-w-sm mx-auto mb-0">
                                    {{ __('Introduza um termo de pesquisa para localizar publicações científicas e autores.') }}
                                </p>
                            </div>
                        </div>
                    </div>
                </div>

            </div>

        </div>
    </section>
</x-app-layout>
