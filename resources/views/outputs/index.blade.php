<x-app-layout>

    <x-slot:title>
        {{ __('Publicações') }}
    </x-slot>

    {{-- Estilos da outputs-table migrados para Tailwind CSS v4 em resources/css/app.css (.outputs-table) --}}

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

    <section class="saas-list-compact py-2">
        <div class="container saas-wide mt-3">
            <div class="row">
                <div class="col-xl-3">
                    <div class="card saas-sticky rounded-2xl border border-slate-200/80 bg-white/95 shadow-sm backdrop-blur-md">

                        <form id="form-filter" name="form-filter" method="POST">
                            @csrf
                            <div class="card-body">

                                <div class="flex items-center justify-between gap-2 pb-2 mb-2 border-b border-slate-100">
                                    <h5 class="text-sm font-bold uppercase tracking-wider text-slate-800 m-0">{{ __('Filtros') }}</h5>
                                    <button class="px-2.5 py-1 text-xs font-semibold text-emerald-800 bg-emerald-50 hover:bg-emerald-100 rounded-full border border-emerald-200/60 transition-colors cursor-pointer inline-flex items-center gap-1" type="button"
                                        onclick="clearSelectedElements()">
                                        <i class="fas fa-rotate-left text-[10px]"></i>
                                        {{ __('Limpar') }}
                                    </button>
                                </div>
                                <div>
                                    <div class="input-group">
                                        <div class="form-outline">
                                            <input type="input" id="title" name="title" class="form-control" />
                                            <label class="form-label"
                                                for="title">{{ __('Nome da publicação') }}</label>
                                        </div>
                                        <button type="button" id="search-by-title-button" class="btn btn-primary">
                                            <i class="fas fa-search"></i>
                                        </button>
                                    </div>
                                </div>
                                <hr>
                                <h6 class=" my-3" style="color:#2A6B20">{{ __('Tipos de Publicações') }}</h6>
                                <div id="output-type-container">
                                    <select class="select" id="select-output-type" multiple data-mdb-filter="true"
                                        data-mdb-container="#output-type-container">
                                        @foreach ($outputsType as $outputType)
                                            <option value="{{ $outputType->id }}" class="output-type"
                                                id="{{ $outputType->name }}">{{ $outputType->name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <hr>
                                <h6 class=" my-3" style="color:#2A6B20">{{ __('Estado') }}</h6>
                                <div class="form-check">
                                    <input class="form-check-input output-status" should-disable="true" type="checkbox"
                                        value="Published" id="published" />
                                    <label class=" form-check-label" for="published">{{ __('Publicado') }}</label>
                                </div>
                                <div class="form-check">
                                    <input class="form-check-input output-status" should-disable="true" type="checkbox"
                                        value="Accepted" id="accepted" />
                                    <label class=" form-check-label" for="accepted">{{ __('Aceite') }}</label>
                                </div>

                                <div class="form-check">
                                    <input class="form-check-input output-status" should-disable="true" type="checkbox"
                                        value="In review" id="in-review" />
                                    <label class=" form-check-label" for="in-review">{{ __('Em revisão') }}</label>
                                </div>
                                <div class="form-check">
                                    <input class="form-check-input output-status" should-disable="true" type="checkbox"
                                        value="Submitted" id="submitted" />
                                    <label class="form-check-label" for="submitted">{{ __('Submetido') }}</label>
                                </div>
                                <div class="form-check">
                                    <input class="form-check-input output-status" should-disable="true" type="checkbox"
                                        value="In print" id="in-print" />
                                    <label class=" form-check-label" for="in-print">{{ __('Na impressão') }}</label>
                                </div>
                                <h6 class=" my-3" style="color:#2A6B20">{{ __('Palavras chave') }}</h6>
                                <div id="keywords-container">
                                    <select class="select" id="select-keywords" multiple
                                        data-mdb-container="#keywords-container" data-mdb-filter="true">
                                        @foreach ($outputsKeyWords as $keyword)
                                            <option value="{{ $keyword->id }}" class="output-keywords"
                                                id="{{ $keyword->keyword }}">{{ $keyword->keyword }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <hr>
                                <h6 class=" my-3" style="color:#2A6B20">{{ __('Citações') }}</h6>
                                <div id="citations-container">
                                    <select class="select" id="select-citations" multiple
                                        data-mdb-container="#citations-container" data-mdb-filter="true">
                                        @foreach ($citationNames as $citationName)
                                            <option value="{{ $citationName->id }}" class="citation-names"
                                                id="{{ $citationName->citation_name }}">
                                                {{ $citationName->citation_name }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                                <hr>
                                <h6 class=" my-3" style="color:#2A6B20">{{ __('Anos') }}</h6>
                                <div>
                                    <div id="multi-ranges-basic-value" class="mt-3">
                                        <span class="d-flex justify-content-between flex-row p-2">
                                            <div id="startYear">{{ $oldestYear }}</div>
                                            <div id="endYear">{{ $newestYear }}
                                            </div>
                                        </span>
                                    </div>
                                    <div class="list-group list-group-flush" id="multi-range-container">
                                        <div class="multi-ranges-basic" id="multi-range" data-mdb-tooltips="true">
                                        </div>
                                    </div>
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
                            <table class="table align-middle mb-0 bg-white outputs-table w-full"
                                id="outputs_table">
                                <thead>
                                    <tr>
                                        <th class="rounded-tl-lg">{{ __('Nome da publicação') }}</th>
                                        <th>{{ __('Estado') }}</th>
                                        <th class="rounded-tr-lg">{{ __('Tipo') }}</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($outputsInformation as $publication)
                                        <tr>
                                            <td>
                                                <x-publication-title firstDivClass="" secondDivClass=""
                                                    :publication=$publication displayType="0"
                                                    displayAccessPubButton="1" />
                                            </td>
                                            <td>
                                                <x-mdb-span type="publicationStatus"
                                                    spanValue="{{ $publication->polymorphic->status ?? 'No State' }}" />
                                            </td>
                                            <td>
                                                <span
                                                    class="badge {{ $colors[$publication->type_id - 1] ?? 'badge-primary' }} rounded-pill d-inline ">{{ $publication->type->name ?? 'Conference paper' }}</span>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>

                        <br>
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
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });

        const basicExample = document.querySelector('.multi-ranges-basic');
        const oneRangeValueBasic = document.querySelector('#multi-ranges-basic-value');

        let rangeInstance
        const oldestDate = <?php echo "$oldestYear"; ?>;

        function yearsRange() {
            const currentDate = new Date();

            let year = <?php echo "$newestYear"; ?>;

            let oldestDate = <?php echo "$oldestYear"; ?>

            let yearDiff = year - oldestDate;

            rangeInstance = new mdb.MultiRangeSlider(document.querySelector('.multi-ranges-basic'), {
                max: yearDiff,
                min: 0,
                startValues: [0, yearDiff]
            });

            document.querySelector('.multi-ranges-basic').addEventListener('value.mdb.multiRangeSlider', (handler) => {

                // Values 
                let [first, second] = handler.values.rounded;

                first += oldestDate + 1;
                second += oldestDate;

                const filteredElement = document.getElementById("years-to-search");

                // Checks if the span with the years to search exists
                if (filteredElement === null) {

                    const attributes = {
                        "type": "button",
                        "class": "chip filtred-filds",
                        "element-id": "date-to-search",
                        "id": "years-to-search",
                        "parent-type": "year"
                    }

                    createElement("DIV", "filtred-filds",
                        `${first} - ${second} <i class="fas fa-times fa-lg">`, "parent-type",
                        "year", attributes);
                } else {
                    filteredElement.innerHTML = `${first} - ${second} <i class="fas fa-times fa-lg">`;
                }

                // Updates the range values
                oneRangeValueBasic.innerHTML = `<span class="d-flex justify-content-between flex-row p-2">
                                    <div id="startYear">${first}</div>
                                    <div id="endYear">${second}</div>
                                </span>
                                `;
            });

            // adds the events
            const rangeSliderHand = document.getElementsByClassName("multi-range-slider-hand")

            Array.from(rangeSliderHand).forEach(function(element) {
                element.addEventListener('mouseup', submitForm);
                element.addEventListener('touchend', submitForm);
            });

        }

        function initializeDataTable(buttons) {
            let buttonsToInsert = []

            buttons.forEach(element => {
                buttonsToInsert.push({
                    extend: element,
                    className: 'btn-jstabales',
                    removeClass: 'btn-group'
                })
            })

            const TABLE = $('#outputs_table').DataTable({
                responsive: false,

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
                }
            })

            TABLE.buttons().container().appendTo('#datatables-buttons')
        }

        function enableOneCheckBoxAndDisableTheRest(ElementClass, elementToDisableId) {
            let targetElement;

            targetElement = document.querySelectorAll(targetId);

            targetElement.forEach(element => {

                let theElementShoulbBeDisabled = (element.getAttribute("id") == elementToDisableId)

                element.disable = theElementShoulbBeDisabled;

            });
        }

        function initializeLoader() {
            // No-op: keeps filtering working if loader helper is unavailable.
        }

        function getSelectedValues(selectId) {
            const selectElement = document.getElementById(selectId)

            if (!selectElement) {
                return []
            }

            return Array.from(selectElement.selectedOptions).map(option => option.value)
        }

        function formHandler() {
            const data = {
                "category": getSelectedValues("select-output-type"),
                "title": document.getElementById("title").value,
                "keywords": getSelectedValues("select-keywords"),
                "citationNames": getSelectedValues("select-citations"),
                "status": getSelectedOptions(".output-status:checked", "checkbox", "class")
            }

            const startYear = document.getElementById("startYear").innerHTML;

            const endYear = document.getElementById("endYear").innerHTML;

            if (startYear != oldestDate || parseInt(endYear) != 2023) {
                data.startYear = startYear;

                data.endYear = endYear;
            }

            initializeLoader()

            $.ajax({
                type: 'GET',
                url: '{{ route('outputs.filter') }}',
                data,
                success: function(data, status) {
                    const output = data[0].filteredResults;


                    let link, publisherSpan;

                    var table = $('#outputs_table').DataTable();

                    // Removes all rows from the table
                    table.clear();

                    // Create the rows of the table using the data that cames from the request
                    output.forEach(element => {

                        let authorName = (element.authors.length != 0) ? element.authors[0].name :
                            "<mark> {{ __('Sem Autor') }} <mark>"

                        let year = ""

                        if (element.year !== null) {
                            year = element.year
                        }

                        link = generateLink(element.doi)

                        publisherSpan = generateSpanElement(showPublisher(element), true)

                        let title = (year == "") ? element.title : element.title + ", " + year

                        table.row.add([
                            `
                            <div class="w-full space-y-1">
                                ${element.citation_string ? `<cite class="not-italic text-xs font-normal text-slate-500 block leading-relaxed line-clamp-2">${element.citation_string}</cite>` : ''}
                                <h4 class="text-sm sm:text-base font-bold text-slate-900 tracking-tight leading-snug m-0">
                                    ${title}
                                </h4>
                                <div class="flex flex-wrap items-center gap-2 pt-0.5 text-xs text-slate-500">
                                    ${publisherSpan}
                                    ${link}
                                </div>
                            </div>`,
                            `<span class="badge badge-${statusBadgeClass(element.polymorphic?.status)} rounded-pill d-inline">${(element.polymorphic?.status) ?? "{{ __('Sem Estado') }}"}</span>`,
                            `<span class="badge badge-${typeBadgeClass(element.type?.name)} rounded-pill d-inline">${(element.type?.name) ?? "{{ __('Sem Tipo') }}"}</span>`,
                        ]);

                    });

                    // Show the added rows
                    table.draw();

                    removeLoader()
                },
                error: function(data, status) {
                    removeLoader()
                }
            });

            return false;
        }

        function submitForm() {
            const formId = "form-filter"

            document.getElementById(formId).submit = formHandler;
            document.getElementById(formId).submit();
        }

        function isValidUrl(string) {
            let success = true;

            try {
                new URL(string);
            } catch (err) {
                success = false;
            }

            return success
        }

        function generateLink(string, returnLinkAsString = true) {
            let [url, link] = ["https://www.doi.org/" + string, ""]


            if (string != "") {
                if (isValidUrl(string)) {
                    url = string
                }

                if (returnLinkAsString) {
                    link = `<a href="${url}" target="_blank" rel="nofollow noopener noreferrer" class="inline-flex items-center gap-1 font-semibold text-emerald-700 hover:text-emerald-900 hover:underline transition-colors ml-auto sm:ml-0"><span>Aceder à Publicação</span> <i class="fas fa-arrow-up-right-from-square text-[10px]"></i></a>`
                } else {
                    link = document.createElement("a")

                    link.hreaf = url
                }
            }



            return link
        }

        function showPublisher(element) {

            console.log(element.polymorphic.publisher);

            if (element.polymorphic.publisher === null) {
                return "Sem Publicador"
            } else {
                return element.polymorphic.publisher
            }
        }

        function generateSpanElement(content, success = true) {
            let spanColor = (success) ? "success" : "danger"

            contentToshow = (content != "") ?
                `<span class="badge badge-${spanColor} rounded-pill d-inline">${content}</span>` : ""

            return contentToshow
        }

        function statusBadgeClass(status) {
            const map = {
                'published': 'success',
                'accepted': 'primary',
                'in review': 'warning',
                'under revision': 'warning',
                'submitted': 'danger',
                'in print': 'info'
            }

            const key = (status || '').toString().toLowerCase()
            return map[key] || 'secondary'
        }

        function typeBadgeClass(typeName) {
            const key = (typeName || '').toString().toLowerCase()

            if (key.includes('book')) return 'success'
            if (key.includes('journal')) return 'primary'
            if (key.includes('conference')) return 'info'
            if (key.includes('abstract')) return 'warning'
            if (key.includes('thesis') || key.includes('dissertation')) return 'dark'

            return 'secondary'
        }

        function initalizeSearch() {
            let submitSearch = false

            Array.from(document.querySelectorAll('option:checked')).map(
                option => {
                    let chipElementExists = document.querySelectorAll(`[element-id="${option.id}"]`).length > 0

                    // if one span is created and is empty check if you are with debug bar active
                    if (option.parentElement.name != 'outputs_table_length') {
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
                }
            )

            Array.from(document.querySelectorAll('.output-status:checked')).map(
                option => {
                    let chipElementExists = document.querySelectorAll(`[element-id="${option.id}"]`).length > 0

                    // if one span is created and is empty check if you are with debug bar active
                    if (option.parentElement.name != 'outputs_table_length') {
                        createElement(
                            'DIV',
                            'filtred-filds',
                            `{{ __('${option.value}') }} <i class="fas fa-times fa-lg">`,
                            'element-id',
                            option.id, {
                                type: 'button',
                                class: 'chip filtred-filds',
                                'element-id': option.id,
                                'parent-type': 'checkBox',
                                useAttribute: true
                            }
                        )
                    }

                    return option
                }
            )

            const titleElement = document.getElementById("title");

            if (titleElement.value.replace(/\s/g, '') != "") {
                createElement("DIV", "filtred-filds", `${titleElement.value} <i class="fas fa-times fa-lg">`,
                    "element-id",
                    titleElement.id, {
                        "type": "button",
                        "class": "chip filtred-filds",
                        "element-id": titleElement.id,
                        "parent-type": "input"
                    });
            }


            if (document.querySelectorAll('.filtred-filds').length > 0) {
                const startYear = document.getElementById("startYear").innerHTML;

                const endYear = document.getElementById("endYear").innerHTML;

                createElement("DIV", "filtred-filds", `${startYear} - ${endYear} <i class="fas fa-times fa-lg">`,
                    "parent-type",
                    "year", {
                        "type": "button",
                        "class": "chip filtred-filds",
                        "element-id": "date-to-search",
                        "id": "years-to-search",
                        "parent-type": "year"
                    });

                submitForm()
            }

        }

        /* 
        
        JQuery Section
        
        */
        $(document).ready(function() {
            yearsRange();

            initializeDataTable(['excel', 'pdf'])

            initalizeSearch()

                const authorStatus = document.getElementById('author-status');
                if (authorStatus) {
                    authorStatus.addEventListener('change', submitForm);
                }

            $(':checkbox').change(function(handler) {
                const checkBoxChecked = handler.currentTarget.checked

                let checkBoxId = handler.currentTarget.id

                if (checkBoxChecked) {
                    let value =
                        handler.currentTarget.previousSibling.parentElement.children[1]
                        .innerText

                    const attributes = {
                        type: 'button',
                        class: 'chip filtred-filds',
                        'element-id': checkBoxId,
                        'parent-type': 'checkBox'
                    }

                    createElement(
                        'DIV',
                        'filtred-filds',
                        `${value} <i class="fas fa-times fa-lg">`,
                        'element-id',
                        checkBoxId,
                        attributes
                    )
                } else {
                    deleteElement('element-id', checkBoxId)
                }

                submitForm()
            })

            /*
            Code Used to show the tool tip when the user searches by title
            */
            $("#search-by-title-button").click(function(handler) {

                const titleToSearch = document.getElementById("title").value;

                const titleId = document.getElementById("title").id;

                const attributes = {
                    "type": "button",
                    "class": "chip filtred-filds",
                    "element-id": titleId,
                    "parent-type": "input"
                }

                let chipElementExists =
                    document.querySelectorAll(
                        `[element-id="${titleId}"]`
                    ).length > 0

                if (chipElementExists) {
                    deleteElement('element-id', titleId)
                }

                createElement("DIV", "filtred-filds", `${titleToSearch} <i class="fas fa-times fa-lg">`,
                    "element-id",
                    titleId, attributes);

                submitForm();
            });


            /*
                    
              Prevents problems when the user makes enter in the input box
            
            */
            $("#form-filter").on("submit", function(event) {

                submitForm();

                event.preventDefault();
            });

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

        });
    </script>

</x-app-layout>
