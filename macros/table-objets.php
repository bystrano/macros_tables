<?php
/**
 * Initialisation des variables
 */
include_spip('macros_tables_fonctions.php');
$objets = $objet . 's';
?>
<?php if (isset($tri_defaut)): ?>
  [(#SET{defaut_tri,<?php echo array2spip($tri_defaut); ?>})]
<?php endif; ?>
<?php if (isset($filtres)): ?>
  [(#SET{env_filtres,<?php
                    $env_filtres = array();
                    foreach ($filtres as $filtre) {
                      $nom_filtre = $filtre['options']['nom'];
                      $env_filtres[$nom_filtre] = '#ENV{' . $nom_filtre . '}';
                    }
                    echo array2spip($env_filtres);
                    ?>})]
<?php endif;?>
<?php
/**
 * Insertion des formulaires de filtres
 */
if (is_array($filtres)) {
  foreach ($filtres as $i => $filtre) {

    $contexte = $filtre['options'];
    $contexte['nom_filtre'] = $objets . '_' . $i;
    $contexte['objets'] = $objets;
    $contexte['nom_ajax'] = $nom_ajax;
    echo '#INCLURE{fond=' . recuperer_macro('filtres/' . $filtre['filtre'] . '.html', $contexte) . ', env}';
  }
} ?>

<B_liste_<?php echo $objets; ?>>
<?php if ($pagination): ?>#ANCRE_PAGINATION<?php endif; ?>
<div class="liste-objets <?php echo $objets; ?>">

<table class='spip liste'>
 [<caption><strong class="caption">(#ENV{titre-table})</strong></caption>]
	<thead>
		<tr class='first_row'>
      <?php
         /**
          * Insertion des entêtes du tableau
          */
         foreach ($colonnes as $i => $colonne) {
           $colonne['options']['nom_table'] = 'liste_' . $objets;
           echo inclure_macro(
                    'colonnes/' . $colonne['colonne'] . '_entete',
                    $colonne['options']);
         } ?>
		</tr>
	</thead>
	<tbody>
    <?php
    /**
     * Boucle principale
     */
    ?>
    <BOUCLE_liste_<?php echo $objets; ?>(<?php echo strtoupper($objets); ?> <?php echo $objets; ?>_liens)<?php
    echo macros_tables_calculer_criteres($colonnes, $tri_defaut, $pagination, $criteres_extra, $filtres,$env_filtres);
     ?>>
		[(#LANG|changer_typo)]
		<tr class="[(#COMPTEUR_BOUCLE|alterner{odd,even})]">
      <?php
        /**
         * Insertion des cellules
         */
        foreach ($colonnes as $i => $colonne) {
          echo inclure_macro(
                   'colonnes/' . $colonne['colonne'] . '_cellule',
                   $colonne['options']);
        }
      ?>
		</tr>
	</BOUCLE_liste_<?php echo $objets; ?>>
	[(#REM|changer_typo)]
	</tbody>
</table>
<?php if ($pagination): ?>
  [<p class='pagination'>(#PAGINATION{<?php echo $pagination['modele'] ?
                                                    $pagination['modele'] : 'prive';
                                                    ?>})</p>]
<?php endif; ?>
</div>
</B_liste_<?php echo $objets; ?>>[
<div class="liste-objets <?php echo $objets; ?> caption-wrap"><strong class="caption">(#ENV*{sinon,''})</strong></div>
]<//B_liste_<?php echo $objets; ?>>
