<x-app-layout>
    <x-slot:title>
        {{ __('Estatísticas') }}
    </x-slot>

    @pushOnce('header-scripts')
        <script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf/2.5.1/jspdf.umd.min.js"></script>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/4.4.0/chart.umd.js" integrity="sha512-6HrPqAvK+lZElIZ4mZ64fyxIBTsaX5zAFZg2V/2WT+iKPrFzTzvx6QAsLW2OaLwobhMYBog/+bvmIEEGXi0p1w==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
    @endPushOnce

    @pushOnce('scripts')
        <script src="{{ asset('javascript/authors/updateProfile2.js') }}" defer></script>
    @endPushOnce

    @php
        $currentYear = (int) date('Y');
        $resolvedOldestYear = $oldestYear ?: ($currentYear - 4);
        $resolvedNewestYear = $newestYear ?: $currentYear;
        $hasData = $numberOfPublications > 0;
    @endphp

    <section class="py-8 bg-slate-50/50 min-h-[calc(100vh-160px)]">
        <div class="max-w-7xl mx-auto px-4 sm:px-6">

            {{-- Page Header & Actions --}}
            <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 mb-6">
                <div>
                    <h1 class="text-xl sm:text-2xl font-bold text-slate-900 tracking-tight flex items-center gap-2.5">
                        <span class="w-2.5 h-6 bg-emerald-700 rounded-full inline-block"></span>
                        {{ __('Estatísticas Globais') }}
                    </h1>
                    <p class="text-xs sm:text-sm text-slate-500 mt-1">
                        {{ __('Métricas agregadas da produção científica, eventos e utilizadores da instituição.') }}
                    </p>
                </div>

                <div class="flex flex-wrap items-center gap-2.5">
                    <form action="{{ route('pub.export') }}" class="m-0">
                        <button type="submit"
                            class="inline-flex items-center gap-2 px-3.5 py-2 text-xs sm:text-sm font-semibold text-slate-700 bg-white hover:bg-slate-50 border border-slate-200 rounded-xl shadow-2xs transition-colors cursor-pointer">
                            <i class="fas fa-file-excel text-emerald-700"></i>
                            <span>{{ __('Exportar Excel') }}</span>
                        </button>
                    </form>

                    @if (auth()->user()->type === 'administrative')
                        <button id="update-all-authors" type="button"
                            class="inline-flex items-center gap-2 px-3.5 py-2 text-xs sm:text-sm font-semibold text-white bg-emerald-700 hover:bg-emerald-800 rounded-xl shadow-xs transition-all cursor-pointer">
                            <i class="fas fa-arrows-rotate text-xs"></i>
                            <span>{{ __('Atualizar Todos os Autores') }}</span>
                        </button>
                    @endif
                </div>
            </div>

            {{-- Async Job Status Banner (Admin) --}}
            @if (auth()->user()->type === 'administrative')
                <div id="update-all-message-container" class="mb-4"></div>
                <div id="update-all-status" class="mb-6 p-4 rounded-2xl bg-white border border-emerald-200 shadow-xs" style="display:none;">
                    <div class="flex flex-wrap items-center justify-between gap-3 text-xs sm:text-sm text-slate-700">
                        <div class="flex items-center gap-2">
                            <i class="fas fa-spinner fa-spin text-emerald-700"></i>
                            <span class="font-bold text-slate-900">{{ __('Estado:') }}</span>
                            <span id="update-all-status-text" class="font-medium text-emerald-800">-</span>
                        </div>
                        <div class="flex items-center gap-2">
                            <span class="font-bold text-slate-900">{{ __('Progresso:') }}</span>
                            <span id="update-all-progress-text" class="font-medium text-slate-800">-</span>
                        </div>
                        <div class="flex items-center gap-2">
                            <span class="font-bold text-slate-900">{{ __('Tempo estimado:') }}</span>
                            <span id="update-all-eta-text" class="font-medium text-slate-800">-</span>
                        </div>
                    </div>
                    <div class="mt-3 pt-3 border-t border-slate-100 text-xs text-rose-700" id="update-all-errors-wrapper" style="display:none;">
                        <span class="font-bold block mb-1">{{ __('Erros registados:') }}</span>
                        <ul id="update-all-errors" class="list-disc pl-5 space-y-0.5 mb-0"></ul>
                    </div>
                </div>
            @endif

            {{-- Stat Cards Grid --}}
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4 mb-8">
                {{-- Publicações --}}
                <div class="bg-white border border-slate-200/80 rounded-2xl p-5 shadow-xs flex items-center gap-4 transition-all hover:border-slate-300">
                    <div class="w-12 h-12 rounded-2xl bg-emerald-50 text-emerald-700 flex items-center justify-center text-lg shrink-0 border border-emerald-100">
                        <i class="fas fa-newspaper"></i>
                    </div>
                    <div class="min-w-0">
                        <p class="text-xs font-bold uppercase tracking-wider text-slate-400 mb-0.5 truncate">{{ __('Publicações') }}</p>
                        <p class="text-2xl font-black text-slate-900 leading-tight mb-0" id="count">{{ $numberOfPublications }}</p>
                    </div>
                </div>

                {{-- Média de Publicações --}}
                <div class="bg-white border border-slate-200/80 rounded-2xl p-5 shadow-xs flex items-center gap-4 transition-all hover:border-slate-300">
                    <div class="w-12 h-12 rounded-2xl bg-blue-50 text-blue-700 flex items-center justify-center text-lg shrink-0 border border-blue-100">
                        <i class="fas fa-chart-line"></i>
                    </div>
                    <div class="min-w-0">
                        <p class="text-xs font-bold uppercase tracking-wider text-slate-400 mb-0.5 truncate">{{ __('Média Anual') }}</p>
                        <p class="text-2xl font-black text-slate-900 leading-tight mb-0">{{ $average }}</p>
                    </div>
                </div>

                {{-- Utilizadores Ativos --}}
                <div class="bg-white border border-slate-200/80 rounded-2xl p-5 shadow-xs flex items-center gap-4 transition-all hover:border-slate-300">
                    <div class="w-12 h-12 rounded-2xl bg-teal-50 text-teal-700 flex items-center justify-center text-lg shrink-0 border border-teal-100">
                        <i class="fas fa-user-check"></i>
                    </div>
                    <div class="min-w-0">
                        <p class="text-xs font-bold uppercase tracking-wider text-slate-400 mb-0.5 truncate">{{ __('Utilizadores Ativos') }}</p>
                        <p class="text-2xl font-black text-slate-900 leading-tight mb-0">{{ $activeUsersCount ?? 0 }}</p>
                    </div>
                </div>

                {{-- Pendentes / Por Aprovar --}}
                <div class="bg-white border border-slate-200/80 rounded-2xl p-5 shadow-xs flex items-center gap-4 transition-all hover:border-slate-300">
                    <div class="w-12 h-12 rounded-2xl bg-amber-50 text-amber-700 flex items-center justify-center text-lg shrink-0 border border-amber-100">
                        <i class="fas fa-user-clock"></i>
                    </div>
                    <div class="min-w-0">
                        <p class="text-xs font-bold uppercase tracking-wider text-slate-400 mb-0.5 truncate">{{ __('Pendentes / Por Aprovar') }}</p>
                        <p class="text-2xl font-black text-slate-900 leading-tight mb-0">{{ ($pendingUsersCount ?? 0) + ($pendingVerificationCount ?? 0) }}</p>
                    </div>
                </div>
            </div>

            {{-- Main Chart & Filters Section --}}
            @if ($hasData)
                <div class="grid grid-cols-1 lg:grid-cols-12 gap-6 items-start">

                    {{-- Filters Sidebar (4 cols) --}}
                    <div class="lg:col-span-4 space-y-4">
                        <div class="bg-white border border-slate-200/80 rounded-2xl shadow-xs overflow-hidden">
                            <div class="p-4 sm:p-5 border-b border-slate-100 bg-slate-50/40">
                                <h2 class="text-xs sm:text-sm font-bold uppercase tracking-wider text-slate-800 m-0 flex items-center gap-2">
                                    <i class="fas fa-sliders text-emerald-700"></i>
                                    {{ __('Filtros do Gráfico') }}
                                </h2>
                            </div>

                            <div class="p-5 space-y-4">
                                {{-- Tipo de Gráfico --}}
                                <div>
                                    <label for="chartType" class="block text-xs font-bold uppercase tracking-wider text-slate-700 mb-1.5">
                                        {{ __('Tipo de Gráfico') }}
                                    </label>
                                    <select name="chartType" id="chartType" onchange="updateChartType()"
                                        class="w-full px-3.5 py-2.5 text-xs sm:text-sm bg-white border border-slate-200 rounded-xl shadow-2xs focus:outline-none focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500 transition-all font-medium text-slate-800">
                                        <option value="line">{{ __('Linha') }}</option>
                                        <option value="bar">{{ __('Barras') }}</option>
                                    </select>
                                </div>

                                {{-- Informação / Fonte --}}
                                <div>
                                    <label for="chartInformation" class="block text-xs font-bold uppercase tracking-wider text-slate-700 mb-1.5">
                                        {{ __('Conjunto de Dados') }}
                                    </label>
                                    <select name="chartInformation" id="chartInformation" onchange="requestInformation()"
                                        class="w-full px-3.5 py-2.5 text-xs sm:text-sm bg-white border border-slate-200 rounded-xl shadow-2xs focus:outline-none focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500 transition-all font-medium text-slate-800">
                                        <option value="publications">{{ __('Publicações') }}</option>
                                        <option value="events">{{ __('Eventos') }}</option>
                                    </select>
                                </div>

                                {{-- Agrupamento --}}
                                <div>
                                    <label for="statsType" class="block text-xs font-bold uppercase tracking-wider text-slate-700 mb-1.5">
                                        {{ __('Agrupamento') }}
                                    </label>
                                    <select name="statsType" id="statsType" onchange="requestInformation()"
                                        class="w-full px-3.5 py-2.5 text-xs sm:text-sm bg-white border border-slate-200 rounded-xl shadow-2xs focus:outline-none focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500 transition-all font-medium text-slate-800">
                                        <option value="global">{{ __('Global (Total por Ano)') }}</option>
                                        <option value="type">{{ __('Desagregado por Tipo') }}</option>
                                    </select>
                                </div>

                                {{-- Intervalo de Anos --}}
                                <div>
                                    <label class="block text-xs font-bold uppercase tracking-wider text-slate-700 mb-1.5">
                                        {{ __('Intervalo de Anos') }}
                                    </label>
                                    <div class="grid grid-cols-2 gap-2">
                                        <div>
                                            <span class="block text-[11px] text-slate-500 mb-1">{{ __('Desde:') }}</span>
                                            <input type="number" id="startYearInput" min="1970" max="{{ $currentYear }}" value="{{ $resolvedOldestYear }}"
                                                class="w-full px-3 py-2 text-xs sm:text-sm bg-white border border-slate-200 rounded-xl shadow-2xs focus:outline-none focus:ring-2 focus:ring-emerald-500 text-slate-800 font-semibold"
                                                onchange="onYearRangeChange()">
                                        </div>
                                        <div>
                                            <span class="block text-[11px] text-slate-500 mb-1">{{ __('Até:') }}</span>
                                            <input type="number" id="endYearInput" min="1970" max="{{ $currentYear }}" value="{{ $resolvedNewestYear }}"
                                                class="w-full px-3 py-2 text-xs sm:text-sm bg-white border border-slate-200 rounded-xl shadow-2xs focus:outline-none focus:ring-2 focus:ring-emerald-500 text-slate-800 font-semibold"
                                                onchange="onYearRangeChange()">
                                        </div>
                                    </div>
                                    <span id="startYear" class="hidden">{{ $resolvedOldestYear }}</span>
                                    <span id="endYear" class="hidden">{{ $resolvedNewestYear }}</span>
                                </div>

                                {{-- Reset Button --}}
                                <button type="button" onclick="resetFilters()"
                                    class="w-full py-2 px-3 text-xs font-semibold text-slate-600 hover:text-slate-900 bg-slate-100 hover:bg-slate-200 rounded-xl transition-colors cursor-pointer text-center">
                                    <i class="fas fa-rotate-left mr-1.5 text-xs"></i>
                                    {{ __('Repor Filtros') }}
                                </button>
                            </div>
                        </div>
                    </div>

                    {{-- Chart Panel (8 cols) --}}
                    <div class="lg:col-span-8" id="reportPage">
                        <div class="bg-white border border-slate-200/80 rounded-2xl shadow-xs overflow-hidden">
                            <div class="p-4 sm:p-5 border-b border-slate-100 flex flex-col sm:flex-row sm:items-center justify-between gap-3 bg-slate-50/40">
                                <h2 class="text-xs sm:text-sm font-bold uppercase tracking-wider text-slate-800 m-0 flex items-center gap-2">
                                    <i class="fas fa-chart-simple text-emerald-700"></i>
                                    {{ __('Evolução Temporal') }}
                                </h2>

                                <div class="flex items-center gap-2">
                                    <button type="button" id="downloadPdf"
                                        class="inline-flex items-center gap-1.5 px-3 py-1.5 text-xs font-semibold text-slate-700 bg-white hover:bg-slate-50 border border-slate-200 rounded-xl shadow-2xs transition-colors cursor-pointer"
                                        title="{{ __('Descarregar em formato PDF') }}">
                                        <i class="fas fa-file-pdf text-emerald-700 text-xs"></i>
                                        <span>PDF</span>
                                    </button>

                                    <a id="downloadImg" download="grafico_estatisticas.png" href="#"
                                        class="inline-flex items-center gap-1.5 px-3 py-1.5 text-xs font-semibold text-slate-700 bg-white hover:bg-slate-50 border border-slate-200 rounded-xl shadow-2xs transition-colors cursor-pointer"
                                        title="{{ __('Descarregar imagem PNG') }}">
                                        <i class="fas fa-image text-emerald-700 text-xs"></i>
                                        <span>PNG</span>
                                    </a>
                                </div>
                            </div>

                            <div class="p-4 sm:p-6">
                                <div class="relative w-full min-h-[360px] sm:min-h-[420px] flex items-center justify-center">
                                    <canvas id="myChart" class="w-full h-full"></canvas>
                                </div>
                            </div>
                        </div>
                    </div>

                </div>
            @else
                {{-- Empty state when no publications exist --}}
                <div class="bg-white border border-slate-200/80 rounded-2xl p-10 sm:p-14 text-center shadow-xs">
                    <div class="w-16 h-16 rounded-full bg-emerald-50 text-emerald-700 flex items-center justify-center mx-auto mb-4 text-2xl border border-emerald-100">
                        <i class="fas fa-chart-pie"></i>
                    </div>
                    <h3 class="text-lg font-bold text-slate-900 mb-1">
                        {{ __('Ainda não existem dados suficientes') }}
                    </h3>
                    <p class="text-xs sm:text-sm text-slate-500 max-w-md mx-auto mb-6">
                        {{ __('Para visualizar gráficos e métricas de evolução temporal, importe autores e sincronize a produção científica através do Ciência Vitae.') }}
                    </p>
                    <a href="{{ route('user.create') }}"
                        class="inline-flex items-center gap-2 px-5 py-2.5 text-xs sm:text-sm font-semibold text-white bg-emerald-700 hover:bg-emerald-800 rounded-xl shadow-xs transition-all cursor-pointer">
                        <i class="fas fa-user-plus text-xs"></i>
                        <span>{{ __('Registar / Importar Autores') }}</span>
                    </a>
                </div>
            @endif

        </div>
    </section>

    @if ($hasData)
        <script>
            const defaultOldestYear = {{ $resolvedOldestYear }};
            const defaultNewestYear = {{ $resolvedNewestYear }};
            const dataSetInformation = [];

            function onYearRangeChange() {
                const startInput = document.getElementById("startYearInput");
                const endInput = document.getElementById("endYearInput");
                const startSpan = document.getElementById("startYear");
                const endSpan = document.getElementById("endYear");

                if (!startInput || !endInput) return;

                let start = parseInt(startInput.value) || defaultOldestYear;
                let end = parseInt(endInput.value) || defaultNewestYear;

                if (start > end) {
                    start = end;
                    startInput.value = start;
                }

                if (startSpan) startSpan.innerText = start;
                if (endSpan) endSpan.innerText = end;

                requestInformation();
            }

            function resetFilters() {
                const startInput = document.getElementById("startYearInput");
                const endInput = document.getElementById("endYearInput");
                const startSpan = document.getElementById("startYear");
                const endSpan = document.getElementById("endYear");
                const chartType = document.getElementById("chartType");
                const chartInfo = document.getElementById("chartInformation");
                const statsType = document.getElementById("statsType");

                if (startInput) startInput.value = defaultOldestYear;
                if (endInput) endInput.value = defaultNewestYear;
                if (startSpan) startSpan.innerText = defaultOldestYear;
                if (endSpan) endSpan.innerText = defaultNewestYear;
                if (chartType) chartType.value = "line";
                if (chartInfo) chartInfo.value = "publications";
                if (statsType) statsType.value = "global";

                requestInformation();
            }

            async function requestInformation() {
                const infoElem = document.getElementById("chartInformation");
                const statsElem = document.getElementById("statsType");
                const startSpan = document.getElementById("startYear");
                const endSpan = document.getElementById("endYear");

                if (!infoElem || !statsElem) return;

                const type = infoElem.value;
                const statsType = statsElem.value;
                const startYear = startSpan ? startSpan.innerText : defaultOldestYear;
                const endYear = endSpan ? endSpan.innerText : defaultNewestYear;

                const url = (type === "events") ? "/statistics/events" : "/statistics/publications";

                try {
                    const response = await fetch(url + "?statsType=" + statsType + "&startYear=" + startYear + "&endYear=" + endYear);
                    if (!response.ok) {
                        throw new Error('Network response was not ok');
                    }
                    const data = await response.json();
                    changeDataset(data.data || []);
                } catch (error) {
                    console.error("Erro ao obter estatísticas:", error);
                }
            }

            function getYearsToShow(array) {
                if (!array || array.length === 0) {
                    const cur = new Date().getFullYear();
                    return [cur - 4, cur - 3, cur - 2, cur - 1, cur].map(String);
                }

                const firstItem = array[0];
                const lastItem = array[array.length - 1];

                const initialYear = firstItem && firstItem.year ? parseInt(firstItem.year) : defaultOldestYear;
                const lastYear = lastItem && lastItem.year ? parseInt(lastItem.year) : defaultNewestYear;

                if (isNaN(initialYear) || isNaN(lastYear)) {
                    return [new Date().getFullYear().toString()];
                }

                const minYear = Math.min(initialYear, lastYear);
                const maxYear = Math.max(initialYear, lastYear);
                const arrayToReturn = [];

                for (let y = minYear; y <= maxYear; y++) {
                    arrayToReturn.push(y.toString());
                }

                return arrayToReturn;
            }

            const chartColorPalette = [
                { border: '#047857', bg: 'rgba(4, 120, 87, 0.15)' },
                { border: '#0284c7', bg: 'rgba(2, 132, 199, 0.15)' },
                { border: '#f59e0b', bg: 'rgba(245, 158, 11, 0.15)' },
                { border: '#8b5cf6', bg: 'rgba(139, 92, 246, 0.15)' },
                { border: '#ec4899', bg: 'rgba(236, 72, 153, 0.15)' },
                { border: '#10b981', bg: 'rgba(16, 185, 129, 0.15)' },
                { border: '#64748b', bg: 'rgba(100, 116, 139, 0.15)' },
            ];

            function changeDataset(array) {
                const dataSetPosition = {};
                dataSetInformation.length = 0;

                const typeElem = document.getElementById("chartInformation");
                const type = typeElem ? typeElem.value : "publications";
                const baseType = (type === "events") ? "{{ __('Eventos') }}" : "{{ __('Publicações') }}";

                if (array && array.length > 0) {
                    for (let i = 0; i < array.length; i++) {
                        const label = array[i]["type"] || baseType;
                        const labelWasChecked = dataSetPosition[label];

                        if (labelWasChecked === undefined) {
                            const colorIndex = dataSetInformation.length % chartColorPalette.length;
                            const palette = chartColorPalette[colorIndex];

                            const newDataset = {
                                label: label,
                                data: [{ x: array[i]["year"], y: array[i]["count"] }],
                                borderColor: palette.border,
                                backgroundColor: palette.bg,
                                tension: 0.25,
                                borderWidth: 2,
                                fill: true,
                                pointBackgroundColor: palette.border,
                                pointRadius: 4,
                                pointHoverRadius: 6,
                            };

                            dataSetInformation.push(newDataset);
                            dataSetPosition[label] = dataSetInformation.length - 1;
                        } else {
                            dataSetInformation[labelWasChecked].data.push({
                                x: array[i]["year"],
                                y: array[i]["count"]
                            });
                        }
                    }
                }

                const data = {
                    labels: getYearsToShow(array),
                    datasets: dataSetInformation
                };

                updateChartData(data);
            }

            function updateChartType() {
                const canvas = document.getElementById('myChart');
                if (!canvas) return;
                const oldChartInstance = Chart.getChart(canvas);
                if (!oldChartInstance) return;

                const oldChartData = oldChartInstance.data;
                const selectedType = document.getElementById("chartType").value;

                oldChartInstance.destroy();

                new Chart(canvas, {
                    type: selectedType,
                    data: oldChartData,
                    options: getChartOptions()
                });
            }

            function getChartOptions() {
                return {
                    animation: { duration: 300 },
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        legend: {
                            position: 'top',
                            labels: {
                                boxWidth: 12,
                                boxHeight: 12,
                                usePointStyle: true,
                                font: { size: 12, weight: '600' }
                            }
                        },
                        tooltip: {
                            backgroundColor: '#0f172a',
                            titleFont: { size: 13, weight: 'bold' },
                            bodyFont: { size: 12 },
                            padding: 10,
                            cornerRadius: 8
                        }
                    },
                    scales: {
                        x: {
                            stacked: false,
                            grid: { color: '#f1f5f9' },
                            ticks: { font: { size: 11, weight: '500' }, color: '#64748b' }
                        },
                        y: {
                            beginAtZero: true,
                            stacked: false,
                            grid: { color: '#f1f5f9' },
                            ticks: { font: { size: 11, weight: '500' }, color: '#64748b', precision: 0 }
                        }
                    }
                };
            }

            function updateChartData(data) {
                const canvas = document.getElementById('myChart');
                if (!canvas) return;

                const chart = Chart.getChart(canvas);
                if (chart) chart.destroy();

                const chartTypeElem = document.getElementById("chartType");
                const selectedType = chartTypeElem ? chartTypeElem.value : "line";

                new Chart(canvas, {
                    type: selectedType,
                    data: data,
                    options: getChartOptions()
                });
            }

            document.addEventListener('DOMContentLoaded', function() {
                requestInformation();

                // PDF Download
                const pdfBtn = document.getElementById('downloadPdf');
                if (pdfBtn) {
                    pdfBtn.addEventListener('click', function(e) {
                        e.preventDefault();
                        const canvas = document.getElementById('myChart');
                        if (!canvas) return;

                        const imgData = canvas.toDataURL('image/png');
                        const jsPDFClass = window.jspdf ? window.jspdf.jsPDF : (window.jsPDF || null);
                        if (!jsPDFClass) {
                            alert('Biblioteca jsPDF não carregada.');
                            return;
                        }

                        const pdf = new jsPDFClass('l', 'mm', 'a4');
                        const imgProps = pdf.getImageProperties(imgData);
                        const pdfWidth = pdf.internal.pageSize.getWidth();
                        const pdfHeight = (imgProps.height * pdfWidth) / imgProps.width;

                        pdf.text('Portal Científico - Estatísticas', 14, 15);
                        pdf.addImage(imgData, 'PNG', 10, 25, pdfWidth - 20, Math.min(pdfHeight, 160));
                        pdf.save('estatisticas_portal_cientifico.pdf');
                    });
                }

                // PNG Download
                const downloadImgBtn = document.getElementById('downloadImg');
                if (downloadImgBtn) {
                    downloadImgBtn.addEventListener('click', function() {
                        const canvas = document.getElementById('myChart');
                        if (!canvas) return;
                        this.href = canvas.toDataURL('image/png');
                    });
                }
            });
        </script>
    @endif

</x-app-layout>