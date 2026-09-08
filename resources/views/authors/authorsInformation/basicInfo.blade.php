@extends('authors.show')

@section('author-information')

<style>
    .info-body {
        padding-left: 12px;
        padding-right: 12px;
    }

    @media (min-width: 1200px) {
        .info-body {
            padding-left: 6px;
            padding-right: 6px;
        }
    }

    .info-table {
        border-collapse: separate;
        border-spacing: 0;
    }

    .info-table thead th {
        background: #e6f1ea;
        color: #1f4d2f;
        font-weight: 700;
        border-bottom: 1px solid #cfe2d6;
        padding: 12px 14px;
    }

    .info-table tbody td {
        padding: 12px 14px;
        border-bottom: 1px solid #dfeae3;
        vertical-align: middle;
    }

    .info-table tbody tr:nth-child(even) {
        background: #f2f8f4;
    }

    .info-table tbody tr:hover {
        background: #e3f2e8;
    }

    .info-list {
        list-style: none;
        padding-left: 0;
        margin: 0;
    }

    .info-list li {
        padding: 8px 12px;
        border-bottom: 1px solid #dfeae3;
    }

    .info-list li:nth-child(even) {
        background: #f2f8f4;
    }

    .info-box {
        background: #ffffff;
        border: 1px solid #dfeae3;
        border-radius: 10px;
        padding: 12px;
    }
</style>

<div class="accordion-item">
    <div class="accordion-header" id="headingOne">
        <h5 class="mb-0">
            <button class="accordion-button " data-mdb-toggle="collapse" data-mdb-target="#collapseOne" aria-expanded="true" aria-controls="collapseOne">
                <b>{{ __("Resumo") }}</b>
            </button>
        </h5>
    </div>
    <div id="collapseOne" class="collapse show" aria-labelledby="headingOne" data-parent="#accordion">
        <div class="card-body info-body" style="hyphens: auto; text-align: justify; ">
            @if ($author->resume)
                <div class="info-box">
                    <p class="mb-0">{{ $author->resume }}</p>
                </div>
            @else
                <div class="info-box">{{ __('Sem Resumo') }}</div>
            @endif
        </div>
    </div>
</div>

<div class="accordion-item">
    <div class="accordion-header" id="headingTwo">
        <h5 class="mb-0">
            <button class="accordion-button collapsed" data-mdb-toggle="collapse" data-mdb-target="#collapseTwo" aria-expanded="false" aria-controls="collapseTwo">
               <b> {{ __("Nomes de citação") }}</b>
            </button>
        </h5>
    </div>
    <div id="collapseTwo" class="collapse " aria-labelledby="headingTwo" data-parent="#accordion">
    <div class="card-body info-body">
        <ul class="info-list">
            @forelse ($author->citationName as $citation)
                <li><cite>{{ $citation->citation_name }}</cite></li>
            @empty
                <li>{{ __('Sem nomes de citação') }}</li>
            @endforelse
        </ul>
    </div>
</div>

</div>
<div class="accordion-item">
    <div class="accordion-header" id="headingThree">
        <h5 class="mb-0">
            <button class="accordion-button collapsed" data-mdb-toggle="collapse" data-mdb-target="#collapseThree" aria-expanded="false" aria-controls="collapseThree">
               <b> {{ __("Línguas") }}</b>
            </button>
        </h5>
    </div>
    <div id="collapseThree" class="collapse " aria-labelledby="headingThree" data-parent="#accordion">
    <div class="card-body info-body">
        @if ($author->languages->count()>0 )
            <div class="table-responsive">
                <table id="tableOne" class="table w-100 info-table">
                    <thead>
                        <tr>
                            <th scope="row">{{ __("Língua") }}</th>
                            <th scope="row">{{__("Leitura")}}</th>
                            <th scope="row">{{__("Escrita")}}</th>
                            <th scope="row">{{__("Fala")}}</th>
                            <th scope="row">{{__("Entendimento")}} </th>
                            <th scope="row">{{__("Nível de revisão por pares")}}</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($author->languages as $language)
                            <tr>
                                <td>{{ __($language->{"language"} ?? '-') }}</td>
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
<span>N/A</span>        @endif
    </div>
</div>

</div>


