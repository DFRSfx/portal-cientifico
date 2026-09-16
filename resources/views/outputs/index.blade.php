<x-app-layout>

    <x-slot:title>
        {{ __('Publicações') }}
    </x-slot>

    @push('header-links')
        <!-- DataTables Bootstrap -->
        <link
            href="https://cdn.datatables.net/v/bs5/jszip-2.5.0/dt-1.13.4/af-2.5.3/b-2.3.6/b-colvis-2.3.6/b-html5-2.3.6/b-print-2.3.6/fh-3.3.2/r-2.4.1/datatables.min.css"
            rel="stylesheet">
    @endpush

    @push('header-scripts')
        <script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.2.7/pdfmake.min.js"></script>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.2.7/vfs_fonts.js"></script>
        <script
            src="https://cdn.datatables.net/v/bs5/jszip-2.5.0/dt-1.13.4/af-2.5.3/b-2.3.6/b-colvis-2.3.6/b-html5-2.3.6/b-print-2.3.6/fh-3.3.2/r-2.4.1/datatables.min.js">
        </script>
    @endpush

    <section class="saas-list-compact py-4">
        <div class="container saas-wide">

            {{-- Page Header --}}
            <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-3 mb-6">
                <div>
                    <h1 class="text-xl sm:text-2xl font-bold text-slate-900 tracking-tight flex items-center gap-2.5">
                        <span class="w-2.5 h-6 bg-emerald-700 rounded-full inline-block"></span>
                        {{ __('Publicações') }}
                    </h1>
                    <p class="text-xs sm:text-sm text-slate-500 mt-1">
                        {{ __('Diretório da produção científica, artigos, livros, conferências e relatórios.') }}
                    </p>
                </div>
                <div class="flex items-center gap-2">
                    <div class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-xl bg-white border border-slate-200/80 shadow-2xs text-xs font-semibold text-slate-700">
                        <i class="fas fa-file-lines text-emerald-600 text-xs"></i>
                        <span id="outputs-total-count">{{ is_countable($outputsInformation) ? count($outputsInformation) : 0 }}</span>
                        <span class="text-slate-400 font-normal">{{ __('publicações') }}</span>
                    </div>
                </div>
            </div>

            <div class="row g-4">
                {{-- Left Column: Sidebar Filters --}}
                <div class="col-xl-3">
                    <div class="card saas-sticky rounded-2xl border border-slate-200/80 bg-white shadow-sm overflow-hidden">
                        <div class="p-4 sm:p-5">
                            
                            {{-- Header & Clear button --}}
                            <div class="flex items-center justify-between gap-2 pb-3 mb-4 border-b border-slate-100">
                                <div class="flex items-center gap-2">
                                    <div class="w-7 h-7 rounded-lg bg-emerald-50 text-emerald-700 flex items-center justify-center text-xs">
                                        <i class="fas fa-filter"></i>
                                    </div>
                                    <h2 class="text-xs font-bold uppercase tracking-wider text-slate-800 m-0">
                                        {{ __('Filtros') }}
                                    </h2>
                                </div>
                                <button type="button" onclick="clearAllFilters()"
                                    class="px-2.5 py-1 text-xs font-semibold text-emerald-800 bg-emerald-50 hover:bg-emerald-100 rounded-full border border-emerald-200/60 transition-colors cursor-pointer inline-flex items-center gap-1">
                                    <i class="fas fa-rotate-left text-[10px]"></i>
                                    <span>{{ __('Limpar') }}</span>
                                </button>
                            </div>

                            <form id="form-filter" onsubmit="event.preventDefault(); triggerFilter();">
                                {{-- Search by Title --}}
                                <div class="mb-4">
                                    <label for="title" class="block text-xs font-semibold uppercase tracking-wider text-slate-500 mb-1.5">
                                        {{ __('Pesquisar Publicação') }}
                                    </label>
                                    <div class="relative flex items-center">
                                        <input type="text" id="title" name="title" placeholder="{{ __('Título da publicação...') }}"
                                            class="w-full text-xs bg-slate-50 border border-slate-200 rounded-xl pl-8 pr-10 py-2.5 text-slate-800 placeholder-slate-400 focus:bg-white focus:border-emerald-600 focus:ring-2 focus:ring-emerald-500/20 transition-all outline-none">
                                        <i class="fas fa-search absolute left-2.5 top-1/2 -translate-y-1/2 text-slate-400 text-xs pointer-events-none"></i>
                                        <button type="button" id="search-by-title-button" onclick="triggerFilter()"
                                            class="absolute right-1.5 top-1/2 -translate-y-1/2 px-2.5 py-1 text-xs font-semibold text-white bg-emerald-700 hover:bg-emerald-800 rounded-lg transition-colors cursor-pointer border-0">
                                            <i class="fas fa-arrow-right text-[10px]"></i>
                                        </button>
                                    </div>
                                </div>

                                {{-- Filter: Tipos de Publicação --}}
                                <div class="mb-4 pt-3 border-t border-slate-100">
                                    <div class="flex items-center justify-between mb-2">
                                        <span class="text-xs font-semibold uppercase tracking-wider text-emerald-800 flex items-center gap-1.5">
                                            <i class="fas fa-bookmark text-[11px] text-emerald-600"></i>
                                            {{ __('Tipos de Publicação') }}
                                        </span>
                                        <span id="badge-count-categories" class="hidden text-[10px] font-bold px-1.5 py-0.2 rounded-full bg-emerald-100 text-emerald-800">0</span>
                                    </div>

                                    <div class="space-y-1 max-h-44 overflow-y-auto pr-1 scrollbar-thin border border-slate-100 rounded-xl p-2 bg-slate-50/50">
                                        @forelse ($outputsType as $outputType)
                                            <label class="flex items-center gap-2.5 px-2 py-1.5 rounded-lg hover:bg-white transition-colors cursor-pointer text-xs text-slate-700 select-none">
                                                <input type="checkbox" value="{{ $outputType->id }}" data-label="{{ $outputType->name }}" data-type="category"
                                                    class="filter-checkbox filter-category-cb rounded border-slate-300 text-emerald-600 focus:ring-emerald-500/20 h-3.5 w-3.5 accent-emerald-600 cursor-pointer">
                                                <span class="font-medium truncate" title="{{ $outputType->name }}">{{ $outputType->name }}</span>
                                            </label>
                                        @empty
                                            <p class="text-xs text-slate-400 italic py-1 m-0">{{ __('Sem tipos disponíveis') }}</p>
                                        @endforelse
                                    </div>
                                </div>

                                {{-- Filter: Estado --}}
                                <div class="mb-4 pt-3 border-t border-slate-100">
                                    <div class="flex items-center justify-between mb-2">
                                        <span class="text-xs font-semibold uppercase tracking-wider text-emerald-800 flex items-center gap-1.5">
                                            <i class="fas fa-circle-check text-[11px] text-emerald-600"></i>
                                            {{ __('Estado') }}
                                        </span>
                                        <span id="badge-count-status" class="hidden text-[10px] font-bold px-1.5 py-0.2 rounded-full bg-emerald-100 text-emerald-800">0</span>
                                    </div>
                                    <div class="space-y-1 border border-slate-100 rounded-xl p-2 bg-slate-50/50">
                                        @php
                                            $statusList = [
                                                'Published' => __('Publicado'),
                                                'Accepted' => __('Aceite'),
                                                'In review' => __('Em revisão'),
                                                'Submitted' => __('Submetido'),
                                                'In print' => __('Na impressão')
                                            ];
                                        @endphp
                                        @foreach ($statusList as $val => $label)
                                            <label class="flex items-center gap-2.5 px-2 py-1.5 rounded-lg hover:bg-white transition-colors cursor-pointer text-xs text-slate-700 select-none">
                                                <input type="checkbox" value="{{ $val }}" data-label="{{ $label }}" data-type="status"
                                                    class="filter-checkbox filter-status-cb rounded border-slate-300 text-emerald-600 focus:ring-emerald-500/20 h-3.5 w-3.5 accent-emerald-600 cursor-pointer">
                                                <span class="font-medium">{{ $label }}</span>
                                            </label>
                                        @endforeach
                                    </div>
                                </div>

                                {{-- Filter: Palavras-chave --}}
                                <div class="mb-4 pt-3 border-t border-slate-100">
                                    <div class="flex items-center justify-between mb-2">
                                        <span class="text-xs font-semibold uppercase tracking-wider text-emerald-800 flex items-center gap-1.5">
                                            <i class="fas fa-tags text-[11px] text-emerald-600"></i>
                                            {{ __('Palavras-chave') }}
                                        </span>
                                        <span id="badge-count-keywords" class="hidden text-[10px] font-bold px-1.5 py-0.2 rounded-full bg-emerald-100 text-emerald-800">0</span>
                                    </div>
                                    <div class="space-y-1 max-h-44 overflow-y-auto pr-1 scrollbar-thin border border-slate-100 rounded-xl p-2 bg-slate-50/50">
                                        @forelse ($outputsKeyWords as $keyword)
                                            <label class="flex items-center gap-2.5 px-2 py-1.5 rounded-lg hover:bg-white transition-colors cursor-pointer text-xs text-slate-700 select-none">
                                                <input type="checkbox" value="{{ $keyword->id }}" data-label="{{ $keyword->keyword }}" data-type="keywords"
                                                    class="filter-checkbox filter-keyword-cb rounded border-slate-300 text-emerald-600 focus:ring-emerald-500/20 h-3.5 w-3.5 accent-emerald-600 cursor-pointer">
                                                <span class="font-medium truncate" title="{{ $keyword->keyword }}">{{ $keyword->keyword }}</span>
                                            </label>
                                        @empty
                                            <p class="text-xs text-slate-400 italic py-1 m-0 text-center">{{ __('Sem palavras-chave') }}</p>
                                        @endforelse
                                    </div>
                                </div>

                                {{-- Filter: Citações --}}
                                <div class="mb-4 pt-3 border-t border-slate-100">
                                    <div class="flex items-center justify-between mb-2">
                                        <span class="text-xs font-semibold uppercase tracking-wider text-emerald-800 flex items-center gap-1.5">
                                            <i class="fas fa-quote-right text-[11px] text-emerald-600"></i>
                                            {{ __('Citações') }}
                                        </span>
                                        <span id="badge-count-citations" class="hidden text-[10px] font-bold px-1.5 py-0.2 rounded-full bg-emerald-100 text-emerald-800">0</span>
                                    </div>
                                    <div class="space-y-1 max-h-44 overflow-y-auto pr-1 scrollbar-thin border border-slate-100 rounded-xl p-2 bg-slate-50/50">
                                        @forelse ($citationNames as $citationName)
                                            <label class="flex items-center gap-2.5 px-2 py-1.5 rounded-lg hover:bg-white transition-colors cursor-pointer text-xs text-slate-700 select-none">
                                                <input type="checkbox" value="{{ $citationName->id }}" data-label="{{ $citationName->citation_name }}" data-type="citations"
                                                    class="filter-checkbox filter-citation-cb rounded border-slate-300 text-emerald-600 focus:ring-emerald-500/20 h-3.5 w-3.5 accent-emerald-600 cursor-pointer">
                                                <span class="font-medium truncate" title="{{ $citationName->citation_name }}">{{ $citationName->citation_name }}</span>
                                            </label>
                                        @empty
                                            <p class="text-xs text-slate-400 italic py-1 m-0 text-center">{{ __('Sem citações disponíveis') }}</p>
                                        @endforelse
                                    </div>
                                </div>

                                {{-- Filter: Anos --}}
                                <div class="pt-3 border-t border-slate-100">
                                    <span class="text-xs font-semibold uppercase tracking-wider text-emerald-800 flex items-center gap-1.5 mb-2">
                                        <i class="fas fa-calendar-days text-[11px] text-emerald-600"></i>
                                        {{ __('Intervalo de Anos') }}
                                    </span>
                                    <div class="grid grid-cols-2 gap-2">
                                        <div>
                                            <label for="startYearInput" class="block text-[10px] font-bold uppercase tracking-wider text-slate-400 mb-1">
                                                {{ __('Desde') }}
                                            </label>
                                            <input type="number" id="startYearInput" value="{{ $oldestYear }}" placeholder="{{ $oldestYear ?? 2000 }}" min="1900" max="{{ date('Y') + 5 }}"
                                                class="w-full text-xs bg-slate-50 border border-slate-200 rounded-xl px-2.5 py-2 text-slate-800 placeholder-slate-400 focus:bg-white focus:border-emerald-600 focus:ring-2 focus:ring-emerald-500/20 transition-all outline-none">
                                        </div>
                                        <div>
                                            <label for="endYearInput" class="block text-[10px] font-bold uppercase tracking-wider text-slate-400 mb-1">
                                                {{ __('Até') }}
                                            </label>
                                            <input type="number" id="endYearInput" value="{{ $newestYear }}" placeholder="{{ $newestYear ?? date('Y') }}" min="1900" max="{{ date('Y') + 5 }}"
                                                class="w-full text-xs bg-slate-50 border border-slate-200 rounded-xl px-2.5 py-2 text-slate-800 placeholder-slate-400 focus:bg-white focus:border-emerald-600 focus:ring-2 focus:ring-emerald-500/20 transition-all outline-none">
                                        </div>
                                    </div>
                                </div>
                            </form>

                        </div>
                    </div>
                </div>

                {{-- Right Column: Table --}}
                <div class="col-xl-9">
                    <div class="card mb-3 rounded-2xl border border-slate-200/80 bg-white shadow-sm overflow-hidden">
                        <div class="p-4 sm:p-6">
                            
                            {{-- Active Filters Chips --}}
                            <div id="active-filters-bar" class="hidden items-center flex-wrap gap-1.5 pb-4 mb-4 border-b border-slate-100">
                                <span class="text-xs text-slate-400 mr-1">{{ __('Filtros ativos:') }}</span>
                                <div class="flex items-center flex-wrap gap-1.5" id="filtred-filds"></div>
                            </div>

                            {{-- Export buttons container --}}
                            <div class="flex items-center justify-between gap-3 mb-4">
                                <div id="datatables-buttons" class="flex flex-wrap items-center gap-1.5"></div>
                            </div>

                            <div class="table-responsive relative">
                                <table class="table align-middle mb-0 bg-white outputs-table w-full" id="outputs_table">
                                    <thead>
                                        <tr>
                                            <th class="rounded-tl-lg">{{ __('Nome da publicação') }}</th>
                                            <th class="w-36">{{ __('Estado') }}</th>
                                            <th class="rounded-tr-lg w-36">{{ __('Tipo') }}</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($outputsInformation as $publication)
                                            <tr>
                                                <td class="p-3">
                                                    <x-publication-title firstDivClass="" secondDivClass=""
                                                        :publication=$publication displayType="0"
                                                        displayAccessPubButton="1" />
                                                </td>
                                                <td class="p-3 whitespace-nowrap">
                                                    @php
                                                        $status = $publication->polymorphic->status ?? null;
                                                    @endphp
                                                    @if ($status)
                                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-semibold border bg-emerald-50 text-emerald-800 border-emerald-200/80">
                                                            {{ $status }}
                                                        </span>
                                                    @else
                                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-semibold border bg-slate-100 text-slate-500 border-slate-200">
                                                            {{ __('Sem Estado') }}
                                                        </span>
                                                    @endif
                                                </td>
                                                <td class="p-3 whitespace-nowrap">
                                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-semibold border bg-slate-100 text-slate-700 border-slate-200">
                                                        {{ $publication->type->name ?? __('Sem Tipo') }}
                                                    </span>
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>

                                {{-- Loading Overlay --}}
                                <div id="table-loading-indicator" class="hidden absolute inset-0 bg-white/70 backdrop-blur-xs flex items-center justify-center z-10">
                                    <div class="flex items-center gap-2 px-4 py-2 rounded-xl bg-white border border-slate-200 shadow-lg text-xs font-semibold text-slate-700">
                                        <i class="fas fa-circle-notch fa-spin text-emerald-600"></i>
                                        <span>{{ __('A filtrar publicações...') }}</span>
                                    </div>
                                </div>
                            </div>

                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    @push('scripts')
        <script>
            let dataTableInstance = null;
            let filterDebounceTimer = null;

            const defaultOldestYear = {{ json_encode($oldestYear) }};
            const defaultNewestYear = {{ json_encode($newestYear) }};

            function statusBadgeClass(status) {
                const map = {
                    'published': 'bg-emerald-50 text-emerald-800 border-emerald-200/80',
                    'accepted': 'bg-sky-50 text-sky-800 border-sky-200/80',
                    'in review': 'bg-amber-50 text-amber-800 border-amber-200/80',
                    'under revision': 'bg-amber-50 text-amber-800 border-amber-200/80',
                    'submitted': 'bg-rose-50 text-rose-800 border-rose-200/80',
                    'in print': 'bg-indigo-50 text-indigo-800 border-indigo-200/80'
                };
                const key = (status || '').toString().toLowerCase();
                return map[key] || 'bg-slate-100 text-slate-600 border-slate-200';
            }

            function typeBadgeClass(typeName) {
                const key = (typeName || '').toString().toLowerCase();
                if (key.includes('book') || key.includes('livro')) return 'bg-emerald-50 text-emerald-800 border-emerald-200/80';
                if (key.includes('journal') || key.includes('revista') || key.includes('artigo')) return 'bg-blue-50 text-blue-800 border-blue-200/80';
                if (key.includes('conference') || key.includes('conferência') || key.includes('atas')) return 'bg-cyan-50 text-cyan-800 border-cyan-200/80';
                if (key.includes('abstract')) return 'bg-amber-50 text-amber-800 border-amber-200/80';
                if (key.includes('thesis') || key.includes('dissertation') || key.includes('tese')) return 'bg-purple-50 text-purple-800 border-purple-200/80';
                return 'bg-slate-100 text-slate-700 border-slate-200';
            }

            function initializeDataTable() {
                const buttonsToInsert = [
                    {
                        extend: 'csv',
                        text: '<i class="fas fa-file-csv text-emerald-700 mr-1"></i> CSV',
                        className: 'btn-jstabales'
                    },
                    {
                        extend: 'excel',
                        text: '<i class="fas fa-file-excel text-emerald-700 mr-1"></i> Excel',
                        className: 'btn-jstabales'
                    },
                    {
                        extend: 'pdf',
                        text: '<i class="fas fa-file-pdf text-emerald-700 mr-1"></i> PDF',
                        className: 'btn-jstabales'
                    }
                ];

                dataTableInstance = $('#outputs_table').DataTable({
                    responsive: false,
                    buttons: buttonsToInsert,
                    order: [],
                    pageLength: 10,
                    language: {
                        search: "{{ __('Procurar:') }}",
                        lengthMenu: "{{ __('Mostrar') }} _MENU_",
                        infoEmpty: "{{ __('Mostrando 0 publicações') }}",
                        info: "{{ __('Mostrando') }} _START_ - _END_ {{ __('de') }} _TOTAL_ {{ __('publicações') }}",
                        infoFiltered: "({{ __('filtrado de') }} _MAX_ {{ __('no total') }})",
                        emptyTable: "{{ __('Sem publicações disponíveis') }}",
                        zeroRecords: "{{ __('Nenhuma publicação corresponde aos filtros') }}",
                        paginate: {
                            next: '{{ __("Próximo") }}',
                            previous: '{{ __("Anterior") }}'
                        }
                    }
                });

                dataTableInstance.buttons().container().appendTo('#datatables-buttons');
            }

            function updateFilterBadges() {
                const catCount = document.querySelectorAll('.filter-category-cb:checked').length;
                const statusCount = document.querySelectorAll('.filter-status-cb:checked').length;
                const kwCount = document.querySelectorAll('.filter-keyword-cb:checked').length;
                const citCount = document.querySelectorAll('.filter-citation-cb:checked').length;

                updateBadge('#badge-count-categories', catCount);
                updateBadge('#badge-count-status', statusCount);
                updateBadge('#badge-count-keywords', kwCount);
                updateBadge('#badge-count-citations', citCount);
            }

            function updateBadge(selector, count) {
                const el = document.querySelector(selector);
                if (!el) return;
                if (count > 0) {
                    el.textContent = count;
                    el.classList.remove('hidden');
                } else {
                    el.classList.add('hidden');
                }
            }

            function createChip(text, onRemove) {
                const chip = document.createElement('span');
                chip.className = 'inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full text-xs font-semibold bg-white border border-emerald-200 text-emerald-900 shadow-2xs';
                chip.innerHTML = `
                    <span class="truncate max-w-[160px]">${text}</span>
                    <button type="button" class="text-slate-400 hover:text-rose-600 focus:outline-none transition-colors ml-0.5 cursor-pointer border-0 bg-transparent">
                        <i class="fas fa-times text-[10px]"></i>
                    </button>
                `;
                chip.querySelector('button').addEventListener('click', onRemove);
                return chip;
            }

            function updateActiveChips() {
                const chipsContainer = document.getElementById('filtred-filds');
                const bar = document.getElementById('active-filters-bar');
                if (!chipsContainer || !bar) return;

                chipsContainer.innerHTML = '';
                let hasActive = false;

                const searchTitleVal = document.getElementById('title')?.value?.trim() || '';
                if (searchTitleVal !== '') {
                    hasActive = true;
                    const chip = createChip('Título: ' + searchTitleVal, () => {
                        document.getElementById('title').value = '';
                        triggerFilter();
                    });
                    chipsContainer.appendChild(chip);
                }

                document.querySelectorAll('.filter-checkbox:checked').forEach(cb => {
                    hasActive = true;
                    const label = cb.getAttribute('data-label') || cb.value;
                    const chip = createChip(label, () => {
                        cb.checked = false;
                        triggerFilter();
                    });
                    chipsContainer.appendChild(chip);
                });

                const startY = document.getElementById('startYearInput')?.value;
                const endY = document.getElementById('endYearInput')?.value;
                if ((startY && startY !== String(defaultOldestYear)) || (endY && endY !== String(defaultNewestYear))) {
                    hasActive = true;
                    const chip = createChip(`Anos: ${startY || '…'} - ${endY || '…'}`, () => {
                        if (document.getElementById('startYearInput')) document.getElementById('startYearInput').value = defaultOldestYear || '';
                        if (document.getElementById('endYearInput')) document.getElementById('endYearInput').value = defaultNewestYear || '';
                        triggerFilter();
                    });
                    chipsContainer.appendChild(chip);
                }

                if (hasActive) {
                    bar.classList.remove('hidden');
                    bar.classList.add('flex');
                } else {
                    bar.classList.add('hidden');
                    bar.classList.remove('flex');
                }

                updateFilterBadges();
            }

            function clearAllFilters() {
                const titleInput = document.getElementById('title');
                if (titleInput) titleInput.value = '';

                document.querySelectorAll('.filter-checkbox').forEach(cb => cb.checked = false);

                const startYInput = document.getElementById('startYearInput');
                if (startYInput) startYInput.value = defaultOldestYear || '';

                const endYInput = document.getElementById('endYearInput');
                if (endYInput) endYInput.value = defaultNewestYear || '';

                triggerFilter();
            }

            function triggerFilter() {
                updateActiveChips();

                const categories = Array.from(document.querySelectorAll('.filter-category-cb:checked')).map(cb => parseInt(cb.value, 10));
                const statuses = Array.from(document.querySelectorAll('.filter-status-cb:checked')).map(cb => cb.value);
                const keywords = Array.from(document.querySelectorAll('.filter-keyword-cb:checked')).map(cb => parseInt(cb.value, 10));
                const citations = Array.from(document.querySelectorAll('.filter-citation-cb:checked')).map(cb => parseInt(cb.value, 10));
                const title = document.getElementById('title')?.value?.trim() || '';
                const startYear = document.getElementById('startYearInput')?.value || null;
                const endYear = document.getElementById('endYearInput')?.value || null;

                const payload = {
                    title: title,
                    category: categories,
                    status: statuses,
                    keywords: keywords,
                    citationNames: citations
                };

                if (startYear && endYear) {
                    payload.startYear = startYear;
                    payload.endYear = endYear;
                }

                const loader = document.getElementById('table-loading-indicator');
                if (loader) {
                    loader.classList.remove('hidden');
                    loader.classList.add('flex');
                }

                $.ajax({
                    type: 'GET',
                    url: "{{ route('outputs.filter') }}",
                    data: payload,
                    success: function(response) {
                        if (!dataTableInstance) return;

                        dataTableInstance.clear();

                        const results = (response && response[0] && response[0].filteredResults) ? response[0].filteredResults : [];
                        const countEl = document.getElementById('outputs-total-count');
                        if (countEl) countEl.textContent = results.length;

                        results.forEach(element => {
                            const yearStr = element.year ? `, ${element.year}` : '';
                            const fullTitle = `${element.title || ''}${yearStr}`;
                            const citation = element.citation_string ? `<cite class="not-italic text-xs font-normal text-slate-500 block leading-relaxed line-clamp-2">${element.citation_string}</cite>` : '';
                            const publisher = element.polymorphic && element.polymorphic.publisher ? element.polymorphic.publisher : 'Sem Publicador';
                            
                            let doiLink = '';
                            if (element.doi) {
                                const doiUrl = element.doi.startsWith('http') ? element.doi : `https://www.doi.org/${element.doi}`;
                                doiLink = `<a href="${doiUrl}" target="_blank" rel="nofollow noopener noreferrer" class="inline-flex items-center gap-1 font-semibold text-emerald-700 hover:text-emerald-900 hover:underline transition-colors ml-auto sm:ml-0"><span>Aceder à Publicação</span> <i class="fas fa-arrow-up-right-from-square text-[10px]"></i></a>`;
                            }

                            const col1 = `
                                <div class="w-full space-y-1">
                                    ${citation}
                                    <h4 class="text-sm sm:text-base font-bold text-slate-900 tracking-tight leading-snug m-0">
                                        ${fullTitle}
                                    </h4>
                                    <div class="flex flex-wrap items-center gap-2 pt-0.5 text-xs text-slate-500">
                                        <span class="inline-flex items-center text-slate-600">
                                            <i class="fas fa-building-columns me-1.5 text-xs opacity-70"></i>
                                            ${publisher}
                                        </span>
                                        ${doiLink}
                                    </div>
                                </div>
                            `;

                            const statusVal = element.polymorphic ? element.polymorphic.status : null;
                            const col2 = `
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-semibold border ${statusBadgeClass(statusVal)}">
                                    ${statusVal || "{{ __('Sem Estado') }}"}
                                </span>
                            `;

                            const typeName = element.type ? element.type.name : null;
                            const col3 = `
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-semibold border ${typeBadgeClass(typeName)}">
                                    ${typeName || "{{ __('Sem Tipo') }}"}
                                </span>
                            `;

                            dataTableInstance.row.add([col1, col2, col3]);
                        });

                        dataTableInstance.draw();
                    },
                    error: function(xhr, status, error) {
                        console.error('Filter request failed:', error);
                    },
                    complete: function() {
                        if (loader) {
                            loader.classList.add('hidden');
                            loader.classList.remove('flex');
                        }
                    }
                });
            }

            $(document).ready(function() {
                initializeDataTable();

                // Checkbox changes trigger filter immediately
                $(document).on('change', '.filter-checkbox', function() {
                    triggerFilter();
                });

                // Debounce title input
                $('#title').on('input', function() {
                    clearTimeout(filterDebounceTimer);
                    filterDebounceTimer = setTimeout(triggerFilter, 400);
                });

                // Debounce year inputs
                $('#startYearInput, #endYearInput').on('input', function() {
                    clearTimeout(filterDebounceTimer);
                    filterDebounceTimer = setTimeout(triggerFilter, 500);
                });
            });
        </script>
    @endpush

</x-app-layout>
