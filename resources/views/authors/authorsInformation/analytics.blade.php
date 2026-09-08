@extends('authors.show')

@section('author-information')
    <!-- Hero -->

    @pushOnce('header-scripts')
        <script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf/2.5.1/jspdf.umd.min.js"></script>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/4.4.0/chart.umd.js"
            integrity="sha512-6HrPqAvK+lZElIZ4mZ64fyxIBTsaX5zAFZg2V/2WT+iKPrFzTzvx6QAsLW2OaLwobhMYBog/+bvmIEEGXi0p1w=="
            crossorigin="anonymous" referrerpolicy="no-referrer"></script>
    @endPushOnce


        <div class="card mb-3">
            <div class="card-body">
                <div class="card-title">
                    <h6 class="my-3" style="color:#2A6B20">{{ __('Filtros') }}</h6>
                </div>
                <h6 class="my-3" style="color:#2A6B20">{{ __('Tipos de gráficos') }}</h6>
                <select name="chartType" id="chartType" class="select " onchange="updateChartType()">
                    <option value="line">{{ __('Linha') }}</option>
                    <option value="bar">{{ __('Barras') }}</option>
                </select>

                <h6 class="my-3" style="color:#2A6B20">{{ __('Informações') }}</h6> <select name="chartType"
                    id="chartInformation" class="select" onchange="requestInformation()">
                    <option value="publications">{{ __('Publicações') }}</option>
                    <option value="events">{{ __('Events') }}</option>
                </select>
                <h6 class="my-3" style="color:#2A6B20">{{ __('Tipo de Estatisticas') }}</h6>
                <select name="statsType" id="statsType" class="select" onchange="requestInformation()">
                    <option value="global">{{ __('Global') }}</option>
                    <option value="type">{{ __('Tipo') }}</option>
                </select>
            </div>
        </div>
        <!-- Pills navs -->
        <ul class="nav nav-pills nav-justified mb-3" id="ex1" role="tablist">
            <li class="nav-item" role="presentation">
                <a class="nav-link active" id="ex3-tab-1" data-mdb-toggle="pill" href="#ex3-pills-1" role="tab"
                    aria-controls="ex3-pills-1" aria-selected="true"> {{ __('Estatística') }} </a>
            </li>
            <li class="nav-item" role="presentation">
                <a class="nav-link" id="ex3-tab-2" data-mdb-toggle="pill" href="#ex3-pills-2" role="tab"
                    aria-controls="ex3-pills-2" aria-selected="false">{{ __('Média') }}</a>
            </li>
        </ul>
        <div class="tab-content" id="reportPage">
            <div class="tab-pane fade show active" id="ex3-pills-1" role="tabpanel" aria-labelledby="ex3-tab-1">
                <div class="card text-center">
                    <div class="card-header"><span style="color:#33642b">{{ __('Estatísticas do Autor') }}</span></div>
                    <div class="card-body">
                        <div class="container">
                            <canvas id="myChart"></canvas>
                        </div>
                        <div class="card-footer">
                            <div class="container d-flex justify-content-around  align-items-center"> <a
                                    href="javascript:void(0)" id="downloadPdf"><i class="fa-solid fa-file-arrow-down"></i>
                                    {{ __('Download do gráfico em PDF') }}</a>

                                <a id="downloadImg" download="Gráfico.png" href="javascript:void(0)"
                                    title="Descargar Gráfico">

                                    <!-- Download Icon -->
                                    <i class="fa-solid  fa-download"></i>
                                    {{ __('Download do gráfico em PNG') }}
                                </a>
                            </div>

                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!-- Pills navs -->


        <div class="tab-pane fade" id="ex3-pills-2" role="tabpanel" aria-labelledby="ex3-tab-2">
            <div class="card text-center">
                <div class="card-header"><span style="color:#33642b">{{ __('Média') }}</span></div>
                <div class="card-body">
                    <div class="row">

                        <div class="col-xl-6 mx-auto">
                            <h2 class="card-title" style="color:#33642b"> {{ $average }}</h2>
                            <p class="card-text"><span class="text-muted">A sua média de publicações por ano </span></p>
                        </div>
                    </div>
                </div>
            </div>
        </div>


    <!-- Hero -->
    <script>
        const dataSetInformation = []

        async function requestInformation() {
            const type = document.getElementById("chartInformation").value;

            const statsType = document.getElementById("statsType").value;

            let url = (type == "events") ? "https://portalcientifico.islagaia.pt/statistics/events" :
                "https://portalcientifico.islagaia.pt/statistics/publications"

            const authorId = {{ $author->id }}

            try {
                const response = await fetch(url + "?statsType=" + statsType + "&authorId=" + authorId);

                if (!response.ok) {
                    throw new Error('Network response was not ok');
                }

                const data = await response.json();

                changeDataset(data.data)
            } catch (error) {
                console.log(error);
            }

        }

        function getYearsToShow(array) {
            let arrayToReturn = [];

            let initialYear, lastYear, numberOfInteractions;

            initialYear = parseInt(array[0]["year"]);

            lastYear = parseInt(array[array.length - 1]["year"]);

            numberOfInteractions = 0;

            do {

                if (numberOfInteractions > 0) {
                    initialYear += 1;
                }

                arrayToReturn.push(initialYear.toString());

                numberOfInteractions++;

            } while (initialYear != lastYear)

            return arrayToReturn;
        }

        /*

        Dataset Manipulation

        */
        function changeDataset(array) {
            let num, arraySize

            const dataSetPosition = []

            if (dataSetInformation.length > 0) dataSetInformation.length = 0

            arraySize = array.length

            if (arraySize == 0) {
                updateChartData({
                    abels: [],
                    datasets: []
                })

                return
            }

            const type = document.getElementById("chartInformation").value;

            const baseType = (type == "events") ? "{{ 'Eventos' }}" : "{{ 'Publicações' }}"

            for (var i = 0; i < arraySize; i++) {
                const label = (array[i]["type"]) ?? baseType

                let labelWasChecked = (dataSetPosition[label]) ?? -1

                if (labelWasChecked == -1) {
                    // Adiciono o dataSet
                    dataSetInformation.push(CreateDataSet(label, array[i]["year"], array[i]["count"]))

                    dataSetPosition[label] = dataSetInformation.length - 1
                } else {
                    // dataSet
                    dataSetInformation[dataSetPosition[label]] = UpdateDatasetData(dataSetInformation[dataSetPosition[
                        label]], array[i]["year"], array[i]["count"])
                }
            }

            const data = {
                labels: getYearsToShow(array),
                datasets: dataSetInformation
            };

            updateChartData(data)
        }

        function CreateDataSet(datasetLabelName, year, numberOfElements) {
            let chartJsDataset, graphCoordinates;

            // Builds the initial dataset only for each type
            chartJsDataset = {
                label: datasetLabelName,
                data: []
            };

            graphCoordinates = {
                x: year,
                y: numberOfElements
            }

            chartJsDataset["data"].push(graphCoordinates);

            return chartJsDataset
        }

        function updateChartType() {
            const oldChartInstance = Chart.getChart(document.getElementById('myChart'))

            let oldChartData

            oldChartData = oldChartInstance.data

            oldChartInstance.destroy()

            new Chart(document.getElementById('myChart'), {
                type: document.getElementById("chartType").value,
                data: oldChartData,
                options: {
                    animation: false,
                    responsive: true,
                    maintainAspectRatio: true,
                    plugins: {
                        legend: {
                            position: 'top',
                        }
                    },
                    scales: {
                        x: {
                            stacked: true,
                        },
                        y: {
                            stacked: true,
                        },
                    },

                },
                plugins: [{
                    id: 'noData',
                    afterDraw: function(chart) {
                        if (chart.data.datasets.length === 0) {
                            // No data is present
                            var ctx = chart.ctx;
                            var width = chart.width;
                            var height = chart.height;
                            chart.clear();

                            ctx.save();
                            ctx.textAlign = 'center';
                            ctx.textBaseline = 'middle';
                            ctx.font = "16px normal 'Helvetica Nueue'";
                            ctx.fillText('{{ __("Sem dados para exibir") }}', width / 2, height / 2);
                            ctx.restore();
                        }
                    },
                }]
            });
        }

        function UpdateDatasetData(chartJsDataset, year, numberElements) {
            let graphCoordinates

            graphCoordinates = {
                x: year,
                y: numberElements
            }

            chartJsDataset["data"].push(graphCoordinates);

            return chartJsDataset
        }

        // Function runs on chart type select update
        function updateChartData(data) {
            const chart = Chart.getChart(document.getElementById('myChart'))

            if (chart) chart.destroy()

            new Chart(document.getElementById('myChart'), {
                type: document.getElementById("chartType").value,
                data: data,
                options: {
                    animation: false,
                    responsive: true,
                    maintainAspectRatio: true,
                    plugins: {
                        legend: {
                            position: 'top',
                        }
                    },
                    scales: {
                        x: {
                            stacked: true,
                        },
                        y: {
                            stacked: true,
                        },
                    },

                },
                plugins: [{
                    id: 'noData',
                    afterDraw: function(chart) {

                        if (chart.data.datasets.length === 0) {
                            // No data is present
                            var ctx = chart.ctx;
                            var width = chart.width;
                            var height = chart.height;
                            chart.clear();

                            ctx.save();
                            ctx.textAlign = 'center';
                            ctx.textBaseline = 'middle';
                            ctx.font = "16px normal 'Helvetica Nueue'";
                            ctx.fillText('{{ __("Sem dados para exibir") }}', width / 2, height / 2);
                            ctx.restore();
                        }
                    },
                }]
            });


        }

        $(document).ready(function() {

            requestInformation()

            $('#downloadPdf').click(function(event) {

                // get size of report page
                var reportPageHeight = $('#reportPage').innerHeight();

                var reportPageWidth = $('#reportPage').innerWidth();

                // create a new canvas object that we will populate with all other canvas objects
                var pdfCanvas = $('<canvas />').attr({
                    id: "canvaspdf",
                    width: reportPageWidth,
                    height: reportPageHeight
                });

                // keep track canvas position
                var pdfctx = $(pdfCanvas)[0].getContext('2d');
                var pdfctxX = 0;
                var pdfctxY = 0;
                var buffer = 100;

                // for each chart.js chart
                $("canvas").each(function(index) {
                    // get the chart height/width
                    var canvasHeight = $(this).innerHeight();
                    var canvasWidth = $(this).innerWidth();

                    // draw the chart into the new canvas
                    pdfctx.drawImage($(this)[0], pdfctxX, pdfctxY, canvasWidth, canvasHeight);
                    pdfctxX += canvasWidth + buffer;

                    // our report page is in a grid pattern so replicate that in the new canvas
                    if (index % 2 === 1) {
                        pdfctxX = 0;
                        pdfctxY += canvasHeight + buffer;
                    }
                });

                // create new pdf and add our new canvas as an image
                var pdf = new jsPDF('l', 'pt', [reportPageWidth, reportPageHeight]);

                pdf.addImage($(pdfCanvas)[0], 'PNG', 0, 0, pdf.internal.pageSize.width, pdf.internal
                    .pageSize.height);

                // download the pdf
                pdf.save('gráfico.pdf');
            });

            //Download Chart Image
            document.getElementById("downloadImg").addEventListener('click', function() {
                /*Get image of canvas element*/
                var url_base64jp = document.getElementById("myChart").toDataURL("image/jpg");
                /*get download button (tag: <a></a>) */
                var a = document.getElementById("downloadImg");
                /*insert chart image url to download button (tag: <a></a>) */
                a.href = url_base64jp;
            });


            window.jsPDF = window.jspdf.jsPDF
        });
    </script>
@endsection
