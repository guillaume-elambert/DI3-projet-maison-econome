function ajaxDateFinPossession(immeuble) {

    var idImmeuble = immeuble.id;
    var regex = /^immeuble-([0-9]+)/;

    if (regex.test(idImmeuble) && (idImmeuble = idImmeuble.match(regex)[1])) {
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

                if(output['success']) {
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