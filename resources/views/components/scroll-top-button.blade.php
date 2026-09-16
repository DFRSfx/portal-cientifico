<button type="button" class="fixed bottom-8 right-8 z-50 w-11 h-11 rounded-full bg-emerald-700 hover:bg-emerald-800 text-white shadow-lg hover:shadow-xl items-center justify-center transition-all duration-200 border-0 cursor-pointer" id="btn-back-to-top" style="display: none;" onclick="backToTop()">
    <i class="fas fa-arrow-up"></i>
</button>

@pushOnce('scripts')
    <script data-navigate-once>
        window.addEventListener('scroll', function() {
            const btn = document.getElementById("btn-back-to-top");
            if (!btn) return;
            if (document.body.scrollTop > 20 || document.documentElement.scrollTop > 20) {
                btn.style.display = "flex";
            } else {
                btn.style.display = "none";
            }
        });

        window.backToTop = function() {
            window.scrollTo({ top: 0, behavior: 'smooth' });
        };
    </script>
@endPushOnce
