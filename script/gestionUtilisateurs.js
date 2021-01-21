var rechercheUtilisateur = $("#rechercheUtilisateur");
var divTableUtilisateurs = $("#divTableUtilisateurs");
var btnRechercheUser = $("#btnRechercheUser");

//Variable qui contient le contenu de la recherche
var currentSearch = "";

//timer identifier
var typingTimer = null;
//time in ms
var doneTypingInterval = 500;


//Lors de la saisie du champ de recherche de la ville
//on attend doneTypingInterval ms avant d'appeler
//la fonction ajax de recherche de la ville
rechercheUtilisateur.bind("keyup paste", function () {
    clearTimeout(typingTimer);

    //Entrée : le contenu du champ de recherche à changé depuis dernière modif
    if (currentSearch != $(this).val()) {
        currentSearch = $(this).val()
        typingTimer = setTimeout(ajaxUtilisateur, doneTypingInterval);
    }
});

//Lors de la séléction d'une ville, on met à jour
//les champs qui dépendent de la ville
rechercheUtilisateur.bind("change search", function () {

    //Entrée : le contenu du champ de recherche à changé depuis dernière modif
    if (currentSearch != $(this).val()) {
        currentSearch = $(this).val()
        ajaxUtilisateur();
    }
});


btnRechercheUser.click(function () {

    //Entrée : le contenu du champ de recherche à changé depuis dernière modif
    if (currentSearch != rechercheUtilisateur.val()) {
        currentSearch = rechercheUtilisateur.val()
        ajaxUtilisateur();
    }
});

function ajaxUtilisateur() {
    var value = rechercheUtilisateur.val();

    if (!value || value == "") {
        value = "%";
    }

    $.ajax({
        url: 'ajax/getTableUtilisateursRecherche.php',
        type: 'POST',
        dataType: 'text',
        data: "recherche=" + value,
        success: function (data) {

            divTableUtilisateurs.html(data);

        },
        error: function (request, error) {
            alert("AJAX Call Error: " + error);
        }
    });
}