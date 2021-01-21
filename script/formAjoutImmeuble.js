//Champ de recherche pour la ville
var rechercheVille = $('#rechercheVille');
//Liste déroulante pour la ville
var selectVille = $('#selectVille');

//Champ de recherche pour la rue
var rechercheRue = $('#rechercheRue');
//Liste déroulante pour la rue
var selectRue = $('#selectRue');


var immeuble = $("#immeuble");

var validBtn = $(":submit");
var form = $('form');

//timer identifier
var typingTimer = null;
//time in ms
var doneTypingInterval = 500;

/*-------------------------------------------------------*/
/*---------------- AJAX RECHERCHE VILLE -----------------*/
/*-------------------------------------------------------*/

function ajaxVille() {
    var value = rechercheVille.val();

    //Entrée : le champ de recherche est n'est pas vide
    if (value != "") {
        resetRue();

        $.ajax({
            url: 'ajax/chercherVille.php',
            type: 'POST',
            dataType: 'text',
            data: "recherche=" + value,
            success: function (data) {
                try {
                    var output = JSON.parse(data);
                } catch (e) {
                    console.log(data);
                    selectVille.attr('disabled', true);
                    alert("Output is not valid JSON: " + data);
                    return;
                }

                selectVille.empty();
                selectVille.append(new Option('--- Veuillez choisir une ville ---', "", false, false));

                $.each(output, function (i, obj) {
                    selectVille.append(new Option(obj.cp + " - " + obj.nomVille, obj.idVille, false, false));
                });

                selectVille.removeAttr('disabled');

            },
            error: function (request, error) {
                selectVille.attr('disabled', true);
                alert("AJAX Call Error: " + error);
            }
        });
    }
}

//Lors de la saisie du champ de recherche de la ville
//on attend doneTypingInterval ms avant d'appeler
//la fonction ajax de recherche de la ville
rechercheVille.bind("keyup paste", function () {
    clearTimeout(typingTimer);
    if (this.value) {
        typingTimer = setTimeout(ajaxVille, doneTypingInterval);
    }
});

//Lors de la séléction d'une ville, on met à jour
//les champs qui dépendent de la ville
selectVille.bind("change", function () {
    if ($(this).val() != "") {
        rechercheRue.removeAttr("disabled");
        ajaxRues();
    } else {
        resetRue();
    }
});



/*-------------------------------------------------------*/
/*----------------- AJAX RECHERCHE RUE ------------------*/
/*-------------------------------------------------------*/

function ajaxRues() {

    var value = rechercheRue.val();
    var valueVille = selectVille.find("option:selected").val();
    immeuble.attr("disabled", true);


    $.ajax({
        url: 'ajax/chercherRueDansVille.php',
        type: 'POST',
        dataType: 'text',
        data: "ville=" + valueVille + "&recherche=" + value,
        success: function (data) {
            try {
                var output = JSON.parse(data);
            } catch (e) {
                console.log(data);
                selectRue.attr('disabled', true);
                alert("Output is not valid JSON: " + data);
                return;
            }

            selectRue.empty();
            selectRue.append(new Option('--- Veuillez choisir une rue ---', "", false, false));

            //On ajoute tous les résultats de l'appel à la fonction AJAX dans la liste déroulante
            $.each(output, function (i, obj) {
                selectRue.append(new Option(obj.nomRue, obj.idRue, false, false));
            });

            //On rend possible la séléction de la rue
            selectRue.removeAttr('disabled');
        },
        error: function (request, error) {
            selectRue.attr('disabled', true);
            alert("AJAX Call Error: " + error);
        }
    });

};

//Lors de la saisie du champ de recherche de la rue
//on attend doneTypingInterval ms avant d'appeler
//la fonction ajax de recherche de la rue
rechercheRue.bind("keyup paste", function () {
    clearTimeout(typingTimer);
    if (this.value) {
        typingTimer = setTimeout(ajaxRues, doneTypingInterval);
    }
});

//Lors de la séléction d'une rue, on met à jour
//les champs qui dépendent de la rue
selectRue.bind("change", function () {
    if ($(this).val() != "") {
        immeuble.removeAttr("disabled");
    } else {
        resetImmeuble();
    }
});

/**
 * Fonction qui désactive et qui vide les champs
 *  de recherche et de séléction de la rue
 */
function resetRue() {
    rechercheRue.attr("disabled", true);
    rechercheRue.val("");
    selectRue.attr("disabled", true);
    selectRue.empty();
    resetImmeuble();
}

function resetImmeuble(){
    immeuble.val("")
    immeuble.attr("disabled", true);
}