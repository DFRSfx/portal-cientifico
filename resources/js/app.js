import './bootstrap';

/**
 * Antigravity: Universal UI Interactive Fallback
 * Provides 100% lightweight, dependency-free interactive support for
 * Bootstrap/MDB data attributes (collapses, dropdowns, tabs/pills, dismissals)
 * alongside Alpine.js without requiring bootstrap.js or mdb.min.js.
 */
document.addEventListener('DOMContentLoaded', () => {
    document.addEventListener('click', (e) => {
        // 1. Collapses / Accordions
        const collapseBtn = e.target.closest('[data-mdb-toggle="collapse"], [data-bs-toggle="collapse"]');
        if (collapseBtn) {
            e.preventDefault();
            const targetSelector = collapseBtn.getAttribute('data-mdb-target') ||
                                   collapseBtn.getAttribute('data-bs-target') ||
                                   collapseBtn.getAttribute('href');
            if (targetSelector && targetSelector.startsWith('#')) {
                const targetEl = document.querySelector(targetSelector);
                if (targetEl) {
                    targetEl.classList.toggle('show');
                    const isShown = targetEl.classList.contains('show');
                    collapseBtn.setAttribute('aria-expanded', isShown ? 'true' : 'false');
                    if (isShown) {
                        collapseBtn.classList.remove('collapsed');
                    } else {
                        collapseBtn.classList.add('collapsed');
                    }
                }
            }
            return;
        }

        // 2. Dropdowns
        const dropdownBtn = e.target.closest('[data-mdb-toggle="dropdown"], [data-bs-toggle="dropdown"]');
        if (dropdownBtn) {
            e.preventDefault();
            const parent = dropdownBtn.closest('.dropdown');
            if (parent) {
                const menu = parent.querySelector('.dropdown-menu');
                if (menu) {
                    const isOpen = menu.classList.toggle('show');
                    dropdownBtn.setAttribute('aria-expanded', isOpen ? 'true' : 'false');
                }
            }
            return;
        } else if (!e.target.closest('.dropdown-menu')) {
            document.querySelectorAll('.dropdown-menu.show').forEach((menu) => {
                menu.classList.remove('show');
                const p = menu.closest('.dropdown');
                if (p) {
                    const btn = p.querySelector('[aria-expanded="true"]');
                    if (btn) btn.setAttribute('aria-expanded', 'false');
                }
            });
        }

        // 3. Tabs & Pills
        const tabBtn = e.target.closest('[data-mdb-toggle="tab"], [data-bs-toggle="tab"], [data-mdb-toggle="pill"], [data-bs-toggle="pill"]');
        if (tabBtn) {
            e.preventDefault();
            const targetSelector = tabBtn.getAttribute('href') ||
                                   tabBtn.getAttribute('data-mdb-target') ||
                                   tabBtn.getAttribute('data-bs-target');
            if (targetSelector && targetSelector.startsWith('#')) {
                const nav = tabBtn.closest('.nav');
                if (nav) {
                    nav.querySelectorAll('.nav-link').forEach((link) => {
                        link.classList.remove('active');
                        link.setAttribute('aria-selected', 'false');
                    });
                }
                tabBtn.classList.add('active');
                tabBtn.setAttribute('aria-selected', 'true');

                const targetEl = document.querySelector(targetSelector);
                if (targetEl) {
                    const container = targetEl.closest('.tab-content') || targetEl.parentElement;
                    if (container) {
                        container.querySelectorAll('.tab-pane').forEach((pane) => {
                            pane.classList.remove('show', 'active');
                        });
                    }
                    targetEl.classList.add('show', 'active');
                }
            }
            return;
        }

        // 4. Dismiss Alerts & Modals
        const dismissBtn = e.target.closest('[data-mdb-dismiss="alert"], [data-bs-dismiss="alert"], [data-mdb-dismiss="modal"], [data-bs-dismiss="modal"]');
        if (dismissBtn) {
            const alert = dismissBtn.closest('.alert');
            if (alert) {
                alert.style.transition = 'opacity 0.2s ease-out';
                alert.style.opacity = '0';
                setTimeout(() => alert.remove(), 200);
            }
            const modal = dismissBtn.closest('.modal');
            if (modal) {
                modal.classList.remove('show');
                modal.style.display = 'none';
            }
            return;
        }
    });
});