<div class="accordion-item">
    <div class="accordion-header" id="headingFourContact">
        <h5 class="mb-0">
            <button class="accordion-button collapsed" data-mdb-toggle="collapse" data-mdb-target="#collapseFourContact" aria-expanded="false" aria-controls="collapseFourContact">
                <b>{{ __("Contactos") }}</b>
            </button>
        </h5>
    </div>
    <div id="collapseFourContact" class="collapse" aria-labelledby="headingFourContact" data-parent="#accordion">
        <div class="card-body info-body">
            <div class="info-box">
                <h6 class="mb-2" style="color:#2A6B20">{{ __("Enderecos de correio eletrónico") }}</h6>
                @forelse($author->emails as $email)
                    <div>
                        {{ $email->email }}
                        @if (!empty($email->use_type))
                            <span>({{ __($email->use_type) }})</span>
                        @endif
                    </div>
                @empty
                    <div>N/A</div>
                @endforelse

                <h6 class="mt-3 mb-2" style="color:#2A6B20">{{ __("Telefones") }}</h6>
                @forelse($author->phones as $phone)
                    @php
                        $phoneNumber = $phone->number ?? $phone->phone_number ?? $phone->phone ?? null;
                    @endphp
                    <div>
                        {{ $phoneNumber ?? 'N/A' }}
                        @php
                            $phoneMeta = array_filter([
                                $phone->type ?? null,
                                $phone->use_type ?? null,
                            ], function ($value) {
                                return isset($value) && trim((string) $value) !== '';
                            });
                        @endphp
                        @if (!empty($phoneMeta))
                            <span>({{ __(implode(' / ', $phoneMeta)) }})</span>
                        @endif
                    </div>
                @empty
                    <div>N/A</div>
                @endforelse

                <h6 class="mt-3 mb-2" style="color:#2A6B20">{{ __("Moradas") }}</h6>
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
                    <div>
                        {{ $addressText !== '' ? $addressText : 'N/A' }}
                        @if (!empty($address->use_type))
                            <span>({{ __($address->use_type) }})</span>
                        @endif
                    </div>
                @empty
                    <div>N/A</div>
                @endforelse

                <h6 class="mt-3 mb-2" style="color:#2A6B20">{{ __("Websites") }}</h6>
                @forelse($author->websites as $website)
                    @php
                        $rawUrl = $website->url ?? null;
                        $displayUrl = $rawUrl ?? 'N/A';
                        $hrefUrl = $rawUrl;
                        if (!empty($rawUrl) && !preg_match('/^https?:\/\//i', $rawUrl)) {
                            $hrefUrl = 'https://' . $rawUrl;
                        }
                    @endphp
                    <div>
                        @if (!empty($hrefUrl))
                            <a href="{{ $hrefUrl }}" target="_blank" rel="noopener noreferrer">{{ $displayUrl }}</a>
                        @else
                            {{ $displayUrl }}
                        @endif
                        @php
                            $websiteMeta = array_filter([
                                $website->use_type ?? null,
                                $website->type ?? null,
                                $website->label ?? null,
                            ], function ($value) {
                                return isset($value) && trim((string) $value) !== '';
                            });
                        @endphp
                        @if (!empty($websiteMeta))
                            <span>({{ __(implode(' / ', $websiteMeta)) }})</span>
                        @endif
                    </div>
                @empty
                    <div>N/A</div>
                @endforelse
            </div>
        </div>
    </div>
</div>

<div class="accordion-item">
    <div class="accordion-header" id="headingFour">
        <h5 class="mb-0">
            <button class="accordion-button collapsed" data-mdb-toggle="collapse" data-mdb-target="#collapseFour" aria-expanded="false" aria-controls="collapseFour">
               <b> {{ __("Dominios de atividade") }}</b>
            </button>
        </h5>
    </div>
    <div id="collapseFour" class="collapse" aria-labelledby="headingFour" data-parent="#accordion">
        <div class="card-body info-body">
            <ul class="info-list">
                @forelse ($author->activity as $domainActivity)
                    <li>{{ $domainActivity->{"topic_name"} ?? 'Sem Valor' }}</li>
                @empty
                    <li>-</li>
                @endforelse
            </ul>
        </div>
    </div>
</div>

<div class="accordion-item">
    <div class="accordion-header" id="headingSix">
        <h5 class="mb-0">
            <button class="accordion-button collapsed" data-mdb-toggle="collapse" data-mdb-target="#collapseFive" aria-expanded="false" aria-controls="collapseFive">
                <b>{{ __("Formação") }}</b>
            </button>
        </h5>
    </div>
    <div id="collapseFive" class="collapse" aria-labelledby="headingSix" data-parent="#accordion">
        <div class="card-body info-body">
            @if (!empty($author->degrees->count() > 0) )
            <div class="table-responsive">
            <table id="tableThree" class="table w-100 info-table">
                    <thead>
                        <tr>
                            <th scope="row">{{ __('Year') }}</th>
                            <th scope="row">{{ __('Status') }}</th>
                            <th scope="row">{{ __('Type') }}</th>
                            <th scope="row">{{ __('Classification') }}</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($author->degrees as $degree)
                        <tr>
                            <td>
                                <x-date-component :model=$degree initialDateAttribute="start_date" finalDateAttribute="end_date"/>
                            </td>
                            <td> <span class="badge rounded-pill d-inline  @if ($degree->degree_status == 'Concluded') badge-success @else badge-primary @endif">{{ $degree->degree_status ?? 'Em curso' }}</span>
                            </td>
                            <td>

                                {{ $degree->degree_name ?? '' }}

                                {{ $degree->degree_type != null ? '(' . $degree->degree_type . ')' : '' }}

                                <p> {{ ($degree->institution_name ?? '') . ' ' . isset($degree->thesis_title) ??  '' }}
                                </p>


                                <p> {{ $degree->thesis_title ?? '' }} </p>

                            </td>
                            <td> <span class="badge rounded-pill d-inline badge-primary">{{ $degree->classification ?? 'N/A' }}</span>
                            </td>
                            @empty
                        <tr>
                            <td><span>N/A</span>
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            @else
            <span>N/A</span>
            @endif
        </div>
    </div>
</div>
@endsection