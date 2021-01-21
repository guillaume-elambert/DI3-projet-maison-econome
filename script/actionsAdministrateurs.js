function ajaxSupprimerUtilisateur(utilisateur) {

    var mailUtilisateur = utilisateur.id;
    var regex = /^utilisateur-([\w.-]+@[\w.-]+\.[a-zA-Z]{2,6})/;

    if (regex.test(mailUtilisateur) && (mailUtilisateur = mailUtilisateur.match(regex)[1])) {
        
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

                if(output['success']) {
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

    var mailUtilisateur = utilisateur.id;
    var regexMail = /^utilisateur-([\w.-]+@[\w.-]+\.[a-zA-Z]{2,6})/;
    var regexPHP = /^(\?uc=[\w-]+)&action=[\w-]+/
    var searchURL = $(location).attr("search");

    if (regexMail.test(mailUtilisateur) && (mailUtilisateur = mailUtilisateur.match(regexMail)[1])) {
        if(searchURL && searchURL != ""){
            if(regexPHP.test(searchURL)){
                var resRegexPHP = searchURL.match(regexPHP);
                var toRedirect = resRegexPHP[1]+"&action=modifier-utilisateur&user="+mailUtilisateur;
                $(location).attr("search", toRedirect);
            }
        }
    }
}