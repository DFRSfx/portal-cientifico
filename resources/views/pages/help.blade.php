<x-app-layout>

    <x-slot:title>
        {{ __('Ajuda') }}
    </x-slot>

    <main>
        <div class="container my-10">
            <div class="row g-4">
                <div class="col-xl-3">
                    <div class="overflow-hidden rounded-2xl shadow-sm border border-slate-200/80 bg-white p-2">
                        <img src="{{ asset('logo/image-portal.webp') }}" class="img-fluid rounded-xl w-full object-cover" alt="Logo POCH">
                    </div>
                </div>
                <div class="col-xl-9">
                    <div class="card shadow-sm border border-slate-200/80 rounded-2xl overflow-hidden bg-white" x-data="{ currentTab: 'faq', activeFaq: 1, activeGuide: 1 }">
                        {{-- Tab Navigation --}}
                        <div class="border-b border-slate-200 bg-slate-50/70 p-3 sm:p-4 flex items-center gap-2">
                            <button type="button" @click="currentTab = 'faq'"
                                    :class="currentTab === 'faq' ? 'bg-emerald-700 text-white shadow-xs' : 'bg-white text-slate-700 hover:bg-slate-100 border border-slate-200'"
                                    class="px-4 py-2 text-xs sm:text-sm font-semibold rounded-xl transition-all cursor-pointer border-0 inline-flex items-center gap-2">
                                <i class="fas fa-circle-question text-xs"></i>
                                <span>{{ __('Perguntas Frequentes (FAQ)') }}</span>
                            </button>
                            <button type="button" @click="currentTab = 'guide'"
                                    :class="currentTab === 'guide' ? 'bg-emerald-700 text-white shadow-xs' : 'bg-white text-slate-700 hover:bg-slate-100 border border-slate-200'"
                                    class="px-4 py-2 text-xs sm:text-sm font-semibold rounded-xl transition-all cursor-pointer border-0 inline-flex items-center gap-2">
                                <i class="fas fa-book-bookmark text-xs"></i>
                                <span>{{ __('Guia de Boas Práticas CiênciaVitae') }}</span>
                            </button>
                        </div>

                        <div class="card-body p-6 sm:p-8">

                            {{-- TAB 1: PERGUNTAS FREQUENTES (FAQ) --}}
                            <div x-show="currentTab === 'faq'" x-cloak>
                                <div class="mb-6">
                                    <h2 class="text-xl sm:text-2xl font-bold text-emerald-800 tracking-tight flex items-center gap-2 mb-2">
                                        <i class="fas fa-circle-question text-emerald-700"></i>
                                        <span>{{ __('Perguntas Mais Frequentes') }}</span>
                                    </h2>
                                    <p class="text-slate-600 text-xs sm:text-sm leading-relaxed mb-0">
                                        {{ __('Encontre respostas para as questões mais comuns relativas à utilização e funcionamento do Portal Científico.') }}
                                    </p>
                                </div>

                                <div class="w-full divide-y divide-slate-200 rounded-xl border border-slate-200 overflow-hidden bg-white shadow-xs">
                                    <!-- Item 1 -->
                                    <div class="bg-white">
                                        <button type="button" @click="activeFaq = (activeFaq === 1 ? null : 1)"
                                            class="w-full px-5 py-4 text-left font-medium text-slate-800 flex justify-between items-center hover:bg-slate-50 transition cursor-pointer border-0 bg-transparent"
                                            :aria-expanded="activeFaq === 1">
                                            <span class="text-xs sm:text-sm font-semibold">{{ __('O que é o Portal Científico?') }}</span>
                                            <i class="fas fa-chevron-down text-slate-400 text-xs transition-transform duration-200" :class="activeFaq === 1 ? 'rotate-180 text-emerald-700' : ''"></i>
                                        </button>
                                        <div x-show="activeFaq === 1" class="px-5 pb-5 pt-1 text-slate-600 text-xs sm:text-sm leading-relaxed border-t border-slate-100 bg-slate-50/50">
                                            <p class="mb-0">{{ __('O Portal Científico é uma plataforma baseada na informação do CiênciaVitae que promove a integração e divulgação da produção científica de docentes e discentes de uma Instituição de Ensino Superior (IES). Esta plataforma tem como objetivo facilitar o acesso e a partilha de pesquisas, artigos, projetos e demais contribuições científicas, criando um ambiente colaborativo e enriquecedor para o avanço do conhecimento.') }}</p>
                                        </div>
                                    </div>

                                    <!-- Item 2 -->
                                    <div class="bg-white">
                                        <button type="button" @click="activeFaq = (activeFaq === 2 ? null : 2)"
                                            class="w-full px-5 py-4 text-left font-medium text-slate-800 flex justify-between items-center hover:bg-slate-50 transition cursor-pointer border-0 bg-transparent"
                                            :aria-expanded="activeFaq === 2">
                                            <span class="text-xs sm:text-sm font-semibold">{{ __('Qual o objetivo desta aplicação?') }}</span>
                                            <i class="fas fa-chevron-down text-slate-400 text-xs transition-transform duration-200" :class="activeFaq === 2 ? 'rotate-180 text-emerald-700' : ''"></i>
                                        </button>
                                        <div x-show="activeFaq === 2" style="display:none;" class="px-5 pb-5 pt-1 text-slate-600 text-xs sm:text-sm leading-relaxed border-t border-slate-100 bg-slate-50/50">
                                            <p class="mb-0">{{ __('O objetivo desta aplicação é promover a integração e divulgação da produção científica de docentes e discentes de uma Instituição de Ensino Superior (IES), facilitando o acesso e a partilha de pesquisas, projetos e métricas com o sistema de qualidade institucional.') }}</p>
                                        </div>
                                    </div>

                                    <!-- Item 3 -->
                                    <div class="bg-white">
                                        <button type="button" @click="activeFaq = (activeFaq === 3 ? null : 3)"
                                            class="w-full px-5 py-4 text-left font-medium text-slate-800 flex justify-between items-center hover:bg-slate-50 transition cursor-pointer border-0 bg-transparent"
                                            :aria-expanded="activeFaq === 3">
                                            <span class="text-xs sm:text-sm font-semibold">{{ __('Como são sincronizados os dados com o CiênciaVitae?') }}</span>
                                            <i class="fas fa-chevron-down text-slate-400 text-xs transition-transform duration-200" :class="activeFaq === 3 ? 'rotate-180 text-emerald-700' : ''"></i>
                                        </button>
                                        <div x-show="activeFaq === 3" style="display:none;" class="px-5 pb-5 pt-1 text-slate-600 text-xs sm:text-sm leading-relaxed border-t border-slate-100 bg-slate-50/50">
                                            <p class="mb-0">{{ __('Os dados são recolhidos diretamente da API oficial do CiênciaVitae (FCT) utilizando o Ciência ID de cada utilizador. A plataforma compara as datas de modificação para minimizar requisições desnecessárias e permite a atualização manual através do botão "Atualizar Ciência Vitae" no Painel do utilizador.') }}</p>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            {{-- TAB 2: GUIA DE BOAS PRÁTICAS CIÊNCIAVITAE (ANEXO 8) --}}
                            <div x-show="currentTab === 'guide'" x-cloak>
                                <div class="mb-6">
                                    <h2 class="text-xl sm:text-2xl font-bold text-emerald-800 tracking-tight flex items-center gap-2 mb-2">
                                        <i class="fas fa-graduation-cap text-emerald-700"></i>
                                        <span>{{ __('Guia: Boas Práticas para o Preenchimento no CiênciaVitae') }}</span>
                                    </h2>
                                    <p class="text-slate-600 text-xs sm:text-sm leading-relaxed mb-0">
                                        {{ __('Ao colaborar no Portal Científico, é crucial que os perfis dos autores sejam preenchidos com rigor e completude. A integridade dessas informações assegura a correta agregação na plataforma, a precisão das estatísticas institucionais e a conformidade para a avaliação de desempenho docente.') }}
                                    </p>
                                </div>

                                <div class="w-full divide-y divide-slate-200 rounded-xl border border-slate-200 overflow-hidden bg-white shadow-xs">

                                    {{-- 8.1 Domínios de Atuação --}}
                                    <div class="bg-white">
                                        <button type="button" @click="activeGuide = (activeGuide === 1 ? null : 1)"
                                                class="w-full px-5 py-4 text-left font-medium text-slate-800 flex justify-between items-center hover:bg-slate-50 transition cursor-pointer border-0 bg-transparent">
                                            <span class="text-xs sm:text-sm font-bold flex items-center gap-2 text-emerald-900">
                                                <span class="w-6 h-6 rounded-full bg-emerald-100 text-emerald-800 flex items-center justify-center text-xs font-black">1</span>
                                                {{ __('8.1 Domínios de Atuação para a Definição do Perfil') }}
                                            </span>
                                            <i class="fas fa-chevron-down text-slate-400 text-xs transition-transform duration-200" :class="activeGuide === 1 ? 'rotate-180 text-emerald-700' : ''"></i>
                                        </button>
                                        <div x-show="activeGuide === 1" class="px-5 pb-5 pt-2 text-slate-600 text-xs sm:text-sm leading-relaxed border-t border-slate-100 bg-slate-50/40 space-y-2">
                                            <p>{{ __('Ao definir o perfil do autor, é fundamental preencher com precisão os domínios de atuação, fornecendo informações específicas:') }}</p>
                                            <ul class="list-disc pl-5 space-y-1.5 mb-0 text-slate-700">
                                                <li><strong>{{ __('Área do Conhecimento:') }}</strong> {{ __('Selecione a área que melhor representa o seu campo de atuação para fins de categorização institucional.') }}</li>
                                                <li><strong>{{ __('Tópico:') }}</strong> {{ __('Especifique o tópico específico na área escolhida para proporcionar uma definição refinada da sua especialização.') }}</li>
                                                <li><strong>{{ __('Palavras-Chave:') }}</strong> {{ __('Introduza termos que descrevam com exatidão os seus interesses, pesquisas e competências para facilitar a pesquisa e a formação de redes colaborativas.') }}</li>
                                            </ul>
                                        </div>
                                    </div>

                                    {{-- 8.2 Percurso Profissional --}}
                                    <div class="bg-white">
                                        <button type="button" @click="activeGuide = (activeGuide === 2 ? null : 2)"
                                                class="w-full px-5 py-4 text-left font-medium text-slate-800 flex justify-between items-center hover:bg-slate-50 transition cursor-pointer border-0 bg-transparent">
                                            <span class="text-xs sm:text-sm font-bold flex items-center gap-2 text-emerald-900">
                                                <span class="w-6 h-6 rounded-full bg-emerald-100 text-emerald-800 flex items-center justify-center text-xs font-black">2</span>
                                                {{ __('8.2 Preenchimento do Percurso Profissional') }}
                                            </span>
                                            <i class="fas fa-chevron-down text-slate-400 text-xs transition-transform duration-200" :class="activeGuide === 2 ? 'rotate-180 text-emerald-700' : ''"></i>
                                        </button>
                                        <div x-show="activeGuide === 2" style="display:none;" class="px-5 pb-5 pt-2 text-slate-600 text-xs sm:text-sm leading-relaxed border-t border-slate-100 bg-slate-50/40 space-y-2">
                                            <p>{{ __('O preenchimento do percurso profissional reflete as responsabilidades académicas e institucionais. Campos obrigatórios e recomendados:') }}</p>
                                            <ul class="list-disc pl-5 space-y-1.5 mb-0 text-slate-700">
                                                <li><strong>{{ __('Tipo de Informação & Emprego:') }}</strong> {{ __('Indique Ciência, Docência no Ensino Superior ou Cargos e Funções, discriminando tempo integral, tempo parcial, etc.') }}</li>
                                                <li><strong>{{ __('Empregador & Vínculo:') }}</strong> {{ __('Selecione o ISLA-IPGT e descreva o tipo de vínculo profissional e categoria.') }}</li>
                                                <li><strong>{{ __('Datas de Início e Fim:') }}</strong> {{ __('Mantenha a data de fim em branco caso se encontre atualmente em exercício de funções.') }}</li>
                                                <li><strong>{{ __('Atividades Associadas:') }}</strong> {{ __('Associe orientações de teses, comissões de avaliação, júris de grau académico e comités científicos de eventos.') }}</li>
                                            </ul>
                                        </div>
                                    </div>

                                    {{-- 8.3 Projetos --}}
                                    <div class="bg-white">
                                        <button type="button" @click="activeGuide = (activeGuide === 3 ? null : 3)"
                                                class="w-full px-5 py-4 text-left font-medium text-slate-800 flex justify-between items-center hover:bg-slate-50 transition cursor-pointer border-0 bg-transparent">
                                            <span class="text-xs sm:text-sm font-bold flex items-center gap-2 text-emerald-900">
                                                <span class="w-6 h-6 rounded-full bg-emerald-100 text-emerald-800 flex items-center justify-center text-xs font-black">3</span>
                                                {{ __('8.3 Preenchimento dos Projetos de Investigação') }}
                                            </span>
                                            <i class="fas fa-chevron-down text-slate-400 text-xs transition-transform duration-200" :class="activeGuide === 3 ? 'rotate-180 text-emerald-700' : ''"></i>
                                        </button>
                                        <div x-show="activeGuide === 3" style="display:none;" class="px-5 pb-5 pt-2 text-slate-600 text-xs sm:text-sm leading-relaxed border-t border-slate-100 bg-slate-50/40 space-y-2">
                                            <p>{{ __('Para projetos de investigação financiados ou institucionais:') }}</p>
                                            <ul class="list-disc pl-5 space-y-1.5 mb-0 text-slate-700">
                                                <li><strong>{{ __('Identificador DOI / Referência:') }}</strong> {{ __('Importe sempre o DOI ou código de financiamento oficial do projeto (ex.: FCT, POCH, Horizonte Europa).') }}</li>
                                                <li><strong>{{ __('Função Desempenhada:') }}</strong> {{ __('Distinga entre Investigador Principal (Principal Investigator) e Colaborador, dado que o sistema de avaliação docente diferencia a liderança de projetos.') }}</li>
                                                <li><strong>{{ __('Entidade Financiadora & Programa:') }}</strong> {{ __('Registe o centro de investigação de acolhimento e o programa de financiamento.') }}</li>
                                            </ul>
                                        </div>
                                    </div>

                                    {{-- 8.4 Outputs Académicos --}}
                                    <div class="bg-white">
                                        <button type="button" @click="activeGuide = (activeGuide === 4 ? null : 4)"
                                                class="w-full px-5 py-4 text-left font-medium text-slate-800 flex justify-between items-center hover:bg-slate-50 transition cursor-pointer border-0 bg-transparent">
                                            <span class="text-xs sm:text-sm font-bold flex items-center gap-2 text-emerald-900">
                                                <span class="w-6 h-6 rounded-full bg-emerald-100 text-emerald-800 flex items-center justify-center text-xs font-black">4</span>
                                                {{ __('8.4 Preenchimento dos Campos dos Outputs (Artigos, Livros, Conferências)') }}
                                            </span>
                                            <i class="fas fa-chevron-down text-slate-400 text-xs transition-transform duration-200" :class="activeGuide === 4 ? 'rotate-180 text-emerald-700' : ''"></i>
                                        </button>
                                        <div x-show="activeGuide === 4" style="display:none;" class="px-5 pb-5 pt-2 text-slate-600 text-xs sm:text-sm leading-relaxed border-t border-slate-100 bg-slate-50/40 space-y-2">
                                            <ul class="list-disc pl-5 space-y-1.5 mb-0 text-slate-700">
                                                <li><strong>{{ __('Identificador DOI:') }}</strong> {{ __('Sempre que disponível, inclua o DOI numérico ou link permanente. O Portal Científico utiliza o DOI para correlacionar citações Scopus e quartis.') }}</li>
                                                <li><strong>{{ __('Estado da Publicação:') }}</strong> {{ __('Identifique corretamente se o output está "Published" (Publicado), "Accepted" (Aceite), "In review" ou "Submitted".') }}</li>
                                                <li><strong>{{ __('Tipo de Output:') }}</strong> {{ __('Classifique com precisão entre Artigo em Revista, Livro, Capítulo de Livro, Artigo em Conferência ou Poster.') }}</li>
                                            </ul>
                                        </div>
                                    </div>

                                    {{-- 8.5 Keywords --}}
                                    <div class="bg-white">
                                        <button type="button" @click="activeGuide = (activeGuide === 5 ? null : 5)"
                                                class="w-full px-5 py-4 text-left font-medium text-slate-800 flex justify-between items-center hover:bg-slate-50 transition cursor-pointer border-0 bg-transparent">
                                            <span class="text-xs sm:text-sm font-bold flex items-center gap-2 text-emerald-900">
                                                <span class="w-6 h-6 rounded-full bg-emerald-100 text-emerald-800 flex items-center justify-center text-xs font-black">5</span>
                                                {{ __('8.5 Palavras-Chave dos Artigos') }}
                                            </span>
                                            <i class="fas fa-chevron-down text-slate-400 text-xs transition-transform duration-200" :class="activeGuide === 5 ? 'rotate-180 text-emerald-700' : ''"></i>
                                        </button>
                                        <div x-show="activeGuide === 5" style="display:none;" class="px-5 pb-5 pt-2 text-slate-600 text-xs sm:text-sm leading-relaxed border-t border-slate-100 bg-slate-50/40">
                                            <p class="mb-0">{{ __('Escolha palavras-chave específicas e normalizadas para facilitar a indexação temática nos motores de busca e filtros do repositório central.') }}</p>
                                        </div>
                                    </div>

                                    {{-- 8.6 Autores e Co-autores --}}
                                    <div class="bg-white">
                                        <button type="button" @click="activeGuide = (activeGuide === 6 ? null : 6)"
                                                class="w-full px-5 py-4 text-left font-medium text-slate-800 flex justify-between items-center hover:bg-slate-50 transition cursor-pointer border-0 bg-transparent">
                                            <span class="text-xs sm:text-sm font-bold flex items-center gap-2 text-emerald-900">
                                                <span class="w-6 h-6 rounded-full bg-emerald-100 text-emerald-800 flex items-center justify-center text-xs font-black">6</span>
                                                {{ __('8.6 Associação de Autores e Co-autores') }}
                                            </span>
                                            <i class="fas fa-chevron-down text-slate-400 text-xs transition-transform duration-200" :class="activeGuide === 6 ? 'rotate-180 text-emerald-700' : ''"></i>
                                        </button>
                                        <div x-show="activeGuide === 6" style="display:none;" class="px-5 pb-5 pt-2 text-slate-600 text-xs sm:text-sm leading-relaxed border-t border-slate-100 bg-slate-50/40">
                                            <p class="mb-0">{{ __('Registe todos os co-autores e associe o respectivo Ciência ID sempre que disponível. Isto evita a duplicação de publicações no repositório institucional e assegura a partilha da produção colaborativa.') }}</p>
                                        </div>
                                    </div>

                                    {{-- 8.7 Discriminação de Atividades --}}
                                    <div class="bg-white">
                                        <button type="button" @click="activeGuide = (activeGuide === 7 ? null : 7)"
                                                class="w-full px-5 py-4 text-left font-medium text-slate-800 flex justify-between items-center hover:bg-slate-50 transition cursor-pointer border-0 bg-transparent">
                                            <span class="text-xs sm:text-sm font-bold flex items-center gap-2 text-emerald-900">
                                                <span class="w-6 h-6 rounded-full bg-emerald-100 text-emerald-800 flex items-center justify-center text-xs font-black">7</span>
                                                {{ __('8.7 Discriminação de Atividades e Impacto') }}
                                            </span>
                                            <i class="fas fa-chevron-down text-slate-400 text-xs transition-transform duration-200" :class="activeGuide === 7 ? 'rotate-180 text-emerald-700' : ''"></i>
                                        </button>
                                        <div x-show="activeGuide === 7" style="display:none;" class="px-5 pb-5 pt-2 text-slate-600 text-xs sm:text-sm leading-relaxed border-t border-slate-100 bg-slate-50/40">
                                            <p class="mb-0">{{ __('Descreva detalhadamente tarefas de arbitragem científica, participação em júris e organização de conferências. Estas atividades são importadas automaticamente pelo Portal Científico para preencher os relatórios de avaliação docente (CEOS / Qualidade).') }}</p>
                                        </div>
                                    </div>

                                </div>
                            </div>

                            {{-- Contact Support Footer --}}
                            <div class="mt-8 pt-6 border-t border-slate-200 flex flex-col sm:flex-row sm:items-center justify-between gap-4">
                                <div>
                                    <h3 class="text-sm sm:text-base font-bold text-emerald-800 mb-1">{{ __('Precisa de apoio adicional?') }}</h3>
                                    <p class="text-slate-600 text-xs sm:text-sm mb-0">
                                        {{ __('Entre em contacto com a equipa técnica para esclarecimento de dúvidas ou suporte à sincronização.') }}
                                    </p>
                                </div>
                                <a href="mailto:info@islagaia.pt"
                                    class="inline-flex items-center gap-2 px-5 py-2.5 text-xs sm:text-sm font-semibold text-white bg-emerald-700 hover:bg-emerald-800 rounded-xl shadow-xs transition-all cursor-pointer shrink-0">
                                    <i class="fas fa-envelope text-xs"></i>
                                    <span>{{ __('Contactar Suporte') }}</span>
                                </a>
                            </div>

                        </div>
                    </div>
                </div>
            </div>
        </div>
    </main>
</x-app-layout>
