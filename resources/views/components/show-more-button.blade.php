@props(["tableId" => ""])

<div class="container d-flex justify-content-center">
    <button class="inline-flex items-center gap-1.5 px-4 py-2 text-xs font-semibold text-emerald-700 hover:text-emerald-900 bg-emerald-50/60 hover:bg-emerald-100 rounded-xl transition-all cursor-pointer border-0" type="button" id="show-more-content">
        <span>{{ __('Mostar Mais') }}</span> <i class="fas fa-arrow-down fa-sm" id="grouth-symbol"></i></button>
</div>

<script>

    const TABLEID = {{ Illuminate\Support\Js::from($tableId) }};

    let showMore = true;

    function showButtonMessage() {

        const button = document.getElementById("show-more-content");

        var buttonMessage;

        if (showMore) {
            buttonMessage = 'Show Less <i class="fas fa-arrow-up fa-sm" id="grouth-symbol">';
            showMore = false;
        } else {
            buttonMessage = 'Show More <i class="fas fa-arrow-down fa-sm" id="grouth-symbol">';
            showMore = true;
        }

        button.innerHTML = buttonMessage;
    }

    function ScrollToElement(elementId) {
        const element = document.getElementById(elementId);

        if (showMore) element.scrollIntoView();
    }

    $(document).ready(function() 
    {
        $("#show-more-content").click(function(handler) {

            const tableElements = document.getElementsByClassName("elements-hidden");

            showButtonMessage();

            $(tableElements).slideToggle('fast', "linear", ScrollToElement("collapsed-information"));
        })
    });
</script>
