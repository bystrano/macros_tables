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

/* retourne les critères de boucle à ajouter selon les colonnes */
function macrotable_calculer_criteres ($colonnes, $tri_defaut, $pagination) {

  $criteres = '';

  foreach ($colonnes as $colonne) {
    $calculer_criteres =
      charger_fonction($colonne['colonne'] . '_criteres', 'colonnes');

    $criteres .= $calculer_criteres($colonne);
  }

  if ($tri_defaut) {
    $criteres .= '{tri ' . array_shift(array_keys($tri_defaut)) . ',#GET{defaut_tri}}';
  }

  if ($pagination) {
    $criteres .= '{pagination ' . $pagination['pas'] . '}';
  }

  return $criteres;
}