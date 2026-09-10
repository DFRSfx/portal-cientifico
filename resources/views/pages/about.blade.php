<x-app-layout>
    <x-slot:title>
        {{ __('Sobre Nós') }}
    </x-slot>

    {{-- Layout Sobre Nós migrado 100% para Tailwind CSS v4 (sem bloco <style> local) --}}
    <div class="min-h-[calc(100vh-120px)] flex flex-col items-center justify-center py-10 sm:py-14 px-4 sm:px-6 lg:px-8 text-slate-900 max-w-6xl mx-auto">
        <!-- Header -->
        <div class="text-center max-w-3xl mb-10 sm:mb-12">
            <h1 class="text-3xl sm:text-4xl lg:text-5xl font-extrabold text-emerald-800 tracking-tight mb-4">
                {{ __('Portal científico') }}
            </h1>
            <p class="text-sm sm:text-base lg:text-lg text-slate-600 leading-relaxed max-w-2xl mx-auto">
                {{ __('Este projeto foi financiado pelo Programa Operacional de Capital Humano (POCH), com o objetivo de desenvolver e implementar soluções inovadoras para melhorar a eficiência e a qualidade dos serviços prestados pelas empresas de ensino') }}
            </p>
        </div>

        <!-- Showcase: image on left, paragraphs on right -->
        <div class="w-full flex flex-col lg:flex-row items-center justify-center gap-10 lg:gap-14 mb-12 sm:mb-14">
            <div class="w-full lg:w-5/12 max-w-md">
                <div class="rounded-2xl border border-slate-200/80 shadow-lg hover:shadow-xl overflow-hidden bg-white hover:-translate-y-1 transition-all duration-300">
                    <img src="{{ asset('logo/image-portal.webp') }}" alt="Logo POCH" class="w-full h-auto object-cover block">
                </div>
            </div>

            <div class="w-full lg:w-7/12 max-w-xl text-left">
                <div class="space-y-5 mb-8">
                    <div class="flex items-start gap-4">
                        <div class="w-10 h-10 shrink-0 flex items-center justify-center bg-emerald-50 border border-emerald-200 rounded-xl text-emerald-700 shadow-xs mt-0.5">
                            <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                <path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"></path>
                                <circle cx="9" cy="7" r="4"></circle>
                                <path d="M23 21v-2a4 4 0 0 0-3-3.87"></path>
                                <path d="M16 3.13a4 4 0 0 1 0 7.75"></path>
                            </svg>
                        </div>
                        <p class="text-sm sm:text-base text-slate-600 leading-relaxed m-0">
                            {{ __('Através deste projeto, estamos comprometidos em promover o desenvolvimento humano e social,bem como estimular a competitividade das empresas do setor X. Com a nossa equipa multidisciplinar e especializada, estamos a trabalhar em estreita colaboração com as empresas para identificar as suas necessidades e criar soluções personalizadas que lhes permitam alcançar os seus objetivos.') }}
                        </p>
                    </div>

                    <div class="flex items-start gap-4">
                        <div class="w-10 h-10 shrink-0 flex items-center justify-center bg-emerald-50 border border-emerald-200 rounded-xl text-emerald-700 shadow-xs mt-0.5">
                            <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                <circle cx="12" cy="12" r="10"></circle>
                                <line x1="2" y1="12" x2="22" y2="12"></line>
                                <path d="M12 2a15.3 15.3 0 0 1 4 10 15.3 15.3 0 0 1-4 10 15.3 15.3 0 0 1-4-10 15.3 15.3 0 0 1 4-10z"></path>
                            </svg>
                        </div>
                        <p class="text-sm sm:text-base text-slate-600 leading-relaxed m-0">
                            {{ __('Estamos empenhados em garantir que este projeto seja bem-sucedido e contribua significativamente para o desenvolvimento económico e social da nossa região e do país como um todo.') }}
                        </p>
                    </div>

                    <div class="flex items-start gap-4">
                        <div class="w-10 h-10 shrink-0 flex items-center justify-center bg-emerald-50 border border-emerald-200 rounded-xl text-emerald-700 shadow-xs mt-0.5">
                            <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                <polygon points="12 2 15.09 8.26 22 9.27 17 14.14 18.18 21.02 12 17.77 5.82 21.02 7 14.14 2 9.27 8.91 8.26 12 2"></polygon>
                            </svg>
                        </div>
                        <p class="text-sm sm:text-base text-slate-600 leading-relaxed m-0">
                            {{ __('Convidamo-lo a conhecer os outros projetos que estamos a desenvolver em colaboração com o Programa Operacional Capital Humano. Visite o nosso website e explore as soluções inovadoras que estamos a criar em vários setores, desde a formação profissional à investigação e desenvolvimento. Junte-se a nós nesta jornada de inovação e progresso!') }}
                        </p>
                    </div>
                </div>

                <!-- Contact Button with exact original label -->
                <a href="{{ route('help.index') }}" class="inline-flex items-center justify-center gap-2 px-6 py-3 bg-gradient-to-r from-emerald-800 to-emerald-700 hover:from-emerald-700 hover:to-emerald-600 text-white font-bold text-sm tracking-wide rounded-xl shadow-md hover:shadow-lg hover:-translate-y-0.5 transition-all">
                    <span>{{ __('Contato') }}</span>
                    <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
                        <line x1="5" y1="12" x2="19" y2="12"></line>
                        <polyline points="12 5 19 12 12 19"></polyline>
                    </svg>
                </a>
            </div>
        </div>

        <!-- POCH Logos Card -->
        <div class="w-full max-w-4xl bg-white border border-slate-200/80 rounded-2xl p-6 sm:p-8 shadow-sm text-center mb-6">
            <div class="max-w-2xl mx-auto">
                <img src="{{ asset('logo/poch-logos.webp') }}" alt="Logotipos POCH" class="w-full h-auto block">
            </div>
        </div>
    </div>
</x-app-layout>
