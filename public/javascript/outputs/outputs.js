const apiResultsToShow = document.getElementById("search-results");

const repositoriesCard = document.getElementById("repositoriesCard");

window.addEventListener("load", (event) => {

    const apiResultsCardAnimation = new mdb.Animate(apiResultsToShow, {
        animation: "slide-in-up",
        animationStart: "manually",
        animationDelay: "0",
        animationDuration: "500",
        onEnd: () => {
            apiResultsCardAnimation.stopAnimation();
        },
        onStart: () => {
            if (apiResultsToShow.style.display != "block") apiResultsToShow.style.display = "block";
        }
    });

    const repositoriesCardAnimation = new mdb.Animate(repositoriesCard, {
        animation: "slide-in-left",
        animationStart: "manually",
        animationDelay: "0",
        animationDuration: "1000",
        onEnd: () => {
            repositoriesCardAnimation.stopAnimation();
        },
        onStart: () => {
            if (repositoriesCard.style.display != "block") repositoriesCard.style.display = "block";
        }
    });

    // Initializes the objects animations
    repositoriesCardAnimation.init();
    apiResultsCardAnimation.init();
});

$("#form").submit(function(event) {
    event.preventDefault();

    // Gets the object instance
    const repositoriesCardAnimation = mdb.Animate.getInstance(repositoriesCard);

    // start the first animation
    repositoriesCardAnimation.startAnimation();

    window.setTimeout(() => {

        const apiResultsCardAnimation = mdb.Animate.getInstance(apiResultsToShow);

        $.ajax({
            type: 'GET',
            data:{
                doi: document.getElementById("text-to-search").value
            },
            url: '/test-search',
            success: function(data, status) {

                document.getElementById("scopus-spinner").style.display = "none";
                document.getElementById("scopus-check-icon").style.display = "block";

                const cardContent = document.getElementById("element1");

                cardContent.innerHTML = `Title: ${data["data"][0]["dcTitle"]}<br>
                                        Doi: ${data["data"][0]["doi"]} <br>
                                        Url: <a href="${data["data"][0]["url"]}"  target="_blank" >clique</a> <br>
                                        ScopusId: ${data["data"][0]["dcIdentifier"]}<br>
                                        Eid: ${data["data"][0]["eid"]} <br>
                                        Creator:${data["data"][0]["dcCreator"]}<br>
                                        Publication Name: ${data["data"][0]["prismPublicationName"]}<br>
                                        Indexed: ${data["data"][0]["api"]}`;


                apiResultsCardAnimation.startAnimation();
            }
        });

    }, 3000);




});

$.ajaxSetup({
    headers: {
        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
    }
});

const basicExample = document.querySelector('.multi-ranges-basic');
const oneRangeValueBasic = document.querySelector('#multi-ranges-basic-value');

var rangeInstance

function yearsRange() {

    const currentDate = new Date();

    let year = currentDate.getFullYear();

    let yearDiff = year - 2012;

    var basicExampleInit = new mdb.MultiRangeSlider(basicExample,{
        max: yearDiff,
        min: 0,
        startValues: [0, yearDiff]
    });
    
}

function checkIfElementExists(propriety, valueToSearch) {
    const elements = document.getElementsByClassName("filtred-filds");

    let elementExists = false;

    const numberOfElements = elements.length;

    for (let i = 0; i < numberOfElements; i++) {
        if (elements[i].attributes[propriety] !== undefined && elements[i].attributes[propriety].value ===
            valueToSearch) {
            elementExists = true;
            break;
        }
    }

    return elementExists;
}

function isObjectDefined(obj) {
    return Object.keys(obj).length !== 0;
}
function createElement(type, classToInsertTheElement, innerHTMLValue, propriety, valueTosearch, attributes = {}) {
    // Checks if the button exists
    const elementExists = checkIfElementExists(propriety, valueTosearch);

    if (elementExists) return;

    const newElement = document.createElement(type);

    // Checks if is necessary to add atributes to the element that is going to be created
    if (isObjectDefined(attributes)) {
        // The attributes is passed as one second argument to access the object current postion with "this"
        Object.keys(attributes).forEach(function(key, index) {

            // sets the attributes in the object
            newElement.setAttribute(key, this[key]);

        }, attributes);

    }

    newElement.innerHTML = innerHTMLValue;

    newElement.addEventListener("click", function() {
        uncheckOption(attributes);
    });

    document.getElementById(classToInsertTheElement).appendChild(newElement);
}

function dropElement(className, typeToDrop, valueToDrop, dropAll = false) {
    const mainLocation = document.getElementsByClassName(className);

    if (dropAll) {
        mainLocation.innerHTML = "";
    } else {

        // Removes all elements with a especific value and tag
        mainLocation.forEach(element => {
            if (element.tagName === typeToDrop && element.value === valueToDrop) {
                element.remove();
                return;
            }
        });
    }


}

function deleteElement(propriety, valueToSearch) {

    const elements = document.getElementsByClassName("filtred-filds");

    const numberOfElements = elements.length;

    for (let i = 0; i < numberOfElements; i++) {
        if (elements[i].attributes[propriety] !== undefined && elements[i].attributes[propriety].value ===
            valueToSearch) {
            elements[i].remove();
            break;
        }
    }

}

