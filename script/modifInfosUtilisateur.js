//timer identifier
var typingTimer = null;
//time in ms
var doneTypingInterval = 500;

var validBtn = $(":submit");
var form = $('form');

var champMdp = $("#mdp");
var champVerifMdp = $("#verifMdp");
var champs = $(".champ input");

var totalChamps = champs.length;
var nbChampsAttendus = totalChamps - 2;



function changeFormState() {
    var isOk = true;
    var stateBtn = validBtn.attr("disabled");

    var selected = $(".champ input[required]");

    
    //Entrée : on a bien le nombre de champs attendus
    //  => On vérifie que les champs ne soient pas vide
    if (selected.length == nbChampsAttendus) {
        //Parcours de tous les champs de saisie
        $.each(selected, function (i, obj) {
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



champs.change(changeFormState);

champMdp.bind("keyup", function () {
    clearTimeout(typingTimer);

    if (this.value && this.value != "") {
        typingTimer = setTimeout(activateMdp, doneTypingInterval);
    } else {
        typingTimer = setTimeout(desactivateMdp, doneTypingInterval);
    }
});



champMdp.bind("change paste", function () {
    if (this.value && this.value != "") {
        activateMdp();
    } else {
        desactivateMdp();
    }
});



function activateMdp() {
    champMdp.attr("required", true);
    champVerifMdp.attr("required", true);

    nbChampsAttendus = totalChamps;
    changeFormState();
}



function desactivateMdp() {
    champMdp.removeAttr("required");
    champVerifMdp.removeAttr("required");

    nbChampsAttendus = totalChamps - 2;
    changeFormState();
}

champVerifMdp.change(changeFormState);


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