$(document).ready(function() {

    $.ajaxSetup({
        headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') }
    });

    $('#update-author-information').on('click', function () {
        const authorsUpdateButton = document.getElementById('update-author-information');
        const messageContainer = $('#update-message-container');

    
        

        function showToast(message, type = 'info') {
            const toast = $('<div class="toast-message"></div>')
                .addClass(type)
                .html(message);

            messageContainer.append(toast);

            // Força animação de slide
            setTimeout(() => toast.addClass('show'), 10);

            // Remove depois de 4s
            setTimeout(() => toast.removeClass('show'), 4000);
            setTimeout(() => toast.remove(), 4600);
        }

        $.ajax({
            type: 'GET',
            url: 'https://portalcientifico.islagaia.pt/authors/update/',
            beforeSend: function () {
                authorsUpdateButton.innerHTML +=
                    '<span id="spinner-container" class="spinner-border spinner-border-sm ms-2" role="status" aria-hidden="true"></span>';

                showToast('<i class="fas fa-info-circle"></i> O update do Ciência Vitae foi iniciado. Este processo pode demorar alguns segundos...', 'info');
            },
            success: function (data) {
                $('#spinner-container').remove();
                showToast('<i class="fas fa-check"></i> ' + data.message, 'success');

                // Recarrega página (opcional)
                window.location.href = window.location.href;
            },
            error: function (xhr, status, error) {
                $('#spinner-container').remove();

                let errorMessage = "Ocorreu um erro inesperado ao atualizar o perfil.";

                if (xhr.responseJSON && xhr.responseJSON.message) {
                    errorMessage = xhr.responseJSON.message;
                } else if (xhr.responseText) {
                    errorMessage = xhr.responseText;
                } else if (error) {
                    errorMessage = error;
                }

                showToast('<i class="fas fa-times-circle"></i> ' + errorMessage, 'error');
            },
            complete: function () {
                authorsUpdateButton.disabled = false;
            }
        });
    });
});

// Função para mostrar alertas/notifications bonitas
function showMessage(messageContent, errorMessage = false, type = "success") {
    let [messageColor, messageIconClass] = ["success", "fas fa-check me-2"];

    if (errorMessage) {
        messageColor = "danger";
        messageIconClass = "fas fa-times-circle me-3";
    } else if (type === "info") {
        messageColor = "info";
        messageIconClass = "fas fa-info-circle me-2";
    }

    const messageElement = document.getElementById("show-message");
    const alertInstance = mdb.Alert.getOrCreateInstance(messageElement);

    alertInstance.update({
        position: "bottom-right",
        delay: 4000,
        autohide: true,
        width: "600px",
        offset: 20,
        stacking: false,
        appendToBody: true,
        color: messageColor
    });

    messageElement.innerHTML =
        ` <i class="${messageIconClass}"></i> ${messageContent} <button type="button" class="btn-close ms-2" data-mdb-dismiss="alert" aria-label="Close"></button>`;

    alertInstance.show();
}