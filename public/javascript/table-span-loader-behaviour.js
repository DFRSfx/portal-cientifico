function checkIfElementExists(propriety, valueToSearch)
{
    const elements = document.getElementsByClassName('filtred-filds')

    let elementExists = false

    const numberOfElements = elements.length

    for (let i = 0; i < numberOfElements; i++) {
        if (
            elements[i].attributes[propriety] !== undefined &&
            elements[i].attributes[propriety].value === valueToSearch
        ) {
            elementExists = true
            break
        }
    }

    return elementExists
}

function isObjectDefined(obj)
{
    return Object.keys(obj).length !== 0
}

function createElement(
    type,
    targetId,
    innerHTMLValue,
    propriety,
    valueTosearch,
    attributes = {}
) {
    // Checks if the button exists
    const elementExists = checkIfElementExists(propriety, valueTosearch)

    if (elementExists) return

    let newElement = document.createElement(type)

    // Checks if is necessary to add atributes to the element that is going to be created
    if (isObjectDefined(attributes)) {
        // The attributes is passed as one second argument to access the object current postion with "this"
        Object.keys(attributes).forEach(function(key, index) {
            // sets the attributes in the object
            newElement.setAttribute(key, this[key])
        }, attributes)
    }

    newElement.innerHTML = innerHTMLValue

    newElement.addEventListener('click', function() {
        uncheckOption(attributes)
    })

    document.getElementById(targetId).appendChild(newElement)
}

function dropElement(className, typeToDrop, valueToDrop, dropAll = false) {
    const mainLocation = document.getElementsByClassName(className)

    if (dropAll) {
        mainLocation.innerHTML = ''
    } else {
        // Removes all elements with a especific value and tag
        mainLocation.forEach(element => {
            if (
                element.tagName === typeToDrop &&
                element.value === valueToDrop
            ) {
                element.remove()
                return
            }
        })
    }
}

function deleteElement(propriety, valueToSearch) {
    const elements = document.getElementsByClassName('filtred-filds')

    const numberOfElements = elements.length

    for (let i = 0; i < numberOfElements; i++) {
        if (
            elements[i].attributes[propriety] !== undefined &&
            elements[i].attributes[propriety].value === valueToSearch
        ) {
            elements[i].remove()
            break
        }
    }
}

function uncheckOption(attributes, shouldSubmitForm = true)
{
    if (!isObjectDefined(attributes)) return

    let useAttribute = attributes['element-id'] ?? -1

    const elementToUncheck = document.getElementById(attributes['element-id'])

    switch (attributes['parent-type']) {
        case "checkBox":
            elementToUncheck.checked = false

            deleteElement('element-id', attributes['element-id'])
            break;
        case "option":
            if (window.mdb && window.mdb.Select && typeof window.mdb.Select.getInstance === 'function') {
                const parentEl = document.getElementById(elementToUncheck.parentElement?.id);
                if (parentEl) {
                    const inst = window.mdb.Select.getInstance(parentEl);
                    if (inst && typeof inst.dispose === 'function') inst.dispose();
                }
            }

            elementToUncheck.selected = false;
            deleteElement('element-id', attributes['element-id']);

            if (window.mdb && window.mdb.Select && typeof window.mdb.Select === 'function') {
                const parentEl = document.getElementById(elementToUncheck.parentElement?.id);
                if (parentEl) new window.mdb.Select(parentEl);
            }
            break;
        case "input":
            document.getElementById(attributes['element-id']).value = ""

            deleteElement('element-id', attributes['element-id'])

            break;
        case "year":
            document.getElementById("startYear").innerHTML = oldestDate

            document.getElementById("endYear").innerHTML = new Date().getFullYear()

            document.getElementById("multi-range-container").innerHTML = ""

            const newDiv = document.createElement("div")

            newDiv.setAttribute("id", "multi-range")
            newDiv.setAttribute("data-mdb-tooltips", "true")
            newDiv.setAttribute("class", "multi-ranges-basic")

            document.getElementById("multi-range-container").appendChild(newDiv)

            yearsRange()

            deleteElement('parent-type', "year")

            break
    }

    if (shouldSubmitForm) {
        submitForm()
    }

}

function getSelectedOptions(targetClassOrId, targetType, typeOfIdentifier) {
    let array = []

    let targetElement

    if (typeOfIdentifier == 'class') {
        targetElement = document.querySelectorAll(targetClassOrId)
    }

    targetElement.forEach(element => {
        let valueToAdd = targetType == 'keywords' ? element.id : element.value

        array.push(valueToAdd)
    })

    return array
}

function clearSelectedElements() {
    const elementsLength = document.getElementById("filtred-filds").children.length

    for (let i = 0; i < elementsLength; i++) {
        uncheckOption({
            "element-id": document.getElementById("filtred-filds").children[0].getAttribute("element-id"),
            "parent-type": document.getElementById("filtred-filds").children[0].getAttribute("parent-type")
        }, false)
    }

    submitForm()
}

/*

loader

*/
// function initializeLoader()
// {

//     if(mdb.Loading.getInstance(document.querySelector('.loading-full')) == null)
//     {
//         const loader1 = `<div class="loading-full">
//                             <div class="spinner-border loading-icon text-succes"></div>
//                             <span class="loading-text">Loading ...</span>
//                          </div>`;

//         const loadingContainer = document.querySelector('#loading');

//         loadingContainer.insertAdjacentHTML('beforeend', loader1);

//         const loadingFull = document.querySelector('.loading-full');

//         new mdb.Loading(loadingFull, {
//             scroll: false,
//             backdropID: 'full-backdrop'
//         });
//     }

// }

function removeLoader()
{
    const loadingFull = document.querySelector('.loading-full')

    const loading = mdb.Loading.getInstance(loadingFull)

    const backdrop = document.getElementById('full-backdrop')

    if (loadingFull == null || document.querySelectorAll('.loading-backdrop')[0] == null) {
        setTimeout(() => {
            removeLoader()
        }, 2000)
    } else {
        document.querySelectorAll('.loading-backdrop')[0].remove()
        loadingFull.remove()
        document.body.style.overflow = ''
    }
}
