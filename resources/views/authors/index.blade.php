<x-app-layout>

    <x-slot:title>
        {{ __('Autores') }}
    </x-slot>

    @once
        @push('header-links')
            <!-- DataTables Bootstrap 5 CSS -->
            <link href="https://cdn.datatables.net/v/bs5/jszip-2.5.0/dt-1.13.4/af-2.5.3/b-2.3.6/b-colvis-2.3.6/b-html5-2.3.6/b-print-2.3.6/fh-3.3.2/r-2.4.1/datatables.min.css" rel="stylesheet" />
        @endpush

        @push('header-scripts')
            <script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.2.7/pdfmake.min.js"></script>
            <script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.2.7/vfs_fonts.js"></script>
            <script src="https://cdn.datatables.net/v/bs5/jszip-2.5.0/dt-1.13.4/af-2.5.3/b-2.3.6/b-colvis-2.3.6/b-html5-2.3.6/b-print-2.3.6/fh-3.3.2/r-2.4.1/datatables.min.js"></script>
        @endpush
    @endonce

    <section class="saas-list-compact py-4">
        <div class="container saas-wide">

            {{-- Page Header --}}
            <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-3 mb-6">
                <div>
                    <h1 class="text-xl sm:text-2xl font-bold text-slate-900 tracking-tight flex items-center gap-2.5">
                        <span class="w-2.5 h-6 bg-emerald-700 rounded-full inline-block"></span>
                        {{ __('Autores') }}
                    </h1>
                    <p class="text-xs sm:text-sm text-slate-500 mt-1">
                        {{ __('Diretório de docentes, investigadores e autores científicos.') }}
                    </p>
                </div>
                <div class="flex items-center gap-2">
                    <div class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-xl bg-white border border-slate-200/80 shadow-2xs text-xs font-semibold text-slate-700">
                        <i class="fas fa-users text-emerald-600 text-xs"></i>
                        <span id="authors-total-count">{{ is_countable($authors) ? count($authors) : '—' }}</span>
                        <span class="text-slate-400 font-normal">{{ __('registados') }}</span>
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

                            {{-- Search by Name --}}
                            <div class="mb-4">
                                <label for="author-search-name" class="block text-xs font-semibold uppercase tracking-wider text-slate-500 mb-1.5">
                                    {{ __('Pesquisar Autor') }}
                                </label>
                                <div class="relative">
                                    <input type="text" id="author-search-name" placeholder="{{ __('Nome do autor...') }}"
                                        class="w-full text-xs bg-slate-50 border border-slate-200 rounded-xl pl-8 pr-3 py-2 text-slate-800 placeholder-slate-400 focus:bg-white focus:border-emerald-600 focus:ring-2 focus:ring-emerald-500/20 transition-all outline-none">
                                    <i class="fas fa-search absolute left-2.5 top-1/2 -translate-y-1/2 text-slate-400 text-xs pointer-events-none"></i>
                                </div>
                            </div>

                            {{-- Filter: Entidades --}}
                            <div class="mb-4 pt-3 border-t border-slate-100">
                                <div class="flex items-center justify-between mb-2">
                                    <span class="text-xs font-semibold uppercase tracking-wider text-emerald-800 flex items-center gap-1.5">
                                        <i class="fas fa-building text-[11px] text-emerald-600"></i>
                                        {{ __('Entidades') }}
                                    </span>
                                    <span id="badge-count-entities" class="hidden text-[10px] font-bold px-1.5 py-0.2 rounded-full bg-emerald-100 text-emerald-800">0</span>
                                </div>

                                @if($entitiesList->isNotEmpty())
                                    <div class="space-y-1 max-h-44 overflow-y-auto pr-1 scrollbar-thin border border-slate-100 rounded-xl p-2 bg-slate-50/50">
                                        @foreach ($entitiesList as $entity)
                                            <label class="flex items-center gap-2.5 px-2 py-1.5 rounded-lg hover:bg-white transition-colors cursor-pointer text-xs text-slate-700 select-none">
                                                <input type="checkbox" value="{{ $entity->name }}" data-label="{{ $entity->name }}" data-type="entities"
                                                    class="filter-checkbox filter-entity-cb rounded border-slate-300 text-emerald-600 focus:ring-emerald-500/20 h-3.5 w-3.5 accent-emerald-600 cursor-pointer">
                                                <span class="font-medium truncate" title="{{ $entity->name }}">{{ $entity->name }}</span>
                                            </label>
                                        @endforeach
                                    </div>
                                @else
                                    <p class="text-xs text-slate-400 italic py-1">{{ __('Sem entidades disponíveis') }}</p>
                                @endif
                            </div>

                            {{-- Filter: Interesses / Áreas de Atividade --}}
                            <div class="mb-4 pt-3 border-t border-slate-100">
                                <div class="flex items-center justify-between mb-2">
                                    <span class="text-xs font-semibold uppercase tracking-wider text-emerald-800 flex items-center gap-1.5">
                                        <i class="fas fa-lightbulb text-[11px] text-emerald-600"></i>
                                        {{ __('Interesses') }}
                                    </span>
                                    <span id="badge-count-topics" class="hidden text-[10px] font-bold px-1.5 py-0.2 rounded-full bg-emerald-100 text-emerald-800">0</span>
                                </div>

                                @php $hasTopics = isset($topics) && count($topics) > 0; @endphp
                                @if($hasTopics)
                                    <div class="space-y-1 max-h-44 overflow-y-auto pr-1 scrollbar-thin border border-slate-100 rounded-xl p-2 bg-slate-50/50">
                                        @foreach ($topics as $topic)
                                            <label class="flex items-center gap-2.5 px-2 py-1.5 rounded-lg hover:bg-white transition-colors cursor-pointer text-xs text-slate-700 select-none">
                                                <input type="checkbox" value="{{ $topic->id }}" data-label="{{ $topic->topic_name }}" data-type="topics"
                                                    class="filter-checkbox filter-topic-cb rounded border-slate-300 text-emerald-600 focus:ring-emerald-500/20 h-3.5 w-3.5 accent-emerald-600 cursor-pointer">
                                                <span class="font-medium truncate" title="{{ $topic->topic_name }}">{{ $topic->topic_name }}</span>
                                            </label>
                                        @endforeach
                                    </div>
                                @else
                                    <div class="px-2.5 py-2 rounded-lg bg-slate-50 border border-slate-100 text-center">
                                        <p class="text-xs text-slate-400 italic m-0">{{ __('Sem interesses disponíveis') }}</p>
                                    </div>
                                @endif
                            </div>

                            {{-- Filter: Línguas --}}
                            <div class="mb-4 pt-3 border-t border-slate-100">
                                <div class="flex items-center justify-between mb-2">
                                    <span class="text-xs font-semibold uppercase tracking-wider text-emerald-800 flex items-center gap-1.5">
                                        <i class="fas fa-language text-[11px] text-emerald-600"></i>
                                        {{ __('Línguas') }}
                                    </span>
                                    <span id="badge-count-languages" class="hidden text-[10px] font-bold px-1.5 py-0.2 rounded-full bg-emerald-100 text-emerald-800">0</span>
                                </div>

                                @php $hasLanguages = isset($languages) && count($languages) > 0; @endphp
                                @if($hasLanguages)
                                    <div class="space-y-1 max-h-44 overflow-y-auto pr-1 scrollbar-thin border border-slate-100 rounded-xl p-2 bg-slate-50/50">
                                        @foreach ($languages as $language)
                                            <label class="flex items-center gap-2.5 px-2 py-1.5 rounded-lg hover:bg-white transition-colors cursor-pointer text-xs text-slate-700 select-none">
                                                <input type="checkbox" value="{{ $language->id }}" data-label="{{ $language->language }}" data-type="languages"
                                                    class="filter-checkbox filter-language-cb rounded border-slate-300 text-emerald-600 focus:ring-emerald-500/20 h-3.5 w-3.5 accent-emerald-600 cursor-pointer">
                                                <span class="font-medium truncate" title="{{ $language->language }}">{{ $language->language }}</span>
                                            </label>
                                        @endforeach
                                    </div>
                                @else
                                    <div class="px-2.5 py-2 rounded-lg bg-slate-50 border border-slate-100 text-center">
                                        <p class="text-xs text-slate-400 italic m-0">{{ __('Sem línguas disponíveis') }}</p>
                                    </div>
                                @endif
                            </div>

                            {{-- Filter: Palavras-chave --}}
                            <div class="pt-3 border-t border-slate-100">
                                <div class="flex items-center justify-between mb-2">
                                    <span class="text-xs font-semibold uppercase tracking-wider text-emerald-800 flex items-center gap-1.5">
                                        <i class="fas fa-tags text-[11px] text-emerald-600"></i>
                                        {{ __('Palavras-chave') }}
                                    </span>
                                    <span id="badge-count-keywords" class="hidden text-[10px] font-bold px-1.5 py-0.2 rounded-full bg-emerald-100 text-emerald-800">0</span>
                                </div>

                                @php $hasKeywords = isset($outputsKeyWords) && count($outputsKeyWords) > 0; @endphp
                                @if($hasKeywords)
                                    <div class="space-y-1 max-h-44 overflow-y-auto pr-1 scrollbar-thin border border-slate-100 rounded-xl p-2 bg-slate-50/50">
                                        @foreach ($outputsKeyWords as $keyword)
                                            <label class="flex items-center gap-2.5 px-2 py-1.5 rounded-lg hover:bg-white transition-colors cursor-pointer text-xs text-slate-700 select-none">
                                                <input type="checkbox" value="{{ $keyword->id }}" data-label="{{ $keyword->keyword }}" data-type="keywords"
                                                    class="filter-checkbox filter-keyword-cb rounded border-slate-300 text-emerald-600 focus:ring-emerald-500/20 h-3.5 w-3.5 accent-emerald-600 cursor-pointer">
                                                <span class="font-medium truncate" title="{{ $keyword->keyword }}">{{ $keyword->keyword }}</span>
                                            </label>
                                        @endforeach
                                    </div>
                                @else
                                    <div class="px-2.5 py-2 rounded-lg bg-slate-50 border border-slate-100 text-center">
                                        <p class="text-xs text-slate-400 italic m-0">{{ __('Sem palavras-chave disponíveis') }}</p>
                                    </div>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>

                {{-- Right Column: Data Table --}}
                <div class="col-xl-9">
                    {{-- Active Filter Pills --}}
                    <div id="active-filters-bar" class="hidden items-center flex-wrap gap-1.5 mb-4 p-3 bg-emerald-50/50 border border-emerald-100 rounded-2xl">
                        <span class="text-xs font-semibold text-emerald-950 flex items-center gap-1.5 mr-1">
                            <i class="fas fa-filter text-[10px] text-emerald-700"></i>
                            {{ __('Filtros ativos:') }}
                        </span>
                        <div id="filtred-filds" class="flex items-center flex-wrap gap-1.5"></div>
                        <button type="button" onclick="clearAllFilters()" class="ml-auto text-xs text-slate-500 hover:text-rose-600 font-medium underline transition-colors cursor-pointer">
                            {{ __('Limpar todos') }}
                        </button>
                    </div>

                    {{-- Main Table Card --}}
                    <div class="card mb-3 rounded-2xl border border-slate-200/80 bg-white shadow-sm overflow-hidden">
                        {{-- Card Header with Export Tools --}}
                        <div class="p-4 sm:p-5 border-b border-slate-100 flex flex-col sm:flex-row sm:items-center justify-between gap-3 bg-slate-50/30">
                            <div>
                                <h3 class="text-sm sm:text-base font-bold text-slate-900 m-0 flex items-center gap-2">
                                    <span>{{ __('Lista de Autores') }}</span>
                                </h3>
                                <p class="text-xs text-slate-500 m-0 mt-0.5">
                                    {{ __('Consulte o perfil, filiação institucional e publicações.') }}
                                </p>
                            </div>
                            <div class="flex items-center gap-2" id="datatables-buttons"></div>
                        </div>

                        {{-- Table Container --}}
                        <div class="p-4 sm:p-5">
                            <div class="table-responsive">
                                <table class="table align-middle mb-0 bg-white authors-table responsive nowrap w-full" id="authors_table">
                                    <thead>
                                        <tr>
                                            <th class="rounded-tl-xl">{{ __('Autor') }}</th>
                                            <th>{{ __('Entidade') }}</th>
                                            <th>{{ __('Tipo') }}</th>
                                            <th class="rounded-tr-xl text-right">{{ __('Ação') }}</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($authors as $author)
                                            <tr>
                                                <td>
                                                    <div class="flex items-center gap-3">
                                                        <x-user-image showLogedUserImage="0"
                                                            cienciaVitae="{{ $author->userInformation?->ciencia_vitae }}"
                                                            cienciaVitaeImageIsPublic="{{ $author->profile_is_public && $author->profile_image_is_public }}"
                                                            class="rounded-full w-10 h-10 object-cover border border-slate-200 shadow-2xs shrink-0"
                                                            height="40" width="40" />
                                                        <div class="min-w-0">
                                                            <p class="font-bold text-slate-900 text-sm mb-0.5 leading-snug truncate">
                                                                {{ $author->userInformation?->name ?? __('Sem nome') }}
                                                            </p>
                                                            <p class="text-xs text-slate-500 mb-0 font-normal truncate">
                                                                {{ $author->userInformation?->email ?? '' }}
                                                            </p>
                                                            @if($author->userInformation?->ciencia_vitae)
                                                                <span class="inline-block text-[10px] font-mono text-emerald-800 bg-emerald-50 px-1.5 py-0.2 rounded border border-emerald-200/60 mt-0.5">
                                                                    {{ $author->userInformation->ciencia_vitae }}
                                                                </span>
                                                            @endif
                                                        </div>
                                                    </div>
                                                </td>
                                                <td>
                                                    @php
                                                        $entity = $author->userInformation?->entidade;
                                                    @endphp
                                                    @if($entity)
                                                        <span class="inline-flex items-center gap-1.5 text-xs font-medium px-2.5 py-1 rounded-lg bg-slate-100 text-slate-700 border border-slate-200/60">
                                                            <i class="fas fa-building text-[10px] text-slate-400"></i>
                                                            <span>{{ $entity }}</span>
                                                        </span>
                                                    @else
                                                        <span class="text-xs text-slate-400">—</span>
                                                    @endif
                                                </td>
                                                <td>
                                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-[11px] font-semibold bg-emerald-50 text-emerald-800 border border-emerald-200/60 uppercase tracking-wider">
                                                        {{ __($author->userInformation?->type ?? 'investigador') }}
                                                    </span>
                                                </td>
                                                <td class="text-right whitespace-nowrap">
                                                    <a href="{{ route('authors.show', $author->id) }}"
                                                       class="inline-flex items-center gap-1.5 px-3 py-1.5 text-xs font-semibold rounded-lg text-emerald-800 bg-emerald-50 hover:bg-emerald-100 border border-emerald-200/60 transition-colors shadow-2xs">
                                                        <span>{{ __('Perfil') }}</span>
                                                        <i class="fas fa-arrow-right text-[10px]"></i>
                                                    </a>
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>

                        {{-- Loading Overlay for AJAX --}}
                        <div id="table-loading-indicator" class="hidden items-center justify-center p-6 border-t border-slate-100 bg-slate-50/50">
                            <div class="inline-flex items-center gap-2 text-xs font-semibold text-emerald-800">
                                <i class="fas fa-circle-notch fa-spin text-emerald-600"></i>
                                <span>{{ __('A atualizar autores...') }}</span>
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

                dataTableInstance = $('#authors_table').DataTable({
                    responsive: true,
                    buttons: buttonsToInsert,
                    order: [[0, 'asc']],
                    pageLength: 10,
                    language: {
                        search: "{{ __('Procurar:') }}",
                        lengthMenu: "{{ __('Mostrar') }} _MENU_",
                        infoEmpty: "{{ __('Mostrando 0 autores') }}",
                        info: "{{ __('Mostrando') }} _START_ - _END_ {{ __('de') }} _TOTAL_ {{ __('autores') }}",
                        infoFiltered: "({{ __('filtrado de') }} _MAX_ {{ __('no total') }})",
                        emptyTable: "{{ __('Nenhum autor encontrado') }}",
                        zeroRecords: "{{ __('Nenhum autor corresponde aos filtros selecionados') }}",
                        paginate: {
                            next: '{{ __("Próximo") }}',
                            previous: '{{ __("Anterior") }}'
                        }
                    }
                });

                dataTableInstance.buttons().container().appendTo('#datatables-buttons');
            }

            function updateFilterBadges() {
                const entityCount = document.querySelectorAll('.filter-entity-cb:checked').length;
                const topicCount = document.querySelectorAll('.filter-topic-cb:checked').length;
                const langCount = document.querySelectorAll('.filter-language-cb:checked').length;
                const keywordCount = document.querySelectorAll('.filter-keyword-cb:checked').length;

                updateBadge('#badge-count-entities', entityCount);
                updateBadge('#badge-count-topics', topicCount);
                updateBadge('#badge-count-languages', langCount);
                updateBadge('#badge-count-keywords', keywordCount);
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

            function updateActiveChips() {
                const chipsContainer = document.getElementById('filtred-filds');
                const bar = document.getElementById('active-filters-bar');
                if (!chipsContainer || !bar) return;

                chipsContainer.innerHTML = '';
                const checkedBoxes = document.querySelectorAll('.filter-checkbox:checked');
                const searchNameVal = document.getElementById('author-search-name')?.value?.trim() || '';

                let hasActive = false;

                if (searchNameVal !== '') {
                    hasActive = true;
                    const chip = createChip('Nome: ' + searchNameVal, () => {
                        document.getElementById('author-search-name').value = '';
                        triggerFilter();
                    });
                    chipsContainer.appendChild(chip);
                }

                checkedBoxes.forEach(cb => {
                    hasActive = true;
                    const label = cb.getAttribute('data-label') || cb.value;
                    const chip = createChip(label, () => {
                        cb.checked = false;
                        triggerFilter();
                    });
                    chipsContainer.appendChild(chip);
                });

                if (hasActive) {
                    bar.classList.remove('hidden');
                    bar.classList.add('flex');
                } else {
                    bar.classList.add('hidden');
                    bar.classList.remove('flex');
                }

                updateFilterBadges();
            }

            function createChip(text, onRemove) {
                const chip = document.createElement('span');
                chip.className = 'inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full text-xs font-semibold bg-white border border-emerald-200 text-emerald-900 shadow-2xs';
                chip.innerHTML = `
                    <span class="truncate max-w-[150px]">${text}</span>
                    <button type="button" class="text-slate-400 hover:text-rose-600 focus:outline-none transition-colors ml-0.5 cursor-pointer">
                        <i class="fas fa-times text-[10px]"></i>
                    </button>
                `;
                chip.querySelector('button').addEventListener('click', onRemove);
                return chip;
            }

            function triggerFilter() {
                updateActiveChips();

                const selectedEntities = Array.from(document.querySelectorAll('.filter-entity-cb:checked')).map(cb => cb.value);
                const selectedTopics = Array.from(document.querySelectorAll('.filter-topic-cb:checked')).map(cb => parseInt(cb.value, 10));
                const selectedLanguages = Array.from(document.querySelectorAll('.filter-language-cb:checked')).map(cb => parseInt(cb.value, 10));
                const selectedKeywords = Array.from(document.querySelectorAll('.filter-keyword-cb:checked')).map(cb => parseInt(cb.value, 10));
                const searchName = document.getElementById('author-search-name')?.value?.trim() || '';

                const payload = {
                    name: searchName,
                    entities: selectedEntities,
                    domainActivity: selectedTopics,
                    language: selectedLanguages,
                    keywords: selectedKeywords
                };

                const loader = document.getElementById('table-loading-indicator');
                if (loader) {
                    loader.classList.remove('hidden');
                    loader.classList.add('flex');
                }

                $.ajax({
                    type: 'GET',
                    url: "{{ route('author.filter') }}",
                    data: payload,
                    success: function(response) {
                        if (!dataTableInstance) return;

                        dataTableInstance.clear();

                        const authors = response.authors || [];
                        const countEl = document.getElementById('authors-total-count');
                        if (countEl) countEl.textContent = authors.length;

                        authors.forEach(element => {
                            const profileUrl = "{{ url('authors') }}/" + element.id;
                            const userInfo = element.user_information || {};
                            const name = userInfo.name || '{{ __("Sem nome") }}';
                            const email = userInfo.email || '';
                            const cv = userInfo.ciencia_vitae || '';
                            const entity = userInfo.entidade || '';
                            const type = userInfo.type || 'investigador';

                            const imageSrc = (element.profile_image_is_public && element.profile_is_public && cv)
                                ? `https://www.cienciavitae.pt/fotos/publico/${cv}.jpg`
                                : `{{ asset('logo/user.jpg') }}`;

                            const cvBadge = cv ? `<span class="inline-block text-[10px] font-mono text-emerald-800 bg-emerald-50 px-1.5 py-0.2 rounded border border-emerald-200/60 mt-0.5">${cv}</span>` : '';
                            const entityBadge = entity
                                ? `<span class="inline-flex items-center gap-1.5 text-xs font-medium px-2.5 py-1 rounded-lg bg-slate-100 text-slate-700 border border-slate-200/60"><i class="fas fa-building text-[10px] text-slate-400"></i><span>${entity}</span></span>`
                                : '<span class="text-xs text-slate-400">—</span>';

                            dataTableInstance.row.add([
                                `
                                <div class="flex items-center gap-3">
                                    <img src="${imageSrc}" onerror="this.onerror=null;this.src='{{ asset('logo/user.jpg') }}';" class="rounded-full w-10 h-10 object-cover border border-slate-200 shadow-2xs shrink-0" alt="${name}">
                                    <div class="min-w-0">
                                        <p class="font-bold text-slate-900 text-sm mb-0.5 leading-snug truncate">${name}</p>
                                        <p class="text-xs text-slate-500 mb-0 font-normal truncate">${email}</p>
                                        ${cvBadge}
                                    </div>
                                </div>
                                `,
                                entityBadge,
                                `<span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-[11px] font-semibold bg-emerald-50 text-emerald-800 border border-emerald-200/60 uppercase tracking-wider">${type}</span>`,
                                `<div class="text-right whitespace-nowrap"><a href="${profileUrl}" class="inline-flex items-center gap-1.5 px-3 py-1.5 text-xs font-semibold rounded-lg text-emerald-800 bg-emerald-50 hover:bg-emerald-100 border border-emerald-200/60 transition-colors shadow-2xs"><span>{{ __('Perfil') }}</span> <i class="fas fa-arrow-right text-[10px]"></i></a></div>`
                            ]);
                        });

                        dataTableInstance.draw();
                    },
                    error: function() {
                        console.error('Erro ao filtrar autores.');
                    },
                    complete: function() {
                        if (loader) {
                            loader.classList.add('hidden');
                            loader.classList.remove('flex');
                        }
                    }
                });
            }

            function clearAllFilters() {
                document.querySelectorAll('.filter-checkbox').forEach(cb => cb.checked = false);
                const searchInput = document.getElementById('author-search-name');
                if (searchInput) searchInput.value = '';
                triggerFilter();
            }

            $(document).ready(function() {
                initializeDataTable();

                // Checkbox filter changes
                document.querySelectorAll('.filter-checkbox').forEach(cb => {
                    cb.addEventListener('change', () => triggerFilter());
                });

                // Name search input with debounce
                const searchInput = document.getElementById('author-search-name');
                if (searchInput) {
                    searchInput.addEventListener('input', () => {
                        clearTimeout(filterDebounceTimer);
                        filterDebounceTimer = setTimeout(() => {
                            triggerFilter();
                        }, 350);
                    });
                }
            });
        </script>
    @endpush

</x-app-layout>
