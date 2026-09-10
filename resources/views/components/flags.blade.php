<li class="relative nav-item px-1" x-data="{ open: false }">

    <a class="nav-link font-bold uppercase tracking-wider text-xs text-slate-700 hover:text-emerald-700 transition-colors flex items-center gap-1 cursor-pointer py-2" href="#"
        @click.prevent="open = !open">
        {{ Lang::locale() }}
        <i class="fas fa-chevron-down text-[9px] text-slate-400 transition-transform duration-150" :class="{ 'rotate-180': open }"></i>
    </a>

    <!-- Language dropdown -->
    <ul class="dropdown-menu shadow-lg border border-slate-200/80 rounded-xl py-1.5 bg-white/98 backdrop-blur-md absolute right-0 mt-1 min-w-[140px] z-50"
        x-show="open" @click.outside="open = false" x-transition.opacity.duration.150ms style="display: none;">

        @foreach ($flags as $language => $langInfo)
            <li>
                <a class="dropdown-item px-3 py-2 text-xs font-medium text-slate-700 hover:bg-emerald-50 hover:text-emerald-800 rounded-lg mx-1 flex items-center justify-between transition-colors" href="{{ route('language', ['locale' => $language]) }}">
                    <span>{{ __($langInfo['lang']) }}</span>
                    @if ($appLanguage == $language)
                        <i class="fa fa-check text-emerald-600 ms-2 text-[10px]"></i>
                    @endif
                </a>
            </li>
        @endforeach

    </ul>

</li>