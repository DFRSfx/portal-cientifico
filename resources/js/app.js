import './bootstrap';
import Alpine from 'alpinejs';

window.Alpine = Alpine;
Alpine.start();

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

/**
 * MDB JavaScript Object Compatibility Layer
 * Provides lightweight drop-in shims for legacy MDB plugin calls:
 * - MultiRangeSlider (converts to modern dual range inputs)
 * - Animate (handles card transitions)
 * - Select (safe disposal and refresh)
 * - Loading
 */
window.mdb = window.mdb || {};

window.mdb.MultiRangeSlider = class {
    constructor(container, options = {}) {
        if (!container) return;
        this.container = container;
        this.min = options.min ?? 0;
        this.max = options.max ?? 100;
        const [startMin, startMax] = options.startValues || [this.min, this.max];
        this.valMin = startMin;
        this.valMax = startMax;

        this.container.innerHTML = `
            <div class="w-full py-2 flex flex-col gap-2">
                <div class="flex items-center gap-3">
                    <input type="range" class="multi-range-slider-hand w-full accent-emerald-600 h-2 bg-slate-200 rounded-lg cursor-pointer"
                           min="${this.min}" max="${this.max}" value="${this.valMin}" id="_range_start_${Math.random().toString(36).substring(2, 7)}">
                    <input type="range" class="multi-range-slider-hand w-full accent-emerald-600 h-2 bg-slate-200 rounded-lg cursor-pointer"
                           min="${this.min}" max="${this.max}" value="${this.valMax}" id="_range_end_${Math.random().toString(36).substring(2, 7)}">
                </div>
            </div>
        `;

        const inputs = this.container.querySelectorAll('input[type="range"]');
        const r1 = inputs[0];
        const r2 = inputs[1];

        const emit = () => {
            let v1 = parseInt(r1.value, 10);
            let v2 = parseInt(r2.value, 10);
            if (v1 > v2) {
                const temp = v1;
                v1 = v2;
                v2 = temp;
            }
            const event = new CustomEvent('value.mdb.multiRangeSlider', {
                detail: {},
                bubbles: true
            });
            event.values = { rounded: [v1, v2] };
            this.container.dispatchEvent(event);
        };

        if (r1 && r2) {
            r1.addEventListener('input', emit);
            r2.addEventListener('input', emit);
            r1.addEventListener('change', emit);
            r2.addEventListener('change', emit);
        }
    }
};

window.mdb.Animate = class {
    constructor(element, options = {}) {
        this.element = element;
        this.options = options;
    }
    init() {}
    startAnimation() {
        if (this.element) {
            this.element.style.display = 'block';
            if (typeof this.options.onStart === 'function') {
                this.options.onStart();
            }
            if (typeof this.options.onEnd === 'function') {
                setTimeout(() => this.options.onEnd(), 300);
            }
        }
    }
    stopAnimation() {
        if (typeof this.options.onEnd === 'function') {
            this.options.onEnd();
        }
    }
    static getInstance(element) {
        return new window.mdb.Animate(element);
    }
};

window.mdb.Select = class {
    constructor(element) {
        this.element = element;
    }
    dispose() {}
    static getInstance(element) {
        return new window.mdb.Select(element);
    }
};

window.mdb.Loading = class {
    constructor(element, options = {}) {
        this.element = element;
    }
    static getInstance(element) {
        return {
            dispose() {}
        };
    }
};
