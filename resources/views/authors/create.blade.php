<x-app-layout>
    <x-slot:title>
        {{ __('Registar Autor') }}
    </x-slot>

    <section class="py-8 bg-slate-50/50 min-h-[calc(100vh-160px)]">
        <div class="max-w-2xl mx-auto px-4 sm:px-6">

            <div class="mb-6">
                <a href="{{ route('authors.index') }}"
                    class="inline-flex items-center gap-2 text-xs font-semibold text-slate-600 hover:text-emerald-800 transition-colors mb-2">
                    <i class="fas fa-arrow-left text-[11px]"></i>
                    <span>{{ __('Voltar aos Autores') }}</span>
                </a>
                <h1 class="text-xl sm:text-2xl font-bold text-slate-900 tracking-tight flex items-center gap-2.5">
                    <span class="w-2.5 h-6 bg-emerald-700 rounded-full inline-block"></span>
                    {{ __('Novo Autor') }}
                </h1>
            </div>

            <div class="bg-white border border-slate-200/80 rounded-2xl shadow-xs overflow-hidden p-6 sm:p-8">
                <form method="POST" action="{{ route('user.store') }}" class="space-y-4">
                    @csrf
                    <input type="hidden" name="type" value="researcher">
                    <input type="hidden" name="is_isla" value="1">

                    <div>
                        <label for="name" class="block text-xs font-bold uppercase tracking-wider text-slate-700 mb-1.5">
                            {{ __('Nome do Autor') }} <span class="text-rose-500">*</span>
                        </label>
                        <input type="text" id="name" name="name" value="{{ old('name') }}" required
                            class="w-full px-3.5 py-2.5 text-sm bg-white border border-slate-200 rounded-xl shadow-2xs focus:outline-none focus:ring-2 focus:ring-emerald-500 placeholder:text-slate-400">
                    </div>

                    <div>
                        <label for="email" class="block text-xs font-bold uppercase tracking-wider text-slate-700 mb-1.5">
                            {{ __('Email Institucional') }} <span class="text-rose-500">*</span>
                        </label>
                        <input type="email" id="email" name="email" value="{{ old('email') }}" required
                            class="w-full px-3.5 py-2.5 text-sm bg-white border border-slate-200 rounded-xl shadow-2xs focus:outline-none focus:ring-2 focus:ring-emerald-500 placeholder:text-slate-400">
                    </div>

                    <div>
                        <label for="ciencia_vitae" class="block text-xs font-bold uppercase tracking-wider text-slate-700 mb-1.5">
                            {{ __('Ciência Vitae ID') }} <span class="text-rose-500">*</span>
                        </label>
                        <input type="text" id="ciencia_vitae" name="ciencia_vitae" value="{{ old('ciencia_vitae') }}" placeholder="A123-B456-C789" required
                            class="w-full px-3.5 py-2.5 text-sm font-mono bg-white border border-slate-200 rounded-xl shadow-2xs focus:outline-none focus:ring-2 focus:ring-emerald-500 placeholder:text-slate-400">
                    </div>

                    <div class="pt-4 flex items-center justify-end gap-3">
                        <a href="{{ route('authors.index') }}"
                            class="px-4 py-2.5 text-xs sm:text-sm font-semibold text-slate-700 bg-white hover:bg-slate-50 border border-slate-200 rounded-xl shadow-2xs transition-colors cursor-pointer">
                            {{ __('Cancelar') }}
                        </a>
                        <button type="submit"
                            class="inline-flex items-center gap-2 px-5 py-2.5 text-xs sm:text-sm font-semibold text-white bg-emerald-700 hover:bg-emerald-800 rounded-xl shadow-xs transition-all cursor-pointer">
                            <i class="fas fa-check text-xs"></i>
                            <span>{{ __('Guardar') }}</span>
                        </button>
                    </div>
                </form>
            </div>

        </div>
    </section>
</x-app-layout>