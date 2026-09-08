<li class="nav-item dropdown px-2">

    <a class="nav-link me-3 me-lg-0 dropdown-toggle hidden-arrow" href="#" id="navbarDropdown"
        role="button" data-mdb-toggle="dropdown" aria-expanded="false">
        {{ Lang::locale() }}    </a>

    <!-- Language -->
    <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="navbarDropdown"
        style="position: absolute; inset: 0px auto auto 0px; margin: 0px; transform: translate(8px, 44px);"
        data-popper-placement="bottom-start" data-mdb-popper="null">

        @foreach ($flags as $language => $langInfo)
            <li>
                <a class="dropdown-item" href="{{ route('language', ['locale' => $language]) }}">{{ __($langInfo['lang']) }}

                    @if ($appLanguage == $language)
                        <i class="fa fa-check text-success ms-2"></i>
                    @endif

                </a>
            </li>
        @endforeach

    </ul>

</li>