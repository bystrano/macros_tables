<?php
$objets = $objet . 's';
?>
[(#SET{defaut_tri,#ARRAY{
<?php
  $defaut_tri = '';
  foreach ($tri_defaut as $col => $sens_tri) {
    $defaut_tri .= $col . ',' . $sens_tri . ',';
  }
  /* on retire la dernière virgule */
  echo substr($defaut_tri, 0, -1);
?>
}})
]<B_liste_<?php echo $objets; ?>>
<?php if ($pagination): ?>#ANCRE_PAGINATION<?php endif; ?>
<div class="liste-objets <?php echo $objets; ?>">

<table class='spip liste'>
<caption><strong class="caption">#ENV{titre-table}</strong></caption>
	<thead>
		<tr class='first_row'>
      <?php
         foreach ($colonnes as $i => $colonne) {
           $colonne['options']['nom_table'] = $objets;
           echo recuperer_fond(
                    'colonnes/' . $colonne['colonne'] . '_entete',
                    $colonne['options']);
         } ?>
		</tr>
	</thead>
	<tbody>
    <BOUCLE_liste_<?php echo $objets; ?>(<?php echo strtoupper($objets); ?> <?php echo $objets; ?>_liens)<?php
    echo macrotable_calculer_criteres($colonnes, $tri_defaut, $pagination, $criteres_extra);
     ?>>
		[(#LANG|changer_typo)]
		<tr class="[(#COMPTEUR_BOUCLE|alterner{odd,even})]">
      <?php foreach ($colonnes as $i => $colonne) {
           echo recuperer_fond(
                    'colonnes/' . $colonne['colonne'] . '_cellule',
                    $colonne['options']);
      } ?>
		</tr>
	</BOUCLE_liste_<?php echo $objets; ?>>
	[(#REM|changer_typo)]
	</tbody>
</table>
<?php if ($pagination): ?>
  [<p class='pagination'>(#PAGINATION{<?php echo $pagination['style'] ?
                                                    $pagination['style'] : 'prive';
                                                    ?>})</p>]
<?php endif; ?>
</div>
</B_liste_<?php echo $objets; ?>>[
<div class="liste-objets <?php echo $objets; ?> caption-wrap"><strong class="caption">(#ENV*{sinon,''})</strong></div>
]<//B_liste_<?php echo $objets; ?>>
