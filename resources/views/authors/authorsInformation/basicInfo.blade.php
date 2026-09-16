@extends('authors.show')

@section('author-information')

<div class="accordion-item">
    <div class="accordion-header" id="headingOne">
        <h5 class="mb-0">
            <button class="accordion-button" data-mdb-toggle="collapse" data-mdb-target="#collapseOne" aria-expanded="true" aria-controls="collapseOne">
                <div class="flex items-center gap-2.5">
                    <i class="fas fa-align-left text-emerald-700 text-sm"></i>
                    <span class="font-bold text-slate-800">{{ __("Resumo") }}</span>
                </div>
            </button>
        </h5>
    </div>
    <div id="collapseOne" class="collapse show" aria-labelledby="headingOne" data-parent="#accordion">
        <div class="card-body info-body">
            @if (!empty(trim($author->resume ?? '')))
                <div class="info-box">
                    <p class="mb-0 leading-relaxed text-slate-700 text-justify">{{ $author->resume }}</p>
                </div>
            @else
                <div class="p-4 sm:p-5 rounded-xl bg-slate-50/60 border border-dashed border-slate-200 text-center text-xs sm:text-sm text-slate-400 italic">
                    <i class="fas fa-circle-info me-1.5 text-slate-400"></i>{{ __('Sem resumo disponível') }}
                </div>
            @endif
        </div>
    </div>
</div>

<div class="accordion-item">
    <div class="accordion-header" id="headingTwo">
        <h5 class="mb-0">
            <button class="accordion-button collapsed" data-mdb-toggle="collapse" data-mdb-target="#collapseTwo" aria-expanded="false" aria-controls="collapseTwo">
                <div class="flex items-center gap-2.5">
                    <i class="fas fa-quote-left text-emerald-700 text-sm"></i>
                    <span class="font-bold text-slate-800">{{ __("Nomes de citação") }}</span>
                </div>
            </button>
        </h5>
    </div>
    <div id="collapseTwo" class="collapse" aria-labelledby="headingTwo" data-parent="#accordion">
        <div class="card-body info-body">
            @if ($author->citationName && $author->citationName->count() > 0)
                <ul class="info-list rounded-xl border border-slate-100 overflow-hidden shadow-xs">
                    @foreach ($author->citationName as $citation)
                        <li><cite class="not-italic font-medium text-slate-800">{{ $citation->citation_name }}</cite></li>
                    @endforeach
                </ul>
            @else
                <div class="p-4 rounded-xl bg-slate-50/60 border border-dashed border-slate-200 text-center text-xs sm:text-sm text-slate-400 italic">
                    <i class="fas fa-circle-info me-1.5 text-slate-400"></i>{{ __('Sem nomes de citação registados') }}
                </div>
            @endif
        </div>
    </div>
</div>

