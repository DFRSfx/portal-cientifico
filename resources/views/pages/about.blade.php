<x-app-layout>
    <x-slot:title>
        {{ __('Sobre Nós') }}
    </x-slot>

    <div class="min-h-[calc(100vh-120px)] py-10 sm:py-14 px-4 sm:px-6 lg:px-8 text-slate-900 max-w-6xl mx-auto space-y-12">
        <!-- Header -->
        <div class="text-center max-w-3xl mx-auto">
            <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-xs font-semibold bg-emerald-100 text-emerald-800 border border-emerald-200 mb-3">
                <svg class="w-3.5 h-3.5 text-emerald-700" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>
                Código do Projeto: POCH-02-53I2-FSE-000019
            </span>
            <h1 class="text-3xl sm:text-4xl lg:text-5xl font-extrabold text-emerald-800 tracking-tight mb-4">
                {{ __('Portal Científico') }}
            </h1>
            <p class="text-sm sm:text-base lg:text-lg text-slate-600 leading-relaxed max-w-2xl mx-auto">
                {{ __('Projeto financiado pelo Programa Operacional Capital Humano (POCH) no âmbito do Fundo Social Europeu (FSE), desenvolvido no ISLA - Instituto Politécnico de Gestão e Tecnologia.') }}
            </p>
        </div>

        <!-- Showcase: image on left, paragraphs on right -->
        <div class="w-full flex flex-col lg:flex-row items-center justify-center gap-10 lg:gap-14 bg-white border border-slate-200/80 rounded-2xl p-6 sm:p-10 shadow-sm">
            <div class="w-full lg:w-5/12 max-w-md">
                <div class="rounded-2xl border border-slate-200/80 shadow-md overflow-hidden bg-slate-50">
                    <img src="{{ asset('logo/image-portal.webp') }}" alt="Logo POCH" class="w-full h-auto object-cover block">
                </div>
            </div>

            <div class="w-full lg:w-7/12 text-left">
                <div class="space-y-5 mb-8">
                    <div class="flex items-start gap-4">
                        <div class="w-10 h-10 shrink-0 flex items-center justify-center bg-emerald-50 border border-emerald-200 rounded-xl text-emerald-700 shadow-xs mt-0.5">
                            <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                <path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"></path>
                                <circle cx="9" cy="7" r="4"></circle>
                                <path d="M23 21v-2a4 4 0 0 0-3-3.87"></path>
                                <path d="M16 3.13a4 4 0 0 1 0 7.75"></path>
                            </svg>
                        </div>
                        <p class="text-sm sm:text-base text-slate-600 leading-relaxed m-0">
                            {{ __('O Portal Científico foi concebido para centralizar, validar e divulgar a produção científica de docentes e investigadores do ISLA Gaia. Através de integrações automáticas via API ao CiênciaVitae, a plataforma garante a interoperabilidade dos perfis académicos e a curadoria contínua dos dados curriculares.') }}
                        </p>
                    </div>

                    <div class="flex items-start gap-4">
                        <div class="w-10 h-10 shrink-0 flex items-center justify-center bg-emerald-50 border border-emerald-200 rounded-xl text-emerald-700 shadow-xs mt-0.5">
                            <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                <circle cx="12" cy="12" r="10"></circle>
                                <line x1="2" y1="12" x2="22" y2="12"></line>
                                <path d="M12 2a15.3 15.3 0 0 1 4 10 15.3 15.3 0 0 1-4 10 15.3 15.3 0 0 1-4-10 15.3 15.3 0 0 1 4-10z"></path>
                            </svg>
                        </div>
                        <p class="text-sm sm:text-base text-slate-600 leading-relaxed m-0">
                            {{ __('Proporciona ferramentas de análise métrica (indexação Scopus, Web of Science, quartis SJR/JCR e indicadores CEOS.PP), simplificando tanto a avaliação do desempenho docente como o reporte e auditoria institucional perante organismos de acreditação.') }}
                        </p>
                    </div>

                    <div class="flex items-start gap-4">
                        <div class="w-10 h-10 shrink-0 flex items-center justify-center bg-emerald-50 border border-emerald-200 rounded-xl text-emerald-700 shadow-xs mt-0.5">
                            <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                <polygon points="12 2 15.09 8.26 22 9.27 17 14.14 18.18 21.02 12 17.77 5.82 21.02 7 14.14 2 9.27 8.91 8.26 12 2"></polygon>
                            </svg>
                        </div>
                        <p class="text-sm sm:text-base text-slate-600 leading-relaxed m-0">
                            {{ __('Alinhado com as políticas de Ciência Aberta e o desenvolvimento socioeconómico e científico regional, o portal promove a transparência e valoriza o impacto das publicações e projetos no ecossistema de ensino superior.') }}
                        </p>
                    </div>
                </div>

                <div class="flex flex-wrap items-center gap-3">
                    <a href="{{ route('help.index') }}" class="inline-flex items-center justify-center gap-2 px-6 py-3 bg-gradient-to-r from-emerald-800 to-emerald-700 hover:from-emerald-700 hover:to-emerald-600 text-white font-bold text-sm tracking-wide rounded-xl shadow-md hover:shadow-lg hover:-translate-y-0.5 transition-all">
                        <span>{{ __('Ajuda & Guia CiênciaVitae') }}</span>
                        <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
                            <line x1="5" y1="12" x2="19" y2="12"></line>
                            <polyline points="12 5 19 12 12 19"></polyline>
                        </svg>
                    </a>
                    <a href="https://www.islagaia.pt" target="_blank" rel="noopener noreferrer" class="inline-flex items-center justify-center gap-2 px-5 py-3 bg-slate-100 hover:bg-slate-200 text-slate-700 font-semibold text-sm rounded-xl transition-colors">
                        <span>{{ __('Website ISLA Gaia') }}</span>
                        <svg class="w-4 h-4 text-slate-500" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14" /></svg>
                    </a>
                </div>
            </div>
        </div>

        <!-- Ficha Técnica & Equipa do Projeto (Relatório Final) -->
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <!-- Ficha Técnica -->
            <div class="bg-white border border-slate-200/80 rounded-2xl p-6 shadow-sm">
                <h2 class="text-lg font-bold text-slate-900 mb-4 flex items-center gap-2">
                    <svg class="w-5 h-5 text-emerald-600" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" /></svg>
                    {{ __('Ficha Técnica do Projeto') }}
                </h2>
                <dl class="divide-y divide-slate-100 text-sm">
                    <div class="py-2.5 flex justify-between gap-4">
                        <dt class="font-medium text-slate-500">{{ __('Programa') }}</dt>
                        <dd class="text-slate-800 font-semibold text-right">POCH – Programa Operacional Capital Humano</dd>
                    </div>
                    <div class="py-2.5 flex justify-between gap-4">
                        <dt class="font-medium text-slate-500">{{ __('Código do Projeto') }}</dt>
                        <dd class="text-slate-800 font-semibold font-mono text-right">POCH-02-53I2-FSE-000019</dd>
                    </div>
                    <div class="py-2.5 flex justify-between gap-4">
                        <dt class="font-medium text-slate-500">{{ __('Entidade Beneficiária') }}</dt>
                        <dd class="text-slate-800 font-semibold text-right">ISLA-IPGT, Vila Nova de Gaia</dd>
                    </div>
                    <div class="py-2.5 flex justify-between gap-4">
                        <dt class="font-medium text-slate-500">{{ __('Coordenador / Gestor') }}</dt>
                        <dd class="text-slate-800 font-semibold text-right">Prof. Doutor Firmino Silva</dd>
                    </div>
                    <div class="py-2.5 flex justify-between gap-4">
                        <dt class="font-medium text-slate-500">{{ __('Contactos Institucionais') }}</dt>
                        <dd class="text-slate-700 text-right text-xs leading-relaxed">
                            Rua Diogo de Macedo, nº 192, 4400-107 Gaia<br>
                            Tel: (+351) 22 377 29 80 | info@islagaia.pt
                        </dd>
                    </div>
                </dl>
            </div>

            <!-- Equipa e Participantes -->
            <div class="bg-white border border-slate-200/80 rounded-2xl p-6 shadow-sm">
                <h2 class="text-lg font-bold text-slate-900 mb-4 flex items-center gap-2">
                    <svg class="w-5 h-5 text-emerald-600" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z" /></svg>
                    {{ __('Equipa de Desenvolvimento e Investigação') }}
                </h2>
                <p class="text-xs text-slate-500 mb-4">
                    {{ __('Docentes, investigadores e participantes responsáveis pela especificação, conceção arquitetural e implementação do portal:') }}
                </p>
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                    <div class="p-3 rounded-xl bg-slate-50 border border-slate-100 flex items-center gap-3">
                        <div class="w-8 h-8 rounded-full bg-emerald-700 text-white font-bold text-xs flex items-center justify-center">FS</div>
                        <div>
                            <div class="text-xs font-semibold text-slate-800">Firmino Silva</div>
                            <div class="text-[11px] text-slate-500">Coordenador & Gestor</div>
                        </div>
                    </div>
                    <div class="p-3 rounded-xl bg-slate-50 border border-slate-100 flex items-center gap-3">
                        <div class="w-8 h-8 rounded-full bg-slate-700 text-white font-bold text-xs flex items-center justify-center">AS</div>
                        <div>
                            <div class="text-xs font-semibold text-slate-800">André Santos</div>
                            <div class="text-[11px] text-slate-500">Participante</div>
                        </div>
                    </div>
                    <div class="p-3 rounded-xl bg-slate-50 border border-slate-100 flex items-center gap-3">
                        <div class="w-8 h-8 rounded-full bg-slate-700 text-white font-bold text-xs flex items-center justify-center">CA</div>
                        <div>
                            <div class="text-xs font-semibold text-slate-800">Cristiano Aires</div>
                            <div class="text-[11px] text-slate-500">Participante</div>
                        </div>
                    </div>
                    <div class="p-3 rounded-xl bg-slate-50 border border-slate-100 flex items-center gap-3">
                        <div class="w-8 h-8 rounded-full bg-slate-700 text-white font-bold text-xs flex items-center justify-center">HG</div>
                        <div>
                            <div class="text-xs font-semibold text-slate-800">Helena Gonçalves</div>
                            <div class="text-[11px] text-slate-500">Participante</div>
                        </div>
                    </div>
                    <div class="p-3 rounded-xl bg-slate-50 border border-slate-100 flex items-center gap-3">
                        <div class="w-8 h-8 rounded-full bg-slate-700 text-white font-bold text-xs flex items-center justify-center">JD</div>
                        <div>
                            <div class="text-xs font-semibold text-slate-800">Jorge Duque</div>
                            <div class="text-[11px] text-slate-500">Participante</div>
                        </div>
                    </div>
                    <div class="p-3 rounded-xl bg-slate-50 border border-slate-100 flex items-center gap-3">
                        <div class="w-8 h-8 rounded-full bg-slate-700 text-white font-bold text-xs flex items-center justify-center">AP</div>
                        <div>
                            <div class="text-xs font-semibold text-slate-800">Arnaldo Pinheiro</div>
                            <div class="text-[11px] text-slate-500">Participante</div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Poster Científico - Dia da Ciência 2023 (Apêndice 9) -->
        <div class="bg-gradient-to-br from-emerald-50 to-slate-50 border border-emerald-200/80 rounded-2xl p-6 sm:p-8 shadow-sm">
            <div class="flex flex-col md:flex-row items-center gap-6 lg:gap-8">
                <div class="w-full md:w-1/3 max-w-[240px] shrink-0">
                    <a href="{{ asset('logo/poster-dia-da-ciencia-2023.png') }}" target="_blank" title="Clique para ampliar o Poster Científico" class="group block relative rounded-xl overflow-hidden border border-slate-300 shadow-md hover:shadow-xl transition-all">
                        <img src="{{ asset('logo/poster-dia-da-ciencia-2023.png') }}" alt="Poster Científico - Dia da Ciência 2023" class="w-full h-auto object-cover group-hover:scale-105 transition-transform duration-300">
                        <div class="absolute inset-0 bg-slate-900/30 opacity-0 group-hover:opacity-100 flex items-center justify-center transition-opacity text-white text-xs font-semibold">
                            <span class="px-3 py-1.5 bg-black/60 rounded-lg backdrop-blur-xs flex items-center gap-1.5">
                                <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0zM10 7v3m0 0v3m0-3h3m-3 0H7" /></svg>
                                {{ __('Ver em tamanho real') }}
                            </span>
                        </div>
                    </a>
                </div>
                <div class="w-full md:w-2/3 space-y-4">
                    <div class="inline-flex items-center gap-2 px-3 py-1 rounded-full text-xs font-semibold bg-emerald-200/70 text-emerald-900">
                        <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" /></svg>
                        {{ __('Dia da Ciência 2023 • 24 de Novembro de 2023') }}
                    </div>
                    <h3 class="text-xl sm:text-2xl font-bold text-slate-900">
                        {{ __('Poster Científico Apresentado no Auditório do ISLA Gaia') }}
                    </h3>
                    <p class="text-sm text-slate-600 leading-relaxed">
                        {{ __('Este projeto deu origem a um poster científico apresentado no Dia da Ciência 2023, demonstrando a metodologia de integração com o ecossistema CiênciaVitae e os fluxos de extração e curadoria dos indicadores científicos do corpo docente.') }}
                    </p>
                    <div>
                        <a href="https://ci-islagaia.pt/event/dia-da-ciencia-2023/" target="_blank" rel="noopener noreferrer" class="inline-flex items-center gap-2 px-4 py-2.5 bg-emerald-800 hover:bg-emerald-700 text-white rounded-xl text-xs sm:text-sm font-semibold shadow-xs transition-colors">
                            <span>{{ __('Consultar Página do Evento no CI-ISLA') }}</span>
                            <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14" /></svg>
                        </a>
                    </div>
                </div>
            </div>
        </div>

        <!-- POCH Logos Card -->
        <div class="w-full bg-white border border-slate-200/80 rounded-2xl p-6 sm:p-8 shadow-sm text-center">
            <div class="max-w-2xl mx-auto">
                <img src="{{ asset('logo/poch-logos.webp') }}" alt="Logotipos POCH" class="w-full h-auto block">
            </div>
        </div>
    </div>
</x-app-layout>
