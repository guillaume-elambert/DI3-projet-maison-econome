function ajaxSupprimerUtilisateur(utilisateur) {

    var mailUtilisateur = utilisateur.id;
    var regex = /^utilisateur-([\w.-]+@[\w.-]+\.[a-zA-Z]{2,6})/;

    if (regex.test(mailUtilisateur) && (mailUtilisateur = mailUtilisateur.match(regex)[1])) {
        console.log(mailUtilisateur);
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