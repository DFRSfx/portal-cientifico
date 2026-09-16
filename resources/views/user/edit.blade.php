<x-app-layout>
    <x-slot:title>
        {{ __('Editar Utilizador') }} - {{ $user->name }}
    </x-slot>

    <section class="py-8 bg-slate-50/50 min-h-[calc(100vh-160px)]">
        <div class="max-w-3xl mx-auto px-4 sm:px-6">

            {{-- Back Link & Breadcrumb --}}
            <div class="mb-6 flex items-center justify-between">
                <a href="{{ old('redirect_to', url()->previous()) }}"
                    class="inline-flex items-center gap-2 text-xs font-semibold text-slate-600 hover:text-emerald-800 transition-colors">
                    <i class="fas fa-arrow-left text-[11px]"></i>
                    <span>{{ __('Voltar à lista') }}</span>
                </a>
                <span class="text-xs text-slate-400 font-mono">ID #{{ $user->id }}</span>
            </div>

            {{-- Main Form Card --}}
            <div class="bg-white border border-slate-200/80 rounded-2xl shadow-xs overflow-hidden">
                {{-- Header --}}
                <div class="p-5 sm:p-6 border-b border-slate-100 bg-slate-50/40 flex items-center gap-4">
                    <div class="w-12 h-12 rounded-full bg-emerald-100 text-emerald-800 font-bold text-base flex items-center justify-center shrink-0 border border-emerald-200/80 shadow-2xs">
                        {{ strtoupper(substr($user->name, 0, 2)) }}
                    </div>
                    <div>
                        <h1 class="text-lg sm:text-xl font-bold text-slate-900 leading-tight">
                            {{ __('Editar Utilizador') }}
                        </h1>
                        <p class="text-xs sm:text-sm text-slate-500 mt-0.5">
                            {{ $user->email }}
                        </p>
                    </div>
                </div>

                {{-- Form Body --}}
                <div class="p-6 sm:p-8">
                    <form method="POST" action="{{ route('user.update', $user->id) }}" class="space-y-5">
                        @csrf
                        @method('PATCH')

                        <input type="hidden" name="redirect_to" value="{{ old('redirect_to', url()->previous()) }}">

                        {{-- Name --}}
                        <div>
                            <label for="name" class="block text-xs font-bold uppercase tracking-wider text-slate-700 mb-1.5">
                                {{ __('Nome Completo') }} <span class="text-rose-500">*</span>
                            </label>
                            <input type="text" id="name" name="name"
                                class="w-full px-3.5 py-2.5 text-sm bg-white border @error('name') border-rose-300 ring-1 ring-rose-300 @else border-slate-200 @enderror rounded-xl shadow-2xs focus:outline-none focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500 transition-all placeholder:text-slate-400"
                                value="{{ old('name', $user->name) }}" required>
                            @error('name')
                                <p class="text-xs text-rose-600 mt-1.5 flex items-center gap-1">
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
                                value="{{ old('email', $user->email) }}" required>
                            @error('email')
                                <p class="text-xs text-rose-600 mt-1.5 flex items-center gap-1">
                                    <i class="fas fa-circle-exclamation text-[10px]"></i> {{ $message }}
                                </p>
                            @enderror
                        </div>

                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-5">
                            {{-- Ciência Vitae ID --}}
                            <div>
                                <label for="ciencia_vitae" class="block text-xs font-bold uppercase tracking-wider text-slate-700 mb-1.5">
                                    {{ __('Ciência Vitae ID') }}
                                </label>
                                <input type="text" id="ciencia_vitae" name="ciencia_vitae"
                                    class="w-full px-3.5 py-2.5 text-sm font-mono bg-white border @error('ciencia_vitae') border-rose-300 ring-1 ring-rose-300 @else border-slate-200 @enderror rounded-xl shadow-2xs focus:outline-none focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500 transition-all placeholder:text-slate-400"
                                    value="{{ old('ciencia_vitae', $user->ciencia_vitae) }}" placeholder="Ex: A123-B456-C789">
                                @error('ciencia_vitae')
                                    <p class="text-xs text-rose-600 mt-1.5 flex items-center gap-1">
                                        <i class="fas fa-circle-exclamation text-[10px]"></i> {{ $message }}
                                    </p>
                                @enderror
                            </div>

                            {{-- Type --}}
                            <div>
                                <label for="type" class="block text-xs font-bold uppercase tracking-wider text-slate-700 mb-1.5">
                                    {{ __('Tipo de Utilizador') }} <span class="text-rose-500">*</span>
                                </label>
                                <select id="type" name="type"
                                    class="w-full px-3.5 py-2.5 text-sm bg-white border @error('type') border-rose-300 ring-1 ring-rose-300 @else border-slate-200 @enderror rounded-xl shadow-2xs focus:outline-none focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500 transition-all" required>
                                    <option value="researcher" @selected(old('type', $user->type) == 'researcher')>{{ __('Investigador') }}</option>
                                    <option value="student" @selected(old('type', $user->type) == 'student')>{{ __('Estudante') }}</option>
                                    <option value="administrative" @selected(old('type', $user->type) == 'administrative')>{{ __('Administrativo') }}</option>
                                </select>
                                @error('type')
                                    <p class="text-xs text-rose-600 mt-1.5 flex items-center gap-1">
                                        <i class="fas fa-circle-exclamation text-[10px]"></i> {{ $message }}
                                    </p>
                                @enderror
                            </div>
                        </div>

                        {{-- Entidade --}}
                        <div>
                            <label for="entidade" class="block text-xs font-bold uppercase tracking-wider text-slate-700 mb-1.5">
                                {{ __('Entidade / Instituição') }}
                            </label>
                            <input type="text" id="entidade" name="entidade"
                                class="w-full px-3.5 py-2.5 text-sm bg-white border @error('entidade') border-rose-300 ring-1 ring-rose-300 @else border-slate-200 @enderror rounded-xl shadow-2xs focus:outline-none focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500 transition-all placeholder:text-slate-400"
                                value="{{ old('entidade', $user->entidade) }}" placeholder="Ex: ISLA Gaia">
                            @error('entidade')
                                <p class="text-xs text-rose-600 mt-1.5 flex items-center gap-1">
                                    <i class="fas fa-circle-exclamation text-[10px]"></i> {{ $message }}
                                </p>
                            @enderror
                        </div>

                        {{-- Flags / Toggles --}}
                        <div class="pt-4 border-t border-slate-100 space-y-3">
                            <label class="flex items-center gap-3 cursor-pointer p-3 rounded-xl hover:bg-slate-50 transition-colors border border-transparent hover:border-slate-200">
                                <input class="w-4 h-4 text-emerald-700 bg-white border-slate-300 rounded focus:ring-emerald-500 cursor-pointer"
                                    type="checkbox" name="is_active" id="is_active" value="1" {{ old('is_active', $user->is_active) ? 'checked' : '' }}>
                                <div>
                                    <span class="text-xs sm:text-sm font-semibold text-slate-800">{{ __('Conta Ativa') }}</span>
                                    <p class="text-xs text-slate-500">{{ __('Permite que o utilizador inicie sessão e aceda aos recursos da sua conta.') }}</p>
                                </div>
                            </label>

                            <label class="flex items-center gap-3 cursor-pointer p-3 rounded-xl hover:bg-slate-50 transition-colors border border-transparent hover:border-slate-200">
                                <input class="w-4 h-4 text-emerald-700 bg-white border-slate-300 rounded focus:ring-emerald-500 cursor-pointer"
                                    type="checkbox" name="is_isla" id="is_isla" value="1" {{ old('is_isla', $user->is_isla) ? 'checked' : '' }}>
                                <div>
                                    <span class="text-xs sm:text-sm font-semibold text-slate-800">{{ __('Pertence ao ISLA') }}</span>
                                    <p class="text-xs text-slate-500">{{ __('Indica se o utilizador está afiliado institucionalmente ao ISLA Gaia.') }}</p>
                                </div>
                            </label>

                            <label class="flex items-center gap-3 cursor-pointer p-3 rounded-xl hover:bg-slate-50 transition-colors border border-transparent hover:border-slate-200">
                                <input class="w-4 h-4 text-emerald-700 bg-white border-slate-300 rounded focus:ring-emerald-500 cursor-pointer"
                                    type="checkbox" name="mark_verified" id="mark_verified" value="1">
                                <div>
                                    <span class="text-xs sm:text-sm font-semibold text-slate-800">{{ __('Marcar email como verificado agora') }}</span>
                                    <p class="text-xs text-slate-500">
                                        {{ __('Estado atual:') }} 
                                        @if($user->email_verified_at)
                                            <span class="text-emerald-700 font-semibold">{{ __('Já verificado') }} ({{ $user->email_verified_at->format('d/m/Y H:i') }})</span>
                                        @else
                                            <span class="text-amber-700 font-semibold">{{ __('Não verificado') }}</span>
                                        @endif
                                    </p>
                                </div>
                            </label>
                        </div>

                        {{-- Action Buttons --}}
                        <div class="pt-5 border-t border-slate-100 flex items-center justify-end gap-3">
                            <a href="{{ old('redirect_to', url()->previous()) }}"
                                class="px-4 py-2.5 text-xs sm:text-sm font-semibold text-slate-700 bg-white hover:bg-slate-50 border border-slate-200 rounded-xl shadow-2xs transition-colors cursor-pointer">
                                {{ __('Cancelar') }}
                            </a>
                            <button type="submit"
                                class="inline-flex items-center gap-2 px-5 py-2.5 text-xs sm:text-sm font-semibold text-white bg-emerald-700 hover:bg-emerald-800 rounded-xl shadow-xs transition-all cursor-pointer">
                                <i class="fas fa-check text-xs"></i>
                                <span>{{ __('Guardar Alterações') }}</span>
                            </button>
                        </div>
                    </form>
                </div>
            </div>

        </div>
    </section>
</x-app-layout>
