#FORMULAIRE_FILTRE_MULTILIKE{<?php echo $nom . ',' .  array2spip($options_saisie); ?>}


<?php if ($ajax === 'oui'): ?>
<script type="text/javascript">
  $(function () {
      $('.formulaire_filtre_multilike form')
          .unbind('submit')
          .submit(function (e) {
              e.preventDefault();
              <?php echo $nom; ?> = $(this)
                 .find('input[name="<?php echo $nom; ?>"]')
                 .attr('value');
              ajaxReload('<?php echo $nom_ajax; ?>', {
                  args:{
                      '<?php echo $nom; ?>': <?php echo $nom; ?>
                  },
                  history: true
              });
          })
    <?php if ($autosubmit === 'oui'): ?>
          .find('input')
          .keyup(function () {
              if (window.multilike_timeout) {
                  window.clearTimeout(multilike_timeout);
              }
              multilike_timeout = window.setTimeout(function () {
                  $('.formulaire_filtre_multilike form').submit();
              }, 500);
          })
    <?php endif; ?>
          ;
  });
</script>
<?php endif; /*endif $ajax */ ?>
