<th class='<?php echo $nom; ?>' scope='col'>
   <?php
   if (( ! isset($tri_defaut)) OR ( ! $tri)) :
     echo $entete;
   else: ?>
     [(#ENV{tri_<?php echo $nom_table; ?>}|=={<?php echo $nom; ?>}|oui)
       [(#ENV{sens_<?php echo $nom_table; ?>}|=={-1}|?{
         <span class="croissant">
           [(#TRI{><?php echo ($entete ? ','.$entete : ',\'\'') .
                               ($ajax ? ',ajax' : '');?>})]
         </span>,
         <span class="decroissant">
           [(#TRI{<<?php echo ($entete ? ','.$entete : ',\'\'') .
                               ($ajax ? ',ajax' : ''); ?>})]
         </span>
       })]
     ]
     [(#ENV{tri_<?php echo $nom_table; ?>}|=={<?php echo $nom; ?>}|non)
       [(#TRI{<?php echo $nom; ?><?php echo ($entete ? ','.$entete : ',\'\'') . ($ajax ? ',ajax' : '');?>})]
     ]<?php
   endif; ?>
</th>
