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
function filtres_multilike_calculer_criteres_objet_dist ($filtre, $recherche=NULL) {

  $opts = $filtre['options'];
  $champs_recherche = $filtre['options']['champs'];
  $criteres = '{' . $filtre['filtre'] . ' #ENV{' . $opts['nom'] . '},' . array2spip($champs_recherche) . '}';

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