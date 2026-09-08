@props([
    'displayType' => 1,
    'displayAccessPubButton' => 0,
    "firstDivClass" => "d-flex mb-3",
    "secondDivClass" => "d-flex align-items-center w-100 ps-3",
    "dispayPublisher" => "0"
])

<div class="{{ $firstDivClass }}">
    <div class="{{ $secondDivClass }}">
        <div class="w-100">
            <span class="text-muted">
                <cite>{{ $publication->citation_string }}</cite>
            </span>
            <br>
            <span class="fw-bolder mt-1">
                @if (isset($publication->year))
                    {{ $publication->title . ', ' . $publication->year }}
                @else
                    {{ $publication->title }}
                @endif
            </span>
            <br>
            @if ($displayType)
                <span class="text-muted ">
                    {{ $publication->type->name }}
                </span>
            @endif

            @if ($dispayPublisher)
                <span class="text-muted ">
                    {{ $publication->polymorphic->publisher ?? "Sem Publicador" }}
                </span>
            @endif

            @if ($displayAccessPubButton && $publication->doi)
                @php($pubUrl = filter_var($publication->doi, FILTER_VALIDATE_URL) ? $publication->doi : 'https://www.doi.org/' . $publication->doi)

                <span>
                    <a href="{{ $pubUrl }}" target="__blank" ref="nofollow noopener noreferrer"> Aceder à Publicação
                    </a>
                </span>
            @endif
        </div>
    </div>
</div>
