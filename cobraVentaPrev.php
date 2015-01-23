<?php
	
	include_once('inc/header.php');
	
	$IdVenta = $_REQUEST['venta'];
	$fuente = $_REQUEST['fuente'];
	
	echo '<p class=parrafoCentrado><b>Por favor seleccione la forma de pago:</b></p>';
	
	echo '<form id="f1" name="f1" method="post" action="cobraVenta.php?venta='.$IdVenta.'&fuente='.$fuente.'"><table class="tabla-centrada"><tr><td class="alineado-izquierda"><label for="forma"><b>Forma de pago</b></label></td><td><select name="forma">';
	echo '<option value="Visa">Visa</option>
		<option value="Efectivo">Efectivo</option>
		<option value="Transferencia">Transferencia</option>
		<option value="Talón">Talón</option>
		<option value="De su lista de boda">De su lista de boda</option>';

	

	echo '</select></td></tr><tr><td></td><td class="alineado-derecha"><input name="sigPaso" type="submit" value="Cobrar" class="boton" /></td></tr></table>';
		echo '</form>';
	include_once('inc/footer.php');

?>