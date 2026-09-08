$.ajaxSetup({ headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') } });


function setUserLocation(languageToSet) {
    let defaultLanguage;

    const browserLanguage = navigator.language || navigator.userLanguage;

    defaultLanguage = "en";

    // Checks if the default language of the user is PT
    if (browserLanguage === "pt-PT") defaultLanguage = "pt";

    if(languageToSet === null)
    {
        location.href = 'https://portalcientifico.islagaia.pt/lang/' + defaultLanguage;
    }

    //alert("oi");
}

function getUserLocation() {

    $.ajax({
        type: 'GET',
        url: "https://portalcientifico.islagaia.pt/sessionlocation",
        success: function (data, status) {

            LanguageInSession = data["userLocation"];
            
            setUserLocation(LanguageInSession);
        }
    });

}


document.onload = getUserLocation();




