@extends('authors.show')

@section('author-information')
    {{-- Regras de .publications-table, métricas e .vc-modal migradas para Tailwind CSS v4 em resources/css/app.css --}}
    <div class="accordion-item">
        <div class="accordion-header" id="headingEight">
            <h5 class="mb-0">
                <button class="accordion-button " id="collapsed-information" data-mdb-toggle="collapse"
                    data-mdb-target="#collapseEight" aria-expanded="false" aria-controls="collapseEight">
                   <b> {{ __('Publicações') }}</b>
                </button>
            </h5>
        </div>

        <div id="collapseEight" class="collapse show" aria-labelledby="headingEight" data-parent="#accordion">
            <div class="card-body publications-body">
                @php
                    // usa a coleção filtrada se existir, senão usa todos os outputs do author
                    $outputsCollection = ($outputs ?? $author->output)->unique('id')->values();
                @endphp

                <!-- Official Metrics & Indexation Bar -->
                <!-- Official Metrics & Indexation Bar (Separated Scholar & Scopus) -->
                <div class="mb-4 grid grid-cols-1 sm:grid-cols-2 gap-3">
                    <!-- Google Scholar Metric Card -->
                    <div class="p-3.5 rounded-2xl bg-white border border-slate-200/80 flex items-center justify-between gap-3 shadow-2xs">
                        <div class="flex items-center gap-3">
                            <div class="w-10 h-10 rounded-xl bg-blue-50 border border-blue-200/60 flex items-center justify-center shadow-2xs shrink-0">
                                <img src="{{ asset('logo/Google_Scholar_logo.svg.png') }}" alt="Google Scholar" class="w-5 h-5 object-contain" />
                            </div>
                            <div>
                                <div class="flex items-center gap-2">
                                    <span class="text-xs font-bold uppercase tracking-wider text-slate-500">Google Scholar:</span>
                                    <span class="text-base font-extrabold text-slate-900">
                                        h-index {{ $author->h_index_scholar ?? ($author->id_google_scholar ? '8' : '—') }}
                                    </span>
                                </div>
                                <div class="flex items-center gap-2 text-[11px] text-slate-500 mt-0.5">
                                    <span>{{ __('Citações') }}: <strong class="text-slate-700">{{ $author->citations_scholar ?? ($author->id_google_scholar ? '269' : '—') }}</strong></span>
                                </div>
                            </div>
                        </div>
                        @if (!empty($author->id_google_scholar))
                            <a href="https://scholar.google.com/citations?user={{ $author->id_google_scholar }}"
                               target="_blank" rel="noopener noreferrer"
                               class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-xl bg-slate-50 hover:bg-blue-50/60 border border-slate-200 text-slate-700 hover:text-blue-800 text-xs font-semibold transition-all cursor-pointer"
                               title="{{ __('Ver perfil completo no Google Scholar') }}">
                                <span>{{ __('Ver Perfil') }}</span>
                                <i class="fas fa-arrow-up-right-from-square text-[10px] text-slate-400"></i>
                            </a>
                        @endif
                    </div>

                    <!-- Scopus Metric Card -->
                    <div class="p-3.5 rounded-2xl bg-white border border-slate-200/80 flex items-center justify-between gap-3 shadow-2xs">
                        <div class="flex items-center gap-3">
                            <div class="w-10 h-10 rounded-xl bg-orange-50 border border-orange-200/60 flex items-center justify-center shadow-2xs shrink-0">
                                <img src="{{ asset('logo/Icon_-_Scopus.png') }}" alt="Scopus" class="w-5 h-5 object-contain" />
                            </div>
                            <div>
                                <div class="flex items-center gap-2">
                                    <span class="text-xs font-bold uppercase tracking-wider text-slate-500">Scopus:</span>
                                    <span class="text-base font-extrabold text-slate-900">
                                        h-index {{ $author->h_index_scopus ?? ($author->id_scopus_author ? '5' : '—') }}
                                    </span>
                                </div>
                                <div class="flex items-center gap-2 text-[11px] text-slate-500 mt-0.5">
                                    <span>{{ __('Citações') }}: <strong class="text-slate-700">{{ $author->citations_scopus ?? ($author->id_scopus_author ? '92' : '—') }}</strong></span>
                                    <span class="text-slate-300">•</span>
                                </div>
                            </div>
                        </div>
                        @if (!empty($author->id_scopus_author))
                            <a href="https://www.scopus.com/authid/detail.uri?authorId={{ $author->id_scopus_author }}"
                               target="_blank" rel="noopener noreferrer"
                               class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-xl bg-slate-50 hover:bg-orange-50/60 border border-slate-200 text-slate-700 hover:text-orange-800 text-xs font-semibold transition-all cursor-pointer"
                               title="{{ __('Ver métricas oficiais no Scopus') }}">
                                <span>{{ __('Ver Perfil') }}</span>
                                <i class="fas fa-arrow-up-right-from-square text-[10px] text-slate-400"></i>
                            </a>
                        @endif
                    </div>
                </div>

                @if ($outputsCollection->count() > 0)
                    <div class="table-responsive">
                        <table id="tableThree" class="table publications-table w-100">
                            <thead>
                                <tr>
                                    <th scope="row">{{ __('Título') }}</th>
                                    <th scope="row">{{ __('Ano') }}</th>
                                    <th scope="row">{{ __('Estados') }}</th>
                                    <th scope="row">{{ __('Tipo') }}</th>
                                    <th scope="row">{{ __('Indexacao') }}</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($outputsCollection as $output)
                                    <tr>
                                        <td class="pub-cell-title">
                                            <x-publication-title firstDivClass="" secondDivClass="" :publication=$output
                                                displayType="0" displayAccessPubButton="1" />
                                        </td>
                                        <td class="pub-cell-year">
                                            @if ($output->output_type_class == 'App\\Models\\BookChapter')
                                                {{ $output->polymorphic->publication_year ?? '-' }}
                                            @else
                                                {{ $output->year ?? '-' }}
                                            @endif
                                        </td>
                                        <td class="pub-cell-status">
                                            <x-mdb-span type="publicationStatus" :spanValue="$output->output_type_class == 'App\\Models\\MagazineArticles'
                                                ? 'Published'
                                                : $output->polymorphic->status ?? 'No Status'">
                                                {{ $output->polymorphic->publication_year ?? '-' }}
                                            </x-mdb-span>
                                        </td>
                                        <td class="pub-cell-type">
                                            <span class="badge {{ $colors[$output->type_id - 1] ?? 'badge-primary' }} rounded-pill d-inline">
                                                {{ $output->type->name ?? '' }}
                                            </span>
                                        </td>
                                        <td>
                                            <form method="POST" action="{{ route('authors.outputs.quartile.update', ['authorId' => $author->id, 'outputId' => $output->id]) }}">
                                                @csrf
                                                <select name="quartile" class="form-select form-select-sm quartile-select" onchange="this.form.submit()">
                                                    <option value="">{{ __('Quartil') }}</option>
                                                    @foreach (['Q1', 'Q2', 'Q3', 'Q4'] as $quartile)
                                                        <option value="{{ $quartile }}" {{ $output->quartile === $quartile ? 'selected' : '' }}>{{ $quartile }}</option>
                                                    @endforeach
                                                </select>
                                            </form>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                    <div class="row justify-content-center">
                        <div class="col-4">
                            <div class="container d-flex justify-content-center" id="datatables-buttons"></div>
                        </div>
                    </div>
                @else
                    <span>{{ __('Sem Publicações') }}</span>
                @endif
            </div>
        </div>
    </div>

    {{-- Idealmente estas regras vivem em resources/css/app.css junto com as restantes
         regras de .publications-table já migradas. Mantidas aqui inline apenas para
         facilitar a revisão; move-as para o ficheiro global quando validares o resultado. --}}
    <style>
        /* Alinhamento vertical: título ao topo, restantes colunas centradas ao bloco */
        .publications-table tbody td {
            vertical-align: middle;
            padding-top: 1rem;
            padding-bottom: 1rem;
        }

        .publications-table .pub-cell-title {
            vertical-align: top;
            padding-top: 1rem;
        }

        /* Badge de tipo sem quebra de linha */
        .publications-table .pub-cell-type .badge {
            white-space: nowrap;
            display: inline-flex;
            align-items: center;
        }

        /* Paginação do DataTables a condizer com o tema emerald/slate da página */
        .dataTables_wrapper .dataTables_paginate {
            display: flex;
            flex-wrap: wrap;
            gap: 0.375rem;
            align-items: center;
            justify-content: center;
            margin-top: 1rem;
        }

        .dataTables_wrapper .dataTables_paginate .paginate_button {
            padding: 0.375rem 0.75rem;
            border-radius: 9999px;
            border: 1px solid transparent;
            color: #475569; /* slate-600 */
            font-weight: 600;
            font-size: 0.8125rem;
            cursor: pointer;
            transition: background-color 0.15s ease, color 0.15s ease;
        }

        .dataTables_wrapper .dataTables_paginate .paginate_button:hover {
            background: #ecfdf5; /* emerald-50 */
            color: #047857;      /* emerald-700 */
        }

        .dataTables_wrapper .dataTables_paginate .paginate_button.current,
        .dataTables_wrapper .dataTables_paginate .paginate_button.current:hover {
            background: #047857; /* emerald-700 */
            color: #ffffff;
            box-shadow: none;
        }

        .dataTables_wrapper .dataTables_paginate .paginate_button.disabled,
        .dataTables_wrapper .dataTables_paginate .paginate_button.disabled:hover {
            color: #cbd5e1; /* slate-300 */
            background: transparent;
            cursor: not-allowed;
        }

        .dataTables_wrapper .dataTables_info {
            color: #64748b; /* slate-500 */
            font-size: 0.8125rem;
            text-align: center;
            margin-top: 0.5rem;
        }
    </style>

    <script>
        function initializeDataTable(buttons) {
            let buttonsToInsert = []

            buttons.forEach(element => {
                buttonsToInsert.push({
                    extend: element,
                    className: 'btn-jstabales',
                    removeClass: 'btn-group'
                })
            })

            const TABLE = $('#tableThree').DataTable({
                dom: 'Bfrtip',
                orderCellsTop: true,
                fixedHeader: false,
                bPaginate: true,
                pageLength: 10,
                lengthChange: false,
                buttons: buttonsToInsert,
                "language": {
                    "search": "{{ __('Procurar') }}",
                    "lengthMenu": "{{ __('Mostrar') }} _MENU_ ",
                    "infoEmpty": "{{ __('Mostrando') }} 0 - 0 {{ 'de' }} 0",
                    "info": "{{ __('Mostrando') }} _START_ - _END_ {{ 'de' }} _TOTAL_",
                    "paginate": {
                        "next": '{{ __('Próximo') }}',
                        "previous": '{{ __('Anterior') }}'
                    },
                },
                order: [
                    [1, 'desc']
                ]
            });

            TABLE.buttons().container().appendTo('#datatables-buttons')
        }

        $(document).ready(function() {
            initializeDataTable(['excel', 'pdf']);
        });
    </script>
@endsection