$.ajaxSetup({ headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') } });


// targetForm|nome da opção
var opcoesTipoByCategoria = {
    publicacao: ["books/create|Book", "conference_paper/create|Conference Paper", "valor3|Cuttack"],
    Maharashtra: ["Mumbai", "Pune", "Nagpur"],
    Kerala: ["kochi", "Kanpur"]
}

/** Gera as opções dos tipos tendo em conta uma categoria escolhida
 *@param string categoria
 */
function makeSubmenu(categoria)
{
    var tipos = (opcoesTipoByCategoria[categoria])?? 0;

    if(tipos != 0)
    {
        var opcoes = "<option selected>Escolha um Tipo</option>";

        // Percorre as opções da categoria
        for (indexTipo in tipos)
        {
            // Cria um array tendo em base um array
            var array = tipos[indexTipo].split("|");

            // Se não existirem pelo menos dois elementos no array a opção não pode ser gerada
            if(array.length != 2) continue;

            // valores da opção
            var value = array[0];
            var name = array[1];

            opcoes += "<option value='"+ value + "'>" + name + "</option>";
        }//for

        // Mostra as novas opções 
        document.getElementById("tipoSelect").innerHTML = opcoes;

    }//if
    else
    {
        document.getElementById("tipoSelect").innerHTML = "<option selected>Escolha um Tipo</option>";
    }//else

}//makeSubmenu

$(document).ready(function() {
      $("#success-alert").fadeTo(2000, 500).slideUp(500, function() {
        $("#success-alert").slideUp(500);
      });
  });


/** Limpa as opções escolhidas
*
 */
function resetSelection()
{
    document.getElementById("categoriaSelect").selectedIndex = 0;
    document.getElementById("tipoSelect").selectedIndex = 0;
}//resetSelection

$( window ).on( "load", resetSelection );


$('#tipoSelect').on('change', function (event) 
{
    // Avoid the link click from loading a new page
    event.preventDefault();

    var tipoSelecionado = this.value;

    window.location.href = tipoSelecionado;

    //alert(document.URL + "#bookForm");

    //$('#bookForm').load(tipoSelecionado + "#bookForm");

    /*
    var formVisivel = $(".visible-form");

    // Valida se existe algum formulário vísivel
    if(formVisivel != null)
    {
        formVisivel.css("display", "none");
        formVisivel.removeClass("visible-form");
    }//if
    
    var targetForm = $("#" + tipoSelecionado);

    targetForm.css("display", "block");
    targetForm.addClass("visible-form");

    */

});


$('input[type="checkbox"]').change(function(){
    this.value = (Number(this.checked));
});


let requestSent = false;

$("#buttonUpdate").on("click", function(event){

    if(requestSent === false)
    {
        requestSent = true;

        const d = document.getElementById("buttonUpdate");

        d.innerHTML += '<span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span>';

        $.ajax({
            url: "https://portalcientifico.islagaia.pt",
            type: 'GET',
            dataType: 'json', // added data type
            success: function(res) {
                console.log(res);
                alert(res);
                requestSent = false;
            }
        });

    }//if

    

  //  <span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span>Loading...

})

// numbero de campos

// sempre que um capo é preenchido o númoer de campos deve de ser desatualizado

// 

const requiredFIelds = document.querySelectorAll("form [required]");

let cont = 0;

const fields = [];

Array.from(requiredFIelds).forEach(function (item){
    item.addEventListener("focusin", function(event){focusFunction(event)},true);
    item.addEventListener("focusout", function(event){focusFunction(event)},true);
})

function focusFunction(event)
{
    
    if(!fields.includes(event.target.id) && event.target.value !== "")
    {
        cont++

        fields.push(event.target.id)
        console.log(event.target.value);
    }//if

}

/**
 * Hidden
 * showing
 * 
 * visivle-form
 */