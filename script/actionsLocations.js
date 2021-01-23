var regexAppartement = /^immeuble-([0-9]+)-appartement-([0-9]+)/;
var regexPHP = /^(\?uc=[\w-]+)&action=[\w-]+/;

var divPopUp = $("#divPopUp");
var titrePopUp = divPopUp.find("#titrePopUp");
var popUpContent = divPopUp.find(".popup-content");

var searchURL = $(location).attr("search");


function ajaxDateFinLocation(appartement) {

    var idAppartement = appartement.id;
    var idImmeuble;

    var resRegex = idAppartement.match(regexAppartement);


    if (regexAppartement.test(idAppartement) && (idImmeuble = resRegex[1]) && (idAppartement = resRegex[2])) {
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

    appartement = appartement.id;
    var resRegex = appartement.match(regexAppartement);

    var idAppartement;
    var idImmeuble;

    if (regexAppartement.test(appartement) && (idImmeuble = resRegex[1]) && (idAppartement = resRegex[2])) {
        $.ajax({
            url: 'ajax/getTableConsommationAppart.php',
            type: 'POST',
            dataType: 'text',
            data: "idImmeuble=" + idImmeuble + "&idAppartement=" + idAppartement,
            success: function (data) {
                openPopUp();
                titrePopUp.html("Consommation de l'appartement " + idAppartement);
                popUpContent.html(data);

            },
            error: function (request, error) {
                alert("AJAX Call Error: " + error);
            }
        });
    }
}



function ajaxGetTableAppareilsAppart(appartement) {

    var idAppartement = appartement.id;
    var idImmeuble;

    var resRegex = idAppartement.match(regexAppartement);

    if (regexAppartement.test(idAppartement) && (idImmeuble = resRegex[1]) && (idAppartement = resRegex[2])) {
        $.ajax({
            url: 'ajax/getTableAppareilsAppartement.php',
            type: 'POST',
            dataType: 'text',
            data: "idImmeuble=" + idImmeuble + "&idAppartement=" + idAppartement,
            success: function (data) {
                openPopUp();
                titrePopUp.html("Appareils de l'appartement " + idAppartement);
                popUpContent.html(data);
                initEventsListeAppareils();

            },
            error: function (request, error) {
                alert("AJAX Call Error: " + error);
            }
        });
    }
}



function redirectAjoutAppareil(appartement) {

    
    appartement = appartement.id;
    var resRegex = appartement.match(regexAppartement);

    var idAppartement;
    var idImmeuble;

    if (regexAppartement.test(appartement) && (idImmeuble = resRegex[1]) && (idAppartement = resRegex[2])) {
        if (searchURL && searchURL != "") {
            if (regexPHP.test(searchURL)) {
                var resRegexPHP = searchURL.match(regexPHP);
                var toRedirect = resRegexPHP[1] + "&action=ajouter-un-appareil&immeuble=" + idImmeuble + "&appartement=" + idAppartement;
                $(location).attr("search", toRedirect);
            }
        }
    }
}