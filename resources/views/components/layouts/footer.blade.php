<footer class="text-center text-lg-start text-muted" style="background-color: #fff;">
    <!-- Section: Links  -->
    <section>
        <div class="container mt-5 pt-3">
            <!-- Grid row -->
            <div class="row mt-3">
                <!-- Grid column -->
                <div class="col-md-6 d-flex flex-column">
                    <div class="p-2">
                        <img class="img-fluid pb-4" src="{{ asset('logo\logo-portal-cientifico.svg') }}" width="200px"
                            height="auto" loading=”lazy” alt="logo portal científico">
                    </div>
                    <div class="p-2">
                        <p style="font-size:14px; font-weight:300;">
                            {{ __('O Portal Científico é uma plataforma baseada na informação do CiênciaVitae que promove a integração e divulgação da produção científica de docentes e discentes de uma Instituição de Ensino Superior (IES). Esta plataforma tem como objetivo facilitar o acesso e a partilha de pesquisas, artigos, projetos e demais contribuições científicas, criando um ambiente colaborativo e enriquecedor para o avanço do conhecimento.') }}
                        </p>
                    </div>
                </div>
                <!-- Grid column -->

                <div class="col-md-5 d-flex flex-column align-self-center">
                    <div class="p-2">
                        <img class="img-fluid" src="{{ asset('logo\poch-logos.webp') }}" alt="logos poch" width="100%"
                            height="100%" loading="lazy">
                    </div>
                </div>
                <!-- Grid column -->

            </div>
            <!-- Grid row -->
        </div>
    </section>
    <!-- Section: Links  -->
    <div class="container p-4 pb-0 mt-1">
        <span>
            {{ __('Conheça os outros projetos:') }}</span>
        <section class="mb-4 mt-2">
            <a href="https://rede.islagaia.pt/" class="p-2 text-reset" target="__blank" rel="noopner noreferrer" >
                Rede de Cooperação
            </a>
            <a href="https://jobs.islagaia.pt/" class="p-2 text-reset" target="__blank" rel="noopner noreferrer">
                Connecting@Job
            </a>
            <a href="https://alumni.islagaia.pt/" class="text-reset" target="__blank" rel="noopner noreferrer">
                Global Alumni
            </a>
            <a href="https://microcredenciais.islagaia.pt/" class="p-2 text-reset" target="__blank" rel="noopner noreferrer">
                Microcredenciais
            </a>

            <a href="https://tutoria.islagaia.pt" class="p-2 text-reset" target="__blank" rel="noopner noreferrer">
                Tutoria
            </a> 
            
            <a href="https://ambientesativos.islagaia.pt/" class="p-2 text-reset" target="__blank" rel="noopner noreferrer">
                Ambientes Ativos
            </a>

        </section>
    </div>

    </div>
    
    <!-- Copyright -->
    <div class="text-center p-4" style="background-color: rgba(0, 0, 0, 0.05);">
        © @php {{echo date("Y");}} @endphp Copyright:
        <a class="text-reset fw-bold" href="https://www.islagaia.pt/">ISLAGAIA-IPGT</a>
        <a href="{{ route('privacy') }}" class="ps-4">{{ __('Política de Privacidade') }}</a>
        <a href="#" class="ps-4">{{ __('Condições de utilização') }}</a>
    </div>

    <!-- Copyright -->
</footer>