<x-app-layout>

    <x-slot:title>
        {{ __('Autores') }}
        </x-slot>
        {{-- Regras de .authors-table migradas para Tailwind CSS v4 em resources/css/app.css (.authors-table) --}}

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

        <section class="saas-list-compact">
            <div class="container saas-wide py-3">
                <div class="row">
                    <div class="col-xl-3">
                        <div class="card mb-3 saas-sticky rounded-2xl border border-slate-200/80 bg-white/95 shadow-sm backdrop-blur-md">
                            <form id="form-filter" name="form-filter" action="{{ route('author.filter') }}">
                                @csrf
                                <div class="card-body p-4">
                                    <div class="flex items-center justify-between gap-2 pb-2 mb-2 border-b border-slate-100">
                                        <h5 class="text-sm font-bold uppercase tracking-wider text-slate-800 m-0">{{ __('Filtros') }}</h5>
                                        <button class="px-2.5 py-1 text-xs font-semibold text-emerald-800 bg-emerald-50 hover:bg-emerald-100 rounded-full border border-emerald-200/60 transition-colors cursor-pointer inline-flex items-center gap-1" type="button" onclick="clearSelectedElements()">
                                            <i class="fas fa-rotate-left text-[10px]"></i>
                                            {{ __('Limpar') }}
                                        </button>
                                    </div>
                                    <hr class="my-2 border-slate-100">
                                    <h6 class="my-3 font-semibold text-xs tracking-wider uppercase text-emerald-800">{{ __('Entidades') }}</h6>
                                    <div class="overflow-auto">
                                        <div id="entities-container">
                                            <select class="select" id="select-entities" multiple data-mdb-filter="true" data-mdb-container="#entities-container">
                                                @foreach ($entitiesList as $entity)
                                                    <option value="{{ $entity->name }}" class="entities">
                                                        {{ $entity->name }}
                                                    </option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>

                                    <hr>
                                    <!-- lista de entidade -->
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
                    <div class="card mb-3 rounded-2xl border border-slate-200/80 bg-white/95 shadow-sm overflow-hidden">
                        <div class="card-body p-3 sm:p-5">
                            <div class="card-title">
                                <div class="container">
                                    <div class="d-inline-flex align-content-start flex-wrap gap-1.5" id="filtred-filds">
                                    </div>
                                </div>
                            </div>
                            <div class="table-responsive p-1">
                                <table class="table align-middle mb-0 bg-white authors-table responsive nowrap w-full" id="authors_table">
                                    <thead>
                                        <tr>
                                            <th class="rounded-tl-lg">{{ __('Autores') }}</th>
                                            <th>{{ __('Tipo') }}</th>
                                            <th class="rounded-tr-lg">{{ __('Ação') }}</th>
                                        </tr>
                                    </thead>
                                    <tbody>

                                        @foreach ($authors as $author)
                                        <tr>
                                            <td>
                                                <div class="d-flex align-items-center gap-3">
                                                    <x-user-image showLogedUserImage="0" cienciaVitae="{{ $author->userInformation->ciencia_vitae }}" cienciaVitaeImageIsPublic="{{ $author->profile_is_public && $author->profile_image_is_public }}" class="rounded-full w-9 h-9 object-cover border border-slate-200 shadow-xs" height="36" />
                                                    <div>
                                                        <p class="font-bold text-slate-900 text-sm mb-0.5 leading-snug">{{ $author->userInformation->name }}</p>
                                                        <p class="text-xs text-slate-500 mb-0 font-normal">{{ $author->userInformation->email }}</p>
                                                    </div>
                                                </div>
                                            </td>
                                            <td>
                                                <span class="badge badge-success bg-emerald-50 text-emerald-800 border border-emerald-100 rounded-full text-xs font-semibold px-2.5 py-1">{{ __('' . ($author->userInformation->type ?? '')) }}</span>
                                            </td>
                                            <td>
                                                <a href="{{ route('authors.show', $author->{"id"}) }}" class="inline-flex items-center gap-1 text-xs font-semibold text-emerald-700 hover:text-emerald-900 hover:underline">
                                                    <span>{{ __('Perfil') }}</span>
                                                    <i class="fas fa-arrow-right text-[10px]"></i>
                                                </a>
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
                    entities: getSelectedOptions('.entities:checked', 'checkbox', 'class'),
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

                            const userType = element['user_information']['type'] ?? ''

                            table.row.add([
                                `
                                <div class="d-flex align-items-center gap-3">
                                    <img src="${imageSrc}" onerror="this.onerror=null;this.src='https://portalcientifico.islagaia.pt/logo/user.jpg';" class="rounded-full w-9 h-9 object-cover border border-slate-200 shadow-xs" alt="${element['user_information']['name']}">
                                    <div>
                                        <p class="font-bold text-slate-900 text-sm mb-0.5 leading-snug">${element['user_information']['name']}</p>
                                        <p class="text-xs text-slate-500 mb-0 font-normal">${element['user_information']['email']}</p>
                                    </div>
                                </div>`,
                                `<span class="badge badge-success bg-emerald-50 text-emerald-800 border border-emerald-100 rounded-full text-xs font-semibold px-2.5 py-1">${userType}</span>`,
                                `<a href="${perfileLink}" class="inline-flex items-center gap-1 text-xs font-semibold text-emerald-700 hover:text-emerald-900 hover:underline"><span>{{ __('Perfil') }}</span> <i class="fas fa-arrow-right text-[10px]"></i></a>`
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
                    let chipElementExists = document.querySelectorAll(`[parent-id="${option.value}"]`).length > 0

                    if (option.parentElement.name != 'authors_table_length') {
                        createElement(
                            'DIV',
                            'filtred-filds',
                            `${option.text} <i class="fas fa-times fa-lg">`,
                            'element-id',
                            option.value, {
                                type: 'button',
                                class: 'chip filtred-filds',
                                'element-id': option.value,
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
                                `[element-id="${selectedValues[i].value}"]`
                            ).length > 0

                        if (chipElementExists && !selectedValues[i].selected) {
                            deleteElement('element-id', selectedValues[i].value)
                        } else if (selectedValues[i].selected) {
                            createElement(
                                'DIV',
                                'filtred-filds',
                                `${selectedValues[i].text} <i class="fas fa-times fa-lg">`,
                                'element-id',
                                selectedValues[i].value, {
                                    type: 'button',
                                    class: 'chip filtred-filds',
                                    'element-id': selectedValues[i].value,
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
