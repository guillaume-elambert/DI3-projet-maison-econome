<?php

$actions = array(
    'immeubles' => array(
        'mettreFin' => array(
            'class'     =>    'action fas fa-times red',
            'onclick'   =>    'ajaxDateFin(this.closest(\'tr\'));',
            'title'     =>    'Déclarer mettre fin à la possession'
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
