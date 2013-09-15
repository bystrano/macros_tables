<?php

function filtres_multilike_dist ($filtre) {

  $opts = $filtre['options'];
  $criteres = '{' . $filtre['filtre'] . ' #ENV{' . $opts['nom_input'] . '},#GET{champs_recherche}}';

  return $criteres;
}

function filtres_multilike_match_dist ($recherche, $champ) {

  include_spip('inc/charsets');

  /* Les accents et la casse n'ont pas d'importance */
  $champ = strtolower(translitteration($champ));
  $recherche = strtolower(translitteration($recherche));
  /* Les espaces entre les mots sont interprétés comme des OU */
  $recherche = implode('|', explode(' ', $recherche));

  return (preg_match('%' . $recherche . '%', $champ) === 1);
}

function critere_multilike_dist ($idb, &$boucles, $crit) {
  $boucle = &$boucles[$idb];
  $table = $boucle->id_table;
  $not = $crit->not;

  /* récupération des paramètres */
  if (isset($crit->param[0])) {
    $recherche = calculer_liste($crit->param[0], array(), $boucles, $boucles[$idb]->id_parent);
  } else { /* parametre obligatoire */
    return (array('zbug_critere_necessite_parametre', array('critere' => $crit->op )));
  }

  if (isset($crit->param[1])) {
    $champs = calculer_liste($crit->param[1], array(), $boucles, $boucles[$idb]->id_parent);
  }

  /* Construction du tableau $where */
  $c = "calculer_where_multilike($table, $recherche, $champs)";

  $boucle->where[] = $c;
}

function calculer_where_multilike ($table, $recherche, $champs) {

  $where = array('LIKE', $table . '.' . array_shift($champs),
                 sql_quote('%' . $recherche . '%'));

  foreach ($champs as $champ) {
    $where = array(
               'OR',
               array('LIKE', $table . '.' . $champ,
                     sql_quote('%' . $recherche . '%')),
               $where,
             );
  }

  return $where;
}