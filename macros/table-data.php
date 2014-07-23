<?php
include_spip('macros_tables_fonctions.php');
?>
<?php if (isset($tri_defaut)): ?>
  [(#SET{defaut_tri,<?php echo array2spip($tri_defaut); ?>})]
<?php endif; ?>
<?php if (is_array($filtres)): ?>
  [(#SET{champs_recherche,<?php
    /* FIXME Ne supporte qu'un seul filtre pour l'instant FIXME */
    echo array2spip($filtres[0]['options']['champs']); ?>})]
<?php endif; ?>
<?php
if (is_array($filtres)) {
  foreach ($filtres as $i => $filtre) {

    $contexte = $filtre['options'];
    $contexte['nom_filtre'] = $i;
    $contexte['nom_ajax'] = $nom_ajax;
    echo '#INCLURE{fond=' . recuperer_macro('filtres/' . $filtre['filtre'] . '.html', $contexte) . ', env}';
  }
} ?>

<B_data>
<?php if ($pagination): ?>#ANCRE_PAGINATION<?php endif; ?>
<div class="liste-objets">

<table class='spip liste'>
[<caption><strong class="caption">(#ENV{titre-table})</strong></caption>]
	<thead>
		<tr class='first_row'>
      <?php
         foreach ($colonnes as $i => $colonne) {
           $colonne['options']['nom_table'] = 'data';
           echo inclure_macro(
                    'colonnes/' . $colonne['colonne'] . '_entete',
                    $colonne['options']);
         } ?>
		</tr>
	</thead>
	<tbody>
    <BOUCLE_data(DATA){source tableau, #ENV{donnees}|macros_tables_filtres{#ENV{<?php echo $filtres[0]['options']['nom_input']; ?>}, <?php echo array2spip($filtres); ?>}}<?php
      echo macros_tables_calculer_criteres($colonnes, $tri_defaut, $pagination, $criteres_extra);
     ?>>
		[(#LANG|changer_typo)]
		<tr class="[(#COMPTEUR_BOUCLE|alterner{odd,even})]">
      <?php foreach ($colonnes as $i => $colonne) {
           echo inclure_macro(
                    'colonnes/' . $colonne['colonne'] . '_cellule',
                    $colonne['options']);
      } ?>
		</tr>
	</BOUCLE_data>
	[(#REM|changer_typo)]
	</tbody>
</table>
<?php if ($pagination): ?>
  [<p class='pagination'>(#PAGINATION{<?php echo $pagination['style'] ?
                                                    $pagination['style'] : 'prive';
                                                    ?>})</p>]
<?php endif; ?>
</div>
</B_data>[
<div class="liste-objets caption-wrap"><strong class="caption">(#ENV*{sinon,''})</strong></div>
]<//B_data>
