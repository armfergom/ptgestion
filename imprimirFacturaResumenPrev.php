<?php
	
	include_once('inc/header.php');
	
	if (isset($_REQUEST['venta']))
		$param = 'venta='.$_REQUEST['venta'];
	else if (isset($_REQUEST['presupuesto']))
		$param = 'presupuesto='.$_REQUEST['presupuesto'];
	
	echo '<p class=parrafoCentrado><b>Por favor introduzca el comentario de la factura resumen:</b></p>';
	
	echo '<form id="f1" name="f1" method="post" target="_new" action="imprimirFacturaResumen.php?'.$param.'">
		  <p class="centrado">
		  <label for="comentario"><b>Comentario:</b></label><br />
		  <textarea name="comentario" rows="5" cols="40"></textarea><br />
		  <input name="electronica" type="submit" value="Electronica" class="boton"/>
		  <input name="normal" type="submit" value="Normal" class="boton"/>
		  </p>
		  </form>';
	  
	include_once('inc/footer.php');

?>