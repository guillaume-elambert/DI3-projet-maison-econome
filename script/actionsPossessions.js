var regexImmeuble = /^immeuble-([0-9]+)/;

function ajaxDateFinPossession(immeuble) {
    
    var idImmeuble = immeuble.id;
    
    if (regexImmeuble.test(idImmeuble) && (idImmeuble = idImmeuble.match(regexImmeuble)[1])) {
        $.ajax({
            url: 'ajax/setDateFinPossession.php',
            type: 'POST',
            dataType: 'text',
            data: "idImmeuble=" + idImmeuble,
            success: function (data) {
                try {
                    var output = JSON.parse(data);
                } catch (e) {
                    console.log(data);
                    alert("Output is not valid JSON: " + data);
                    return;
                }

                immeuble.parentNode.removeChild(immeuble);

                if (output['success']) {
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