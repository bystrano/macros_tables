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
]<B_liste_objets>
#ANCRE_PAGINATION
<div class="liste-objets <?php echo $objets; ?>">

<pre>
  <?php echo macrotable_calculer_criteres($colonnes, $tri_defaut); ?>
</pre>

<table class='spip liste'>
<caption><strong class="caption">#ENV{titre-table}</strong></caption>
	<thead>
		<tr class='first_row'>
      <?php
         foreach ($colonnes as $i => $colonne) {
           echo recuperer_fond(
                    'colonnes/' . $colonne['colonne'] . '_entete',
                    $colonne['options']);
         } ?>
		</tr>
	</thead>
	<tbody>
    <BOUCLE_liste_objets(<?php echo strtoupper($objets); ?> <?php echo $objets; ?>_liens)<?php
    echo macrotable_calculer_criteres($colonnes, $tri_defaut);
     ?>{pagination #ENV{nb,10}}{!lang_select}{tout}>
		[(#LANG|changer_typo)]
		<tr class="[(#COMPTEUR_BOUCLE|alterner{odd,even})]">
      <?php foreach ($colonnes as $i => $colonne) {
           echo recuperer_fond(
                    'colonnes/' . $colonne['colonne'] . '_cellule',
                    $colonne['options']);
      } ?>
		</tr>
	</BOUCLE_liste_objets>
	[(#REM|changer_typo)]
	</tbody>
</table>
[<p class='pagination'>(#PAGINATION{#ENV{pagination,prive}})</p>]
</div>
</B_liste_objets>[
<div class="liste-objets <?php echo $objets; ?> caption-wrap"><strong class="caption">(#ENV*{sinon,''})</strong></div>
]<//B_liste_objets>
