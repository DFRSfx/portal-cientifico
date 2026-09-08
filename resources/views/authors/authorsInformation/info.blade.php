@extends('authors.show')

<title>Estatísticas de {{ substr($authors->{"name"}, 0, 16) ?? 'Sem Valor' }} ...</title>

@section('author-information')
    <!-- Hero -->

    @push('header-scripts')
        <script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf/2.5.1/jspdf.umd.min.js"></script>
    @endpush

    <div class="card mb-3">
        <div class="card-body">
            <div class="card-title">
                <h6 class="my-3" style="color:#2A6B20">Filtros</h6>
            </div>
            <h6 class="my-3" style="color:#2A6B20">Tipos de gráficos</h6>
            <select name="chartType" id="chartType" class="select " onchange="updateChartType()">
                <option value="line">Linha</option>
                <option value="bar">Barras</option>
            </select>

            <h6 class="my-3" style="color:#2A6B20">Informações</h6> <select name="chartType" id="chartInformation"
                class="select" onchange="requestInformation()">
                <option value="publications">Publications</option>
                <option value="events">Events</option>
            </select>
        </div>
    </div>

    @if (!count($outputsByYear))
        <span>Autor não tem dados suficientes para serem visualizados</span>
    @else
        <!-- Pills navs -->
        <ul class="nav nav-pills nav-justified mb-3" id="ex1" role="tablist">
            <li class="nav-item" role="presentation">
                <a class="nav-link active" id="ex3-tab-1" data-mdb-toggle="pill" href="#ex3-pills-1" role="tab"
                    aria-controls="ex3-pills-1" aria-selected="true"> Número de Outputs </a>
            </li>
            <li class="nav-item" role="presentation">
                <a class="nav-link" id="ex3-tab-2" data-mdb-toggle="pill" href="#ex3-pills-2" role="tab"
                    aria-controls="ex3-pills-2" aria-selected="false">Média</a>
            </li>
        </ul>
        <div class="tab-content" id="reportPage">
            <div class="tab-pane fade show active" id="ex3-pills-1" role="tabpanel" aria-labelledby="ex3-tab-1">
                <div class="card text-center">
                    <div class="card-header"><span style="color:#33642b">Percentagens de outputs</span></div>
                    <div class="card-body">
                        <div class="container">
                            <canvas id="myChart"></canvas>
                        </div>
                        <div class="card-footer">
                            <div class="container d-flex justify-content-around  align-items-center"> <a
                                    href="javascript:void(0)" id="downloadPdf"><i class="fa-solid fa-file-arrow-down"></i> Download do gráfico em PDF</a>

                                <a id="downloadImg" download="Gráfico.png" href="javascript:void(0)"
                                    title="Descargar Gráfico">

                                    <!-- Download Icon -->
                                    <i class="fa-solid  fa-download"></i>
                                    Download do gráfico em PNG
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
                <div class="card-header"><span style="color:#33642b">Média</span></div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-xl-6">
                            <h2 class="card-title" style="color:#33642b"> {{ $averageGlobal }}</h2>
                            <p class="card-text"><span class="text-muted">A média global de publicações por ano </span></p>
                        </div>
                        <div class="col-xl-6">
                            <h2 class="card-title" style="color:#33642b"> {{ $average }}</h2>
                            <p class="card-text"><span class="text-muted">A sua média de publicações por ano </span></p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    @endif

    <!-- Hero -->
    <script>
        const dataSetInformation = []

        async function requestInformation() {
            let type = document.getElementById("chartInformation").value;

            let url = (type == "events") ? "http://127.0.0.1:8000/statistics/events" :
                "http://127.0.0.1:8000/statistics/publications"

            const authorId = {{ $authors->id }}

            try 
            {
                const response = await fetch(url + `?authorId=${authorId}`);

                if (!response.ok) 
                {
                    throw new Error('Network response was not ok');
                }

                const data = await response.json();

                changeDataset(data.data)
            } 
            catch (error) 
            {
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

            for (var i = 0; i < arraySize; i++) {
                const label = (array[i]["type"]) ?? "ALL Pubs"

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
