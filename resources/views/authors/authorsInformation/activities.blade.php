@extends('authors.show')
{{-- os que não tiverem título, copiar a descrição em trim() --}}
{{-- se for of uma conference scientifc comittee --}}
@section('author-information')
    <style>
        .btn-jstabales {
            background-color: transparent;
            color: black;
        }

        .activities-body {
            padding-left: 12px;
            padding-right: 12px;
        }

        @media (min-width: 1200px) {
            .activities-body {
                padding-left: 6px;
                padding-right: 6px;
            }
        }

            .activities-table {
                border-collapse: separate;
                border-spacing: 0;
            }

            .activities-table thead th {
                background: #e6f1ea;
                color: #1f4d2f;
                font-weight: 700;
                border-bottom: 1px solid #cfe2d6;
                padding: 12px 14px;
            }

            .activities-table tbody td {
                padding: 12px 14px;
                border-bottom: 1px solid #dfeae3;
                vertical-align: middle;
            }

            .activities-table tbody tr:nth-child(even) {
                background: #f2f8f4;
            }

            .activities-table tbody tr:hover {
                background: #e3f2e8;
            }
    </style>
    <div class="accordion-item">
        <div class="accordion-header" id="headingEight">
            <h5 class="mb-0">
                <button class="accordion-button " id="collapsed-information" data-mdb-toggle="collapse"
                    data-mdb-target="#collapseEight" aria-expanded="false" aria-controls="collapseEight">
                    <b> {{ __('Atividades') }}</b>
                </button>
            </h5>
        </div>

        <div id="collapseEight" class="collapse show" aria-labelledby="headingEight" data-parent="#accordion">
            <div class="card-body activities-body">
                @if ($author->service->count() > 0)
                    <div class="table-responsive">
                        <table id="tableThree" class="table w-100 activities-table">
                            <thead>
                                <tr>
                                    {{-- <th scope="row">{{ __('Tipo de evento') }}</th> --}}
                                    <th scope="row">{{ __('Nome do evento') }}</th>
                                    <th scope="row">{{ __('Ano') }}</th>
                                    <th scope="row">{{ __('Descrição do evento') }}</th>
                                    <th scope="row">{{ __('Tipo') }}</th>
                                </tr>
                            </thead>
                            <tbody>
                                @php
                                    $class = '';
                                    $hasMoreThanFiveOutputs = false;
                                @endphp
                                @forelse ($author->service as $service)
                                    {{-- Hides the publication after 5 publications hide_content elements-hidden --}}

                                    @if ($loop->index >= 5)
                                        @php($hasMoreThanFiveOutputs = true)
                                        @php($class = 'hide_content elements-hidden')
                                    @endif

                                    <tr class="{{ $class }}">
                                        {{-- <td>
                                            @if ($service->type->name == 'Conference scientific committee')
                                                @if (empty($service->polymorphic->event_type))
                                                    Conference scientific committee
                                                @endif
                                            @else
                                                {{ $service->polymorphic->event_type ?? '-' }}
                                            @endif

                                        </td> --}}
                                        <td>
                                            @if ($service->type->name == 'Event organisation')
                                                @if (!empty($service->polymorphic->event_name))
                                                    {{ $service->polymorphic->event_name }}
                                                @else
                                                    {{ $service->polymorphic->event_description ?? '-' }}
                                                @endif
                                            @elseif ($service->type->name == 'Supervision' && $service->polymorphic->supervisory_title == 'Supervisor')
                                                {{ $service->polymorphic->thesis_title }}
                                            @elseif ($service->type->name == 'Conference scientific committee')
                                                {{ $service->polymorphic->conference }}
                                            @elseif ($service->type->name == 'Jury of academic degree')
                                                {{ $service->polymorphic->theme }}
                                            @else
                                                {{ $service->polymorphic->event_name }}
                                            @endif


                                        </td>
                                        <td>
                                            @if ($service->type->name == 'Jury of academic degree')
                                                {{ $service->polymorphic->year }}
                                            @else
                                                {{ $service->end_year ?? '-' }}
                                            @endif


                                        </td>
                                        <td>
                                            {{ $service->polymorphic->event_description ?? '-' }}
                                        <td>
                                            <span class="badge badge-primary rounded-pill d-inline">
                                                {{ $service->type->name ?? '-' }}
                                            </span>

                                        </td>
                                    @empty
                                            <td>{{ __('Sem Atividades') }}</td>
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
                    <span>{{ __('Sem Atividades') }}</span>
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
