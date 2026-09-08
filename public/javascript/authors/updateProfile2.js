document.addEventListener('DOMContentLoaded', function () {
    const tokenMeta = document.querySelector('meta[name="csrf-token"]');
    if (tokenMeta && window.$) {
        $.ajaxSetup({
            headers: { 'X-CSRF-TOKEN': tokenMeta.getAttribute('content') }
        });
    }

    function showAllMessage(html, type = 'info') {
        const container = $('#update-all-message-container');
        if (!container.length) return;

        const toast = $('<div class="toast-message"></div>')
            .addClass(type)
            .html(html);

        container.append(toast);

        setTimeout(() => toast.addClass('show'), 10);
        setTimeout(() => toast.removeClass('show'), 4000);
        setTimeout(() => toast.remove(), 4600);
    }

    $('#update-all-authors').on('click', function () {
        const btn = this;
        const originalHtml = btn.innerHTML;
        btn.disabled = true;
        btn.innerHTML = originalHtml +
            '<span class="spinner-border spinner-border-sm ms-2" role="status"></span>';

        let lastId = 0;
        const batchSize = 10;

        function runBatch() {
            $.ajax({
                type: 'GET',
                url: '/authors/update-all',
                data: { lastId: lastId, batchSize: batchSize },
                success: function (data) {
                    if (data.message) {
                        showAllMessage('<i class="fas fa-info-circle"></i> ' + data.message, 'info');
                    }

                    if (data.done) {
                        btn.disabled = false;
                        btn.innerHTML = originalHtml;
                        showAllMessage('<i class="fas fa-check"></i> Atualizacao de todos os autores concluida.', 'success');
                        setTimeout(() => window.location.reload(), 2000);
                    } else {
                        lastId = data.lastId || lastId;
                        setTimeout(runBatch, 500);
                    }
                },
                error: function (xhr) {
                    btn.disabled = false;
                    btn.innerHTML = originalHtml;

                    let errorMessage = 'Erro ao atualizar todos os autores.';
                    if (xhr.responseJSON && xhr.responseJSON.message) {
                        errorMessage = xhr.responseJSON.message;
                    }
                    showAllMessage('<i class="fas fa-times-circle"></i> ' + errorMessage, 'error');
                }
            });
        }

        showAllMessage(
            '<i class="fas fa-info-circle"></i> A atualizar TODOS os autores em batches. Isto pode demorar alguns minutos...',
            'info'
        );

        runBatch();
    });
});
