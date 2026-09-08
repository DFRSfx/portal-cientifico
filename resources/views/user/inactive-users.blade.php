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

    <div class="container-fluid pt-5 px-4">
        <div class="row justify-content-center">
            <div class="col-12">
                <div class="card">

                    <div class="card-body">

                        <div class="table-responsive p-2">
                            <table class="table align-middle mb-0 bg-white" id="users_table">
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
                                            <td><p class="fw-normal mb-0">{{ $user->name }}</p></td>
                                            <td><p class="fw-normal mb-0">{{ $user->email }}</p></td>
                                            <td><p class="fw-normal mb-0">{{ $user->ciencia_vitae ?? '-' }}</p></td>
                                            <td>
                                                <span class="badge {{ $user->email_verified_at ? 'badge-success' : 'badge-warning' }} rounded-pill">
                                                    {{ $user->email_verified_at ? __('Verificado') : __('Pendente') }}
                                                </span>
                                            </td>
                                            <td><p class="fw-normal mb-0">{{ $user->created_at?->format('Y-m-d') ?? '-' }}</p></td>
                                            <td class="d-flex flex-wrap gap-2">
                                                <a href="{{ route('user.edit', $user->id) }}" class="btn btn-sm btn-outline-primary">
                                                    {{ __('Editar') }}
                                                </a>
                                                <form method="POST" action="{{ route('user.activate', $user->id) }}">
                                                    @csrf
                                                    <button type="submit" class="btn btn-sm btn-success">
                                                        {{ __('Aprovar') }}
                                                    </button>
                                                </form>
                                                <form method="POST" action="{{ route('user.resend-verification', $user->id) }}">
                                                    @csrf
                                                    <button type="submit" class="btn btn-sm btn-outline-secondary">
                                                        {{ __('Reenviar email') }}
                                                    </button>
                                                </form>
                                                <form method="POST" action="{{ route('user.destroy', $user->id) }}" onsubmit="return confirm('{{ __('Tem a certeza que deseja remover?') }}');">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="btn btn-sm btn-outline-danger">
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
