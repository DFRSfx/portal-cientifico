@extends('authors.show')

@section('author-information')

<div class="accordion-item">
    <div class="accordion-header" id="headingOne">
        <h5 class="mb-0">
            <button class="accordion-button " data-mdb-toggle="collapse" data-mdb-target="#collapseOne" aria-expanded="true" aria-controls="collapseOne">
                <b>{{ __("Resumo") }}</b>
            </button>
        </h5>
    </div>
    <div id="collapseOne" class="collapse show" aria-labelledby="headingOne" data-parent="#accordion">
        <div class="card-body" style="hyphens: auto; text-align: justify; ">
            @if ($author->resume)
                <p class="mb-0">{{ $author->resume }}</p>
            @else
                <span>{{ __('Sem Resumo') }}</span>
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
    <div class="card-body">
        @forelse ($author->citationName as $citation)
                <p class="mb-0"><cite>{{ $citation->citation_name }}</cite></p>
        @empty
-        @endforelse
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
    <div class="card-body">
        @if ($author->languages->count()>0 )
            <div class="table-responsive">
                <table id="tableOne" class="table">
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
    <div class="accordion-header" id="headingFour">
        <h5 class="mb-0">
            <button class="accordion-button collapsed" data-mdb-toggle="collapse" data-mdb-target="#collapseFour" aria-expanded="false" aria-controls="collapseFour">
               <b> {{ __("Dominios de atividade") }}</b>
            </button>
        </h5>
    </div>
    <div id="collapseFour" class="collapse" aria-labelledby="headingFour" data-parent="#accordion">
        <div class="card-body">

            @forelse ($author->activity as $domainActivity)
            <!--<td> {{ $dominio->{"research-classification"}->{"value"} ?? 'Sem Valor' }} </td> -->
            <p class="mb-0">{{ $domainActivity->{"topic_name"} ?? 'Sem Valor' }}</p>

            @empty
            <span>-</span>

            @endforelse


        </div>
    </div>
</div>

<!--<div class="accordion-item">
    <div class="accordion-header" id="headingFive">
        <h5 class="mb-0">
            <button class="accordion-button collapsed" data-mdb-toggle="collapse" data-mdb-target="#collapseFiveContact" aria-expanded="false" aria-controls="collapseFiveContact">
                <b>{{ __("Contacto") }}</b>
            </button>
        </h5>
    </div>
    <div id="collapseFiveContact" class="collapse" aria-labelledby="headingFive" data-parent="#accordion">
        <div class="card-body">
            <h6>{{ __("Endereços de correio eletrónico") }}</h6>
            @forelse($author->emails as $email)
                <div>{{ $email->email }} <span>({{ __($email->type) }})</span></div>
            @empty
                <div>N/A</div>
            @endforelse

            <h6 class="mt-3">{{ __("Telefones") }}</h6>
            @forelse($author->phones as $phone)
                <div>{{ $phone->number }} <span>({{ __($phone->type) }})</span></div>
            @empty
                <div>N/A</div>
            @endforelse

            <h6 class="mt-3">{{ __("Moradas") }}</h6>
            @forelse($author->addresses as $address)
                <div>{{ $address->full_address ?? 'N/A' }}</div>
            @empty
                <div>N/A</div>
            @endforelse
        </div>
    </div>
</div>-->

<div class="accordion-item">
    <div class="accordion-header" id="headingSix">
        <h5 class="mb-0">
            <button class="accordion-button collapsed" data-mdb-toggle="collapse" data-mdb-target="#collapseFive" aria-expanded="false" aria-controls="collapseFive">
                <b>{{ __("Formação") }}</b>
            </button>
        </h5>
    </div>
    <div id="collapseFive" class="collapse" aria-labelledby="headingSix" data-parent="#accordion">
        <div class="card-body">
            @if (!empty($author->degrees->count() > 0) )
            <div class="table-responsive">
                <table id="tableThree" class="table">
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