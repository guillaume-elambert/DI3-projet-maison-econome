var regex = /^immeuble-([0-9]+)-appartement-([0-9]+)/;
var divConsommationAppart = $("#divConsommationAppart");


function ajaxDateFinLocation(appartement) {

    var idAppartement = appartement.id;
    var idImmeuble;

    if (regex.test(idAppartement) && (idImmeuble = idAppartement.match(regex)[1]) && (idAppartement = idAppartement.match(regex)[2])) {
        $.ajax({
            url: 'ajax/setDateFinLocation.php',
            type: 'POST',
            dataType: 'text',
            data: "idImmeuble=" + idImmeuble + "&idAppartement=" + idAppartement,
            success: function (data) {
                try {
                    var output = JSON.parse(data);
                } catch (e) {
                    console.log(data);
                    alert("Output is not valid JSON: " + data);
                    return;
                }

                appartement.parentNode.removeChild(appartement);

                if (output['success']) {
                    alert(output['success']);
                } else if (output['erreurs']) {
                    alert(output['erreurs']);
                }

            },
            error: function (request, error) {
                alert("AJAX Call Error: " + error);
            }
        });
    }
}


function ajaxGetTableConsoAppart(appartement) {

    var idAppartement = appartement.id;
    var idImmeuble;

    if (regex.test(idAppartement) && (idImmeuble = idAppartement.match(regex)[1]) && (idAppartement = idAppartement.match(regex)[2])) {
        $.ajax({
            url: 'ajax/getTableConsommationAppart.php',
            type: 'POST',
            dataType: 'text',
            data: "idImmeuble=" + idImmeuble + "&idAppartement=" + idAppartement,
            success: function (data) {
                openPopUp();
                divConsommationAppart.find("#titrePopUpConsommation").html("Consommation de l'appartement "+idAppartement);
                divConsommationAppart.find(".popup-content").html(data);

            },
            error: function (request, error) {
                alert("AJAX Call Error: " + error);
            }
        });
    }
}

function ajaxAjouterAppareil(appartement){
    
}

function redirectModificationAppareil(appartement) {

    var idAppartement = appartement.id;
    
    var regexPHP = /^(\?uc=[\w-]+)&action=[\w-]+/
    var searchURL = $(location).attr("search");

    if (regex.test(idAppartement) && (idImmeuble = idAppartement.match(regex)[1]) && (idAppartement = idAppartement.match(regex)[2])) {
        if(searchURL && searchURL != ""){
            if(regexPHP.test(searchURL)){
                var resRegexPHP = searchURL.match(regexPHP);
                var toRedirect = resRegexPHP[1]+"&action=ajouter-un-appareil&immeuble="+idImmeuble+"&appartement="+idAppartement;
                $(location).attr("search", toRedirect);
            }
        }
    }
}