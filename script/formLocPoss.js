//Champ de recherche pour la ville
var rechercheVille = $('#rechercheVille');
//Liste déroulante pour la ville
var selectVille = $('#selectVille');

//Champ de recherche pour la rue
var rechercheRue = $('#rechercheRue');
//Liste déroulante pour la rue
var selectRue = $('#selectRue');

//Champ de recherche pour l'immeuble
var rechercheImmeuble = $('#rechercheImmeuble');
//Liste déroulante pour l'immeuble
var selectImmeuble = $('#selectImmeuble');

//Liste déroulante pour l'appartement
var selectAppartement = $('#selectAppartement');

//Champs à prendre en compte avant la validation (on ne prend pas en compte les champs de recherche)
var fieldNotSearchField = $(".champ:not(#ville, #rue, #immeuble, #appartement, #situation) input");

//Liste de tous les champs que l'utilisateur doit saisir
var champs = $.merge($.merge([], fieldNotSearchField), $("select, input[type=radio]"));

var estProprietaire = false;
var radioLocataire = $("#locataire");
var radioProprietaire = $("#proprietaire");

var tableAppartement = $("#appartement");

//Nombre de champs total (-1 car sinon on compte les 2 radios)
var totalChamps = champs.length - 1;
var nbChampsAttendus = totalChamps;
/*
totalChamps = window.totalChamps;
nbChampsAttendus = window.nbChampsAttendus;*/

var validBtn = $(":submit");
var form = $('form');

//timer identifier
var typingTimer = null;
//time in ms
var doneTypingInterval = 500;

/*-------------------------------------------------------*/
/*-------------- VERIF FORM OK POUR ENVOI ---------------*/
/*-------------------------------------------------------*/

/**
 * Fonction qui active/désactive le bouton de validation du formulaire en fonction
 * du contenu du formulaire et de l'état du bouton de validation
 *  => Si l'un des champs est vide : on bloque le formulaire
 *  => Sinon on débloque le formulaire
 */
function changeFormState() {
    var isOk = true;
    var stateBtn = validBtn.attr("disabled");

    //Si estProprietaire est à true => on ne doit pas prendre en compte le champ de saisie de l'appartement
    var notAppartement = estProprietaire ? ":not(#appartement)" : "";

    //Ensemble des champs qui doivent être remplis
    var selected = $.merge($.merge([], fieldNotSearchField), $("tr" + notAppartement + " option:selected, input[type=radio]:checked"));


    //Entrée : on a bien le nombre de champs attendus
    //  => On vérifie que les champs ne soient pas vide
    if (selected.length == nbChampsAttendus) {
        //Parcours de tous les champs de saisie
        $.each($.merge($.merge([], fieldNotSearchField), selected), function (i, obj) {
            if (!$(obj).val() || $(obj).val() == "") {
                isOk = false;
                return;
            }
        });
    } else isOk = false;


    //Entrée : le formulaire est valide
    //      ET le bouton de validation est désactivé
    //  => on réactive le bouton
    if (isOk && stateBtn) {
        validBtn.removeAttr("disabled title");
    }
    //Entrée : le formulaire n'est pas valide
    //      ET le bouton de validation est cliquable
    //  => on désactive le bouton de validation du formulaire
    else if (!isOk && !stateBtn) {
        validBtn.attr({
            "disabled": "true",
            "title": "Veuillez remplir le formulaire"
        });
    }
}

//Si l'utilisateur se défini comme locataire on fait en sorte
//que le champs de saisie de l'appartement apparraisse  et 
//soit pris en compte dans la validation du formulaire
$(radioLocataire).change(function () {
    tableAppartement.removeClass("invisible");
    selectAppartement.attr('required', true);
    estProprietaire = false;
    nbChampsAttendus = totalChamps;

    if (!rechercheImmeuble.attr("disabled") || rechercheImmeuble.attr("disabled") == false) {
        ajaxImmeubles();
    }

    changeFormState();
});

//Si l'utilisateur se défini comme propriétaire on fait en sorte
//que le champs de saisie de l'appartement n'apparraisse pas et 
//ne soit pas pris en compte dans la validation du formulaire
$(radioProprietaire).change(function () {
    tableAppartement.addClass("invisible");
    selectAppartement.removeAttr('required');
    estProprietaire = true;
    --nbChampsAttendus;

    if (!rechercheImmeuble.attr("disabled") || rechercheImmeuble.attr("disabled") == false) {
        ajaxImmeubles();
    }

    changeFormState();
});

//Detection changements sur les champs du formulaire
//Si l'un des champs est vide : on bloque le formulaire
$(champs).change(function () {
    changeFormState();
});



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

    resetImmeuble();

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
        rechercheImmeuble.removeAttr("disabled");
        ajaxImmeubles();
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



