<?php

function colonnes_champ_criteres_dist($colonne) {

	$criteres = '';
	$opts = $colonne['options'];

	if (isset($opts['filtrer']) and ($opts['filtrer'] == 'oui')) {
	$criteres .= '{' . $opts['nom'] . '?}';
	}

	return $criteres;
}
