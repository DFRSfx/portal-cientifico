<x-app-layout>
    <x-slot:title>
        {{ __('Gestão de Qualidade Científica') }}
    </x-slot>

    @push('header-links')
        <link href="https://cdn.datatables.net/v/bs5/jszip-2.5.0/dt-1.13.4/af-2.5.3/b-2.3.6/b-colvis-2.3.6/b-html5-2.3.6/b-print-2.3.6/fh-3.3.2/r-2.4.1/datatables.min.css" rel="stylesheet" />
    @endpush

    @push('header-scripts')
        <script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.2.7/pdfmake.min.js"></script>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.2.7/vfs_fonts.js"></script>
        <script src="https://cdn.datatables.net/v/bs5/jszip-2.5.0/dt-1.13.4/af-2.5.3/b-2.3.6/b-colvis-2.3.6/b-html5-2.3.6/b-print-2.3.6/fh-3.3.2/r-2.4.1/datatables.min.js"></script>
    @endpush

    <section class="py-8 bg-slate-50/50 min-h-[calc(100vh-160px)]">
        <div class="max-w-7xl mx-auto px-4 sm:px-6">

            {{-- Notifications --}}
            @if (session('success'))
                <div class="mb-6 p-4 rounded-xl bg-emerald-50 border border-emerald-200 text-emerald-800 text-sm font-medium flex items-center justify-between shadow-2xs">
                    <div class="flex items-center gap-2.5">
                        <i class="fas fa-check-circle text-emerald-600"></i>
                        <span>{{ session('success') }}</span>
                    </div>
                    <button type="button" onclick="this.parentElement.remove()" class="text-emerald-700 hover:text-emerald-900 border-0 bg-transparent cursor-pointer">&times;</button>
                </div>
            @endif

            @if (session('error'))
                <div class="mb-6 p-4 rounded-xl bg-rose-50 border border-rose-200 text-rose-800 text-sm font-medium flex items-center justify-between shadow-2xs">
                    <div class="flex items-center gap-2.5">
                        <i class="fas fa-exclamation-triangle text-rose-600"></i>
                        <span>{{ session('error') }}</span>
                    </div>
                    <button type="button" onclick="this.parentElement.remove()" class="text-rose-700 hover:text-rose-900 border-0 bg-transparent cursor-pointer">&times;</button>
                </div>
            @endif

            {{-- Header --}}
            <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 mb-6">
                <div>
                    <h1 class="text-xl sm:text-2xl font-bold text-slate-900 tracking-tight flex items-center gap-2.5">
                        <span class="w-2.5 h-6 bg-emerald-700 rounded-full inline-block"></span>
                        {{ __('Gestão de Qualidade Científica') }}
                    </h1>
                    <p class="text-xs sm:text-sm text-slate-500 mt-1">
                        {{ __('Auditoria, validação e correção de integridade das publicações para os sistemas de avaliação institucional e CEOS.') }}
                    </p>
                </div>

                <div class="flex items-center gap-2">
                    <a href="{{ route('admin.institution-search') }}"
                       class="inline-flex items-center gap-2 px-3.5 py-2 text-xs sm:text-sm font-semibold text-emerald-800 bg-emerald-50 hover:bg-emerald-100 border border-emerald-200 rounded-xl shadow-2xs transition-colors">
                        <i class="fas fa-building-columns text-emerald-700"></i>
                        <span>{{ __('Pesquisa Institucional') }}</span>
                    </a>
                    <a href="{{ route('statistics') }}"
                       class="inline-flex items-center gap-2 px-3.5 py-2 text-xs sm:text-sm font-semibold text-slate-700 bg-white hover:bg-slate-50 border border-slate-200 rounded-xl shadow-2xs transition-colors">
                        <i class="fas fa-chart-line text-emerald-700"></i>
                        <span>{{ __('Estatísticas Globais') }}</span>
                    </a>
                </div>
            </div>

            {{-- KPI Cards: Indicadores de Qualidade --}}
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4 mb-6">
                <a href="{{ route('admin') }}" class="bg-white border {{ !$activeFilter ? 'border-emerald-600 ring-2 ring-emerald-500/20' : 'border-slate-200/80' }} rounded-2xl p-4 shadow-xs block transition hover:border-slate-300">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-[11px] font-bold uppercase tracking-wider text-slate-400 mb-0.5">{{ __('Total de Publicações') }}</p>
                            <p class="text-2xl font-black text-slate-900 mb-0">{{ $totalOutputs }}</p>
                        </div>
                        <div class="w-10 h-10 rounded-xl bg-slate-100 text-slate-600 flex items-center justify-center text-sm">
                            <i class="fas fa-layer-group"></i>
                        </div>
                    </div>
                </a>

                <a href="{{ route('admin', ['filter' => 'has_quartile']) }}" class="bg-white border {{ $activeFilter === 'has_quartile' ? 'border-emerald-600 ring-2 ring-emerald-500/20' : 'border-slate-200/80' }} rounded-2xl p-4 shadow-xs block transition hover:border-slate-300">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-[11px] font-bold uppercase tracking-wider text-emerald-700 mb-0.5">{{ __('Com Quartil Atribuído') }}</p>
                            <p class="text-2xl font-black text-emerald-800 mb-0">{{ $withQuartileCount }}</p>
                        </div>
                        <div class="w-10 h-10 rounded-xl bg-emerald-50 text-emerald-700 flex items-center justify-center text-sm">
                            <i class="fas fa-award"></i>
                        </div>
                    </div>
                </a>

                <a href="{{ route('admin', ['filter' => 'missing_doi']) }}" class="bg-white border {{ $activeFilter === 'missing_doi' ? 'border-amber-600 ring-2 ring-amber-500/20' : 'border-slate-200/80' }} rounded-2xl p-4 shadow-xs block transition hover:border-slate-300">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-[11px] font-bold uppercase tracking-wider text-amber-700 mb-0.5">{{ __('Sem DOI / Identificador') }}</p>
                            <p class="text-2xl font-black text-amber-800 mb-0">{{ $missingDoiCount }}</p>
                        </div>
                        <div class="w-10 h-10 rounded-xl bg-amber-50 text-amber-700 flex items-center justify-center text-sm">
                            <i class="fas fa-link-slash"></i>
                        </div>
                    </div>
                </a>

                <a href="{{ route('admin', ['filter' => 'missing_year']) }}" class="bg-white border {{ $activeFilter === 'missing_year' ? 'border-rose-600 ring-2 ring-rose-500/20' : 'border-slate-200/80' }} rounded-2xl p-4 shadow-xs block transition hover:border-slate-300">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-[11px] font-bold uppercase tracking-wider text-rose-700 mb-0.5">{{ __('Sem Ano de Publicação') }}</p>
                            <p class="text-2xl font-black text-rose-800 mb-0">{{ $missingYearCount }}</p>
                        </div>
                        <div class="w-10 h-10 rounded-xl bg-rose-50 text-rose-700 flex items-center justify-center text-sm">
                            <i class="fas fa-calendar-xmark"></i>
                        </div>
                    </div>
                </a>
            </div>

            {{-- Quality Table Card --}}
            <div class="bg-white border border-slate-200/80 rounded-2xl shadow-xs overflow-hidden">
                <div class="p-4 sm:p-5 border-b border-slate-100 flex flex-wrap items-center justify-between gap-3 bg-slate-50/40">
                    <div class="flex items-center gap-2">
                        <span class="text-xs font-bold uppercase tracking-wider text-slate-700">{{ __('Filtros rápidos de qualidade:') }}</span>
                        <a href="{{ route('admin') }}" class="px-2.5 py-1 text-xs rounded-lg font-medium {{ !$activeFilter ? 'bg-emerald-700 text-white' : 'bg-white border border-slate-200 text-slate-700 hover:bg-slate-50' }}">{{ __('Todas') }}</a>
                        <a href="{{ route('admin', ['filter' => 'missing_quartile']) }}" class="px-2.5 py-1 text-xs rounded-lg font-medium {{ $activeFilter === 'missing_quartile' ? 'bg-emerald-700 text-white' : 'bg-white border border-slate-200 text-slate-700 hover:bg-slate-50' }}">{{ __('Sem Quartil') }} ({{ $missingQuartileCount }})</a>
                        <a href="{{ route('admin', ['filter' => 'missing_doi']) }}" class="px-2.5 py-1 text-xs rounded-lg font-medium {{ $activeFilter === 'missing_doi' ? 'bg-amber-600 text-white' : 'bg-white border border-slate-200 text-slate-700 hover:bg-slate-50' }}">{{ __('Sem DOI') }} ({{ $missingDoiCount }})</a>
                        <a href="{{ route('admin', ['filter' => 'missing_year']) }}" class="px-2.5 py-1 text-xs rounded-lg font-medium {{ $activeFilter === 'missing_year' ? 'bg-rose-600 text-white' : 'bg-white border border-slate-200 text-slate-700 hover:bg-slate-50' }}">{{ __('Sem Ano') }} ({{ $missingYearCount }})</a>
                    </div>
                </div>

                <div class="p-4 sm:p-5">
                    <div class="table-responsive">
                        <table class="table align-middle mb-0 bg-white w-full text-xs" id="quality_table">
                            <thead>
                                <tr class="text-slate-500 font-bold uppercase tracking-wider border-b border-slate-200">
                                    <th class="py-3 px-3">{{ __('Publicação / Citação') }}</th>
                                    <th class="py-3 px-2 w-32">{{ __('Tipo') }}</th>
                                    <th class="py-3 px-2 w-20">{{ __('Ano') }}</th>
                                    <th class="py-3 px-2 w-24 text-center">{{ __('Quartil') }}</th>
                                    <th class="py-3 px-2 w-28">{{ __('DOI / Link') }}</th>
                                    <th class="py-3 px-2 w-24 text-center">{{ __('Conformidade') }}</th>
                                    <th class="py-3 px-2 w-20 text-center">{{ __('Ações') }}</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-slate-100">
                                @forelse ($outputs as $output)
                                    @php
                                        $hasDoi = !empty($output->doi);
                                        $hasYear = !empty($output->year);
                                        $hasType = !empty($output->type_id);
                                        $isComplete = $hasDoi && $hasYear && $hasType;
                                    @endphp
                                    <tr class="hover:bg-slate-50/70 transition-colors">
                                        <td class="py-3 px-3">
                                            <p class="font-bold text-slate-900 text-sm leading-snug mb-0.5">{{ $output->title }}</p>
                                            @if (!empty($output->citation_string))
                                                <cite class="italic text-[11px] text-slate-500 block leading-tight truncate max-w-xl">{{ $output->citation_string }}</cite>
                                            @endif
                                            @if ($output->authors && $output->authors->count())
                                                <div class="flex items-center gap-1.5 mt-1 text-[11px] text-slate-500">
                                                    <i class="fas fa-user-pen text-[10px] text-emerald-600"></i>
                                                    <span>{{ $output->authors->pluck('userInformation.name')->filter()->implode(', ') ?: __('Autor associado') }}</span>
                                                </div>
                                            @endif
                                        </td>
                                        <td class="py-3 px-2">
                                            <span class="inline-flex px-2 py-0.5 rounded-md text-[11px] font-semibold bg-slate-100 text-slate-700 border border-slate-200">
                                                {{ $output->type->name ?? __('Sem Tipo') }}
                                            </span>
                                        </td>
                                        <td class="py-3 px-2 font-semibold text-slate-800">
                                            {{ $output->year ?: '—' }}
                                        </td>
                                        <td class="py-3 px-2 text-center">
                                            @if ($output->quartile)
                                                <span class="inline-flex items-center px-2 py-0.5 rounded-full text-[11px] font-bold
                                                    {{ $output->quartile === 'Q1' ? 'bg-emerald-100 text-emerald-800 border border-emerald-200' : '' }}
                                                    {{ $output->quartile === 'Q2' ? 'bg-blue-100 text-blue-800 border border-blue-200' : '' }}
                                                    {{ $output->quartile === 'Q3' ? 'bg-amber-100 text-amber-800 border border-amber-200' : '' }}
                                                    {{ $output->quartile === 'Q4' ? 'bg-orange-100 text-orange-800 border border-orange-200' : '' }}
                                                    {{ $output->quartile === 'N/A' ? 'bg-slate-100 text-slate-600 border border-slate-200' : '' }}">
                                                    {{ $output->quartile }}
                                                </span>
                                            @else
                                                <span class="text-slate-400 italic text-[11px]">{{ __('Por definir') }}</span>
                                            @endif
                                        </td>
                                        <td class="py-3 px-2">
                                            @if ($hasDoi)
                                                @php($doiUrl = filter_var($output->doi, FILTER_VALIDATE_URL) ? $output->doi : 'https://doi.org/' . $output->doi)
                                                <a href="{{ $doiUrl }}" target="_blank" rel="noopener noreferrer"
                                                   class="inline-flex items-center gap-1 font-semibold text-emerald-700 hover:text-emerald-900 hover:underline max-w-[120px] truncate" title="{{ $output->doi }}">
                                                    <i class="fas fa-arrow-up-right-from-square text-[10px]"></i>
                                                    <span class="truncate">{{ $output->doi }}</span>
                                                </a>
                                            @else
                                                <span class="text-amber-600 font-medium flex items-center gap-1">
                                                    <i class="fas fa-triangle-exclamation text-[10px]"></i>
                                                    <span>{{ __('Sem DOI') }}</span>
                                                </span>
                                            @endif
                                        </td>
                                        <td class="py-3 px-2 text-center">
                                            @if ($isComplete)
                                                <span class="inline-flex items-center gap-1 px-2 py-0.5 rounded-full text-[10px] font-bold bg-emerald-50 text-emerald-700 border border-emerald-200">
                                                    <i class="fas fa-check text-[9px]"></i> {{ __('Válido') }}
                                                </span>
                                            @else
                                                <span class="inline-flex items-center gap-1 px-2 py-0.5 rounded-full text-[10px] font-bold bg-amber-50 text-amber-700 border border-amber-200">
                                                    <i class="fas fa-wrench text-[9px]"></i> {{ __('Incompleto') }}
                                                </span>
                                            @endif
                                        </td>
                                        <td class="py-3 px-2 text-center">
                                            <button type="button"
                                                    onclick="openQualityModal({{ $output->id }}, '{{ addslashes($output->title) }}', '{{ $output->doi ?? '' }}', '{{ $output->year ?? '' }}', '{{ $output->quartile ?? '' }}')"
                                                    class="px-2.5 py-1 text-xs font-semibold text-white bg-emerald-700 hover:bg-emerald-800 rounded-lg shadow-2xs transition-colors border-0 cursor-pointer inline-flex items-center gap-1">
                                                <i class="fas fa-pen-to-square text-[10px]"></i>
                                                <span>{{ __('Validar') }}</span>
                                            </button>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="7" class="py-8 text-center text-slate-400 text-sm">
                                            {{ __('Nenhuma publicação encontrada para o filtro selecionado.') }}
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

        </div>
    </section>

    {{-- Edit / Validate Quality Modal --}}
    <div id="qualityModal" class="hidden fixed inset-0 z-50 bg-slate-900/50 backdrop-blur-xs flex items-center justify-center p-4">
        <div class="bg-white rounded-2xl shadow-xl border border-slate-200 max-w-lg w-full overflow-hidden">
            <div class="p-5 border-b border-slate-100 flex items-center justify-between">
                <h3 class="text-base font-bold text-slate-900 m-0 flex items-center gap-2">
                    <i class="fas fa-shield-halved text-emerald-700"></i>
                    <span>{{ __('Validação de Qualidade da Publicação') }}</span>
                </h3>
                <button type="button" onclick="closeQualityModal()" class="text-slate-400 hover:text-slate-600 border-0 bg-transparent text-lg cursor-pointer leading-none">&times;</button>
            </div>

            <form id="qualityForm" method="POST" action="" class="p-5 space-y-4">
                @csrf
                <div>
                    <label class="block text-xs font-bold uppercase tracking-wider text-slate-500 mb-1">{{ __('Título da Publicação') }}</label>
                    <p id="modalTitle" class="text-xs text-slate-800 font-semibold bg-slate-50 p-2.5 rounded-xl border border-slate-200 m-0"></p>
                </div>

                <div class="grid grid-cols-2 gap-3">
                    <div>
                        <label for="modalQuartile" class="block text-xs font-bold uppercase tracking-wider text-slate-700 mb-1.5">{{ __('Quartil') }}</label>
                        <select id="modalQuartile" name="quartile" class="w-full text-xs bg-slate-50 border border-slate-200 rounded-xl px-3 py-2 text-slate-800 font-medium focus:bg-white focus:border-emerald-600 focus:ring-2 focus:ring-emerald-500/20 outline-none">
                            <option value="">{{ __('Sem Quartil') }}</option>
                            <option value="Q1">Q1</option>
                            <option value="Q2">Q2</option>
                            <option value="Q3">Q3</option>
                            <option value="Q4">Q4</option>
                            <option value="N/A">N/A</option>
                        </select>
                    </div>

                    <div>
                        <label for="modalYear" class="block text-xs font-bold uppercase tracking-wider text-slate-700 mb-1.5">{{ __('Ano') }}</label>
                        <input type="number" id="modalYear" name="year" min="1900" max="{{ date('Y') + 1 }}"
                               class="w-full text-xs bg-slate-50 border border-slate-200 rounded-xl px-3 py-2 text-slate-800 font-medium focus:bg-white focus:border-emerald-600 focus:ring-2 focus:ring-emerald-500/20 outline-none">
                    </div>
                </div>

                <div>
                    <label for="modalDoi" class="block text-xs font-bold uppercase tracking-wider text-slate-700 mb-1.5">{{ __('Identificador DOI / URL') }}</label>
                    <input type="text" id="modalDoi" name="doi" placeholder="10.xxxx/yyyy ou https://..."
                           class="w-full text-xs bg-slate-50 border border-slate-200 rounded-xl px-3 py-2 text-slate-800 font-medium focus:bg-white focus:border-emerald-600 focus:ring-2 focus:ring-emerald-500/20 outline-none">
                </div>

                <div class="pt-3 border-t border-slate-100 flex items-center justify-end gap-2">
                    <button type="button" onclick="closeQualityModal()"
                            class="px-4 py-2 text-xs font-semibold text-slate-700 bg-white hover:bg-slate-50 border border-slate-200 rounded-xl cursor-pointer">
                        {{ __('Cancelar') }}
                    </button>
                    <button type="submit"
                            class="px-4 py-2 text-xs font-semibold text-white bg-emerald-700 hover:bg-emerald-800 rounded-xl shadow-xs transition-colors border-0 cursor-pointer">
                        {{ __('Guardar Validação') }}
                    </button>
                </div>
            </form>
        </div>
    </div>

    @push('scripts')
        <script>
            $(document).ready(function() {
                $('#quality_table').DataTable({
                    responsive: true,
                    pageLength: 25,
                    order: [[2, 'desc']],
                    language: {
                        url: '//cdn.datatables.net/plug-ins/1.13.4/i18n/pt-PT.json'
                    },
                    dom: '<"flex flex-wrap items-center justify-between gap-3 mb-4"Bf>rt<"flex flex-wrap items-center justify-between gap-3 mt-4"lip>',
                    buttons: [
                        { extend: 'copy', text: '<i class="fas fa-copy me-1"></i> Copiar', className: 'btn btn-sm btn-outline-secondary' },
                        { extend: 'csv', text: '<i class="fas fa-file-csv me-1"></i> CSV', className: 'btn btn-sm btn-outline-secondary' },
                        { extend: 'excel', text: '<i class="fas fa-file-excel me-1"></i> Excel', className: 'btn btn-sm btn-outline-secondary' },
                        { extend: 'print', text: '<i class="fas fa-print me-1"></i> Imprimir', className: 'btn btn-sm btn-outline-secondary' }
                    ]
                });
            });

            function openQualityModal(id, title, doi, year, quartile) {
                document.getElementById('modalTitle').textContent = title;
                document.getElementById('modalDoi').value = doi;
                document.getElementById('modalYear').value = year;
                document.getElementById('modalQuartile').value = quartile;
                document.getElementById('qualityForm').action = "{{ url('authors/admin/quality') }}/" + id;
                document.getElementById('qualityModal').classList.remove('hidden');
            }

            function closeQualityModal() {
                document.getElementById('qualityModal').classList.add('hidden');
            }
        </script>
    @endpush
</x-app-layout>
