@props([
    'displayType' => 1,
    'displayAccessPubButton' => 0,
    "firstDivClass" => "d-flex mb-3",
    "secondDivClass" => "d-flex align-items-center w-100 ps-3",
    "dispayPublisher" => "0"
])

<div class="{{ $firstDivClass }}">
    <div class="{{ $secondDivClass }}">
        <div class="w-full space-y-1">
            @if (!empty($publication->citation_string))
                <cite class="italic text-xs sm:text-sm font-normal text-slate-500 block leading-relaxed line-clamp-2">
                    {{ $publication->citation_string }}
                </cite>
            @endif

            <h3 class="text-base sm:text-lg font-bold text-slate-900 tracking-tight leading-snug m-0">
                @if (!empty($publication->id))
                    <a href="{{ route('outputs.show', $publication->id) }}" class="text-slate-900 hover:text-emerald-700 hover:underline transition-colors">
                        @if (isset($publication->year))
                            {{ $publication->title . ', ' . $publication->year }}
                        @else
                            {{ $publication->title }}
                        @endif
                    </a>
                @else
                    @if (isset($publication->year))
                        {{ $publication->title . ', ' . $publication->year }}
                    @else
                        {{ $publication->title }}
                    @endif
                @endif
            </h3>

            <div class="flex flex-wrap items-center gap-2 pt-1 text-xs sm:text-sm text-slate-500">
                @if ($displayType && !empty($publication->type->name))
                    <span class="inline-flex items-center font-medium text-emerald-800 bg-emerald-50 px-2.5 py-0.5 rounded-md border border-emerald-100">
                        {{ $publication->type->name }}
                    </span>
                @endif

                @if ($dispayPublisher)
                    <span class="inline-flex items-center text-slate-600">
                        <i class="fas fa-building-columns me-1.5 text-xs opacity-70"></i>
                        {{ $publication->polymorphic->publisher ?? "Sem Publicador" }}
                    </span>
                @endif

                @if (!empty($publication->id))
                    <a href="{{ route('outputs.show', $publication->id) }}"
                       class="inline-flex items-center gap-1 font-semibold text-emerald-700 hover:text-emerald-900 hover:underline transition-colors text-xs">
                        <span>{{ __('Ver Detalhes') }}</span>
                        <i class="fas fa-arrow-right text-[10px]"></i>
                    </a>
                @endif

                @if ($displayAccessPubButton && $publication->doi)
                    @php($pubUrl = filter_var($publication->doi, FILTER_VALIDATE_URL) ? $publication->doi : 'https://www.doi.org/' . $publication->doi)
                    <a href="{{ $pubUrl }}" target="_blank" rel="nofollow noopener noreferrer"
                       class="inline-flex items-center gap-1 font-medium text-slate-500 hover:text-emerald-800 hover:underline transition-colors text-xs ml-auto sm:ml-0">
                        <span>{{ __('DOI') }}</span>
                        <i class="fas fa-arrow-up-right-from-square text-[9px]"></i>
                    </a>
                @endif
            </div>
        </div>
    </div>
</div>
