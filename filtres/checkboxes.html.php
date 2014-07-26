<div class="formulaire_spip">
  <form id="filtre_<?php echo $nom_filtre; ?>" class="filtre_checkboxes" method="get" action="">

    <input type="hidden" name="page" value="[(#SELF|parametre_url{page})]" />
    [(#SAISIE{checkbox, <?php echo $nom; ?>, <?php echo "datas=" . array2spip($datas); ?>})]
    <input type="submit" value="filtrer" />

  </form>
</div>

<?php if ($ajax === 'oui'): ?>
<script type="text/javascript">
  $(function () {
      $('#filtre_<?php echo $nom_filtre; ?>')
          .unbind('submit')
          .submit(function (e) {
              e.preventDefault();
              <?php echo $nom; ?> = [];
              $(this)
                 .find('input[name="<?php echo $nom; ?>[]"]')
                 .each(function (i, el) {
                     if ($(el).attr('checked') === 'checked') {
                         <?php echo $nom; ?>.push(el.value);
                     }
                 });
              ajaxReload('<?php echo $nom_ajax; ?>', {
                  args:{
                      '<?php echo $nom; ?>': <?php echo $nom; ?>
                  },
                  history: true
              });
          })
    <?php if ($autosubmit === 'oui'): ?>
          .find('input')
          .change(function () {
              $('#filtre_<?php echo $nom_filtre; ?>').submit();
          })
          .parents('form').first()
          .find('input[type="submit"]').hide();
    <?php endif; ?>
      ;
  });
</script>
<?php endif; /*endif $ajax */ ?>
