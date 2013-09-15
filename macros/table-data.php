<?php
include_spip('macrotable_fonctions.php');
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
    $contexte['nom_filtre'] = $objets . '_' . $i;
    $contexte['objets'] = $objets;
    echo '#INCLURE{fond=' . recuperer_macro('filtres/' . $filtre['filtre'] . '.html', $contexte) . ', env}';
  }
} ?>

<B_data>
<?php if ($pagination): ?>#ANCRE_PAGINATION<?php endif; ?>
<div class="liste-objets">

<table class='spip liste'>
<caption><strong class="caption">#ENV{titre-table}</strong></caption>
	<thead>
		<tr class='first_row'>
      <?php
         foreach ($colonnes as $i => $colonne) {
           $colonne['options']['nom_table'] = $objets;
           echo inclure_macro(
                    'colonnes/' . $colonne['colonne'] . '_entete',
                    $colonne['options']);
         } ?>
		</tr>
	</thead>
	<tbody>
    <BOUCLE_data(DATA){source tableau, #ENV{donnees}}<?php
      echo macrotable_calculer_criteres($colonnes, $tri_defaut, $pagination, $criteres_extra, $filtres);
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
