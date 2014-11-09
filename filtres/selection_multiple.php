<?php
/**
 * Fonctions implémentant le filtre selection_multiple
 *
 * @plugin     Macro Table
 * @copyright  2013
 * @author     Michel Bystranowski
 * @licence    GNU/GPL
 * @package    SPIP\Macros_Tables\Filtres\Selection_Multiple
 */

if (!defined('_ECRIRE_INC_VERSION')) return;


/**
 * Implémente la fonction calculer_criteres_objet de l'API filtres pour le filtre selection_multiple
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
function filtres_selection_multiple_calculer_criteres_objet_dist ($filtre) {

    if ($filtre['options']['force'] !== 'oui') {
        erreur_squelette("Le filtre checkbox ne peut pas être utilisé avec la macro table-objet");
    }

    return "";
}

/**
 * Implémente la fonction filter_data de l'API filtres pour le filtre selection_multiple
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
function filtres_selection_multiple_filtrer_data_dist ($filtre, $recherche, $ligne) {

    $nom_champ = $filtre['options']['champs'];
    $champ = explode(',', $ligne[$nom_champ]);

    // si pas de case cochée ou pas de champs à filtrer, pas de filtre
    if (is_null($recherche) OR is_null($nom_champ)) return TRUE;

    foreach ($recherche as $case) {
        foreach ($champ as $valeur) {
            if ($valeur == $case)
                return TRUE;
        }
    }

    return FALSE;
}