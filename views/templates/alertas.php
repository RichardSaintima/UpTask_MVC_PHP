<?php
    foreach($alertas as $key => $alerta ) :
        foreach($alerta as $mensaje ) :
?>
    <div class="alertas <?php echo $key; ?>"><?php echo $mensaje ?></div>

<?php 
    endforeach;
    endforeach;
 ?>