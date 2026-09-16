<x-app-layout>
    <x-slot:title>
        {{ __('Novo Utilizador / Importar Autores') }}
    </x-slot>

    <section class="py-8 bg-slate-50/50 min-h-[calc(100vh-160px)]">
        <div class="max-w-5xl mx-auto px-4 sm:px-6">

            {{-- Back Link & Header --}}
            <div class="mb-6 flex flex-col sm:flex-row sm:items-center justify-between gap-4">
                <div>
                    <a href="{{ route('user.active') }}"
                        class="inline-flex items-center gap-2 text-xs font-semibold text-slate-600 hover:text-emerald-800 transition-colors mb-2">
                        <i class="fas fa-arrow-left text-[11px]"></i>
                        <span>{{ __('Voltar à Gestão de Utilizadores') }}</span>
                    </a>
                    <h1 class="text-xl sm:text-2xl font-bold text-slate-900 tracking-tight flex items-center gap-2.5">
                        <span class="w-2.5 h-6 bg-emerald-700 rounded-full inline-block"></span>
                        {{ __('Adicionar Utilizadores') }}
                    </h1>
                    <p class="text-xs sm:text-sm text-slate-500 mt-1">
                        {{ __('Crie utilizadores individuais manualmente ou importe múltiplos autores via folha de cálculo.') }}
                    </p>
                </div>
            </div>

            {{-- Feedback Alerts --}}
            @if (session('success'))
                <div class="mb-6 p-4 rounded-xl bg-emerald-50 border border-emerald-200 text-emerald-900 text-xs sm:text-sm font-medium flex items-center gap-3 shadow-2xs">
                    <i class="fas fa-check-circle text-emerald-600 text-base"></i>
                    <span>{{ session('success') }}</span>
                </div>
            @endif

            @if (isset($errors) && $errors->any())
                <div class="mb-6 p-4 rounded-xl bg-rose-50 border border-rose-200 text-rose-900 text-xs sm:text-sm font-medium flex items-center gap-3 shadow-2xs">
                    <i class="fas fa-triangle-exclamation text-rose-600 text-base"></i>
                    <span>{{ $errors->first() }}</span>
                </div>
            @endif

            <div class="grid grid-cols-1 lg:grid-cols-12 gap-6 items-start">
                
                {{-- Import Column (5 cols) --}}
                <div class="lg:col-span-5 space-y-4">
                    <div class="bg-white border border-slate-200/80 rounded-2xl shadow-xs overflow-hidden">
                        <div class="p-5 border-b border-slate-100 bg-slate-50/40">
                            <h2 class="text-sm font-bold text-slate-900 flex items-center gap-2">
                                <i class="fas fa-file-import text-emerald-700"></i>
                                {{ __('Importação em Massa') }}
                            </h2>
                            <p class="text-xs text-slate-500 mt-1">
                                {{ __('Carregue um ficheiro Excel ou CSV para registar múltiplos autores de uma só vez.') }}
                            </p>
                        </div>
                        <div class="p-5">
                            <form action="{{ route('import') }}" method="POST" enctype="multipart/form-data" class="space-y-4">
                                @csrf
                                <div>
                                    <label for="file" class="block text-xs font-bold uppercase tracking-wider text-slate-700 mb-2">
                                        {{ __('Ficheiro (.xlsx, .xls, .csv)') }}
                                    </label>
                                    <input type="file" name="file" id="file" accept=".xlsx,.xls,.csv" required
                                        class="block w-full text-xs text-slate-600 file:mr-3 file:py-2 file:px-3.5 file:rounded-xl file:border-0 file:text-xs file:font-semibold file:bg-emerald-50 file:text-emerald-800 hover:file:bg-emerald-100 file:cursor-pointer border border-slate-200 rounded-xl bg-slate-50/50 p-1.5 focus:outline-none focus:ring-2 focus:ring-emerald-500 cursor-pointer transition-all">
                                </div>
                                <button type="submit"
                                    class="w-full inline-flex items-center justify-center gap-2 px-4 py-2.5 text-xs sm:text-sm font-semibold text-white bg-emerald-700 hover:bg-emerald-800 rounded-xl shadow-xs transition-all cursor-pointer">
                                    <i class="fas fa-file-excel text-xs"></i>
                                    <span>{{ __('Importar Ficheiro') }}</span>
                                </button>
                            </form>
                        </div>
                    </div>

                    {{-- Format instructions box --}}
                    <div class="p-4 rounded-2xl bg-emerald-50/60 border border-emerald-100 text-xs text-slate-600">
                        <p class="font-bold text-emerald-950 mb-1.5 flex items-center gap-1.5">
                            <i class="fas fa-circle-info text-emerald-700"></i>
                            {{ __('Estrutura esperada do ficheiro:') }}
                        </p>
                        <p class="text-slate-600 mb-2">O cabeçalho deve conter exatamente as seguintes colunas:</p>
                        <div class="flex flex-wrap gap-1.5 font-mono text-[11px]">
                            <span class="px-2 py-0.5 bg-white border border-emerald-200 rounded text-emerald-900 font-semibold">nome_completo</span>
                            <span class="px-2 py-0.5 bg-white border border-emerald-200 rounded text-emerald-900 font-semibold">email</span>
                            <span class="px-2 py-0.5 bg-white border border-emerald-200 rounded text-emerald-900 font-semibold">id_ciencia_vitae</span>
                        </div>
                    </div>
                </div>

                {{-- Create Single User Column (7 cols) --}}
                <div class="lg:col-span-7">
                    <div class="bg-white border border-slate-200/80 rounded-2xl shadow-xs overflow-hidden">
                        <div class="p-5 border-b border-slate-100 bg-slate-50/40">
                            <h2 class="text-sm font-bold text-slate-900 flex items-center gap-2">
                                <i class="fas fa-user-plus text-emerald-700"></i>
                                {{ __('Registo Individual') }}
                            </h2>
                            <p class="text-xs text-slate-500 mt-1">
                                {{ __('Introduza os dados para criar uma nova conta de utilizador no sistema.') }}
                            </p>
                        </div>
                        <div class="p-5 sm:p-6">
                            <form method="POST" action="{{ route('user.store') }}" class="space-y-4">
                                @csrf

                                {{-- Name --}}
                                <div>
                                    <label for="name" class="block text-xs font-bold uppercase tracking-wider text-slate-700 mb-1.5">
                                        {{ __('Nome') }} <span class="text-rose-500">*</span>
                                    </label>
                                    <input type="text" id="name" name="name"
                                        class="w-full px-3.5 py-2.5 text-sm bg-white border @error('name') border-rose-300 ring-1 ring-rose-300 @else border-slate-200 @enderror rounded-xl shadow-2xs focus:outline-none focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500 transition-all placeholder:text-slate-400"
                                        value="{{ old('name') }}" placeholder="{{ __('Nome completo') }}" required>
                                    @error('name')
                                        <p class="text-xs text-rose-600 mt-1 flex items-center gap-1">
                                            <i class="fas fa-circle-exclamation text-[10px]"></i> {{ $message }}
                                        </p>
                                    @enderror
                                </div>

                                {{-- Email --}}
                                <div>
                                    <label for="email" class="block text-xs font-bold uppercase tracking-wider text-slate-700 mb-1.5">
                                        {{ __('Email') }} <span class="text-rose-500">*</span>
                                    </label>
                                    <input type="email" id="email" name="email"
                                        class="w-full px-3.5 py-2.5 text-sm bg-white border @error('email') border-rose-300 ring-1 ring-rose-300 @else border-slate-200 @enderror rounded-xl shadow-2xs focus:outline-none focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500 transition-all placeholder:text-slate-400"
                                        value="{{ old('email') }}" placeholder="{{ __('exemplo@islagaia.pt') }}" required>
                                    @error('email')
                                        <p class="text-xs text-rose-600 mt-1 flex items-center gap-1">
                                            <i class="fas fa-circle-exclamation text-[10px]"></i> {{ $message }}
                                        </p>
                                    @enderror
                                </div>

                                {{-- Type --}}
                                <div>
                                    <label for="user-type-select" class="block text-xs font-bold uppercase tracking-wider text-slate-700 mb-1.5">
                                        {{ __('Tipo de Utilizador') }} <span class="text-rose-500">*</span>
                                    </label>
                                    @php
                                        $userTypes = [
                                            ['name' => __('Investigador'), 'value' => 'researcher'],
                                            ['name' => __('Docente'), 'value' => 'teacher'],
                                            ['name' => __('Estudante'), 'value' => 'student'],
                                            ['name' => __('Administrativo'), 'value' => 'administrative'],
                                        ];
                                    @endphp
                                    <select id="user-type-select" name="type"
                                        class="w-full px-3.5 py-2.5 text-sm bg-white border @error('type') border-rose-300 ring-1 ring-rose-300 @else border-slate-200 @enderror rounded-xl shadow-2xs focus:outline-none focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500 transition-all">
                                        @foreach ($userTypes as $type)
                                            <option value="{{ $type['value'] }}" @selected(old('type') == $type['value'])>
                                                {{ $type['name'] }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>

                                {{-- Ciência Vitae ID --}}
                                <div id="ciencia-vitae-container">
                                    <label for="ciencia_vitae" class="block text-xs font-bold uppercase tracking-wider text-slate-700 mb-1.5">
                                        {{ __('Ciência Vitae ID') }} <span id="cv-required-star" class="text-rose-500">*</span>
                                    </label>
                                    <input type="text" id="ciencia_vitae" name="ciencia_vitae"
                                        class="w-full px-3.5 py-2.5 text-sm font-mono bg-white border @error('ciencia_vitae') border-rose-300 ring-1 ring-rose-300 @else border-slate-200 @enderror rounded-xl shadow-2xs focus:outline-none focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500 transition-all placeholder:text-slate-400"
                                        value="{{ old('ciencia_vitae') }}" placeholder="Ex: A123-B456-C789" required>
                                    <p class="text-[11px] text-slate-400 mt-1">{{ __('Formato: XXXX-XXXX-XXXX (ex: A123-B456-C789)') }}</p>
                                    @error('ciencia_vitae')
                                        <p class="text-xs text-rose-600 mt-1 flex items-center gap-1">
                                            <i class="fas fa-circle-exclamation text-[10px]"></i> {{ $message }}
                                        </p>
                                    @enderror
                                </div>

                                {{-- Pertence ao ISLA --}}
                                <div class="pt-2">
                                    <input type="hidden" name="is_isla" value="0">
                                    <label class="flex items-center gap-3 cursor-pointer p-3 rounded-xl hover:bg-slate-50 transition-colors border border-slate-200">
                                        <input class="w-4 h-4 text-emerald-700 bg-white border-slate-300 rounded focus:ring-emerald-500 cursor-pointer"
                                            type="checkbox" name="is_isla" id="flexCheckDefault" value="1" {{ old('is_isla', 1) ? 'checked' : '' }}>
                                        <div>
                                            <span class="text-xs sm:text-sm font-semibold text-slate-800">{{ __('Pertence ao ISLA') }}</span>
                                            <p class="text-xs text-slate-500">{{ __('Indica se o utilizador está afiliado institucionalmente ao ISLA.') }}</p>
                                        </div>
                                    </label>
                                </div>

                                {{-- Actions --}}
                                <div class="pt-4 flex items-center justify-end gap-3">
                                    <a href="{{ route('user.active') }}"
                                        class="px-4 py-2.5 text-xs sm:text-sm font-semibold text-slate-700 bg-white hover:bg-slate-50 border border-slate-200 rounded-xl shadow-2xs transition-colors cursor-pointer">
                                        {{ __('Cancelar') }}
                                    </a>
                                    <button type="submit"
                                        class="inline-flex items-center gap-2 px-5 py-2.5 text-xs sm:text-sm font-semibold text-white bg-emerald-700 hover:bg-emerald-800 rounded-xl shadow-xs transition-all cursor-pointer">
                                        <i class="fas fa-user-plus text-xs"></i>
                                        <span>{{ __('Criar Utilizador') }}</span>
                                    </button>
                                </div>

                            </form>
                        </div>
                    </div>
                </div>

            </div>

        </div>
    </section>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const select = document.getElementById('user-type-select');
            const cvContainer = document.getElementById('ciencia-vitae-container');
            const cvInput = document.getElementById('ciencia_vitae');

            function toggleCv() {
                if (!select || !cvContainer) return;
                if (select.value === 'administrative') {
                    cvContainer.style.display = 'none';
                    if (cvInput) cvInput.removeAttribute('required');
                } else {
                    cvContainer.style.display = 'block';
                    if (cvInput) cvInput.setAttribute('required', 'required');
                }
            }

            if (select) {
                select.addEventListener('change', toggleCv);
                toggleCv();
            }
        });
    </script>

</x-app-layout>
