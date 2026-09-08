<!--Start header-->
<header>
    <!-- Start Navbar -->
    <nav class="navbar navbar-expand-lg navbar-light bg-white">
        <div class="container ">
            <a class="navbar-brand img-fluid  " href="/"><img src="{{ asset('logo\logo-portal-cientifico.svg') }}"
                    alt="Logotipo Global Alumni Network" width="160px" height="auto"></a>
            <!-- Toggle button -->
            <button class="navbar-toggler  ms-auto " type="button" data-mdb-toggle="collapse"
                data-mdb-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false"
                aria-label="Toggle navigation">
                <i class="fas fa-bars"></i>
            </button>

            <!-- Collapsible wrapper -->
            <div class="collapse navbar-collapse col-lg-10 " id="navbarSupportedContent">
                <!-- Menu links -->
                <ul class="navbar-nav ms-auto mb-2 mb-lg-0 align-items-center ">

                    <li class="nav-item px-2">
                        <a class="nav-link active  link-post " aria-current="pagestatistics"
                            href="{{ route('about.index') }}" class="ps-4">{{ __('Sobre') }}</a>
                    </li>

                    @if (Auth::check())
                        <li class="nav-item dropdown px-2">
                            <a class="nav-link active  link-post " aria-current="pagestatistics"
                                href="{{ route('dashboard') }}">{{ __('Painel') }}</a>
                        </li>

                    @if (auth()->user()->type == 'administrative')
                            <li class="nav-item dropdown px-2">
                                <a class="nav-link dropdown-toggle" style="color: black;" href="#"
                                    id="navbarDropdownMenuLink" role="button" data-mdb-toggle="dropdown"
                                    aria-expanded="false">
                                    {{ __('Utilizadores') }}
                                    @if (($pendingApprovalCount ?? 0) > 0 || ($pendingVerificationCount ?? 0) > 0)
                                        <span class="badge rounded-pill bg-danger ms-1">
                                            {{ ($pendingApprovalCount ?? 0) + ($pendingVerificationCount ?? 0) }}
                                        </span>
                                    @endif
                                </a>
                                <ul class="dropdown-menu" aria-labelledby="navbarDropdownMenuInvestigação">
                                    <li>
                                        <a class="dropdown-item " aria-current="pageAuthors"
                                            href="{{ route('user.create') }}">{{ __('Criar') }}</a>
                                    </li>
                                    <li>
                                        <a class="dropdown-item " aria-current="pageAuthors"
                                            href="{{ route('user.active') }}">{{ __('Utilizadores Ativos') }}
                                            @if (($activeUserCount ?? 0) > 0)
                                                <span class="badge rounded-pill bg-success text-white ms-1">{{ $activeUserCount }}</span>
                                            @endif
                                        </a>
                                    </li>
                                    <li>
                                        <a class="dropdown-item " aria-current="pageAuthors"
                                            href="{{ route('user.inactive') }}">{{ __('Utilizadores Inativos') }}
                                            @if (($pendingApprovalCount ?? 0) > 0)
                                                <span class="badge rounded-pill bg-warning text-dark ms-1">{{ $pendingApprovalCount }}</span>
                                            @endif
                                        </a>
                                </ul>
                            </li>
                        @else
                            <li class="nav-item dropdown px-2">
                                <a class="nav-link dropdown-toggle" style="color: black;" href="#"
                                    id="navbarDropdownMenuLink" role="button" data-mdb-toggle="dropdown"
                                    aria-expanded="false">
                                    {{ __('Reporte') }}
                                </a>
                                <ul class="dropdown-menu" aria-labelledby="navbarDropdownMenuInvestigação">
                                    @php
                                        $reportEntities = auth()->user()->entities ?? [];
                                        if (empty($reportEntities) && !empty(auth()->user()->entidade)) {
                                            $reportEntities = [auth()->user()->entidade];
                                        }
                                        $reportEntities = array_values(array_unique(array_filter($reportEntities)));
                                    @endphp

                                    @foreach ($reportEntities as $entity)
                                        <li>
                                            <a class="dropdown-item report-link" aria-current="pageAuthors" href="#!"
                                                data-entity="{{ $entity }}"
                                                data-report-action="{{ route('ceos-excel') }}">
                                                {{ $entity }}
                                            </a>
                                        </li>
                                    @endforeach

                                    <!--<li>
                                        <a class="dropdown-item" aria-current="pageAuthors"
                                            href="{{ route('ceos-excel-all') }}">{{ __('All Authors') }}</a>
                                    </li>-->
                                </ul>
                            </li>
                            @endif
                             @if (Auth::user()->is_isla == 1)
                                <li class="nav-item dropdown px-2">
                                    <a class="nav-link dropdown-toggle" style="color: black;" href="#"
                                        id="navbarDropdownMenuLink" role="button" data-mdb-toggle="dropdown"
                                        aria-expanded="false">
                                        {{ __('Investigação') }}
                                    </a>
                                    <ul class="dropdown-menu" aria-labelledby="navbarDropdownMenuInvestigação">
                                        <li>
                                            <a class="dropdown-item " aria-current="pageAuthors"
                                                href="{{ route('authors.index') }}">{{ __('Autores') }}</a>
                                        </li>
                                        <li>
                                            <a class="dropdown-item"
                                                href="{{ route('outputs.index') }}">{{ __('Publicações') }}</a>
                                        </li>
                                    </ul>
                                </li>
                            @endif
                        @endif

                        {{-- Displays the flags --}}
                        <x-flags :flags="[
                            'en' => [
                                'lang' => 'Inglês',
                                // 'flag' => 'flag flag-united-kingdom',
                            ],
                            'pt' => [
                                'lang' => 'Português',
                                // 'flag' => 'flag flag-portugal',
                            ],
                        ]" />

                        <li class="nav-item dropdown px-2">
                            <a class="nav-link dropdown-toggle " href="/" id="navbarDropdownMenuLink"
                                role="button" data-mdb-toggle="dropdown" aria-expanded="false">


                                <x-user-image
                                    showLogedUserImage="{{ Auth::check() && auth()->user()->type != 'administrative' }}" />

                            </a>

                            <ul class="dropdown-menu" aria-labelledby="navbarDropdownMenuUserLogin">

                                @if (!Auth::check())
                                    <li>
                                        <a class="dropdown-item"
                                            href="{{ route('login') }}">{{ __('Iniciar Sessão') }}</a>
                                    </li>
                                @else
                                    <li>
                                        <a class="dropdown-item"
                                            href="  {{ route('profile.edit') }}">{{ __('Perfil') }}</a>
                                    </li>

                                    <li>
                                        <form method="POST" action="{{ route('logout') }}">
                                            @csrf
                                            <button type="submit" class="dropdown-item"> {{ __('Logout') }}</button>
                                        </form>
                                    </li>
                                @endif

                            </ul>
                        </li>

                </ul>
                <!-- Menu links -->
            </div>
            <!-- Collapsible wrapper -->
        </div>
        <!-- Container wrapper -->
    </nav>
    <!-- End Navbar -->


</header>
<!-- End header-->
