<x-app-layout>

    <x-slot:title>
        {{ __('Estatísticas') }}
        </x-slot>

        @pushOnce('header-scripts')
        <script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf/2.5.1/jspdf.umd.min.js"></script>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/4.4.0/chart.umd.js" integrity="sha512-6HrPqAvK+lZElIZ4mZ64fyxIBTsaX5zAFZg2V/2WT+iKPrFzTzvx6QAsLW2OaLwobhMYBog/+bvmIEEGXi0p1w==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
        @endPushOnce

        <section>
            <div class="container pt-5">

                <div class="container d-flex justify-content-end">
                    <div class="row">
                        <div class="col">
                            <form action="{{ route('pub.export') }}">
                                <button class="btn btn-primary">{{ __('Gerar Excel') }}</button>
                            </form>
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-4 mb-4 mb-md-0">
                        <div class="card">
                            <div class="card-body">
                                <div class="d-flex align-items-center">
                                    <div class="p-3 badge-primary rounded-4 " style="background-color:#2A6B20">
                                        <i class="fas fa-newspaper fa-lg fa-fw" style="color:white;"></i>
                                    </div>
                                    <div class="flex-grow-1 ms-4">
                                        <p class="text-muted mb-1">{{ __('Publicações') }}</p>
                                        <h2 class="mb-0">
                                            {{ $numberOfPublications }}
                                        </h2>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-4 mb-4 mb-md-0">
                        <div class="card">
                            <div class="card-body">
                                <div class="d-flex align-items-center">
                                    <div class="p-3 badge-primary rounded-4 " style="background-color:#2A6B20">
                                        <i class="fas fa-user fa-lg fa-fw" style="color:white;"></i>
                                    </div>
                                    <div class="flex-grow-1 ms-4">
                                        <p class="text-muted mb-1">{{ __('Média de Publicações') }}</p>
                                        <h2 class="mb-0">
                                            {{ $average }}
                                        </h2>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

            </div>
        </section>


        <section>
            <div class="container pt-5">
                @if (!empty($publicationsByYear))
                <div class="row">

                    <div class="col-xl-3">
                        <div class="card mb-3">
                            <div class="card-body">
                                <div class="card-title">
                                    <h6 class="my-3" style="color:#2A6B20">{{ __('Filtros') }}</h6>
                                </div>
                                <h6 class="my-3" style="color:#2A6B20">{{ __('Tipo de gráfico') }}s</h6>
                                <select name="chartType" id="chartType" class="select " onchange="updateChartType()">
                                    <option value="line">{{ __('Linha') }}</option>
                                    <option value="bar">{{ __('Barras') }}</option>
                                </select>

                                <h6 class="my-3" style="color:#2A6B20">{{ __('Informações') }}</h6>
                                <select name="chartInformation" id="chartInformation" class="select" onchange="requestInformation()">
                                    <option value="publications">{{ __('Publicações') }}</option>
                                    <option value="events">{{ __('Eventos') }}</option>
                                </select>

                                <h6 class="my-3" style="color:#2A6B20">{{ __('Tipo de Estatisticas') }}</h6>
                                <select name="statsType" id="statsType" class="select" onchange="requestInformation()">
                                    <option value="global">{{ __('Global') }}</option>
                                    <option value="type">{{ __('Tipo') }}</option>
                                </select>

                                <h6 class="my-3" style="color:#2A6B20">{{ __('Anos') }}</h6>
                                <div id="multi-ranges-basic-value" class="mt-3">
                                    <span class="d-flex justify-content-between flex-row p-2">
                                        <div id="startYear">{{$oldestYear}}</div>
                                        <div id="endYear">{{$newestYear }}
                                        </div>
                                    </span>
                                </div>

                                <div class="list-group list-group-flush">
                                    <div class="multi-ranges-basic" id="multi-range" data-mdb-tooltips="true"></div>
                                </div>

                            </div>
                        </div>
                    </div>

                    <div class="col-xl-9" id="reportPage">
                        <div class="card">
                            <div class="card-body">
                                <div class="card text-center">
                                    <div class="card-header">
                                        <span style="color:#33642b">{{ __('Estatísticas') }}</span>
                                    </div>
                                    <div class="card-body">
                                        <canvas id="myChart"></canvas>
                                    </div>
                                    <div class="card-footer">
                                        <div class="container d-flex justify-content-around  align-items-center">
                                            <a href="#" id="downloadPdf"><i class="fa-solid fa-file-arrow-down"></i>
                                                {{ __('Download do gráfico em PDF') }} </a>

                                            <a id="downloadImg" download="Gráfico.png" href="#" title="Descargar Gráfico">

                                                <!-- Download Icon -->
                                                <i class="fa-solid  fa-download"></i>
                                                {{ __('Download do gráfico em PNG') }}
                                            </a>
                                        </div>

                                    </div>
                                </div>

                                <!-- Pills content -->
                            </div>
                        </div>
                    </div>
                </div>
                @else
                <div class="row">
                    <div class="col-xl-12">
                        <div class="card mb-3">
                            <div class="card-body">
                                <h1>{{ __('Sem dados suficientes') }} !</h1>
                            </div>
                        </div>
                    </div>
                </div>
                @endif
            </div>
        </section>

        <script>
            const dataSetInformation = []

            async function requestInformation() {
                const type = document.getElementById("chartInformation").value;

                const statsType = document.getElementById("statsType").value;

                const startYear = document.getElementById("startYear").innerHTML

                const endYear = document.getElementById("endYear").innerHTML

                let url = (type == "events") ? "https://portalcientifico.islagaia.pt/statistics/events" :
                    "https://portalcientifico.islagaia.pt/statistics/publications"

                try {
                    const response = await fetch(url + "?statsType=" + statsType + "&startYear=" + startYear + "&endYear=" +
                        endYear);

                    if (!response.ok) {
                        throw new Error('Network response was not ok');
                    }

                    const data = await response.json();

                    changeDataset(data.data)
                } catch (error) {
                    console.log(error);
                }

            }

            function getOutputCount(array) {
                let sum = 0;

                array.forEach(element => {

                    sum += parseInt(element["numberOfOutputs"])

                });

                document.getElementById('count').innerHTML = sum;
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

                const type = document.getElementById("chartInformation").value;

                const baseType = (type == "events") ? "{{ ('Eventos') }}" : "{{ ('Publicações') }}"

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

            // Years
            function yearsRange() {
                const currentDate = new Date();

                const basicExample = document.querySelector('.multi-ranges-basic');

                let year = currentDate.getFullYear();

                let oldestDate = 2000

                let yearDiff = year - oldestDate;

                var basicExampleInit = new mdb.MultiRangeSlider(basicExample, {
                    max: yearDiff,
                    min: 0,
                    startValues: [0, yearDiff]
                });

                basicExample.addEventListener('value.mdb.multiRangeSlider', (handler) => {

                    // Values 
                    let [first, second] = handler.values.rounded;

                    first += oldestDate + 1;
                    second += oldestDate;

                    const oneRangeValueBasic = document.querySelector('#multi-ranges-basic-value');


                    // Updates the range values
                    oneRangeValueBasic.innerHTML = `<span class="d-flex justify-content-between flex-row p-2">
                                    <div id="startYear">${first}</div>
                                    <div id="endYear">${second}</div>
                                </span>
                                `;
                });

                // adds the events
                const rangeSliderHand = document.getElementsByClassName("multi-range-slider-hand")

                Array.from(rangeSliderHand).forEach(function(element) {
                    element.addEventListener('mouseup', requestInformation);
                    element.addEventListener('touchend', requestInformation);
                });
            }

            $(document).ready(function() {

                requestInformation()

                yearsRange()

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

</x-app-layout>