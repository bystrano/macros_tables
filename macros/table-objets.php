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

  <?php
  /**
   * Insertion des formulaires de filtres
   */
  echo '<div class="filtres">';

  if (is_array($filtres)) {
    foreach ($filtres as $i => $filtre) {

      $contexte = $filtre['options'];
      $contexte['nom_filtre'] = $i;
      $contexte['nom_ajax'] = $nom_ajax;
      echo '#INCLURE{fond=' . recuperer_macro('filtres/' . $filtre['filtre'] . '.html', $contexte) . ', env}';
    }
  }
  echo '</div>';?>

<?php endif;?>

<B_liste_<?php echo $objets; ?>>
<?php if ($pagination): ?>#ANCRE_PAGINATION<?php endif; ?>
<div class="liste-objets <?php echo $objets; ?>">

<table class='spip liste'>
 [<caption><strong class="caption">(#ENV{titre-table})</strong></caption>]
  <?php
  /* On vérifie qu'il y a au moins une entete non-vide. Sinon on zappe
     le thead. */
  $skip_thead = TRUE;
  foreach ($colonnes as $colonne) {
    if (isset($colonne['options']['entete']) AND $colonne['options'] ['entete']) {
      $skip_thead = FALSE;
      break;
    }
  }
  if ( ! $skip_thead ): ?>
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
  <?php endif; ?>
    <tbody>
    <?php
    /**
     * Boucle principale
     */
    ?>
    <BOUCLE_liste_<?php echo $objets; ?>(<?php echo strtoupper($objets); ?>)<?php
    echo macros_tables_calculer_criteres($colonnes, $tri_defaut, $pagination, $criteres_extra, $filtres);
     ?>>
        [(#LANG|changer_typo)]
        <tr class="<?php echo $classe_ligne; ?>[ (#COMPTEUR_BOUCLE|alterner{odd,even})]">
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
  [<p class='<?php ($pagination['ajax'] != 'non') ? "pagination" : ""; ?>'>
     (#PAGINATION{<?php echo $pagination['modele'] ? $pagination['modele'] : 'prive'; ?>})
  </p>]
<?php endif; ?>
</div>
</B_liste_<?php echo $objets; ?>>[
<div class="liste-objets <?php echo $objets; ?> caption-wrap"><strong class="caption">(#ENV*{sinon,''})</strong></div>
]<//B_liste_<?php echo $objets; ?>>
