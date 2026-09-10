<button type="button" class="fixed bottom-8 right-8 z-50 w-11 h-11 rounded-full bg-emerald-700 hover:bg-emerald-800 text-white shadow-lg hover:shadow-xl items-center justify-center transition-all duration-200 border-0 cursor-pointer" id="btn-back-to-top" style="display: none;" onclick="backToTop()">
    <i class="fas fa-arrow-up"></i>
</button>

@pushOnce('scripts')
    <script>
        $(function() {
            var intervalID = setInterval(function() {
                //  Do whatever in here that happens every 3 seconds
            }, 3000);

            setTimeout(function() {
                clearInterval(intervalID);
            }, 30000);
        });

        //Get the button
        let mybutton = document.getElementById("btn-back-to-top");

        // When the user scrolls down 20px from the top of the document, show the button
        window.onscroll = function() {
            scrollFunction();
        };

        function scrollFunction() 
        {
            if (document.body.scrollTop > 20 || document.documentElement.scrollTop > 20) {
                mybutton.style.display = "block";
            } else {
                mybutton.style.display = "none";
            }
        }

        function backToTop() {
            document.body.scrollTop = 0;
            document.documentElement.scrollTop = 0;
        }
    </script>
@endPushOnce
