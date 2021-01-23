<?php

$actions = array(
    'possessions' => array(
        'mettreFin' => array(
            'class'     =>    'action fas fa-times red',
            'onclick'   =>    'ajaxDateFinPosession(this.closest(\'tr\'));',
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
            'onclick'   =>    'redirectModificationAppareil(this.closest(\'tr\'));',
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
    'admin' => array(
        'supprimerUser' => array(
            'class'     =>    'action fas fa-times red',
            'onclick'   =>    'ajaxSupprimerUtilisateur(this.closest(\'tr\'));',
            'title'     =>    'Supprimer l\'utilisateur'
        ),
        'modifierUser'  => array(
            'class'     =>    'action fas fa-edit',
            'onclick'   =>    'redirectModificationUtilisateur(this.closest(\'tr\'))',
            'title'     =>    'Modifier l\'utilisateur'
        )
    )
);
