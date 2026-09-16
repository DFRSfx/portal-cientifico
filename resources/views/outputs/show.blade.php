<x-app-layout>
    <x-slot:title>
        {{ $output->title ?? __('Detalhes da Publicação') }}
    </x-slot>

    @php
        $poly = $output->polymorphic;
        $institutionAuthors = $institutionAuthors ?? collect();
        $relatedOutputs = $relatedOutputs ?? collect();

        // Venue / Publication venue
        $venue = $poly->journal ?? $poly->conference_name ?? $poly->proceedings_title ?? $poly->book_title ?? $poly->magazine ?? $poly->newspaper ?? $poly->institutions_list ?? null;

        // Pages
        $startPage = $poly->start_page ?? $poly->chapter_start_page ?? $poly->page_range_from ?? null;
        $endPage = $poly->end_page ?? $poly->chapter_end_page ?? $poly->page_range_to ?? null;
        $totalPages = $poly->pages_number ?? null;

        // Volume & Issue / Edition
        $volume = $poly->volume ?? $poly->book_volume ?? $poly->volumes_number ?? null;
        $issue = $poly->issue ?? $poly->edition ?? $poly->book_edition ?? null;

        // Location
        $locationParts = array_filter([
            $poly->conference_location ?? null,
            $poly->conf_city ?? $poly->city ?? $poly->pub_city ?? $poly->publication_location ?? null,
            $poly->conf_country ?? $poly->country ?? $poly->pub_country ?? null
        ]);
        $location = !empty($locationParts) ? implode(', ', array_unique($locationParts)) : null;

        // Dates
        $day = $poly->publication_day ?? $poly->presentation_day ?? null;
        $month = $poly->publication_month ?? $poly->presentation_month ?? null;
        $year = $poly->publication_year ?? $poly->conference_year ?? $output->year ?? null;

        $monthNames = [
            1 => __('Janeiro'), 2 => __('Fevereiro'), 3 => __('Março'), 4 => __('Abril'),
            5 => __('Maio'), 6 => __('Junho'), 7 => __('Julho'), 8 => __('Agosto'),
            9 => __('Setembro'), 10 => __('Outubro'), 11 => __('Novembro'), 12 => __('Dezembro')
        ];
        $monthName = !empty($month) && isset($monthNames[(int)$month]) ? $monthNames[(int)$month] : null;

        $formattedPublishedDate = '';
        if ($day && $monthName && $year) {
            $formattedPublishedDate = "$day $monthName $year";
        } elseif ($monthName && $year) {
            $formattedPublishedDate = "$monthName $year";
        } elseif ($year) {
            $formattedPublishedDate = (string)$year;
        }

        // External URLs & DOI
        $articleUrl = $poly->url ?? null;
        $cleanDoi = trim((string)$output->doi);
        $doiUrl = null;
        if (!empty($cleanDoi)) {
            $doiUrl = filter_var($cleanDoi, FILTER_VALIDATE_URL) ? $cleanDoi : 'https://doi.org/' . ltrim($cleanDoi, '/');
        }

        // Role & Classification
        $role = $poly->role ?? null;
        $degreeType = $poly->degree_type ?? null;
        $supervisors = $poly->supervisors ?? null;
        $classification = $poly->classification ?? null;
        $publisher = $poly->publisher ?? null;
        $refreed = $poly->refreed ?? null;
        $openAccess = $poly->open_access ?? (!empty($doiUrl) ? true : null);
        $status = $poly->status ?? null;

        // Authors parsing
        $citationAuthors = [];
        if (!empty($output->citation_string)) {
            $rawParts = explode(';', $output->citation_string);
            foreach ($rawParts as $p) {
                $trimmed = trim($p);
                if (!empty($trimmed)) {
                    $citationAuthors[] = $trimmed;
                }
            }
        }

        // Format venue string in Oxford Academic style
        $venueCitationParts = [];
        if (!empty($venue)) {
            $venueCitationParts[] = $venue;
        }
        if (!empty($volume)) {
            $venueCitationParts[] = __('Volume') . ' ' . $volume;
        }
        if (!empty($issue)) {
            $venueCitationParts[] = __('Issue') . ' ' . $issue;
        }
        if (!empty($formattedPublishedDate)) {
            $venueCitationParts[] = $formattedPublishedDate;
        }
        if ($startPage && $endPage) {
            $venueCitationParts[] = "pp. {$startPage}–{$endPage}";
        } elseif ($totalPages) {
            $venueCitationParts[] = "{$totalPages} " . __('páginas');
        }

        $venueCitationString = implode(', ', $venueCitationParts);

        // Display authors for right sidebar
        $displayAuthors = !empty($institutionAuthors) && $institutionAuthors->count() > 0 ? $institutionAuthors : $output->authors;

        // Citations count
        $citationsCount = $output->citations ? $output->citations->count() : 0;
        $viewsCount = $output->views_count ?? 1;
        $coverUrl = $output->cover_image;
    @endphp

    <div class="min-h-screen bg-slate-50/50 py-6 sm:py-10 px-4 sm:px-6 lg:px-8">
        <div class="max-w-[1400px] mx-auto space-y-6">

            {{-- Top Navigation & Breadcrumb --}}
            <div class="flex flex-wrap items-center justify-between gap-3 text-xs sm:text-sm bg-white p-3.5 sm:px-5 rounded-xl border border-slate-200/80 shadow-2xs">
                <nav class="flex items-center gap-2 text-slate-500 overflow-x-auto">
                    <a href="{{ route('home') }}" class="hover:text-emerald-700 transition-colors flex items-center gap-1.5 font-medium shrink-0">
                        <i class="fas fa-house text-xs"></i>
                        <span>{{ __('Início') }}</span>
                    </a>
                    <span class="text-slate-300">/</span>
                    @if(Auth::check())
                        <a href="{{ route('outputs.index') }}" class="hover:text-emerald-700 transition-colors font-medium shrink-0">
                            {{ __('Publicações') }}
                        </a>
                        <span class="text-slate-300">/</span>
                    @endif
                    @if(!empty($venue))
                        <span class="text-slate-600 truncate max-w-[180px] hidden md:inline">{{ $venue }}</span>
                        <span class="text-slate-300 hidden md:inline">/</span>
                    @endif
                    <span class="text-slate-800 font-semibold truncate max-w-[220px] sm:max-w-md">{{ $output->title }}</span>
                </nav>

                <a href="{{ url()->previous() !== url()->current() ? url()->previous() : route('home') }}"
                   class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-lg border border-slate-200 bg-white hover:bg-slate-50 text-slate-700 text-xs font-semibold shadow-2xs transition-colors shrink-0">
                    <i class="fas fa-arrow-left text-[11px] text-slate-400"></i>
                    <span>{{ __('Voltar') }}</span>
                </a>
            </div>

            {{-- 3-COLUMN EDITORIAL GRID (Oxford Academic / The ISME Journal Style) --}}
            <div class="grid grid-cols-1 lg:grid-cols-12 gap-6 lg:gap-8 items-start">

                {{-- ========================================================= --}}
                {{-- LEFT COLUMN: Journal Cover & Article Contents (TOC)       --}}
                {{-- ========================================================= --}}
                <aside class="lg:col-span-3 xl:col-span-3 space-y-6 lg:sticky lg:top-20">
                    {{-- Publication Cape / Cover Card (Only shown if a real cover image exists) --}}
                    @if(!empty($coverUrl))
                        <div class="bg-white border border-slate-200/90 rounded-2xl p-4 sm:p-5 shadow-xs text-center space-y-4">
                            <div class="mx-auto max-w-[200px] relative group">
                                <div class="relative rounded-xl overflow-hidden shadow-md ring-1 ring-slate-900/10 transition-transform duration-200 group-hover:scale-[1.02] bg-slate-100">
                                    <img src="{{ $coverUrl }}" alt="{{ $output->title }}"
                                         class="w-full h-auto aspect-[3/4] object-cover block"
                                         onerror="this.closest('.bg-white').style.display='none';">
                                </div>
                            </div>

                            {{-- Issue Meta Info --}}
                            <div class="space-y-1 text-xs">
                                @if(!empty($volume) || !empty($issue))
                                    <div class="font-semibold text-slate-800">
                                        {{ !empty($volume) ? __('Volume') . ' ' . $volume : '' }}
                                        {{ !empty($issue) ? ', ' . __('Fascículo') . ' ' . $issue : '' }}
                                    </div>
                                @endif
                                <div class="text-slate-500 text-[11px]">
                                    {{ $formattedPublishedDate ?: ($year ?? '-') }}
                                </div>
                            </div>
                        </div>
                    @endif

                    {{-- Sticky Table of Contents (Article Contents) --}}
                    <div class="bg-white border border-slate-200/90 rounded-2xl p-5 shadow-xs space-y-3">
                        <h3 class="text-xs font-bold uppercase tracking-wider text-slate-500 border-b border-slate-100 pb-2 flex items-center gap-2">
                            <i class="fas fa-list-ul text-emerald-600"></i>
                            <span>{{ __('Conteúdos do Artigo') }}</span>
                        </h3>
                        <nav class="space-y-1 text-xs font-medium">
                            <a href="#article-abstract" class="toc-link block px-2.5 py-1.5 rounded-lg text-slate-700 hover:text-emerald-800 hover:bg-emerald-50/70 transition-colors">
                                &bull; {{ __('Resumo (Abstract)') }}
                            </a>
                            @if($output->keywords && $output->keywords->count() > 0)
                                <a href="#article-keywords" class="toc-link block px-2.5 py-1.5 rounded-lg text-slate-700 hover:text-emerald-800 hover:bg-emerald-50/70 transition-colors">
                                    &bull; {{ __('Palavras-chave') }}
                                </a>
                            @endif
                            <a href="#article-details" class="toc-link block px-2.5 py-1.5 rounded-lg text-slate-700 hover:text-emerald-800 hover:bg-emerald-50/70 transition-colors">
                                &bull; {{ __('Detalhes Bibliográficos') }}
                            </a>
                            <a href="#article-coauthors" class="toc-link block px-2.5 py-1.5 rounded-lg text-slate-700 hover:text-emerald-800 hover:bg-emerald-50/70 transition-colors">
                                &bull; {{ __('Coautores & Ciência ID') }}
                            </a>
                            @if($output->projects && $output->projects->count() > 0)
                                <a href="#article-projects" class="toc-link block px-2.5 py-1.5 rounded-lg text-slate-700 hover:text-emerald-800 hover:bg-emerald-50/70 transition-colors">
                                    &bull; {{ __('Projetos Financiadores') }}
                                </a>
                            @endif
                            @if(!empty($relatedOutputs) && $relatedOutputs->count() > 0)
                                <a href="#article-related" class="toc-link block px-2.5 py-1.5 rounded-lg text-slate-700 hover:text-emerald-800 hover:bg-emerald-50/70 transition-colors">
                                    &bull; {{ __('Obras Relacionadas') }}
                                </a>
                            @endif
                        </nav>
                    </div>
                </aside>

                {{-- ========================================================= --}}
                {{-- CENTER COLUMN: Main Academic Article (Editorial View)     --}}
                {{-- ========================================================= --}}
                <main class="lg:col-span-6 xl:col-span-6 space-y-6">

                    {{-- Main Header Article Card --}}
                    <article class="bg-white border border-slate-200/90 rounded-2xl p-6 sm:p-8 shadow-xs space-y-5">
                        
                        {{-- Badges Row --}}
                        <div class="flex flex-wrap items-center gap-2 text-xs">
                            <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-md text-[11px] font-bold uppercase tracking-wider bg-slate-100 text-slate-800 border border-slate-200">
                                <i class="fas fa-file-lines text-[10px] text-slate-500"></i>
                                {{ $output->type->name ?? __('Artigo Científico') }}
                            </span>

                            @if($openAccess)
                                <span class="inline-flex items-center gap-1 px-2.5 py-1 rounded-md text-[11px] font-bold bg-amber-50 text-amber-900 border border-amber-200/80" title="{{ __('Acesso Aberto') }}">
                                    <i class="fas fa-lock-open text-[10px] text-amber-600"></i>
                                    <span>{{ __('Open Access') }}</span>
                                </span>
                            @endif

                            @if(!empty($output->quartile))
                                <span class="inline-flex items-center gap-1 px-2.5 py-1 rounded-md text-[11px] font-bold bg-emerald-50 text-emerald-800 border border-emerald-200/80">
                                    <i class="fas fa-award text-[10px] text-emerald-600"></i>
                                    <span>Quartil {{ $output->quartile }}</span>
                                </span>
                            @endif

                            @if($refreed)
                                <span class="inline-flex items-center gap-1 px-2.5 py-1 rounded-md text-[11px] font-semibold bg-blue-50 text-blue-800 border border-blue-200/80">
                                    <i class="fas fa-check-double text-[10px] text-blue-600"></i>
                                    <span>{{ __('Peer Reviewed') }}</span>
                                </span>
                            @endif
                        </div>

                        {{-- Article Title (Serif/Academic Style) --}}
                        <h1 class="text-2xl sm:text-3xl font-serif font-bold text-slate-900 leading-snug tracking-tight m-0">
                            {{ $output->title }}
                            @if($openAccess)
                                <span class="text-amber-600 text-xl align-middle inline-block ml-1" title="{{ __('Acesso Aberto') }}">&#128275;</span>
                            @endif
                        </h1>

                        {{-- Authors Row --}}
                        <div class="space-y-1.5 pt-1">
                            @if(!empty($citationAuthors) && count($citationAuthors) > 0)
                                <div class="text-sm font-medium text-slate-700 flex flex-wrap items-center gap-x-2 gap-y-1">
                                    @foreach($citationAuthors as $idx => $cAuthor)
                                        @php
                                            $matchedAuthor = $institutionAuthors->first(function($a) use ($cAuthor) {
                                                $name = $a->userInformation->name ?? '';
                                                return !empty($name) && (str_contains(strtolower($cAuthor), strtolower($name)) || str_contains(strtolower($name), strtolower($cAuthor)));
                                            });
                                        @endphp

                                        @if($matchedAuthor)
                                            <a href="{{ route('authors.show', $matchedAuthor->id) }}"
                                               class="text-emerald-800 hover:text-emerald-900 hover:underline font-semibold inline-flex items-center gap-1">
                                                <span>{{ $cAuthor }}</span>
                                                <i class="fas fa-envelope text-[10px] text-emerald-600" title="{{ $matchedAuthor->userInformation->email ?? '' }}"></i>
                                            </a>
                                        @else
                                            <span class="text-slate-800">{{ $cAuthor }}</span>
                                        @endif

                                        @if($idx < count($citationAuthors) - 1)
                                            <span class="text-slate-400">,</span>
                                        @endif
                                    @endforeach
                                </div>
                            @elseif($output->authors && $output->authors->count() > 0)
                                <div class="text-sm font-medium text-slate-700 flex flex-wrap items-center gap-x-2 gap-y-1">
                                    @foreach($output->authors as $idx => $authItem)
                                        <a href="{{ route('authors.show', $authItem->id) }}"
                                           class="text-emerald-800 hover:text-emerald-900 hover:underline font-semibold inline-flex items-center gap-1">
                                            <span>{{ $authItem->userInformation->name ?? __('Autor') }}</span>
                                            <i class="fas fa-envelope text-[10px] text-emerald-600" title="{{ $authItem->userInformation->email ?? '' }}"></i>
                                        </a>
                                        @if($idx < $output->authors->count() - 1)
                                            <span class="text-slate-400">,</span>
                                        @endif
                                    @endforeach
                                </div>
                            @endif
                        </div>

                        {{-- Academic Citation & Publication Venue Line (Italic style) --}}
                        @if(!empty($venueCitationString))
                            <div class="text-xs sm:text-sm text-slate-600 italic font-serif leading-relaxed pt-1">
                                {{ $venueCitationString }}
                            </div>
                        @endif

                        {{-- DOI Line --}}
                        @if(!empty($doiUrl))
                            <div class="text-xs flex flex-wrap items-center gap-2 pt-1 font-mono">
                                <a href="{{ $doiUrl }}" target="_blank" rel="noopener noreferrer"
                                   class="text-emerald-700 hover:text-emerald-900 hover:underline inline-flex items-center gap-1.5 break-all">
                                    <i class="fas fa-link text-[10px]"></i>
                                    <span>{{ $doiUrl }}</span>
                                </a>
                                <button type="button" onclick="copyToClipboard('{{ $doiUrl }}', this)"
                                        class="px-2 py-0.5 rounded border border-slate-200 bg-slate-50 hover:bg-slate-100 text-slate-600 text-[10px] transition-colors cursor-pointer"
                                        title="{{ __('Copiar DOI') }}">
                                    <i class="fas fa-copy"></i>
                                    <span>{{ __('Copiar') }}</span>
                                </button>
                            </div>
                        @endif

                        {{-- Published Date Line --}}
                        @if(!empty($formattedPublishedDate))
                            <div class="text-xs text-slate-600 flex items-center gap-2 font-medium pt-1">
                                <span class="font-bold text-slate-800">{{ __('Publicado:') }}</span>
                                <span>{{ $formattedPublishedDate }}</span>
                            </div>
                        @endif

                        {{-- ACTION BAR (PDF, Cite, Share, URL - matching Oxford Academic reference) --}}
                        <div class="pt-3 border-t border-slate-200/80 flex flex-wrap items-center gap-2.5">
                            @if(!empty($articleUrl))
                                <a href="{{ $articleUrl }}" target="_blank" rel="noopener noreferrer"
                                   class="inline-flex items-center gap-2 px-4 py-2 rounded-xl bg-emerald-700 hover:bg-emerald-800 text-white text-xs sm:text-sm font-semibold shadow-xs transition-colors">
                                    <i class="fas fa-file-pdf"></i>
                                    <span>{{ __('Aceder ao Artigo (PDF / URL)') }}</span>
                                </a>
                            @elseif(!empty($doiUrl))
                                <a href="{{ $doiUrl }}" target="_blank" rel="noopener noreferrer"
                                   class="inline-flex items-center gap-2 px-4 py-2 rounded-xl bg-emerald-700 hover:bg-emerald-800 text-white text-xs sm:text-sm font-semibold shadow-xs transition-colors">
                                    <i class="fas fa-arrow-up-right-from-square"></i>
                                    <span>{{ __('Ver no Editor (DOI)') }}</span>
                                </a>
                            @endif

                            {{-- Cite Button --}}
                            <button type="button" onclick="openCitationModal()"
                                    class="inline-flex items-center gap-1.5 px-3.5 py-2 rounded-xl border border-slate-200 bg-white hover:bg-slate-50 text-slate-700 text-xs sm:text-sm font-semibold shadow-2xs transition-colors cursor-pointer">
                                <i class="fas fa-quote-left text-slate-400"></i>
                                <span>{{ __('Citar') }}</span>
                            </button>

                            {{-- Share Button --}}
                            <button type="button" onclick="shareArticle('{{ addslashes($output->title) }}', window.location.href, this)"
                                    class="inline-flex items-center gap-1.5 px-3.5 py-2 rounded-xl border border-slate-200 bg-white hover:bg-slate-50 text-slate-700 text-xs sm:text-sm font-semibold shadow-2xs transition-colors cursor-pointer">
                                <i class="fas fa-share-nodes text-slate-400"></i>
                                <span>{{ __('Partilhar') }}</span>
                            </button>
                        </div>
                    </article>

                    {{-- SECTION: ABSTRACT (Resumo) --}}
                    <section id="article-abstract" class="bg-white border border-slate-200/90 rounded-2xl p-6 sm:p-8 shadow-xs space-y-4 scroll-mt-24">
                        <div class="flex items-center justify-between border-b border-slate-100 pb-3">
                            <h2 class="text-lg sm:text-xl font-bold font-serif text-slate-900 flex items-center gap-2 m-0">
                                <i class="fas fa-align-left text-emerald-600 text-base"></i>
                                <span>{{ __('Abstract') }}</span>
                            </h2>
                            @if(!empty($doiUrl) && empty($output->abstract))
                                <button type="button" onclick="fetchCrossrefAbstract('{{ $cleanDoi }}', this)"
                                        class="inline-flex items-center gap-1.5 px-3 py-1 rounded-lg text-xs font-semibold bg-emerald-50 text-emerald-800 border border-emerald-200/70 hover:bg-emerald-100 transition-colors cursor-pointer">
                                    <i class="fas fa-rotate"></i>
                                    <span>{{ __('Consultar Abstract via DOI') }}</span>
                                </button>
                            @endif
                        </div>

                        <div id="abstract-container" class="prose prose-slate max-w-none text-slate-700 leading-relaxed text-sm sm:text-[15px]">
                            @if(!empty($output->abstract))
                                <p class="whitespace-pre-line">{{ $output->abstract }}</p>
                            @else
                                <div class="p-4 rounded-xl bg-slate-50 border border-slate-200/70 space-y-2">
                                    <p class="text-slate-600 italic text-sm m-0">
                                        {{ __('Esta obra encontra-se catalogada no Portal Científico.') }}
                                        @if(!empty($venue))
                                            {{ __('Publicada oficialmente em') }} <strong>{{ $venue }}</strong>{{ !empty($year) ? " ($year)" : '' }}.
                                        @endif
                                        @if(!empty($output->citation_string))
                                            {{ __('Referenciada como:') }} <em>"{{ $output->citation_string }}"</em>.
                                        @endif
                                    </p>
                                    @if(!empty($doiUrl))
                                        <p class="text-xs text-slate-500 m-0 pt-1">
                                            {{ __('O resumo completo e texto integral podem ser consultados diretamente no DOI oficial:') }}
                                            <a href="{{ $doiUrl }}" target="_blank" rel="noopener noreferrer" class="text-emerald-700 hover:underline font-semibold font-mono">
                                                {{ $doiUrl }}
                                            </a>
                                        </p>
                                    @endif
                                </div>
                            @endif
                        </div>
                    </section>

                    {{-- SECTION: KEYWORDS (Palavras-chave) --}}
                    @if($output->keywords && $output->keywords->count() > 0)
                        <section id="article-keywords" class="bg-white border border-slate-200/90 rounded-2xl p-6 sm:p-7 shadow-xs space-y-3 scroll-mt-24">
                            <h3 class="text-xs font-bold uppercase tracking-wider text-slate-500 flex items-center gap-2 m-0">
                                <i class="fas fa-tags text-emerald-600"></i>
                                <span>{{ __('Palavras-chave / Keywords') }}</span>
                            </h3>
                            <div class="flex flex-wrap gap-2">
                                @foreach($output->keywords as $keyword)
                                    <span class="inline-flex items-center px-3 py-1 rounded-lg text-xs font-medium bg-slate-100 text-slate-800 border border-slate-200">
                                        {{ $keyword->keyword }}
                                    </span>
                                @endforeach
                            </div>
                        </section>
                    @endif

                    {{-- SECTION: BIBLIOGRAPHIC DETAILS --}}
                    <section id="article-details" class="bg-white border border-slate-200/90 rounded-2xl p-6 sm:p-8 shadow-xs space-y-5 scroll-mt-24">
                        <h2 class="text-base sm:text-lg font-bold text-slate-900 flex items-center gap-2 border-b border-slate-100 pb-3 m-0">
                            <i class="fas fa-table-list text-emerald-600"></i>
                            <span>{{ __('Detalhes Bibliográficos e Editoriais') }}</span>
                        </h2>

                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 text-xs sm:text-sm">
                            @if(!empty($venue))
                                <div class="p-3.5 rounded-xl bg-slate-50 border border-slate-200/70">
                                    <span class="text-slate-400 text-xs font-semibold block uppercase tracking-wider mb-1">{{ __('Veículo / Revista / Livro') }}</span>
                                    <span class="font-bold text-slate-900">{{ $venue }}</span>
                                </div>
                            @endif

                            @if(!empty($publisher))
                                <div class="p-3.5 rounded-xl bg-slate-50 border border-slate-200/70">
                                    <span class="text-slate-400 text-xs font-semibold block uppercase tracking-wider mb-1">{{ __('Editora / Publisher') }}</span>
                                    <span class="font-bold text-slate-900">{{ $publisher }}</span>
                                </div>
                            @endif

                            @if(!empty($volume) || !empty($issue))
                                <div class="p-3.5 rounded-xl bg-slate-50 border border-slate-200/70">
                                    <span class="text-slate-400 text-xs font-semibold block uppercase tracking-wider mb-1">{{ __('Volume & Fascículo') }}</span>
                                    <span class="font-bold text-slate-900">
                                        {{ !empty($volume) ? __('Vol.') . ' ' . $volume : '' }}
                                        {{ !empty($issue) ? ' (' . __('N.º') . ' ' . $issue . ')' : '' }}
                                    </span>
                                </div>
                            @endif

                            @if(($startPage && $endPage) || $totalPages)
                                <div class="p-3.5 rounded-xl bg-slate-50 border border-slate-200/70">
                                    <span class="text-slate-400 text-xs font-semibold block uppercase tracking-wider mb-1">{{ __('Paginação') }}</span>
                                    <span class="font-bold text-slate-900">
                                        {{ $startPage && $endPage ? "Páginas $startPage - $endPage" : "$totalPages páginas" }}
                                    </span>
                                </div>
                            @endif

                            @if(!empty($location))
                                <div class="p-3.5 rounded-xl bg-slate-50 border border-slate-200/70">
                                    <span class="text-slate-400 text-xs font-semibold block uppercase tracking-wider mb-1">{{ __('Local de Publicação / Conferência') }}</span>
                                    <span class="font-bold text-slate-900">{{ $location }}</span>
                                </div>
                            @endif

                            @if(!empty($role))
                                <div class="p-3.5 rounded-xl bg-slate-50 border border-slate-200/70">
                                    <span class="text-slate-400 text-xs font-semibold block uppercase tracking-wider mb-1">{{ __('Papel / Role') }}</span>
                                    <span class="font-bold text-slate-900">{{ $role }}</span>
                                </div>
                            @endif

                            @if(!empty($degreeType))
                                <div class="p-3.5 rounded-xl bg-slate-50 border border-slate-200/70">
                                    <span class="text-slate-400 text-xs font-semibold block uppercase tracking-wider mb-1">{{ __('Grau Académico') }}</span>
                                    <span class="font-bold text-slate-900">{{ $degreeType }}</span>
                                </div>
                            @endif

                            @if(!empty($supervisors))
                                <div class="p-3.5 rounded-xl bg-slate-50 border border-slate-200/70">
                                    <span class="text-slate-400 text-xs font-semibold block uppercase tracking-wider mb-1">{{ __('Orientadores') }}</span>
                                    <span class="font-bold text-slate-900">{{ $supervisors }}</span>
                                </div>
                            @endif
                        </div>

                        {{-- Identifiers row (ISBN, ISSN, Handle, PMID) --}}
                        @if(!empty($output->isbn) || !empty($output->issn) || !empty($output->handle) || !empty($output->pmid) || !empty($cleanDoi))
                            <div class="pt-4 border-t border-slate-100">
                                <h3 class="text-xs font-bold uppercase tracking-wider text-slate-500 mb-3 flex items-center gap-1.5">
                                    <i class="fas fa-fingerprint text-emerald-600"></i>
                                    <span>{{ __('Identificadores Oficiais') }}</span>
                                </h3>
                                <div class="grid grid-cols-1 sm:grid-cols-2 gap-2 text-xs">
                                    @if(!empty($cleanDoi))
                                        <div class="flex items-center justify-between p-2.5 rounded-lg bg-slate-50 border border-slate-200 font-mono">
                                            <span class="text-slate-500 font-sans font-medium">DOI:</span>
                                            <span class="text-slate-900 font-semibold select-all">{{ $cleanDoi }}</span>
                                        </div>
                                    @endif
                                    @if(!empty($output->isbn))
                                        <div class="flex items-center justify-between p-2.5 rounded-lg bg-slate-50 border border-slate-200 font-mono">
                                            <span class="text-slate-500 font-sans font-medium">ISBN:</span>
                                            <span class="text-slate-900 font-semibold select-all">{{ $output->isbn }}</span>
                                        </div>
                                    @endif
                                    @if(!empty($output->issn))
                                        <div class="flex items-center justify-between p-2.5 rounded-lg bg-slate-50 border border-slate-200 font-mono">
                                            <span class="text-slate-500 font-sans font-medium">ISSN:</span>
                                            <span class="text-slate-900 font-semibold select-all">{{ $output->issn }}</span>
                                        </div>
                                    @endif
                                    @if(!empty($output->handle))
                                        <div class="flex items-center justify-between p-2.5 rounded-lg bg-slate-50 border border-slate-200 font-mono">
                                            <span class="text-slate-500 font-sans font-medium">Handle / URI:</span>
                                            <a href="{{ filter_var($output->handle, FILTER_VALIDATE_URL) ? $output->handle : 'https://hdl.handle.net/' . $output->handle }}"
                                               target="_blank" rel="noopener noreferrer" class="text-emerald-700 hover:underline font-semibold truncate max-w-[180px]">
                                                {{ $output->handle }}
                                            </a>
                                        </div>
                                    @endif
                                    @if(!empty($output->pmid))
                                        <div class="flex items-center justify-between p-2.5 rounded-lg bg-slate-50 border border-slate-200 font-mono">
                                            <span class="text-slate-500 font-sans font-medium">PubMed PMID:</span>
                                            <a href="https://pubmed.ncbi.nlm.nih.gov/{{ $output->pmid }}/"
                                               target="_blank" rel="noopener noreferrer" class="text-emerald-700 hover:underline font-semibold">
                                                {{ $output->pmid }}
                                            </a>
                                        </div>
                                    @endif
                                </div>
                            </div>
                        @endif
                    </section>

                    {{-- SECTION: CO-AUTHORS WITH CIÊNCIA ID (Phase 3 Native Integration) --}}
                    <section id="article-coauthors" class="bg-white border border-slate-200/90 rounded-2xl p-6 sm:p-8 shadow-xs space-y-4 scroll-mt-24"
                             x-data="{
                                 coauthors: [],
                                 loading: false,
                                 loaded: false,
                                 error: null,
                                 fetchCoauthors() {
                                     this.loading = true;
                                     this.error = null;
                                     fetch('{{ route('outputs.coauthors', $output->id) }}')
                                         .then(res => {
                                             if (!res.ok) throw new Error('{{ __('Não foi possível contactar a API.') }}');
                                             return res.json();
                                         })
                                         .then(data => {
                                             this.coauthors = data.coauthors || [];
                                             this.loaded = true;
                                             this.loading = false;
                                         })
                                         .catch(err => {
                                             this.error = err.message;
                                             this.loading = false;
                                         });
                                 }
                             }">
                        <div class="flex flex-wrap items-center justify-between gap-2 border-b border-slate-100 pb-3">
                            <h2 class="text-base sm:text-lg font-bold text-slate-900 flex items-center gap-2 m-0">
                                <i class="fas fa-id-badge text-emerald-600"></i>
                                <span>{{ __('Descoberta de Coautores & Ciência ID') }}</span>
                            </h2>
                            <button type="button" @click="fetchCoauthors()" x-show="!loaded" :disabled="loading"
                                    class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-xl border border-emerald-200 bg-emerald-50 text-emerald-800 text-xs font-semibold hover:bg-emerald-100 transition-colors cursor-pointer shadow-2xs">
                                <i class="fas fa-magnifying-glass" x-show="!loading"></i>
                                <i class="fas fa-spinner fa-spin" x-show="loading" style="display: none;"></i>
                                <span x-text="loading ? '{{ __('A consultar API...') }}' : '{{ __('Consultar Ciência ID') }}'"></span>
                            </button>
                        </div>

                        {{-- Default citation authors view --}}
                        <div class="space-y-3" x-show="!loaded && !loading">
                            <p class="text-xs text-slate-500 m-0">
                                {{ __('Autores discriminados na citação da obra:') }}
                            </p>
                            <div class="p-3.5 rounded-xl bg-slate-50 border border-slate-200/80 text-xs font-mono text-slate-700">
                                {{ $output->citation_string ?: $output->title }}
                            </div>
                        </div>

                        {{-- Loaded coauthors list via Ciência Vitae API --}}
                        <div x-show="loaded" style="display: none;" class="space-y-3">
                            <template x-if="coauthors.length === 0">
                                <p class="text-xs text-slate-500 italic p-3 rounded-lg bg-slate-50">
                                    {{ __('Nenhum coautor adicional com Ciência ID associado encontrado para esta publicação.') }}
                                </p>
                            </template>

                            <div class="grid grid-cols-1 sm:grid-cols-2 gap-3" x-show="coauthors.length > 0">
                                <template x-for="co in coauthors" :key="co.ciencia_id">
                                    <div class="p-3 rounded-xl border border-slate-200/80 bg-slate-50/60 flex items-start justify-between gap-3">
                                        <div class="space-y-0.5">
                                            <h4 class="text-xs font-bold text-slate-900 m-0" x-text="co.name"></h4>
                                            <p class="text-[11px] text-slate-500 m-0 font-mono" x-text="co.ciencia_id"></p>
                                        </div>
                                        <a :href="'https://cienciavitae.pt/portal/' + co.ciencia_id" target="_blank" rel="noopener noreferrer"
                                           class="px-2 py-1 rounded bg-white border border-slate-200 text-emerald-700 text-[11px] font-semibold hover:bg-emerald-50 transition-colors shrink-0">
                                            {{ __('Ver CV') }} &rarr;
                                        </a>
                                    </div>
                                </template>
                            </div>
                        </div>
                    </section>

                    {{-- SECTION: FUNDING PROJECTS (Phase 5 Native Integration) --}}
                    @if($output->projects && $output->projects->count() > 0)
                        <section id="article-projects" class="bg-white border border-slate-200/90 rounded-2xl p-6 sm:p-8 shadow-xs space-y-4 scroll-mt-24">
                            <h2 class="text-base sm:text-lg font-bold text-slate-900 flex items-center gap-2 border-b border-slate-100 pb-3 m-0">
                                <i class="fas fa-hand-holding-dollar text-emerald-600"></i>
                                <span>{{ __('Projetos de Financiamento Associados') }}</span>
                            </h2>
                            <div class="space-y-3">
                                @foreach($output->projects as $project)
                                    <div class="p-4 rounded-xl bg-slate-50 border border-slate-200/80 space-y-1.5">
                                        <div class="flex items-center justify-between gap-2">
                                            <span class="text-[11px] font-semibold text-emerald-800 uppercase tracking-wider">
                                                {{ $project->funding_agency ?? __('Financiamento') }}
                                            </span>
                                            @if(!empty($project->reference))
                                                <span class="font-mono text-[11px] text-slate-600 font-semibold px-2 py-0.5 bg-white rounded border border-slate-200">
                                                    Ref: {{ $project->reference }}
                                                </span>
                                            @endif
                                        </div>
                                        <h3 class="text-xs sm:text-sm font-bold text-slate-900 m-0">
                                            {{ $project->title }}
                                        </h3>
                                    </div>
                                @endforeach
                            </div>
                        </section>
                    @endif

                    {{-- SECTION: RELATED PUBLICATIONS --}}
                    @if(!empty($relatedOutputs) && $relatedOutputs->count() > 0)
                        <section id="article-related" class="bg-white border border-slate-200/90 rounded-2xl p-6 sm:p-8 shadow-xs space-y-4 scroll-mt-24">
                            <h2 class="text-base sm:text-lg font-bold text-slate-900 flex items-center gap-2 border-b border-slate-100 pb-3 m-0">
                                <i class="fas fa-layer-group text-emerald-600"></i>
                                <span>{{ __('Outras Publicações Relacionadas') }}</span>
                            </h2>
                            <div class="grid grid-cols-1 sm:grid-cols-3 gap-3.5">
                                @foreach($relatedOutputs as $rel)
                                    <a href="{{ route('outputs.show', $rel->id) }}"
                                       class="group block p-3.5 rounded-xl border border-slate-200/80 bg-slate-50/50 hover:bg-white hover:border-emerald-300 hover:shadow-xs transition-all">
                                        <span class="text-[10px] font-semibold text-emerald-800 block mb-1">
                                            {{ $rel->type->name ?? __('Publicação') }} &bull; {{ $rel->year ?? '-' }}
                                        </span>
                                        <h4 class="text-xs font-bold text-slate-900 group-hover:text-emerald-800 line-clamp-2 transition-colors m-0">
                                            {{ $rel->title }}
                                        </h4>
                                    </a>
                                @endforeach
                            </div>
                        </section>
                    @endif

                    {{-- Discreet Scientific Provenance Note (Replaces old bulky box) --}}
                    <div class="text-center py-4 border-t border-slate-200/60 text-xs text-slate-400 space-y-1">
                        <div class="flex items-center justify-center gap-1.5 font-medium text-slate-500">
                            <i class="fas fa-shield-halved text-emerald-600 text-[11px]"></i>
                            <span>{{ __('Portal Científico ISLA') }}</span>
                            <span class="text-slate-300">&bull;</span>
                            <span>{{ __('Metadados sincronizados oficialmente via Ciência Vitae / FCT') }}</span>
                        </div>
                    </div>

                </main>

                {{-- ========================================================= --}}
                {{-- RIGHT COLUMN: Metrics, Authors & Research Impact          --}}
                {{-- ========================================================= --}}
                <aside class="lg:col-span-3 xl:col-span-3 space-y-6 lg:sticky lg:top-20">

                    {{-- METRICS CARD (Matching Oxford Academic / The ISME Journal screenshot) --}}
                    <div class="bg-white border border-slate-200/90 rounded-2xl p-5 shadow-xs space-y-4">
                        <h3 class="text-xs font-bold uppercase tracking-wider text-slate-500 border-b border-slate-100 pb-2 flex items-center justify-between">
                            <span>{{ __('Métricas do Artigo') }}</span>
                            <i class="fas fa-chart-simple text-emerald-600"></i>
                        </h3>

                        {{-- 3-badge Metrics Grid (Citations, Views, Quartile/Altmetric) --}}
                        <div class="grid grid-cols-3 gap-2 text-center py-1">
                            {{-- Citations --}}
                            <div class="space-y-1.5 flex flex-col items-center">
                                <span class="text-[10px] font-bold uppercase tracking-wider text-slate-500 block">
                                    {{ __('Citações') }}
                                </span>
                                <div class="w-12 h-12 rounded-xl bg-gradient-to-tr from-amber-400 via-emerald-500 to-teal-500 p-0.5 shadow-xs">
                                    <div class="w-full h-full bg-white rounded-[10px] flex items-center justify-center font-bold text-slate-900 text-sm">
                                        {{ $citationsCount }}
                                    </div>
                                </div>
                            </div>

                            {{-- Views (Reads) --}}
                            <div class="space-y-1.5 flex flex-col items-center">
                                <span class="text-[10px] font-bold uppercase tracking-wider text-slate-500 block">
                                    {{ __('Vistas') }}
                                </span>
                                <div class="w-12 h-12 rounded-full bg-slate-900 text-white flex items-center justify-center font-bold text-xs shadow-xs">
                                    {{ $viewsCount }}
                                </div>
                            </div>

                            {{-- Quartile / Impact --}}
                            <div class="space-y-1.5 flex flex-col items-center">
                                <span class="text-[10px] font-bold uppercase tracking-wider text-slate-500 block">
                                    {{ __('Impacto') }}
                                </span>
                                <div class="w-12 h-12 rounded-full border-2 border-dashed border-slate-300 bg-slate-50 flex items-center justify-center font-bold text-slate-700 text-xs shadow-2xs">
                                    {{ !empty($output->quartile) ? $output->quartile : '?' }}
                                </div>
                            </div>
                        </div>

                        {{-- Links to External Metrics --}}
                        <div class="pt-2 border-t border-slate-100 text-center">
                            <a href="https://scholar.google.com/scholar?q={{ urlencode($output->title) }}" target="_blank" rel="noopener noreferrer"
                               class="text-[11px] font-semibold text-emerald-700 hover:underline inline-flex items-center gap-1">
                                <i class="fas fa-circle-info text-[10px]"></i>
                                <span>{{ __('Mais informações de impacto') }} &rarr;</span>
                            </a>
                        </div>
                    </div>

                    {{-- INSTITUTION AUTHORS CARD (Docentes & Investigadores do ISLA) --}}
                    <div class="bg-white border border-slate-200/90 rounded-2xl p-5 shadow-xs space-y-4">
                        <h3 class="text-xs font-bold uppercase tracking-wider text-slate-500 border-b border-slate-100 pb-2 flex items-center gap-2">
                            <i class="fas fa-users text-emerald-600"></i>
                            <span>{{ __('Autores da Instituição') }}</span>
                        </h3>

                        @if($displayAuthors && $displayAuthors->count() > 0)
                            <div class="space-y-3">
                                @foreach($displayAuthors as $author)
                                    @php
                                        $authorUser = $author->userInformation;
                                    @endphp
                                    <div class="p-3 rounded-xl bg-slate-50 border border-slate-200/70 space-y-2 hover:bg-slate-100/60 transition-colors">
                                        <div class="flex items-center gap-3">
                                            <div class="w-10 h-10 shrink-0 rounded-full overflow-hidden border border-slate-200 bg-white flex items-center justify-center">
                                                <x-user-image showLogedUserImage="0"
                                                    cienciaVitae="{{ $authorUser?->ciencia_vitae }}"
                                                    cienciaVitaeImageIsPublic="{{ $author->profile_is_public && $author->profile_image_is_public }}"
                                                    class="rounded-full w-full h-full object-cover block" height="40" width="40" />
                                            </div>
                                            <div class="grow min-w-0">
                                                <h4 class="text-xs font-bold text-slate-900 m-0 truncate">
                                                    {{ $authorUser->name ?? __('Autor') }}
                                                </h4>
                                                <p class="text-[11px] text-slate-500 m-0 truncate">
                                                    {{ $authorUser->entidade ?? 'ISLA - IPGT' }}
                                                </p>
                                            </div>
                                        </div>

                                        @if(!empty($authorUser?->ciencia_vitae) || !empty($author->orcid))
                                            <div class="pt-1.5 border-t border-slate-200/60 flex items-center justify-between text-[10px] text-slate-500">
                                                @if(!empty($authorUser?->ciencia_vitae))
                                                    <span class="font-mono truncate max-w-[110px]" title="{{ $authorUser->ciencia_vitae }}">{{ $authorUser->ciencia_vitae }}</span>
                                                @endif
                                                @if(!empty($author->orcid))
                                                    <a href="https://orcid.org/{{ $author->orcid }}" target="_blank" rel="noopener noreferrer" class="text-emerald-700 hover:underline font-mono">ORCID</a>
                                                @endif
                                            </div>
                                        @endif

                                        <div class="pt-1">
                                            <a href="{{ route('authors.show', $author->id) }}"
                                               class="text-[11px] font-semibold text-emerald-700 hover:text-emerald-900 inline-flex items-center gap-1 transition-colors">
                                                <span>{{ __('Ver Perfil') }}</span>
                                                <i class="fas fa-arrow-right text-[9px]"></i>
                                            </a>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        @else
                            <p class="text-xs text-slate-500 italic m-0">
                                {{ __('Autores registados na citação bibliográfica.') }}
                            </p>
                        @endif
                    </div>

                    {{-- IMPACT DISCOVERY (Google Scholar & Scopus direct queries) --}}
                    <div class="bg-white border border-slate-200/90 rounded-2xl p-5 shadow-xs space-y-3">
                        <h3 class="text-xs font-bold uppercase tracking-wider text-slate-500 border-b border-slate-100 pb-2">
                            {{ __('Pesquisa de Artigos Relacionados') }}
                        </h3>
                        <div class="space-y-2 text-xs">
                            <a href="https://scholar.google.com/scholar?q={{ urlencode($output->title) }}" target="_blank" rel="noopener noreferrer"
                               class="flex items-center justify-between p-2.5 rounded-xl border border-slate-200/80 bg-slate-50 hover:bg-emerald-50 hover:text-emerald-800 transition-colors font-medium">
                                <span class="flex items-center gap-2">
                                    <i class="fas fa-graduation-cap text-slate-400"></i>
                                    <span>Google Scholar</span>
                                </span>
                                <i class="fas fa-arrow-up-right-from-square text-[10px]"></i>
                            </a>

                            @if(!empty($cleanDoi))
                                <a href="https://www.scopus.com/search/form.uri?display=basic#basic" target="_blank" rel="noopener noreferrer"
                                   class="flex items-center justify-between p-2.5 rounded-xl border border-slate-200/80 bg-slate-50 hover:bg-emerald-50 hover:text-emerald-800 transition-colors font-medium">
                                    <span class="flex items-center gap-2">
                                        <i class="fas fa-book-bookmark text-slate-400"></i>
                                        <span>Scopus Elsevier</span>
                                    </span>
                                    <i class="fas fa-arrow-up-right-from-square text-[10px]"></i>
                                </a>
                            @endif
                        </div>
                    </div>

                </aside>

            </div>

        </div>
    </div>

    {{-- CITATION MODAL (BibTeX / APA / Harvard) --}}
    <div id="citationModal" class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-slate-900/60 backdrop-blur-xs" style="display: none;">
        <div class="bg-white rounded-2xl max-w-lg w-full p-6 shadow-2xl border border-slate-200 space-y-4">
            <div class="flex items-center justify-between border-b border-slate-100 pb-3">
                <h3 class="text-base font-bold text-slate-900 flex items-center gap-2 m-0">
                    <i class="fas fa-quote-left text-emerald-600"></i>
                    <span>{{ __('Exportar Citação Bibliográfica') }}</span>
                </h3>
                <button type="button" onclick="closeCitationModal()" class="text-slate-400 hover:text-slate-600 p-1 cursor-pointer">
                    <i class="fas fa-xmark text-base"></i>
                </button>
            </div>

            <div class="flex gap-2 border-b border-slate-200 text-xs font-semibold">
                <button type="button" onclick="switchCitationFormat('apa')" id="citeTabApa" class="px-3 py-2 text-emerald-800 border-b-2 border-emerald-700 cursor-pointer">APA</button>
                <button type="button" onclick="switchCitationFormat('bibtex')" id="citeTabBibtex" class="px-3 py-2 text-slate-500 hover:text-slate-800 cursor-pointer">BibTeX</button>
                <button type="button" onclick="switchCitationFormat('harvard')" id="citeTabHarvard" class="px-3 py-2 text-slate-500 hover:text-slate-800 cursor-pointer">Harvard</button>
            </div>

            <div class="relative">
                <textarea id="citationTextarea" readonly rows="5" class="w-full p-3 rounded-xl bg-slate-50 border border-slate-200 font-mono text-xs text-slate-800 leading-relaxed outline-none select-all"></textarea>
            </div>

            <div class="flex justify-end gap-2 pt-2">
                <button type="button" onclick="closeCitationModal()" class="px-4 py-2 rounded-xl border border-slate-200 text-slate-700 text-xs font-semibold hover:bg-slate-50 cursor-pointer">
                    {{ __('Fechar') }}
                </button>
                <button type="button" onclick="copyCitationText(this)" class="px-4 py-2 rounded-xl bg-emerald-700 hover:bg-emerald-800 text-white text-xs font-semibold shadow-xs cursor-pointer flex items-center gap-1.5">
                    <i class="fas fa-copy"></i>
                    <span id="copyCitationBtnText">{{ __('Copiar Citação') }}</span>
                </button>
            </div>
        </div>
    </div>

    {{-- SCRIPTS --}}
    <script>
        function copyToClipboard(text, btn) {
            navigator.clipboard.writeText(text).then(() => {
                const orig = btn.innerHTML;
                btn.innerHTML = '<i class="fas fa-check text-emerald-600"></i> {{ __("Copiado!") }}';
                setTimeout(() => { btn.innerHTML = orig; }, 2000);
            });
        }

        function shareArticle(title, url, btn) {
            if (navigator.share) {
                navigator.share({ title: title, url: url }).catch(() => {});
            } else {
                copyToClipboard(url, btn);
            }
        }

        // Citations Generator
        const citationsData = {
            apa: `{{ addslashes($output->citation_string ?: $output->title . ' (' . ($output->year ?? date('Y')) . '). ' . ($venue ? $venue . '. ' : '') . ($doiUrl ? $doiUrl : '')) }}`,
            bibtex: `@article{output_{{ $output->id }},\n  title={ {!! addslashes($output->title) !!} },\n  author={ {!! addslashes($output->citation_string ?: 'ISLA Researcher') !!} },\n  journal={ {!! addslashes($venue ?: 'Portal Científico') !!} },\n  year={ {{ $output->year ?? date('Y') }} },\n  doi={ {!! addslashes($output->doi ?? '') !!} }\n}`,
            harvard: `{{ addslashes(($output->citation_string ?: 'Author') . ' (' . ($output->year ?? date('Y')) . ') \'' . $output->title . '\', ' . ($venue ?: 'Scientific Portal') . ($doiUrl ? ', available at: ' . $doiUrl : '')) }}`
        };

        let currentCitationFormat = 'apa';

        function openCitationModal() {
            const modal = document.getElementById('citationModal');
            if (!modal) return;
            modal.style.display = 'flex';
            switchCitationFormat('apa');
        }

        function closeCitationModal() {
            const modal = document.getElementById('citationModal');
            if (modal) modal.style.display = 'none';
        }

        function switchCitationFormat(fmt) {
            currentCitationFormat = fmt;
            const textarea = document.getElementById('citationTextarea');
            if (textarea) textarea.value = citationsData[fmt] || '';

            ['apa', 'bibtex', 'harvard'].forEach(f => {
                const tab = document.getElementById('citeTab' + f.charAt(0).toUpperCase() + f.slice(1));
                if (tab) {
                    if (f === fmt) {
                        tab.className = 'px-3 py-2 text-emerald-800 border-b-2 border-emerald-700 cursor-pointer font-bold';
                    } else {
                        tab.className = 'px-3 py-2 text-slate-500 hover:text-slate-800 cursor-pointer';
                    }
                }
            });
        }

        function copyCitationText(btn) {
            const textarea = document.getElementById('citationTextarea');
            if (!textarea) return;
            navigator.clipboard.writeText(textarea.value).then(() => {
                const textSpan = document.getElementById('copyCitationBtnText');
                if (textSpan) {
                    const orig = textSpan.textContent;
                    textSpan.textContent = '{{ __("Copiado com sucesso!") }}';
                    setTimeout(() => { textSpan.textContent = orig; }, 2000);
                }
            });
        }

        // Live Crossref Abstract query if DOI is present
        function fetchCrossrefAbstract(doi, btn) {
            if (!doi) return;
            btn.disabled = true;
            btn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> {{ __("A carregar...") }}';
            
            const cleanDoi = doi.replace(/^https?:\/\/doi\.org\//, '');
            fetch('https://api.crossref.org/works/' + encodeURIComponent(cleanDoi))
                .then(res => res.json())
                .then(data => {
                    const abs = data?.message?.abstract;
                    if (abs) {
                        // Strip XML/JATS tags if present (e.g. <jats:p>)
                        const cleanText = abs.replace(/<[^>]*>?/gm, ' ').replace(/\s+/g, ' ').trim();
                        document.getElementById('abstract-container').innerHTML = '<p class="whitespace-pre-line">' + cleanText + '</p>';
                        btn.style.display = 'none';
                    } else {
                        btn.innerHTML = '<i class="fas fa-circle-exclamation"></i> {{ __("Abstract indisponível no Crossref") }}';
                    }
                })
                .catch(() => {
                    btn.innerHTML = '<i class="fas fa-circle-exclamation"></i> {{ __("Erro na consulta") }}';
                });
        }
    </script>
</x-app-layout>
