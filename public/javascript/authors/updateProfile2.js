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
        const batchSize = 10;

        const statusPanel = document.getElementById('update-all-status');
        const statusText = document.getElementById('update-all-status-text');
        const progressText = document.getElementById('update-all-progress-text');
        const etaText = document.getElementById('update-all-eta-text');
        const errorsWrapper = document.getElementById('update-all-errors-wrapper');
        const errorsList = document.getElementById('update-all-errors');

        const tokenMeta = document.querySelector('meta[name="csrf-token"]');
        const csrfToken = tokenMeta ? tokenMeta.getAttribute('content') : '';

        function updateStatusPanel(data) {
            if (!statusPanel) return;
            statusPanel.style.display = 'block';

            const processed = data.processed ?? 0;
            const total = data.total ?? 0;
            const updated = data.updated ?? 0;
            const failed = data.failed ?? 0;
            const skipped = data.skipped ?? 0;
            const priv = data.private ?? 0;
            const status = data.status ?? '-';

            if (statusText) {
                statusText.textContent = status;
            }

            if (progressText) {
                progressText.textContent = `Processados ${processed}/${total}. Atualizados ${updated}, Falharam ${failed}, Ignorados ${skipped}, Privados ${priv}.`;
            }

            if (etaText) {
                let etaValue = '-';
                const startedAt = data.startedAt ? new Date(data.startedAt) : null;
                if (startedAt && processed > 0) {
                    const elapsedMs = Date.now() - startedAt.getTime();
                    const rate = processed / (elapsedMs / 1000);
                    const remaining = Math.max(total - processed, 0);
                    if (rate > 0) {
                        const etaSeconds = Math.ceil(remaining / rate);
                        const etaMinutes = Math.floor(etaSeconds / 60);
                        const etaRemainder = etaSeconds % 60;
                        etaValue = `${etaMinutes}m ${etaRemainder}s`;
                    }
                }
                etaText.textContent = etaValue;
            }

            if (errorsWrapper && errorsList) {
                errorsList.innerHTML = '';
                if (Array.isArray(data.errors) && data.errors.length > 0) {
                    errorsWrapper.style.display = 'block';
                    data.errors.forEach((err) => {
                        const li = document.createElement('li');
                        li.textContent = err;
                        errorsList.appendChild(li);
                    });
                } else {
                    errorsWrapper.style.display = 'none';
                }
            }
        }

        function pollStatus(runId) {
            const pollInterval = 5000;

            const timer = setInterval(function () {
                $.ajax({
                    type: 'GET',
                    url: `/authors/update-all-job/${runId}`,
                    success: function (data) {
                        updateStatusPanel(data);

                        if (data.status === 'completed') {
                            clearInterval(timer);
                            btn.disabled = false;
                            btn.innerHTML = originalHtml;
                            showAllMessage('<i class="fas fa-check"></i> Atualizacao de todos os autores concluida.', 'success');
                        }
                    },
                    error: function () {
                        clearInterval(timer);
                        btn.disabled = false;
                        btn.innerHTML = originalHtml;
                        showAllMessage('<i class="fas fa-times-circle"></i> Erro ao obter estado do processo.', 'error');
                    }
                });
            }, pollInterval);
        }

        showAllMessage(
            '<i class="fas fa-info-circle"></i> A iniciar atualizacao em background. Isto pode demorar alguns minutos...',
            'info'
        );

        $.ajax({
            type: 'POST',
            url: '/authors/update-all-job',
            data: { batchSize: batchSize },
            headers: { 'X-CSRF-TOKEN': csrfToken },
            success: function (data) {
                const runId = data.runId;
                if (!runId) {
                    btn.disabled = false;
                    btn.innerHTML = originalHtml;
                    showAllMessage('<i class="fas fa-times-circle"></i> Run inválido.', 'error');
                    return;
                }
                pollStatus(runId);
            },
            error: function (xhr) {
                btn.disabled = false;
                btn.innerHTML = originalHtml;

                let errorMessage = 'Erro ao iniciar atualizacao em background.';
                if (xhr.responseJSON && xhr.responseJSON.message) {
                    errorMessage = xhr.responseJSON.message;
                }
                showAllMessage('<i class="fas fa-times-circle"></i> ' + errorMessage, 'error');
            }
        });
    });
});
