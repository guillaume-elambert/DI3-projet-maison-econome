/*-------------------------------------------------------*/
/*----------------------- NAV BAR -----------------------*/
/*-------------------------------------------------------*/

var navBar = document.getElementById('navBar');
var subnavigation = navBar.querySelector('#actionsUser');


actionsUser.addEventListener('click', function () {
    actionsUser.classList.toggle("active");

    //On passe tous les éléments parents (sauf celui cliqué) en invisible
    navBar.querySelectorAll(".parent:not(#actionsUser)").forEach(parent => {
        parent.classList.toggle("invisible");
    });
}, false);