<form id="filtre_<?php echo $nom_filtre; ?>" class="filtre_multilike" method="get" action="">

  <input type="text" name="<?php echo $nom_input; ?>" value="#ENV{<?php echo $nom_input; ?>}" />
  <input type="submit" value="filtrer" />
</form>

<script type="text/javascript">
  $(function () {
      $('#filtre_<?php echo $nom_filtre; ?>')
          .unbind('submit')
          .submit(function (e) {
              e.preventDefault();
              <?php echo $nom_input; ?> = $(this)
                 .find('input[name="<?php echo $nom_input; ?>"]')
                 .attr('value');
              ajaxReload('listedocuments', {
                  args:{
                      '<?php echo $nom_input; ?>': <?php echo $nom_input; ?>
                  },
                  history: true
              });
          });
  });
</script>
