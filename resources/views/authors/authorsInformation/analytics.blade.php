@extends('authors.show')

@section('author-information')
    @pushOnce('header-scripts')
        <script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf/2.5.1/jspdf.umd.min.js"></script>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/4.4.0/chart.umd.js"
            integrity="sha512-6HrPqAvK+lZElIZ4mZ64fyxIBTsaX5zAFZg2V/2WT+iKPrFzTzvx6QAsLW2OaLwobhMYBog/+bvmIEEGXi0p1w=="
            crossorigin="anonymous" referrerpolicy="no-referrer"></script>
    @endPushOnce

    <!-- Top KPI Highlights Grid -->
    <div class="grid grid-cols-2 lg:grid-cols-4 gap-3 sm:gap-4 mb-5">
        <!-- Total Outputs -->
        <div class="p-4 rounded-2xl bg-gradient-to-br from-white to-slate-50 border border-slate-200/80 shadow-2xs hover:shadow-xs transition-all">
            <div class="flex items-center justify-between mb-2">
                <span class="text-[11px] font-bold uppercase tracking-wider text-slate-500">{{ __('Publicações') }}</span>
                <span class="w-8 h-8 rounded-xl bg-emerald-50 text-emerald-700 flex items-center justify-center text-xs shadow-2xs">
                    <i class="fas fa-newspaper"></i>
                </span>
            </div>
            <div class="text-2xl sm:text-3xl font-extrabold text-slate-900 tracking-tight">{{ $totalOutputs ?? $author->output()->count() }}</div>
            <p class="text-[11px] text-slate-400 font-medium mt-1 mb-0 flex items-center gap-1">
                <i class="fas fa-check-circle text-emerald-600 text-[10px]"></i>
                <span>{{ __('Histórico consolidado') }}</span>
            </p>
        </div>

        <!-- Annual Average -->
        <div class="p-4 rounded-2xl bg-gradient-to-br from-white to-slate-50 border border-slate-200/80 shadow-2xs hover:shadow-xs transition-all">
            <div class="flex items-center justify-between mb-2">
                <span class="text-[11px] font-bold uppercase tracking-wider text-slate-500">{{ __('Média Anual') }}</span>
                <span class="w-8 h-8 rounded-xl bg-emerald-50 text-emerald-700 flex items-center justify-center text-xs shadow-2xs">
                    <i class="fas fa-calculator"></i>
                </span>
            </div>
            <div class="text-2xl sm:text-3xl font-extrabold text-emerald-800 tracking-tight">{{ $average }}</div>
            <p class="text-[11px] text-slate-400 font-medium mt-1 mb-0">
                {{ __('Publicações / ano ativo') }}
            </p>
        </div>

        <!-- Peak Year -->
        <div class="p-4 rounded-2xl bg-gradient-to-br from-white to-slate-50 border border-slate-200/80 shadow-2xs hover:shadow-xs transition-all">
            <div class="flex items-center justify-between mb-2">
                <span class="text-[11px] font-bold uppercase tracking-wider text-slate-500">{{ __('Ano de Pico') }}</span>
                <span class="w-8 h-8 rounded-xl bg-emerald-50 text-emerald-700 flex items-center justify-center text-xs shadow-2xs">
                    <i class="fas fa-arrow-trend-up"></i>
                </span>
            </div>
            <div class="flex items-baseline gap-2">
                <span class="text-2xl sm:text-3xl font-extrabold text-slate-900 tracking-tight">{{ $peakYear ?? '-' }}</span>
                @if(!empty($peakCount))
                    <span class="text-xs font-bold text-emerald-700 bg-emerald-50 px-2 py-0.5 rounded-md border border-emerald-200/80">
                        {{ $peakCount }} pub.
                    </span>
                @endif
            </div>
            <p class="text-[11px] text-slate-400 font-medium mt-1 mb-0">
                {{ __('Maior produção anual') }}
            </p>
        </div>

        <!-- Timeline Span -->
        <div class="p-4 rounded-2xl bg-gradient-to-br from-white to-slate-50 border border-slate-200/80 shadow-2xs hover:shadow-xs transition-all">
            <div class="flex items-center justify-between mb-2">
                <span class="text-[11px] font-bold uppercase tracking-wider text-slate-500">{{ __('Período Ativo') }}</span>
                <span class="w-8 h-8 rounded-xl bg-emerald-50 text-emerald-700 flex items-center justify-center text-xs shadow-2xs">
                    <i class="fas fa-timeline"></i>
                </span>
            </div>
            <div class="text-lg sm:text-xl font-extrabold text-slate-900 tracking-tight">
                {{ $firstYear ? ($firstYear . ' – ' . $lastYear) : '-' }}
            </div>
            <p class="text-[11px] text-slate-400 font-medium mt-1 mb-0">
                @if(!empty($firstYear) && !empty($lastYear))
                    {{ (int)$lastYear - (int)$firstYear + 1 }} {{ __('anos de atividade') }}
                @else
                    {{ __('Sem registo de anos') }}
                @endif
            </p>
        </div>
    </div>

    <!-- Main Chart Section -->
    <div class="card rounded-2xl border border-slate-200/80 bg-white shadow-xs overflow-hidden mb-5">
        <!-- Section Header with Title & Export Actions -->
        <div class="p-4 sm:p-5 border-b border-slate-100 flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3 bg-slate-50/40">
            <div class="flex items-center gap-2.5">
                <span class="w-9 h-9 rounded-xl bg-emerald-50 text-emerald-700 flex items-center justify-center text-sm shadow-2xs">
                    <i class="fas fa-chart-line"></i>
                </span>
                <div>
                    <h6 class="font-bold text-slate-900 text-sm sm:text-base mb-0">{{ __('Evolução da Produção Científica') }}</h6>
                    <p class="text-xs text-slate-400 font-medium mb-0">{{ __('Análise temporal da produtividade e atividades do autor') }}</p>
                </div>
            </div>

            <!-- Export Buttons -->
            <div class="flex items-center gap-2 self-end sm:self-auto">
                <button type="button" id="downloadPdf" class="inline-flex items-center gap-1.5 px-3 py-1.5 bg-white hover:bg-emerald-50/60 active:bg-emerald-100 text-slate-700 hover:text-emerald-800 text-xs font-semibold rounded-xl border border-slate-200 transition-all shadow-2xs cursor-pointer">
                    <i class="fas fa-file-pdf text-red-500 text-xs"></i>
                    <span>{{ __('Exportar PDF') }}</span>
                </button>
                <button type="button" id="downloadImg" class="inline-flex items-center gap-1.5 px-3 py-1.5 bg-white hover:bg-emerald-50/60 active:bg-emerald-100 text-slate-700 hover:text-emerald-800 text-xs font-semibold rounded-xl border border-slate-200 transition-all shadow-2xs cursor-pointer">
                    <i class="fas fa-file-image text-emerald-600 text-xs"></i>
                    <span>{{ __('Exportar PNG') }}</span>
                </button>
            </div>
        </div>

        <!-- Filter Control Bar -->
        <div class="p-4 sm:p-5 bg-white border-b border-slate-100">
            <div class="grid grid-cols-1 sm:grid-cols-3 gap-3 sm:gap-4">
                <!-- Chart Type Segmented Button -->
                <div>
                    <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1.5">
                        <i class="fas fa-shapes text-emerald-700 me-1"></i>{{ __('Tipo de Visualização') }}
                    </label>
                    <input type="hidden" id="chartType" value="line" />
                    <div class="grid grid-cols-2 gap-1.5 p-1 bg-slate-100/90 rounded-xl border border-slate-200/70">
                        <button type="button" id="btnChartLine" onclick="setChartType('line')"
                                class="chart-type-btn flex items-center justify-center gap-1.5 py-1.5 px-3 text-xs font-semibold rounded-lg transition-all duration-150 active-type bg-white text-emerald-800 shadow-2xs cursor-pointer">
                            <i class="fas fa-chart-line text-xs"></i>
                            <span>{{ __('Linha') }}</span>
                        </button>
                        <button type="button" id="btnChartBar" onclick="setChartType('bar')"
                                class="chart-type-btn flex items-center justify-center gap-1.5 py-1.5 px-3 text-xs font-semibold rounded-lg transition-all duration-150 text-slate-600 hover:text-slate-900 cursor-pointer">
                            <i class="fas fa-chart-column text-xs"></i>
                            <span>{{ __('Barras') }}</span>
                        </button>
                    </div>
                </div>

                <!-- Information Source -->
                <div>
                    <label for="chartInformation" class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1.5">
                        <i class="fas fa-database text-emerald-700 me-1"></i>{{ __('Dados de Análise') }}
                    </label>
                    <div class="relative">
                        <select name="chartInformation" id="chartInformation" class="w-full rounded-xl border border-slate-200 bg-white px-3.5 py-2 text-xs sm:text-sm font-semibold text-slate-700 shadow-2xs focus:border-emerald-600 focus:ring-1 focus:ring-emerald-600 transition-all cursor-pointer" onchange="requestInformation()">
                            <option value="publications">{{ __('Publicações Científicas') }}</option>
                            <option value="events">{{ __('Eventos e Atividades') }}</option>
                        </select>
                    </div>
                </div>

                <!-- Grouping Mode -->
                <div>
                    <label for="statsType" class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1.5">
                        <i class="fas fa-layer-group text-emerald-700 me-1"></i>{{ __('Modo de Agrupamento') }}
                    </label>
                    <div class="relative">
                        <select name="statsType" id="statsType" class="w-full rounded-xl border border-slate-200 bg-white px-3.5 py-2 text-xs sm:text-sm font-semibold text-slate-700 shadow-2xs focus:border-emerald-600 focus:ring-1 focus:ring-emerald-600 transition-all cursor-pointer" onchange="requestInformation()">
                            <option value="global">{{ __('Total Global Anual') }}</option>
                            <option value="type">{{ __('Desdobrar por Categoria') }}</option>
                        </select>
                    </div>
                </div>
            </div>
        </div>

        <!-- Chart Canvas Container -->
        <div id="reportPage" class="p-4 sm:p-6 bg-slate-50/20">
            <div class="w-full relative min-h-[380px] max-h-[520px] flex items-center justify-center">
                <canvas id="myChart"></canvas>
            </div>
        </div>

        <!-- Annual Breakdown Quick-Chips -->
        @if($outputsByYear->isNotEmpty())
            <div class="p-4 bg-white border-t border-slate-100">
                <div class="flex items-center justify-between gap-2 mb-2.5">
                    <span class="text-xs font-bold uppercase tracking-wider text-slate-500">
                        <i class="fas fa-calendar-days text-emerald-700 me-1.5"></i>{{ __('Histórico Anual de Produção') }}
                    </span>
                    <span class="text-[11px] text-slate-400">{{ __('N.º de registos por ano') }}</span>
                </div>
                <div class="flex flex-wrap items-center gap-2 overflow-x-auto pb-1 scrollbar-thin">
                    @foreach($outputsByYear->groupBy('year') as $yr => $items)
                        <div class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-lg bg-slate-50 border border-slate-200/80 hover:border-emerald-300 hover:bg-emerald-50/30 transition-all text-xs">
                            <span class="font-mono text-slate-600 font-medium">{{ $yr }}</span>
                            <span class="font-bold text-emerald-800 bg-emerald-100/70 px-1.5 py-0.2 rounded text-[11px]">
                                {{ $items->sum('count') }}
                            </span>
                        </div>
                    @endforeach
                </div>
            </div>
        @endif
    </div>

    <!-- Hidden Anchor for PNG Download -->
    <a id="hiddenDownloadImg" download="Grafico_Producao_{{ $author->id }}.png" style="display: none;"></a>

    <script>
        const dataSetInformation = [];

        // Curated Editorial Palette for Chart Categories
        const PALETTE = [
            { border: '#059669', bg: 'rgba(5, 150, 105, 0.12)' },
            { border: '#2563eb', bg: 'rgba(37, 99, 235, 0.12)' },
            { border: '#d97706', bg: 'rgba(217, 119, 6, 0.12)' },
            { border: '#7c3aed', bg: 'rgba(124, 58, 237, 0.12)' },
            { border: '#db2777', bg: 'rgba(219, 39, 119, 0.12)' },
            { border: '#0891b2', bg: 'rgba(8, 145, 178, 0.12)' },
            { border: '#ea580c', bg: 'rgba(234, 88, 12, 0.12)' },
        ];

        function setChartType(type) {
            document.getElementById('chartType').value = type;
            const btnLine = document.getElementById('btnChartLine');
            const btnBar = document.getElementById('btnChartBar');

            if (type === 'line') {
                btnLine.classList.add('bg-white', 'text-emerald-800', 'shadow-2xs');
                btnLine.classList.remove('text-slate-600');
                btnBar.classList.remove('bg-white', 'text-emerald-800', 'shadow-2xs');
                btnBar.classList.add('text-slate-600');
            } else {
                btnBar.classList.add('bg-white', 'text-emerald-800', 'shadow-2xs');
                btnBar.classList.remove('text-slate-600');
                btnLine.classList.remove('bg-white', 'text-emerald-800', 'shadow-2xs');
                btnLine.classList.add('text-slate-600');
            }

            updateChartType();
        }

        async function requestInformation() {
            const type = document.getElementById("chartInformation").value;
            const statsType = document.getElementById("statsType").value;
            const url = (type === "events") ? "/statistics/events" : "/statistics/publications";
            const authorId = {{ $author->id }};

            try {
                const response = await fetch(`${url}?statsType=${encodeURIComponent(statsType)}&authorId=${encodeURIComponent(authorId)}`, {
                    headers: { 'Accept': 'application/json' }
                });

                if (!response.ok) {
                    throw new Error('Network response was not ok');
                }

                const data = await response.json();
                changeDataset(data.data || []);
            } catch (error) {
                console.error("Erro ao carregar estatísticas:", error);
            }
        }

        function getYearsToShow(array) {
            if (!array || array.length === 0) return [];

            let initialYear = parseInt(array[0]["year"]);
            let lastYear = parseInt(array[array.length - 1]["year"]);

            if (isNaN(initialYear) || isNaN(lastYear)) {
                return array.map(item => String(item.year));
            }

            const years = [];
            for (let y = initialYear; y <= lastYear; y++) {
                years.push(String(y));
            }
            return years;
        }

        function changeDataset(array) {
            dataSetInformation.length = 0;

            if (!array || array.length === 0) {
                updateChartData({ labels: [], datasets: [] });
                return;
            }

            const type = document.getElementById("chartInformation").value;
            const baseType = (type === "events") ? "{{ __('Eventos') }}" : "{{ __('Publicações') }}";
            const dataSetPosition = {};

            for (let i = 0; i < array.length; i++) {
                const label = array[i]["type"] || baseType;
                const year = String(array[i]["year"]);
                const count = Number(array[i]["count"]);

                if (dataSetPosition[label] === undefined) {
                    const datasetIdx = dataSetInformation.length;
                    const colors = PALETTE[datasetIdx % PALETTE.length];
                    const newDataset = {
                        label: label,
                        data: [{ x: year, y: count }],
                        borderColor: colors.border,
                        backgroundColor: colors.bg,
                        tension: 0.35,
                        pointBackgroundColor: colors.border,
                        pointBorderColor: '#ffffff',
                        pointBorderWidth: 2,
                        pointRadius: 4.5,
                        pointHoverRadius: 7,
                        borderRadius: 6,
                        borderWidth: 2,
                        fill: true
                    };
                    dataSetInformation.push(newDataset);
                    dataSetPosition[label] = datasetIdx;
                } else {
                    dataSetInformation[dataSetPosition[label]].data.push({ x: year, y: count });
                }
            }

            const data = {
                labels: getYearsToShow(array),
                datasets: dataSetInformation
            };

            updateChartData(data);
        }

        function getCommonChartOptions() {
            return {
                animation: { duration: 400 },
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        position: 'top',
                        align: 'end',
                        labels: {
                            boxWidth: 12,
                            boxHeight: 12,
                            usePointStyle: true,
                            pointStyle: 'circle',
                            font: { family: 'inherit', size: 12, weight: 600 },
                            color: '#334155',
                            padding: 16
                        }
                    },
                    tooltip: {
                        backgroundColor: '#0f172a',
                        titleFont: { size: 13, weight: 'bold' },
                        bodyFont: { size: 12 },
                        padding: 12,
                        cornerRadius: 10,
                        boxPadding: 4,
                        usePointStyle: true
                    }
                },
                scales: {
                    x: {
                        grid: { display: false },
                        ticks: {
                            color: '#64748b',
                            font: { weight: 600, size: 11 }
                        }
                    },
                    y: {
                        beginAtZero: true,
                        grid: {
                            color: 'rgba(226, 232, 240, 0.7)',
                            borderDash: [3, 3]
                        },
                        ticks: {
                            color: '#64748b',
                            precision: 0,
                            font: { size: 11 }
                        }
                    }
                }
            };
        }

        function updateChartType() {
            const chartCanvas = document.getElementById('myChart');
            const oldChartInstance = Chart.getChart(chartCanvas);

            if (!oldChartInstance) return;

            const currentData = oldChartInstance.data;
            const currentType = document.getElementById("chartType").value;

            oldChartInstance.destroy();

            new Chart(chartCanvas, {
                type: currentType,
                data: currentData,
                options: getCommonChartOptions(),
                plugins: [{
                    id: 'noData',
                    afterDraw: function(chart) {
                        if (chart.data.datasets.length === 0) {
                            const ctx = chart.ctx;
                            const width = chart.width;
                            const height = chart.height;
                            chart.clear();
                            ctx.save();
                            ctx.textAlign = 'center';
                            ctx.textBaseline = 'middle';
                            ctx.fillStyle = '#94a3b8';
                            ctx.font = "14px 'Roboto', sans-serif";
                            ctx.fillText('{{ __("Sem dados registados para este filtro") }}', width / 2, height / 2);
                            ctx.restore();
                        }
                    }
                }]
            });
        }

        function updateChartData(data) {
            const chartCanvas = document.getElementById('myChart');
            const existingChart = Chart.getChart(chartCanvas);

            if (existingChart) existingChart.destroy();

            new Chart(chartCanvas, {
                type: document.getElementById("chartType").value,
                data: data,
                options: getCommonChartOptions(),
                plugins: [{
                    id: 'noData',
                    afterDraw: function(chart) {
                        if (chart.data.datasets.length === 0) {
                            const ctx = chart.ctx;
                            const width = chart.width;
                            const height = chart.height;
                            chart.clear();
                            ctx.save();
                            ctx.textAlign = 'center';
                            ctx.textBaseline = 'middle';
                            ctx.fillStyle = '#94a3b8';
                            ctx.font = "14px 'Roboto', sans-serif";
                            ctx.fillText('{{ __("Sem dados registados para este filtro") }}', width / 2, height / 2);
                            ctx.restore();
                        }
                    }
                }]
            });
        }

        document.addEventListener('DOMContentLoaded', function () {
            requestInformation();

            // Export to PDF
            const pdfBtn = document.getElementById('downloadPdf');
            if (pdfBtn) {
                pdfBtn.addEventListener('click', function () {
                    const canvas = document.getElementById('myChart');
                    if (!canvas) return;

                    const { jsPDF } = window.jspdf || {};
                    if (!jsPDF) return;

                    const imgData = canvas.toDataURL('image/png', 1.0);
                    const pdf = new jsPDF('landscape', 'px', [canvas.width, canvas.height]);
                    pdf.addImage(imgData, 'PNG', 0, 0, canvas.width, canvas.height);
                    pdf.save('Estatisticas_Producao_{{ $author->id }}.pdf');
                });
            }

            // Export to PNG
            const imgBtn = document.getElementById('downloadImg');
            if (imgBtn) {
                imgBtn.addEventListener('click', function () {
                    const canvas = document.getElementById('myChart');
                    if (!canvas) return;
                    const url = canvas.toDataURL('image/png');
                    const link = document.getElementById('hiddenDownloadImg');
                    link.href = url;
                    link.click();
                });
            }
        });
    </script>
@endsection
