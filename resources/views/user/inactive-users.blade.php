<x-app-layout>

    @push('header-links')
        <!-- DataTables Bootstrap -->
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

    <div class="container-fluid pt-5 px-4 max-w-7xl mx-auto">
        <div class="row justify-content-center">
            <div class="col-12">
                <div class="card rounded-2xl border border-slate-200/80 bg-white/95 shadow-sm overflow-hidden">

                    <div class="card-body p-4 sm:p-6">

                        <div class="table-responsive p-1">
                            <table class="table users-table align-middle mb-0 bg-white" id="users_table">
                                <thead class="bg-light">
                                    <tr>
                                        <th>{{ __('Nome') }}</th>
                                        <th>{{ __('Email') }}</th>
                                        <th>{{ __('Ciencia Vitae Id') }}</th>
                                        <th>{{ __('Verificacao') }}</th>
                                        <th>{{ __('Registo') }}</th>
                                        <th>{{ __('Acoes') }}</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($users as $user)
                                        <tr>
                                            <td><p class="font-semibold text-slate-800 mb-0">{{ $user->name }}</p></td>
                                            <td><p class="text-slate-600 mb-0">{{ $user->email }}</p></td>
                                            <td><p class="text-slate-600 mb-0 font-mono text-xs">{{ $user->ciencia_vitae ?? '-' }}</p></td>
                                            <td>
                                                @if ($user->email_verified_at)
                                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-semibold bg-emerald-100 text-emerald-800 border border-emerald-200">
                                                        <i class="fas fa-check-circle me-1 text-[10px]"></i>{{ __('Verificado') }}
                                                    </span>
                                                @else
                                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-semibold bg-amber-100 text-amber-800 border border-amber-200">
                                                        <i class="fas fa-clock me-1 text-[10px]"></i>{{ __('Pendente') }}
                                                    </span>
                                                @endif
                                            </td>
                                            <td><p class="text-slate-600 mb-0 text-xs">{{ $user->created_at?->format('Y-m-d') ?? '-' }}</p></td>
                                            <td class="d-flex flex-wrap gap-1.5">
                                                <a href="{{ route('user.edit', $user->id) }}" class="btn btn-sm text-xs font-semibold text-blue-700 bg-blue-50 hover:bg-blue-100 border border-blue-200 rounded-lg transition-all">
                                                    {{ __('Editar') }}
                                                </a>
                                                <form method="POST" action="{{ route('user.activate', $user->id) }}">
                                                    @csrf
                                                    <button type="submit" class="btn btn-sm text-xs font-semibold text-white bg-emerald-700 hover:bg-emerald-800 border-0 rounded-lg transition-all shadow-xs">
                                                        {{ __('Aprovar') }}
                                                    </button>
                                                </form>
                                                <form method="POST" action="{{ route('user.resend-verification', $user->id) }}">
                                                    @csrf
                                                    <button type="submit" class="btn btn-sm text-xs font-semibold text-slate-700 bg-slate-100 hover:bg-slate-200 border border-slate-300 rounded-lg transition-all">
                                                        {{ __('Reenviar email') }}
                                                    </button>
                                                </form>
                                                <form method="POST" action="{{ route('user.destroy', $user->id) }}" onsubmit="return confirm('{{ __('Tem a certeza que deseja remover?') }}');">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="btn btn-sm text-xs font-semibold text-rose-700 bg-rose-50 hover:bg-rose-100 border border-rose-200 rounded-lg transition-all">
                                                        {{ __('Remover') }}
                                                    </button>
                                                </form>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    @error('errorMessage')
        <x-alert-component id="alert-message" error-message="1" message="{{ $message }}" />
    @enderror

    @if (\Session::has('success'))
        <x-alert-component id="alert-message" error-message="0" message="{{ \Session::get('success') }}" />
    @endif

    <script>
        function initializeDataTable(buttons) {
            const buttonsToInsert = buttons.map(element => ({
                extend: element,
                className: 'btn-jstabales',
                removeClass: 'btn-group'
            }));

            const TABLE = $('#users_table').DataTable({
                dom: 'Bfrtip',
                orderCellsTop: true,
                fixedHeader: false,
                bPaginate: false,
                buttons: buttonsToInsert,
                language: {
                    search: "{{ __('Procurar') }}",
                    lengthMenu: "{{ __('Mostrar') }} _MENU_ ",
                    infoEmpty: "{{ __('Mostrando') }} 0 - 0 {{ 'de' }} 0",
                    info: "{{ __('Mostrando') }} _START_ - _END_ {{ 'de' }} _TOTAL_",
                    paginate: {
                        next: '{{ __('Próximo') }}',
                        previous: '{{ __('Anterior') }}'
                    },
                },
                order: [
                    [1, 'desc']
                ]
            });

            TABLE.buttons().container().appendTo('#datatables-buttons')
        }

        $(document).ready(function() {
            initializeDataTable(['excel', 'pdf']);
        });
    </script>

</x-app-layout>
