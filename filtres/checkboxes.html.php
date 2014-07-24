<div class="formulaire_spip">
  <form id="filtre_<?php echo $nom_filtre; ?>" class="filtre_checkboxes" method="get" action="">

    <input type="hidden" name="page" value="[(#SELF|parametre_url{page})]" />
    [(#SAISIE{checkbox, <?php echo $nom; ?>, <?php echo "datas=" . array2spip($datas); ?>})]
    <input type="submit" value="filtrer" />

  </form>
</div>
