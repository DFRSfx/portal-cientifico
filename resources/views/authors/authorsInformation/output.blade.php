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
                    $class = '';
                    $hasMoreThanFiveOutputs = false;
                @endphp

                @php
                    $canEditMetrics = auth()->check() && (auth()->user()->type === 'administrative'
                        || (auth()->user()->authorInformation && auth()->user()->authorInformation->id === $author->id));
                @endphp

                <div class="d-flex align-items-center justify-content-between mb-3">
                    <div class="metrics-inline">
                        <span class="metric-label">H-index:</span>
                        <span class="metric-value">{{ $author->h_index ?? '-' }}</span>
                        @if ($author->h_index_is_self_declared)
                            <span class="badge rounded-pill badge-warning">Auto-declarado</span>
                        @endif
                        <span class="metric-label">Fonte:</span>
                        <span>{{ $author->h_index_source ?? '-' }}</span>
                        <span class="metric-label">Data:</span>
                        <span>{{ $author->h_index_reported_at?->format('Y-m-d') ?? '-' }}</span>
                    </div>

                    @if ($canEditMetrics)
                        <button type="button" class="btn btn-outline-success btn-sm hindex-modal-open">
                            {{ __('Editar H-index') }}
                        </button>
                    @endif
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
                                @forelse ($outputsCollection as $output)
                                    @if ($loop->index >= 5)
                                        @php($hasMoreThanFiveOutputs = true)
                                        @php($class = 'hide_content elements-hidden')
                                    @endif

                                    <tr class="{{ $class }}">
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
                                @empty
                                    <tr>
                                        <td colspan="5">{{ __('Sem Publicações') }}</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                    <div class="row justify-content-center">
                        <div class="col-4">
                            <x-show-more-button moreThanFiveElements="{{ $hasMoreThanFiveOutputs }}" />
                        </div>

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

    @if ($canEditMetrics)
        <div id="hIndexModal" class="vc-modal" aria-hidden="true" role="dialog" aria-labelledby="hIndexModalTitle" style="display:none;">
            <div class="vc-modal-backdrop"></div>
            <div class="vc-modal-dialog" role="document">
                <div class="vc-modal-card">
                    <button type="button" class="vc-close" id="closeHIndexModal" aria-label="{{ __('Close') }}">&times;</button>

                    <header class="vc-modal-header">
                        <div class="vc-header-left">
                            <svg width="44" height="44" viewBox="0 0 24 24" fill="none" aria-hidden="true">
                                <rect width="24" height="24" rx="6" fill="#2f6b2f"/>
                                <path d="M7 12h10M7 8h10M7 16h6" stroke="#fff" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/>
                            </svg>
                        </div>
                        <div class="vc-header-body">
                            <h5 id="hIndexModalTitle">{{ __('Editar H-index') }}</h5>
                            <p class="vc-subtitle">{{ __('Atualize o seu H-index auto-declarado.') }}</p>
                        </div>
                    </header>

                    <div class="vc-modal-body">
                        <form id="hIndexForm" method="POST" action="{{ route('authors.metrics.update', $author->id) }}" class="vc-form">
                            @csrf
                            <div class="vc-input-row">
                                <label class="form-label" for="h_index_modal">H-index</label>
                                <input type="number" min="0" max="200" name="h_index" id="h_index_modal" class="form-control @error('h_index') is-invalid @enderror" value="{{ old('h_index', $author->h_index) }}">
                                @error('h_index')<div class="invalid-feedback">{{ $message }}</div>@enderror
                            </div>
                            <div class="vc-input-row">
                                <label class="form-label" for="h_index_source_modal">Fonte</label>
                                <input type="text" name="h_index_source" id="h_index_source_modal" class="form-control @error('h_index_source') is-invalid @enderror" value="{{ old('h_index_source', $author->h_index_source) }}" placeholder="Scopus / Google Scholar / WOS">
                                @error('h_index_source')<div class="invalid-feedback">{{ $message }}</div>@enderror
                            </div>
                            <div class="vc-input-row">
                                <label class="form-label" for="h_index_reported_at_modal">Data</label>
                                <input type="date" name="h_index_reported_at" id="h_index_reported_at_modal" class="form-control @error('h_index_reported_at') is-invalid @enderror" value="{{ old('h_index_reported_at', optional($author->h_index_reported_at)->format('Y-m-d')) }}">
                                @error('h_index_reported_at')<div class="invalid-feedback">{{ $message }}</div>@enderror
                            </div>

                            <div class="vc-footer">
                                <button type="submit" class="vc-cta">{{ __('Guardar') }}</button>
                                <button type="button" class="vc-secondary" id="cancelHIndexModal">{{ __('Cancelar') }}</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    @endif

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
                "bPaginate": false,
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

        document.addEventListener('DOMContentLoaded', function() {
            const openBtn = document.querySelector('.hindex-modal-open');
            const modal = document.getElementById('hIndexModal');
            const closeBtn = document.getElementById('closeHIndexModal');
            const cancelBtn = document.getElementById('cancelHIndexModal');

            if (!modal || !openBtn) {
                return;
            }

            function openModal() {
                if (modal.parentElement !== document.body) {
                    document.body.appendChild(modal);
                }
                modal.style.display = 'flex';
                modal.setAttribute('aria-hidden', 'false');
                document.body.style.overflow = 'hidden';
                const firstInput = modal.querySelector('input');
                if (firstInput) {
                    setTimeout(function() {
                        firstInput.focus();
                    }, 120);
                }
            }

            function closeModal() {
                modal.style.display = 'none';
                modal.setAttribute('aria-hidden', 'true');
                document.body.style.overflow = '';
            }

            openBtn.addEventListener('click', openModal);
            closeBtn?.addEventListener('click', closeModal);
            cancelBtn?.addEventListener('click', closeModal);
            modal.addEventListener('click', function(event) {
                if (event.target === modal) {
                    closeModal();
                }
            });
            document.addEventListener('keydown', function(event) {
                if (event.key === 'Escape') {
                    closeModal();
                }
            });
        });
    </script>
@endsection