/*-------------------------------------------------------*/
/*--------------- AJAX RECHERCHE IMMEUBLE ---------------*/
/*-------------------------------------------------------*/

function ajaxImmeubles() {

    var value = rechercheImmeuble.val();
    var valueRue = selectRue.find("option:selected").val();
    var url;
    resetAppartement();

    if (estProprietaire) {
        url = 'ajax/chercherImmeublesLibresDansRue.php';
    } else {
        url = 'ajax/chercherImmeubleDansRue.php';
    }

    $.ajax({
        url: url,
        type: 'POST',
        dataType: 'text',
        data: "rue=" + valueRue + "&recherche=" + value,
        success: function (data) {
            try {
                var output = JSON.parse(data);
            } catch (e) {
                console.log(data);
                selectImmeuble.attr('disabled', true);
                alert("Output is not valid JSON: " + data);
                return;
            }

            selectImmeuble.empty();
            selectImmeuble.append(new Option('--- Veuillez choisir un immeuble ---', "", false, false));

            //On ajoute tous les résultats de l'appel à la fonction AJAX dans la liste déroulante
            $.each(output, function (i, obj) {
                selectImmeuble.append(new Option("Numéro " + obj.numeroImmeuble, obj.idImmeuble, false, false));
            });

            //On rend possible la séléction de l'immeuble
            selectImmeuble.removeAttr('disabled');
        },
        error: function (request, error) {
            selectImmeuble.attr('disabled', true);
            alert("AJAX Call Error: " + error);
        }
    });
};


//Lors de la saisie du champ de recherche de l'immeuble
//on attend doneTypingInterval ms avant d'appeler
//la fonction ajax de recherche de l'immeuble
rechercheImmeuble.bind("keyup paste", function () {
    clearTimeout(typingTimer);
    if (this.value) {
        typingTimer = setTimeout(ajaxImmeubles, doneTypingInterval);
    }
});

//Lors de la séléction d'un immeuble, on met à jour
//les champs qui dépendent de l'immeuble
selectImmeuble.bind("change", function () {
    if ($(this).val() != "") {
        selectImmeuble.removeAttr("disabled");
        if (!estProprietaire) {
            ajaxAppartements();
        }
    } else {
        resetAppartement();
    }
});

/**
 * Fonction qui désactive et qui vide les champs
 *  de recherche et de séléction de l'immeuble
 */
function resetImmeuble() {
    rechercheImmeuble.attr("disabled", true);
    rechercheImmeuble.val("");
    selectImmeuble.attr("disabled", true);
    selectImmeuble.empty();
    resetAppartement();
}



/*-------------------------------------------------------*/
/*---------------- AJAX LISTER APPARTEMENT --------------*/
/*-------------------------------------------------------*/

function ajaxAppartements() {

    var valueImmeuble = selectImmeuble.find("option:selected").val();

    $.ajax({
        url: 'ajax/listerAppartementsLibresDansImmeuble.php',
        type: 'POST',
        dataType: 'text',
        data: "immeuble=" + valueImmeuble,
        success: function (data) {
            try {
                var output = JSON.parse(data);
            } catch (e) {
                console.log(data);
                selectAppartement.attr('disabled', true);
                alert("Output is not valid JSON: " + data);
                return;
            }

            selectAppartement.empty();
            selectAppartement.append(new Option('--- Veuillez choisir un appartement ---', "", false, false));

            //On ajoute tous les résultats de l'appel à la fonction AJAX dans la liste déroulante
            $.each(output, function (i, obj) {
                selectAppartement.append(new Option("Numéro " + obj.idAppartement, obj.idAppartement, false, false));
            });

            //On rend possible la séléction de l'appartement
            selectAppartement.removeAttr('disabled');
        },
        error: function (request, error) {
            selectAppartement.attr('disabled', true);
            alert("AJAX Call Error: " + error);
        }
    });

};

/**
 * Fonction qui désactive et qui vide le champs de séléction de l'appartement
 */
function resetAppartement() {
    selectAppartement.attr("disabled", true);
    selectAppartement.empty();
    changeFormState();
}

//Lors de la séléction d'un appartement, on met à jour
//l'etat du formulaire si sa valeur n'est pas le champs
//par défaut
$(selectAppartement).bind("change", function () {
    if ($(this).val() != "") {
        changeFormState();
    }
});



changeFormState();

//On sauvegarde l'état du tableau lors du chargement
form.data('serialize', form.serialize());

//On sauvegarde l'état du tableau avant envoie (éviter message erreur)
form.bind('submit', function (e) {
    form.data('serialize', form.serialize());
});

//On affiche une popup quand tentative de fermeture alors que
//des modifications ont été effectuées
$(window).bind('beforeunload', function (e) {
    if (form.serialize() != form.data('serialize')) return true;
    else e = null;
});