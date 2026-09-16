<x-app-layout>
    <x-slot:title>
        {{ __('Pesquisa Institucional (Ciência Vitae)') }}
    </x-slot>

    <section class="py-8 bg-slate-50/50 min-h-[calc(100vh-160px)]">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 space-y-6">

            {{-- Header --}}
            <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
                <div>
                    <nav class="flex items-center gap-2 text-xs text-slate-500 mb-1">
                        <a href="{{ route('admin') }}" class="hover:text-emerald-700 font-medium">{{ __('Qualidade') }}</a>
                        <span>/</span>
                        <span class="text-slate-800 font-semibold">{{ __('Pesquisa Institucional') }}</span>
                    </nav>
                    <h1 class="text-xl sm:text-2xl font-bold text-slate-900 tracking-tight flex items-center gap-2.5">
                        <span class="w-2.5 h-6 bg-emerald-700 rounded-full inline-block"></span>
                        {{ __('Descoberta Institucional de Investigadores') }}
                    </h1>
                    <p class="text-xs sm:text-sm text-slate-500 mt-1">
                        {{ __('Pesquisa direta na API v1.1 do Ciência Vitae por entidade/instituição para identificar novos investigadores e validar vínculos.') }}
                    </p>
                </div>

                <div class="flex items-center gap-2">
                    <a href="{{ route('admin') }}"
                       class="inline-flex items-center gap-2 px-3.5 py-2 text-xs sm:text-sm font-semibold text-slate-700 bg-white hover:bg-slate-50 border border-slate-200 rounded-xl shadow-2xs transition-colors">
                        <i class="fas fa-arrow-left text-xs text-slate-500"></i>
                        <span>{{ __('Voltar à Qualidade') }}</span>
                    </a>
                </div>
            </div>

            {{-- Search Bar Card --}}
            <div class="bg-white border border-slate-200/90 rounded-2xl p-5 shadow-xs">
                <form method="GET" action="{{ route('admin.institution-search') }}" class="flex flex-col sm:flex-row items-center gap-3">
                    <div class="relative w-full sm:flex-1">
                        <i class="fas fa-building-columns absolute left-3.5 top-1/2 -translate-y-1/2 text-slate-400 text-sm"></i>
                        <input type="text" name="institution" value="{{ $institutionQuery }}" required
                               placeholder="{{ __('Nome da instituição (ex.: ISLA, Instituto Politécnico, etc.)...') }}"
                               class="w-full pl-10 pr-4 py-2.5 rounded-xl border border-slate-200 text-xs sm:text-sm text-slate-800 placeholder-slate-400 focus:outline-none focus:ring-2 focus:ring-emerald-700/20 focus:border-emerald-700 transition">
                    </div>
                    <button type="submit"
                            class="w-full sm:w-auto inline-flex items-center justify-center gap-2 px-6 py-2.5 bg-emerald-700 hover:bg-emerald-800 text-white font-semibold text-xs sm:text-sm rounded-xl shadow-xs transition cursor-pointer border border-emerald-700">
                        <i class="fas fa-search text-xs"></i>
                        <span>{{ __('Pesquisar no CiênciaVitae') }}</span>
                    </button>
                </form>
            </div>

            {{-- Error display if API fails --}}
            @if(!empty($error))
                <div class="p-4 rounded-xl bg-rose-50 border border-rose-200 text-rose-800 text-xs sm:text-sm flex items-center gap-3 shadow-2xs">
                    <i class="fas fa-triangle-exclamation text-rose-600 text-base"></i>
                    <div>
                        <div class="font-bold">{{ __('Erro ao comunicar com a API do Ciência Vitae') }}</div>
                        <div class="text-xs text-rose-700 mt-0.5">{{ $error }}</div>
                    </div>
                </div>
            @endif

            @php
                $rawList = $results['person-summary'] ?? $results['personSummary'] ?? $results['persons'] ?? [];
                if (isset($rawList['name']) || isset($rawList['ciencia-id'])) {
                    $rawList = [$rawList];
                }
                if (!is_array($rawList)) {
                    $rawList = [];
                }
                $totalResults = $results['total'] ?? count($rawList);
            @endphp

            @if($results !== null)
                {{-- Results Card --}}
                <div class="bg-white border border-slate-200/90 rounded-2xl p-6 shadow-sm space-y-4">
                    <div class="flex flex-wrap items-center justify-between gap-3 pb-3 border-b border-slate-100">
                        <div>
                            <h2 class="text-base font-bold text-slate-900 flex items-center gap-2">
                                <i class="fas fa-users text-emerald-700"></i>
                                <span>{{ __('Resultados da Pesquisa') }}</span>
                                <span class="px-2.5 py-0.5 rounded-full text-xs font-bold bg-emerald-100 text-emerald-800">{{ $totalResults }}</span>
                            </h2>
                            <p class="text-xs text-slate-500 mt-0.5">
                                {{ __('Investigadores registados com a instituição:') }} <strong class="text-slate-700">{{ $institutionQuery }}</strong>
                            </p>
                        </div>
                    </div>

                    @if(count($rawList) > 0)
                        <div class="table-responsive rounded-xl border border-slate-200 overflow-hidden shadow-2xs">
                            <table class="table w-100 info-table mb-0">
                                <thead>
                                    <tr>
                                        <th scope="col">{{ __('Investigador') }}</th>
                                        <th scope="col">{{ __('Ciência ID') }}</th>
                                        <th scope="col">{{ __('Vínculo / Instituição') }}</th>
                                        <th scope="col">{{ __('Estado no Portal') }}</th>
                                        <th scope="col" class="text-right">{{ __('Ação') }}</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($rawList as $person)
                                        @php
                                            $pName = $person['name'] ?? $person['fullName'] ?? $person['value'] ?? __('Desconhecido');
                                            $pCienciaId = $person['ciencia-id'] ?? $person['cienciaId'] ?? $person['id'] ?? null;
                                            $normId = strtoupper(trim((string)$pCienciaId));
                                            $isRegistered = in_array($normId, $existingCienciaIds);
                                            $pInst = $person['institution'] ?? $institutionQuery;
                                        @endphp
                                        <tr>
                                            <td>
                                                <div class="font-bold text-slate-900 flex items-center gap-2">
                                                    <i class="fas fa-user-circle text-slate-400"></i>
                                                    <span>{{ $pName }}</span>
                                                </div>
                                            </td>
                                            <td>
                                                @if(!empty($pCienciaId))
                                                    <a href="https://www.cienciavitae.pt/pt/{{ $pCienciaId }}" target="_blank" rel="noopener noreferrer"
                                                       class="font-mono text-xs text-emerald-700 hover:text-emerald-900 hover:underline inline-flex items-center gap-1 font-semibold">
                                                        <span>{{ $pCienciaId }}</span>
                                                        <i class="fas fa-arrow-up-right-from-square text-[9px]"></i>
                                                    </a>
                                                @else
                                                    <span class="text-slate-400 text-xs">-</span>
                                                @endif
                                            </td>
                                            <td>
                                                <span class="text-xs text-slate-600">{{ is_string($pInst) ? $pInst : json_encode($pInst) }}</span>
                                            </td>
                                            <td>
                                                @if($isRegistered)
                                                    <span class="inline-flex items-center gap-1.5 px-2.5 py-0.5 rounded-full text-xs font-semibold bg-emerald-100 text-emerald-800 border border-emerald-200">
                                                        <i class="fas fa-check-circle text-[10px]"></i>
                                                        {{ __('Registado no Portal') }}
                                                    </span>
                                                @else
                                                    <span class="inline-flex items-center gap-1.5 px-2.5 py-0.5 rounded-full text-xs font-semibold bg-blue-50 text-blue-800 border border-blue-200">
                                                        <i class="fas fa-user-plus text-[10px]"></i>
                                                        {{ __('Novo no CiênciaVitae') }}
                                                    </span>
                                                @endif
                                            </td>
                                            <td class="text-right">
                                                @if(!$isRegistered && !empty($pCienciaId))
                                                    <a href="{{ route('user.create') }}?ciencia_id={{ urlencode($pCienciaId) }}&name={{ urlencode($pName) }}"
                                                       class="inline-flex items-center gap-1 px-3 py-1 bg-emerald-700 hover:bg-emerald-800 text-white text-xs font-semibold rounded-lg shadow-2xs transition">
                                                        <i class="fas fa-plus text-[10px]"></i>
                                                        <span>{{ __('Convidar / Criar') }}</span>
                                                    </a>
                                                @else
                                                    <span class="text-xs text-slate-400 italic">{{ __('Integrado') }}</span>
                                                @endif
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @else
                        <div class="p-8 text-center bg-slate-50 rounded-xl border border-dashed border-slate-200">
                            <i class="fas fa-user-slash text-2xl text-slate-300 mb-2 block"></i>
                            <div class="text-slate-700 font-semibold text-sm">{{ __('Nenhum investigador encontrado') }}</div>
                            <div class="text-slate-400 text-xs mt-1">{{ __('Experimente procurar com outra variação do nome institucional.') }}</div>
                        </div>
                    @endif
                </div>
            @endif

        </div>
    </section>
</x-app-layout>
