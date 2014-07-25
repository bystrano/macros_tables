<?php
/**
 * Fonctions implémentant le filtre checkboxes
 *
 * @plugin     Macro Table
 * @copyright  2013
 * @author     Michel Bystranowski
 * @licence    GNU/GPL
 * @package    SPIP\Macros_Tables\Filtres\Checkboxes
 */

if (!defined('_ECRIRE_INC_VERSION')) return;


/**
 * Implémente la fonction calculer_criteres_objet de l'API filtres pour le filtre checkboxes
 *
 * @param array $filtre
 *      Un tableau représentant une instance de filtre, de la forme :
 *      array(
 *          'filtre'  => 'multilike',
 *          'options' => array(…les options…),
 *      );
 *
 * @return string
 *     Une chaîne de caractères représentant un ou plusieurs
 *     critères. Sera inséré directement dans la boucle.
 */
function filtres_checkboxes_calculer_criteres_objet_dist ($filtre) {

    $criteres = "";

    return $criteres;
}

/**
 * Implémente la fonction filter_data de l'API filtres pour le filtre checkboxes
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
function filtres_checkboxes_filtrer_data_dist ($filtre, $recherche, $ligne) {

    return TRUE;
}