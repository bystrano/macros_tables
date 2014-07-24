<?php
/**
 * Fonctions implémentant le filtre multilike
 *
 * @plugin     Macro Table
 * @copyright  2013
 * @author     Michel Bystranowski
 * @licence    GNU/GPL
 * @package    SPIP\Macros_Tables\Filtres\Multilike
 */

if (!defined('_ECRIRE_INC_VERSION')) return;


/**
 * Implémente la fonction calculer_criteres_objet de l'API filtres pour le filtre multilike
 *
 * @param array $filtre
 *      Un tableau représentant une instance de filtre, de la forme :
 *      array(
 *          'filtre'  => 'multilike',
 *          'options' => array(…les options…),
 *      );
 * @return string
 *     Une chaîne de caractères représentant un ou plusieurs
 *     critères. Sera inséré directement dans la boucle.
 */
function filtres_multilike_calculer_criteres_objet_dist ($filtre) {

  $opts = $filtre['options'];
  $criteres = '{' . $filtre['filtre'] . ' #ENV{' . $opts['nom_input'] . '},#GET{champs_recherche}}';

  return $criteres;
}

/**
 * Implémente la fonction filter_data de l'API filtres pour le filtre multilike
 *
 * @param array $filtre
 *      Un tableau représentant une instance du filtre.
 * @param mixed $recherche
 *      Le paramètre définissant la recherche à effectuer.
 * @param array $ligne
 *      Un ligne du tableau à filtrer
 *
 * @return bool
 *     Retourne True si la ligne passée en paramètre passe le filtre,
 *     False sinon.
 */
function filtres_multilike_filtrer_data_dist ($filtre, $recherche, $ligne) {

  include_spip('inc/charsets');

  $recherche = strtolower(translitteration($recherche));
  /* Les espaces entre les mots sont interprétés comme des OU */
  $recherche = implode('|', explode(' ', $recherche));

  foreach ($filtre['options']['champs'] as $champ) {

      /* Les accents et la casse n'ont pas d'importance */
      $ligne[$champ] = strtolower(translitteration($ligne[$champ]));

      if (preg_match('%' . $recherche . '%', $ligne[$champ]) === 1) {
          return TRUE;
      }
  }

  return FALSE;
}


/**
 * Implémente un nouveau critère, qui permet de faire LIKE sql sur plusieurs champs
 *
 * @example <BOUCLE_exemple(ARTICLES){multilike test,#ARRAY{0,chapo,1,texte}}>
 *     N'affichera dans la boucle que les articles qui contiennent le
 *     mot "test" dans leur chapo ou dans leur texte.
 */
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


/**
 * Calculer le WHERE SQL correspondant à un critère multilike
 */
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