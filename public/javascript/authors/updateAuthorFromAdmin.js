document.addEventListener('DOMContentLoaded', function () {
    const container = document.getElementById('admin-update-message-container');
    if (!container) return;

    function showMessage(html, type = 'info') {
        const toast = document.createElement('div');
        toast.className = `toast-message ${type}`;
        toast.innerHTML = html;
        container.appendChild(toast);

        setTimeout(() => toast.classList.add('show'), 10);
        setTimeout(() => toast.classList.remove('show'), 4000);
        setTimeout(() => toast.remove(), 4600);
    }

    document.querySelectorAll('.update-author-cv').forEach((button) => {
        button.addEventListener('click', function () {
            const authorId = this.getAttribute('data-author-id');
            if (!authorId) return;

            const originalHtml = this.innerHTML;
            this.disabled = true;
            this.innerHTML = originalHtml +
                '<span class="spinner-border spinner-border-sm ms-2" role="status"></span>';

            showMessage('<i class="fas fa-info-circle"></i> A atualizar o autor...', 'info');

            const controller = new AbortController();
            const timeoutId = setTimeout(() => controller.abort(), 180000);

            fetch(`/authors/update/${authorId}`, { signal: controller.signal })
                .then(async (response) => {
                    const data = await response.json().catch(() => ({}));
                    if (!response.ok) {
                        const errorMessage = data.message || 'Erro ao atualizar o autor.';
                        throw new Error(errorMessage);
                    }
                    return data;
                })
                .then((data) => {
                    const message = data.message || 'Atualizacao concluida.';
                    showMessage(`<i class="fas fa-check"></i> ${message}`, 'success');
                })
                .catch((error) => {
                    const fallbackMessage = error.name === 'AbortError'
                        ? 'Atualizacao a demorar. Pode voltar a carregar mais tarde.'
                        : error.message;
                    showMessage(`<i class="fas fa-times-circle"></i> ${fallbackMessage}`, 'error');
                })
                .finally(() => {
                    clearTimeout(timeoutId);
                    this.disabled = false;
                    this.innerHTML = originalHtml;
                });
        });
    });
});
