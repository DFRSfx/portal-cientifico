<footer class="bg-white border-t border-slate-200/80 text-slate-600 mt-12 transition-colors">
    <!-- Section: Links & About -->
    <section class="py-10">
        <div class="container">
            <div class="row g-4 align-items-center">
                <!-- Portal description -->
                <div class="col-lg-7 col-md-6 d-flex flex-column">
                    <div class="mb-4">
                        <img class="img-fluid" src="{{ asset('logo/logo-portal-cientifico.svg') }}" width="200px"
                            height="auto" loading="lazy" alt="logo portal científico">
                    </div>
                    <div>
                        <p class="text-sm text-slate-600 leading-relaxed max-w-2xl font-normal m-0">
                            {{ __('O Portal Científico é uma plataforma baseada na informação do CiênciaVitae que promove a integração e divulgação da produção científica de docentes e discentes de uma Instituição de Ensino Superior (IES). Esta plataforma tem como objetivo facilitar o acesso e a partilha de pesquisas, artigos, projetos e demais contribuições científicas, criando um ambiente colaborativo e enriquecedor para o avanço do conhecimento.') }}
                        </p>
                    </div>
                </div>

                <!-- POCH Logos -->
                <div class="col-lg-5 col-md-6">
                    <div class="p-4 bg-slate-50/80 border border-slate-200/70 rounded-2xl flex items-center justify-center shadow-xs">
                        <img class="img-fluid max-h-24 w-auto object-contain" src="{{ asset('logo/poch-logos.webp') }}" alt="logos poch" loading="lazy">
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Section: Partner Projects -->
    <section class="border-t border-slate-100 py-6 bg-slate-50/40">
        <div class="container">
            <p class="text-xs font-bold uppercase tracking-wider text-slate-500 mb-3">
                {{ __('Conheça os outros projetos:') }}
            </p>
            <div class="flex flex-wrap items-center gap-2">
                <a href="https://rede.islagaia.pt/" class="inline-flex items-center px-3 py-1.5 rounded-lg text-xs font-medium text-slate-700 bg-white hover:bg-emerald-50 hover:text-emerald-800 hover:border-emerald-200 border border-slate-200/80 shadow-2xs transition-all" target="_blank" rel="noopener noreferrer">
                    Rede de Cooperação
                </a>
                <a href="https://jobs.islagaia.pt/" class="inline-flex items-center px-3 py-1.5 rounded-lg text-xs font-medium text-slate-700 bg-white hover:bg-emerald-50 hover:text-emerald-800 hover:border-emerald-200 border border-slate-200/80 shadow-2xs transition-all" target="_blank" rel="noopener noreferrer">
                    Connecting@Job
                </a>
                <a href="https://alumni.islagaia.pt/" class="inline-flex items-center px-3 py-1.5 rounded-lg text-xs font-medium text-slate-700 bg-white hover:bg-emerald-50 hover:text-emerald-800 hover:border-emerald-200 border border-slate-200/80 shadow-2xs transition-all" target="_blank" rel="noopener noreferrer">
                    Global Alumni
                </a>
                <a href="https://microcredenciais.islagaia.pt/" class="inline-flex items-center px-3 py-1.5 rounded-lg text-xs font-medium text-slate-700 bg-white hover:bg-emerald-50 hover:text-emerald-800 hover:border-emerald-200 border border-slate-200/80 shadow-2xs transition-all" target="_blank" rel="noopener noreferrer">
                    Microcredenciais
                </a>
                <a href="https://tutoria.islagaia.pt" class="inline-flex items-center px-3 py-1.5 rounded-lg text-xs font-medium text-slate-700 bg-white hover:bg-emerald-50 hover:text-emerald-800 hover:border-emerald-200 border border-slate-200/80 shadow-2xs transition-all" target="_blank" rel="noopener noreferrer">
                    Tutoria
                </a> 
                <a href="https://ambientesativos.islagaia.pt/" class="inline-flex items-center px-3 py-1.5 rounded-lg text-xs font-medium text-slate-700 bg-white hover:bg-emerald-50 hover:text-emerald-800 hover:border-emerald-200 border border-slate-200/80 shadow-2xs transition-all" target="_blank" rel="noopener noreferrer">
                    Ambientes Ativos
                </a>
            </div>
        </div>
    </section>

    <!-- Copyright -->
    <div class="border-t border-slate-200/80 bg-slate-100/60 py-4 px-4">
        <div class="container flex flex-col sm:flex-row items-center justify-between gap-3 text-xs text-slate-500">
            <div>
                © {{ date("Y") }} {{ __('Copyright') }}:
                <a class="font-semibold text-slate-700 hover:text-emerald-800 transition-colors" href="https://www.islagaia.pt/" target="_blank" rel="noopener noreferrer">ISLAGAIA-IPGT</a>
            </div>
            <div class="flex items-center gap-4">
                <a href="{{ route('privacy') }}" class="hover:text-emerald-800 transition-colors">{{ __('Política de Privacidade') }}</a>
                <span class="text-slate-300">•</span>
                <a href="#" class="hover:text-emerald-800 transition-colors">{{ __('Condições de utilização') }}</a>
            </div>
        </div>
    </div>
</footer>