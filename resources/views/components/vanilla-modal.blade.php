<div id="reportModal" class="vc-modal" aria-hidden="true" role="dialog" aria-labelledby="reportModalTitle" style="display:none;">
    <div class="vc-modal-backdrop"></div>
    <div class="vc-modal-dialog" role="document">
        <div class="vc-modal-card">
            <button type="button" class="vc-close" id="closeModalBtn" aria-label="{{ __('Close') }}">&times;</button>

            <header class="vc-modal-header">
                <div class="vc-header-left">
                    <!-- icon -->
                    <svg width="44" height="44" viewBox="0 0 24 24" fill="none" aria-hidden="true">
                        <rect width="24" height="24" rx="6" fill="#2f6b2f"/>
                        <path d="M7 12h10M7 8h10M7 16h6" stroke="#fff" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/>
                    </svg>
                </div>
                <div class="vc-header-body">
                    <h5 id="reportModalTitle">{{ __('Gerar relatório de CEOS.PP') }}</h5>
                    <p class="vc-subtitle">{{ __('Selecione o ano para gerar um relatório protegido em Excel.') }}</p>
                </div>
            </header>

            <div class="vc-modal-body">
                <form id="reportForm" action="{{ route('ceos-excel') }}" method="GET" class="vc-form" aria-describedby="reportHelp">
                    @method('GET')
                    <p id="reportHelp" class="vc-help small">{{ __('Escolha um dos anos rápidos ou digite manualmente. O relatório é gerado em Excel protegido.') }}</p>

                    <div class="vc-quickyears" role="list">
                        @php
                            $current = (int) now()->format('Y');
                        @endphp
                        @for($i = 0; $i < 5; $i++)
                            <button type="button" class="vc-year-btn" data-year="{{ $current - $i }}" aria-pressed="false">{{ $current - $i }}</button>
                        @endfor
                    </div>

                    <div class="vc-input-row">
                        <label for="yearPicker" class="form-label"> {{ __('Ano') }} </label>
                        <input type="number" class="form-control" id="yearPicker" name="year" placeholder="2025" min="1900" max="2099" required>
                    </div>

                    <div class="vc-footer">
                        <button type="submit" id="generateBtn" class="vc-cta">
                            <span id="ctaText">{{ __('Gerar Reporte') }}</span>
                            <span id="ctaSpinner" class="vc-spinner" aria-hidden="true" style="display:none;"></span>
                        </button>
                        <button type="button" class="vc-secondary" id="cancelBtn">{{ __('Cancelar') }}</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <style>
        /* Minimal styles scoped to the component */
        .vc-modal { position: fixed; inset: 0; z-index: 1050; display:flex; align-items:center; justify-content:center; }
        .vc-modal-backdrop { position:absolute; inset:0; background:rgba(0,0,0,0.45); backdrop-filter: blur(2px); }
        .vc-modal-dialog { position:relative; z-index:2; width:100%; max-width:640px; padding:1rem; }
        .vc-modal-card { background:#fff; border-radius:12px; overflow:hidden; box-shadow:0 8px 30px rgba(0,0,0,0.15); transform:translateY(10px); animation:vc-enter .18s ease-out both; }
        @keyframes vc-enter { from { opacity:0; transform:translateY(18px) scale(.99) } to { opacity:1; transform:translateY(0) scale(1) } }

        .vc-modal-header { display:flex; gap:1rem; align-items:center; padding:1.25rem; background:linear-gradient(90deg,#2f6b2f,#245022); color:#fff; }
        .vc-header-left svg { border-radius:8px; }
        .vc-header-body h5 { margin:0; font-size:1.125rem; font-weight:600; }
        .vc-subtitle { margin:0; opacity:.9; font-size:.875rem; color:rgba(255,255,255,.95) }

        .vc-close { position:absolute; right:.75rem; top:.5rem; background:transparent; border:0; color:#fff; font-size:1.5rem; line-height:1; cursor:pointer; }

        .vc-modal-body { padding:1.25rem; }
        .vc-help { margin-bottom:.5rem; color:#666; }

        .vc-quickyears { display:flex; gap:.5rem; flex-wrap:wrap; margin-bottom:1rem; }
        .vc-year-btn { background:#f1f6f1; border:1px solid #e6efe6; padding:.45rem .6rem; border-radius:8px; cursor:pointer; font-weight:600; color:#234a23; }
        .vc-year-btn[aria-pressed="true"] { background:#245022; color:#fff; box-shadow:0 6px 12px rgba(36,80,34,.18); transform:translateY(-1px); }

        .vc-input-row { margin-bottom:1rem; }
        .vc-cta { background:#2f6b2f; color:#fff; border:0; padding:.75rem 1rem; border-radius:8px; font-weight:700; display:inline-flex; gap:.5rem; align-items:center; }
        .vc-secondary { background:transparent; border:0; color:#666; padding:.5rem 1rem; cursor:pointer; }

        .vc-spinner { width:18px; height:18px; border:3px solid rgba(255,255,255,0.25); border-left-color:#fff; border-radius:50%; animation:spin .85s linear infinite; display:inline-block; }
        @keyframes spin { to { transform:rotate(360deg); } }
    </style>

    <script>
        (function () {
            const modal = document.getElementById('reportModal');
            const closeBtn = document.getElementById('closeModalBtn');
            const cancelBtn = document.getElementById('cancelBtn');
            const quickBtns = modal?.querySelectorAll('.vc-year-btn') || [];
            const yearInput = document.getElementById('yearPicker');
            const form = document.getElementById('reportForm');
            const generateBtn = document.getElementById('generateBtn');
            const ctaSpinner = document.getElementById('ctaSpinner');
            const ctaText = document.getElementById('ctaText');

            function openModal() {
                modal.style.display = 'flex';
                modal.setAttribute('aria-hidden', 'false');
                // focus on year input
                setTimeout(()=> yearInput.focus(), 160);
            }
            function closeModal() {
                modal.style.display = 'none';
                modal.setAttribute('aria-hidden', 'true');
            }

            // attach handlers
            closeBtn?.addEventListener('click', closeModal);
            cancelBtn?.addEventListener('click', closeModal);
            document.addEventListener('keydown', function (e) { if (e.key === 'Escape') closeModal(); });

            quickBtns.forEach(b => {
                b.addEventListener('click', function () {
                    // toggle pressed state
                    quickBtns.forEach(x => x.setAttribute('aria-pressed', 'false'));
                    this.setAttribute('aria-pressed', 'true');
                    const y = this.getAttribute('data-year');
                    yearInput.value = y;
                });
            });

            form?.addEventListener('submit', async function (e) {
                e.preventDefault();

                // show spinner and disable submit to avoid double post
                generateBtn.disabled = true;
                ctaSpinner.style.display = 'inline-block';
                ctaText.textContent = '{{ __("Generating...") }}';

                // build URL with query params (GET)
                const url = new URL(form.action, window.location.origin);
                const formData = new FormData(form);
                for (const [k, v] of formData.entries()) {
                    url.searchParams.set(k, v);
                }

                try {
                    const resp = await fetch(url.toString(), {
                        method: 'GET',
                        credentials: 'same-origin',
                        headers: {
                            'X-Requested-With': 'XMLHttpRequest'
                        }
                    });

                    if (!resp.ok) throw new Error('Network response not ok');

                    const blob = await resp.blob();
                    // tenta obter filename do header Content-Disposition
                    const cd = resp.headers.get('Content-Disposition') || '';
                    let filename = 'report.xlsx';
                    const m = cd.match(/filename\*?=(?:UTF-8'')?["']?([^;"']+)/i);
                    if (m && m[1]) filename = decodeURIComponent(m[1]);

                    // cria link de download e dispara
                    const blobUrl = window.URL.createObjectURL(blob);
                    const a = document.createElement('a');
                    a.href = blobUrl;
                    a.download = filename;
                    document.body.appendChild(a);
                    a.click();
                    a.remove();
                    window.URL.revokeObjectURL(blobUrl);

                    // opcional: fecha modal
                    // closeModal();
                } catch (err) {
                    console.error(err);
                    alert('{{ __("Erro ao gerar o relatório. Tenta novamente.") }}');
                } finally {
                    // sempre reativa o botão e esconde o spinner
                    generateBtn.disabled = false;
                    ctaSpinner.style.display = 'none';
                    ctaText.textContent = '{{ __("Gerar Reporte") }}';
                }
            });

            // expose openModal to global so you can call openReportModal() from other places
            window.openReportModal = openModal;
            window.closeReportModal = closeModal;
        })();
    </script>
</div>