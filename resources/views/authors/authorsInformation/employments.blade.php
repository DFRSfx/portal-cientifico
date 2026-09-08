@extends('authors.show')

@section('author-information')
    <style>
        .affiliation-table {
            border-collapse: separate;
            border-spacing: 0;
        }

        .affiliation-table thead th {
            background: #e6f1ea;
            color: #1f4d2f;
            font-weight: 700;
            border-bottom: 1px solid #cfe2d6;
            padding: 12px 14px;
            position: relative;
            cursor: pointer;
        }

        .affiliation-table tbody td {
            padding: 12px 14px;
            border-bottom: 1px solid #dfeae3;
            vertical-align: middle;
        }

        .affiliation-table tbody tr:nth-child(even) {
            background: #f2f8f4;
        }

        .affiliation-table tbody tr:hover {
            background: #e3f2e8;
        }

    </style>

    <div class="accordion-item">
        <div class="accordion-header" id="headingSeven">
            <h5 class="mb-0">
                <button class="accordion-button" data-mdb-toggle="collapse" data-mdb-target="#collapseSeven"
                    aria-expanded="false" aria-controls="collapseSeven" id="collapsed-information">
                  <b>  {{ __('Percurso profissional') }}</b>
                </button>
            </h5>
        </div>
        <div id="collapseSeven" class="collapse show" aria-labelledby="headingSeven" data-parent="#accordion">
            <div class="card-body">
                @php
                    $employments = $author->employments->sortBy(function ($employment) {
                        return $employment->start_date ?? '9999-12-31';
                    });
                @endphp
                @if ($employments->count() > 0)
                    <div class="table-responsive">
                        <table id="emplymentsTable" class="table affiliation-table">
                            <thead>
                                <tr>
                                    <th scope="col" class="position-relative">{{ __('Ano') }}</th>
                                    <th scope="col">{{ __('Função') }}</th>
                                    <th scope="col">{{ __('Empregador') }}</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse ($employments as $employment)
                                    @php
                                        $startYear = $employment->start_date_year ?? null;
                                        $startMonth = $employment->start_date_month ?? null;
                                        $startDay = $employment->start_date_day ?? null;
                                        $endYear = $employment->end_date_year ?? null;
                                        $endMonth = $employment->end_date_month ?? null;
                                        $endDay = $employment->end_date_day ?? null;

                                        $sortYear = $startYear ?? $endYear ?? 9999;
                                        $sortMonth = $startMonth ?? $endMonth ?? 12;
                                        $sortDay = $startDay ?? $endDay ?? 31;
                                        $orderKey = sprintf('%04d%02d%02d', $sortYear, $sortMonth, $sortDay);
                                    @endphp

                                    <tr>

                                        {{-- Valida se a categoria não foi lida --}}
                                        <td data-order="{{ $orderKey }}">
                                            <x-date-component :model=$employment initialDateAttribute="start_date" finalDateAttribute="end_date"/>
                                        </td>
                                        {{-- institution/organization --}}
                                        <td>
                                            {{ $employment->position_title ?? '' }}

                                            {{ isset($employment->position_type) ? '(' . $employment->position_type . ')' : __('Sem dados') }}
                                        </td>
                                        {{-- Employer --}}
                                        <td>
                                            {{ $employment->institution_name ?? __('Sem instituição identificável') }}
                                        </td>
                                    </tr>
                                @empty
                                @endforelse
                            </tbody>
                        </table>

                    </div>
                    <div class="row justify-content-center">
                        <div class="col-4">
                            <div class="container d-flex justify-content-center" id="datatables-buttons"></div>
                        </div>
                    </div>
                @else
                    <span>-</span>
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

            const TABLE = $('#emplymentsTable').DataTable({
                dom: 'Bfrtip',
                orderCellsTop: true,
                fixedHeader: false,
                "bPaginate": false,
                "ordering": true,
                "order": [[0, 'desc']],
                buttons: buttonsToInsert,
                "language": {
                    "search": "{{ __('Procurar') }}",
                    "lengthMenu": "{{__('Mostrar')}} _MENU_ ",
                    "infoEmpty":      "{{__('Mostrando')}} 0 - 0 {{('de')}} 0",
                    "info":           "{{__('Mostrando')}} _START_ - _END_ {{('de')}} _TOTAL_",
                    "paginate": {

                        "next": '{{ __("Próximo") }}',
                        "previous": '{{ __("Anterior") }}'
                    },
                },

            });


            TABLE.buttons().container().appendTo('#datatables-buttons')
        }

        $(document).ready(function() {
            initializeDataTable(['copy', 'excel', 'pdf']),

        });
    </script>
@endsection
