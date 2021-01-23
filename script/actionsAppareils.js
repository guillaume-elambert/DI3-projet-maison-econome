var regexAppareil = /^immeuble-([0-9]+)-appartement-([0-9]+)-piece-([0-9]+)-appareil-([0-9]+)/;
var regexPHP = /^(\?uc=[\w-]+)&action=[\w-]+/;

var searchURL = $(location).attr("search");


function ajaxDateFinFonctionnement(appareil) {

    var etat = parseInt(appareil.getAttribute("state"));


    var idAppareil = appareil.id;
    var resRegex = idAppareil.match(regexAppareil);

    var idImmeuble;
    var idAppartement;
    var idPiece;

    if (
        regexAppareil.test(idAppareil) &&
        (idImmeuble = resRegex[1]) &&
        (idAppartement = resRegex[2]) &&
        (idPiece = resRegex[3]) &&
        (idAppareil = resRegex[4])
    ) {
        
        etat = (etat + 1) % 2;

        $.ajax({
            url: 'ajax/setDateFinFonctionnement.php',
            type: 'POST',
            dataType: 'text',
            data: "idImmeuble=" + idImmeuble + "&idAppartement=" + idAppartement + "&idPiece=" + idPiece + "&idAppareil=" + idAppareil + "&etat=" + etat,
            success: function (data) {
                try {
                    var output = JSON.parse(data);
                } catch (e) {
                    console.log(data);
                    alert("Output is not valid JSON: " + data);
                    return;
                }

                if (output['success']) {

                    var stateTD = appareil.getElementsByClassName("state")[0];
                    stateTD.innerHTML = (etat == 1 ? "Allumé" : "Éteint");

                    appareil.setAttribute("state", etat);

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




function ajaxDeleteAppareil(appareil) {


    var idAppareil = appareil.id;
    var resRegex = idAppareil.match(regexAppareil);

    var idImmeuble;
    var idAppartement;
    var idPiece;

    if (
        regexAppareil.test(idAppareil) &&
        (idImmeuble = resRegex[1]) &&
        (idAppartement = resRegex[2]) &&
        (idPiece = resRegex[3]) &&
        (idAppareil = resRegex[4])
    ) {

        $.ajax({
            url: 'ajax/deleteAppareilAppartement.php',
            type: 'POST',
            dataType: 'text',
            data: "idImmeuble=" + idImmeuble + "&idAppartement=" + idAppartement + "&idPiece=" + idPiece + "&idAppareil=" + idAppareil,
            success: function (data) {
                try {
                    var output = JSON.parse(data);
                } catch (e) {
                    console.log(data);
                    alert("Output is not valid JSON: " + data);
                    return;
                }

                if (output['success']) {

                    appareil.parentNode.removeChild(appareil);
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




function redirectModificationAppareil(appareil) {

    var idAppareil = appareil.id;
    var resRegex = idAppareil.match(regexAppareil);

    var idImmeuble;
    var idAppartement;
    var idPiece;

    if (
        regexAppareil.test(idAppareil) &&
        (idImmeuble = resRegex[1]) &&
        (idAppartement = resRegex[2]) &&
        (idPiece = resRegex[3]) &&
        (idAppareil = resRegex[4])
    ) {

        if (searchURL && searchURL != "") {

            //Entrée: le format de l'URL correspond à ce qui est attendu
            if (regexPHP.test(searchURL)) {

                var resRegexPHP = searchURL.match(regexPHP);
                var toRedirect = resRegexPHP[1] + "&action=modifier-appareil&immeuble=" + idImmeuble + "&appartement=" + idAppartement + "&piece=" + idPiece + "&appareil=" + idAppareil;
                $(location).attr("search", toRedirect);

            }
        }
    }
}