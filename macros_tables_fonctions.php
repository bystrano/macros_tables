<?php
/**
 * Fonctions utiles au plugin Macro Table
 *
 * @plugin     Macro Table
 * @copyright  2013
 * @author     Michel Bystranowski
 * @licence    GNU/GPL
 * @package    SPIP\Macros_Tables\Fonctions
 */

if (!defined('_ECRIRE_INC_VERSION')) return;

/**
 * retourne les critères de boucle à ajouter selon les colonnes.
 *
 * @param array $colonnes        Un tableau de colonnes, au format des
 *                               macros table.
 * @param array $tri_defaut      Les sens par défaut pour le tri.
 * @param array $pagination      Les paramètres de pagination.
 * @param array $criteres_extra  Des critères à ajouter en plus.
 * @param array $filtres         Un tableau de filtres, au format des
 *                               macros table
 * @return String                Les critères à ajouter dans la boucle.
 */
function macros_tables_calculer_criteres ($colonnes, $tri_defaut, $pagination, $criteres_extra, $filtres=NULL) {

  $criteres = '';

  foreach ($colonnes as $c) {
    $calculer_criteres_colonne =
      charger_fonction($c['colonne'] . '_criteres', 'colonnes');

    $criteres .= $calculer_criteres_colonne($c);
  }

  if (is_array($filtres)) {
    foreach ($filtres as $f) {
      include_spip('filtres/' . $f['filtre']);
      $calculer_criteres_filtres =
        charger_fonction($f['filtre'] . '_calculer_criteres_objet', 'filtres');

      $criteres .= $calculer_criteres_filtres($f);
    }
  }

  if ($tri_defaut) {
    $cles_tri = array_keys($tri_defaut);
    $criteres .= '{tri ' . array_shift($cles_tri) . ',#GET{defaut_tri}}';
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

/**
 * La fonction qui est appliquée aux tableaux de données des tables DATA.
 *
 * Pour chaque filtre, charge la fonction match correspondante et
 * l'applique aux champs appropriés. Retourne un tableau de toutes les
 * lignes qui ont matché.
 *
 * @param array $tableau     Le tableau a filtrer
 * @param array $recherche   Un tableau contenant les éléments à
 *                             chercher pour les différents filtres.
 * @param array $filtres     Un tableau de filtres, au format des macros
 *                           table.
 * @return array             Le tableau filtré.
 */
function macros_tables_filtres ($tableau, $recherche, $filtres) {

  $fonctions_match = array();
  foreach ($filtres as $f) {
    include_spip('filtres/' . $f['filtre']);
    $fonctions_match[] = charger_fonction($f['filtre'] . '_filtrer_data','filtres');
  }

  if ( ! $fonctions_match) {
    return $tableau;
  }

  $resultat = array();

  foreach ($tableau as $ligne) {
    $pass = FALSE;
    foreach($fonctions_match as $i => $match) {
      if (( ! $pass) AND ( ! $match($filtres[$i], $recherche[$filtres[$i]['options']['nom']], $ligne))) {
        $pass = TRUE; // Si un filtre ne match pas, on ne teste pas les autres.
      }
    }
    if ( ! $pass) {
        $resultat[] = $ligne;
    }
  }

  return $resultat;
}

/**
 * Retourne la balise #ARRAY correspondant à un tableau PHP.
 *
 * Agit récursivement sur les tableaux multi-dimensionnels.
 *
 * @param array $tableau  Un tableau…
 * @return String         Une balise #ARRAY correspondant au tableau.
 */
function array2spip ($tableau) {

  if ( ! is_array($tableau) OR (count($tableau) === 0)) {
    return '#ARRAY';
  }

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

  $champs_prefixes = array();
  foreach ($champs as $champ) {
      if (strpos($champ, '.')) {

          list($table_joint, $champ) = explode('.', $champ);

          include_spip('base/objets');

          $trouver_table = charger_fonction('trouver_table', 'base');

          $depart = array($table,
                          $trouver_table($table));

          $arrivee = array($nom_objet_joint,
                           $trouver_table($table_joint));

          $alias_jointure = calculer_jointure($boucle, $depart, $arrivee);

          spip_log($alias_jointure, 'debug');

          $champs_prefixes[] = "$alias_jointure.$champ";

      } else {
          $champs_prefixes[] = "$table.$champ";
      }
  }

  $champs = $champs_prefixes;

  $where = array('LIKE', array_shift($champs),
                 sql_quote('%' . $recherche . '%'));

  foreach ($champs as $champ) {
    $where = array(
               'OR',
               array('LIKE', $champ,
                     sql_quote('%' . $recherche . '%')),
               $where,
             );
  }

  return $recherche ? $where : null;
}