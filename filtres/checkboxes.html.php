<?php
  $options_saisie = $options_saisies ?: array();
  $options_saisie['datas'] = $datas;
?>

#FORMULAIRE_FILTRE_CHECKBOXES{<?php echo $nom . ',' .  array2spip($options_saisie); ?>}


<?php if ($ajax === 'oui'): ?>
<script type="text/javascript">
  $(function () {
      $('.formulaire_filtre_checkboxes form')
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
              $('.formulaire_filtre_checkboxes form').submit();
          })
          .parents('form').first()
          .find('input[type="submit"]').hide();
    <?php endif; ?>
      ;
  });
</script>
<?php endif; /*endif $ajax */ ?>
