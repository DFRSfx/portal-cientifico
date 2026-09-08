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

            setTimeout(() => toast.addClass('show'), 10);

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
