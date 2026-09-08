@extends('authors.show')

@section('author-information')
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
                @if (count($author->employments) > 0)
                    <div class="table-responsive">
                        <table id="emplymentsTable" class="table">
                            <thead>
                                <tr>
                                    <th scope="row">{{ __('Ano') }}</th>
                                    <th scope="row">{{ __('Função') }}</th>
                                    <th scope="row">{{ __('Empregador') }}</th>
                                </tr>
                            </thead>
                            <tbody>
                                @php
                                    $class = '';
                                    $hasMoreThanFiveOutputs = false;
                                @endphp

                                @forelse ($author->employments as $employment)
                                    {{-- Hides the publication after 5 publications --}}
                                    @if ($loop->index >= 5)
                                        @php($hasMoreThanFiveOutputs = true)
                                        @php($class = 'hide_content elements-hidden')
                                    @endif

                                    <tr class="{{ $class }}">

                                        {{-- Valida se a categoria não foi lida --}}
                                        <td>
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
                            <x-show-more-button moreThanFiveElements="{{ $hasMoreThanFiveOutputs }}"/>
                        </div>

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
