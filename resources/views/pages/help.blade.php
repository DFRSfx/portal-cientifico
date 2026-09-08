<x-app-layout>

    <x-slot:title>
        Ajuda
    </x-slot>

    <main>
        <div class="container " style="margin-top: 50px;">
            <div class="row">
                <div class="col-xl-3">
                    <div class="bg-image hover-overlay shadow-1-strong rounded-5 ripple" data-mdb-ripple-color="light">
                        <img src="{{ asset('logo/image-portal.webp') }}" class="img-fluid " alt="Logo POCH">
                        <div class="mask" style="background-color: rgba(251, 251, 251, 0.15)"></div>
                        </a>
                    </div>
                </div>
                <div class="col-xl-9">
                    <div class="card">
                        <div class="card-body mb-5">
                            <h4 class="h4" style="color:#33642b">{{ __('Perguntas Mais Frequentes') }}</h4>
                            <p>{{ __('Encontre respostas para perguntas frequentes na nossa secção de perguntas frequentes. Abordamos as perguntas mais comuns que os nossos utilizadores fazem e fornecemos soluções claras e úteis. Se ainda tiver dúvidas, entre em contacto com a nossa equipa de suporte.') }}
                            </p>
                            <div class="accordion w-100 mt-3" id="basicAccordion">
                                <div class="accordion-item">
                                    <h2 class="accordion-header" id="headingOne">
                                        <button class="accordion-button collapsed" type="button"
                                            data-mdb-toggle="collapse" data-mdb-target="#basicAccordionCollapseOne"
                                            aria-expanded="false" aria-controls="collapseOne">
                                            {{ __('O que é a nossa aplicação?') }}
                                        </button>
                                    </h2>
                                    <div id="basicAccordionCollapseOne" class="accordion-collapse collapse"
                                        aria-labelledby="headingOne" data-mdb-parent="#basicAccordion">
                                        <div class="accordion-body">
                                            <p>{{ __('O Portal Científico é uma plataforma baseada na informação do CiênciaVitae que promove a integração e divulgação da produção científica de docentes e discentes de uma Instituição de Ensino Superior (IES). Esta plataforma tem como objetivo facilitar o acesso e a partilha de pesquisas, artigos, projetos e demais contribuições científicas, criando um ambiente colaborativo e enriquecedor para o avanço do conhecimento.') }}
                                            </p>
                                        </div>
                                    </div>
                                </div>
                                <div class="accordion-item">
                                    <h2 class="accordion-header" id="headingTwo">
                                        <button class="accordion-button collapsed" type="button"
                                            data-mdb-toggle="collapse" data-mdb-target="#basicAccordionCollapseTwo"
                                            aria-expanded="false" aria-controls="collapseTwo">
                                            {{ __('Qual o objetivo desta aplicação?') }} </button>
                                    </h2>
                                    <div id="basicAccordionCollapseTwo" class="accordion-collapse collapse"
                                        aria-labelledby="headingTwo" data-mdb-parent="#basicAccordion">
                                        <div class="accordion-body">
                                            <p> {{ __('O objetivo desta aplicação é promover a integração e divulgação da produção científica de docentes e discentes de uma Instituição de Ensino Superior (IES), tendo como objetivo facilitar o acesso e a partilha de pesquisas, artigos, projetos e demais contribuições científicas, criando um ambiente colaborativo e enriquecedor para o avanço do conhecimento.') }}
                                            </p>
                                        </div>
                                    </div>
                                </div>
                                <div class="accordion-item">
                                    <h2 class="accordion-header" id="headingThree">
                                        <button class="accordion-button collapsed" type="button"
                                            data-mdb-toggle="collapse" data-mdb-target="#basicAccordionCollapseThree"
                                            aria-expanded="false" aria-controls="collapseThree">
                                            {{ __('Como funciona a aplicação?') }} </button>
                                    </h2>
                                    <div id="basicAccordionCollapseThree" class="accordion-collapse collapse"
                                        aria-labelledby="headingThree" data-mdb-parent="#basicAccordion">
                                        <div class="accordion-body">
                                            <p> {{ __('A aplicação usa algoritmos avançados para receber utilizadores e seus artigos cientifcos, do CienciaVitae, para posteriormente os utilizadores puderem interagir com os dados coletados, desde pesquisar por autores, artigos, <cite>Keywords</cite>, entre outros, através de um sistema avançado de filtragem/pesquisa.') }}
                                            </p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="mt-4">
                                <h5 class="h5" style="color:#33642b">{{ __('Contato') }}</h5>

                                <p>
                                    {{ __('Estamos aqui para ajudá-lo! Se precisar de assistência com qualquer aspecto do nosso site, entre em contacto com a nossa equipa de suporte e teremos o prazer de ajudá-lo.') }}
                                </p>
                                <a href="mailto:info@islagaia.pt" class="btn btn-rounded text-white" role="button"
                                    style="background-color:rgb(42, 107, 32)">{{ __('Contato') }}</a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        </div>
        </div>
    </main>
</x-app-layout>
