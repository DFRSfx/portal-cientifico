<x-app-layout>

    @php
        $routes = [
            'authors.employments' => 'Percurso profissional',
            'authors.outputs' => 'Publicações',
            'authors.statistics' => 'Estatísticas',
            'authors.show' => 'Informação básica',
            'authors.activities' => 'Atividades',

        ];

        $routeText = $routes[request()->route()->getName()] ?? '';
    @endphp

    <x-slot:title>
        {{ __('' . $routeText) }}
    </x-slot>

    @push('header-links')
        <!-- DataTables Bootstrap -->
        <link
            href="https://cdn.datatables.net/v/bs5/jq-3.7.0/jszip-3.10.1/dt-1.13.8/b-2.4.2/b-colvis-2.4.2/b-html5-2.4.2/b-print-2.4.2/datatables.min.css"
            rel="stylesheet">
    @endpush

    @push('header-scripts')
        <script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.2.7/pdfmake.min.js"></script>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.2.7/vfs_fonts.js"></script>
        <script
            src="https://cdn.datatables.net/v/bs5/jq-3.7.0/jszip-3.10.1/dt-1.13.8/b-2.4.2/b-colvis-2.4.2/b-html5-2.4.2/b-print-2.4.2/datatables.min.js">
        </script>
    @endpush

    <style>
        .scientific-production-menu {
            max-height: calc(100vh - 220px);
            overflow-y: auto;
            -webkit-overflow-scrolling: touch;
            overscroll-behavior: contain;
            touch-action: pan-y;
            z-index: 1060;
        }

        .author-identifiers-menu {
            display: none;
        }

        .author-identifiers-menu.show {
            display: block;
        }

        .sp-panel,
        .sp-backdrop,
        .ai-panel,
        .ai-backdrop {
            display: none;
        }

        .sp-panel,
        .sp-backdrop {
            display: none;
        }

        @media (max-width: 768px) {
            .scientific-production-menu {
                display: none !important;
            }

            .scientific-production-menu {
                display: none !important;
            }

            .scientific-production-menu.show {
                display: none !important;
            }

            .sp-panel {
                position: fixed;
                left: 12px;
                right: 12px;
                top: 12px;
                bottom: 12px;
                background: #fff;
                border-radius: 14px;
                box-shadow: 0 18px 40px rgba(0, 0, 0, 0.18);
                z-index: 1070;
                display: none;
                flex-direction: column;
                overflow: hidden;
            }

            .sp-panel.open {
                display: flex;
            }

            .sp-panel-header {
                display: flex;
                align-items: center;
                justify-content: space-between;
                padding: 12px 14px;
                border-bottom: 1px solid #e6efe6;
                font-weight: 600;
            }

            .sp-panel-body {
                overflow-y: auto;
                -webkit-overflow-scrolling: touch;
            }

            .sp-backdrop {
                position: fixed;
                inset: 0;
                background: rgba(0, 0, 0, 0.35);
                z-index: 1065;
                display: none;
            }

            .sp-backdrop.open {
                display: block;
            }

            .author-identifiers-menu {
                display: none !important;
            }

            .ai-panel {
                position: fixed;
                left: 12px;
                right: 12px;
                top: 12px;
                bottom: 12px;
                background: #fff;
                border-radius: 14px;
                box-shadow: 0 18px 40px rgba(0, 0, 0, 0.18);
                z-index: 1070;
                display: none;
                flex-direction: column;
                overflow: hidden;
            }

            .ai-panel.open {
                display: flex;
            }

            .ai-panel-header {
                display: flex;
                align-items: center;
                justify-content: space-between;
                padding: 12px 14px;
                border-bottom: 1px solid #e6efe6;
                font-weight: 600;
            }

            .ai-panel-body {
                overflow-y: auto;
                -webkit-overflow-scrolling: touch;
            }

            .ai-backdrop {
                position: fixed;
                inset: 0;
                background: rgba(0, 0, 0, 0.35);
                z-index: 1065;
                display: none;
            }

            .ai-backdrop.open {
                display: block;
            }
        }

        @media (min-width: 769px) {
            .sp-panel,
            .sp-backdrop,
            .ai-panel,
            .ai-backdrop {
                display: none !important;
            }
        }
    </style>

    {{-- Bootstrap Bundle removido: o projeto já carrega `mdb.min.js` no layout global --}}


    <section class="saas-page">
        <div class="container saas-profile saas-profile-wide py-3 ">
            <div class="row g-3">
                <div class="col-xl-3">
                    <div class="card testimonial-card mb-3 saas-sticky">
                        <div class="card-up"></div>
                        <div class="avatar mx-auto white">
                            <x-user-image showLogedUserImage="0"
                                cienciaVitae="{{ $author->userInformation->ciencia_vitae }}"
                                cienciaVitaeImageIsPublic="{{ $author->profile_is_public && $author->profile_image_is_public }}"
                                class="rounded-circle img-fluid" />
                        </div>

                        <div class="card-body">
                            <h5 class="font-weight-bolder mb-0">
                                {{ $author->userInformation->name ?? __('Sem Valor') }}
                            </h5>
                            @php
                                $rawEntities = [];
                                $entityName = $author->userInformation->entidade ?? null;

                                if (is_string($entityName) && trim($entityName) !== '') {
                                    $rawEntities[] = $entityName;
                                }

                                if (!empty($author->userInformation->entities) && is_array($author->userInformation->entities)) {
                                    foreach ($author->userInformation->entities as $value) {
                                        if (is_string($value) && trim($value) !== '') {
                                            $rawEntities[] = $value;
                                        }
                                    }
                                }

                                $expandedEntities = [];
                                foreach ($rawEntities as $value) {
                                    $parts = preg_split('/\s*[\/;,]\s*/', $value);
                                    foreach ($parts as $part) {
                                        $trimmed = trim($part);
                                        if ($trimmed !== '') {
                                            $expandedEntities[] = $trimmed;
                                        }
                                    }
                                }

                                $expandedEntities = array_values(array_unique($expandedEntities));
                                $entityCount = count($expandedEntities);
                                $entityName = $entityCount > 1 ? 'All institutions' : ($expandedEntities[0] ?? null);
                            @endphp
                            @if (!isset($author->profile_updated_date))
                                <div>
                                    <span class="badge rounded-pill badge-success">{{ $author->userInformation->type }}</span>
                                    <span class="badge rounded-pill badge-success">{{ __('No Profile Status') }}</span>
                                </div>
                            @else
                                <div>
                                    <span class="badge rounded-pill badge-success">{{ $author->userInformation->type }}</span>
                                </div>
                            @endif
                            @if ($entityName)
                                @if ($entityCount > 1)
                                    <div class="dropdown d-inline-block mt-1">
                                        <button class="btn btn-sm btn-light dropdown-toggle" type="button"
                                            id="authorEntitiesDropdown" data-mdb-toggle="dropdown" aria-expanded="false"
                                            style="padding: 6px 12px; border: 1px solid #2ac41cff; border-radius: 6px;">
                                            {{ $entityName }}
                                        </button>
                                        <ul class="dropdown-menu" aria-labelledby="authorEntitiesDropdown">
                                            @foreach($expandedEntities as $entityLabel)
                                                <li><span class="dropdown-item-text">{{ $entityLabel }}</span></li>
                                            @endforeach
                                        </ul>
                                    </div>
                                @else
                                    <small class="badge text-muted d-inline-block mt-1"
                                        style="padding: 6px 12px; border: 1px solid #2ac41cff; border-radius: 6px;">
                                        {{ $entityName }}
                                    </small>
                                @endif
                            @endif

                            <div class="my-3">
                                @php
                                    $url = 'https://www.cienciavitae.pt/pt/' . $author->userInformation->ciencia_vitae;
                                @endphp

                                @php
                                    $cienciaVitaeId = trim((string) ($author->userInformation->ciencia_vitae ?? ''));
                                    $orcid = trim((string) ($author->orcid ?? ''));
                                    $scopus = trim((string) ($author->id_scopus_author ?? ''));
                                    $googleScholar = trim((string) ($author->id_google_scholar ?? ''));
                                    // Nota: neste projeto o Ciência Vitae pode enviar o "Researcher Id" com um URL (ex.: ResearchGate).
                                    // Preferimos o campo oficial `id_researcher`, mas fazemos fallback para `researchgate_profile` para não perder valores já guardados.
                                    $wosResearcher = trim((string) ($author->id_researcher ?? ''));
                                    if ($wosResearcher === '') {
                                        $wosResearcher = trim((string) ($author->researchgate_profile ?? ''));
                                    }
                                    $lattes = trim((string) ($author->id_lattes ?? ''));

                                    // Ciencia Vitae may label this identifier as IDAIC.
                                    // If the value is already a URL (e.g., ResearchGate profile), link directly.
                                    // If it looks like a ResearcherID (e.g., A-1234-2012), link directly to WoS author record.
                                    // Otherwise, fall back to a search for IDAIC <value>.
                                    $wosUrl = null;
                                    if ($wosResearcher !== '') {
                                        if (str_starts_with($wosResearcher, 'http://') || str_starts_with($wosResearcher, 'https://')) {
                                            $wosUrl = $wosResearcher;
                                        } elseif (str_contains(strtolower($wosResearcher), 'researchgate.')) {
                                            $wosUrl = 'https://' . ltrim($wosResearcher, '/');
                                        } elseif (preg_match('/^[A-Z]-\d{4}-\d{4}$/i', $wosResearcher)) {
                                            $wosUrl = 'https://www.webofscience.com/wos/author/record/' . rawurlencode($wosResearcher);
                                        } else {
                                            $wosUrl = 'https://www.google.com/search?q=' . rawurlencode('IDAIC ' . $wosResearcher);
                                        }
                                    }

                                    // Human-friendly display values (keep URLs clickable but show only the identifier part)
                                    $wosResearcherDisplay = $wosResearcher;
                                    if ($wosResearcherDisplay !== '') {
                                        $candidateUrl = null;
                                        if (str_starts_with($wosResearcherDisplay, 'http://') || str_starts_with($wosResearcherDisplay, 'https://')) {
                                            $candidateUrl = $wosResearcherDisplay;
                                        } elseif (str_contains(strtolower($wosResearcherDisplay), 'researchgate.')) {
                                            $candidateUrl = 'https://' . ltrim($wosResearcherDisplay, '/');
                                        }

                                        if ($candidateUrl) {
                                            $path = (string) (parse_url($candidateUrl, PHP_URL_PATH) ?? '');
                                            $slug = trim(basename(rtrim($path, '/')), '/');
                                            if ($slug !== '') {
                                                $wosResearcherDisplay = $slug;
                                            }
                                        }
                                    }

                                    $lattesUrl = null;
                                    if ($lattes !== '') {
                                        // If API already gives a URL, keep it; otherwise build the canonical Lattes CV URL.
                                        $lattesUrl = (str_starts_with($lattes, 'http://') || str_starts_with($lattes, 'https://'))
                                            ? $lattes
                                            : ('http://lattes.cnpq.br/' . ltrim($lattes, '/'));
                                    }

                                    $lattesDisplay = $lattes;
                                    if ($lattesDisplay !== '' && (str_starts_with($lattesDisplay, 'http://') || str_starts_with($lattesDisplay, 'https://'))) {
                                        $path = (string) (parse_url($lattesDisplay, PHP_URL_PATH) ?? '');
                                        $slug = trim(basename(rtrim($path, '/')), '/');
                                        if ($slug !== '') {
                                            $lattesDisplay = $slug;
                                        }
                                    }

                                    $identifierRows = [
                                        [
                                            'label' => 'Ciencia Vitae',
                                            'value' => $cienciaVitaeId,
                                            'title' => 'Ciencia Vitae ID',
                                            'icon' => '/logo/cienciaVitae.svg',
                                            'url' => $cienciaVitaeId !== '' ? $url : null,
                                        ],
                                        [
                                            'label' => 'ORCID',
                                            'value' => $orcid,
                                            'title' => 'ORCID ID',
                                            'icon' => '/logo/orcid.svg',
                                            'url' => $orcid !== '' ? ('https://orcid.org/' . $orcid) : null,
                                        ],
                                        [
                                            'label' => 'Scopus',
                                            'value' => $scopus,
                                            'title' => 'Scopus Author ID',
                                            'icon' => '/logo/Icon_-_Scopus.png',
                                            'url' => $scopus !== '' ? ('https://www.scopus.com/authid/detail.uri?authorId=' . $scopus) : null,
                                        ],
                                        [
                                            'label' => 'Google Scholar',
                                            'value' => $googleScholar,
                                            'title' => 'Google Scholar ID',
                                            'icon' => '/logo/Google_Scholar_logo.svg.png',
                                            'url' => $googleScholar !== '' ? ('https://scholar.google.com/citations?user=' . $googleScholar) : null,
                                        ],
                                        [
                                            'label' => 'Researcher Id',
                                            'value' => $wosResearcherDisplay,
                                            'title' => 'Researcher Id (IDAIC)',
                                            'icon' => '/logo/ResearchGate_icon_SVG.svg',
                                            'url' => $wosUrl,
                                        ],
                                        // When the Researcher Id actually resolves to Web of Science,
                                        // also expose it explicitly as a "Web of Science" identifier.
                                        [
                                            'label' => 'Web of Science',
                                            'value' => (is_string($wosUrl) && str_contains($wosUrl, 'webofscience.com')) ? $wosResearcherDisplay : '',
                                            'title' => 'Web of Science (Researcher Id)',
                                            'icon' => null,
                                            'url' => (is_string($wosUrl) && str_contains($wosUrl, 'webofscience.com')) ? $wosUrl : null,
                                        ],
                                        [
                                            'label' => 'LattesID',
                                            'value' => $lattesDisplay,
                                            'title' => 'LattesID',
                                            'icon' => '/logo/lattes.svg',
                                            'url' => $lattesUrl,
                                        ],
                                    ];
                                @endphp

                                @php
                                    $identifierRowsWithValue = array_values(array_filter($identifierRows, fn ($r) => !empty($r['value'])));
                                    $identifiersCount = count($identifierRowsWithValue);
                                @endphp

                                @if ($identifiersCount > 0)
                                    <div class="dropdown mt-3">
                                        <button class="btn w-100 d-flex justify-content-between align-items-center"
                                                type="button"
                                                id="dropdownIdentifiers"
                                                data-mdb-toggle="dropdown"
                                                aria-expanded="false"
                                                style="
                                                    background-color: rgba(255, 255, 255, 0.9);
                                                    border: 1px solid #2ac41cff;
                                                    border-radius: 8px;
                                                    font-weight: bold;
                                                    padding: 10px 14px;
                                                    box-shadow: 0 1px 3px rgba(0, 0, 0, 0.1);
                                                    transition: all 0.2s ease-in-out;
                                                ">
                                            <span>{{ __('Identificadores de Autor') }}</span>
                                            <span class="d-inline-flex align-items-center gap-3">
                                                <span class="text-primary">{{ $identifiersCount }}</span>
                                                <i class="fas fa-caret-down"></i>
                                            </span>
                                        </button>

                                        <ul class="dropdown-menu w-100 shadow-sm mt-1 p-2 author-identifiers-menu" aria-labelledby="dropdownIdentifiers" style="border-radius: 12px;">
                                            @foreach ($identifierRowsWithValue as $row)
                                                <li class="mb-1">
                                                    @php
                                                        $content = '<div class="d-flex align-items-center gap-2">'
                                                            . (!empty($row['icon'])
                                                                ? ('<span class="d-inline-flex align-items-center justify-content-center border rounded-circle" style="width:34px;height:34px;background:#fff;">'
                                                                    . '<img src="' . e(asset($row['icon'])) . '" alt="' . e($row['label']) . '" style="width:20px;height:20px;" />'
                                                                    . '</span>')
                                                                : '<span class="d-inline-flex align-items-center justify-content-center border rounded-circle" style="width:34px;height:34px;background:#fff;"></span>')
                                                            . '<div class="d-flex flex-column min-w-0" style="max-width: 260px;">'
                                                            . '<span class="text-muted" style="font-size: 12px; line-height: 1.1;">'
                                                            . e($row['label'])
                                                            . '</span>'
                                                            . '<span class="font-monospace small text-dark text-truncate" title="' . e($row['title']) . '" style="line-height: 1.2;">'
                                                            . e($row['value'])
                                                            . '</span>'
                                                            . '</div>'
                                                            . (!empty($row['url']) ? '<span class="ms-auto text-muted small">↗</span>' : '')
                                                            . '</div>';
                                                    @endphp

                                                    @if (!empty($row['url']))
                                                        <a class="dropdown-item rounded-3 py-2" href="{{ $row['url'] }}" target="_blank" rel="noopener noreferrer">{!! $content !!}</a>
                                                    @else
                                                        <div class="dropdown-item rounded-3 py-2" style="cursor: default;">{!! $content !!}</div>
                                                    @endif
                                                </li>
                                            @endforeach
                                        </ul>

                                        <div class="ai-backdrop" id="aiBackdrop"></div>
                                        <div class="ai-panel" id="aiPanel" aria-hidden="true">
                                            <div class="ai-panel-header">
                                                <span>{{ __('Identificadores de Autor') }}</span>
                                                <button type="button" class="btn btn-sm btn-light" id="aiClose">{{ __('Fechar') }}</button>
                                            </div>
                                            <div class="ai-panel-body">
                                                <ul class="dropdown-menu w-100 shadow-sm" style="position: static; display: block; box-shadow: none; border: 0;">
                                                    @foreach ($identifierRowsWithValue as $row)
                                                        <li class="mb-1">
                                                            @php
                                                                $content = '<div class="d-flex align-items-center gap-2">'
                                                                    . (!empty($row['icon'])
                                                                        ? ('<span class="d-inline-flex align-items-center justify-content-center border rounded-circle" style="width:34px;height:34px;background:#fff;">'
                                                                            . '<img src="' . e(asset($row['icon'])) . '" alt="' . e($row['label']) . '" style="width:20px;height:20px;" />'
                                                                            . '</span>')
                                                                        : '<span class="d-inline-flex align-items-center justify-content-center border rounded-circle" style="width:34px;height:34px;background:#fff;"></span>')
                                                                    . '<div class="d-flex flex-column min-w-0" style="max-width: 260px;">'
                                                                    . '<span class="text-muted" style="font-size: 12px; line-height: 1.1;">'
                                                                    . e($row['label'])
                                                                    . '</span>'
                                                                    . '<span class="font-monospace small text-dark text-truncate" title="' . e($row['title']) . '" style="line-height: 1.2;">'
                                                                    . e($row['value'])
                                                                    . '</span>'
                                                                    . '</div>'
                                                                    . (!empty($row['url']) ? '<span class="ms-auto text-muted small">↗</span>' : '')
                                                                    . '</div>';
                                                            @endphp

                                                            @if (!empty($row['url']))
                                                                <a class="dropdown-item rounded-3 py-2" href="{{ $row['url'] }}" target="_blank" rel="noopener noreferrer">{!! $content !!}</a>
                                                            @else
                                                                <div class="dropdown-item rounded-3 py-2" style="cursor: default;">{!! $content !!}</div>
                                                            @endif
                                                        </li>
                                                    @endforeach
                                                </ul>
                                            </div>
                                        </div>
                                    </div>
                                @endif

                            </div>
                            
                            <hr class="mb-2">
                                <div class="list-group-item border-0 font-weight-bold p-0">
                                    <div class="dropdown w-100">
                                        @php
                                            $uniqueOutputs = $author->output->unique('id')->values();
                                            $groupedOutputs = $uniqueOutputs->groupBy('type.name');
                                        @endphp
                                        <button class="btn w-100 d-flex justify-content-between align-items-center"
                                                type="button"
                                                id="dropdownScientificProduction"
                                                data-mdb-toggle="dropdown"
                                                aria-expanded="false"
                                                style="
                                                    background-color: rgba(255, 255, 255, 0.9);
                                                    border: 1px solid #2ac41cff;
                                                    border-radius: 8px;
                                                    font-weight: bold;
                                                    padding: 10px 14px;
                                                    box-shadow: 0 1px 3px rgba(0, 0, 0, 0.1);
                                                    transition: all 0.2s ease-in-out;
                                                ">
                                                <span>{{ __('Produção científica') }}</span>
                                                <span class="d-inline-flex align-items-center gap-3">
                                                    <span class="text-primary">{{ $uniqueOutputs->count() }}</span>
                                                    <i class="fas fa-caret-down"></i>
                                                </span>
                                            </button>

                                        <ul class="dropdown-menu w-100 shadow-sm scientific-production-menu"
                                            aria-labelledby="dropdownScientificProduction">
                                            <li>
                                                <a class="dropdown-item d-flex justify-content-between align-items-center"
                                                   href="{{ route('authors.outputs', ['authorsId' => $author->id]) }}">
                                                    <span>{{ __('All') }}</span>
                                                    <span class="text-success fw-bold">{{ $uniqueOutputs->count() }}</span>
                                                </a>
                                            </li>
                                            @forelse ($groupedOutputs as $typeName => $outputs)
                                                <li>
                                                    <a class="dropdown-item d-flex justify-content-between align-items-center"
                                                    href="{{ route('authors.outputs', ['authorsId' => $author->id, 'type' => $typeName]) }}">
                                                        <span>{{ $typeName }}</span>
                                                        <span class="text-success fw-bold">{{ $outputs->count() }}</span>
                                                    </a>
                                                </li>
                                            @empty
                                                <li><span class="dropdown-item text-muted">{{ __('Sem publicações') }}</span></li>
                                            @endforelse
                                        </ul>

                                        <div class="sp-backdrop" id="spBackdrop"></div>
                                        <div class="sp-panel" id="spPanel" aria-hidden="true">
                                            <div class="sp-panel-header">
                                                <span>{{ __('Produção científica') }}</span>
                                                <button type="button" class="btn btn-sm btn-light" id="spClose">{{ __('Fechar') }}</button>
                                            </div>
                                            <div class="sp-panel-body">
                                                <ul class="dropdown-menu w-100 shadow-sm" style="position: static; display: block; box-shadow: none; border: 0;">
                                                    <li>
                                                        <a class="dropdown-item d-flex justify-content-between align-items-center"
                                                           href="{{ route('authors.outputs', ['authorsId' => $author->id]) }}">
                                                            <span>{{ __('All') }}</span>
                                                            <span class="text-success fw-bold">{{ $uniqueOutputs->count() }}</span>
                                                        </a>
                                                    </li>
                                                    @forelse ($groupedOutputs as $typeName => $outputs)
                                                        <li>
                                                            <a class="dropdown-item d-flex justify-content-between align-items-center"
                                                            href="{{ route('authors.outputs', ['authorsId' => $author->id, 'type' => $typeName]) }}">
                                                                <span>{{ $typeName }}</span>
                                                                <span class="text-success fw-bold">{{ $outputs->count() }}</span>
                                                            </a>
                                                        </li>
                                                    @empty
                                                        <li><span class="dropdown-item text-muted">{{ __('Sem publicações') }}</span></li>
                                                    @endforelse
                                                </ul>
                                            </div>
                                        </div>

                                    </div>
                                </div>

                            <hr class="mt-2 mb-0">
                            <x-ciencia-vitae-update-profile-button authorId="{{ $author->id }}" />

                        </div>
                    </div>
                </div>

                <div class="col-xl-9">
                    <div class="card mb-2">
                        <div class="card-body">

                            <!-- Tabs navs -->
                            <ul class="nav nav-tabs nav-justified mb-2" id="ex1" role="tablist">
                                <li class="nav-item" role="presentation">
                                    <a class="{{ request()->routeIs('authors.show') ? 'nav-link active' : 'nav-link' }}"
                                        style="color:#2A6B20 ;"
                                        href="{{ route('authors.show', $author->id) }}">{{ __('Informação pessoal') }}</a>
                                </li>
                                <li class="nav-item" role="presentation">
                                    <a class="{{ request()->routeIs('authors.employments') ? 'nav-link active' : 'nav-link' }}"
                                        style="color:#2A6B20 ;"
                                        href="{{ route('authors.employments', ['id' => $author->id]) }}">{{ __('Percurso profissional') }}</a>
                                </li>
                                <li class="nav-item" role="presentation">
                                    <a class="{{ request()->routeIs('authors.outputs') ? 'nav-link active' : 'nav-link' }}"
                                    style="color:#2A6B20 ;"
                                    href="{{ route('authors.outputs', ['authorsId' => $author->id]) }}">{{ __('Publicações') }}</a>
                                </li>
                                <li class="nav-item" role="presentation">
                                    <a class="{{ request()->routeIs('authors.projects') ? 'nav-link active' : 'nav-link' }}"
                                    style="color:#2A6B20 ;"
                                    href="{{ route('authors.projects', ['authorsId' => $author->id]) }}">{{ __('Projetos') }}</a>
                                </li>
                                <li class="nav-item" role="presentation">
                                    <a class="{{ request()->routeIs('authors.activities') ? 'nav-link active' : 'nav-link' }}"
                                    style="color:#2A6B20 ;"
                                    href="{{ route('authors.activities', ['authorsId' => $author->id]) }}">{{ __('Atividades') }}</a>
                                </li>
                                <li class="nav-item" role="presentation">
                                    <a class="{{ request()->routeIs('authors.statistics') ? 'nav-link active' : 'nav-link' }}"
                                        style="color:#2A6B20 ;"
                                        href="{{ route('authors.statistics', ['id' => $author->id]) }}">{{ __('Estatísticas') }}</a>
                                </li>
                            </ul>
                            <!-- Tabs navs -->

                            <!-- Tabs content -->
                            <div class="tab-content" id="ex2-content">
                                <div class="card-body">
                                    <div class="accordion accordion-borderless" id="accordion">
                                        @yield('author-information')
                                    </div>
                                </div>
                                <!-- Tabs content -->
                            </div>
                        </div>
                    </div>
                </div>

            </div>
    </section>

    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const identifiersBtn = document.getElementById('dropdownIdentifiers');
            const identifiersMenu = document.querySelector('.author-identifiers-menu');
            const aiPanel = document.getElementById('aiPanel');
            const aiBackdrop = document.getElementById('aiBackdrop');
            const aiClose = document.getElementById('aiClose');
            const btn = document.getElementById('dropdownScientificProduction');
            const menu = document.querySelector('.scientific-production-menu');
            const panel = document.getElementById('spPanel');
            const backdrop = document.getElementById('spBackdrop');
            const closeBtn = document.getElementById('spClose');

            if (!btn || !menu) {
                return;
            }

            if (identifiersBtn && identifiersMenu && aiPanel && aiBackdrop) {
                identifiersBtn.addEventListener('click', function (event) {
                    if (window.innerWidth <= 768) {
                        event.preventDefault();
                        event.stopPropagation();
                        identifiersBtn.removeAttribute('data-mdb-toggle');
                        identifiersMenu.classList.remove('show');
                        identifiersMenu.style.display = 'none';
                        aiPanel.classList.add('open');
                        aiPanel.setAttribute('aria-hidden', 'false');
                        aiBackdrop.classList.add('open');
                        document.body.style.overflow = 'hidden';
                    }
                });

                const closeIdentifiersPanel = function () {
                    aiPanel.classList.remove('open');
                    aiPanel.setAttribute('aria-hidden', 'true');
                    aiBackdrop.classList.remove('open');
                    document.body.style.overflow = '';
                };

                aiBackdrop.addEventListener('click', closeIdentifiersPanel);
                aiClose.addEventListener('click', closeIdentifiersPanel);
                document.addEventListener('keydown', function (event) {
                    if (event.key === 'Escape') {
                        closeIdentifiersPanel();
                    }
                });
            }

            function positionMenu() {
                if (window.innerWidth > 768) {
                    menu.classList.remove('is-fixed');
                    menu.style.top = '';
                    menu.style.maxHeight = '';
                    return;
                }

                const rect = btn.getBoundingClientRect();
                const top = rect.bottom + window.scrollY;
                menu.classList.add('is-fixed');
                menu.style.top = `${top}px`;
                menu.style.maxHeight = `${Math.max(window.innerHeight - top - 12, 120)}px`;
            }
            function openPanel() {
                if (!panel || !backdrop) {
                    return;
                }
                panel.classList.add('open');
                panel.setAttribute('aria-hidden', 'false');
                backdrop.classList.add('open');
                document.body.style.overflow = 'hidden';
            }

            function closePanel() {
                if (!panel || !backdrop) {
                    return;
                }
                panel.classList.remove('open');
                panel.setAttribute('aria-hidden', 'true');
                backdrop.classList.remove('open');
                document.body.style.overflow = '';
            }

            btn.addEventListener('click', function (event) {
                if (window.innerWidth <= 768) {
                    event.preventDefault();
                    event.stopPropagation();
                    btn.removeAttribute('data-mdb-toggle');
                    menu.classList.remove('show');
                    menu.style.display = 'none';
                    openPanel();
                    return;
                }
                positionMenu();
                requestAnimationFrame(positionMenu);
                setTimeout(positionMenu, 0);
            });

            backdrop?.addEventListener('click', closePanel);
            closeBtn?.addEventListener('click', closePanel);
            document.addEventListener('keydown', function (event) {
                if (event.key === 'Escape') {
                    closePanel();
                }
            });
            window.addEventListener('resize', positionMenu);
        });
    </script>

    <div class="alert alert-dismissible fade " id="show-message" role="alert" data-mdb-color="primary"
        data-mdb-width="600px" data-mdb-hidden="true" data-mdb-autohide="true" data-mdb-append-to-body="true"
        data-mdb-delay="2000" data-mdb-position="bottom-right">
        <i class="dsadasd"></i>
        <button type="button" class="btn-close ms-2" data-mdb-dismiss="alert"
            aria-label="{{ __('Close') }}"></button>
    </div>


</x-app-layout>
