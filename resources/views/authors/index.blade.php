<x-app-layout>

    <x-slot:title>
        {{ __('Autores') }}
        </x-slot>
        <style>
            .btn-jstabales {
                background-color: transparent;
                color: black;
            }
        </style>

        @once
        @push('header-links')
        <!-- DataTables Bootstrap -->
        <link href="https://cdn.datatables.net/v/bs5/jszip-2.5.0/dt-1.13.4/af-2.5.3/b-2.3.6/b-colvis-2.3.6/b-html5-2.3.6/b-print-2.3.6/fh-3.3.2/r-2.4.1/datatables.min.css" rel="stylesheet" />
        @endpush

        @push('header-scripts')
        <script src="https://cdn.datatables.net/v/bs5/jszip-2.5.0/dt-1.13.4/af-2.5.3/b-2.3.6/b-colvis-2.3.6/b-html5-2.3.6/b-print-2.3.6/fh-3.3.2/r-2.4.1/datatables.min.js">
        </script>
        @endpush
        @endonce

        <section>
            <div class="container pt-5">
                <div class="row">
                    <div class="col-xl-3">
                        <div class="card mb-3">
                            <form id="form-filter" name="form-filter" action="{{ route('author.filter') }}">
                                @csrf
                                <div class="card-body">
                                    <h5 class="card-title">{{ __('Filter') }}</h5>
                                    <button class="btn btn-primary btn-sm " type="button" onclick="clearSelectedElements()">
                                        {{ __('Limpar') }}</button>

                                    <hr>
                                    <h6 class=" my-3" style="color:#2A6B20 ;">{{ __('Interesses') }}</h6>
                                    <div class="overflow-auto">

                                        <div id="topics-container">
                                            <select class="select" id="select-topics" multiple data-mdb-filter="true" data-mdb-container="#topics-container">
                                                @foreach ($topics as $topic)
                                                <option value="{{ $topic->id }}" class="output-topics topics" id="{{ $topic->topic_name }}">{{ $topic->topic_name }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>
                                    <hr>
                                    <h6 class=" my-3" style="color:#2A6B20 ;">{{ __('Línguas') }} </h6>
                                    <div class="overflow-auto">
                                        <div id="languages-container">
                                            <select class="select" id="select-languages" multiple data-mdb-filter="true" data-mdb-container="#languages-container">
                                                @foreach ($languages as $language)
                                                <option value="{{ $language->id }}" class="languages" id="{{ $language->language }}">{{ $language->language }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>

                                    <hr>
                                    <h6 class=" my-3" style="color:#2A6B20">{{ __('Palavras chave') }}</h6>
                                    <div id="keywords-container">
                                        <select class="select" id="select-keywords" multiple data-mdb-filter="true" data-mdb-container="#keywords-container">
                                            @foreach ($outputsKeyWords as $keyword)
                                            <option value="{{ $keyword->id }}" class="output-keywords keywords" id="{{ $keyword->keyword }}">{{ $keyword->keyword }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                            </form>
                        </div>
                    </div>
                </div>

                <div class="col-xl-9">
                    <div class="card mb-2">
                        <div class="card-body">
                            <div class="card-title">
                                <div class="container">
                                    <div class="d-inline-flex align-content-start flex-wrap" id="filtred-filds">
                                    </div>
                                </div>
                            </div>
                            <div class="table-responsive p-2 ">
                                <table class="table align-middle mb-0 bg-white responsive nowrap" style="width:100% " id="authors_table">
                                    <thead class="bg-light">
                                        <tr>
                                            <th>{{ __('Autores') }}</th>
                                            <th>{{ __('Tipo') }}</th>
                                            <th>{{ __('Ação') }}</th>
                                        </tr>
                                    </thead>
                                    <tbody>

                                        @foreach ($authors as $author)
                                        <tr>
                                            <td>
                                                <div class="d-flex align-items-center">

                                                    <x-user-image showLogedUserImage="0" cienciaVitae="{{ $author->userInformation->ciencia_vitae }}" cienciaVitaeImageIsPublic="{{ $author->profile_is_public && $author->profile_image_is_public }}" class="rounded-circle" height="45" />

                                                    <div class="ms-3">
                                                        <p class="fw-bold mb-1">{{ $author->userInformation->name }}
                                                        </p>
                                                        <p class="text-muted mb-0">
                                                            {{ $author->userInformation->email }}
                                                        </p>
                                                    </div>

                                                </div>

                                            </td>

                                            <td>
                                                <span class="badge badge-success rounded-pill d-inline">{{ __('' . ($author->userInformation->type ?? ''))  }}</span>
                                            </td>
                                            <td>
                                                <a href="{{ route('authors.show', $author->{"id"}) }}">{{ __('Perfil') }}</a>
                                            </td>
                                        </tr>
                                        @endforeach

                                    </tbody>
                                </table>
                            </div>
                            <div class="container d-flex justify-content-center" id="datatables-buttons"></div>

                        </div>
                        <!-- Container for demo purpose -->
                        <div class="container" id="loading">
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <script src="{{ asset('javascript\table-span-loader-behaviour.js') }}" defer></script>

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

                const TABLE = $('#authors_table').DataTable({
                    responsive: true,

                    buttons: buttonsToInsert,
                    "language": {
                        "search": "{{ __('Procurar') }}",
                        "lengthMenu": "{{__('Mostrar')}} _MENU_ ",
                        "infoEmpty": "{{__('Mostrando')}} 0 - 0 {{('de')}} 0",
                        "info": "{{__('Mostrando')}} _START_ - _END_ {{('de')}} _TOTAL_",
                        "paginate": {

                            "next": '{{ __("Próximo") }}',
                            "previous": '{{ __("Anterior") }}'
                        },
                    }
                })

                TABLE.buttons().container().appendTo('#datatables-buttons')
            }

            function formHandler() {
                const keywords = getSelectedOptions(
                    '.keywords:checked',
                    'checkbox',
                    'class'
                )

                const data = {
                    domainActivity: getSelectedOptions(
                        '.topics:checked',
                        'checkbox',
                        'class'
                    ),
                    language: getSelectedOptions('.languages:checked', 'checkbox', 'class'),
                    keywords: keywords
                }

                //initializeLoader()

                $.ajax({
                    type: 'GET',
                    url: document.getElementById('form-filter').action,
                    data,
                    success: function(data, status) {
                        var table = $('#authors_table').DataTable()

                        // Removes all rows from the table
                        table.clear()

                        // Create the rows of the table using the data that cames from the request
                        data.authors.forEach(element => {
                            const perfileLink =
                                'https://portalcientifico.islagaia.pt/authors/' + element['id']

                            const imageSrc =
                                element.profile_image_is_public && element.profile_is_public ?
                                `https://www.cienciavitae.pt/fotos/publico/${element['user_information'].ciencia_vitae}.jpg` :
                                'https://portalcientifico.islagaia.pt/logo/user.jpg'

                            table.row.add([
                                `
                 <div class="d-flex align-items-center">
                    <img src="${imageSrc}" onerror="this.onerror=null;this.src='https://portalcientifico.islagaia.pt/logo/user.jpg';" style="width: 45px; height: 45px" class="rounded-circle" alt="gfvg">

                                            <div class="ms-3">
                                            <p class="fw-bold mb-1">${element['user_information']['name']}</p>
                                            <p class="text-muted mb-0">${element['user_information']['email']}</p>
                                          </div>
                                        </div>`,
                                '<span class="badge badge-success rounded-pill d-inline">Teacher</span>',
                                `<a href="${perfileLink}">Ver perfil</a>`
                            ])
                        })

                        // Show the added rows
                        table.draw()

                        removeLoader()
                    },
                    error: function(data, status) {
                        removeLoader()
                    }
                })

                return false
            }

            function submitForm() {
                const formId = 'form-filter'

                document.getElementById(formId).submit = formHandler
                document.getElementById(formId).submit()
            }

            function initalizeSearch() {
                let submitSearch = false

                const bb = Array.from(document.querySelectorAll('option:checked')).map(option => {
                    let chipElementExists = document.querySelectorAll(`[parent-id="${option.id}"]`).length > 0

                    if (option.parentElement.name != 'authors_table_length') {
                        createElement(
                            'DIV',
                            'filtred-filds',
                            `${option.id} <i class="fas fa-times fa-lg">`,
                            'element-id',
                            option.id, {
                                type: 'button',
                                class: 'chip filtred-filds',
                                'element-id': option.id,
                                'parent-type': 'option',
                                useAttribute: true
                            }
                        )
                    }

                    return option
                })

                if (document.querySelectorAll('.filtred-filds').length > 0) {
                    submitForm()
                }
            }

            $(document).ready(function() {
                initializeDataTable([ 'csv', 'excel', 'pdf'])

                initalizeSearch()

                $('.select').change(function(handler) {

                    const selectedValues = handler.currentTarget

                    const chipFields = document.getElementById('filtred-filds').children

                    let seletedOption

                    for (let i = 0; i < selectedValues.length; i++) {
                        let chipElementExists =
                            document.querySelectorAll(
                                `[element-id="${selectedValues[i].id}"]`
                            ).length > 0

                        if (chipElementExists && !selectedValues[i].selected) {
                            deleteElement('element-id', selectedValues[i].id)
                        } else if (selectedValues[i].selected) {
                            createElement(
                                'DIV',
                                'filtred-filds',
                                `${selectedValues[i].id} <i class="fas fa-times fa-lg">`,
                                'element-id',
                                selectedValues[i].id, {
                                    type: 'button',
                                    class: 'chip filtred-filds',
                                    'element-id': selectedValues[i].id,
                                    'parent-type': 'option',
                                    useAttribute: true
                                }
                            )
                        }
                    }

                    submitForm()
                })
            })
        </script>


</x-app-layout>
