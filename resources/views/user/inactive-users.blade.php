<x-app-layout>

    <x-slot:title>
        {{ __('Utilizadores Inativos / Pendentes') }}
    </x-slot>

    @push('header-links')
        <link
            href="https://cdn.datatables.net/v/bs5/jq-3.7.0/jszip-3.10.1/dt-1.13.8/b-2.4.2/b-colvis-2.4.2/b-html5-2.4.2/b-print-2.4.2/datatables.min.css"
            rel="stylesheet">
    @endpush

    @push('header-scripts')
        <script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.2.7/pdfmake.min.js"></script>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.2.7/vfs_fonts.js"></script>
        <script
            src="https://cdn.datatables.net/v/bs5/jq-3.7.0/jszip-3.10.1/dt-1.13.8/b-2.4.2/b-colvis-2.4.2/b-html5-2.4.2/b-print-2.4.2/datatables.min.js">
        </script>
    @endpush

    @php
        $activeCount = \App\Models\User::where('is_active', 1)->count();
        $inactiveCount = \App\Models\User::where('is_active', 0)->count();
    @endphp

    <section class="saas-list-compact py-4">
        <div class="container saas-wide">

            {{-- Page Header --}}
            <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 mb-6">
                <div>
                    <h1 class="text-xl sm:text-2xl font-bold text-slate-900 tracking-tight flex items-center gap-2.5">
                        <span class="w-2.5 h-6 bg-emerald-700 rounded-full inline-block"></span>
                        {{ __('Gestão de Utilizadores') }}
                    </h1>
                    <p class="text-xs sm:text-sm text-slate-500 mt-1">
                        {{ __('Administração de contas de utilizadores, permissões e autorizações no sistema.') }}
                    </p>
                </div>
                <div class="flex items-center gap-2">
                    <a href="{{ route('user.create') }}"
                        class="inline-flex items-center gap-2 px-4 py-2 text-xs sm:text-sm font-semibold text-white bg-emerald-700 hover:bg-emerald-800 rounded-xl shadow-xs transition-all cursor-pointer">
                        <i class="fas fa-user-plus text-xs"></i>
                        <span>{{ __('Novo Utilizador') }}</span>
                    </a>
                </div>
            </div>

            {{-- Navigation Tabs --}}
            <div class="flex items-center gap-2 mb-4 border-b border-slate-200">
                <a href="{{ route('user.active') }}"
                    class="inline-flex items-center gap-2 px-4 py-2.5 text-xs sm:text-sm font-medium text-slate-600 hover:text-emerald-800 hover:bg-slate-50 rounded-t-xl transition-all">
                    <i class="fas fa-user-check text-xs text-slate-400"></i>
                    <span>{{ __('Utilizadores Ativos') }}</span>
                    <span class="px-2 py-0.5 rounded-full text-[10px] font-bold bg-slate-100 text-slate-700">
                        {{ $activeCount }}
                    </span>
                </a>
                <a href="{{ route('user.inactive') }}"
                    class="inline-flex items-center gap-2 px-4 py-2.5 text-xs sm:text-sm font-bold text-emerald-800 border-b-2 border-emerald-600 bg-emerald-50/70 rounded-t-xl transition-all">
                    <i class="fas fa-user-clock text-xs text-emerald-600"></i>
                    <span>{{ __('Inativos / Pendentes') }}</span>
                    <span class="px-2 py-0.5 rounded-full text-[10px] font-bold {{ $inactiveCount > 0 ? 'bg-amber-100 text-amber-800' : 'bg-slate-100 text-slate-700' }}">
                        {{ $inactiveCount }}
                    </span>
                </a>
            </div>

            {{-- Alerts --}}
            @if (session('success'))
                <div class="mb-4 p-3.5 rounded-xl bg-emerald-50 border border-emerald-200 text-emerald-900 text-xs font-medium flex items-center gap-2.5 shadow-2xs">
                    <i class="fas fa-check-circle text-emerald-600 text-sm"></i>
                    <span>{{ session('success') }}</span>
                </div>
            @endif

            @if (isset($errors) && $errors->any())
                <div class="mb-4 p-3.5 rounded-xl bg-rose-50 border border-rose-200 text-rose-900 text-xs font-medium flex items-center gap-2.5 shadow-2xs">
                    <i class="fas fa-triangle-exclamation text-rose-600 text-sm"></i>
                    <span>{{ $errors->first() }}</span>
                </div>
            @endif

            {{-- Main Table Card --}}
            <div class="card rounded-2xl border border-slate-200/80 bg-white shadow-sm overflow-hidden">
                <div class="p-4 sm:p-5 border-b border-slate-100 flex flex-col sm:flex-row sm:items-center justify-between gap-3 bg-slate-50/30">
                    <div>
                        <h2 class="text-xs sm:text-sm font-bold uppercase tracking-wider text-slate-800 m-0">
                            {{ __('Lista de Utilizadores Inativos ou Pendentes') }}
                        </h2>
                    </div>
                    <div class="flex items-center gap-2" id="datatables-buttons"></div>
                </div>

                <div class="p-4 sm:p-6">
                    <div class="table-responsive">
                        <table class="table users-table align-middle mb-0 bg-white w-full" id="users_table">
                            <thead>
                                <tr>
                                    <th class="rounded-tl-xl">{{ __('Utilizador') }}</th>
                                    <th>{{ __('Ciência Vitae ID') }}</th>
                                    <th>{{ __('Estado Email') }}</th>
                                    <th>{{ __('Data Registo') }}</th>
                                    <th>{{ __('Tipo') }}</th>
                                    <th class="rounded-tr-xl text-right">{{ __('Ações') }}</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($users as $user)
                                    <tr>
                                        <td class="py-3">
                                            <div class="flex items-center gap-3">
                                                <div class="w-9 h-9 rounded-full bg-slate-100 text-slate-700 font-bold text-xs flex items-center justify-center shrink-0 border border-slate-200 shadow-2xs">
                                                    {{ strtoupper(substr($user->name, 0, 2)) }}
                                                </div>
                                                <div class="min-w-0">
                                                    <p class="font-bold text-slate-900 text-sm mb-0.5 leading-snug truncate">
                                                        {{ $user->name }}
                                                    </p>
                                                    <p class="text-xs text-slate-500 mb-0 truncate font-normal">
                                                        {{ $user->email }}
                                                    </p>
                                                </div>
                                            </div>
                                        </td>
                                        <td class="py-3">
                                            @if(!empty($user->ciencia_vitae))
                                                <span class="inline-block text-[11px] font-mono font-medium text-emerald-800 bg-emerald-50 px-2 py-0.5 rounded-md border border-emerald-200/60">
                                                    {{ $user->ciencia_vitae }}
                                                </span>
                                            @else
                                                <span class="text-xs text-slate-400">—</span>
                                            @endif
                                        </td>
                                        <td class="py-3">
                                            @if ($user->email_verified_at)
                                                <span class="inline-flex items-center gap-1.5 px-2.5 py-0.5 rounded-full text-xs font-semibold bg-emerald-50 text-emerald-800 border border-emerald-200/80">
                                                    <i class="fas fa-check-circle text-[10px] text-emerald-600"></i>
                                                    {{ __('Verificado') }}
                                                </span>
                                            @else
                                                <span class="inline-flex items-center gap-1.5 px-2.5 py-0.5 rounded-full text-xs font-semibold bg-amber-50 text-amber-800 border border-amber-200/80">
                                                    <i class="fas fa-clock text-[10px] text-amber-600"></i>
                                                    {{ __('Pendente') }}
                                                </span>
                                            @endif
                                        </td>
                                        <td class="py-3 text-xs text-slate-600 font-medium">
                                            {{ $user->created_at?->format('Y-m-d') ?? '-' }}
                                        </td>
                                        <td class="py-3">
                                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-[10px] font-bold uppercase tracking-wider bg-slate-100 text-slate-700 border border-slate-200/80">
                                                {{ $user->type }}
                                            </span>
                                        </td>
                                        <td class="py-3 text-right">
                                            <div class="inline-flex items-center justify-end gap-1.5 flex-wrap">
                                                <form method="POST" action="{{ route('user.activate', $user->id) }}" class="inline-block m-0">
                                                    @csrf
                                                    <button type="submit"
                                                        class="inline-flex items-center gap-1 px-3 py-1 text-xs font-semibold text-white bg-emerald-700 hover:bg-emerald-800 rounded-lg transition-colors cursor-pointer shadow-xs"
                                                        title="{{ __('Aprovar e ativar utilizador') }}">
                                                        <i class="fas fa-check text-[10px]"></i>
                                                        <span>{{ __('Aprovar') }}</span>
                                                    </button>
                                                </form>

                                                <a href="{{ route('user.edit', $user->id) }}"
                                                    class="inline-flex items-center gap-1 px-2.5 py-1 text-xs font-semibold text-slate-700 bg-slate-50 hover:bg-slate-100 border border-slate-200 rounded-lg transition-colors cursor-pointer"
                                                    title="{{ __('Editar utilizador') }}">
                                                    <i class="fas fa-pen text-[10px] text-slate-500"></i>
                                                    <span>{{ __('Editar') }}</span>
                                                </a>

                                                <form method="POST" action="{{ route('user.resend-verification', $user->id) }}" class="inline-block m-0">
                                                    @csrf
                                                    <button type="submit"
                                                        class="inline-flex items-center gap-1 px-2.5 py-1 text-xs font-semibold text-blue-800 bg-blue-50 hover:bg-blue-100 border border-blue-200 rounded-lg transition-colors cursor-pointer"
                                                        title="{{ __('Reenviar email de confirmação') }}">
                                                        <i class="fas fa-paper-plane text-[10px]"></i>
                                                        <span>{{ __('Email') }}</span>
                                                    </button>
                                                </form>

                                                <form method="POST" action="{{ route('user.destroy', $user->id) }}" class="inline-block m-0"
                                                    onsubmit="return confirm('{{ __('Tem a certeza que deseja remover este utilizador?') }}');">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit"
                                                        class="inline-flex items-center gap-1 px-2.5 py-1 text-xs font-semibold text-rose-800 bg-rose-50 hover:bg-rose-100 border border-rose-200 rounded-lg transition-colors cursor-pointer"
                                                        title="{{ __('Remover utilizador') }}">
                                                        <i class="fas fa-trash-can text-[10px]"></i>
                                                        <span>{{ __('Remover') }}</span>
                                                    </button>
                                                </form>
                                            </div>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

        </div>
    </section>

    <script>
        function initializeDataTable() {
            const buttonsToInsert = [
                {
                    extend: 'csv',
                    text: '<i class="fas fa-file-csv text-emerald-700 mr-1"></i> CSV',
                    className: 'btn-jstabales'
                },
                {
                    extend: 'excel',
                    text: '<i class="fas fa-file-excel text-emerald-700 mr-1"></i> Excel',
                    className: 'btn-jstabales'
                },
                {
                    extend: 'pdf',
                    text: '<i class="fas fa-file-pdf text-emerald-700 mr-1"></i> PDF',
                    className: 'btn-jstabales'
                }
            ];

            const TABLE = $('#users_table').DataTable({
                responsive: false,
                buttons: buttonsToInsert,
                order: [[0, 'asc']],
                pageLength: 10,
                language: {
                    search: "{{ __('Procurar:') }}",
                    lengthMenu: "{{ __('Mostrar') }} _MENU_",
                    infoEmpty: "{{ __('Mostrando 0 utilizadores') }}",
                    info: "{{ __('Mostrando') }} _START_ - _END_ {{ __('de') }} _TOTAL_ {{ __('utilizadores') }}",
                    infoFiltered: "({{ __('filtrado de') }} _MAX_ {{ __('no total') }})",
                    emptyTable: "{{ __('Nenhum utilizador inativo ou pendente') }}",
                    zeroRecords: "{{ __('Nenhum utilizador encontrado') }}",
                    paginate: {
                        next: '{{ __("Próximo") }}',
                        previous: '{{ __("Anterior") }}'
                    }
                }
            });

            TABLE.buttons().container().appendTo('#datatables-buttons');
        }

        $(document).ready(function() {
            initializeDataTable();
        });
    </script>

</x-app-layout>
