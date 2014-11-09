<?php

function formulaires_filtre_selection_multiple_saisies ($nom, $options=array()) {

    $options['nom'] = $nom;

    return array(
        array(
            'saisie' => 'selection_multiple',
            'options' => $options,
        ),
    );
}


function formulaires_filtre_selection_multiple_charger ($nom, $options=array()) {

    if (_request($nom)) {
        return array(
            $nom => _request($nom),
        );
    } else {
        return array();
    }
}