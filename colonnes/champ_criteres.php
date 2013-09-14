<?php

function colonnes_champ_criteres_dist ($colonne) {

  $criteres = '';
  $opts = $colonne['options'];

  if ($opts['filtrer'] == 'oui') {
    $criteres .= '{' . $opts['nom'] . '?}';
  }

  return $criteres;
}