<?php

function formulaires_filtre_checkboxes_saisies ($nom, $options=array()) {

    $options['nom'] = $nom;

    return array(
        array(
            'saisie' => 'checkbox',
            'options' => $options,
        ),
    );
}


function formulaires_filtre_checkboxes_charger ($nom, $options=array()) {

    if (_request($nom)) {
        return array(
            $nom => _request($nom),
        );
    } else {
        return array();
    }
}