function uncheckOption(attributes) {

    if (!isObjectDefined(attributes)) return;

    const elementToUncheck = document.getElementById(attributes["parent-id"]);

    if (attributes["parent-type"] === "checkBox")
    {
        elementToUncheck.click();
    } else if (attributes["parent-type"] === "input")
    {
        elementToUncheck.value = "";

        deleteElement("parent-id", attributes["parent-id"]);

        submitForm();
    }
    else
    {
        const oldRangeInstance = mdb.MultiRangeSlider.getInstance(basicExample)

        console.log(mdb.MultiRangeSlider.getInstance(basicExample));

        // removes the instance from the element 
        oldRangeInstance.dispose()

        yearsRange()
        
        submitForm()
    }

}

/*

gets all the selected option inside a main element

*/
function getSelectedOptions(targetId, targetType, typeOfIdentifier) {
    let array = [];

    let targetElement;

    if (typeOfIdentifier == "class") {
        targetElement = document.querySelectorAll(targetId);
    }

    targetElement.forEach(element => {
        array.push(element.value);
    });

    return array;

}

function enableOneCheckBoxAndDisableTheRest(ElementClass, elementToDisableId) {
    let targetElement;

    targetElement = document.querySelectorAll(targetId);

    targetElement.forEach(element => {

        let theElementShoulbBeDisabled = (element.getAttribute("id") == elementToDisableId)

        element.disable = theElementShoulbBeDisabled;

    });

}

function formHandler() {
    const data = {
        "category": getSelectedOptions(".output-type:checked", "checkbox", "class"),
        "title": document.getElementById("title").value,
        "keywords": getSelectedOptions(".output-keywords:checked", "checkbox", "class")
    }

    const startYear = document.getElementById("startYear").innerHTML;
    const endYear = document.getElementById("endYear").innerHTML;

    if (startYear != "2012" || endYear != "2023")
    {
        data.startYear = startYear;
        data.endYear = endYear;
    }

    $.ajax({
        type: 'POST',
        url: '/test-search',
        data,
        beforeSend: function() {

            const loader1 = `
                        <div class="loading-full">
                            <div class="spinner-border loading-icon text-succes"></div>
                            <span class="loading-text">Loading...</span>
                        </div>
                        `;

            const test2 = document.querySelector('#loading');

            test2.insertAdjacentHTML('beforeend', loader1);

            const loadingFull = document.querySelector('.loading-full');

            const loading = new mdb.Loading(loadingFull, {
                scroll: false,
                backdropID: 'full-backdrop'
            });


        },
        success: function(data, status) {

            const loadingFull = document.querySelector('.loading-full');

            const loading = mdb.Loading.getInstance(loadingFull);

            const backdrop = document.querySelector('#full-backdrop');

            const output = data[0].filteredResults;

            let link, publisherSpan;

            var table = $('#outputs_table').DataTable();

            // Removes all rows from the table
            table.clear();

            // Create the rows of the table using the data that cames from the request
            output.forEach(element => {

                var authorName = (element.authors.length != 0) ? element.authors[0].name :
                    "<mark> Sem Autor <mark>"

                const year = (element.conference_year) ?? element.polymorphic
                    .conference_year

                link = generateLink(element.doi)

                publisherSpan = generateSpanElement(showPublisher(element), true)

                table.row.add([
                    `<p class="fw-normal mb-2">${authorName}</p>
        <p class="fw-normal mb-2">${element.title + ", " + year}</p> ${publisherSpan}
        ${link} `,
                    ` <span class="badge badge-success rounded-pill d-inline ">${(element.status)?? "Sem Estado"}</span>`,
                    ` <span class="badge badge-success rounded-pill d-inline ">${(element.type.name)?? "Sem Estado"}</span>`,
                ]);

            });

            // Show the added rows
            table.draw();

            backdrop.remove();
            loadingFull.remove();
            document.body.style.overflow = ''
        },
        error: function(data, status) {

        }
    });

    return false;
}

function submitForm()
{
    const formId = "form-filter"

    document.getElementById(formId).submit = formHandler;
    document.getElementById(formId).submit();
}

function isValidUrl(string) {

    let success = true;

    try {
        new URL(string);
    } catch (err) {
        success = false;
    }

    return success
}

function generateLink(string, returnLinkAsString = true) {
    let [url, link] = ["https://www.doi.org/" + string, ""]


    if (string != "") {
        if (isValidUrl(string)) {
            url = string
        }

        if (returnLinkAsString) {
            link = `<a href ="${url}">Aceder à Publicação</a>`
        } else {
            link = document.createElement("a")

            link.hreaf = url
        }
    }



    return link
}

function showPublisher(element) {
    return (element.publisher) ?? "Sem Publicador"
}

function generateSpanElement(content, success = true) {
    let spanColor = (success) ? "success" : "danger"

    contentToshow = (content != "") ?
        `<span class="badge badge-${spanColor} rounded-pill d-inline">${content}</span>` : ""

    return contentToshow
}

