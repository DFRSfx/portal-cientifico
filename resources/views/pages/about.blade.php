<x-app-layout>

    <x-slot:title>
        {{ __('Sobre Nós') }}
    </x-slot>

    <div class="container" style="margin-top: 50px;">
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

                    <div class="card-body  mb-2">
                        <div class="container">
                            <h5 class="h5" style="color:#33642b">{{ __('Portal científico') }}</h5>
                            <p>
                                {{ __('Este projeto foi financiado pelo Programa Operacional de Capital Humano (POCH), com o objetivo de desenvolver e implementar soluções inovadoras para melhorar a eficiência e a qualidade dos serviços prestados pelas empresas de ensino') }}


                            </p>
                            <img src="{{ asset('logo/poch-logos.webp') }}" alt="Logotipos POCH" width="100%"
                                class="mt-3 mb-5">

                            <p>
                                {{ __('Através deste projeto, estamos comprometidos em promover o desenvolvimento humano e social,bem como estimular a competitividade das empresas do setor X. Com a nossa equipa multidisciplinar e especializada, estamos a trabalhar em estreita colaboração com as empresas para identificar as suas necessidades e criar soluções personalizadas que lhes permitam alcançar os seus objetivos.') }}
                            </p>
                            <p> {{ __('Estamos empenhados em garantir que este projeto seja bem-sucedido e contribua significativamente para o desenvolvimento económico e social da nossa região e do país como um todo.') }}
                            </p>
                            <p>{{ __('Convidamo-lo a conhecer os outros projetos que estamos a desenvolver em colaboração com o Programa Operacional Capital Humano. Visite o nosso website e explore as soluções inovadoras que estamos a criar em vários setores, desde a formação profissional à investigação e desenvolvimento. Junte-se a nós nesta jornada de inovação e progresso!') }}
                            </p>

                            <a href="{{ route('help.index') }}">
                                <button type="button" class="btn btn-rounded gain-bg text-white ripple-surface-dark"
                                    data-mdb-ripple-color="dark" style="background-color: rgb(51, 100, 43);">
                                    {{__('Contato')}}</button></a>
                         
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </div>
</x-app-layout>
