<form id="filtre_<?php echo $nom_filtre; ?>" class="filtre_multilike" method="get" action="">

  <input type="hidden" name="page" value="[(#SELF|parametre_url{page})]" />
  <input type="text" name="<?php echo $nom; ?>" value="#ENV{<?php echo $nom; ?>}" />
  <input type="submit" value="filtrer" />

</form>

<script type="text/javascript">
  $(function () {
      $('#filtre_<?php echo $nom_filtre; ?>')
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
<?php if ($autosubmit == 'oui'): ?>
          .find('input')
          .keyup(function () {
              if (window.multilike_timeout) {
                  window.clearTimeout(multilike_timeout);
              }
              multilike_timeout = window.setTimeout(function () {
                  $('#filtre_<?php echo $nom_filtre; ?>').submit();
              }, 500);
          })
<?php endif; ?>
          ;
  });
</script>
