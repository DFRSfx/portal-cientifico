@extends('authors.show')

@section('author-information')
    <style>
        .btn-jstabales {
            background-color: transparent;
            color: black;
        }
    </style>
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
            <div class="card-body">
                @php
                    // usa a coleção filtrada se existir, senão usa todos os outputs do author
                    $outputsCollection = $outputs ?? $author->output;
                    $class = '';
                    $hasMoreThanFiveOutputs = false;
                @endphp

                @if ($outputsCollection->count() > 0)
                    <div class="table-responsive">
                        <table id="tableThree" class="table">
                            <thead>
                                <tr>
                                    <th scope="row">{{ __('Título') }}</th>
                                    <th scope="row">{{ __('Ano') }}</th>
                                    <th scope="row">{{ __('Estados') }}</th>
                                    <th scope="row">{{ __('Tipo') }}</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse ($outputsCollection as $output)
                                    @if ($loop->index >= 5)
                                        @php($hasMoreThanFiveOutputs = true)
                                        @php($class = 'hide_content elements-hidden')
                                    @endif

                                    <tr class="{{ $class }}">
                                        <td>
                                            <x-publication-title firstDivClass="" secondDivClass="" :publication=$output
                                                displayType="0" displayAccessPubButton="1" />
                                        </td>
                                        <td>
                                            @if ($output->output_type_class == 'App\\Models\\BookChapter')
                                                {{ $output->polymorphic->publication_year ?? '-' }}
                                            @else
                                                {{ $output->year ?? '-' }}
                                            @endif
                                        </td>
                                        <td>
                                            <x-mdb-span type="publicationStatus" :spanValue="$output->output_type_class == 'App\\Models\\MagazineArticles'
                                                ? 'Published'
                                                : $output->polymorphic->status ?? 'No Status'">
                                                {{ $output->polymorphic->publication_year ?? '-' }}
                                            </x-mdb-span>
                                        </td>
                                        <td>
                                            <span class="badge {{ $colors[$output->type_id - 1] ?? 'badge-primary' }} rounded-pill d-inline">
                                                {{ $output->type->name ?? '' }}
                                            </span>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="4">{{ __('Sem Publicações') }}</td>
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
    </script>
@endsection