//Cacher écran chargement
$(".loading").hide();
$("#navBar").css("z-index", "11");

//Ecran chargement quand ajax
$(document).ajaxStart(function () {
    $(".loading").show();
});

$(document).ajaxStop(function () {
    $(".loading").hide();
});

/*-------------------------------------------------------*/
/*----------------------- NAV BAR -----------------------*/
/*-------------------------------------------------------*/

var navBar = document.getElementById('navBar');
var subnavigation = navBar.querySelectorAll('.parentWithSubNav');


subnavigation.forEach(parent => {
    parent.addEventListener('click', function () {
        this.classList.toggle("active");

        //On passe tous les éléments parents (sauf celui cliqué) en invisible
        navBar.querySelectorAll(".parent:not(#" + this.id + ")").forEach(parent => {
            parent.classList.toggle("invisible");
        });
    }, false)
});


function openPopUp() {
    $(".overlay").addClass("active");
}

$(".close").click(function () {
    $(".overlay").removeClass("active");
});

$(".overlay .background").click(function () {
    $(".overlay").removeClass("active");
})