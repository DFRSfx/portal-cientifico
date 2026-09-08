$.ajaxSetup({
    headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') }
})

$('#update-author-information').on('click', function (event) {
    const authorsUpdateButton = document.getElementById(
        'update-author-information'
    )

    $.ajax({
        type: 'GET',
        url: 'https://portalcientifico.islagaia.pt/authors/update/',
        beforeSend: function () {
            authorsUpdateButton.innerHTML +=
                '<span id="spinner-container" class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span>'
        },
        success: function (data, status) {
            $('#spinner-container').remove()

            showMessage(data.message)
            window.location.href = window.location.href;

        },
        error: function (data, status) {
            $('#spinner-container').remove()

            showMessage(data.message)
        }
    })
})

const responseContainerElement = document.getElementById('response-container')

window.onload = event => {

    responseContainerElement.classList.add('alert', 'fade')

    const alert = new mdb.Alert(responseContainerElement, {
        color: 'success',
        stacking: true,
        hidden: true,
        width: '450px',
        position: 'bottom-right',
        autohide: true,
        delay: 5000,
        added: true
    })
    
}

function updateOrAddPropsOfOneObject (object, proprieties) {
    for (var proprietyName in proprieties) {
        object.proprietyName = proprieties.proprietyName
    }
}

function showMessage (message) {
    const alertInstance = Alert.getInstance(responseContainerElement)

    console.log(alertInstance)

    /*


    const mdbElementProprieties = {
        color: 'primary',
        stacking: true,
        hidden: true,
        width: '450px',
        position: 'bottom-right',
        autohide: true,
        delay: 5000,
        added: true
    }

    
    

    console.log(mdb.Alert.getOrCreateInstance(responseContainerElement))

    // if the element dont exists objectAlreadyExists proriety dont exist
    if (alertInstance.hasOwnProperty('objectAlreadyExists'))
    {
        updateOrAddPropsOfOneObject(alertInstance, {
            _config: mdbElementProprieties,
            _options: mdbElementProprieties,
            objectAlreadyExists: true
        })
    }

    */

    // if the user clicks more than once in the button and the alert is still active the alert will bug because the previus animation has not been finished
    if (responseContainerElement.style.display === 'block') {
        alertInstance.hide()
    }

    document.getElementById('sync-with-cienciavitae-response').innerHTML =
        'dsadsad'

    alertInstance.show()
}

function showMessage (message) 
{
    const messageElement = document.getElementById(
        'sync-with-cienciavitae-response'
    )

    messageElement.value = message

    const alertInstance = mdb.Alert.getInstance(responseContainerElement)

    // if the user clicks more than once in the button and the alert is still active the alert will bug because the previus animation has not been finished
    if (responseContainerElement.style.display === 'block') {
        alertInstance.hide()
    }

    alertInstance.show()
}

// Set up the toggle effect:
$('.read-more-show').on('click', function (e) {
    $(this).next('.read-more-content').removeClass('hide_content')
    $(this).addClass('hide_content')
})

//esconde a div com o texto
$('.read-more-hide').on('click', function (e) {
    var p = $(this).parent('.read-more-content')
    p.addClass('hide_content') // adiciona o "esconder" ao css
    p.prev('.read-more-show').removeClass('hide_content') 
    // Hide only the preceding "Read More" esconde a class anterior
})
