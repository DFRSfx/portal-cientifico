<button type="button" class="btn btn-success btn-floating btn-lg" id="btn-back-to-top"
    style="position: fixed;
    bottom: 32px;
    right: 32px;
    display: none;" onclick="backToTop()">
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
