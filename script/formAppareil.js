//champ de recherche pour l'appareil
var rechercheAppareil = $('#rechercheAppareil');
//liste déroulante pour les appareils
var selectTypeAppareil = $('#selectTypeAppareil');
//timer identifier
var typingTimer = null;
//time in ms
var doneTypingInterval = 500;



function ajaxAppareil() {
    var value = rechercheAppareil.val();

    $.ajax({
        url: 'ajax/chercherTypeAppareil.php',
        type: 'POST',
        dataType: 'text',
        data: "recherche=" + value,
        success: function (data) {
            try {
                var output = JSON.parse(data);
            } catch (e) {
                console.log(data);
                selectTypeAppareil.attr('disabled', true);
                alert("Output is not valid JSON: " + data);
                return;
            }

            selectTypeAppareil.empty();
            selectTypeAppareil.append(new Option('--- Veuillez choisir le type de l\'appareil ---', "", false, false));

            $.each(output, function (i, obj) {
                selectTypeAppareil.append(new Option(obj.libelleTypeAppareil, obj.idTypeAppareil, false, false));
            });

            selectTypeAppareil.removeAttr('disabled');

        },
        error: function (request, error) {
            selectTypeAppareil.attr('disabled', true);
            alert("AJAX Call Error: " + error);
        }
    });

}

ajaxAppareil();

//Lors de la saisie du champ de recherche de la ville
//on attend doneTypingInterval ms avant d'appeler
//la fonction ajax de recherche de la ville
rechercheAppareil.bind("keyup paste", function () {
    clearTimeout(typingTimer);
    typingTimer = setTimeout(ajaxAppareil, doneTypingInterval);
});