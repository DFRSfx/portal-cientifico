<div id="reportModal" class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-slate-900/60 overflow-y-auto" aria-hidden="true" role="dialog" aria-labelledby="reportModalTitle" style="display:none;">
    <div class="relative w-full max-w-lg bg-white rounded-2xl shadow-2xl overflow-hidden border border-slate-200/80 my-8 transition-all" role="document">
        
        <!-- Header -->
        <header class="bg-gradient-to-r from-emerald-800 to-emerald-700 px-6 py-5 flex items-start justify-between gap-4 text-white">
            <div class="flex items-center gap-3.5 min-w-0">
                <div class="w-11 h-11 rounded-xl bg-white/15 flex items-center justify-center text-white shrink-0 shadow-inner">
                    <i class="fas fa-file-excel text-xl"></i>
                </div>
                <div class="min-w-0">
                    <h3 id="reportModalTitle" class="text-base sm:text-lg font-bold text-white tracking-tight leading-snug">
                        {{ __('Gerar relatório') }}
                    </h3>
                    <p class="text-xs text-emerald-100/90 mt-0.5 font-normal leading-relaxed">
                        {{ __('Selecione o ano para exportar o relatório detalhado em Excel.') }}
                    </p>
                </div>
            </div>
            <button type="button" class="text-white/70 hover:text-white hover:bg-white/15 rounded-lg p-1.5 transition-colors focus:outline-none cursor-pointer shrink-0" id="closeModalBtn" aria-label="{{ __('Close') }}">
                <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
                </svg>
            </button>
        </header>

        <!-- Body -->
        <div class="p-6">
            <form id="reportForm" action="{{ route('ceos-excel') }}" method="GET" aria-describedby="reportHelp">
                @method('GET')
                <input type="hidden" id="reportEntity" name="entity" value="">

                <!-- Quick Years Selection -->
                <div class="mb-5">
                    <label class="block text-xs font-bold uppercase tracking-wider text-slate-500 mb-2">
                        {{ __('Seleção rápida de ano') }}
                    </label>
                    <div class="flex items-center gap-2 flex-wrap" role="list">
                        @php
                            $current = (int) now()->format('Y');
                        @endphp
                        @for($i = 0; $i < 5; $i++)
                            @php $y = $current - $i; @endphp
                            <button type="button" 
                                    class="vc-year-btn px-3.5 py-1.5 rounded-lg text-xs font-semibold border transition-all cursor-pointer bg-slate-50 hover:bg-emerald-50 text-slate-700 hover:text-emerald-800 border-slate-200/90 hover:border-emerald-200" 
                                    data-year="{{ $y }}" 
                                    aria-pressed="false">
                                {{ $y }}
                            </button>
                        @endfor
                    </div>
                </div>

                <!-- Manual Year Input -->
                <div class="mb-5">
                    <label for="yearPicker" class="block text-xs font-bold uppercase tracking-wider text-slate-700 mb-1.5">
                        {{ __('Ano pretendido') }} <span class="text-rose-500">*</span>
                    </label>
                    <div class="relative">
                        <div class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none text-slate-400">
                            <i class="fas fa-calendar-alt text-sm"></i>
                        </div>
                        <input type="number" 
                               class="w-full pl-10 pr-4 py-2.5 rounded-xl border border-slate-200 bg-white text-sm font-medium text-slate-800 placeholder-slate-400 focus:outline-none focus:ring-2 focus:ring-emerald-600/20 focus:border-emerald-600 transition-all shadow-2xs" 
                               id="yearPicker" 
                               name="year" 
                               placeholder="{{ $current }}" 
                               min="1900" 
                               max="2099" 
                               required>
                    </div>
                </div>

                <!-- Info Box -->
                <div class="flex items-start gap-2.5 p-3 rounded-xl bg-slate-50 border border-slate-200/80 text-xs text-slate-600 leading-relaxed mb-6">
                    <i class="fas fa-shield-halved text-emerald-700 text-sm mt-0.5 shrink-0"></i>
                    <span>{{ __('O relatório é gerado em formato Excel protegido (.xlsx) com a informação científica consolidada da entidade selecionada.') }}</span>
                </div>

                <!-- Footer -->
                <div class="border-t border-slate-100 pt-4 -mx-6 px-6 bg-slate-50/50 flex items-center justify-end gap-2.5">
                    <button type="button" 
                            class="px-4 py-2 text-xs sm:text-sm font-semibold text-slate-600 hover:text-slate-800 hover:bg-slate-200/60 rounded-xl transition-colors cursor-pointer border-0 bg-transparent" 
                            id="cancelBtn">
                        {{ __('Cancelar') }}
                    </button>
                    <button type="submit" 
                            id="generateBtn" 
                            class="px-5 py-2 text-xs sm:text-sm font-semibold text-white bg-emerald-700 hover:bg-emerald-800 active:scale-[0.98] rounded-xl shadow-xs transition-all flex items-center gap-2 cursor-pointer border-0 disabled:opacity-50 disabled:cursor-not-allowed">
                        <span id="ctaSpinner" class="w-4 h-4 border-2 border-white/30 border-t-white rounded-full animate-spin" style="display:none;" aria-hidden="true"></span>
                        <i class="fas fa-download text-xs" id="ctaIcon"></i>
                        <span id="ctaText">{{ __('Gerar Relatório') }}</span>
                    </button>
                </div>
            </form>
        </div>

    </div>

    <script data-navigate-once>
        (function () {
            const modal = document.getElementById('reportModal');
            const closeBtn = document.getElementById('closeModalBtn');
            const cancelBtn = document.getElementById('cancelBtn');
            const quickBtns = modal?.querySelectorAll('.vc-year-btn') || [];
            const yearInput = document.getElementById('yearPicker');
            const form = document.getElementById('reportForm');
            const generateBtn = document.getElementById('generateBtn');
            const ctaSpinner = document.getElementById('ctaSpinner');
            const ctaIcon = document.getElementById('ctaIcon');
            const ctaText = document.getElementById('ctaText');

            function syncYearBtnState(selectedYear) {
                quickBtns.forEach(b => {
                    const isMatch = b.getAttribute('data-year') === String(selectedYear);
                    b.setAttribute('aria-pressed', isMatch ? 'true' : 'false');
                    if (isMatch) {
                        b.classList.add('bg-emerald-700', 'text-white', 'border-emerald-700', 'shadow-xs');
                        b.classList.remove('bg-slate-50', 'text-slate-700', 'border-slate-200/90');
                    } else {
                        b.classList.remove('bg-emerald-700', 'text-white', 'border-emerald-700', 'shadow-xs');
                        b.classList.add('bg-slate-50', 'text-slate-700', 'border-slate-200/90');
                    }
                });
            }

            function openModal() {
                modal.style.display = 'flex';
                modal.setAttribute('aria-hidden', 'false');
                setTimeout(() => yearInput?.focus(), 120);
            }

            function closeModal() {
                modal.style.display = 'none';
                modal.setAttribute('aria-hidden', 'true');
            }

            closeBtn?.addEventListener('click', closeModal);
            cancelBtn?.addEventListener('click', closeModal);
            document.addEventListener('keydown', function (e) {
                if (e.key === 'Escape' && modal && modal.style.display === 'flex') {
                    closeModal();
                }
            });

            quickBtns.forEach(b => {
                b.addEventListener('click', function () {
                    const y = this.getAttribute('data-year');
                    if (yearInput) {
                        yearInput.value = y;
                    }
                    syncYearBtnState(y);
                });
            });

            yearInput?.addEventListener('input', function () {
                syncYearBtnState(this.value);
            });

            form?.addEventListener('submit', async function (e) {
                e.preventDefault();

                generateBtn.disabled = true;
                if (ctaSpinner) ctaSpinner.style.display = 'inline-block';
                if (ctaIcon) ctaIcon.style.display = 'none';
                if (ctaText) ctaText.textContent = '{{ __("A gerar...") }}';

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
                    const cd = resp.headers.get('Content-Disposition') || '';
                    let filename = 'relatorio.xlsx';
                    const m = cd.match(/filename\*?=(?:UTF-8'')?["']?([^;"']+)/i);
                    if (m && m[1]) filename = decodeURIComponent(m[1]);

                    const blobUrl = window.URL.createObjectURL(blob);
                    const a = document.createElement('a');
                    a.href = blobUrl;
                    a.download = filename;
                    document.body.appendChild(a);
                    a.click();
                    a.remove();
                    window.URL.revokeObjectURL(blobUrl);

                    closeModal();
                } catch (err) {
                    console.error(err);
                    alert('{{ __("Erro ao gerar o relatório. Tente novamente.") }}');
                } finally {
                    generateBtn.disabled = false;
                    if (ctaSpinner) ctaSpinner.style.display = 'none';
                    if (ctaIcon) ctaIcon.style.display = 'inline-block';
                    if (ctaText) ctaText.textContent = '{{ __("Gerar Relatório") }}';
                }
            });

            window.openReportModal = openModal;
            window.closeReportModal = closeModal;
        })();
    </script>
</div>