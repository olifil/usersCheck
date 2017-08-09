$(document).ready(function(){

    // Suppression du flashBag
    setTimeout(function() {
        $('#flashBag').fadeOut(1000);
    }, 3000);

    // Envoie du formulaire
    $('#check').on('submit', function(event){
        event.preventDefault();

        $('#searchResult').html('');

        var prenom = $('#prenom').val();
        var nom = $('#nom'). val();

        if ( prenom == "" || nom == "" ) {
            console.log('Il y a au moins un champs vide');
            return;
        }

        $.ajax({
            url: Routing.generate('macif.user.check'),
            type: "GET",
            data: {
                prenom: prenom,
                nom: nom
            },
            onBeforeSend: function() {
                // Mise en place du loader
                $('#search-icon')
                    .removeClass('fa-search')
                    .addClass('fa-spinner fa-pulse');
            },
            success: function(response) {
                console.log(response.data);
                console.log(response.data.length);
                var html;

                if (response.type) {
                    html = "<p class=\"response\">Il y a <span class=\"highlight\">" + response.data.length + "</span> utilisateur(s) inscrit(s) à Rezo Pouce et correspondant(s) aux valeurs saisies.</p>";
                } else {
                    html = "<p class=\"response\">Il n'y a pas d'utilisateur correspondant exactement à vos critère par contre, il y a <span class=\"highlight\">" + response.data.length + "</span> utilisateur(s) inscrit(s) à Rezo Pouce et approchant(s) les valeurs saisies.</p>";
                }

                html += "<table class=\"table \"><thead><tr><th class=\" text-center\">Nom</th><th class=\" text-center\">Prénom</th><th class=\" text-center\"><span class=\"help\" title=\"Champs de vérification\">Date de naissance<span></th></tr></thead><tbody>";
                $.each(response.data, function(index, value){
                    html += "<tr><td class=\"text-center\">" + value.nom + "</td><td class=\"text-center\">" + value.prenom + "</td><td class=\"manual-check text-center\">" + value.dateNaissance + "</td></tr>";
                });
                console.log(html);
                html += "</tbody></table>";
                $('#searchResult').html(html);
            },
            error: function(response, xlr) {
                var html = "<p class=\"response highlight\">Il n'y a pas d'utilisateur Rezo Pouce associé aux valeurs saisies.</p>"
                $('#searchResult').html(html);
            },
            complete: function(response) {
                // Suppression du loader
                $('#search-icon')
                    .removeClass('fa-spinner fa-pulse')
                    .addClass('fa-search');
            }
        });

    });
})
