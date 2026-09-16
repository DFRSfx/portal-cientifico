<div class="relative shrink-0" x-data="{ open: false }" @click.outside="open = false">
    {{-- Trigger Button --}}
    <button type="button" @click="open = !open"
            class="h-9 inline-flex items-center gap-1.5 px-2.5 sm:px-3 text-xs font-semibold text-slate-700 hover:text-emerald-800 bg-white hover:bg-slate-50 border border-slate-200 hover:border-slate-300 rounded-lg shadow-2xs transition-all cursor-pointer focus:outline-none focus:ring-2 focus:ring-emerald-700/20"
            aria-label="{{ __('Selecionar idioma') }}" :aria-expanded="open ? 'true' : 'false'">
        <span class="inline-flex items-center gap-1.5">
            @if ($appLanguage == 'pt')
                <img src="{{ asset('logo/flags/pt.svg') }}" alt="PT" class="w-4 h-3 rounded-xs object-cover shadow-2xs shrink-0" />
                <span class="font-semibold tracking-wide">PT</span>
            @else
                <img src="{{ asset('logo/flags/en.svg') }}" alt="EN" class="w-4 h-3 rounded-xs object-cover shadow-2xs shrink-0" />
                <span class="font-semibold tracking-wide">EN</span>
            @endif
        </span>
        <svg xmlns="http://www.w3.org/2000/svg" width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round" class="text-slate-400 transition-transform duration-200 shrink-0" :class="{ 'rotate-180': open }">
            <path d="m6 9 6 6 6-6"></path>
        </svg>
    </button>

    {{-- Dropdown Menu --}}
    <ul class="dropdown-menu shadow-xl border border-slate-200/90 rounded-xl p-1 bg-white absolute right-0 mt-2 min-w-[160px] z-50 list-none"
        x-show="open" x-cloak x-transition.opacity.duration.150ms>
        @foreach ($flags as $language => $langInfo)
            <li>
                <a class="w-full flex items-center justify-between gap-3 px-3 py-2 text-xs font-medium {{ $appLanguage == $language ? 'text-emerald-800 bg-emerald-50 font-semibold' : 'text-slate-700 hover:bg-slate-50 hover:text-emerald-800' }} rounded-lg transition-colors"
                   href="{{ route('language', ['locale' => $language]) }}">
                    <span class="inline-flex items-center gap-2 shrink-0">
                        @if (file_exists(public_path('logo/flags/' . $language . '.svg')))
                            <img src="{{ asset('logo/flags/' . $language . '.svg') }}" alt="{{ $language }}" class="w-4 h-3 rounded-xs object-cover shadow-2xs shrink-0" />
                        @endif
                        <span class="whitespace-nowrap">{{ __($langInfo['lang']) }}</span>
                    </span>
                    @if ($appLanguage == $language)
                        <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round" class="text-emerald-600 shrink-0 ml-auto" aria-hidden="true">
                            <polyline points="20 6 9 17 4 12"></polyline>
                        </svg>
                    @endif
                </a>
            </li>
        @endforeach
    </ul>
</div>