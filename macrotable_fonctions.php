<?php
/**
 * Fonctions utiles au plugin Macro Table
 *
 * @plugin     Macro Table
 * @copyright  2013
 * @author     Michel Bystranowski
 * @licence    GNU/GPL
 * @package    SPIP\Macrotable\Fonctions
 */

if (!defined('_ECRIRE_INC_VERSION')) return;

include_spip('filtres/multilike');

/* retourne les critères de boucle à ajouter selon les colonnes */
function macrotable_calculer_criteres ($colonnes, $tri_defaut, $pagination, $criteres_extra, $filtres=NULL) {

  $criteres = '';

  foreach ($colonnes as $c) {
    $calculer_criteres_colonne =
      charger_fonction($c['colonne'] . '_criteres', 'colonnes');

    $criteres .= $calculer_criteres_colonne($c);
  }

  if (is_array($filtres)) {
    foreach ($filtres as $f) {
      $calculer_criteres_filtres =
        charger_fonction($f['filtre'], 'filtres');

      $criteres .= $calculer_criteres_filtres($f);
    }
  }

  if ($tri_defaut) {
    $criteres .= '{tri ' . array_shift(array_keys($tri_defaut)) . ',#GET{defaut_tri}}';
  }

  if ($pagination) {
    $criteres .= '{pagination ' . $pagination['pas'] . '}';
  }

  if ($criteres_extra) {
    foreach ($criteres_extra as $critere_extra) {
      $criteres .= $critere_extra;
    }
  }

  return $criteres;
}

/* La fonction qui est appliquée aux tableaux de données des tables DATA */
function macrotable_filtres ($tableau, $recherche, $filtres) {

  $fonctions_match = array();
  foreach ($filtres as $f) {
    $fonctions_match[] = charger_fonction($f['filtre'] . '_match','filtres');
  }
  $resultat = array();

  foreach ($tableau as $ligne) {
    $pass = FALSE;
    foreach($fonctions_match as $i => $match) {
      if ( ! $pass) {
        foreach ($filtres[$i]['options']['champs'] as $champ) {
          if ($match($recherche, $ligne[$champ])) {
            $resultat[] = $ligne ;
            $pass = TRUE;
            break;
          }
        }
      }
    }
  }

  return $resultat;
}

function array2spip ($tableau) {

  $balise = '#ARRAY{';
  foreach ($tableau as $cle => $valeur) {
    if (is_array($valeur)) {
      $balise .= $cle . ',' . array2spip($valeur) . ',';
    } else {
      $balise .= $cle . ',' . $valeur . ',';
    }
  }
  /* on retire la dernière virgule */
  $balise = substr($balise, 0, -1);
  $balise .= '}';

  return $balise;
}