<div class="accordion-item">
    <div class="accordion-header" id="headingThree">
        <h5 class="mb-0">
            <button class="accordion-button collapsed" data-mdb-toggle="collapse" data-mdb-target="#collapseThree" aria-expanded="false" aria-controls="collapseThree">
                <div class="flex items-center gap-2.5">
                    <i class="fas fa-language text-emerald-700 text-sm"></i>
                    <span class="font-bold text-slate-800">{{ __("Línguas") }}</span>
                </div>
            </button>
        </h5>
    </div>
    <div id="collapseThree" class="collapse" aria-labelledby="headingThree" data-parent="#accordion">
        <div class="card-body info-body">
            @if ($author->languages && $author->languages->count() > 0)
                <div class="table-responsive rounded-xl border border-slate-200/80 overflow-hidden shadow-xs">
                    <table id="tableOne" class="table w-100 info-table mb-0">
                        <thead>
                            <tr>
                                <th scope="col">{{ __("Língua") }}</th>
                                <th scope="col">{{ __("Leitura") }}</th>
                                <th scope="col">{{ __("Escrita") }}</th>
                                <th scope="col">{{ __("Fala") }}</th>
                                <th scope="col">{{ __("Entendimento") }}</th>
                                <th scope="col">{{ __("Nível de revisão por pares") }}</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($author->languages as $language)
                                <tr>
                                    <td class="font-semibold text-slate-800">{{ __($language->{"language"} ?? '-') }}</td>
                                    <td>{{ __($language->pivot->{"read-level"} ?? '-') }}</td>
                                    <td>{{ __($language->pivot->{"writing_level"} ?? '-') }}</td>
                                    <td>{{ __($language->pivot->{"speech_level"} ?? '-') }}</td>
                                    <td>{{ __($language->pivot->{"listening_level"} ?? '-') }}</td>
                                    <td>{{ __($language->pivot->{"peer_review_level"} ?? '-') }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @else
                <div class="p-4 rounded-xl bg-slate-50/60 border border-dashed border-slate-200 text-center text-xs sm:text-sm text-slate-400 italic">
                    <i class="fas fa-circle-info me-1.5 text-slate-400"></i>{{ __('Sem línguas registadas') }}
                </div>
            @endif
        </div>
    </div>
</div>

<div class="accordion-item">
    <div class="accordion-header" id="headingFourContact">
        <h5 class="mb-0">
            <button class="accordion-button collapsed" data-mdb-toggle="collapse" data-mdb-target="#collapseFourContact" aria-expanded="false" aria-controls="collapseFourContact">
                <div class="flex items-center gap-2.5">
                    <i class="fas fa-address-book text-emerald-700 text-sm"></i>
                    <span class="font-bold text-slate-800">{{ __("Contactos") }}</span>
                </div>
            </button>
        </h5>
    </div>
    <div id="collapseFourContact" class="collapse" aria-labelledby="headingFourContact" data-parent="#accordion">
        <div class="card-body info-body">
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <!-- Emails -->
                <div class="p-4 rounded-xl bg-slate-50/70 border border-slate-200/80">
                    <h6 class="text-xs font-bold uppercase tracking-wider text-emerald-800 mb-2.5 flex items-center gap-2">
                        <i class="fas fa-envelope text-emerald-600"></i>
                        <span>{{ __("Endereços de correio eletrónico") }}</span>
                    </h6>
                    @forelse($author->emails as $email)
                        <div class="text-sm text-slate-700 py-1">
                            <a href="mailto:{{ $email->email }}" class="text-emerald-700 hover:text-emerald-900 hover:underline font-medium">{{ $email->email }}</a>
                            @if (!empty($email->use_type))
                                <span class="text-xs text-slate-400 ms-1">({{ __($email->use_type) }})</span>
                            @endif
                        </div>
                    @empty
                        <div class="text-xs text-slate-400 italic">{{ __('Sem endereços de correio eletrónico') }}</div>
                    @endforelse
                </div>

                <!-- Telefones -->
                <div class="p-4 rounded-xl bg-slate-50/70 border border-slate-200/80">
                    <h6 class="text-xs font-bold uppercase tracking-wider text-emerald-800 mb-2.5 flex items-center gap-2">
                        <i class="fas fa-phone text-emerald-600"></i>
                        <span>{{ __("Telefones") }}</span>
                    </h6>
                    @forelse($author->phones as $phone)
                        @php
                            $phoneNumber = $phone->number ?? $phone->phone_number ?? $phone->phone ?? null;
                            $phoneMeta = array_filter([
                                $phone->type ?? null,
                                $phone->use_type ?? null,
                            ], function ($value) {
                                return isset($value) && trim((string) $value) !== '';
                            });
                        @endphp
                        <div class="text-sm text-slate-700 py-1">
                            <span class="font-medium text-slate-800">{{ $phoneNumber ?? 'N/A' }}</span>
                            @if (!empty($phoneMeta))
                                <span class="text-xs text-slate-400 ms-1">({{ __(implode(' / ', $phoneMeta)) }})</span>
                            @endif
                        </div>
                    @empty
                        <div class="text-xs text-slate-400 italic">{{ __('Sem telefones') }}</div>
                    @endforelse
                </div>

                <!-- Moradas -->
                <div class="p-4 rounded-xl bg-slate-50/70 border border-slate-200/80">
                    <h6 class="text-xs font-bold uppercase tracking-wider text-emerald-800 mb-2.5 flex items-center gap-2">
                        <i class="fas fa-location-dot text-emerald-600"></i>
                        <span>{{ __("Moradas") }}</span>
                    </h6>
                    @forelse($author->addresses as $address)
                        @php
                            $addressParts = array_filter([
                                $address->adress ?? $address->address ?? null,
                                $address->postal_code ?? null,
                                $address->city ?? null,
                                $address->state ?? null,
                                $address->country ?? null,
                            ], function ($value) {
                                return isset($value) && trim((string) $value) !== '';
                            });
                            $addressText = implode(', ', $addressParts);
                        @endphp
                        <div class="text-sm text-slate-700 py-1">
                            <span>{{ $addressText !== '' ? $addressText : 'N/A' }}</span>
                            @if (!empty($address->use_type))
                                <span class="text-xs text-slate-400 ms-1">({{ __($address->use_type) }})</span>
                            @endif
                        </div>
                    @empty
                        <div class="text-xs text-slate-400 italic">{{ __('Sem moradas') }}</div>
                    @endforelse
                </div>

                <!-- Websites -->
                <div class="p-4 rounded-xl bg-slate-50/70 border border-slate-200/80">
                    <h6 class="text-xs font-bold uppercase tracking-wider text-emerald-800 mb-2.5 flex items-center gap-2">
                        <i class="fas fa-globe text-emerald-600"></i>
                        <span>{{ __("Websites") }}</span>
                    </h6>
                    @forelse($author->websites as $website)
                        @php
                            $rawUrl = $website->url ?? null;
                            $displayUrl = $rawUrl ?? 'N/A';
                            $hrefUrl = $rawUrl;
                            if (!empty($rawUrl) && !preg_match('/^https?:\/\//i', $rawUrl)) {
                                $hrefUrl = 'https://' . $rawUrl;
                            }
                            $websiteMeta = array_filter([
                                $website->use_type ?? null,
                                $website->type ?? null,
                                $website->label ?? null,
                            ], function ($value) {
                                return isset($value) && trim((string) $value) !== '';
                            });
                        @endphp
                        <div class="text-sm text-slate-700 py-1 truncate">
                            @if (!empty($hrefUrl))
                                <a href="{{ $hrefUrl }}" target="_blank" rel="noopener noreferrer" class="text-emerald-700 hover:text-emerald-900 hover:underline font-medium break-all">{{ $displayUrl }}</a>
                            @else
                                <span>{{ $displayUrl }}</span>
                            @endif
                            @if (!empty($websiteMeta))
                                <span class="text-xs text-slate-400 ms-1">({{ __(implode(' / ', $websiteMeta)) }})</span>
                            @endif
                        </div>
                    @empty
                        <div class="text-xs text-slate-400 italic">{{ __('Sem websites') }}</div>
                    @endforelse
                </div>
            </div>
        </div>
    </div>
</div>

<div class="accordion-item">
    <div class="accordion-header" id="headingFour">
        <h5 class="mb-0">
            <button class="accordion-button collapsed" data-mdb-toggle="collapse" data-mdb-target="#collapseFour" aria-expanded="false" aria-controls="collapseFour">
                <div class="flex items-center gap-2.5">
                    <i class="fas fa-tags text-emerald-700 text-sm"></i>
                    <span class="font-bold text-slate-800">{{ __("Domínios de atividade") }}</span>
                </div>
            </button>
        </h5>
    </div>
    <div id="collapseFour" class="collapse" aria-labelledby="headingFour" data-parent="#accordion">
        <div class="card-body info-body">
            @if ($author->activity && $author->activity->count() > 0)
                <div class="flex flex-wrap gap-2">
                    @foreach ($author->activity as $domainActivity)
                        <span class="inline-flex items-center px-3 py-1.5 rounded-lg text-xs font-semibold bg-emerald-50 text-emerald-800 border border-emerald-100/90 shadow-xs">
                            {{ $domainActivity->{"topic_name"} ?? 'Sem Valor' }}
                        </span>
                    @endforeach
                </div>
            @else
                <div class="p-4 rounded-xl bg-slate-50/60 border border-dashed border-slate-200 text-center text-xs sm:text-sm text-slate-400 italic">
                    <i class="fas fa-circle-info me-1.5 text-slate-400"></i>{{ __('Sem domínios de atividade registados') }}
                </div>
            @endif
        </div>
    </div>
</div>

<div class="accordion-item">
    <div class="accordion-header" id="headingSix">
        <h5 class="mb-0">
            <button class="accordion-button collapsed" data-mdb-toggle="collapse" data-mdb-target="#collapseFive" aria-expanded="false" aria-controls="collapseFive">
                <div class="flex items-center gap-2.5">
                    <i class="fas fa-graduation-cap text-emerald-700 text-sm"></i>
                    <span class="font-bold text-slate-800">{{ __("Formação") }}</span>
                </div>
            </button>
        </h5>
    </div>
    <div id="collapseFive" class="collapse" aria-labelledby="headingSix" data-parent="#accordion">
        <div class="card-body info-body">
            @if (!empty($author->degrees) && $author->degrees->count() > 0)
                <div class="table-responsive rounded-xl border border-slate-200/80 overflow-hidden shadow-xs">
                    <table id="tableThree" class="table w-100 info-table mb-0">
                        <thead>
                            <tr>
                                <th scope="col">{{ __('Ano') }}</th>
                                <th scope="col">{{ __('Estado') }}</th>
                                <th scope="col">{{ __('Grau / Descrição') }}</th>
                                <th scope="col">{{ __('Classificação') }}</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($author->degrees as $degree)
                                <tr>
                                    <td class="whitespace-nowrap">
                                        <x-date-component :model=$degree initialDateAttribute="start_date" finalDateAttribute="end_date"/>
                                    </td>
                                    <td>
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-semibold {{ $degree->degree_status == 'Concluded' ? 'bg-emerald-100 text-emerald-800' : 'bg-blue-100 text-blue-800' }}">
                                            {{ $degree->degree_status ?? 'Em curso' }}
                                        </span>
                                    </td>
                                    <td>
                                        <div class="font-semibold text-slate-800">
                                            {{ $degree->degree_name ?? '' }}
                                            @if(!empty($degree->degree_type))
                                                <span class="text-xs font-normal text-slate-500">({{ $degree->degree_type }})</span>
                                            @endif
                                        </div>
                                        @if(!empty($degree->institution_name))
                                            <div class="text-xs text-slate-500 mt-0.5">{{ $degree->institution_name }}</div>
                                        @endif
                                        @if(!empty($degree->thesis_title))
                                            <div class="text-xs italic text-slate-600 mt-1"><i class="fas fa-book-open me-1 text-slate-400"></i>{{ $degree->thesis_title }}</div>
                                        @endif
                                    </td>
                                    <td>
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-semibold bg-slate-100 text-slate-700">
                                            {{ $degree->classification ?? 'N/A' }}
                                        </span>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="4" class="text-center text-slate-400 italic py-4">
                                        {{ __('Sem formação registada') }}
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            @else
                <div class="p-4 rounded-xl bg-slate-50/60 border border-dashed border-slate-200 text-center text-xs sm:text-sm text-slate-400 italic">
                    <i class="fas fa-circle-info me-1.5 text-slate-400"></i>{{ __('Sem formação registada') }}
                </div>
            @endif
        </div>
    </div>
</div>

<div class="accordion-item">
    <div class="accordion-header" id="headingDistinctions">
        <h5 class="mb-0">
            <button class="accordion-button collapsed" data-mdb-toggle="collapse" data-mdb-target="#collapseDistinctions" aria-expanded="false" aria-controls="collapseDistinctions">
                <div class="flex items-center gap-2.5">
                    <i class="fas fa-trophy text-emerald-700 text-sm"></i>
                    <span class="font-bold text-slate-800">{{ __("Distinções e Prémios") }}</span>
                    @if($author->distinctions && $author->distinctions->count() > 0)
                        <span class="ms-2 px-2 py-0.5 rounded-full text-xs font-bold bg-emerald-100 text-emerald-800">{{ $author->distinctions->count() }}</span>
                    @endif
                </div>
            </button>
        </h5>
    </div>
    <div id="collapseDistinctions" class="collapse" aria-labelledby="headingDistinctions" data-parent="#accordion">
        <div class="card-body info-body">
            @if ($author->distinctions && $author->distinctions->count() > 0)
                <div class="table-responsive rounded-xl border border-slate-200/80 overflow-hidden shadow-xs">
                    <table class="table w-100 info-table mb-0">
                        <thead>
                            <tr>
                                <th scope="col">{{ __('Ano') }}</th>
                                <th scope="col">{{ __('Distinções e Prémios') }}</th>
                                <th scope="col">{{ __('Tipo') }}</th>
                                <th scope="col">{{ __('Entidade Concedente') }}</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($author->distinctions as $distinction)
                                <tr>
                                    <td class="whitespace-nowrap font-medium text-slate-700">
                                        {{ $distinction->effective_year ?? '-' }}
                                    </td>
                                    <td>
                                        <div class="font-semibold text-slate-900">{{ $distinction->distinction_name }}</div>
                                        @if(!empty($distinction->description))
                                            <div class="text-xs text-slate-500 mt-0.5">{{ $distinction->description }}</div>
                                        @endif
                                    </td>
                                    <td>
                                        @if(!empty($distinction->distinction_type))
                                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-semibold bg-emerald-50 text-emerald-800 border border-emerald-200">
                                                {{ $distinction->distinction_type }}
                                            </span>
                                        @else
                                            <span class="text-slate-400">-</span>
                                        @endif
                                    </td>
                                    <td>
                                        <div class="text-xs sm:text-sm text-slate-800">
                                            {{ $distinction->institution_name ?? '-' }}
                                            @if(!empty($distinction->country))
                                                <span class="text-xs text-slate-400 block">{{ $distinction->country }}</span>
                                            @endif
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @else
                <div class="p-4 rounded-xl bg-slate-50/60 border border-dashed border-slate-200 text-center text-xs sm:text-sm text-slate-400 italic">
                    <i class="fas fa-circle-info me-1.5 text-slate-400"></i>{{ __('Sem distinções registadas') }}
                </div>
            @endif
        </div>
    </div>
</div>
@endsection