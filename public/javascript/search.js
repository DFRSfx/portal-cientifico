function checkIfElementExists (propriety, valueToSearch) {
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

function isObjectDefined (obj) {
    return Object.keys(obj).length !== 0
}

function createElement (
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
        Object.keys(attributes).forEach(function (key, index) {
            // sets the attributes in the object
            newElement.setAttribute(key, this[key])
        }, attributes)
    }

    newElement.innerHTML = innerHTMLValue

    newElement.addEventListener('click', function () {
        uncheckOption(attributes)
    })

    document.getElementById(targetId).appendChild(newElement)
}

function dropElement (className, typeToDrop, valueToDrop, dropAll = false) {
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

function deleteElement (propriety, valueToSearch) {
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

function uncheckOption (attributes) {
    if (!isObjectDefined(attributes)) return

    let useAttribute = attributes['parent-id'] ?? -1

    const elementToUncheck = document.getElementById(attributes['parent-id'])

    if (attributes['parent-type'] === 'checkBox') {
        elementToUncheck.click()
    } else if (attributes['parent-type'] === 'option') {
        mdb.Select.getInstance(
            document.getElementById(elementToUncheck.parentElement.id)
        ).dispose()

        elementToUncheck.selected = false

        deleteElement('parent-id', attributes['parent-id'])

        new mdb.Select(
            document.getElementById(elementToUncheck.parentElement.id)
        )
    }
}

function getSelectedOptions (targetClassOrId, targetType, typeOfIdentifier) {
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

function formHandler () {
    const keywords = getSelectedOptions(
        '.keywords:checked',
        'checkbox',
        'class'
    )

    const data = {
        domainActivity: getSelectedOptions(
            '.topics:checked',
            'checkbox',
            'class'
        ),
        language: getSelectedOptions('.languages:checked', 'checkbox', 'class'),
        keywords: keywords
    }

    $.ajax({
        type: 'GET',
        url: document.getElementById('form-filter').action,
        data,
        success: function (data, status) {
            var table = $('#authors_table').DataTable()

            // Removes all rows from the table
            table.clear()

            // Create the rows of the table using the data that cames from the request
            data.authors.forEach(element => {
                const perfileLink =
                    '/authors/' + element['id']

                table.row.add([
                    `
                 <div class="d-flex align-items-center">
                    <img src="https://www.cienciavitae.pt/fotos/publico/${element.ciencia_vitae}.jpg" onerror="this.onerror=null;this.src='/logo/user.jpg';" style="width: 45px; height: 45px" class="rounded-circle" alt="gfvg">
                                        
                                            <div class="ms-3">
                                            <p class="fw-bold mb-1">${element['name']}</p>
                                            <p class="text-muted mb-0">${element['email']}</p>
                                          </div>
                                        </div>`,
                    '<span class="badge badge-success rounded-pill d-inline">Teacher</span>',
                    `<a href="${perfileLink}">Ver perfil</a>`
                ])
            })

            // Show the added rows
            table.draw()
        },
        error: function (data, status) {}
    })

    return false
}

function submitForm () {
    const formId = 'form-filter'

    document.getElementById(formId).submit = formHandler
    document.getElementById(formId).submit()
}


function initializeDataTable (buttons) {
    let buttonsToInsert = []

    buttons.forEach(element => {
        buttonsToInsert.push({
            extend: element,
            className: 'btn-jstabales',
            removeClass: 'btn-group'
        })
    })

    const TABLE = $('#authors_table').DataTable({
        buttons: buttonsToInsert
    })

    TABLE.buttons().container().appendTo('#datatables-buttons')
}

/*
        
JQuery
        
*/
$(document).ready(function () {
    initializeDataTable(['copy', 'csv', 'excel', 'pdf', 'print'])

    $(':checkbox').change(function (handler) {
        const checkBoxChecked = handler.currentTarget.checked

        let checkBoxId = handler.currentTarget.id

        if (checkBoxChecked) {
            let value =
                handler.currentTarget.previousSibling.parentElement.children[1]
                    .innerText

            const attributes = {
                type: 'button',
                class: 'chip filtred-filds',
                'parent-id': checkBoxId,
                'parent-type': 'checkBox'
            }

            createElement(
                'DIV',
                'filtred-filds',
                `${value} <i class="fas fa-times fa-lg">`,
                'parent-id',
                checkBoxId,
                attributes
            )
        } else {
            deleteElement('parent-id', checkBoxId)
        }

        submitForm()
    })

    $('.select').change(function (handler) {
        const selectedValues = handler.currentTarget

        const chipFields = document.getElementById('filtred-filds').children

        let seletedOption

        for (let i = 0; i < selectedValues.length; i++) {
            let chipElementExists =
                document.querySelectorAll(
                    `[parent-id="${selectedValues[i].id}"]`
                ).length > 0

            if (chipElementExists && !selectedValues[i].selected) {
                deleteElement('parent-id', selectedValues[i].id)
            } else if (selectedValues[i].selected) {
                createElement(
                    'DIV',
                    'filtred-filds',
                    `${selectedValues[i].id} <i class="fas fa-times fa-lg">`,
                    'parent-id',
                    selectedValues[i].id,
                    {
                        type: 'button',
                        class: 'chip filtred-filds',
                        'parent-id': selectedValues[i].id,
                        'parent-type': 'option',
                        useAttribute: true
                    }
                )
            }
        }

        submitForm()
    })
})
