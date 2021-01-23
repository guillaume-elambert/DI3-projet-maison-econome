var regexPiece = /^immeuble-([0-9]+)-appartement-([0-9]+)(-piece-([0-9]+))?/;
var selectPieceListeAppareils; // = $("#selectPieceListAppareils");
var divTableListeAppart; // = $("#divTableListeAppart");

function initEventsListeAppareils() {

    selectPieceListeAppareils = $("#selectPieceListeAppareils");
    divTableListeAppart = $("#divTableListeAppart");

    selectPieceListeAppareils.change(function () {
        ajaxGetAppareilsPiece($(this).val());
    });
}


function ajaxGetAppareilsPiece(piece) {

    var resRegex = piece.match(regexPiece);
    console.log(resRegex);

    var idPiece = piece.id;
    var idAppartement;
    var idImmeuble;

    if (
        regexPiece.test(piece) &&
        (idImmeuble = resRegex[1]) &&
        (idAppartement = resRegex[2]) &&
        (idPiece = resRegex[4] || !resRegex[3])
    ) {
        var url = 'ajax/getTableAppareilsPiece.php';
        var data = "idImmeuble=" + idImmeuble + "&idAppartement=" + idAppartement + "&idPiece=" + idPiece;

        if (idPiece == "") {
            url = 'ajax/getTableAppareilsAppartement.php';
            data = "idImmeuble=" + idImmeuble + "&idAppartement=" + idAppartement;
        }

        $.ajax({
            url: url,
            type: 'POST',
            dataType: 'text',
            data: data,
            success: function (data) {

                divTableListeAppart.html(data);

            },
            error: function (request, error) {
                alert("AJAX Call Error: " + error);
            }
        });
    }
}