/*

Javascript Event Listners Section

*/
window.addEventListener('DOMContentLoaded', (event) => {
    yearsRange();

    const rangeSliderHand = document.getElementsByClassName("multi-range-slider-hand")

    Array.from(rangeSliderHand).forEach(function(element) {
        element.addEventListener('mouseup', submitForm);
    });

});


// Shows The values of the range 
basicExample.addEventListener('value.mdb.multiRangeSlider', (handler) => {

    // Values 
    let [first, second] = handler.values.rounded;

    first += 2012;
    second += 2012;

    const filteredElement = document.getElementById("years-to-search");

    // Checks if the span with the years to search exists
    if (filteredElement === null)
    {

        const attributes = {
            "type": "button",
            "class": "chip filtred-filds",
            "parent-id": "date-to-search",
            "id": "years-to-search",
            "parent-type": "year"
        }

        createElement("DIV", "filtred-filds", `${first} - ${second} dasdasdwdwdwdwd `, "id",
            "date-to-search", attributes);
    }
    else
    {
        filteredElement.innerHTML = `${first} - ${second}`;
    }

    // Updates the range values
    oneRangeValueBasic.innerHTML = `<span class="d-flex justify-content-between flex-row p-2">
                                        <div id="startYear">${first}</div>
                                        <div id="endYear">${second}</div>
                                    </span>
                                    `;
});


/* 

JQuery Section

*/
$(document).ready(function() {

    const buttons = ["copy", "csv", "excel", "pdf", "print"];

    let buttonsToInsert = [];

    buttons.forEach(element => {
        buttonsToInsert.push({
            extend: element,
            className: "btn-jstabales",
            removeClass: "btn-group"
        });
    });

    const table = $('#outputs_table').DataTable({
        buttons: buttonsToInsert,
        searching: false
    });

    table.buttons().container().appendTo('#datatables-buttons');

});

$(":checkbox").change(function(handler) {

    const checkBoxChecked = handler.currentTarget.checked;
    let checkBoxId = handler.currentTarget.id;

    if (checkBoxChecked) {
        let value = handler.currentTarget.previousSibling.parentElement.children[1].innerText;

        const attributes = {
            "type": "button",
            "class": "chip filtred-filds",
            "parent-id": checkBoxId,
            "parent-type": "checkBox"
        }

        createElement("DIV", "filtred-filds", `${value} <i class="fas fa-times fa-lg">`, "parent-id",
            checkBoxId, attributes);
    } else {
        deleteElement("parent-id", checkBoxId);
    }

    submitForm();
});

/*

Code Used to show the tool tip when the user searches by title

*/
$("#search-by-title-button").click(function(handler) {

    const titleToSearch = document.getElementById("title").value;

    const titleId = document.getElementById("title").id;

    const attributes = {
        "type": "button",
        "class": "chip filtred-filds",
        "parent-id": titleId,
        "parent-type": "input"
    }

    createElement("DIV", "filtred-filds", `${titleToSearch} <i class="fas fa-times fa-lg">`, "parent-id", titleId, attributes);

    submitForm();
});


/*

Prevents problems when the user makes enter in the input box

*/
$("#form-filter").on("submit", function(event) {

    submitForm();

    event.preventDefault();
});

const outputsByYear = {{ Js::from($outputsByYear) }};

function getOutputCount(array) {
    let sum = 0;

    console.log(array);

    array.forEach(element => {

        console.log(element);

        sum += parseInt(element["numberOfOutputs"])

    });

    console.log(sum);

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

function buildData(outputsByYear) {
    let chartJsDataset, graphCoordinates;

    // Builds the initial dataset only for each type
    chartJsDataset = {
        label: "Publications",
        data: []
    };

    outputsByYear.forEach(output => {

        graphCoordinates = {
            x: output["year"],
            y: output["numberOfOutputs"]
        }

        chartJsDataset["data"].push(graphCoordinates);
    });

    return chartJsDataset;
}

const data = {
    labels: getYearsToShow(outputsByYear),
    datasets: [buildData(outputsByYear)]
};

const ctx = document.getElementById('myChart');

myChart = new Chart(ctx, {
    type: 'line',
    data: data,
    options: {
        responsive: true,
        plugins: {
            legend: {
                position: 'top',
            },
            title: {
                display: true,
                text: 'Publicações em cada ano'
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

// Function runs on chart type select update
function updateChartType() {
    // Since you can't update chart type directly in Charts JS you must destroy original chart and rebuild
    myChart.destroy();

    myChart = new Chart(ctx, {
        type: document.getElementById("chartType").value,
        data: data,
        options: {
            responsive: true,
            plugins: {
                legend: {
                    position: 'top',
                },
                title: {
                    display: true,
                    text: 'Publicações em cada ano'
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
};

window.jsPDF = window.jspdf.jsPDF;

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
    pdf.addImage($(pdfCanvas)[0], 'PNG', 50, 50);

    // download the pdf
    pdf.save('filename.pdf');
});


/*

Functions done 

*/
window.onload = (event) => {
    getOutputCount(outputsByYear)
};