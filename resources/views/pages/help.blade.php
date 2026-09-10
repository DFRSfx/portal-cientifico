<x-app-layout>

    <x-slot:title>
        Ajuda
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
                    <div class="card shadow-sm border border-slate-200/80">
                        <div class="card-body p-6">
                            <h4 class="h4 font-bold text-emerald-800 mb-2">{{ __('Perguntas Mais Frequentes') }}</h4>
                            <p class="text-slate-600 text-sm leading-relaxed mb-6">{{ __('Encontre respostas para perguntas frequentes na nossa secção de perguntas frequentes. Abordamos as perguntas mais comuns que os nossos utilizadores fazem e fornecemos soluções claras e úteis. Se ainda tiver dúvidas, entre em contacto com a nossa equipa de suporte.') }}
                            </p>

                            <div class="w-full divide-y divide-slate-200 rounded-xl border border-slate-200 overflow-hidden bg-white shadow-xs"
                                 x-data="{ active: null }">

                                <!-- Item 1 -->
                                <div class="bg-white">
                                    <button type="button"
                                        @click="active = (active === 1 ? null : 1)"
                                        class="w-full px-5 py-4 text-left font-medium text-slate-800 flex justify-between items-center hover:bg-slate-50 transition cursor-pointer border-0 bg-transparent"
                                        :aria-expanded="active === 1">
                                        <span class="text-sm md:text-base font-semibold">{{ __('O que é a nossa aplicação?') }}</span>
                                        <i class="fas fa-chevron-down text-slate-400 text-xs transition-transform duration-200"
                                           :class="active === 1 ? 'rotate-180 text-emerald-700' : ''"></i>
                                    </button>
                                    <div x-show="active === 1"
                                         x-transition:enter="transition ease-out duration-200"
                                         x-transition:enter-start="opacity-0 -translate-y-1"
                                         x-transition:enter-end="opacity-100 translate-y-0"
                                         style="display: none;"
                                         class="px-5 pb-5 pt-1 text-slate-600 text-sm leading-relaxed border-t border-slate-100 bg-slate-50/50">
                                        <p class="mb-0">{{ __('O Portal Científico é uma plataforma baseada na informação do CiênciaVitae que promove a integração e divulgação da produção científica de docentes e discentes de uma Instituição de Ensino Superior (IES). Esta plataforma tem como objetivo facilitar o acesso e a partilha de pesquisas, artigos, projetos e demais contribuições científicas, criando um ambiente colaborativo e enriquecedor para o avanço do conhecimento.') }}</p>
                                    </div>
                                </div>

                                <!-- Item 2 -->
                                <div class="bg-white">
                                    <button type="button"
                                        @click="active = (active === 2 ? null : 2)"
                                        class="w-full px-5 py-4 text-left font-medium text-slate-800 flex justify-between items-center hover:bg-slate-50 transition cursor-pointer border-0 bg-transparent"
                                        :aria-expanded="active === 2">
                                        <span class="text-sm md:text-base font-semibold">{{ __('Qual o objetivo desta aplicação?') }}</span>
                                        <i class="fas fa-chevron-down text-slate-400 text-xs transition-transform duration-200"
                                           :class="active === 2 ? 'rotate-180 text-emerald-700' : ''"></i>
                                    </button>
                                    <div x-show="active === 2"
                                         x-transition:enter="transition ease-out duration-200"
                                         x-transition:enter-start="opacity-0 -translate-y-1"
                                         x-transition:enter-end="opacity-100 translate-y-0"
                                         style="display: none;"
                                         class="px-5 pb-5 pt-1 text-slate-600 text-sm leading-relaxed border-t border-slate-100 bg-slate-50/50">
                                        <p class="mb-0">{{ __('O objetivo desta aplicação é promover a integração e divulgação da produção científica de docentes e discentes de uma Instituição de Ensino Superior (IES), tendo como objetivo facilitar o acesso e a partilha de pesquisas, artigos, projetos e demais contribuições científicas, criando um ambiente colaborativo e enriquecedor para o avanço do conhecimento.') }}</p>
                                    </div>
                                </div>

                                <!-- Item 3 -->
                                <div class="bg-white">
                                    <button type="button"
                                        @click="active = (active === 3 ? null : 3)"
                                        class="w-full px-5 py-4 text-left font-medium text-slate-800 flex justify-between items-center hover:bg-slate-50 transition cursor-pointer border-0 bg-transparent"
                                        :aria-expanded="active === 3">
                                        <span class="text-sm md:text-base font-semibold">{{ __('Como funciona a aplicação?') }}</span>
                                        <i class="fas fa-chevron-down text-slate-400 text-xs transition-transform duration-200"
                                           :class="active === 3 ? 'rotate-180 text-emerald-700' : ''"></i>
                                    </button>
                                    <div x-show="active === 3"
                                         x-transition:enter="transition ease-out duration-200"
                                         x-transition:enter-start="opacity-0 -translate-y-1"
                                         x-transition:enter-end="opacity-100 translate-y-0"
                                         style="display: none;"
                                         class="px-5 pb-5 pt-1 text-slate-600 text-sm leading-relaxed border-t border-slate-100 bg-slate-50/50">
                                        <p class="mb-0">{{ __('A aplicação usa algoritmos avançados para receber utilizadores e seus artigos cientifcos, do CienciaVitae, para posteriormente os utilizadores puderem interagir com os dados coletados, desde pesquisar por autores, artigos, Keywords, entre outros, através de um sistema avançado de filtragem/pesquisa.') }}</p>
                                    </div>
                                </div>

                            </div>

                            <div class="mt-8 pt-6 border-t border-slate-200">
                                <h5 class="h5 font-bold text-emerald-800 mb-2">{{ __('Contato') }}</h5>
                                <p class="text-slate-600 text-sm mb-4">
                                    {{ __('Estamos aqui para ajudá-lo! Se precisar de assistência com qualquer aspecto do nosso site, entre em contacto com a nossa equipa de suporte e teremos o prazer de ajudá-lo.') }}
                                </p>
                                <a href="mailto:info@islagaia.pt" class="btn btn-primary btn-rounded" role="button">{{ __('Contato') }}</a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </main>
</x-app-layout>
