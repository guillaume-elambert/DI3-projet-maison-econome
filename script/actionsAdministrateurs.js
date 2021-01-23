var regexMail = /^utilisateur-([\w.-]+@[\w.-]+\.[a-zA-Z]{2,6})/;
var regexPHP = /^(\?uc=[\w-]+)&action=[\w-]+/;
var searchURL = $(location).attr("search");

function ajaxSupprimerUtilisateur(utilisateur) {

    var mailUtilisateur = utilisateur.id;
    var resRegex = mailUtilisateur.match(regexMail);
    

    //Entrée: le paramètre est bien un mail
    if (regexMail.test(mailUtilisateur) &&
        (mailUtilisateur = resRegex[1])
    ) {

        $.ajax({
            url: 'ajax/deleteUtilisateur.php',
            type: 'POST',
            dataType: 'text',
            data: "mailUtilisateur=" + mailUtilisateur,
            success: function (data) {
                try {
                    var output = JSON.parse(data);
                } catch (e) {
                    console.log(data);
                    alert("Output is not valid JSON: " + data);
                    return;
                }

                utilisateur.parentNode.removeChild(utilisateur);

                if (output['success']) {
                    alert(output['success']);
                } else if (output['erreurs']) {
                    alert(output['erreurs']);
                }

            },
            error: function (request, error) {
                selectVille.attr('disabled', true);
                alert("AJAX Call Error: " + error);
            }
        });
    }
}


function redirectModificationUtilisateur(utilisateur) {

    utilisateur = utilisateur.id;
    var resRegexMail = utilisateur.match(regexMail);

    var mailUtilisateur;

    //Entrée: le paramètre est bien un email
    if (regexMail.test(utilisateur) && (mailUtilisateur = resRegexMail[1])) {

        if (searchURL && searchURL != "") {

            //Entrée: le format de l'URL correspond à ce qui est attendu
            if (regexPHP.test(searchURL)) {

                var resRegexPHP = searchURL.match(regexPHP);
                var toRedirect = resRegexPHP[1] + "&action=modifier-utilisateur&user=" + mailUtilisateur;
                $(location).attr("search", toRedirect);

            }
        }
    }
}