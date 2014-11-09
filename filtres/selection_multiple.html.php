<?php
  $options_saisie = $options_saisie ?: array();
  $options_saisie['datas'] = $datas;
?>

#FORMULAIRE_FILTRE_SELECTION_MULTIPLE{<?php echo $nom . ',' .  array2spip($options_saisie); ?>}


<?php if ($ajax === 'oui'): ?>
<script type="text/javascript">
  $(function () {
      $('.formulaire_filtre_selection_multiple form')
          .unbind('submit')
          .submit(function (e) {
              e.preventDefault();
              <?php echo $nom; ?> = [];
              $(this)
                 .find('option')
                 .each(function (i, el) {
                     if ($(el).attr('selected') === 'selected') {
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
          .find('select')
          .change(function () {
              $('.formulaire_filtre_selection_multiple form').submit();
          })
          .parents('form').first()
          .find('input[type="submit"]').hide();
    <?php endif; ?>
      ;
  });
</script>
<?php endif; /*endif $ajax */ ?>
