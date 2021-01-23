<?php

$actions = array(
    'possessions' => array(
        'mettreFin' => array(
            'class'     =>    'action fas fa-times red',
            'onclick'   =>    'ajaxDateFinPossession(this.closest(\'tr\'));',
            'title'     =>    'Déclarer mettre fin à la possession'
        )
    ),
    'locations' => array(
        'voirAppareils' => array(
            'class'     =>    'action fas fa-laptop-house',
            'onclick'   =>    'ajaxGetTableAppareilsAppart(this.closest(\'tr\'))',
            'title'     =>    'Voir les appareils de l\'appartement'
        ),
        'ajouterAppareil' => array(
            'class'     =>    'action fas fa-plus',
            'onclick'   =>    'redirectAjoutAppareil(this.closest(\'tr\'));',
            'title'     =>    'Ajouter un appareil à l\'appartement'
        ),
        'voirConso' => array(
            'class'     =>    'action fas fa-chart-pie',
            'onclick'   =>    'ajaxGetTableConsoAppart(this.closest(\'tr\'));',
            'title'     =>    'Voir la consommation de l\'appartement'
        ),
        'mettreFin' => array(
            'class'     =>    'action fas fa-times red',
            'onclick'   =>    'ajaxDateFinLocation(this.closest(\'tr\'));',
            'title'     =>    'Déclarer mettre fin à la location'
        )
    ),
    'appareils' => array(
        'changerEtat' => array(
            'class'     =>    'action fas fa-power-off',
            'onclick'   =>    'ajaxDateFinFonctionnement(this.closest(\'tr\'));',
            'title'     =>    'Allumer/éteindre l\'appareil'
        ),
        'modifierUser'  => array(
            'class'     =>    'action fas fa-edit',
            'onclick'   =>    'redirectModificationAppareil(this.closest(\'tr\'))',
            'title'     =>    'Modifier les informations de l\'appareil'
        ),
        'mettreFin' => array(
            'class'     =>    'action fas fa-times red',
            'onclick'   =>    'ajaxDeleteAppareil(this.closest(\'tr\'));',
            'title'     =>    'Supprimer cet appareil'
        )
    ),
    'admin' => array(
        'supprimerUser' => array(
            'class'     =>    'action fas fa-times red',
            'onclick'   =>    'ajaxSupprimerUtilisateur(this.closest(\'tr\'));',
            'title'     =>    'Supprimer l\'utilisateur'
        ),
        'modifierUser'  => array(
            'class'     =>    'action fas fa-edit',
            'onclick'   =>    'redirectModificationUtilisateur(this.closest(\'tr\'))',
            'title'     =>    'Modifier les informations de l\'utilisateur'
        )
    )
);
