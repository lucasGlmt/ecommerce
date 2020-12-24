// Gestion dynamique du panier
var request;

$("#cart").click(function(event){

    event.preventDefault();

    if (request) {
        request.abort();
    }

    request = $.ajax({
        url: "getCart.php",
        type: "get",
    });

    request.done(function (response, textStatus, jqXHR){
        response = JSON.parse(response)
        response.forEach((article) => {
            $("#cartContent").append("<p><b>"+article.idProduit+"</b> - quantité : "+article.qte+"</p>")
        })
    });

    request.fail(function (jqXHR, textStatus, errorThrown){
        
    });


    request.always(function () {
        $("#loading").fadeOut();
    });

});
