<?php

function formulaires_filtre_multilike_saisies ($nom, $options=array()) {

    $options['nom'] = $nom;

    return array(
        array(
            'saisie' => 'input',
            'options' => $options,
        ),
    );
}


function formulaires_filtre_multilike_charger ($nom, $options=array()) {

    if (_request($nom)) {
        return array(
            $nom => _request($nom),
        );
    } else {
        return array();
    }
}