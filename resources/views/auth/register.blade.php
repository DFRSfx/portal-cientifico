<x-app-layout>
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Manrope:wght@500;600;700&display=swap');

        body.saas {
            font-family: 'Manrope', 'Segoe UI', sans-serif;
            background: radial-gradient(circle at 10% 15%, #d8f0e1 0, #eef2f7 28%, #f6f7fb 60%);
        }

        .auth-shell {
            min-height: calc(100vh - 80px);
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 32px 18px 48px;
        }

        .auth-card {
            width: min(1260px, 98vw);
            background: rgba(255, 255, 255, 0.96);
            border-radius: 22px;
            box-shadow: 0 28px 78px rgba(26, 63, 23, 0.22);
            overflow: hidden;
            display: grid;
            grid-template-columns: 1.05fr 0.95fr;
            position: relative;
            z-index: 1;
        }

        .auth-form-wrap { padding: 52px 58px; }

        .auth-hero {
            position: relative;
            color: #fff;
            background: radial-gradient(circle at 15% 20%, rgba(94, 171, 111, 0.42), transparent 46%),
                linear-gradient(145deg, #1a3c27, #246438 45%, #1d4d30 100%);
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 56px;
            isolation: isolate;
        }

        .auth-hero::after {
            content: '';
            position: absolute;
            inset: 0;
            background: url('{{ asset('logo/image-portal.png') }}') center/cover no-repeat;
            opacity: 0.26;
            z-index: 0;
        }

        .auth-hero::before {
            content: '';
            position: absolute;
            inset: 0;
            background: linear-gradient(180deg, rgba(20, 46, 28, 0.5), rgba(16, 36, 22, 0.28));
            z-index: 0;
        }

        .auth-hero-content {
            position: relative;
            z-index: 1;
            max-width: 540px;
            display: flex;
            flex-direction: column;
            align-items: center;
            text-align: center;
            gap: 12px;
            padding: 20px 22px;
           
        }

        .brand-mark {
            display: inline-flex;
            align-items: center;
            gap: 12px;
            padding: 10px 14px;
            background: rgba(255, 255, 255, 0.34);
            border-radius: 12px;
            border: 1px solid rgba(255, 255, 255, 0.24);
            margin-bottom: 6px;
            box-shadow: 0 18px 42px rgba(0, 0, 0, 0.26);
        }

        .auth-card label { font-weight: 600; color: #1f2d24; }

        .auth-form-wrap .form-label { margin-bottom: 4px; }
        .auth-form-wrap .mb-3 { margin-bottom: 0.65rem !important; }

        .form-control,
        .form-select,
        .select2-selection {
            border-radius: 10px !important;
            border: 1px solid #d5dfd7 !important;
            min-height: 46px;
            box-shadow: none !important;
        }

        .auth-form-wrap .select2-container .select2-selection--multiple {
            min-height: 42px;
            padding: 4px 8px;
        }

        .auth-form-wrap .select2-container .select2-selection__rendered {
            padding: 0 !important;
        }

        .auth-form-wrap .select2-container--default .select2-search--inline .select2-search__field {
            margin-top: 4px;
        }

        .auth-form-wrap .select2-container--default .select2-search--inline {
            display: none;
        }

        .select2-selection__rendered { padding: 6px 10px !important; }

        .btn-primary-auth {
            background: linear-gradient(115deg, #2e7a3f, #1f572d);
            border: none;
            border-radius: 12px;
            padding: 12px 16px;
            font-weight: 700;
            letter-spacing: 0.2px;
            color: #fff;
        }

        .hero-headline { font-size: 32px; line-height: 1.24; text-shadow: 0 10px 28px rgba(0, 0, 0, 0.38); }
        .hero-body { font-size: 20px; color: #f0f6f2; line-height: 1.68; text-shadow: 0 8px 20px rgba(0, 0, 0, 0.32); }

        .feature-list { list-style: none; padding: 0; margin: 10px 0 0; color: #f4f9f5; width: 100%; max-width: 460px; text-align: left; }
        .feature-list li { margin-bottom: 9px; font-size: 16px; display: flex; gap: 8px; align-items: flex-start; }
        .feature-dot { width: 9px; height: 9px; border-radius: 50%; background: #9bf3c8; margin-top: 7px; flex-shrink: 0; box-shadow: 0 0 0 4px rgba(155, 243, 200, 0.12); }

        @media (max-width: 992px) {
            .auth-card { grid-template-columns: 1fr; }
            .auth-hero { order: -1; padding: 40px 28px; }
            .auth-form-wrap { padding: 36px 26px; }
        }
    </style>

    <div class="auth-shell">
        <div class="auth-card">
            <div class="auth-form-wrap">
                <div class="d-flex align-items-center justify-content-between mb-4">
                    <div class="d-flex align-items-center gap-3">
                        <img src="{{ asset('logo/icon-portalcientifico.svg') }}" alt="Portal Científico" style="width: 46px; height: 46px;">
                        <div>
                            <div style="font-weight: 800; font-size: 24px; color: #1d3a24;">{{ __('Portal Científico') }}</div>
                            <div class="text-muted" style="font-size: 14px;">{{ __('Efetuar Registo') }}</div>
                        </div>
                    </div>
                </div>

                <form method="POST" action="{{ route('register') }}" novalidate id="registerForm" class="needs-validation">
                    @csrf
                    <input type="hidden" name="type" value="researcher">

                    <div class="mb-3">
                        <label for="entities" class="form-label">{{ __('Escolha a entidade') }}</label>
                        <select class="form-select form-control @error('entities') is-invalid @enderror" id="entities" name="entities[]" multiple data-placeholder required>
                            @foreach ($entitiesList as $entity)
                                <option value="{{ $entity->name }}" {{ in_array($entity->name, old('entities', [])) ? 'selected' : '' }}>
                                    {{ $entity->name }}
                                </option>
                            @endforeach
                        </select>
                        @error('entities')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>

                    <div class="mb-3" id="custom-entity-wrap" style="display: none;">
                        <label class="form-label" for="custom_entity">{{ __('Outra entidade') }}</label>
                        <input type="text" id="custom_entity" name="custom_entity" class="form-control @error('custom_entity') is-invalid @enderror" value="{{ old('custom_entity') }}" placeholder="Ex.: ISLA GAIA / CEOS.PP" >
                        @error('custom_entity')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>

                    <div class="mb-3">
                        <label class="form-label" for="name">{{ __('Nome') }}</label>
                        <input type="text" id="name" class="form-control @error('name') is-invalid @enderror" name="name" value="{{ old('name') }}" required autocomplete="name" placeholder="Ex.: Ana Silva">
                        @error('name')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>

                    <div class="mb-3">
                        <label class="form-label" for="email">{{ __('Email') }}</label>
                        <input type="email" id="email" class="form-control @error('email') is-invalid @enderror" name="email" value="{{ old('email') }}" required placeholder="Ex.: ana.silva@instituicao.pt">
                        @error('email')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>

                    <div class="mb-3">
                        <label class="form-label" for="ciencia_vitae">{{ __('Ciencia Vitae ID') }}</label>
                        <input type="text" id="ciencia_vitae" class="form-control @error('ciencia_vitae') is-invalid @enderror" name="ciencia_vitae" value="{{ old('ciencia_vitae') }}" placeholder="Ex.: DXX3-X46X-XEX8">
                        @error('ciencia_vitae')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>

                    <div class="mb-3">
                        <label class="form-label" for="password">{{ __('Password') }}</label>
                        <input type="password" id="password" class="form-control @error('password') is-invalid @enderror" name="password" required placeholder="••••••••">
                        @error('password')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>

                    <div class="mb-3">
                        <label class="form-label" for="password_confirmation">{{ __('Confirmar Password') }}</label>
                        <input type="password" id="password_confirmation" class="form-control" name="password_confirmation" required placeholder="••••••••">
                    </div>

                    <div class="d-grid mt-3">
                        <button type="submit" class="btn btn-primary-auth w-100" id="registerBtn">
                            <span class="btn-text">{{ __('Registar') }}</span>
                            <span class="btn-spinner" aria-hidden="true" style="display:none;"></span>
                        </button>
                    </div>

                    <div class="text-center mt-3 small">
                        <a href="{{ route('login') }}">{{ __('Já tem registo?') }}</a>
                    </div>
                </form>
            </div>

            <div class="auth-hero">
                <div class="auth-hero-content">
                    <div class="brand-mark">
                        <img src="{{ asset('logo/icon-portalcientifico.svg') }}" alt="Brand" style="width: 32px; height: 32px;">
                        <span class="fw-semibold">{{ __('Portal Científico') }}</span>
                    </div>
                    <h3 class="fw-bold hero-headline">{{ __('Junta-te à comunidade científica') }}</h3>
                    <p class="hero-body">{{ __('Registe-se para aceder à sua produção científica, projetos e relatórios num só lugar.') }}</p>
                    <ul class="feature-list">
                        <li><span class="feature-dot"></span><span><strong>{{ __('Perfis integrados') }}</strong> — {{ __('CiênciaVitae, ORCID e Scopus') }}</span></li>
                        <li><span class="feature-dot"></span><span><strong>{{ __('Visualização') }}</strong> — {{ __('Veja os seus projetos e atividades') }}</span></li>
                        <li><span class="feature-dot"></span><span><strong>{{ __('Relatórios') }}</strong> — {{ __('Exportação de relatórios e dados') }}</span></li>
                    </ul>
                </div>
            </div>
        </div>
    </div>

    <!-- inclui Select2 CSS/JS (CDN) e inicialização -->
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>

    <script>
        (function () {
            const form = document.getElementById('registerForm');
            const btn = document.getElementById('registerBtn');
            const spinner = btn.querySelector('.btn-spinner');
            const btnText = btn.querySelector('.btn-text');

            form?.addEventListener('submit', function () {
                btn.disabled = true;
                spinner.style.display = 'inline-block';
                btnText.textContent = '{{ __('A registar...') }}';
            });
        })();

        document.addEventListener('DOMContentLoaded', function () {
            $('#entities').select2({
                placeholder: $('#entities').data('placeholder') || "{{ __('Selecione uma ou mais entidades') }}",
                allowClear: true,
                width: '100%'
            });

            function toggleCustomEntity() {
                const selected = $('#entities').val() || [];
                const showCustom = selected.includes('OTHER');
                $('#custom-entity-wrap').toggle(showCustom);
                $('#custom_entity').prop('required', showCustom);
                if (!showCustom) {
                    $('#custom_entity').val('');
                }
            }

            toggleCustomEntity();
            $('#entities').on('change', toggleCustomEntity);
        });
    </script>
</x-app-layout>